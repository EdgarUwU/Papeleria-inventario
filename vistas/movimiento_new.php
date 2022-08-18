<div class="container is-fluid mb-6">
    <h1 class="title">Movimientos</h1>
    <h2 class="subtitle">Nuevo movimiento</h2>
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

    <div class="form-rest"></div>
    <?php
		if ($datos['foto'] != "") {
			echo '<center><img src="./img/producto/' . $datos['foto'] . '" class="image center" width="200px" height="auto" style="align-items: center;"></center>';
		} else {
			echo '<center><img src="./img/producto/producto.png" class="image center" width="200px" height="auto" style="align-items: center;"></center>';
		}
	?>

    <form action="./php/usuario_guardar.php" method="POST" class="FormularioAjax" autocomplete="off">
        <div class="columns">
            <div class="column">
                <div class="control">
                    <label>Cantidad</label>
                    <span>*</span>
                    <input class="input" type="text" name="nombre" maxlength="45" pattern="{1,500}" required>
                </div>
            </div>
            <div class="column ml-6">
                <div class="control">
                    <label>Fecha</label>
                    <span>*</span><br>
                    <input type="date" value=<?php echo date("Y-m-d")?> require />
                </div>
            </div>
            <div class="column ml-6">
				<div class="control">
					<labes>Descripción</labes>
					<span>*</span>
				  	<textarea class="textarea" name="producto_descripcion" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,500}" maxlength="100" required ></textarea>
				</div>
		  	</div>
        </div>
        <div class="column">
            <div class="control">
                <label>Tipo de Movimiento</label>
                <span>*</span><br>
                <div class="select is-rounded">
                    <select name="permiso_usuario" require>
                        <option value="" selected="">Seleccione una opción</option>
                        <option value="1">Venta</option>
                        <option value="2">Compra</option>
                        <option value="3">Merma</option>
                    </select>
                </div>
            </div>
        </div>
        <p class="has-text-centered">
            <button type="submit" class="button is-info is-rounded">Guardar</button>
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