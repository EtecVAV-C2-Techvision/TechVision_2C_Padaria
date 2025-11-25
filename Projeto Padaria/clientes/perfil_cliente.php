<?php
include('proteger_cliente.php');
include('../conexao.php');

$idCli = $_SESSION['cliente']['idCli'];

// Quando o formulário é enviado
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = $_POST['nome_completo'] ?? '';
    $email = $_POST['email'] ?? '';
    $telefone = $_POST['telefone'] ?? '';
    $endereco = $_POST['endereco'] ?? '';
    $nova_senha = $_POST['nova_senha'] ?? '';

    // Atualiza dados principais
    $sql = "UPDATE clientes SET nome_completo='$nome', email='$email', telefone='$telefone', endereco='$endereco' WHERE idCli=$idCli";
    mysqli_query($conn, $sql);

    // Atualiza senha (com hash), se o campo não estiver vazio
    if ($nova_senha != '') {
        $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
        $sql = "UPDATE clientes SET senha='$senha_hash' WHERE idCli=$idCli";
        mysqli_query($conn, $sql);
    }

    echo "<script>alert('Dados atualizados com sucesso!');window.location='perfil_cliente.php';</script>";
    exit;
}

// Pega dados do cliente
$busca = mysqli_query($conn, "SELECT * FROM clientes WHERE idCli=$idCli");
$cliente = mysqli_fetch_assoc($busca);

// Pega pedidos do cliente
$pedidos = mysqli_query($conn, "SELECT * FROM pedidos WHERE idCli=$idCli ORDER BY data_pedido DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Perfil do Cliente</title>

<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #fff7e0;
        margin: 0;
        padding: 20px;
    }

    h2 {
        color: #333;
        margin-bottom: 15px;
        text-align: center;
    }

    /* CONTAINER DO FORMULÁRIO */
    .perfil-box {
        max-width: 600px;
        margin: 20px auto 40px auto;
        background: #ffffff;
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
        border-top: 8px solid #ffcb45;
    }

    label {
        font-weight: bold;
        color: #444;
        display: block;
        margin-top: 10px;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-top: 4px;
        border-radius: 6px;
        border: 2px solid #f0e3b0;
        background: #fffef8;
        font-size: 15px;
        transition: .2s;
    }

    input:focus {
        border-color: #ffcb45;
        box-shadow: 0 0 4px rgba(255,203,69,0.5);
    }

    button {
        display: block;
        width: 100%;
        padding: 12px;
        margin-top: 20px;
        background: #ffcb45;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 16px;
        border: 2px solid #f8d447;
        cursor: pointer;
        transition: .2s;
        color: #333;
    }

    button:hover {
        background: #f8d447;
    }

    /* TABELA DE PEDIDOS */
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 25px;
        background: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
    }

    th {
        background: #ffcb45;
        color: #333;
        font-weight: bold;
        padding: 12px;
        border-bottom: 3px solid #f8d447;
    }

    td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #f2e5b3;
        color: #444;
        font-size: 15px;
    }

    tr:hover td {
        background: #fff3c2;
    }

    .btn {
        display: inline-block;
        background: #ffcb45;
        padding: 10px 16px;
        border-radius: 8px;
        color: #333;
        font-weight: bold;
        text-decoration: none;
        transition: 0.2s;
        border: 2px solid #f8d447;
        margin: 8px 5px;
    }

    .btn:hover {
        background: #f8d447;
    }

    .links-center {
        text-align: center;
        margin-top: 25px;
    }
</style>

</head>
<body>

<h2>👤 Meu Perfil</h2>

<div class="perfil-box">
<form method="post">

    <label>Nome completo:</label>
    <input type="text" name="nome_completo"
           value="<?= htmlspecialchars($cliente['nome_completo']); ?>">

    <label>Email:</label>
    <input type="email" name="email"
           value="<?= htmlspecialchars($cliente['email']); ?>">

    <label>Telefone:</label>
    <input type="text" name="telefone"
           value="<?= htmlspecialchars($cliente['telefone']); ?>">

    <label>Endereço:</label>
    <input type="text" name="endereco"
           value="<?= htmlspecialchars($cliente['endereco']); ?>">

    <label>Nova senha (opcional):</label>
    <input type="password" name="nova_senha">

    <button type="submit">Salvar Alterações</button>

</form>
</div>

<h2>📦 Meus Pedidos</h2>

<table>
<tr>
    <th>ID</th>
    <th>Data</th>
    <th>Status</th>
    <th>Ver</th>
</tr>

<?php if(mysqli_num_rows($pedidos) > 0){ 
    while($p = mysqli_fetch_assoc($pedidos)){ ?>
    <tr>
        <td><?= $p['idPedido']; ?></td>
        <td><?= $p['data_pedido']; ?></td>
        <td><?= $p['status'] ?? 'Pendente'; ?></td>
        <td><a class="btn" href="ver_pedidos.php?id=<?= $p['idPedido']; ?>">Ver Itens</a></td>
    </tr>
<?php } } else { ?>
    <tr><td colspan="4">Nenhum pedido encontrado.</td></tr>
<?php } ?>
</table>

<div class="links-center">
    <a class="btn" href="compras.php">🛒 Voltar às Compras</a>
    <a class="btn" href="../index.php">🏠 Início</a>
</div>

</body>
</html>

</html>


