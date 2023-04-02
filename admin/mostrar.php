<?php
session_start();
require_once '../database/conexion.php';

$consulta1=$DB_con->prepare('SELECT * FROM producto'); 
$consulta1->execute();
$productos=$consulta1->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="keywords" content="tailwind,tailwindcss,tailwind css,css,starter template,free template,admin templates, admin template, admin dashboard, free tailwind templates, tailwind example">
    <!-- Css -->
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="../css/card.css">
    <link rel="stylesheet" href="../css/style.css">
            <!-- iconos en fontawesome -->
            <script src="https://kit.fontawesome.com/4b93f520b2.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i,700,700i" rel="stylesheet">
    <link rel="icon" type="image/x-icon" href="../img/logo/icono.png">
    <title>Compu_Start: Vista Previa</title>
</head>

<body>
    
<!--Container -->
<div class="mx-auto bg-grey-400">
    <!--Screen-->
    <div class="min-h-screen flex flex-col">
        <!--Header Section Starts Here-->
        <header class="bg-nav">
            <?php include("../componentes/headerAdmin.php") ?>
        </header>
        <!--/Header-->

        <div class="flex flex-1">
            <!--Sidebar-->
            <aside id="sidebar" class="bg-side-nav w-1/2 md:w-1/6 lg:w-1/6 border-r border-side-nav hidden md:block lg:block">
