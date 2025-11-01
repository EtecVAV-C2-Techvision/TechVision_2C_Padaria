<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
    <link rel="stylesheet" href="funcionarios/estetica.css">

</head>
<body class="login-body">

  <div class="login-container">
    <h2>Entrar</h2>
    <form method="POST" action="funcionarios/login.php">
      <label for="usuario">Usuário:</label>
      <input type="text" id="usuario" name="usuario" required>

      <label for="senha">Senha:</label>
      <input type="password" id="senha" name="senha" required>

      <button type="submit">Entrar</button>
    </form>
  </div>
</body>
</html>
