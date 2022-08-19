<?php
	/*== Almacenando datos ==*/
    $movimientos_id_del=limpiar_cadena($_GET['movimiento_id_del']);

    /*== Verificando producto ==*/
    $check_movimientos=conexion2();
    $check_movimientos=$check_movimientos->query("SELECT id_movimientos FROM movimientos WHERE id_movimientos='$movimientos_id_del'");

    if($check_movimientos->rowCount()==1){

    	$eliminar_movimientos=conexion();
		$eliminar_movimientos=$eliminar_movimientos->prepare("UPDATE movimientos SET deleted=:deleted WHERE id_movimientos=:id");
		$marcadores=[
			":id"=>$movimientos_id_del,
			":deleted"=>"1"
		];

		$eliminar_movimientos->execute(($marcadores));

    	if($eliminar_movimientos->rowCount()==1){

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
	    $eliminar_movimientos=null;
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El PRODUCTO que intenta eliminar no existe
            </div>
        ';
    }
    $check_movimientos=null;