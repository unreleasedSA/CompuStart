<?php
    error_reporting( ~E_NOTICE ); // avoid notice
	
	require_once '../database/conexion.php';

    $consulta=$DB_con->prepare('SELECT marca FROM marca');
    $consulta->execute();
    $marcas=$consulta->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $estado = $_POST["estado_marca"];

        foreach ($marcas as $key => $nombre) {
            $marca = "";
            if ($_POST['marca'] === $nombre['marca']) {
            session_start();
            $_SESSION["marcarepetidaActualizar"] = "marcarepetida";
            header('location:../admin/marca.php');
            break;
            } else {
                $marca = $_POST['marca'];
                if (isset($marca)){
                    $actualizar=$DB_con->prepare('UPDATE marca SET marca=:marca, estado_marca=:estado_marca WHERE id_marca=:id');
                    $actualizar->bindParam(':marca', $marca);
                    $actualizar->bindParam(':estado_marca', $estado);
                    $actualizar->bindParam(':id', $id);

                    try {
                        if ($actualizar->execute()) {
                            session_start();
                            $_SESSION['actualizar_marca'] = 'registro';
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
                        $_SESSION['actualizar_error'] = 'registro';
                        header("location: ../admin/marca.php");
                        break;
                    }
                    break;
                    }
            }
        }
    }