<?php
require "../../vendor/autoload.php";

use models\_login as login;

$headers = getallheaders();
$request = json_decode(file_get_contents("php://input"));

switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':

        if(isset($_GET['opcion'])){

            switch ($_GET['opcion']) {

                case 'login':
                    echo json_encode(login::login($request));
                    break;
    
                case 'reset':
                    //echo json_encode(login::reset_password($request));
                    echo json_encode(login::reset_password($request));
                    break;
    
                case 'menu':
                    if(isset($headers['token'])){
                        echo json_encode(login::get_menu($headers['token']));
                    }else{
                        echo json_encode(["error"=>true,"message"=>"No hay token en el encabezado"]);
                    }
                    break;
                
                default:
                    echo "Defina el método";
                    break;
            }
        }

        break;
    
    default:
        echo "API Funcionando";
        break;
}