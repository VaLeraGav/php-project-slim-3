<?php

namespace App;

use PDO;

class Database
{
    public PDO $dbh;

    // jdbc:postgresql://localhost:5432/postgres

    public function __construct()
    {
        try {
            $pdo = new PDO("sqlite:identifier.sqlite");

            if ($pdo) {
                echo "Connected to the $db database successfully!";
            }
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }

    }
}
