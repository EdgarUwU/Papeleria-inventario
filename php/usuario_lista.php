<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";

if (isset($busqueda) && $busqueda != "") {

	$consulta_datos = "SELECT * FROM USUARIOS WHERE ((id_usuario!='" . $_SESSION['id'] . "') 
		AND deleted = '0' AND (nombre LIKE '%$busqueda%' OR apellido_pat LIKE '%$busqueda%' OR username LIKE '%$busqueda%' OR apellido_mat LIKE '%$busqueda%')) 
		ORDER BY nombre ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_usuario) FROM USUARIOS WHERE ((id_usuario!='" . $_SESSION['id'] . "') AND deleted = '0' AND (nombre LIKE '%$busqueda%' OR apellido_pat LIKE '%$busqueda%' OR username LIKE '%$busqueda%' OR apellido_mat LIKE '%$busqueda%'))";
} else {

	$consulta_datos = "SELECT * FROM USUARIOS WHERE id_usuario!='" . $_SESSION['id'] . "' AND deleted='0' ORDER BY nombre ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_usuario) FROM USUARIOS WHERE id_usuario!='" . $_SESSION['id'] . "'  AND deleted='0'";
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
			        <figure class="media-left">
			            <p class="image is-64x64">';
		if (is_file("./img/user/" . $rows['foto'])) {
			$tabla .= '<img src="./img/user/' . $rows['foto'] . '">';
		} else {
			$tabla .= '<img src="./img/user/user_default.jpg">';
		}
		$tabla .= '</p>
			        </figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>' . $contador . ' - ' . $rows['nombre'] . ' ' . $rows['apellido_pat'] . ' ' . $rows['apellido_mat'] . '</strong><br>
			                <strong>Privilegio:</strong> ' . $rows['privilegios'] . '

			              </p>
			            </div>
			            <div class="has-text-right">
			                <a href="index.php?vista=user_update&user_id_up='.$rows['id_usuario'].'" class="button is-success is-rounded is-small">Actualizar</a>
							<a href="'.$url.$pagina.'&user_id_del='.$rows['id_usuario'].'" class="button is-danger is-rounded is-small">Eliminar</a>
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
	$tabla .= '<p class="has-text-right">Mostrando productos <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
}

$conexion = null;
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
	echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
