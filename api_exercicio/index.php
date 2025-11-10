<?php
header('Access-Control-Allow-Origin: *');
header('Content-type: application/json');

date_default_timezone_set("America/Sao_Paulo");

if(isset($_GET['path'])){
    $path = explode("/", $_GET['path']);
}
else{
    echo "Caminho não existe"; exit;
}

if(isset($path[0])) { 
    $api = $path[0];
} else {
    echo "Caminho não existe";exit;
}
if(isset($path[1])) { 
    $acao = $path[1];
} else {
    $acao = '';
}
if(isset($path[2])) { 
    $param = $path[2];
} else {
    $param = '';
}

$method = $_SERVER['REQUEST_METHOD'];
var_dump($method);

include_once "classes/db.class.php";
include_once "api/clientes/clientes.php";


/*$GLOBALS['secretJWT'] = '123456';

include_once "classes/autoload.class.php";
new Autoload();

$rota = new Rotas();
$rota->add('POST', '/usuarios/login', 'Usuarios::login', false);
$rota->add('GET', '/clientes/listar', 'Clientes::listarTodos', true);
$rota->add('GET', '/clientes/listar/[PARAM]', 'Clientes::listarUnico', true);
$rota->add('POST', '/clientes/adicionar', 'Clientes::adicionar', true);
$rota->add('PUT', '/clientes/atualizar/[PARAM]', 'Clientes::atualizar', true);
$rota->ir($_GET['path']);*/

?>