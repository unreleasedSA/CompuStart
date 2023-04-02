<?php
    session_start();

    require('../database/basededatos.php');
    error_reporting();

    //Creamos un objeto del tipo Database
    $db = new Database();
    $connection = $db->connect(); //Creamos la conexión a la BD

    $id = $_GET["id"];

    $consulta = $connection->prepare("SELECT id_detalle_orden, PRODUCTO.producto AS NombreProducto, cantidad_venta, precio_producto, monto_total FROM detalle_orden INNER JOIN PRODUCTO ON detalle_orden.id_producto = PRODUCTO.id_producto WHERE id_orden=:id"); // Traduzco mi petición
    $consulta->execute(['id' => $id]); //Ejecuto mi petición

    $detalles = $consulta->fetchAll(PDO::FETCH_ASSOC); //Me traigo los datos que necesito

    // Cuando la conexión está establecida...
    $consulta2 = $connection->prepare("SELECT id_orden, cliente, CONCAT(cliente.nombre, ' ', cliente.apellido) AS nombre, cliente.direccion AS direccion,  cliente.telefono AS telefono, total, orden.estado FROM orden INNER JOIN cliente ON orden.cliente = cliente.id WHERE id_orden=:id");// Traduzco mi petición
    $consulta2->execute(['id' => $id]); //Ejecuto mi petición

    $orden = $consulta2->fetch(PDO::FETCH_ASSOC);

    // Cuando la conexión está establecida...
    $consulta3 = $connection->prepare("SELECT id_orden, detalle_orden.id_producto, producto.producto AS nombreProducto, cantidad_venta, precio_producto, monto_total, detalle_orden.estado FROM detalle_orden INNER JOIN producto ON detalle_orden.id_producto = producto.id_producto WHERE id_orden=:id");// Traduzco mi petición
    $consulta3->execute(['id' => $id]); //Ejecuto mi petición

    $informacion = $consulta3->fetchALL(PDO::FETCH_ASSOC);

    $consulta4 = $connection->prepare("SELECT orden.estado, condicion FROM orden WHERE id_orden=:id"); // Traduzco mi petición
    $consulta4->execute(['id' => $id]); //Ejecuto mi petición

    $boton = $consulta4->fetch(PDO::FETCH_ASSOC); //Me traigo los datos que necesito
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie'=edge">
    <meta name="keywords"
        content="tailwind,tailwindcss,tailwind css,css,starter template,free template,admin templates, admin template, admin dashboard, free tailwind templates, tailwind example">
    <!-- Css -->
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/all.css">
    <!-- iconos en fontawesome -->
    <script src="https://kit.fontawesome.com/4b93f520b2.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i,700,700i" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i,700,700i" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="icon" type="image/x-icon" href="../img/logo/icono.png">
    <title>Compu_Start: Detalle de Orden</title>  
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
                <aside id="sidebar"
                    class="bg-side-nav w-1/2 md:w-1/6 lg:w-1/6 border-r border-side-nav md:block lg:block">
                    <!--Barra lateral-->
                    <ul class="list-reset flex flex-col">
                        <?php include("../componentes/barralateralAdmin.php") ?>
                    </ul>
                </aside>

                <!--/Sidebar-->
                <!--Main-->
                <main class="bg-white-300 flex-1 p-3 overflow-hidden">
                    <!-- información -->
                    <div class="flex flex-col">
                        <div class="rounded overflow-hidden shadow bg-white mx-2 w-full">
                            <div class="px-2 py-3 border-b border-light-grey">
                                <div class="font-bold text-xl">Detalle de la venta del Cliente</div>
                            </div>
                            <div class="table-responsive">
                                <table class="table text-grey-darkest">
                                    <thead class="bg-grey-dark text-white text-normal">
                                        <tr>
                                            <th scope="col">Nombre del Producto</th>
                                            <th scope="col">Cantidad</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col">Monto Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                                foreach ($detalles as $key => $detalle) {
                                            ?>
                                        <tr>
                                            <td>
                                                <?php echo $detalle["NombreProducto"] . "<br>"; ?>
                                            </td>
                                            <td>
                                                <?php echo $detalle["cantidad_venta"] . "<br>"; ?></td>
                                            <td>
                                                $ <?php echo number_format($detalle["precio_producto"],2) . "<br>"; ?>
                                            </td>
                                            <td>
                                                $ <?php echo number_format($detalle["monto_total"],2) . "<br>"; ?>
                                            </td>
                                        </tr>
                                        <?php
                                                }
                                            ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="flex flex-1  flex-col md:flex-row lg:flex-row mx-2">
                        <div class="mb-2 border-solid border-gray-300 rounded border shadow-sm w-full">
                            <div class="bg-gray-200 px-2 py-3 border-solid border-gray-200 border-b">
                                Información
                            </div>
                                <div class="p-3">
                                    <div class="flex flex-wrap -mx-3 mb-2">
                                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                                            <div class="relative">
                                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-1" for="grid-last-name">
                                                    Nombre
                                                </label>
                                            </div>
                                            <div class="px-6 py-2 border-b border-light-grey">
                                                <div class="font-bold"><?php echo $orden['nombre']?></div>
                                            </div>
                                            <div class="relative">
                                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-1" for="grid-last-name">
                                                    Dirección
                                                </label>
                                            </div>
                                            <div class="px-6 py-2 border-b border-light-grey">
                                                <div class="font-bold"><?php echo $orden['direccion']?></div>
                                            </div>
                                            <div class="relative">
                                                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-1" for="grid-last-name">
                                                    Teléfono
                                                </label>
                                            </div>
                                            <div class="px-6 py-2 border-b border-light-grey">
                                                <div class="font-bold"><?php echo $orden['telefono']?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                        </div>
                    </div>
                    <br>
                    <div class="flex flex-1  flex-col md:flex-row lg:flex-row mx-2">
                        <div class="mb-2 border-solid border-gray-300 rounded border shadow-sm w-full">
                            <div class="bg-gray-200 px-2 py-3 border-solid border-gray-200 border-b">
                                Autorizar Venta
                            </div>
                            <div class="p-3">
                                <form class="w-full" action="./cambiodeestadodepedidos.php" method="post">
                                    <div class="flex flex-wrap -mx-3 mb-2">
                                        <div class="w-full md:w-1/3 px-3 mb-6 md:mb-0">
                                            <div class="px-6 py-2 border-b border-light-grey">
                                                <div class="font-bold text-xl">Estado</div>
                                            </div>
                                            <div class="relative">
                                            <?php
                                                if ($boton['estado'] == 1 and $boton['condicion'] == 0){
                                            ?>
                                                    <select name="estado" class="block appearance-none w-full bg-grey-200 border border-grey-200 text-grey-darker py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-grey" id="grid-state" required>
                                                        <option value="" selected>Seleccione una opción</option>
                                                        <option value="0">Aprobado</option>
                                                        <option value="1">Cancelar</option>
                                                    </select>
                                            <?php
                                                }elseif ($boton['estado'] == 0 and $boton['condicion'] == 0) {
                                            ?>
                                                    <label
                                                        class="block uppercase tracking-wide text-gray-700 text-xs font-light mb-1"
                                                        for="grid-last-name">
                                                        Comprado
                                                    </label>
                                            <?php
                                                }elseif ($boton['condicion'] == 1) {
                                            ?>
                                                    <label
                                                        class="block uppercase tracking-wide text-gray-700 text-xs font-light mb-1"
                                                        for="grid-last-name">
                                                        Cancelado
                                                    </label>
                                            <?php
                                                }
                                            ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-5">
                                        <?php
                                            if ($boton['estado'] == 1 and $boton['condicion'] == 0){
                                        ?>
                                            <input type="text" name="id_orden" id="" value="<?php echo $orden["id_orden"] ?>" hidden>
                                            <button
                                                class='bg-green-500 hover:bg-green-800 text-white font-bold py-2 px-4 rounded'>Actualizar</button>
                                            <button
                                                class='close-modal cursor-pointer bg-red-200 hover:bg-red-500 text-red-900 font-bold py-2 px-4 rounded'
                                                type="button"> <a href="./otros.php">Volver</a>
                                            </button>
                                        <?php
                                            }elseif ($boton['estado'] == 0 and $boton['condicion'] == 0) {
                                        ?>
                                                <button
                                                    class='close-modal cursor-pointer bg-red-200 hover:bg-red-500 text-red-900 font-bold py-2 px-4 rounded'
                                                    type="button"> <a href="./otros.php">Volver</a>
                                                </button>
                                        <?php
                                            }elseif ($boton['condicion'] == 1) {
                                        ?>
                                                <button
                                                    class='close-modal cursor-pointer bg-red-200 hover:bg-red-500 text-red-900 font-bold py-2 px-4 rounded'
                                                    type="button"> <a href="./otros.php">Volver</a>
                                                </button>
                                        <?php
                                            }
                                        ?>
                                    </div>
                                </form>
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

</html>