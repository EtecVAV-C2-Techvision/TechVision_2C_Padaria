<?php
header("Content-Type: application/json");
include("../conexao.php");

$token = $_GET["token"] ?? "";

$check = $conn->prepare("SELECT idCli FROM clientes WHERE token=?");
$check->bind_param("s",$token);
$check->execute();
$res = $check->get_result();
if ($res->num_rows == 0) { echo json_encode([]); exit; }

$idCli = $res->fetch_assoc()["idCli"];

$q = $conn->query("SELECT * FROM pedidos WHERE idCli=$idCli ORDER BY data_pedido DESC");

$dados = [];
while ($r = $q->fetch_assoc()) $dados[] = $r;

echo json_encode($dados);
?>
