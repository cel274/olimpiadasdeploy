<?php
$host = "nozomi.proxy.rlwy.net";
$user = "root";
$pass = "zjOHqXFxmUwzcwlpFabmSeHzyxZkhZIb";
$db   = "agvi"; 
$port = "16460";

$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Error de conexión: " . $conn->connect_error);
}
?>
