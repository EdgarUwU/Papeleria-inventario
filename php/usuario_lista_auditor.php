<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";

if (isset($busqueda) && $busqueda != "") {

	$consulta_datos = "SELECT * FROM usuario WHERE ((id_usuario!='" . $_SESSION['id'] . "') 
		AND (nombre LIKE '%$busqueda%' OR apellido_pat LIKE '%$busqueda%' OR privilegios LIKE '%$busqueda%' OR apellido_mat LIKE '%$busqueda%')) 
		ORDER BY nombre ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_usuario) FROM usuario WHERE ((id_usuario!='" . $_SESSION['id'] . "') 
	AND (nombre LIKE '%$busqueda%' OR apellido_pat LIKE '%$busqueda%' OR privilegio LIKE '%$busqueda%' 
	OR apellido_mat LIKE '%$busqueda%')) ORDER BY nombre ASC LIMIT $inicio,$registros";
} else {

	$consulta_datos = "SELECT * FROM usuario WHERE id_usuario!='" . $_SESSION['id'] . "' ORDER BY nombre ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_usuario) FROM usuario WHERE id_usuario!='" . $_SESSION['id'] . "'  AND deleted='0'";
}

$conexion = conexion2();

$datos = $conexion->query($consulta_datos);
$datos = $datos->fetchAll();

$total = $conexion->query($consulta_total);
$total = (int) $total->fetchColumn();

$Npaginas = ceil($total / $registros);

if ($total >= 1 && $pagina <= $Npaginas) {
	$contador = $inicio + 1;
	$pag_inicio = $inicio + 1;
	foreach ($datos as $rows) {
		$tabla .= '
				<article class="media">
				<figure class="media-left image is-128x128" >
				<img src="./img/user/user_default.jpg">';
		$last_login = $rows['last_login'];
        $estado = $rows['deleted'];
        $modificado = $rows['modify_date'];
        $modificado_por = $rows['modify_by'];
		$creado_por = $rows['created_by'];
		if ($last_login == "") {
			$last_login = "No ha iniciado sesión";
		}else{
			//fecha de ultimo login menos fecha actual
			$fecha_ultimo_login = date_create($last_login);
			$fecha_actual = gmdate("Y-m-d H:i:s", time()-18000);
			$fecha_actual = date_create($fecha_actual);
			$diferencia = date_diff($fecha_ultimo_login, $fecha_actual);
			$diferencia = $diferencia->format("%h horas %i minutos");
			$last_login = $diferencia;
		}
        if($modificado == ""){
            $modificado = "No se ha modificado";
        }
        if($estado == '0'){
            $estado = '<span class="tag is-success">Activo</span>';
        }else{
            $estado = '<span class="tag is-danger">Inactivo</span>';
        }
		$tabla .= '</p>
			        </figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>' . $contador . ' - ' . $rows['nombre'] . ' ' 
                            . $rows['apellido_pat'] . ' ' . $rows['apellido_mat'] . ' ' . $estado . '</strong><br>
			                <strong>Privilegio:</strong> ' . $rows['privilegios'] . ' 
                            <strong>Username:</strong> ' . $rows['nombre_usuario'] . '
                            <strong>Creado:</strong> ' . $rows['created_date'] . '
							<strong>Creado por:</strong> ' . $creado_por. '
                            <strong>Ultima modificación el:</strong> ' . $modificado . '<br>
                            <strong>Modificado por:</strong> ' . $modificado_por. '
                            <strong>Numero de inicio de sesión:</strong> ' . $rows['login_count'] . '
							<strong>Creado desde la ip:</strong> ' . $rows['ip'] . '
							<strong>Ultimo inicio de sesión:</strong> ' . $last_login . '

			              </p>
			            </div>
			        </div>
			    </article>

			    <hr>
            ';
		$contador++;
	}
	$pag_final = $contador - 1;
} else {
	if ($total >= 1) {
		$tabla .= '
				<p class="has-text-centered" >
					<a href="' . $url . '1" class="button is-link is-rounded is-small mt-4 mb-4">
						Haga clic aqui para recargar el listado
					</a>
				</p>
			';
	} else {
		$tabla .= '
				<p class="has-text-centered" >No hay registros en el sistema</p>
			';
	}
}

if ($total > 0 && $pagina <= $Npaginas) {
	$tabla .= '<p class="has-text-right">Mostrando usuarios <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
}

$conexion = null;
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
	echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
