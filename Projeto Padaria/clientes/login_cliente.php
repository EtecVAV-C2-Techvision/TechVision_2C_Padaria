<?php
session_start();
include('../conexao.php');

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

<style>
    body {
        font-family: "Segoe UI", Arial, sans-serif;
        background: #fff7e0;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 420px;
        margin: 80px auto;
        background: #ffffff;
        padding: 35px;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 0 12px rgba(0,0,0,0.1);
        border-top: 10px solid #ffcb45;
    }

    h2 {
        margin-bottom: 20px;
        color: #333;
    }

    label {
        display: block;
        text-align: left;
        font-weight: bold;
        margin-top: 10px;
        margin-bottom: 5px;
        color: #444;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        border: 2px solid #f0e3b0;
        border-radius: 6px;
        font-size: 15px;
        background: #fffef8;
        transition: 0.2s;
    }

    input:focus {
        border-color: #ffcb45;
        box-shadow: 0 0 4px rgba(255,203,69,0.5);
    }

    button {
        width: 100%;
        padding: 12px;
        margin-top: 18px;
        background: #ffcb45;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        font-size: 16px;
        border: 2px solid #f8d447;
        color: #333;
        transition: 0.2s;
    }

    button:hover {
        background: #f8d447;
    }

    .erro {
        color: red;
        margin-bottom: 12px;
        font-weight: bold;
    }

    .links {
        margin-top: 20px;
        font-size: 15px;
    }

    .links a {
        color: #d39a00;
        font-weight: bold;
        text-decoration: none;
    }

    .links a:hover {
        text-decoration: underline;
    }
</style>

</head>
<body>

<div class="container">
    <h2>Login de Cliente</h2>

    <?php if (isset($erro)) echo "<p class='erro'>$erro</p>"; ?>

    <form method="post">
        <label>Usuário:</label>
        <input type="text" name="usuario" required>

        <label>Senha:</label>
        <input type="password" name="senha" required>

        <button type="submit">Entrar</button>
    </form>

    <div class="links">
        Não tem conta? <a href="cadastro_cliente.php">Cadastre-se</a><br><br>
        <a href="../index.php">⬅ Voltar à Página Inicial</a>
    </div>
</div>

</body>
</html>

</html>
