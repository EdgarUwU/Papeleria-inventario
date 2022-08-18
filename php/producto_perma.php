<?php
	/*== Almacenando datos ==*/
    $product_id_del=limpiar_cadena($_GET['product_id_del']);

    /*== Verificando producto ==*/
    $check_producto=conexion2();
    $check_producto=$check_producto->query("SELECT id_prod FROM productos WHERE id_prod='$product_id_del'");

    if($check_producto->rowCount()==1){

    	$eliminar_producto=conexion();
		$eliminar_producto=$eliminar_producto->prepare("DELETE FROM productos WHERE id_prod= :id");
		$marcadores=[
			":id"=>$product_id_del
		];
		$eliminar_inventario=conexion();
		$eliminar_inventario=$eliminar_inventario->prepare("DELETE FROM inventario WHERE id_prod=:id");
		$marcadores=[
			":id"=>$product_id_del,
		];
		$eliminar_inventario->execute(($marcadores));
		$eliminar_producto->execute(($marcadores));


    	if($eliminar_producto->rowCount()==1){

	        echo '
	            <div class="notification is-info is-light">
	                <strong>¡PRODUCTO ELIMINADO!</strong><br>
	                Los datos del producto se eliminaron con exito
	            </div>
	        ';
	    }else{
	        echo '
	            <div class="notification is-danger is-light">
	                <strong>¡Ocurrio un error inesperado!</strong><br>
	                No se pudo eliminar el producto, por favor intente nuevamente
	            </div>
	        ';
	    }
	    $eliminar_producto=null;
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRODUCTO que intenta eliminar no existe
            </div>
        ';
    }
    $check_producto=null;