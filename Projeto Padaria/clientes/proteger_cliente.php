<?php
if (!isset($_SESSION['cliente'])) {
    header("Location: login_cliente.php");
    exit;
}
?>
