<?php

require 'vendor/autoload.php';

use App\Config;
use App\OperationsHandler;
use App\Request;
use App\SQLiteConnection;
use App\SQLiteCreateTable;

// Cabeceras CORS
header('Content-type:application/json;charset=utf-8');
header('Access-Control-Allow-Origin: *');
header(
    "Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method"
);
header("Access-Control-Allow-Methods: GET");
header("Allow: GET");

/// Parámetros
$request = new Request(
    [
        'operation' => $_GET["operation"] ?? '',
        'operationId' => $_GET["operationId"] ?? '',
        'name' => $_GET["name"] ?? '',
        'password' => $_GET["password"] ?? '',
        'amount' => $_GET["amount"] ?? 0,
        'type' => $_GET["type"] ?? '',
        'posid' => $_GET["posid"] ?? 0,
        'posuser' => $_GET["posuser"] ?? 'unknown',
        'parameters' => $_GET["parameters"] ?? '',
    ]
);

$response = ["code" => 0, "response" => ["errorMessage" => ""]];
if (!$request->validateUserPass()) {
    $response["code"] = -1;
    $response["response"]["errorMessage"] = "Authentication Failed";
}

/// Base de datos
$pdo = (new SQLiteConnection())->connect();
try {
    $pdo = new PDO("sqlite:".Config::PATH_TO_SQLITE_FILE);
} catch (PDOException $e) {
    $response["code"] = -1;
    $response["response"]["errorMessage"] = "Simulator Error: SQLite error";
    echo json_encode($response);
    die;
}
$prepareDatabase = new SQLiteCreateTable($pdo);
$prepareDatabase->createTables();

$operationHandler = new OperationsHandler($pdo);

switch ($request->getOperation()) {
    /**
     * Esta llamada crea una operationId
     */
    case "startOperation":
        $response["code"] = 1;
        $response["response"]["errorMessage"] = "none";
        $response["response"]["operation"] = [
            "operationId" => $operationHandler->createNewOperation($request)
        ];
        break;

    /**
     * Esta es la segunda llamada del proceso: Empieza el pago en la máquina?
     */
    case "acknowledgeOperationId":
        $status = $operationHandler->acknowledgeOperation($request);

        if ($status == 0) {
            $response["code"] = 1;
            $response["response"]["errorMessage"] = "none";
        } else {
            $response["code"] = -2;
            $response["response"]["errorMessage"] = "Operation not found";
        }
        break;

    /**
     * Pregunta por el estado del pago a la máquina
     */
    case "askOperation":
        if ($request->getOperationId() != 1234) {
            $response["code"] = -2;
            $response["response"]["errorMessage"] = "Operation not found";
        } else {
            $response["code"] = 1;
            $response["response"]["errorMessage"] = "none";
            $response["response"]["operation"]["operation"] = [
                "operationId" => 1234,
                "state" => "F",
            ];
            $response["response"]["operation"]["devices"] = [
                "type" => "1",
                "state" => "3",
                "totalin" => "0",
                "totalout" => "0",
                "pieces" => []
            ];
            $response["response"]["operation"]["messages"] = [];
            $response["response"]["operation"]["messagesFixed"] = [];
            $response["response"]["operation"]["withError"] = "false";
            $response["response"]["operation"]["withErrorMRX"] = "false";
        }
        break;

    /**
     * Finaliza la operacion
     */
    case "finishOperation":
        if ($request->getOperationId() != 1234) {
            $response["code"] = -2;
            $response["response"]["errorMessage"] = "Operation not in execution";
        } else {
            $response["code"] = 1;
            $response["response"]["errorMessage"] = "none";
        }
        break;

    /**
     * Indica a la máquina que el TPV ha marcado el pago como realizado
     */
    case "setOperationImported":
        if ($request->getOperationId() != 1234) {
            $response["code"] = -2;
            $response["response"]["errorMessage"] = "Operation not found";
        } else {
            $response["code"] = 1;
            $response["response"]["errorMessage"] = "none";
        }
        break;
}

echo json_encode($response);

