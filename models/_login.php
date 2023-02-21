<?php

namespace models;

use configuration\authToken as tk;
use configuration\db as db;
use configuration\algorithms as algo;

# archivos de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class _login{

    public static function login($data){
        $output['error'] = true;
        if(!empty($data)){
            $query = "SELECT
            usuarios.id_usuario id_usuario, usuarios.contrasena contrasena, usuarios.usuario usuario, usuarios.email correo,
            CONCAT(personas.nombres,' ',personas.a_paterno,' ',personas.a_materno) nombre,
            perfiles.perfil perfil
            FROM usuarios
            INNER JOIN personas ON usuarios.id_persona = personas.id_persona
            INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
            WHERE usuarios.email = '$data->email'";

            $usuario = db::query($query);
            
            if(isset($usuario[0]->id_usuario)){
                if(algo::verify($data->contrasena,$usuario[0]->contrasena)){
                    unset($usuario[0]->contrasena);

                    $id_usuario=$usuario[0]->id_usuario;

                    $query = "SELECT
                    menu.recurso recurso, menu.url ruta, menu.icono icono
                    FROM usuarios
                    INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
                    INNER JOIN perfiles_menu ON usuarios.id_perfil = perfiles_menu.id_perfil
                    INNER JOIN menu ON perfiles_menu.id_menu = menu.id_menu
                    WHERE usuarios.id_usuario = '$id_usuario'";

                    $menu = db::query($query);
                    $jwtData['usuario'] = $usuario[0];
                    $jwtData['menu'] = $menu;

                    $output['token'] = tk::SignIn($jwtData);
                    $output['error'] = false;

                }else{
                    $output['message'] = "La contraseña es incorrecta";
                }
            }else{
                $output['message'] = "El usuario no existe";
            }

        }

        return $output;

    }

    public static function menu($_token){

        $output['error'] = true;

        if(tk::Check($_token)){
            $output['error'] = false;
            $output['data'] = tk::GetData($_token);
        }else{
            $output['message'] = "El token ha expirado";
        }

        return $output;
    }

    public static function reset_password($data){

        $output['error']=true;

        if(!empty($data)){
            $query = "SELECT 
            usuarios.id_usuario id_usuario, usuarios.email email, usuarios.contrasena contrasena,
            CONCAT(personas.nombres,' ',personas.a_paterno,' ',personas.a_materno) nombre
            FROM usuarios
            INNER JOIN personas ON usuarios.id_persona = personas.id_persona
            WHERE usuarios.email = '$data->email'";

            $usuario = db::query($query);
            if(isset($usuario[0]->id_usuario)){
                
                if(algo::verify($data->contrasena,$usuario[0]->contrasena)){

                    $data->nueva_contrasena = algo::hash($data->nueva_contrasena);
                    $id_usuario = $usuario[0]->id_usuario;

                    $query = "UPDATE usuarios
                    SET contrasena = '$data->nueva_contrasena'
                    WHERE id_usuario = '$id_usuario'";

                    if(db::query($query)){

                        $usuario = $usuario[0];
                        unset($usuario->contrasena);
                        $folio = algo::folioGenerator();

                        //$output['folio'] = algo::folioGenerator();
                        //$output['usuario'] = $usuario;

                        
                        if(self::enviar_correo($usuario,$folio)){
                            $output['error'] = false;
                            $output['message'] = "La contraseña se actualizó exitosamente";
                        }else{

                            $output['message'] = "La contraseña se actualizó pero hubo un error con el envío de email";
                        }
                            
                    }else{
                            $output['message'] = "Hubo un error con la actualización de la contraseña";
                    }
                }else{
                    $output['message'] = "La contraseña no coincide";
                }
            }else{
                $output['message'] = "El usuario no existe";
            }
        }else{
            $output['message'] = "No se ingresó data";
        }
        return $output;
    }

    public static function enviar_correo($data,$folio){

        #Instancia de conexión
        $mail = new PHPMailer(true);
            #Congiguraciones del servidor
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host       = 'smtp.gmail.com'; //Set the SMTP server to send through
            $mail->SMTPAuth   = true; //Enable SMTP authentication
            $mail->Username   = 'sergiofol1093@gmail.com'; //SMTP username
            $mail->Password   = 'guxnjgozzcgoonod'; //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port       = 465;

            #Datos del emisor y receptor
            $mail->setFrom("sergiofol1093@gmail.com",'Administrador');
            $mail->addAddress($data->email,"{$data->nombre}");

            #Configuracion del contenido de envío
            $mail->isHTML(true);
            $mail->Subject = "Actualización de contraseña";
            $mail->AltBody = "Su contrasena se ha actualizado";
            $mail->Body = "
            <div>
                <h3>Hola {$data->nombre}</h3>
                <p>Te informamos que tu contraseña ha sido actualizada. Folio:{$folio}</p>
            </div>
            ";

            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';

            if($mail->send()){
                return true;
            }else{
                return false;
            }

    }


}



?>