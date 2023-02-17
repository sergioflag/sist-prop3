<?php
require "../../vendor/autoload.php";

use models\_libros as libro;

$headers = getallheaders();
$request = json_decode(file_get_contents("php://input"));

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if(isset($_GET['id'])){
            echo json_encode(libro::libro($_GET['id']),JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(libro::lista_libros(),JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':
        echo json_encode(libro::guardar($request,JSON_UNESCAPED_UNICODE));
        break;

    case 'PUT':
        
        if(isset($_GET['id'])){
            echo json_encode(libro::actualizar($request,$_GET['id']));
        }else{
            echo json_encode(["error"=>true,"message"=>"No hay id del libro"]);
        }

        break;

    case 'DELETE':
        if(isset($_GET['id'])){
            echo json_encode(libro::eliminar($_GET['id']));
        }else{
            echo json_encode(["error"=>true,"message"=>"No hay id del libro"]);
        }
        break;
    
    default:
        echo "API Funcionando";
        break;
}