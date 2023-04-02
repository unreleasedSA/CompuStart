<?php
    error_reporting( ~E_NOTICE ); // avoid notice

    require_once '../database/conexion.php';

    $consulta=$DB_con->prepare('SELECT categoria FROM categoria');
    $consulta->execute();
    $categorias=$consulta->fetchAll(PDO::FETCH_ASSOC);

    if (isset($_POST['id'])) {
        $id = $_POST['id'];

        $estado = $_POST["estado_categoria"];

        foreach ($categorias as $key => $a) {
            $categoria = "";
            if ($_POST['categoria'] === $a['categoria']) {
            session_start();
            $_SESSION["categoriaRepetida"] = "categoriarepetida";
            header('location:../admin/categoria.php');
            break;
            } else {
                $categoria = $_POST['categoria'];
                if (isset($categoria)){
                    $actualizar=$DB_con->prepare('UPDATE categoria SET categoria=:categoria, estado_categoria=:estado_categoria WHERE id_categoria=:id');
                    $actualizar->bindParam(':categoria', $categoria);
                    $actualizar->bindParam(':estado_categoria', $estado);
                    $actualizar->bindParam(':id', $id);

                    try {
                        if ($actualizar->execute()) {
                            session_start();
                            $_SESSION['actualizar_categoria'] = 'registro';
                            header("location: ../admin/categoria.php");
                            break;
                        } else {
                            session_start();
                            $_SESSION['error'] = 'registro';
                            header("location: ../admin/categoria.php");
                            break;  
                        }
                    } catch (\Throwable $th) {
                        session_start();
                        $_SESSION['actualizar_error'] = 'registro';
                        header("location: ../admin/categoria.php");
                        break;
                    }
                    break;
                    }
            }
        }
    }