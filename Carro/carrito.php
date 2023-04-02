<?php
    session_start();
    if (isset($_GET['botonAdd'])) {
        switch ($_GET['botonAdd']) {

            //Esto es si la persona oprime el botón agregar al carrito
            case 'agregar':
                if (is_numeric($_GET['id'])) {
                    $id_producto=$_GET['id'];
                }else {
                    $mensaje="El id esta mal";
                }

                if (is_string($_GET['producto'])) {
                    $nombre_producto=$_GET['producto'];
                }else {
                    $mensaje="El producto esta mal";
                }

                if (is_numeric($_GET['precio'])) {
                    $precio_producto=$_GET['precio'];
                }else {
                    $mensaje="El precio esta mal";
                }

                if(is_numeric($_GET['cantidad'])){
                    $cantidad=(int)$_GET['cantidad'];
                } else{
                    $mensaje="La cantidad esta mal";
                }

                if(is_numeric($_GET['cantidad_max'])){
                    $cantidadMax=$_GET['cantidad_max'];
                } else{
                    $mensaje="La cantidad maxima esta mal";
                }

                if (!isset($_SESSION['carrito'])) {
                    $carro_pro=array(
                        'id'=>$id_producto,
                        'producto'=>$nombre_producto,
                        'precio'=>$precio_producto,
                        'cantidad'=>$cantidad,
                        'cantidad_max'=>$cantidadMax
                    );
                    $_SESSION['carrito'][0]=$carro_pro;
                    $mensaje="Producto agregado al carrito";
                }else {
                    $carro_pro=array(
                        'id'=>$id_producto,
                        'producto'=>$nombre_producto,
                        'precio'=>$precio_producto,
                        'cantidad'=>$cantidad,
                        'cantidad_max'=>$cantidadMax
                    );
                    $idsProductos=array_column($_SESSION['carrito'], 'id');
                    if (in_array($id_producto, $idsProductos)) {
                        foreach ($_SESSION["carrito"] as $key => $producto) {
                            if($id_producto==$producto["id"]){
                                $_SESSION['carrito'][$key]=$carro_pro;
                            } else {
                                continue;
                            }
                        }
                    }
                    else{

                        $numero_productos=count($_SESSION['carrito']);
                        $carro_pro=array(
                            'id'=>$id_producto,
                            'producto'=>$nombre_producto,
                            'precio'=>$precio_producto,
                            'cantidad'=>$cantidad,
                            'cantidad_max'=>$cantidadMax
                        );
                        $_SESSION['carrito'][$numero_productos]=$carro_pro;
                        $mensaje="Producto agregado al carrito";
                    }
                }
            break;

            case 'eliminar':
                if (is_numeric($_GET['id'])) {
                    $id_producto=$_GET['id'];
                    foreach ($_SESSION['carrito'] as $indice => $producto) {
                        if ($producto['id']==$id_producto) {
                            unset($_SESSION['carrito'][$indice]);
                            echo '<script> alert("Producto borrado.."); </script>';
                        }
                    }
                }else {
                    $mensaje="El id esta mal";
                }
            break;

            case 'aumentar':
                if (is_numeric($_GET['id'])) {
                    $id_producto=$_GET['id'];
                    foreach ($_SESSION['carrito'] as $indice => $producto) {
                        if ($producto['id']==$id_producto) {
                            if ($_SESSION['carrito'][$indice]['cantidad']==$_SESSION['carrito'][$indice]['cantidad_max']) {
                                $_SESSION['carrito'][$indice]['cantidad']=$_SESSION['carrito'][$indice]['cantidad_max'];
                            }else {
                                $_SESSION['carrito'][$indice]['cantidad']++; 
                            }
                            break;  
                        }
                    }
                }else {
                    $mensaje="El disminuir esta mal";
                }
            break;

            case 'disminuir':
                if (is_numeric($_GET['id'])) {
                    $id_producto=$_GET['id'];
                    foreach ($_SESSION['carrito'] as $indice => $producto) {
                        if ($producto['id']==$id_producto) {
                            if ($_SESSION['carrito'][$indice]['cantidad']==1) {
                                $_SESSION['carrito'][$indice]['cantidad']=1;
                            }else {
                                $_SESSION['carrito'][$indice]['cantidad']--; 
                            }
                            break;  
                        }
                    }
                }else {
                    $mensaje="El disminuir esta mal";
                }
            break;
        }
    }