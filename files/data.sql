-- COMANDOS EN LA TERMINAL PARA COMPOSER
    -- composer update
    -- composer dump-autoload --> después de crear un _model.php

-- SE CREA LA DB CON EL MODELO

-- SE INSERTAN LOS SCRIPTS DE FORMA MANUAL

    -- DATA DE TABLA MENU

    INSERT INTO menu(recurso,url)
    VALUES('inicio','/inicio'),
    ('usuarios','/usuarios'),
    ('libros','/libros'),
    ('lectores','/lectores'),
    ('prestamos','/prestamos')
    ;

    -- DATA PERFILES

    INSERT INTO perfiles(perfil)
    VALUES('administrador'),('recepcionista');

    -- DATA PERFIL-MENU

    INSERT INTO perfiles_menu(id_perfil,id_menu)
    VALUES(1,1),(1,2),(1,3),(1,4),(1,5),
    (2,1),(2,3),(2,4),(2,5)
    ;

--- 3 EL RESTO DE LA DATA SE INSERTA CON APIS

--- METODOS DE USUARIOS
    -- GET USUARIOS
    SELECT
        usuarios.id_usuario id, usuarios.email email, usuarios.status estado,
        CONCAT(personas.nombres,' ',personas.a_paterno,' ',personas.a_materno) nombre,
        perfiles.perfil perfil
    FROM usuarios
    INNER JOIN personas ON usuarios.id_persona = personas.id_persona
    INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil

    -- GET USUARIO
    SELECT
        usuarios.id_usuario id, usuarios.email email, usuarios.status estado,
        CONCAT(personas.nombres,' ',personas.a_paterno,' ',personas.a_materno) nombre,
        perfiles.perfil perfil
    FROM usuarios
    INNER JOIN personas ON usuarios.id_persona = personas.id_persona
    INNER JOIN perfiles ON usuarios.id_perfil = perfiles.id_perfil
    WHERE usuarios.id_usuario = ''

    -- INSERT USUARIO
        -- TABLAS persona + usuario
    INSERT INTO personas(nombres,a_paterno,a_materno,telefono,f_nacimiento) 
    VALUES('Sergio','Flores','Lagunas','+52 418 585 6688','1993-10-27')

    INSERT INTO usuarios(id_persona,id_perfil,usuario,email,contrasena)
    VALUES((SELECT MAX(id_persona) FROM personas),1,'admin09','sergio@mail.com','admin09')
    
    -- UPDATE USUARIO
    UPDATE personas
    SET nombres = '', a_paterno = '', a_materno = '', telefono = '', f_nacimiento = ''
    WHERE id_persona = ''

    UPDATE usuarios
    SET id_perfil = '', usuario = '', email = '', contrasena = ''
    WHERE id_usuario = ''

    -- DELETE USUARIO
    UPDATE personas
    SET status = 0
    WHERE id_persona = ''

    UPDATE usuarios
    SET status = 0
    WHERE id_usuario = ''

-- METODOS LECTORES

    -- GET LECTORES
    SELECT
        lectores.direccion direccion, lectores.status estado,
        CONCAT(personas.nombres,' ',personas.a_paterno,' ',personas.a_materno) nombre
    FROM personas
    INNER JOIN lectores ON personas.id_persona = lectores.id_persona

    -- GET LECTOR
    SELECT
        lectores.direccion direccion, lectores.status estado,
        CONCAT(personas.nombres,' ',personas.a_paterno,' ',personas.a_materno) nombre
    FROM personas
    INNER JOIN lectores ON personas.id_persona = lectores.id_persona
    WHERE lectores.id_persona = ''

    -- INSERT LECTOR
     -- MISMA DATA DE PERSONA
    
    INSERT INTO usuarios(id_persona,direccion)
    VALUES((SELECT MAX(id_persona) FROM personas),'')
    
    -- UPDATE LECTOR
    -- DELETE LECTOR