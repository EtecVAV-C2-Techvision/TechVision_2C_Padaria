<?php
include "proteger.php";
include "../conexao.php";

// PERMITIR APENAS GERENTE
if ($_SESSION['funcao'] !== 'gerente') {
    die("<h3 style='color:red;'>Acesso negado! Apenas gerentes podem acessar.</h3>");
}

$idFunc = $_SESSION['idFunc'];

// ======================================================================
// EXCLUIR CLIENTE
// ======================================================================
if (isset($_GET['excluir'])) {
    $idCli = (int) $_GET['excluir'];

    // Buscar cliente antes de excluir
    $sql = $conn->prepare("SELECT nome_completo FROM clientes WHERE idCli=?");
    $sql->bind_param("i", $idCli);
    $sql->execute();
    $cliente = $sql->get_result()->fetch_assoc();

    if ($cliente) {

        // Registrar log
        $acao = "Excluiu o cliente '{$cliente['nome_completo']}' (ID $idCli)";
        $log = $conn->prepare("INSERT INTO log_clientes (idCli, idFunc, acao) VALUES (?, ?, ?)");
        $log->bind_param("iis", $idCli, $idFunc, $acao);
        $log->execute();

        // Excluir cliente
        $del = $conn->prepare("DELETE FROM clientes WHERE idCli=?");
        $del->bind_param("i", $idCli);
        $del->execute();

        echo "<script>alert('Cliente excluído com sucesso!');window.location='gerenciar_clientes.php';</script>";
        exit;

    } else {
        echo "<script>alert('Cliente não encontrado.');window.location='gerenciar_clientes.php';</script>";
        exit;
    }
}

// ======================================================================
// ALTERAR CLIENTE (POPUP)
// ======================================================================
if (isset($_POST['alterar'])) {

    $idCli = $_POST['idCli'];
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $usuario = $_POST['usuario'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];

    $sql = $conn->prepare("UPDATE clientes SET nome_completo=?, email=?, usuario=?, telefone=?, endereco=? WHERE idCli=?");
    $sql->bind_param("sssssi", $nome, $email, $usuario, $telefone, $endereco, $idCli);
    $sql->execute();

    // Registrar alteração no log
    $acao = "Alterou dados do cliente '$nome' (ID $idCli)";
    $log = $conn->prepare("INSERT INTO log_clientes (idCli, idFunc, acao) VALUES (?, ?, ?)");
    $log->bind_param("iis", $idCli, $idFunc, $acao);
    $log->execute();

    echo "<script>alert('Cliente atualizado com sucesso!');window.location='gerenciar_clientes.php';</script>";
    exit;
}

// ======================================================================
// LISTAR CLIENTES
// ======================================================================
$clientes = $conn->query("SELECT * FROM clientes ORDER BY idCli DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Gerenciar Clientes</title>

<style>
    body {font-family: Arial; background:#fff7e0; padding:20px;}
    h2{text-align:center; color:#333;}
    table{width:100%; background:#fff; border-collapse:collapse; margin-top:20px;
          box-shadow:0 0 10px rgba(0,0,0,0.1);}
    th{background:#ffcb45; padding:12px; color:#333;}
    td{padding:10px; border-bottom:1px solid #f2e5b3; text-align:center;}
    tr:hover td{background:#fff3c2;}

    .btn{padding:6px 12px; border-radius:6px; text-decoration:none; font-weight:bold; cursor:pointer;}
    .editar{background:#28a745; color:white;}
    .editar:hover{background:#218838;}
    .excluir{background:#dc3545; color:white;}
    .excluir:hover{background:#c82333;}
    .voltar{background:#ffcb45; margin-top:20px; display:inline-block;}

    #popup{
        display:none; position:fixed; top:50%; left:50%;
        transform:translate(-50%,-50%); background:#fff;
        padding:20px; border-radius:10px; width:350px;
        box-shadow:0 0 10px rgba(0,0,0,0.3);
    }
</style>

</head>
<body>

<h2>📋 Gerenciar Clientes</h2>

<a href="dashboard.php" class="btn voltar">⬅ Voltar</a>

<table>
<tr>
    <th>ID</th>
    <th>Nome</th>
    <th>E-mail</th>
    <th>Usuário</th>
    <th>Telefone</th>
    <th>Endereço</th>
    <th>Ações</th>
</tr>

<?php while ($c = $clientes->fetch_assoc()): ?>
<tr>
    <td><?= $c['idCli'] ?></td>
    <td><?= $c['nome_completo'] ?></td>
    <td><?= $c['email'] ?></td>
    <td><?= $c['usuario'] ?></td>
    <td><?= $c['telefone'] ?></td>
    <td><?= $c['endereco'] ?></td>
    <td>

        <button class="btn editar" onclick="editarCliente(
            '<?= $c['idCli'] ?>',
            '<?= addslashes($c['nome_completo']) ?>',
            '<?= addslashes($c['email']) ?>',
            '<?= addslashes($c['usuario']) ?>',
            '<?= addslashes($c['telefone']) ?>',
            '<?= addslashes($c['endereco']) ?>'
        )">Editar</button>

        <a href="?excluir=<?= $c['idCli'] ?>" class="btn excluir"
            onclick="return confirm('Deseja realmente excluir este cliente?')">
            Excluir
        </a>

    </td>
</tr>
<?php endwhile; ?>
</table>


<!-- POPUP DE EDIÇÃO -->
<div id="popup">
    <h3>Alterar Cliente</h3>

    <form method="post">
        <input type="hidden" name="idCli" id="idCli">

        Nome:<br>
        <input type="text" name="nome" id="nome" required><br><br>

        E-mail:<br>
        <input type="email" name="email" id="email" required><br><br>

        Usuário:<br>
        <input type="text" name="usuario" id="usuario" required><br><br>

        Telefone:<br>
        <input type="text" name="telefone" id="telefone"><br><br>

        Endereço:<br>
        <input type="text" name="endereco" id="endereco"><br><br>

        <button type="submit" name="alterar" class="btn editar">Salvar</button>
        <button type="button" class="btn excluir" onclick="document.getElementById('popup').style.display='none'">Fechar</button>
    </form>
</div>

<script>
function editarCliente(id, nome, email, usuario, telefone, endereco) {
    document.getElementById("idCli").value = id;
    document.getElementById("nome").value = nome;
    document.getElementById("email").value = email;
    document.getElementById("usuario").value = usuario;
    document.getElementById("telefone").value = telefone;
    document.getElementById("endereco").value = endereco;

    document.getElementById("popup").style.display = 'block';
}
</script>

</body>
</html>
