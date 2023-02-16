<?php

namespace models;

use configuration\authToken as tk;
use configuration\db as db;
use configuration\algorithms as algo;

class _lectores{

    public static function lista_lectores(){

        $query = "SELECT
                    lectores.direccion direccion, lectores.status estado,
                    CONCAT(personas.nombres,' ',personas.a_paterno,' ',personas.a_materno) nombre
                FROM personas
                INNER JOIN lectores ON personas.id_persona = lectores.id_persona";

        $lectores = db::query($query);

        if(!empty($lectores)){
            $output['error'] = false;
            $output['lectores'] = $lectores;
        }else{
            $output['error'] = true;
            $output['message'] = "No hay lectores registrados";
        }

        return $output;
        
    }

    public static function lector($_id){

        $query = "SELECT
                    lectores.direccion direccion, lectores.status estado,
                    CONCAT(personas.nombres,' ',personas.a_paterno,' ',personas.a_materno) nombre
                FROM personas
                INNER JOIN lectores ON personas.id_persona = lectores.id_persona
                WHERE lectores.id_persona = '$_id'";

        $lectores = db::query($query);

        if(!empty($lectores)){
            $output['error'] = false;
            $output['lectores'] = $lectores[0];
        }else{
            $output['error'] = true;
            $output['message'] = "El lector no existe";
        }

        return $output;

    }

    public static function guardar($data){
        $output['error'] = true;

        if(!empty($data)){
            $query = "SELECT 1 existe FROM personas WHERE nombres = '$data->nombres' AND a_paterno = '$data->a_paterno' AND a_materno = '$data->a_materno'";
            $lectores = db::query($query);

            if(!isset($lectores[0]->existe)){

                $queries[0]= "INSERT INTO personas(nombres,a_paterno,a_materno,telefono,f_nacimiento) 
                VALUES('$data->nombres','$data->a_paterno','$data->a_materno','$data->telefono','$data->f_nacimiento')";

                $queries[1]= "INSERT INTO lectores(id_persona,direccion)
                VALUES((SELECT MAX(id_persona) FROM personas),'$data->direccion')";

                if(db::stored_procedure($queries)){
                    $output['error'] = false;
                    $output['message'] = "El lector se registró con éxito";
                }else{
                    $output['message'] = "Hubo un error en el registro";
                }

            }else{
                $output['message'] = "El lector ya existe";
            }
        }

        return $output;

    }

    public static function actualizar($data,$_id){

        $output['error'] = true;

        if(!empty($data)){
            $query = "SELECT 1 existe FROM lectores WHERE id_persona = '$_id'";
            $usuarios = db::query($query);

            if(isset($usuarios[0]->existe)){

                $data->contrasena = algo::hash($data->contrasena);

                $queries[0]= "UPDATE personas
                SET nombres = '$data->nombres', a_paterno = '$data->a_paterno', a_materno = '$data->a_materno', telefono = '$data->telefono', f_nacimiento = '$data->f_nacimiento'
                WHERE id_persona = '$_id'";

                $queries[1]= "UPDATE lectores
                SET direccion = '$data->direccion'
                WHERE id_persona = '$_id'";

                if(db::stored_procedure($queries)){
                    $output['error'] = false;
                    $output['message'] = "El lector se actualizó con éxito";
                }else{
                    $output['message'] = "Hubo un error en el registro";
                }

            }else{
                $output['message'] = "El lector no existe";
            }
        }

        return $output;

    }

    public static function eliminar($_id){

        $query = "SELECT 1 existe FROM lectores WHERE id_persona = '$_id'";
        $usuarios = db::query($query);

        if(isset($usuarios[0]->existe)){

            $queries[0]= "UPDATE personas
            SET status = 0
            WHERE id_persona = '$_id'";

            $queries[1]= "UPDATE lectores
            SET status = 0
            WHERE id_persona = '$_id'";

            if(db::stored_procedure($queries)){
                $output['error'] = false;
                $output['message'] = "El lector se eliminó con éxito";
            }else{
                $output['message'] = "Hubo un error en el registro";
            }

        }else{
            $output['message'] = "El lector no existe";
        }

 
        return $output;

    }

}



?>