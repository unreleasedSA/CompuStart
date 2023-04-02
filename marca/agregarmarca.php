<?php
    error_reporting( ~E_NOTICE ); // avoid notice
	
	require_once '../database/conexion.php';

    $consulta=$DB_con->prepare('SELECT marca FROM marca');
    $consulta->execute();
    $marcas=$consulta->fetchAll(PDO::FETCH_ASSOC);

    $estado = 1;

    foreach ($marcas as $key => $nombre) {
        $marca = "";
        if ($_POST['marca'] === $nombre['marca']) {
            session_start();
            $_SESSION["marcarepetida"] = "marca repetida";
            header('location:../admin/marca.php');
            break;
        } else{
            $marca = $_POST['marca'];
            if (isset($marca)){
                $agregar=$DB_con->prepare('INSERT INTO marca(marca,estado_marca) VALUES(:marca, :estado_marca)');
                $agregar->bindParam(':marca', $marca);
                $agregar->bindParam(':estado_marca', $estado);

                try {
                    if ($agregar->execute()) {
                        session_start();
                        $_SESSION['agregar'] = 'registro';
                        header("location: ../admin/marca.php");
                        break;
                    } else {
                        session_start();
                        $_SESSION['error'] = 'registro';
                        header("location: ../admin/marca.php");
                        break;  
                    }
                } catch (\Throwable $th) {
                    session_start();
                    $_SESSION["marcarepetida"] = "marca repetida";
                    header('location:../admin/marca.php');
                    break;
                }
                break;
            }
        }
    }