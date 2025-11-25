<?php 
require "proteger.php"; 

header("Content-Type: application/json; charset=utf-8");

$conn = new mysqli("localhost", "root", "", "dbcompras");
if ($conn->connect_error) { 
    die(json_encode(["erro" => "ERRO_CON"]));
}

$sql = "SELECT * FROM tbcategorias ORDER BY nomeCat ASC";
$result = $conn->query($sql);

$lista = array();
while ($row = $result->fetch_assoc()) {
    $lista[] = $row;
}

echo json_encode($lista);

$conn->close();
?>
