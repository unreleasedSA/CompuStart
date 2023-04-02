<?php
    //Para poder usar la clase Database y su función connect
    require('../database/basededatos.php');

    //Creamos un objeto del tipo Database
    $db = new Database();
    $connection = $db->connect(); //Creamos la conexión a la BD

    // Cuando la conexión está establecida...
    $consulta = $connection->prepare("SELECT * FROM proveedor"); // Traduzco mi petición
    $consulta->execute(); //Ejecuto mi petición

    $proveedores = $consulta->fetchAll(PDO::FETCH_ASSOC); //Me traigo los datos que necesito

    $nombre = $_POST['nombre'];
    $apellido = $_POST["apellido"];
    $direccion = $_POST['direccion'];
    $estado=1;

    foreach ($proveedores as $key => $provee) {
        $proveedor = "";
        $nit = "";
        $correo = "";
        $telefono = "";
        $web = "";
        if ($_POST['proveedor'] == $provee['proveedor']) {
            session_start();
            $_SESSION["nombreproveedor"] = "proveedor repetido";
            header('location:../admin/proveedor.php');
            break;
        }elseif ($_POST['nit'] == $provee['nit']) {
                session_start();
                $_SESSION["nitRepetido"] = "nit repetido";
                header('location:../admin/proveedor.php');
                break;
        }elseif ($_POST['correo'] == $provee['correo']) {
                session_start();
                $_SESSION["correotRepetido"] = "correo repetido";
                header('location:../admin/proveedor.php');
                break;
        }elseif ($_POST['telefono'] == $provee['telefono']) {
                        session_start();
                        $_SESSION["telefonotRepetido"] = "telefono repetido";
                        header('location:../admin/proveedor.php');
                        break;
        }elseif ($_POST['direccion_web'] == $provee['direccion_web']) {
                            session_start();
                            $_SESSION["correotRepetido"] = "correo repetido";
                            header('location:../admin/proveedor.php');
                            break;
        } else {
                $proveedor = $_POST['proveedor'];
                $nit = $_POST["nit"];
                $correo = $_POST['correo'];
                $telefono = $_POST['telefono'];
                $web = $_POST['direccion_web'];
                if (isset($proveedor) and isset($nit) and isset($correo) and isset($telefono) and isset($direccion)) {
                    $query = $connection->prepare("INSERT INTO proveedor(proveedor, nombre, apellido, nit, correo, telefono,  direccion_web, direccion, estado_proveedor) VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?)");// Traduzco mi petición
                    $guardar = $query->execute([$proveedor, $nombre, $apellido, $nit, $correo, $telefono, $web, $direccion, $estado]); //Ejecuto mi petición

                    try {
                        if ($guardar) {
                            session_start();
                            $_SESSION['proveedorExitoso'] = 'registro';
                            header("location: ../admin/proveedor.php");
                            break;
                        } else {
                            session_start();
                            $_SESSION['proveedor_error'] = 'guardad';
                            header("location: ../admin/proveedor.php");
                            break;
                        }
                        
                    } catch (\Throwable $th) {
                        session_start();
                        $_SESSION["proveedorRepetido"] = "proveedor repetida";
                        header('location:../admin/proveedor.php');
                        break;
                    }
                    break;
                }
            }
        }
