<?php

$host = "127.0.0.1";
$user = "root";
$password = "";
$database = "campusconnect";
$port = 3307;

$conn = mysqli_connect(
    $host,
    $user,
    $password,
    $database,
    $port
);

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}



?>