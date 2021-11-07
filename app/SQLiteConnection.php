<?php

namespace App;

use PDO;

class SQLiteConnection
{
    /**
     * PDO instance
     *
     * @var type
     */
    private $pdo;

    /**
     * return in instance of the PDO object that connects to the SQLite database
     *
     * @return \PDO
     */
    public function connect()
    {
        if ($this->pdo == null) {
            $this->pdo = new PDO("sqlite:".Config::PATH_TO_SQLITE_FILE);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        return $this->pdo;
    }
}