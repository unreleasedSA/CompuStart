<?php
    session_start();
    require('../database/basededatos.php');

    //Creamos un objeto del tipo Database
    $db = new Database();
    $connection = $db->connect(); //Creamos la conexión a la BD

    // Cuando la conexión está establecida...
    $consulta = $connection->prepare("SELECT *, PROVEEDOR.proveedor AS NombreProveedor, PRODUCTO.producto AS NombreProducto FROM compra INNER JOIN PRODUCTO ON compra.id_producto = PRODUCTO.id_producto INNER JOIN PROVEEDOR ON compra.id_proveedor = PROVEEDOR.id_proveedor"); // Traduzco mi petición
    $consulta->execute(); //Ejecuto mi petición

    $compras = $consulta->fetchAll(PDO::FETCH_ASSOC); //Me traigo los datos que necesito
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
    <!-- iconos en fontawesome -->
    <script src="https://kit.fontawesome.com/4b93f520b2.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i,700,700i" rel="stylesheet">
    <script src="../js/validaciones.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <link rel="icon" type="image/x-icon" href="../img/logo/icono.png">
    <title>Compu_Start: Total Compras</title>
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

            <!--/Sidebar-->
            <!--Main-->
            <main class="bg-white-300 flex-1 p-3 overflow-hidden">

            <div class="flex flex-col">
                        <!--Grid Form-->

                        <div class="flex flex-1  flex-col md:flex-row lg:flex-row mx-2">
                            <div class="mb-2 border-solid border-gray-300 rounded border shadow-sm w-full">
                                <div class="bg-gray-200 px-2 py-3 border-solid border-gray-200 border-b">
                                    Lista de Compras
                                </div>
                                <div class="p-3">
                                    <table class="table-responsive w-full rounded" id="dataTable">
                                        <thead>
                                            <tr>
                                                <th class="border w-1/1 px-4 py-2"># de Compra</th>
                                                <th class="border w-1/1 px-4 py-2">Proveedor</th>
                                                <th class="border w-1/1 px-4 py-2">Producto</th>
                                                <th class="border w-1/1 px-4 py-2">Cantidad</th>
                                                <th class="border w-1/1 px-4 py-2">Precio</th>
                                                <th class="border w-1/1 px-4 py-2">Total</th>
                                                <th class="border w-1/1 px-4 py-2">Fecha</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                                foreach ($compras as $key => $compras) {
                                            ?>
                                                    <tr>
                                                        <td class="border px-4 py-2">
                                                            <?php echo $compras["id_compra"] . "<br>"; ?></td>
                                                        <td class="border px-4 py-2">
                                                            <?php echo $compras["NombreProveedor"] . "<br>"; ?></td>
                                                        <td class="border  px-4 py-2">
                                                            <?php echo $compras["NombreProducto"] . "<br>"; ?></td>
                                                        <td class="border w-1/6 px-4 py-2">
                                                            <?php echo $compras["cantidad"] . "<br>"; ?></td>
                                                            <td class="border w-1/6 px-4 py-2">
                                                            $ <?php echo number_format($compras["precio"]) . "<br>"; ?></td>
                                                            <td class="border w-1/6 px-4 py-2">
                                                            $ <?php echo number_format($compras["total"]) . "<br>"; ?></td>
                                                        <td class="border w-1/6 px-4 py-2">
                                                            <?php echo $compras["fecha"] . "<br>"; ?></td>
                                                    </tr>
                                            <?php
                                                }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
            </main>
            <!--/Main-->
        </div>
    </div>

</div>
<script src="../js/main.js"></script>
</body>
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
            "url":"//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json"
        }
        });
    });
</script>

</html>
