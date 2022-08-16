<?php
    $id = (isset($_GET['user_id_up'])) ? $_GET['user_id_up'] : 0;
?>
<div class="container is-fluid mb-6">
	<?php if($id==$_SESSION['id']){ ?>
		<h1 class="title">Mi cuenta</h1>
		<h2 class="subtitle">Actualizar datos de cuenta</h2>
	<?php }else{ ?>
		<h1 class="title pt-4">Recuperar usuario</h1>
	<?php } ?>
</div>

<div class="container pb-6 pt-6">
	<?php

		require_once "./php/main.php";

        /*== Verificando usuario ==*/
    	$check_usuario=conexion2();
    	$check_usuario=$check_usuario->query("SELECT * FROM USUARIOS WHERE id_usuario='$id'");

        if($check_usuario->rowCount()>0){
        	$datos=$check_usuario->fetch();
	?>

	<div class="form-rest mb-6 mt-6"></div>

	<form action="./php/usuario_recover_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<input type="hidden" name="id_usuario" value="<?php echo $datos['id_usuario']; ?>" required >
		
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Nombres</label>
				  	<input class="input" type="text" name="nombre" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" required value="<?php echo $datos['nombre']; ?>" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Apellido Paterno</label>
				  	<input class="input" type="text" name="apellido_pat" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datos['apellido_pat']; ?>" >
				</div>
		  	</div>
			  <div class="column">
		    	<div class="control">
					<label>Apellido Materno</label>
				  	<input class="input" type="text" name="apellido_mat" pattern="[a-zA-ZáéíóúÁÉÍÓÚñÑ ]{3,40}" maxlength="40" value="<?php echo $datos['apellido_mat']; ?>" >
				</div>
		  	</div>
		</div>
		<div class="columns">
		  	<div class="column">
		    	<div class="control">
					<label>Usuario</label>
				  	<input class="input" type="text" name="username" pattern="[a-zA-Z0-9]{4,20}" maxlength="20" required value="<?php echo $datos['username']; ?>" >
				</div>
		  	</div>
		</div>
		<div class="columns">
			<div class="column">
		    	<div class="control">
					<label>Contraseña</label>
				  	<input class="input" type="password" name="contrasena" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
				</div>
		  	</div>
		  	<div class="column">
		    	<div class="control">
					<label>Repetir Contraseña</label>
				  	<input class="input" type="password" name="contrasena2" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" >
				</div>
		  	</div>
		</div>
		<br><br><br>
		<p class="has-text-centered">
			<button type="submit" class="button is-success is-rounded">Recuperar</button>
		</p>
	</form>
	<?php 
		}else{
			include "./inc/error_alert.php";
		}
		$check_usuario=null;
		include "./inc/btn_back.php";
	?>
</div>