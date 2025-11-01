<?php
include "proteger.php";

if (trim(strtolower($_SESSION['funcao'])) !== 'gerente') {
    die("Acesso negado.");
}

include "conexao.php";

$sql = "SELECT idFunc, usuario, nome_completo, email, funcao FROM funcionarios WHERE funcao <> 'gerente'";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>ListarFuncionários</title>
    <link rel="stylesheet" href="estetica.css">

</head>
<body>

    <a href='dashboard.php' class="voltar">Voltar</a>

    <h2>Funcionários</h2>

    <table>
        <tr>
            <th>ID</th>
            <th>Usuário</th>
            <th>Nome</th>
            <th>Email</th>
            <th>Função</th>
            <th>Ações</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['idFunc'] ?></td>
                    <td><?= $row['usuario'] ?></td>
                    <td><?= $row['nome_completo'] ?></td>
                    <td><?= $row['email'] ?></td>
                    <td><?= $row['funcao'] ?></td>
                    <td>
                        <a href='editar_funcionario.php?id=<?= $row['idFunc'] ?>'>Editar</a>
                        <a href='excluir_funcionario.php?id=<?= $row['idFunc'] ?>' class="delete" onclick='return confirm("Confirma exclusão?")'>Excluir</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Nenhum funcionário encontrado.</td>
            </tr>
        <?php endif; ?>
    </table>

</body>
</html>
