<?php
header("Content-Type: text/plain; charset=utf-8");

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) { 
    echo "NEGADO";
    exit;
}

$token = $_GET["token"] ?? "";

if ($token == "") {
    echo "NEGADO";
    exit;
}

$sql = "SELECT id FROM usuarios WHERE token='$token' LIMIT 1";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "NEGADO";
    exit;
}
?>
