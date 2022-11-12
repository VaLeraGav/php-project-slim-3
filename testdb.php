<?php


$pdo = new PDO("sqlite:identifier.sqlite");

$statement = $pdo->query("SELECT * FROM  urls");
$rows = $statement->fetchAll(PDO:: FETCH_ASSOC);

print_r($rows);

