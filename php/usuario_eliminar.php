<?php

	/*== Almacenando datos ==*/
    $user_id_del=limpiar_cadena($_GET['user_id_del']);

    /*== Verificando usuario ==*/
    $check_usuario=conexion2();
    $check_usuario=$check_usuario->query("SELECT id_usuario FROM USUARIOS WHERE id_usuario='$user_id_del'");
    
    if($check_usuario->rowCount()==1){

    	
    		
	    	$eliminar_usuario=conexion();
	    	$eliminar_usuario=$eliminar_usuario->prepare("UPDATE USUARIOS SET deleted=:deleted WHERE id_usuario=:id");
			$marcadores=[
				":id"=>$user_id_del,
				":deleted"=>"1"
			];

	    	$eliminar_usuario->execute(($marcadores));

	    	if($eliminar_usuario->rowCount()==1){
		        echo '
		            <div class="notification is-info is-light">
		                <strong>¡USUARIO ELIMINADO!</strong><br>
		                Los datos del usuario se eliminaron con exito
		            </div>
		        ';
		    }else{
		        echo '
		            <div class="notification is-danger is-light">
		                <strong>¡Ocurrio un error inesperado!</strong><br>
		                No se pudo eliminar el usuario, por favor intente nuevamente
		            </div>
		        ';
		    }
		    $eliminar_usuario=null;
    }else{
        echo '
            <div class="notification is-danger is-light">
                <strong>¡Ocurrio un error inesperado!</strong><br>
                El USUARIO que intenta eliminar no existe
            </div>
        ';
    }
    $check_usuario=null;