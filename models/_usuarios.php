<?php

namespace models;

# archivos de Configuracion
use configuration\db as db;
use configuration\algorithms as algo;
use configuration\_mailer as mail;

# archivos de PHPMailer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class _usuarios{

    public static function lista_usuarios(){

        $query = "SELECT
            personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno, personas.telefono telefono, personas.f_nacimiento f_nacimiento,
            usuarios.id_usuario id_usuario, usuarios.usuario usuario, usuarios.email email, usuarios.status estado,
            perfiles.id_perfil id_perfil, perfiles.perfil perfil
        FROM usuarios
        INNER JOIN personas ON usuarios.id_persona = personas.id_persona
        INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil";

        $usuarios = db::query($query);

        if(!empty($usuarios)){
            $output['error'] = false;
            $output['usuarios'] = $usuarios;
        }else{
            $output['error'] = true;
            $output['message'] = "No hay usuarios registrados";
        }

        return $output;
        
    }

    public static function usuario($_id){

        $query = "SELECT
            personas.id_persona id_persona, personas.nombres nombres, personas.a_paterno a_paterno, personas.a_materno a_materno, personas.telefono telefono, personas.f_nacimiento f_nacimiento,
            usuarios.id_usuario id_usuario, usuarios.usuario usuario, usuarios.email email, usuarios.status estado
            perfiles.id_perfil id_perfil, perfiles.perfil perfil
        FROM usuarios
        INNER JOIN personas ON usuarios.id_persona = personas.id_persona
        INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
        WHERE usuarios.id_usuario = '$_id'";

        $usuarios = db::query($query);

        if(!empty($usuarios)){
            $output['error'] = false;
            $output['usuarios'] = $usuarios[0];
        }else{
            $output['error'] = true;
            $output['message'] = "El usuario no existe";
        }

        return $output;

    }

    public static function guardar($data){
        $output['error'] = true;

        if(!empty($data)){
            $query = "SELECT 1 existe FROM usuarios WHERE usuario = '$data->usuario' AND email = '$data->email'";
            $usuarios = db::query($query);

            if(!isset($usuarios[0]->existe)){

                $data->contrasena = algo::hash($data->contrasena);

                $queries[0]= "INSERT INTO personas(nombres,a_paterno,a_materno,telefono,f_nacimiento) 
                VALUES('$data->nombres','$data->a_paterno','$data->a_materno','$data->telefono','$data->f_nacimiento')";

                $queries[1]= "INSERT INTO usuarios(id_persona,id_perfil,usuario,email,contrasena)
                VALUES((SELECT MAX(id_persona) FROM personas),'$data->id_perfil','$data->usuario','$data->email','$data->contrasena')";

                if(db::stored_procedure($queries)){
                    $query = "SELECT MAX(id_usuario) id_usuario FROM usuarios";
                    $_id = db::query($query);

                    $output['error'] = false;
                    $output['message'] = "El usuario se registró con éxito";
                    $output['id_usuario'] = $_id[0]->id_usuario;

                }else{
                    $output['message'] = "Hubo un error en el registro";
                }

            }else{
                $output['message'] = "El usuario ya existe";
            }
        }

        return $output;

    }

    public static function actualizar($data,$_id){

        $output['error'] = true;

        if(!empty($data)){
            $query = "SELECT 1 existe FROM usuarios WHERE id_usuario = '$_id'";
            $usuarios = db::query($query);

            if(isset($usuarios[0]->existe)){

                $data->contrasena = algo::hash($data->contrasena);

                $queries[0]= "UPDATE personas
                SET nombres = '$data->nombres', a_paterno = '$data->a_paterno', a_materno = '$data->a_materno', telefono = '$data->telefono', f_nacimiento = '$data->f_nacimiento'
                WHERE id_persona = '$_id'";

                $queries[1]= "UPDATE usuarios
                SET id_perfil = '$data->id_perfil', usuario = '$data->usuario', email = '$data->email', contrasena = '$data->contrasena'
                WHERE id_usuario = '$_id'";

                if(db::stored_procedure($queries)){
                    $output['error'] = false;
                    $output['message'] = "El usuario se actualizó con éxito";
                }else{
                    $output['message'] = "Hubo un error en el registro";
                }

            }else{
                $output['message'] = "El usuario no existe";
            }
        }

        return $output;

    }

    public static function eliminar($_id){

        $query = "SELECT 1 existe FROM usuarios WHERE id_usuario = '$_id'";
        $usuarios = db::query($query);

        if(isset($usuarios[0]->existe)){

            $queries[0]= "UPDATE personas
            SET status = 0
            WHERE id_persona = '$_id'";

            $queries[1]= "UPDATE usuarios
            SET status = 0
            WHERE id_usuario = '$_id'";

            if(db::stored_procedure($queries)){
                $output['error'] = false;
                $output['message'] = "El usuario se eliminó con éxito";
            }else{
                $output['message'] = "Hubo un error en el registro";
            }

        }else{
            $output['message'] = "El usuario no existe";
        }

 
        return $output;

    }

    // NA

    public static function enviar_correo($data,$_id){

        #Instancia de conexión
        $mail = new PHPMailer(true);

        try {
            #Congiguraciones del servidor
            $mail->SMTPDebug = SMTP::DEBUG_SERVER; //Enable verbose debug output
            $mail->isSMTP(); //Send using SMTP
            $mail->Host       = 'smtp.gmail.com'; //Set the SMTP server to send through
            $mail->SMTPAuth   = true; //Enable SMTP authentication
            $mail->Username   = 'sergiofol1093@gmail.com'; //SMTP username
            $mail->Password   = 'guxnjgozzcgoonod'; //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; //Enable implicit TLS encryption
            $mail->Port       = 465;

            #Datos del emisor y receptor
            $mail->setFrom("sergiofol1093@gmail.com",'Administrador');
            $mail->addAddress($data->email,"{$data->nombres} {$data->a_paterno}");

            #Configuracion del contenido de envío
            $mail->isHTML(true);
            $mail->Subject = "Confirmacion de creacion cuenta de usuario";
            $mail->AltBody = "Su cuenta de usuario ha sido creada";
            $mail->Body = "
            <div>
                <h3>Hola {$data->nombres} {$data->a_paterno}</h3>
                <p>Te informamos que tu cuenta de usuario ha sido creada. Tu id es: {$_id}</p>
            </div>
            ";
            $mail->CharSet = 'UTF-8';
            $mail->Encoding = 'base64';
            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }

          

        



    }


}



?>