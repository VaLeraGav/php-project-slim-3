<?php

namespace App;

use PDO;

class Database
{
    public PDO $dbh;

    // jdbc:postgresql://localhost:5432/postgres

    public function __construct()
    {
        $dbUrl = getenv('DATABASE_URL');
        if ($dbUrl) {
            throw new \Exception('error: DATABASE_URL');
        }
    }
}
