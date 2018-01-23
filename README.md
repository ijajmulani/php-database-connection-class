# php-database-connection-class

To make it work 

<?php
include "DB.php";

$db = DB::getInstance('host', 'username', 'password', 'databasename');
$query = "select * from users where id = ?";
$data = ['11'];
$stmt = $db->executeQuery($query, $data);
var_dump($stmt->get_result()->fetch_assoc());
