<?php

namespace models;

use configuration\authToken as tk;
use configuration\db as db;
use configuration\algorithms as algo;

class _login{

    public static function login($data){

        $output['error'] = true;

        if(!empty($data)){
            # Buscar el usuario y extraemos su contraseña, nombre, perfil, id_perfil y correo de la db

            $query = "SELECT
                    usuarios.id_usuario id_usuario, usuarios.contrasena contrasena, usuarios.usuario usuario, usuarios.email correo,
                    CONCAT(personas.nombres,' ',personas.a_paterno,' ',personas.a_materno) nombre,
                    perfiles.perfil perfil
                FROM usuarios
                INNER JOIN personas ON usuarios.id_persona = personas.id_persona
                INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
                WHERE usuarios.email = '$data->email'";

            $usuario = db::query($query);

            if(isset($usuario[0]->contrasena)){
                #Si el usuario existe, verificamos la contraseña
                if(algo::verify($data->contrasena,$usuario[0]->contrasena)){
                    #Si la contraseña es correcta, extraemos el menu al que tiene acceso en base al perfil
                    $id_usuario = $usuario[0]->id_usuario;
                    unset($usuario[0]->contrasena);

                    $query = "SELECT
                            menu.recurso recurso, menu.url ruta, menu.icono icono
                        FROM usuarios
                        INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
                        INNER JOIN perfiles_menu ON usuarios.id_perfil = perfiles_menu.id_perfil
                        INNER JOIN menu ON perfiles_menu.id_menu = menu.id_menu
                        WHERE usuarios.id_usuario = '$id_usuario'";

                    $resp = db::query($query);

                    foreach($resp as $r){
                        $menu[] = [
                            "recurso" => $r->recurso,
                            "ruta" => $r->ruta,
                            "icono" => $r->icono
                        ];
                    }

                    $token_data['usuario'] = $usuario[0];
                    $token_data['menu'] = $menu;

                    # Generamos el token
                    $token = tk::SignIn($token_data);

                    $output['token'] = $token;
                    $output['error'] = false;
                }else{
                    #Si la contraseña no es correcta
                    $output['message'] = "La contraseña es incorrecta";
                }
            }else{
                #Si el usuario no existe
                $output['message'] = "El usuario no existe";
            }

            return $output;;

        }
    }

    public static function reset_password($data){

        $output['error'] = true;

        if(!empty($data)){
            #Buscamos el usuario y extraemos su contrasena
            $query = "SELECT
                id_usuario, email, contrasena
            FROM usuarios
            WHERE email = '$data->email'";
        
            $usuario = db::query($query);

            if(isset($usuario[0]->id_usuario)){
                #Si el usuario existe, verificamos contraseña
                if(algo::verify($data->contrasena,$usuario[0]->contrasena)){
                    #Si la contraseña actual coincide con la registrada en la db
                    //$output['message'] = "La contraseña actual coincide";
                    $id_usuario = $usuario[0]->id_usuario;
                    $data->nueva_contrasena = algo::hash($data->nueva_contrasena);

                    $query = "UPDATE usuarios
                    SET contrasena = '$data->nueva_contrasena'
                    WHERE id_usuario = '$id_usuario'";

                    if(db::query($query)){
                        $output['error'] = false;
                        $output['message'] = "La contraseña se actualizó correctamente";
                    }else{
                        $output['message'] = "Hubo un error";
                    }

                }else{
                    #Si la contraseña actual no coincide con la registrada en la db
                    $output['message'] = "La contraseña actual no coincide";
                }
            
            }else{
                #Si el usuario no existe
                $output['message'] = "El usuario no existe";
            }
            
            return $output;
        }
        
    }

    public static function get_menu($_token){

        $output['error'] = true;

        if(tk::Check($_token)){
            $output['error'] = false;
            $output['data'] = tk::GetData($_token);
        }else{
            $output['message'] = "El token ha expirado";
        }

        return $output;

    }

}



?>"