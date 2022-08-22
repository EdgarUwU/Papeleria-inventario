<?php
require_once "../inc/session_start.php";
require_once "./main.php";


/*== Almacenando datos ==*/
$id = limpiar_cadena($_POST['id_prod']);
$cantidad = limpiar_cadena($_POST['cantidad']);
$fecha = limpiar_cadena($_POST['fecha']);
$tipo = limpiar_cadena($_POST['tipo']);
$created_by = $_SESSION['nombre'] . " " . $_SESSION['apellido_pat'] . " " . $_SESSION['apellido_mat'];
$modify_by=$_SESSION['nombre']." ".$_SESSION['apellido_pat']." ".$_SESSION['apellido_mat'];
$motivo = limpiar_cadena($_POST['motivo']);


/*== Verificando campos obligatorios ==*/
if ($cantidad == "" || $fecha == "" || $tipo == "" || $motivo == "") {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No ha llenado todos los campos que son obligatorios
            </div>
        ';
    exit();
}


/*== Verificando integridad de los datos ==*/

if (verificar_datos("[0-9]{1,25}", $cantidad)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La cantidad no coincide con el formato solicitado
            </div>
        ';
    exit();
}

//verificando que la fecha sea valida
if (verificar_datos("[0-9]{4}-[0-9]{2}-[0-9]{2}", $fecha)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La fecha no coincide con el formato solicitado
            </div>
        ';
    exit();
}


$check_id_inventario = conexion2();
$check_id_inventario = $check_id_inventario->query("SELECT id_inventario FROM inventario WHERE id_prod = '$id'");
$id_inventario = $check_id_inventario->fetch();
$id_inventario = $id_inventario['id_inventario'];


/*== Guardando datos ==*/
$guardar_movimiento = conexion();
$guardar_movimiento = $guardar_movimiento->prepare("INSERT INTO movimientos (cant_mov,fecha,tipo,motivo,id_inventario,created_by)
                                                VALUES(:cantidad,:fecha,:tipo,:motivo,:id_inventario,:created_by)");

$marcadores = [
    ':cantidad' => $cantidad,
    ':fecha' => $fecha.' '.gmdate("H:i:s",time()-18000),
    ':tipo' => $tipo,
    ':motivo' => $motivo,
    ':id_inventario' => $id_inventario,
    ':created_by' => $created_by,
];

//comparar si hay suficiente cantidad en el inventario
$check_cantidad = conexion2();
$check_cantidad = $check_cantidad->prepare("SELECT cantidad FROM inventario WHERE id_prod = '$id'");
$check_cantidad->execute();
$cantidad_inventario = $check_cantidad->fetch();

if (intval($cantidad) > $cantidad_inventario['cantidad'] && ($tipo =='1' || $tipo =='3')) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La cantidad solicitada es mayor a la cantidad en inventario
            </div>
        ';
    exit();
}else{
    $guardar_movimiento->execute($marcadores);
}
if ($tipo=="2") {
    $actualizar_inventario = conexion();
    $actualizar_inventario = $actualizar_inventario->prepare("UPDATE inventario SET cantidad = cantidad + :cantidad,modify_date=:modify_date,modify_by=:modify_by 
                                                            WHERE id_inventario = :id_inventario");
    $marcadores = [
        ':cantidad' => $cantidad,
        ':id_inventario' => $id_inventario,
        ":modify_date"=>gmdate("Y-m-d H:i:s",time()-18000),
        ":modify_by"=>$modify_by,
    ];
    $actualizar_inventario->execute($marcadores);
} elseif ($tipo=="1" || $tipo=="3") {
    $actualizar_inventario = conexion();
    $actualizar_inventario = $actualizar_inventario->prepare("UPDATE inventario SET cantidad = cantidad - :cantidad,modify_date=:modify_date,modify_by=:modify_by
                                                             WHERE id_inventario = :id_inventario");
    $marcadores = [
        ':cantidad' => $cantidad,
        ':id_inventario' => $id_inventario,
        ":modify_date"=>gmdate("Y-m-d H:i:s",time()-18000),
        ":modify_by"=>$modify_by,
    ];
    $actualizar_inventario->execute($marcadores);
}

if ($guardar_movimiento->rowCount() == 1) {
    echo '
            <div class="notification is-info is-light">
                <strong>!MOVIMIENTO REGISTRADO!</strong><br>
                El movimiento se registro con exito
                <META HTTP-EQUIV="REFRESH" CONTENT="1.2;URL=index.php?vista=product_list">
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
$guardar_movimiento = null;
