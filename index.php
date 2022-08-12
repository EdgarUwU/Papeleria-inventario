<?php require "./inc/session_start.php"; ?>
<!DOCTYPE html>
<html>
    <head>
        <?php include "./inc/head.php"; ?>
    </head>
    <body>
        <?php

            if(!isset($_GET['vista']) || $_GET['vista']==""){
                $_GET['vista']="login";
            }


            if(is_file("./vistas/".$_GET['vista'].".php") && $_GET['vista']!="login" && $_GET['vista']!="404"){

                /*== Cerrar sesion ==*/
                if((!isset($_SESSION['id']) || $_SESSION['id']=="") || (!isset($_SESSION['username']) || $_SESSION['username']=="")){
                    include "./vistas/logout.php";
                    exit();
                }
                if(isset($_SESSION['privilegios']) && $_SESSION['privilegios']=="Administrador"){
                    include "./inc/navbar_admin.php";
                    include "./vistas/".$_GET['vista'].".php";
                    include "./inc/script.php";
                }
                if(isset($_SESSION['privilegios']) && $_SESSION['privilegios']=="Normal"){
                    include "./inc/navbar.php";
                    include "./vistas/".$_GET['vista'].".php";
                    include "./inc/script.php";
                }
                if(isset($_SESSION['privilegios']) && $_SESSION['privilegios']=="Auditoria"){
                    include "./inc/navbar_auditor.php";
                    include "./vistas/".$_GET['vista'].".php";
                    include "./inc/script.php";
                }
                

            }else{
                if($_GET['vista']=="login"){
                    include "./vistas/login.php";
                }else{
                    include "./vistas/404.php";
                }
            }
        ?>
    </body>
</html>