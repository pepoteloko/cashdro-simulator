<?php

namespace App;

class SQLiteCreateTable
{
    /**
     * PDO object
     *
     * @var \PDO
     */
    private $pdo;

    /**
     * connect to the SQLite database
     */
    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * create tables
     */
    public function createTables()
    {
        $commands = [
            '
            CREATE TABLE IF NOT EXISTS operations (
                operation_id INTEGER,
                pos_id       INTEGER,
                pos_user     VARCHAR NOT NULL,
                amount       DECIMAL(4,2) DEFAULT 0.00,
                status       CHARACTER(1) DEFAULT "C"
            )
            ',
        ];
        // execute the sql commands to create new tables
        foreach ($commands as $command) {
            $this->pdo->exec($command);
        }
    }

    /**
     * get the table list in the database
     */
    public function getTableList(): array
    {
        $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type = 'table' ORDER BY name");
        $tables = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $tables[] = $row['name'];
        }

        return $tables;
    }
}