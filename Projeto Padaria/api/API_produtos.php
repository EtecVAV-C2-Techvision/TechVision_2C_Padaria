<?php
ob_clean();
header('Content-Type: application/json; charset=utf-8');
include("../conexao.php");

$token = $_GET["token"] ?? "";
if ($token == "") {     echo "NEGADO"; exit; }

$check = $conn->prepare("SELECT idCli FROM clientes WHERE token=?");
$check->bind_param("s", $token);
$check->execute();
$res = $check->get_result();
if ($res->num_rows == 0) { echo "NEGADO"; exit; }

$q = $conn->query("SELECT * FROM produtos WHERE quantidade > 0 ORDER BY nome ASC");

$lista = [];
while ($r = $q->fetch_assoc()) $lista[] = $r;

echo json_encode($lista);
?>
