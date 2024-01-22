<?php
$servername = "bgceftiqs9aiirhidsog-mysql.services.clever-cloud.com";
$username = "u9hwqdxokergb05c";
$password = "Jb8b0WfUyRZdZBddii2f";
$dbname = "bgceftiqs9aiirhidsog";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexion fallida: " . $conn->connect_error);
}
?>