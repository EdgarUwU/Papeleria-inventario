<?php
	require_once "../inc/session_start.php";

	require_once "main.php";

    /*== Almacenando id ==*/
    $id=limpiar_cadena($_POST['id_usuario']);

    /*== Verificando usuario ==*/
	$check_usuario=conexion2();
	$check_usuario=$check_usuario->query("SELECT * FROM USUARIOS WHERE id_usuario='$id' AND deleted='0'");

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


    /*== Almacenando datos del usuario ==*/
    $nombre=limpiar_cadena($_POST['nombre']);
    $apellido_pat=limpiar_cadena($_POST['apellido_pat']);
    $apellido_mat=limpiar_cadena($_POST['apellido_mat']);

    $username=limpiar_cadena($_POST['username']);

    $contrasena=limpiar_cadena($_POST['contrasena']);
    $contrasena2=limpiar_cadena($_POST['contrasena2']);


    /*== Verificando campos obligatorios del usuario ==*/
    if($nombre=="" || $username=="" || $contrasena=="" || $contrasena2==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
        /*== Verificando integridad de los datos (usuario) ==*/
    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido_pat)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El APELLIDO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}",$apellido_mat)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El APELLIDO no coincide con el formato solicitado
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
    }


    

    /*== Verificando usuario ==*/
    if($username!=$datos['username']){
	    $check_usuario=conexion2();
	    $check_usuario=$check_usuario->query("SELECT username FROM USUARIOS WHERE username='$username' AND deleted = 0");
	    if($check_usuario->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                El USUARIO ingresado ya se encuentra registrado, por favor elija otro
	            </div>
	        ';
	        exit();
	    }
	    $check_usuario=null;
    }


    /*== Verificando claves ==*/
    if($contrasena!="" || $contrasena2!=""){
    	if(verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$contrasena) || verificar_datos("[a-zA-Z0-9$@.-]{7,100}",$contrasena2)){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                Las CLAVES no coinciden con el formato solicitado
	            </div>
	        ';
	        exit();
	    }else{
		    if($contrasena!=$contrasena2){
		        echo '
		            <div class="notification is-danger is-light">
		                <strong>¡Ocurrio un error inesperado!</strong><br>
		                Las CLAVES que ha ingresado no coinciden
		            </div>
		        ';
		        exit();
		    }else{
		        $contrasena=password_hash($contrasena,PASSWORD_BCRYPT,["cost"=>10]);
		    }
	    }
    }else{
    	$contrasena=$datos['contrasena'];
    }


    /*== Actualizar datos ==*/
    $actualizar_usuario=conexion();
    $actualizar_usuario=$actualizar_usuario->prepare("UPDATE USUARIOS SET username=:username,nombre=:nombre,apellido_pat=:apellido_pat,
    apellido_mat=:apellido_mat,contrasena=:contrasena,modified=:modified,modified_by=:modified_by WHERE id_usuario=:id");
//gtm-6
    $marcadores_usuario=[
        ":nombre"=>$nombre,
        ":apellido_pat"=>$apellido_pat,
        ":apellido_mat"=>$apellido_mat,
        ":username"=>$username,
        ":contrasena"=>$contrasena,
        ":id"=>$id,
        ":modified"=>gmdate("Y-m-d H:i:s",time()-18000),
        ":modified_by"=>$_SESSION['id']
    ];

    if($actualizar_usuario->execute($marcadores_usuario)){
        echo '
            <div class="notification is-info is-light">
                <strong>USUARIO ACTUALIZADO!</strong><br>
                El usuario se actualizo con exito
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
