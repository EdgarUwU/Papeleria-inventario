<div class="container is-fluid mb-6 pt-2">
	<h1 class="title">Productos</h1>
	<h2 class="subtitle">Actualizar producto</h2>
</div>

<div class="container pb-2 pt-2">
	<?php

		require_once "./php/main.php";

		$id = (isset($_GET['product_id_up'])) ? $_GET['product_id_up'] : 0;

		/*== Verificando producto ==*/
    	$check_producto=conexion2();
    	$check_producto=$check_producto->query("SELECT * FROM productos,inventario WHERE productos.id_prod='$id'");

        if($check_producto->rowCount()>0){
        	$datos=$check_producto->fetch();
	?>

	<div class="form-rest mb-2 mt-2"></div>
	
	<h2 class="title has-text-centered"><?php echo $datos['nombre_prod']; ?></h2>
	<?php
		if ($datos['foto'] != "") {
			echo '<center><img src="./img/producto/' . $datos['foto'] . '" class="image center" width="200px" height="auto" style="align-items: center;"></center>';
		} else {
			echo '<center><img src="./img/producto/producto.png" class="image center" width="200px" height="auto" style="align-items: center;"></center>';
		}
	?>

	<form action="./php/producto_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off" >

		<input type="hidden" name="id_producto" value="<?php echo $datos['id_prod']; ?>" required >

		<form action="./php/producto_guardar.php" method="POST" class="FormularioAjax" autocomplete="off" enctype="multipart/form-data" >
		<div class="columns">
			<div class="column">
				<div class="control">
					<label>Codigo</label>
					<span>*</span>
					<input class="input" type="text" name="producto_codigo" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo $datos['cod_bar']; ?>" required>
				</div>
			</div>
			<div class="column">
				<div class="control">
					<label>Nombre</label>
					<span>*</span>
					<input class="input" type="text" name="producto_nombre" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" value="<?php echo $datos['nombre_prod']; ?>" required>
				</div>
			</div>
		</div>
		<div class="columns">
			<div class="column">
				<div class="control">
					<label>Precio</label>
					<span>*</span>
					<input class="input" type="text" name="producto_precio" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo $datos['precio']; ?>" required>
				</div>
			</div>
			<div class="column">
				<div class="control">
					<label>Stock</label>
					<span>*</span>
					<input class="input" type="text" name="producto_stock" pattern="[0-9.]{1,25}" maxlength="25" value="<?php echo $datos['cantidad']; ?>" required>
				</div>
			</div>
			<div class="column">
				<div class="control">
					<label>Marca</label>
					<span>*</span>
					<input class="input" type="text" name="producto_marca" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" value="<?php echo $datos['marca']; ?>" required>
				</div>
			</div>
		</div>
		<div class="columns">
			<div class="column">
				<div class="control">
					<label>Descripción</label>
					<span>*</span>
					<input class="input" type="text" name="producto_descripcion" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,70}" maxlength="70" value="<?php echo $datos['descripcion']; ?>" required>
				</div>
			</div>
			<div class="column">
				<div class="control">
					<labes>Presentación</labes>
					<span>*</span>
					<textarea class="textarea" name="producto_presentacion" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,500}" maxlength="500"  required> <?php echo htmlspecialchars($datos['presentacion']);?></textarea>
				</div>
			</div>
		</div>
		<p class="has-text-centered pt-6">
			<button type="submit" class="button is-success is-rounded">Actualizar</button>
		</p>
	</form>
	<?php 
		}else{
			include "./inc/error_alert.php";
		}
		$check_producto=null;
	include "./inc/btn_back.php";
	?>
</div>