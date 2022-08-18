<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";


if (isset($busqueda) && $busqueda != "") {

	$consulta_datos = "SELECT * FROM productos,inventario 
	where productos.id_prod=inventario.id_prod AND (productos.deleted='0' OR productos.nombre_prod 
	LIKE '%$busqueda%' OR productos.presentacion LIKE '%$busqueda%' OR productos.marca LIKE '%$busqueda%') ORDER BY productos.nombre_prod ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_prod) FROM productos WHERE productos.deleted='0' 
	AND  nombre_prod LIKE '%$busqueda%' OR presentacion OR marca LIKE '%$busqueda%' OR descripcion LIKE '%$busqueda%' ORDER BY productos.nombre_prod ASC";"; 
	LIKE '%$busqueda%' ";
} else {

	$consulta_datos = "SELECT * FROM productos,inventario 
	where productos.id_prod=inventario.id_prod AND productos.deleted='0' 
	ORDER BY nombre_prod ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_prod) FROM productos WHERE productos.deleted='0'";
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
			        <figure class="media-left image is-128x128" >';
		if (is_file("./img/producto/" . $rows['foto'])) {
			$tabla .= '<img src="./img/producto/' . $rows['foto'] . '"/>';
		} else {
			$tabla .= '<img src="./img/producto.png">';
		}
		$tabla .= '</figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <h4>' . $contador . ' - ' . $rows['nombre_prod'] . '</h4><br>
			                <strong>Precio:</strong> $' . $rows['precio'] . ' 
							<strong>Marca:</strong> ' . $rows['marca'] . '
							<strong>Stock:</strong> ' . $rows['cantidad'] . '
							<strong>Descripción:</strong> ' . $rows['descripcion'] . '
							<strong>Presentación:</strong> ' . $rows['presentacion'] . '			              
							</p>
			            </div>
			            <div class="has-text-right">
			                <a href="index.php?vista=product_update&product_id_up=' . $rows['id_prod'] . '" class="button is-success is-rounded is-small">Actualizar</a>
			                <a href="' . $url . $pagina . '&product_id_del=' . $rows['id_prod'] . '" class="button is-danger is-rounded is-small">Eliminar</a>
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
