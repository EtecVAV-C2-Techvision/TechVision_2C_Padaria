
<?php
session_start();

if (empty($_SESSION['cliente']) || empty($_SESSION['cliente']['idCli'])) {
    header("Location: login_cliente.php");
    exit;
}
?>
