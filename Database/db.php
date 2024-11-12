<?php
//host
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "human resource management system";

$connection = new mysqli($servername, $username, $password, $dbname);

if ($connection->connect_error) {
    die("connection failed: " . $connection->connect_error);
}
