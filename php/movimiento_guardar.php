<?php
require_once "../inc/session_start.php";
require_once "./main.php";


/*== Almacenando datos ==*/

$cantidad = limpiar_cadena($_POST['cantidad']);
$fecha = limpiar_cadena($_POST['fecha']);
$tipo = limpiar_cadena($_POST['tipo']);
$created_by = $_SESSION['nombre']. " ". $_SESSION['apellido_pat']. " ". $_SESSION['apellido_mat'];
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
if (!verificar_fecha($fecha)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                La fecha no coincide con el formato solicitado
            </div>
        ';
    exit();
}