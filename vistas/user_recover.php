<div class="container is-fluid mb-6">
    <h1 class="title">Recuperar Usuarios</h1>
    <h2 class="subtitle">Lista de usuarios eliminados</h2>
</div>

<div class="container pb-6">
    <?php
        require_once "./php/main.php";

        if(isset($_POST['modulo_buscador'])){
            require_once "./php/buscador.php";
        }

        if(!isset($_SESSION['busqueda_usuario_recover']) && empty($_SESSION['busqueda_usuario_recover'])){
    ?>
    <div class="columns">
        <div class="column">
            <form action="" method="POST" autocomplete="off" >
                <input type="hidden" name="modulo_buscador" value="usuario_recover">   
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
                <input type="hidden" name="modulo_buscador" value="usuario_recover"> 
                <input type="hidden" name="eliminar_buscador" value="usuario_recover">
                <p>Estas buscando <strong>“<?php echo $_SESSION['busqueda_usuario_recover']; ?>”</strong></p>
                <br>
                <button type="submit" class="button is-danger is-rounded">Eliminar busqueda</button>
            </form>
        </div>
    </div>
    <?php
            # Eliminar usuario #
            if(isset($_GET['user_id_del'])){
                require_once "./php/usuario_perma.php";
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
            $url="index.php?vista=user_recover&page="; /* <== */
            $registros=8;
            $busqueda=$_SESSION['busqueda_usuario_recover']; /* <== */

            # Paginador usuario #
            require_once "./php/usuario_recover_list.php";
        } 
    ?>
</div>

<div class="container pt-4">  
    <?php
        require_once "./php/main.php";

        # Eliminar usuario #
        if(isset($_GET['user_id_del'])){
            require_once "./php/usuario_perma.php";
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
        $url="index.php?vista=user_recover&page=";
        $registros=8;
        $busqueda="";

        # Paginador usuario #
        require_once "./php/usuario_recover_list.php";
    ?>
</div>