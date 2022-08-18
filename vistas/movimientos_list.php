<div class="container is-fluid mb-6">
    <h1 class="title">Movimientos</h1>
    <h2 class="subtitle">Lista de movimientos</h2>
</div>
<div class="container pb-6">
    <?php
        require_once "./php/main.php";

        if(isset($_POST['modulo_buscador'])){
            require_once "./php/buscador.php";
        }

        if(!isset($_SESSION['movimiento_busqueda']) && empty($_SESSION['movimiento_busqueda'])){
    ?>
    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="movimiento">   
                <div class="field is-grouped">
                    <p class="control is-expanded">
                        <input class="input is-rounded" type="text" name="txt_buscador" placeholder="¿Qué estas buscando?" pattern="[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ ]{1,30}" maxlength="30" >
                    </p>
                    <p class="control">
                        <button class="button is-info" type="submit" >Buscar</button>
                    </p>
                </div>
            </form>
        </div>
    </div>
    <?php }else{ ?>
    <div class="columns">
        <div class="column">
            <form class="has-text-centered mt-6 mb-6" action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="movimiento"> 
                <input type="hidden" name="eliminar_buscador" value="movimiento">
                <p>Estas buscando <strong>“<?php echo $_SESSION['movimiento']; ?>”</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>
    <?php
            # Eliminar usuario #
            if(isset($_GET['movimiento_id_del'])){
                require_once "./php/movimiento_eliminar.php";
            }

            if(!isset($_GET['page'])){
                $pagina=1;
            }else{
                $pagina=(int) $_GET['page'];
                if($pagina<=1){
                    $pagina=1;
                }
            }

            $pagina=limpiar_cadena($pagina);
            $url="index.php?vista=movimientos_list&page="; /* <== */
            $registros=8;
            $busqueda=$_SESSION['busqueda_movimiento']; /* <== */

            # Paginador usuario #
            require_once "./php/movimientos_lista.php";
        } 
    ?>
</div>

<div class="container pt-4">  
    <?php
        require_once "./php/main.php";

        # Eliminar usuario #
        if(isset($_GET['user_id_del'])){
            require_once "./php/movimientos_eliminar.php";
        }

        if(!isset($_GET['page'])){
            $pagina=1;
        }else{
            $pagina=(int) $_GET['page'];
            if($pagina<=1){
                $pagina=1;
            }
        }

        $pagina=limpiar_cadena($pagina);
        $url="index.php?vista=movimientos_list&page=";
        $registros=8;
        $busqueda="";

        # Paginador usuario #
        require_once "./php/movimientos_lista.php";
    ?>
</div>