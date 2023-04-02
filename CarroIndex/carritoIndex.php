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
                    $cantidad=$_GET['cantidad'];
                } else{
                    $mensaje="La cantidad esta mal";
                }

                if(is_numeric($_GET['cantidad_max'])){
                    $cantidadMax=$_GET['cantidad_max'];
                } else{
                    $mensaje="La cantidad esta mal";
                }

                if (!isset($_SESSION['carritoIndex'])) {
                    $carro_pro=array(
                        'id'=>$id_producto,
                        'producto'=>$nombre_producto,
                        'precio'=>$precio_producto,
                        'cantidad'=>$cantidad,
                        'cantidad_max'=>$cantidadMax
                    );
                    $_SESSION['carritoIndex'][0]=$carro_pro;

                    setcookie("carrito[0][id]", $carro_pro["id"], time()+3600);
                    setcookie("carrito[0][producto]", $carro_pro["producto"], time()+3600);
                    setcookie("carrito[0][precio]", $carro_pro["precio"], time()+3600);
                    setcookie("carrito[0][cantidad]", $carro_pro["cantidad"], time()+3600);
                    setcookie("carrito[0][cantidad_max]", $carro_pro["cantidad_max"], time()+3600);

                    $mensaje="Producto agregado al carrito";
                } else {
                    $carro_pro=array(
                        'id'=>$id_producto,
                        'producto'=>$nombre_producto,
                        'precio'=>$precio_producto,
                        'cantidad'=>$cantidad,
                        'cantidad_max'=>$cantidadMax
                    );
                    $idsProductos=array_column($_SESSION['carritoIndex'], 'id');
                    if (in_array($id_producto, $idsProductos)) {
                        foreach ($_SESSION["carritoIndex"] as $key => $producto) {
                            if($id_producto==$producto["id"]){
                                $_SESSION['carritoIndex'][$key]=$carro_pro;
                            } else {
                                continue;
                            }
                        }
                    }
                    else{

                        $numero_productos=count($_SESSION['carritoIndex']);
                        $carro_pro=array(
                            'id'=>$id_producto,
                            'producto'=>$nombre_producto,
                            'precio'=>$precio_producto,
                            'cantidad'=>$cantidad,
                            'cantidad_max'=>$cantidadMax
                        );
                        $_SESSION['carritoIndex'][$numero_productos]=$carro_pro;

                        setcookie("carrito[".$numero_productos."][id]", $carro_pro["id"], time()+3600);
                        setcookie("carrito[".$numero_productos."][producto]", $carro_pro["producto"], time()+3600);
                        setcookie("carrito[".$numero_productos."][precio]", $carro_pro["precio"], time()+3600);
                        setcookie("carrito[".$numero_productos."][cantidad]", $carro_pro["cantidad"], time()+3600);
                        setcookie("carrito[".$numero_productos."][cantidad_max]", $carro_pro["cantidad_max"], time()+3600);

                        $mensaje="Producto agregado al carrito";
                    }
                }
            break;

            case 'eliminar':
                if (is_numeric($_GET['id'])) {
                    $id_producto=$_GET['id'];
                    foreach ($_SESSION['carritoIndex'] as $indice => $producto) {
                        if ($producto['id']==$id_producto) {
                            unset($_SESSION['carritoIndex'][$indice]);
                            setcookie("carrito[".$indice."][id]", $producto["id"], time()-3600);
                            setcookie("carrito[".$indice."][producto]", $producto["producto"], time()-3600);
                            setcookie("carrito[".$indice."][precio]", $producto["precio"], time()-3600);
                            setcookie("carrito[".$indice."][cantidad]", $producto["cantidad"], time()-3600);
                            setcookie("carrito[".$indice."][cantidad_max]", $producto["cantidad_max"], time()-3600);
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
                    foreach ($_SESSION['carritoIndex'] as $indice => $producto) {
                        if ($producto['id']==$id_producto) {
                            if ($_SESSION['carritoIndex'][$indice]['cantidad']==$_SESSION['carritoIndex'][$indice]['cantidad_max']) {
                                $_SESSION['carritoIndex'][$indice]['cantidad']=$_SESSION['carritoIndex'][$indice]['cantidad_max'];
                                setcookie("carrito[".$indice."][cantidad]", $_SESSION['carritoIndex'][$indice]['cantidad_max'], time()+3600);
                            }else {
                                $_SESSION['carritoIndex'][$indice]['cantidad']++; 
                                setcookie("carrito[".$indice."][cantidad]", $_SESSION['carritoIndex'][$indice]['cantidad'], time()-3600);
                            }
                            break;  
                        }
                    }
                }else {
                    $mensaje="El aumento esta mal";
                }
            break;

            case 'disminuir':
                if (is_numeric($_GET['id'])) {
                    $id_producto=$_GET['id'];
                    foreach ($_SESSION['carritoIndex'] as $indice => $producto) {
                        if ($producto['id']==$id_producto) {
                            if ($_SESSION['carritoIndex'][$indice]['cantidad']==1) {
                                $_SESSION['carritoIndex'][$indice]['cantidad']=1;
                                setcookie("carrito[".$indice."][cantidad]", $_SESSION['carritoIndex'][$indice]['cantidad'], time()+3600);
                            }else {
                                $_SESSION['carritoIndex'][$indice]['cantidad']--; 
                                setcookie("carrito[".$indice."][cantidad]", $_SESSION['carritoIndex'][$indice]['cantidad'], time()+3600);
                            }
                            break;  
                        }
                    }
                }else {
                    $mensaje="El aumento esta mal";
                }
            break;

            case 'proceder':
                header('location:./CarroIndex/destruyeSesion.php');
        }
    }


/*foreach ($_SESSION["carritoIndex"] as $key => $producto) {
                        setcookie("carrito[".$key."][id]", $producto["id"], time()+3600);
                        setcookie("carrito[".$key."][producto]", $producto["producto"], time()+3600);
                        setcookie("carrito[".$key."][precio]", $producto["precio"], time()+3600);
                        setcookie("carrito[".$key."][cantidad]", $producto["cantidad"], time()+3600);
                        setcookie("carrito[".$key."][cantidad_max]", $producto["cantidad_max"], time()+3600);
                    }*/