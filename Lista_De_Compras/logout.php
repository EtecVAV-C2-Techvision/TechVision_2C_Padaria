<?php
header("Content-Type: text/plain; charset=utf-8");

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) { 
    echo "ERRO_CON";
    exit;
}

$token = $_GET["token"] ?? "";

if ($token == "") {
    echo "NEGADO";
    exit;
}

// Remove token do usuário
$sql = "UPDATE usuarios SET token='' WHERE token='$token'";
$conn->query($sql);

echo "DESLOGADO";

$conn->close();
?>
