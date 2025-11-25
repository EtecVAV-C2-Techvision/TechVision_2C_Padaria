<?php
session_start();
echo isset($_SESSION["logado"]) ? "LOGADO" : "NADA";

