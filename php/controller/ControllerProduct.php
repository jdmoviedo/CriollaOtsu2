<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'validaciones.php');
        require_once(rutaBase . 'php' . DS . 'controller' . DS . 'ControllerLogin.php');        
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelProduct.php');
        $permisos = Sesion::GetParametro('permisos');

        if ($permisos) {
            if (ControllerLogin::verificarLogin("Product")) {
                $peticion = $_POST['peticion'];
                switch ($peticion) {
                    case "crearProducto":
                        $IdRestaurant = isset($_POST['selectRestaurant']) ? $_POST['selectRestaurant'] : NULL;
                        $IdTypeProduct = isset($_POST['selectTypeProduct']) ? $_POST['selectTypeProduct'] : NULL;                        
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;
                        $LongDescription = isset($_POST['LongDescription']) ? trim($_POST['LongDescription']) : NULL;
                        $Value = isset($_POST['Value']) ? trim($_POST['Value']) : NULL;

                        if (
                            Validar::numeros($IdRestaurant) && Validar::numeros($IdTypeProduct) 
                            && Validar::requerido($Description)  && Validar::numeros($Value) 
                        ) {
                            echo ModelProduct::crearProducto($IdRestaurant, $IdTypeProduct, $Description, $LongDescription, $Value);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "buscarProductos":
                        echo ModelProduct::buscarProductos();
                        break;
                    case "datosProducto":
                        $IdProduct = isset($_POST['IdProduct']) ? trim($_POST['IdProduct']) : null;
                        if (
                            validar::patronnumeros($IdProduct)
                        ) {
                            echo ModelProduct::datosProducto($IdProduct);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "editarProducto":
                        $IdProduct = isset($_POST['IdProduct']) ? trim($_POST['IdProduct']) : NULL;
                        $IdRestaurant = isset($_POST['selectRestaurant']) ? $_POST['selectRestaurant'] : NULL;
                        $IdTypeProduct = isset($_POST['selectTypeProduct']) ? $_POST['selectTypeProduct'] : NULL;                        
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;
                        $LongDescription = isset($_POST['LongDescription']) ? trim($_POST['LongDescription']) : NULL;
                        $Value = isset($_POST['Value']) ? trim($_POST['Value']) : NULL;


                        if (
                            Validar::numeros($IdProduct) && Validar::numeros($IdRestaurant) && Validar::numeros($IdTypeProduct) 
                            && Validar::requerido($Description) && Validar::numeros($Value) 
                        ) {
                            echo ModelProduct::editarProducto($IdProduct, $IdRestaurant, $IdTypeProduct, $Description, $LongDescription, $Value);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "cambiarEstado":
                        $IdProduct = isset($_POST['IdProduct']) ? trim($_POST['IdProduct']) : null;
                        $IdStatusProduct = isset($_POST['IdStatusProduct']) ? trim($_POST['IdStatusProduct']) : null;
                        if (
                            validar::numeros($IdProduct) && validar::numeros($IdStatusProduct)
                        ) {
                            echo ModelProduct::cambiarEstado($IdProduct, $IdStatusProduct);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case 'subirImagen':
                        $IdProduct = isset($_POST['IdProduct']) ? trim($_POST['IdProduct']) : null;

                        if (validar::numeros($IdProduct)) {
                            $error = $_FILES['image']['error'];
                            $size = $_FILES['image']['size'];
                            $type = $_FILES['image']['type'];
                            if ($error == "0") {
                                if ($type == "image/jpeg" || $type == "image/png") {
                                    if ($size <= 20971520) {
                                        $tmp = $_FILES['image']['tmp_name'];
                                        echo ModelProduct::subirImagen($IdProduct, $type, $tmp);
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
                    case "ProductHasIngredient":
                        $IdProduct = isset($_POST['IdProduct']) ? trim($_POST['IdProduct']) : null;
                        if (
                            validar::patronnumeros($IdProduct)
                        ) {
                            echo ModelProduct::ProductHasIngredient($IdProduct);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "registrarProductHasIngredient":
                        $IdProduct = isset($_POST['IdProduct']) ? trim($_POST['IdProduct']) : null;
                        $Datos = isset($_POST['Datos']) ? $_POST['Datos'] : [];

                        if (
                            validar::patronnumeros($IdProduct) && validar::array_requerido($Datos)
                        ) {
                            echo ModelProduct::registrarProductHasIngredient($IdProduct, $Datos);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                }
            }
        }
    } else {
        echo json_encode('Sin peticion 0_o');
    }
}
