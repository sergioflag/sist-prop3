<?php
require "../../vendor/autoload.php";

use models\_lectores as lector;

$headers = getallheaders();
$request = json_decode(file_get_contents("php://input"));

switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if(isset($_GET['id'])){
            echo json_encode(lector::lector($_GET['id']),JSON_UNESCAPED_UNICODE);
        }else{
            echo json_encode(lector::lista_lectores(),JSON_UNESCAPED_UNICODE);
        }
        break;

    case 'POST':
        echo json_encode(lector::guardar($request,JSON_UNESCAPED_UNICODE));
        break;

    case 'PUT':
        
        if(isset($_GET['id'])){
            echo json_encode(lector::actualizar($request,$_GET['id']));
        }else{
            echo json_encode(["error"=>true,"message"=>"No hay id del lector"]);
        }

        break;

    case 'DELETE':
        if(isset($_GET['id'])){
            echo json_encode(lector::eliminar($_GET['id']));
        }else{
            echo json_encode(["error"=>true,"message"=>"No hay id del lector"]);
        }
        break;
    
    default:
        echo "API Funcionando";
        break;
}