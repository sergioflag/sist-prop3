<?php
require "../../vendor/autoload.php";

use models\_usuarios as usuario;

$headers = getallheaders();
$request = json_decode(file_get_contents("php://input"));

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if(isset($_GET['id'])){
            echo json_encode(usuario::usuario($_GET['id']),JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(usuario::lista_usuarios(),JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':
        echo json_encode(usuario::guardar($request));
        break;

    case 'PUT':
        
        if(isset($_GET['id'])){
            echo json_encode(usuario::actualizar($request,$_GET['id']));
        }else{
            echo json_encode(["error"=>true,"message"=>"No hay id del usuario"]);
        }

        break;

    case 'DELETE':
        if(isset($_GET['id'])){
            echo json_encode(usuario::eliminar($_GET['id']));
        }else{
            echo json_encode(["error"=>true,"message"=>"No hay id del usuario"]);
        }
        break;
    
    default:
        echo "API Funcionando";
        break;
}