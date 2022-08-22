<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";


if (isset($busqueda) && $busqueda != "") {

	$consulta_datos = "SELECT * FROM productos a INNER JOIN inventario b ON a.id_prod=b.id_prod WHERE a.id_prod=b.id_prod  AND (a.created_by LIKE '%$busqueda%'
    OR a.nombre_prod LIKE '%$busqueda%' OR a.presentacion LIKE '%$busqueda%') 
    ORDER BY a.nombre_prod ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_prod) FROM productos WHERE nombre_prod LIKE '%$busqueda%' OR presentacion 
	LIKE '%$busqueda%' ";
} else {

	$consulta_datos = "SELECT * FROM productos a INNER JOIN inventario b ON a.id_prod=b.id_prod WHERE a.id_prod=b.id_prod
	ORDER BY a.nombre_prod ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_prod) FROM productos";
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
			$tabla .= '<a href="index.php?vista=movimientos_list_auditor&movimiento_id_aud=' . $rows['id_inventario'] . '">
			<img src="./img/producto/' . $rows['foto'] . '"/>
			</a>';
		} else {
			$tabla .= '<a href="index.php?vista=movimientos_list_auditor&movimiento_id_aud=' . $rows['id_inventario'] . '">
			<img src="./img/producto.png">
			</a>';
		}
		$datos_prod = conexion();
		$datos_prod = $datos_prod->query("SELECT * FROM productos WHERE id_prod='" . $rows['id_prod'] . "'");
		$datos_prod = $datos_prod->fetchAll();
		$datos_prod = $datos_prod[0];
		$estato = $datos_prod['deleted'];
        if ($estato == 1) {
            $estato = '<span class="tag is-danger">Eliminado</span>';
        } else {
            $estato = '<span class="tag is-success">Activo</span>';
        }
        if ($datos_prod['modify_date']=="") {
            $datos_prod['modify_date'] = "No se ha modificado";
        }
        $modified_by = $datos_prod['modify_by'];
		$tabla .= '</figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>' . $contador . ' - ' . $rows['nombre_prod'] .' ' . $estato . '</strong><br>
							<strong>Codigo:</strong> $' . $rows['cod_bar'] . ' 
			                <strong>Precio:</strong> $' . $rows['precio'] . ' 
							<strong>Marca:</strong> ' . $rows['marca'] . '
							<strong>Stock:</strong> ' . $rows['cantidad'] . '
							<strong>Descripción:</strong> ' . $rows['descripcion'] . '
							<strong>Presentación:</strong> ' . $rows['presentacion'] . '
                            <strong>Creado por:</strong> ' . $rows['created_by']. '<br>
                            <strong>Fecha de creación:</strong> ' . $datos_prod['created_date'] . '
                            <strong>Fecha de modificación:</strong> ' . $datos_prod['modify_date'] . '
                            <strong>Modificado por:</strong> ' . $modified_by . '

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