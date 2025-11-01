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
body{font-family:Arial;background:#fff9ef;margin:20px}
table{width:100%;border-collapse:collapse;background:#fff}
th,td{padding:10px;border:1px solid #ccc;text-align:center}
input{margin:5px;padding:8px;width:300px}
button{padding:8px 12px;background:#ff914d;color:#fff;border:none;border-radius:5px;cursor:pointer}
</style>
</head>
<body>

<h2>👤 Meu Perfil</h2>

<form method="post">
    <label>Nome completo:</label><br>
    <input type="text" name="nome_completo" value="<?php echo htmlspecialchars($cliente['nome_completo']); ?>"><br>

    <label>Email:</label><br>
    <input type="email" name="email" value="<?php echo htmlspecialchars($cliente['email']); ?>"><br>

    <label>Telefone:</label><br>
    <input type="text" name="telefone" value="<?php echo htmlspecialchars($cliente['telefone']); ?>"><br>

    <label>Endereço:</label><br>
    <input type="text" name="endereco" value="<?php echo htmlspecialchars($cliente['endereco']); ?>"><br><br>

    <label>Nova senha (opcional):</label><br>
    <input type="password" name="nova_senha"><br><br>

    <button type="submit">Salvar Alterações</button>
</form>

<h2>📦 Meus Pedidos</h2>
<table>
<tr><th>ID</th><th>Data</th><th>Status</th><th>Ver</th></tr>
<?php if(mysqli_num_rows($pedidos) > 0){ 
    while($p = mysqli_fetch_assoc($pedidos)){ ?>
    <tr>
        <td><?php echo $p['idPedido']; ?></td>
        <td><?php echo $p['data_pedido']; ?></td>
        <td><?php echo $p['status'] ?? 'Pendente'; ?></td>
        <td><a href="ver_pedidos.php?id=<?php echo $p['idPedido']; ?>">Ver Itens</a></td>
    </tr>
<?php } } else { ?>
    <tr><td colspan="4">Nenhum pedido encontrado.</td></tr>
<?php } ?>
</table>
  <br>
  <a class="btn" href="compras.php">🛒 Voltar às Compras</a>
  <a class="btn" href="../index.php">🏠 Início</a>
</body>
</html>


