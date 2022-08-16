<?php
$inicio = ($pagina > 0) ? (($pagina * $registros) - $registros) : 0;
$tabla = "";


if (isset($busqueda) && $busqueda != "") {

	$consulta_datos = "SELECT * FROM PRODUCTO,INVENTARIO, USUARIOS 
	where PRODUCTO.id_producto=INVENTARIO.id_producto AND (AND USUARIOS.id_usuario=PRODUCTO.created_by 
    OR PRODUCTO.nombre_prod LIKE '%$busqueda%' OR PRODUCTO.presentacion LIKE '%$busqueda%') 
    ORDER BY PRODUCTO.nombre_prod ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_producto) FROM PRODUCTO WHERE nombre_prod LIKE '%$busqueda%' OR presentacion 
	LIKE '%$busqueda%' ";
} else {

	$consulta_datos = "SELECT * FROM PRODUCTO,INVENTARIO,USUARIOS 
	where PRODUCTO.id_producto=INVENTARIO.id_producto AND USUARIOS.id_usuario=PRODUCTO.created_by 
	ORDER BY nombre_prod ASC LIMIT $inicio,$registros";

	$consulta_total = "SELECT COUNT(id_producto) FROM PRODUCTO ";
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
        $datos_prod = $conexion->query("SELECT PRODUCTO.deleted,PRODUCTO.created,PRODUCTO.modified,PRODUCTO.modified_by,
        USUARIOS.nombre,USUARIOS.apellido_pat,USUARIOS.apellido_mat FROM PRODUCTO,USUARIOS 
        WHERE PRODUCTO.created_by=USUARIOS.id_usuario 
        AND PRODUCTO.nombre_prod='" . $rows['nombre_prod'] . "'");
        $datos_prod = $datos_prod->fetchAll();
        $datos_prod = $datos_prod[0];
        $estato = $datos_prod['deleted'];
        if ($estato == 1) {
            $estato = '<span class="tag is-danger">Eliminado</span>';
        } else {
            $estato = '<span class="tag is-success">Activo</span>';
        }
        if ($datos_prod['modified']=="") {
            $datos_prod['modified'] = "No se ha modificado";
        }
        $modified_by = $datos_prod['modified_by'];
        if ($modified_by=="") {
            $modified_by= "No se ha modificado";
        }else{
            $modified_by = $datos_prod['nombre'] . " " . $datos_prod['apellido_pat'] . " " . $datos_prod['apellido_mat'];
        }
		$tabla .= '</figure>
			        <div class="media-content">
			            <div class="content">
			              <p>
			                <strong>' . $contador . ' - ' . $rows['nombre_prod'] .' ' . $estato . '</strong><br>
			                <strong>Precio:</strong> $' . $rows['precio'] . ' 
							<strong>Marca:</strong> ' . $rows['marca'] . '
							<strong>Stock:</strong> ' . $rows['stock'] . '
							<strong>Descripción:</strong> ' . $rows['presentacion'] . '
                            <strong>Creado por:</strong> ' . $rows['nombre'] . ' 
                            '. $rows['apellido_pat'] . ' ' . $rows['apellido_mat'] . '
                            <strong>Fecha de creación:</strong> ' . $datos_prod['created'] . '<br>
                            <strong>Fecha de modificación:</strong> ' . $datos_prod['modified'] . '
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