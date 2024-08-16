<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'validaciones.php');
        require_once(rutaBase . 'php' . DS . 'controller' . DS . 'ControllerLogin.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelTypeProduct.php');
        $permisos = Sesion::GetParametro('permisos');

        if ($permisos) {
            if (ControllerLogin::verificarLogin("TypeProduct")) {
                $peticion = $_POST['peticion'];
                switch ($peticion) {
                    case "crearTipoProducto":
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;

                        if (
                            Validar::letras($Description)
                        ) {
                            echo ModelTypeProduct::crearTipoProducto($Description);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "buscarTipoProductos":
                        echo ModelTypeProduct::buscarTipoProductos();
                        break;
                    case "datosTipoProducto":
                        $IdTypeProduct = isset($_POST['IdTypeProduct']) ? trim($_POST['IdTypeProduct']) : null;
                        if (
                            validar::patronnumeros($IdTypeProduct)
                        ) {
                            echo ModelTypeProduct::datosTipoProducto($IdTypeProduct);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "editarTipoProducto":
                        $IdTypeProduct = isset($_POST['IdTypeProduct']) ? trim($_POST['IdTypeProduct']) : NULL;
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;

                        if (
                            Validar::numeros($IdTypeProduct) && Validar::letras($Description)
                        ) {
                            echo ModelTypeProduct::editarTipoProducto($IdTypeProduct, $Description);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case 'subirImagen':
                        $IdTypeProduct = isset($_POST['IdTypeProduct']) ? trim($_POST['IdTypeProduct']) : null;

                        if (validar::numeros($IdTypeProduct)) {
                            $error = $_FILES['image']['error'];
                            $size = $_FILES['image']['size'];
                            $type = $_FILES['image']['type'];
                            if ($error == "0") {
                                if ($type == "image/jpeg" || $type == "image/png") {
                                    if ($size <= 20971520) {
                                        $tmp = $_FILES['image']['tmp_name'];
                                        echo ModelTypeProduct::subirImagen($IdTypeProduct, $type, $tmp);
                                    } else {
                                        echo json_encode(array("status" => "2"));
                                    }
                                } else {
                                    echo json_encode(array("status" => "3"));
                                }
                            } else {
                                echo json_encode(array("status" => "4"));
                            }
                        } else {
                            echo json_encode(array("status" => "5"));
                        }
                        break;
                }
            }
        }
    } else {
        echo json_encode('Sin peticion 0_o');
    }
}
