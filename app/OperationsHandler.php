<?php

namespace App;

use PDO;
use phpDocumentor\Reflection\Types\Integer;

class OperationsHandler
{
    /**
     * PDO object
     *
     * @var PDO
     */
    private $pdo;

    /**
     * connect to the SQLite database
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * @param  Request  $request
     *
     * @return int
     */
    public function createNewOperation(Request $request): int
    {
        $maxId = $this->getNewOperationId($request->getPosId());

        $this->insertOperation($maxId, $request->getPosId(), $request->getPosUser(), $request->getAmount());

        return $maxId;
    }

    /**
     * @param  Request  $request
     *
     * @return bool
     */
    public function acknowledgeOperation(Request $request): bool
    {
        // Buscar en la base de datos
        $query = "
            SELECT *
            FROM operations
            WHERE pos_id = :posId
            AND operation_id = :operationId
        ";
        $sth = $this->pdo->prepare($query);
        $sth->execute([':posId' => $request->getPosId(), ':operationId' => $request->getOperationId()]);
        $result = $sth->fetchAll();

        if (count($result) == 1) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param  Request  $request
     *
     * @return string
     */
    public function askOperation(Request $request): string
    {
        // Buscar en la base de datos
        $result = $this->loadOperation($request);

        if ($result == null) {
            return "not-found";
        }

        if ($result['status'] == 'C') {
            // Lo ponemos en estado "P" de preguntado, así la siguiente vez estará pagado
            $this->changeStatus($request->getOperationId(), $request->getPosId(), "P");

            return "P";
        } else {
            return "F";
        }

        return "F";
    }

    public function finishOperation(Request $request)
    {
        // Buscar en la base de datos
        $result = $this->loadOperation($request);

        if ($result == null) {
            return "not-found";
        }

        // Lo ponemos en estado "T" de terminado
        $this->changeStatus($request->getOperationId(), $request->getPosId(), "T");

        return true;
    }

    public function importedOperation(Request $request)
    {
        // Buscar en la base de datos
        $result = $this->loadOperation($request);

        if ($result == null) {
            return "not-found";
        }

        // Lo ponemos en estado "I" de importado
        $this->changeStatus($request->getOperationId(), $request->getPosId(), "I");

        return true;
    }

    /**
     * @param  int  $maxId
     * @param  int  $posId
     * @param  string  $posUser
     * @param  float  $amount
     */
    private function insertOperation(int $maxId, int $posId, string $posUser, float $amount): void
    {
        $query = "
            INSERT INTO operations (operation_id, pos_id, pos_user, amount, status)
            VALUES(:maxId, :posId, :posUser, :amount, 'C')
        ";
        $sth = $this->pdo->prepare($query);
        $sth->execute(
            [
                ':maxId' => $maxId,
                ':posId' => $posId,
                ':posUser' => $posUser,
                ':amount' => $amount
            ]
        );
    }

    /**
     * @param  int  $posId
     *
     * @return int|mixed
     */
    private function getNewOperationId(int $posId)
    {
        $query = "
            SELECT max(operation_id) AS operationId
            FROM operations
            WHERE pos_id = :posId
        ";
        $sth = $this->pdo->prepare($query);
        $sth->execute([':posId' => $posId]);
        $result = $sth->fetchAll();

        $maxId = $result[0]["operationId"];
        if ($maxId == null) {
            $maxId = 0;
        }
        return $maxId + 1;
    }

    private function changeStatus(int $operationId, $posId, string $status)
    {
        $query = "
                UPDATE operations SET status = :status
                WHERE pos_id = :posId
                AND operation_id = :operationId
            ";
        $sth = $this->pdo->prepare($query);
        $sth->execute([':posId' => $posId, ':operationId' => $operationId, ':status' => $status]);
    }

    /**
     * @param  Request  $request
     *
     * @return array|false
     */
    public function loadOperation(Request $request)
    {
        $query = "
            SELECT *
            FROM operations
            WHERE pos_id = :posId
            AND operation_id = :operationId
        ";
        $sth = $this->pdo->prepare($query);
        $sth->execute([':posId' => $request->getPosId(), ':operationId' => $request->getOperationId()]);
        $result = $sth->fetchAll();

        if (count($result) == 0) {
            return null;
        } else {
            return $result[0];
        }
    }
}