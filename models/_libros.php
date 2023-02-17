<?php

namespace models;

use configuration\authToken as tk;
use configuration\db as db;
use configuration\algorithms as algo;

class _libros{

    public static function lista_libros(){

        $query = "SELECT
                    libros.id_libro id_libro, libros.titulo titulo, libros.fecha_publicacion publicacion, libros.paginas paginas, libros.isbn isbn,
                    generos.genero genero, 
                    editoriales.editorial editorial,
                    autores.autor autor,
                    stock.cantidad stock
                FROM libros
                INNER JOIN generos ON libros.id_genero = generos.id_genero
                INNER JOIN editoriales ON libros.id_editorial = editoriales.id_editorial
                INNER JOIN autores ON libros.id_autor = autores.id_autor
                INNER JOIN stock ON libros.id_libro = stock.id_libro";

        $libros = db::query($query);

        if(!empty($libros)){
            $output['error'] = false;
            $output['libros'] = $libros;
        }else{
            $output['error'] = true;
            $output['message'] = "No hay libros registrados";
        }

        return $output;
        
    }

    public static function libro($_id){

        $query = "SELECT
                    usuarios.id_usuario id, usuarios.email email, usuarios.status estado,
                    CONCAT(personas.nombres,' ',personas.a_paterno,' ',personas.a_materno) nombre,
                    perfiles.perfil perfil
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
                    $output['error'] = false;
                    $output['message'] = "El usuario se registró con éxito";
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

}



?>