<?php
session_start();
include('../conexao.php');

// Se o cliente já estiver logado, redireciona
if (isset($_SESSION['cliente'])) {
    header("Location: compras.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $usuario = $_POST['usuario'];
    $senha = $_POST['senha'];

    $sql = "SELECT * FROM clientes WHERE usuario = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $usuario);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows > 0) {
        $cliente = $resultado->fetch_assoc();
        if (password_verify($senha, $cliente['senha'])) {
            $_SESSION['cliente'] = [
                'idCli' => $cliente['idCli'],
                'usuario' => $cliente['usuario'],
                'nome' => $cliente['nome_completo']
            ];
            header("Location: compras.php");
            exit;
        } else {
            $erro = "Senha incorreta.";
        }
    } else {
        $erro = "Usuário não encontrado.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<title>Login de Cliente</title>
</head>
<body>
<h2>Login de Cliente</h2>

<?php if (isset($erro)) echo "<p style='color:red;'>$erro</p>"; ?>

<form method="post">
    <label>Usuário:</label><br>
    <input type="text" name="usuario" required><br><br>

    <label>Senha:</label><br>
    <input type="password" name="senha" required><br><br>

    <button type="submit">Entrar</button>
</form>

<p>Não tem conta? <a href="cadastro_cliente.php">Cadastre-se</a></p>
<p><a href="../index.php">⬅ Voltar à página inicial</a></p>
</body>
</html>
