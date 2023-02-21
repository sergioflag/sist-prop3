<?php
require "../../vendor/autoload.php";

use models\_login as login;

$headers = getallheaders();
$request = json_decode(file_get_contents("php://input"));


switch ($_SERVER['REQUEST_METHOD']) {

    case 'GET':
        if(isset($headers['token'])){
            echo json_encode(login::menu($headers['token']));
        }else{
            echo json_encode(["error"=>true,"message"=>"No hay token en el encabezado"]);
        }
        break;

    case 'POST':
        echo json_encode(login::login($request));
        break;

    case 'PUT':
        echo json_encode(login::reset_password($request));
        break;
    
    default:
        echo "API Funcionando";
        break;
}
