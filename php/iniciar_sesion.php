<?php
/*== Almacenando datos ==*/
$username = limpiar_cadena($_POST['username']);
$contrasena = limpiar_cadena($_POST['contrasena']);


/*== Verificando campos obligatorios ==*/
if ($username == "" || $contrasena == "") {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                No has llenado todos los campos que son obligatorios
            </div>
        ';
    exit();
}


/*== Verificando integridad de los datos ==*/
if (verificar_datos("[a-zA-Z0-9]{4,20}", $username)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El username no coincide con el formato solicitado
            </div>
        ';
    exit();
}

if (verificar_datos("[a-zA-Z0-9$@.-]{7,100}", $contrasena)) {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                Las contraseñas no coinciden con el formato solicitado
            </div>
        ';
    exit();
}

if (getenv('HTTP_CLIENT_IP')) {
    $ip = getenv('HTTP_CLIENT_IP');
} elseif (getenv('HTTP_X_FORWARDED_FOR')) {
    $ip = getenv('HTTP_X_FORWARDED_FOR');
} elseif (getenv('HTTP_X_FORWARDED')) {
    $ip = getenv('HTTP_X_FORWARDED');
} elseif (getenv('HTTP_FORWARDED_FOR')) {
    $ip = getenv('HTTP_FORWARDED_FOR');
} elseif (getenv('HTTP_FORWARDED')) {
    $ip = getenv('HTTP_FORWARDED');
} else {
    $ip = $_SERVER['REMOTE_ADDR'];
}


$check_user = conexion2();
$check_user = $check_user->query("SELECT * FROM USUARIOS WHERE username = '$username'");
if ($check_user->rowCount() == 1) {

    $check_user = $check_user->fetch();

    if ($check_user['username'] == $username && password_verify($contrasena, $check_user['contrasena'])) {

        $_SESSION['id'] = $check_user['id_usuario'];
        $_SESSION['username'] = $check_user['username'];
        $_SESSION['nombre'] = $check_user['nombre'];
        $_SESSION['apellido_pat'] = $check_user['apellido_pat'];
        $_SESSION['apellido_mat'] = $check_user['apellido_mat'];
        $_SESSION['privilegios'] = $check_user['privilegios'];
        $_SESSION['ip'] = $ip;



        if (headers_sent()) {
            echo "<script> window.location.href='index.php?vista=home'; </script>";
        } else {
            header("Location: index.php?vista=home");
            $login_count=conexion();
            $login_count=$login_count->prepare("UPDATE USUARIOS set login_count=login_count+1,
                                                last_login=:last_login, last_ip=:ip where id_usuario=:id_usuario");
                $marcadores=[
                    ':id_usuario'=>$_SESSION['id'],
                    ':ip'=>$ip,
                    ':last_login'=>gmdate("Y-m-d H:i:s",time()-18000),
                ];
                $login_count->execute($marcadores);
        }
    } else {
        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                Usuario o contraseña incorrectos
	            </div>
	        ';
    }
} else {
    echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El usuario ingresado no existe
            </div>
        ';
}
$check_user = null;
