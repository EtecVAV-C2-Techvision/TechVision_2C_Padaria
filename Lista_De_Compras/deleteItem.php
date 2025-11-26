<?php
require "proteger.php";

header("Content-Type: text/plain; charset=utf-8");

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) {
    echo "ERRO_CON";
    exit;
}

$idItem = $_GET['idItem'] ?? "";

if ($idItem == "") {
    echo "CAMPOS_VAZIOS";
    exit;
}

$idItem = intval($idItem);

$sql = "DELETE FROM tbcompras WHERE idItem = $idItem";

if ($conn->query($sql) === TRUE) {
    echo "OK";
} else {
    echo "ERRO";
}

$conn->close();
?>
