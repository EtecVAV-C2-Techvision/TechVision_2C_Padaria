<?php
require "proteger.php";

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) {
    die("Falha de conexão: " . $conn->connect_error);
}

$idItem = intval($_GET['idItem']);
$nome = $_GET['nome'];
$quantidade = $_GET['quantidade'];

$sql = "UPDATE tbcompras 
        SET nome = '$nome', quantidade = '$quantidade'
        WHERE idItem = $idItem";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    echo "ERRO";
}

$conn->close();
?>
