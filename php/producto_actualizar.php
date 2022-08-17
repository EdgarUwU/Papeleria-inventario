<?php
	require_once "main.php";
    require_once "../inc/session_start.php";

	/*== Almacenando id ==*/
    $id=limpiar_cadena($_POST['id_producto']);


    /*== Verificando producto ==*/
	$check_producto=conexion2();
	$check_producto=$check_producto->query("SELECT * FROM productos WHERE id_prod='$id'");

    if($check_producto->rowCount()<=0){
    	echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El producto no existe en el sistema
            </div>
        ';
        exit();
    }else{
    	$datos=$check_producto->fetch();
    }
    $check_producto=null;


    /*== Almacenando datos ==*/
	$nombre=limpiar_cadena($_POST['producto_nombre']);
	$precio=limpiar_cadena($_POST['producto_precio']);
	$marca=limpiar_cadena($_POST['producto_marca']);
    $stock=limpiar_cadena($_POST['producto_stock']);
	$descripcion=limpiar_cadena($_POST['producto_descripcion']);
    $codigo=limpiar_cadena($_POST['producto_codigo']);
    $presentacion=limpiar_cadena($_POST['producto_presentacion']);
    $modified_by=$_SESSION['nombre']." ".$_SESSION['apellido_pat']." ".$_SESSION['apellido_mat'];


	/*== Verificando campos obligatorios ==*/
    if($nombre=="" || $precio=="" || $marca=="" || $stock=="" || $descripcion=="" || $codigo=="" || $presentacion==""){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No ha llenado todos los campos que son obligatorios
            </div>
        ';
        exit();
    }


    /*== Verificando integridad de los datos ==*/

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$nombre)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[0-9.]{1,25}",$precio)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRECIO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    if(verificar_datos("[0-9.]{1,25}",$stock)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El stock no coincide con el formato solicitado
            </div>
        ';
        exit();
    }

    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$marca)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La marca no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$descripcion)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$codigo)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El CODIGO no coincide con el formato solicitado
            </div>
        ';
        exit();
    }
    if(verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}",$presentacion)){
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La presentacion no coincide con el formato solicitado
            </div>
        ';
        exit();
    }


    /*== Verificando nombre ==*/
    if($nombre!=$datos['nombre_prod']){
	    $check_nombre=conexion2();
	    $check_nombre=$check_nombre->query("SELECT nombre_prod FROM productos WHERE nombre_prod='$nombre' AND deleted='0'");
	    if($check_nombre->rowCount()>0){
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                El NOMBRE ingresado ya se encuentra registrado, por favor elija otro
	            </div>
	        ';
	        exit();
	    }
	    $check_nombre=null;
    }


    /*== Actualizando datos ==*/
    $actualizar_producto=conexion();
    $actualizar_producto=$actualizar_producto->prepare("UPDATE productos SET nombre_prod=:nombre,marca=:marca,precio=:precio,
                                                        presentacion=:presentacion,cod_bar=:codigo,modify_date=:modify_date,modify_by=:modify_by WHERE id_prod=:id");

    $marcadores=[
        ":nombre"=>$nombre,
        ":marca"=>$marca,
        ":precio"=>$precio,
        ":presentacion"=>$descripcion,
        ":codigo"=>$codigo,
        ":modify_date"=>gmdate("Y-m-d H:i:s",time()-18000),
        ":modify_by"=>$modified_by,
        ":id"=>$id
    ];


    if($actualizar_producto->execute($marcadores)){
    $actualizar_stock=conexion();
    $actualizar_stock=$actualizar_stock->prepare("UPDATE inventario SET cantidad=:stock,modify_date=:modify_date,
                                                 modify_by=:modify_by WHERE id_prod=:id");

    $marcadores_stock=[
        ":stock"=>$stock,
        ":modify_date"=>gmdate("Y-m-d H:i:s",time()-18000),
        ":modify_by"=>$modified_by,
        ":id"=>$id
    ];
    $actualizar_stock->execute($marcadores_stock);
        echo '
            <div class="notification is-info is-light">
                <strong>¡PRODUCTO ACTUALIZADO!</strong><br>
                El producto se actualizo con exito
            </div>
        ';
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo actualizar el producto, por favor intente nuevamente
            </div>
        ';
    }
    $actualizar_producto=null;