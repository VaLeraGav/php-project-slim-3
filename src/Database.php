<?php

namespace App;

use Carbon\Carbon;
use PDO;

class Database
{
    public PDO $db;

    public function __construct()
    {
        try {

            $pdo = new PDO("sqlite:../identifier.sqlite");

            $this->db = $pdo;
        } catch (\PDOException $e) {
            echo $e->getMessage();
        }
    }

    public function query(string $sql, $params = [])
    {
        $connect = $this->db->query($sql);
        $result = $connect->execute($params);
        if (!$result) {
            throw new \Exception('не верный запрос');
        }
        $matches = $connect->fetchAll(PDO::FETCH_ASSOC);
        if ($matches === false) {
            throw new \Exception('Expect array, boolean given');
        }
        return $matches;
    }

    public function isUrlUnique($urlName)
    {
        $sql = 'SELECT id,name FROM urls WHERE name = :urlName';
        return $this->query($sql, [':urlName' => $urlName])[0];
    }

    public function save(string $urlName)
    {
        $createdAt = Carbon::now()->toDateTimeString();
        $sql = 'INSERT INTO urls (name, created_at) VALUES (:name, :createdAt)';
        $params = [
            ':name' => $urlName,
            ':createdAt' => $createdAt
        ];
        $this->query($sql, $params);
    }

}
