<?php
    session_start();
    require('../database/basededatos.php');
    include '../database/conexion.php';

    //Creamos un objeto del tipo Database
    $db = new Database();
    $connection = $db->connect(); //Creamos la conexión a la BD

    $estado = $_POST['estado'];
    $idOrden = $_POST['id_orden'];

    switch ($estado) {
        case 0:
            $consultar = $DB_con->prepare('SELECT * FROM orden WHERE id_orden=:orden');
            $consultar->bindParam(':orden', $idOrden);
            $consultar->execute();

            $orden = $consultar->fetch(PDO::FETCH_ASSOC);

            $insertar = $DB_con->prepare('INSERT INTO venta(cliente, total) VALUES(:cliente, :total)');
            $insertar->bindParam(':cliente', $orden['cliente']);
            $insertar->bindParam(':total', $orden['total']);
            $insertar->execute();

            $idVenta = $DB_con->lastInsertId();

            $consultar2 = $DB_con->prepare('SELECT * FROM detalle_orden WHERE id_orden=:orden');
            $consultar2->bindParam(':orden', $idOrden);
            $consultar2->execute();

            $detalles = $consultar2->fetchAll(PDO::FETCH_ASSOC);

            foreach ($detalles as $key => $detalle) {
                $insertar2 = $DB_con->prepare('INSERT INTO detalle_venta(id_venta, id_producto, cantidad_venta, precio_producto, monto_total) VALUES(:venta, :producto, :cantidad, :precio, :total)');
                $insertar2->bindParam(':venta', $idVenta);
                $insertar2->bindParam(':producto', $detalle['id_producto']);
                $insertar2->bindParam(':cantidad', $detalle['cantidad_venta']);
                $insertar2->bindParam(':precio', $detalle['precio_producto']);
                $insertar2->bindParam(':total', $detalle['monto_total']);
                $insertar2->execute();
                
                $cambio=$DB_con->prepare('UPDATE detalle_orden SET estado=:estado WHERE id_detalle_orden=:detalle');
                $cambio->bindParam(':estado', $estado);
                $cambio->bindParam(':detalle', $detalle['id_detalle_orden']);
                $cambio->execute();

            }
            
            $cambiar = $DB_con->prepare('UPDATE orden SET estado=:estado WHERE id_orden=:orden');
            $cambiar->bindParam(':estado', $estado);
            $cambiar->bindParam(':orden', $idOrden);
            
            if ($cambiar->execute()) {
            session_start();
            $_SESSION['Aprobado'] = 'registro';
            header("location: ./otros.php");
            } else {
                session_start();
                $_SESSION['errorDeAprobar'] = 'registro';
                header("location: ./otros.php");
            }
            break;
        case 1:
            $consultar3 = $DB_con->prepare('SELECT * FROM detalle_orden WHERE id_orden=:orden');
            $consultar3->bindParam(':orden', $idOrden);
            $consultar3->execute();

            $details = $consultar3->fetchAll(PDO::FETCH_ASSOC);

            foreach ($details as $key => $detail) {
                $consultar4=$DB_con->prepare('SELECT * FROM producto WHERE id_producto=:id');
                $consultar4->bindParam(':id', $detail['id_producto']);
                $consultar4->execute();
    
                $cantidad=$consultar4->fetch(PDO::FETCH_ASSOC);

                $suma=$cantidad['cantidad']+$detail['cantidad_venta'];

                $sumatoria=$DB_con->prepare('UPDATE producto SET cantidad=:cantidad WHERE id_producto=:id');
                $sumatoria->bindParam(':cantidad', $suma);
                $sumatoria->bindParam(':id', $detail['id_producto']);
                $sumatoria->execute();

                $cambio2=$DB_con->prepare('UPDATE detalle_orden SET extra=:extra WHERE id_detalle_orden=:detalle');
                $cambio2->bindParam(':extra', $estado);
                $cambio2->bindParam(':detalle', $detail['id_detalle_orden']);
                $cambio2->execute();
            }

            $cambiar2 = $DB_con->prepare('UPDATE orden SET condicion=:condicion WHERE id_orden=:orden');
            $cambiar2->bindParam(':condicion', $estado);
            $cambiar2->bindParam(':orden', $idOrden);
            if ($cambiar2->execute()) {
                session_start();
                $_SESSION['Cancelado'] = 'registro';
                header("location: ./otros.php");
                } else {
                    session_start();
                    $_SESSION['errorDeAprobar'] = 'registro';
                    header("location: ./otros.php");
                }
            break;
    }