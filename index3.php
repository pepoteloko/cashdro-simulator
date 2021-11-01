<?php

// Cabeceras CORS
header('Content-type:application/json;charset=utf-8');
header('Access-Control-Allow-Origin: *');
header(
    "Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method"
);
header("Access-Control-Allow-Methods: GET");
header("Allow: GET");

/// Parámetros
$operation = $_GET["operation"] ?? '';
$operationId = $_GET["operationId"] ?? '';
$name = $_GET["name"] ?? '';
$password = $_GET["password"] ?? '';
$type = $_GET["type"] ?? '';
$posid = $_GET["posid"] ?? '';
$posuser = $_GET["posuser"] ?? '';
$parameters = $_GET["parameters"] ?? '';

$response = ["code" => 0, "response" => []];
if ($name != 'admin' || $password != 'password') {
    $response["code"] = -1;
    $response["response"]["errorMessage"] = "Authentication Failed";
}

switch ($operation) {
    /**
     * Esta llamada crea una operationId
     */
    case "startOperation":
        $response["code"] = 1;
        $response["response"]["errorMessage"] = "none";
        $response["response"]["operation"] = [
            "operationId" => 1234
        ];
        break;

    /**
     * Esta es la segunda llamada del proceso: Empieza el pago en la máquina?
     */
    case "acknowledgeOperationId":
        if ($operationId != 1234) {
            $response["code"] = -2;
            $response["response"]["errorMessage"] = "Operation not found";
        } else {
            $response["code"] = 1;
            $response["response"]["errorMessage"] = "none";
        }
        break;

    /**
     * Pregunta por el estado del pago a la máquina
     */
    case "askOperation":
        if ($operationId != 1234) {
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
    case "finsihOperation":
        if ($operationId != 1234) {
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
        if ($operationId != 1234) {
            $response["code"] = -2;
            $response["response"]["errorMessage"] = "Operation not found";
        } else {
            $response["code"] = 1;
            $response["response"]["errorMessage"] = "none";
        }
        break;
}

echo json_encode($response);

