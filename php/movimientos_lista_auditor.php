<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";
$id = (isset($_GET['movimiento_id_aud'])) ? $_GET['movimiento_id_aud'] : 0;




if (isset($busqueda) && $busqueda != "") {

	$consulta_datos = "SELECT * FROM inventario a JOIN movimientos b ON a.id_inventario=b.id_inventario JOIN productos c ON a.id_prod=c.id_prod where a.id_inventario=b.id_inventario  AND (b.tipo 
	LIKE '%$busqueda%' OR b.cant_mov LIKE '%$busqueda%' OR b.motivo LIKE '%$busqueda%') ORDER BY b.tipo 
    ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_movimientos) FROM movimientos WHERE tipo LIKE '%$busqueda%' OR cant_mov LIKE '%$busqueda%' OR motivo LIKE '%$busqueda%'";
} else {

	$consulta_datos = "SELECT * FROM inventario a JOIN movimientos b ON a.id_inventario=b.id_inventario JOIN productos c ON a.id_prod=c.id_prod where a.id_inventario=b.id_inventario 
	ORDER BY fecha ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_movimientos) FROM movimientos";
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
		if (is_file("./img/producto/" . $rows['foto'])) {
			$tabla .= '<img src="./img/producto/' . $rows['foto'] . '">';
		} else {
			$tabla .= '<img src="./img/producto.png">';
		}
		$tabla .= '</p>
			        </figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>' . $contador . ' - ' . $rows['tipo'] .'</strong><br>
			                <strong>Cantidad:</strong> ' . $rows['cant_mov'] . ' 
							<strong>Fecha:</strong> ' . $rows['fecha'] . '
							<strong>Motivo:</strong> ' . $rows['motivo'] . '
							<strong>Producto:</strong> ' . $rows['nombre_prod'] . '
							<strong>Modificado por:</strong> ' . $rows['modify_by'] . '<br>
							<strong>Fecha de modificación:</strong> ' . $rows['modify_date'] . '
							<strong>Creado por:</strong> ' . $rows['created_by'] . '
							<strong>Fecha de creación:</strong> ' . $rows['created_date'] . '


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
	$tabla .= '<p class="has-text-right">Mostrando productos <strong>' . $pag_inicio . '</strong> al <strong>' . $pag_final . '</strong> de un <strong>total de ' . $total . '</strong></p>';
}

$conexion = null;
echo $tabla;

if ($total >= 1 && $pagina <= $Npaginas) {
	echo paginador_tablas($pagina, $Npaginas, $url, 7);
}
