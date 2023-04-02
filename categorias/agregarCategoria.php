<?php
    error_reporting( ~E_NOTICE ); // avoid notice

	require_once '../database/conexion.php';

    $consulta=$DB_con->prepare('SELECT categoria FROM categoria');
    $consulta->execute();
    $categorias=$consulta->fetchAll(PDO::FETCH_ASSOC);

    $estado = 1;

    foreach ($categorias as $key => $nombre) {
        $categoria = "";
        if ($_POST['categoria'] === $nombre['categoria']) {
            session_start();
            $_SESSION["categoriaRepetida"] = "categoria repetida";
            header('location:../admin/categoria.php');
            break;
        } else {
            $categoria =$_POST["categoria"];
            if (isset($categoria)){
                $agregar=$DB_con->prepare('INSERT INTO categoria(categoria,estado_categoria) VALUES(:categoria, :estado_categoria)');
                $agregar->bindParam(':categoria', $categoria);
                $agregar->bindParam(':estado_categoria', $estado);

                try {
                    if ($agregar->execute()) {
                        session_start();
                        $_SESSION['categoria'] = 'registro';
                        header("location: ../admin/categoria.php");
                        break;
                    } else {
                        session_start();
                        $_SESSION['error'] = 'registro';
                        header("location: ../admin/categoria.php");
                        break;  
                    }
                } catch (\Throwable $td) {
                    session_start();
                    $_SESSION["categoriaRepetida"] = "categoria repetida";
                    header('location:../admin/categoria.php');
                    break;
                }
                break;
            }
            break;
        }
    }