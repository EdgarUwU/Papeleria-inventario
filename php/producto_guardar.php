<?php
require_once "../inc/session_start.php";

require_once "main.php";

/*== Almacenando datos ==*/
$nombre = limpiar_cadena($_POST['producto_nombre']);
$precio = limpiar_cadena($_POST['producto_precio']);
$marca = limpiar_cadena($_POST['producto_marca']);
$stock = limpiar_cadena($_POST['producto_stock']);
$descripcion = limpiar_cadena($_POST['producto_descripcion']);


/*== Verificando campos obligatorios ==*/
if ($nombre == "" || $precio == "" || $marca == "" || $stock == "" || $descripcion == "") {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No ha llenado todos los campos que son obligatorios
            </div>
        ';
    exit();
}


/*== Verificando integridad de los datos ==*/

if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $nombre)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
    exit();
}

if (verificar_datos("[0-9.]{1,25}", $precio)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRECIO no coincide con el formato solicitado
            </div>
        ';
    exit();
}
if (verificar_datos("[0-9.]{1,25}", $stock)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El stock no coincide con el formato solicitado
            </div>
        ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $marca)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La marca no coincide con el formato solicitado
            </div>
        ';
    exit();
}
if (verificar_datos("[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}", $descripcion)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El NOMBRE no coincide con el formato solicitado
            </div>
        ';
    exit();
}

/*== Verificando nombre ==*/
$check_nombre = conexion2();
$check_nombre = $check_nombre->query("SELECT nombre_prod FROM PRODUCTO WHERE nombre_prod='$nombre'");
if ($check_nombre->rowCount() > 0) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El producto ingresado ya se encuentra registrado, por favor elija otro
            </div>
        ';
    exit();
}
$check_nombre = null;



/* Directorios de imagenes */
$img_dir = '../img/producto/';


/*== Comprobando si se ha seleccionado una imagen ==*/
if ($_FILES['producto_foto']['name'] != "" && $_FILES['producto_foto']['size'] > 0) {
    /* Creando directorio de imagenes */
    if (!file_exists($img_dir)) {
        if (!mkdir($img_dir, 0777)) {
            echo '
                    <div class="notification is-danger is-light">
                        <strong>¡Ocurrio un error inesperado!</strong><br>
                        Error al crear el directorio de imagenes
                    </div>
                ';
            exit();
        }
    }

    /* Comprobando formato de las imagenes */
    if (mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/jpeg" && mime_content_type($_FILES['producto_foto']['tmp_name']) != "image/png") {
        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                La imagen que ha seleccionado es de un formato que no está permitido
	            </div>
	        ';
        exit();
    }


    /* Comprobando que la imagen no supere el peso permitido */
    if (($_FILES['producto_foto']['size'] / 1024) > 3072) {
        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                La imagen que ha seleccionado supera el límite de peso permitido
	            </div>
	        ';
        exit();
    }


    /* extencion de las imagenes */
    switch (mime_content_type($_FILES['producto_foto']['tmp_name'])) {
        case 'image/jpeg':
            $img_ext = ".jpg";
            break;
        case 'image/png':
            $img_ext = ".png";
            break;
    }

    /* Cambiando permisos al directorio */
    chmod($img_dir, 0777);

    /* Nombre de la imagen */
    $img_nombre = renombrar_fotos($nombre);

    /* Nombre final de la imagen */
    $foto = $img_nombre . $img_ext;

    /* Moviendo imagen al directorio */
    if (!move_uploaded_file($_FILES['producto_foto']['tmp_name'], $img_dir . $foto)) {
        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                No podemos subir la imagen al sistema en este momento, por favor intente nuevamente
	            </div>
	        ';
        exit();
    }
} else {
    $foto = "";
}


/*== Guardando datos ==*/
$guardar_producto = conexion();
$guardar_producto = $guardar_producto->prepare("INSERT INTO PRODUCTO (nombre_prod,marca,presentacion,precio,created_by,foto) 
                                                VALUES(:nombre,:marca,:presentacion,:precio,:created_by,:foto)");

$marcadores = [
    ":nombre" => $nombre,
    ":marca" => $marca,
    ":presentacion" => $descripcion,
    ":precio" => $precio,
    ":foto" => $foto,
    ":created_by" => $_SESSION['id']
];
$guardar_producto->execute($marcadores);
$check_id_prod = conexion();
$check_id_prod = $check_id_prod->query("SELECT A.id_producto as 'id' FROM PRODUCTO A LEFT JOIN INVENTARIO B ON A.id_producto=B.id_producto WHERE A.nombre_prod='$nombre'");
$id_prod = $check_id_prod->fetch();

$guardarstock = conexion();
$guardarstock = $guardarstock->prepare("INSERT INTO INVENTARIO (stock,id_producto,create_by) 
                                        VALUES(:stock,:id_prod,:create_by)");
$marcadoresstock = [
    ":stock" => $stock,
    ":id_prod" => $id_prod['id'],
    ":create_by" => $_SESSION['id']
];
$guardarstock->execute($marcadoresstock);

if ($guardar_producto->rowCount() == 1) {
    echo '
            <div class="notification is-info is-light">
                <strong>¡PRODUCTO REGISTRADO!</strong><br>
                El producto se registro con exito
                <a href="index.php?vista=product_list"</a>
            </div>
        ';
} else {

    if (is_file($img_dir . $foto)) {
        chmod($img_dir . $foto, 0777);
        unlink($img_dir . $foto);
    }

    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No se pudo registrar el producto, por favor intente nuevamente
            </div>
        ';
}
$guardar_producto = null;
