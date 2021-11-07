<?php

namespace App;

use PDO;

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
     * @return int
     */
    public function acknowledgeOperation(Request $request): int
    {
        // TODO: Buscar en la base de datos
        // SELECT * FROM operations WHERE id = $request->getOperation() AND pos_id = $request->getPosId()

        // TODO: Devolver el estado
        // 0: OK
        // 1: No existe

        return 0;
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
}