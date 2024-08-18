<?php
$host = 'localhost';
$dbname = 'grading_sys';
$user = 'admin';
$pass = 'admin';


$connection = new mysqli($host, $user, $pass, $dbname);
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}



?>