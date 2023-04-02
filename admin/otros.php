<?php
    session_start();
    require('../database/basededatos.php');

    //Creamos un objeto del tipo Database
    $db = new Database();
    $connection = $db->connect(); //Creamos la conexión a la BD

    // Cuando la conexión está establecida...
    $consulta = $connection->prepare("SELECT id_orden, CONCAT(cliente.nombre, ' ', cliente.apellido) AS nombre, total, orden.estado, condicion FROM orden INNER JOIN cliente ON orden.cliente = cliente.id"); // Traduzco mi petición
    $consulta->execute(); //Ejecuto mi petición

    $ordenes = $consulta->fetchAll(PDO::FETCH_ASSOC); //Me traigo los datos que necesito
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Css -->
    <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../css/all.css">
    <link rel="stylesheet" href="./css/styleadmi.css">
    <!-- iconos en fontawesome -->
    <script src="https://kit.fontawesome.com/4b93f520b2.js" crossorigin="anonymous"></script>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,400i,600,600i,700,700i" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.25/css/jquery.dataTables.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.js"></script>
    <link rel="icon" type="image/x-icon" href="../img/logo/icono.png">
    <title>Compu_Start: Pedidos</title>
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
                    <div class="flex flex-1 flex-col md:flex-row lg:flex-row mx-2">
                        <div class="rounded overflow-hidden shadow bg-white mx-2 w-full">
                            <div class="px-6 py-2 border-b border-light-grey">
                                <div class="font-bold text-xl">Pedidos</div>
                            </div>
                            <div class="table-responsive">
                            <table class="table text-grey-darkest" id="dataTable">
                                    <thead class="bg-grey-dark text-white text-normal">
                                        <tr>
                                            <th scope="col" class="text-align: center"># de Orden</th>
                                            <th scope="col" class="text-align: center">Nombre del Cliente</th>
                                            <th scope="col" class="text-align: center">Valor Total</th>
                                            <th scope="col" class="text-align: center">Estado</th>
                                            <th scope="col" class="text-align: center">Detalles</th>
                                        </tr>
                                    </thead>
                                    <?php
                                        foreach ($ordenes as $key => $orden){
                                    ?>
                                    <tbody>
                                        <tr>
                                            <th scope="row" class="text-center">
                                                <?php echo $orden["id_orden"] . "<br>"; ?>
                                            </th>
                                            <td class="text-align: center">
                                                <?php echo $orden["nombre"] . "<br>"; ?>
                                            </td>
                                            <td class="text-align: center">
                                                $ <?php echo number_format($orden["total"],2) . "<br>"; ?>
                                            </td>
                                            <td class="text-align: center">
                                            <?php
                                                if ($orden['estado'] == 1 and $orden['condicion'] == 0) {
                                                    echo "En Proceso";
                                                } elseif ($orden['estado'] == 0 and $orden['condicion'] == 0) {
                                                    echo "Comprado";
                                                } elseif ($orden['condicion'] == 1) {
                                                    echo "Orden Cancelada";
                                                }
                                            ?>
                                            </td>
                                            <td class="text-center">
                                                <a class="bg-blue-800 cursor-pointer rounded p-1 mx-1 text-white" href="./detalleOrden.php?id=<?php echo $orden["id_orden"]; ?>">
                                                    <i class="fas fa-edit"></i></a>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <?php
                                                }
                                            ?>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
            <!--/Main-->
        </div>
    </div>

</div>
<script src="../js/main.js">
    
</script>
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
<?php 
if (isset($_SESSION['Aprobado'])) {
    echo "<script>
    Swal.fire({
        icon: 'success',
        title: 'Éxito',
        text: 'Orden Aprobada'
        });
    </script>";
    unset($_SESSION['Aprobado']);
}
if (isset($_SESSION['Cancelado'])) {
    echo "<script>
    Swal.fire({
        icon: 'info',
        title: 'Orden Cancelada',
        text: 'Esta Orden fue cancelada'
        });
    </script>";
    unset($_SESSION['Cancelado']);
}
if (isset($_SESSION['errorDeAprobar'])) {
    echo "<script>
    Swal.fire({
        icon: 'error',
        title: 'Éxito',
        text: 'Error con el cambio de estado de la orden'
        });
    </script>";
    unset($_SESSION['errorDeAprobar']);
}
?>