<!--Barra lateral-->
                <ul class="list-reset flex flex-col">
                    <?php include("../componentes/barralateralAdmin.php") ?>
                </ul>
            </aside>

            <!--Main-->
            <main class="bg-white-300 flex-1 p-3 overflow-hidden">
                <!-- información -->
                <div class="flex flex-col">
                    <!-- Stats Row Starts Here -->
                    <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                        <div class="container">
                            <?php
                                $limite = 6;
                                $principio = 3;
                                $numero=1;
                                foreach ($productos as $key => $producto) {
                                    $consultaM = $DB_con->prepare('SELECT * FROM marca WHERE id_marca=:id');
                                    $consultaM->bindParam(":id", $producto["id_marca"]);
                                    $consultaM->execute();
                                    $marca = $consultaM->fetch(PDO::FETCH_ASSOC);
                                    if ($producto["estado_producto"]==0 || $marca["estado_marca"]==0) {
                                        $principio++;
                                        $limite++;
                                        continue;
                                    } else {
                            ?>
                            <!-- card 1 -->
                            <div class="card">
                                <figure>
                                    <?php //Este script sirve para poner solo la primera imagen
                                        $consulta2 = $DB_con->prepare('SELECT * FROM imagenes WHERE producto_id=:id');
                                        $consulta2->bindParam(":id", $producto["id_producto"]);
                                        $consulta2->execute();
                                        $imagenes = $consulta2->fetchAll(PDO::FETCH_ASSOC);
                                    ?>
                                    <img src="../imagenes/<?php echo $imagenes[0]['url'] ?>" height="200px" class="card-img-top" alt="...">
                                </figure>
                                <div class="contenido">
                                    <h5 class="card-title"><?php echo $producto['producto'] ?></h5>
                                    <p><?php echo $producto['descripcion_breve'] ?></p>
                                    <a href="">Agregar</a>
                                    <a href="" class="ver">Ver mas</a>
                                </div>
                            </div>
                            <?php
                                    if ($numero%3==0) {
                                        break;
                                        }else {
                                            $numero++; 
                                        }
                                    }
                                }
                            ?>
                        </div>
                    </div>                   
                </div>

                <!-- Card Section Starts Here -->
                <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                    <div class="rounded overflow-hidden shadow bg-white mx-2 w-full">
                        <!-- carrusel -->
                        <div class="color">
                            <div class="loader">
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                                    <span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col">
                    <!-- Stats Row Starts Here -->
                    <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                        <div class="container">
                        <?php
                        for ($i = $principio; $i < $limite; $i++) {
                            $consultaM = $DB_con->prepare('SELECT * FROM marca WHERE id_marca=:id');
                            $consultaM->bindParam(":id", $productos[$i]["id_marca"]);
                            $consultaM->execute();
                            $marca = $consultaM->fetch(PDO::FETCH_ASSOC);
                            if ($productos[$i]["estado_producto"]==0 || $marca["estado_marca"]==0) {
                                $limite++;
                                continue;
                            } else {
                        ?>
                            <!-- card 1 -->
                            <div class="card">
                                <figure>
                                    <?php //Este script sirve para poner solo la primera imagen
                                        $consulta2 = $DB_con->prepare('SELECT * FROM imagenes WHERE producto_id=:id');
                                        $consulta2->bindParam(":id", $productos[$i]["id_producto"]);
                                        $consulta2->execute();
                                        $imagenes = $consulta2->fetchAll(PDO::FETCH_ASSOC);
                                    ?>
                                    <img src="../imagenes/<?php echo $imagenes[0]['url'] ?>" height="200px" class="card-img-top" alt="...">
                                </figure>
                                <div class="contenido">
                                    <h5 class="card-title"><?php echo $productos[$i]['producto'] ?></h5>
                                    <p><?php echo $productos[$i]['descripcion_breve'] ?></p>
                                    <a href="" class="ver">Ver mas</a>
                                </div>
                            </div>
                            <?php
                                }
                            }
                            ?>
                        </div>
                    </div>                   
                </div>

                <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                    <div class="rounded overflow-hidden shadow bg-white mx-2 w-full">
                        <!-- efecto visual 2 -->
                        <div class="color">
                            <div class="loader">
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                                <span></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!--Profile Tabs-->
                <div class="flex flex-col">
                    <!-- Stats Row Starts Here -->
                    <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                        <div class="container">
                        <?php
                        $limite2 = $limite + 3;
                        for ($i = $limite; $i < $limite2; $i++) {
                            $consultaM = $DB_con->prepare('SELECT * FROM marca WHERE id_marca=:id');
                            $consultaM->bindParam(":id", $productos[$i]["id_marca"]);
                            $consultaM->execute();
                            $marca = $consultaM->fetch(PDO::FETCH_ASSOC);
                            if ($productos[$i]["estado_producto"]==0 || $marca["estado_marca"]==0) {
                                $limite2++;
                                continue;
                            } else {
                        ?>
                            <!-- card 1 -->
                            <div class="card">
                                <figure>
                                    <?php //Este script sirve para poner solo la primera imagen
                                        $consulta2 = $DB_con->prepare('SELECT * FROM imagenes WHERE producto_id=:id');
                                        $consulta2->bindParam(":id", $productos[$i]["id_producto"]);
                                        $consulta2->execute();
                                        $imagenes = $consulta2->fetchAll(PDO::FETCH_ASSOC);
                                    ?>
                                    <img src="../imagenes/<?php echo $imagenes[0]['url'] ?>" height="200px" class="card-img-top" alt="...">
                                </figure>
                                <div class="contenido">
                                    <h5 class="card-title"><?php echo $productos[$i]['producto'] ?></h5>
                                    <p><?php echo $productos[$i]['descripcion_breve'] ?></p>
                                    <a href="" class="ver">Ver mas</a>
                                </div>
                            </div>
                        <?php
                            }
                        }
                        ?>
                        </div>
                    </div>                   
                </div>

                <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                    <div class="rounded overflow-hidden shadow bg-white mx-2 w-full">
                        <!-- efecto visual 2 -->
                        <section class=" mb-3">
                            <img src="../img/scroll/gabinete1.jpg" alt="">
                            <img src="../img/scroll/gabinete2.jpg"alt="">
                            <img src="../img/scroll/gabinete3.jpg"alt="">
                            <img src="../img/scroll/gabinete4.jpg" alt="">
                            <img src="../img/scroll/gabinete5.jpg" alt="">
                            <img src="../img/scroll/gabinete6.jpg" alt="">
                            <img src="../img/scroll/gabinete7.jpg" alt="">
                            <img src="../img/scroll/gabinete8.jpg" alt="">
                        </section>
                    </div>
                </div>

                <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                    <div class="rounded overflow-hidden shadow bg-white mx-2 w-full">
                        <!-- efecto visual 2 -->
                        <div class="fondo">
                            <div class="slider">
                                <span style="--i:1"><img src="../img/pruebas/1.jpg" alt=""></span>
                                <span style="--i:2"><img src="../img/pruebas/2.jpg" alt=""></span>
                                <span style="--i:3"><img src="../img/pruebas/3.webp" alt=""></span>
                                <span style="--i:4"><img src="../img/pruebas/4.jpg" alt=""></span>
                                <span style="--i:5"><img src="../img/pruebas/5.jpg" alt=""></span>
                                <span style="--i:6"><img src="../img/pruebas/6.jpg" alt=""></span>
                                <span style="--i:7"><img src="../img/pruebas/7.jpg" alt=""></span>
                                <span style="--i:8"><img src="../img/pruebas/8.jpg" alt=""></span>
                            </div>
                        </div>
                    </div>
                </div>

                </div>
            </main>
        </div>
    </div>
</div>

<script src="../js/main.js"></script>

</body>
</html>
