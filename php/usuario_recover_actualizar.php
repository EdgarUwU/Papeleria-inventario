<?php
	require_once "../inc/session_start.php";

	require_once "main.php";
    
    $id=limpiar_cadena($_POST['id_usuario']);

    /*== Verificando usuario ==*/
	$check_usuario=conexion2();
	$check_usuario=$check_usuario->query("SELECT * FROM USUARIOS WHERE id_usuario='$id' AND deleted='1'");

    if($check_usuario->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El usuario no existe en el sistema
            </div>
        ';
        exit();
    }else{
    	$datos=$check_usuario->fetch();
    }
    $check_usuario=null;

    /*== Actualizar datos ==*/
    $actualizar_usuario=conexion();
    $actualizar_usuario=$actualizar_usuario->prepare("UPDATE USUARIOS SET deleted=:deleted WHERE id_usuario=:id");

    $marcadores=[
        ':deleted'=>'0',
        ':id'=>$id
    ];

    if($actualizar_usuario->execute($marcadores)){
        echo '
            <div class="notification is-info is-light">
                <strong>¡USUARIO ACTUALIZADO!</strong><br>
                El usuario se recupero con exito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar el usuario, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_usuario=null;
