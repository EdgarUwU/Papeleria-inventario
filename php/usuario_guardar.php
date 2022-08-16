<?php
    
    require_once "main.php";
    require_once "../inc/session_start.php";

    /*== Almacenando datos ==*/
    $nombre=limpiar_cadena($_POST['nombre']);
    $apellido_pat=limpiar_cadena($_POST['apellido_pat']);
    $apellido_mat=limpiar_cadena($_POST['apellido_mat']);

    $username=limpiar_cadena($_POST['username']);

    $contrasena_1=limpiar_cadena($_POST['contrasena']);
    $contrasena_2=limpiar_cadena($_POST['contrasena2']);
    $permiso=limpiar_cadena($_POST['permiso_usuario']);
    $create_by=limpiar_cadena($_SESSION['id']);


    /*== Verificando campos obligatorios ==*/
    if($nombre=="" || $permiso=="" || $username=="" || $contrasena_1=="" || $contrasena_2==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No ha llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    /*== Verificando integridad de los datos ==*/
    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }


    if(verificar_datos("[a-zA-Z0-9]{4,20}",$username)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El USUARIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$contrasena_1) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$contrasena_2)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Las contrasenaS no coinciden con el formato solicitado
            </div>
        ';
        exit();
    }



    /*== Verificando usuario ==*/
    $check_usuario=conexion2();
    $check_usuario=$check_usuario->query("SELECT username FROM USUARIOS WHERE username='$username'");
    if($check_usuario->rowCount()>0){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El username ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
        exit();
    }
    $check_usuario=null;


    /*== Verificando contrasenas ==*/
    if($contrasena_1!=$contrasena_2){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Las contrasenaS que ha ingresado no coinciden
            </div>
        ';
        exit();
    }else{
        $contrasena=password_hash($contrasena_1,PASSWORD_BCRYPT,["cost"=>10]);
    }
    


    /*== Guardando datos ==*/
    $guardar_usuario=conexion();
    $guardar_usuario=$guardar_usuario->prepare("INSERT INTO USUARIOS (username,nombre,apellido_pat,apellido_mat,contrasena,create_by,ip,privilegios) 
    VALUES (:username,:nombre,:apellido_pat,:apellido_mat,:contrasena,:create_by,:ip,:privilegios)");

    $marcadores=[
        ":username"=>$username,
        ":nombre"=>$nombre,
        ":apellido_pat"=>$apellido_pat,
        ":apellido_mat"=>$apellido_mat,
        ":contrasena"=>$contrasena,
        ":create_by"=>$create_by,
        ":ip"=>$_SESSION['ip'],
        ":privilegios"=>$permiso
    ];

    $guardar_usuario->execute($marcadores);

    if($guardar_usuario->rowCount()==1){
        echo '
            <div class="notification is-info is-light">
                <strong>¡USUARIO REGISTRADO!</strong><br>
                El usuario se registro con exito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el usuario, por favor intente nuevamente
            </div>
        ';
    }
    $guardar_usuario=null;