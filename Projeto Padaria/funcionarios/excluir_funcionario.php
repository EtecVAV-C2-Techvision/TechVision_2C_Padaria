<?php
include "proteger.php";

if (trim(strtolower($_SESSION['funcao'])) !== 'gerente') {
    die("Acesso negado.");
}

include('../conexao.php');

$id = intval($_GET['id'] ?? 0);

// Só exclui se não for gerente
$sql = "DELETE FROM funcionarios WHERE idFunc = $id AND funcao <> 'gerente'";

if ($conn->query($sql) === TRUE) {
    header("Location: listar_funcionarios.php");
    exit;
} else {
    echo "Erro ao excluir: " . $conn->error;
}
