<div class="container is-fluid mb-6">
    <h1 class="title">Movimientos</h1>
    <h2 class="subtitle">Actualizar movimiento</h2>
</div>
<div class="container pb-2 pt-2">
    <?php

    require_once "./php/main.php";

    $id = (isset($_GET['movimiento_id_up'])) ? $_GET['movimiento_id_up'] : 0;

    /*== Verificando producto ==*/
    $check_producto = conexion2();
    $check_producto = $check_producto->query("SELECT * FROM inventario a JOIN movimientos b ON a.id_inventario=b.id_inventario JOIN productos c 
												ON a.id_prod=c.id_prod where b.id_movimientos='$id'");

    if ($check_producto->rowCount() > 0) {
        $datos = $check_producto->fetch();
    ?>

        <div class="form-rest"></div>
        <?php
        if ($datos['foto'] != "") {
            echo '<center><img src="./img/producto/' . $datos['foto'] . '" class="image center" width="150px" height="auto" style="align-items: center;"></center>';
        } else {
            echo '<center><img src="./img/producto/producto.png" class="image center" width="150px" height="auto" style="align-items: center;"></center>';
        }
        ?>

        <form action="./php/movimiento_actualizar.php" method="POST" class="FormularioAjax" autocomplete="off">
            <div class="columns mt-5">
            <input type="hidden" name="id_movimiento" value="<?php echo $id ?>" required>
                <div class="column">
                    <div class="control mr-6">
                        <label>Cantidad</label>
                        <span>*</span>
                        <input class="input" type="text" name="cantidad" maxlength="45" value="<?php echo  $datos['cant_mov']; ?>" required>
                    </div>
                </div>
                <div class="column ml-6">
                    <div class="control">
                        <label>Fecha</label>
                        <span>*</span><br>
                        <input type="date" name="fecha" value=<?php echo $datos['fecha'] ?> require />
                    </div>
                </div>
                <div class="column">
                    <div class="control">
                        <labes>Motivo</labes>
                        <span>*</span>
                        <textarea class="textarea" name="motivo" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ().,$#\-\/ ]{1,500}" maxlength="500" required> <?php echo htmlspecialchars($datos['motivo']);?></textarea>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="control">
                    <label>Tipo de Movimiento</label>
                    <span>*</span><br>
                    <div class="select is-rounded">
                        <select name="tipo" require>
                            <option value="" selected="">Seleccione una opción</option>
                            <option value="1">Venta</option>
                            <option value="2">Ingreso</option>
                            <option value="3">Perdida</option>
                        </select>
                    </div>
                </div>
            </div>
            <p class="has-text-centered">
                <button type="submit" class="button is-info is-rounded">Guardar</button>
            </p>
        </form>
    <?php
    } else {
        include "./inc/error_alert.php";
    }
    $check_producto = null;
    include "./inc/btn_back.php";
    ?>
</div>