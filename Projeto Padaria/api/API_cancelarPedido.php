<?php
header("Content-Type: text/plain");
include("../conexao.php");

$token = $_GET["token"] ?? "";
$idPedido = $_GET["idPedido"] ?? 0;

// valida token
$check = $conn->prepare("SELECT idCli FROM clientes WHERE token=?");
$check->bind_param("s",$token);
$check->execute();
$res = $check->get_result();
if ($res->num_rows == 0) { echo json_encode(["status"=>"NEGADO"]); exit; }

$idCli = $res->fetch_assoc()["idCli"];

// atualiza status
$sql = $conn->prepare("UPDATE pedidos SET status='Cancelado' WHERE idPedido=? AND idCli=?");
$sql->bind_param("ii",$idPedido,$idCli);
$sql->execute();

if ($sql->affected_rows > 0) echo "OK";
else echo "ERRO";
?>
