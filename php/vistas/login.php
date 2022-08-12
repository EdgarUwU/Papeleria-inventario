<div class="main-container">

	<form class="box login" action="" method="POST" autocomplete="off">
		<h5 class="title is-5 has-text-centered is-uppercase">Sistema de inventario</h5>
		<img class="logo-login" src="img/logo.jpg" alt="logo">

		<div class="field">
			<label class="label">Usuario</label>
			<div class="control">
			    <input class="input" type="text" name="username" pattern="[a-zA-Z0-9]{4,20}" maxlength="45" required >
			</div>
		</div>

		<div class="field">
		  	<label class="label">Contraseña</label>
		  	<div class="control">
		    	<input class="input" type="password" name="contrasena" pattern="[a-zA-Z0-9$@.-]{7,100}" maxlength="100" required >
		  	</div>
		</div>

		<p class="has-text-centered mb-4 mt-3">
			<button type="submit" class="button is-info is-rounded">Iniciar sesion</button>
		</p>

		<?php
			if(isset($_POST['username']) && isset($_POST['contrasena'])){
				require_once "./php/main.php";
				require_once "./php/iniciar_sesion.php";
			}
		?>
	</form>
</div>