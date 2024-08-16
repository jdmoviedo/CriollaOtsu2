<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'validaciones.php');
        require_once(rutaBase . 'php' . DS . 'controller' . DS . 'ControllerLogin.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelRestaurant.php');
        $permisos = Sesion::GetParametro('permisos');

        if ($permisos) {
            if (ControllerLogin::verificarLogin("Restaurant")) {
                $peticion = $_POST['peticion'];
                switch ($peticion) {
                    case "crearRestaurante":
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;

                        if (
                            Validar::letras($Description)
                        ) {
                            echo ModelRestaurant::crearRestaurante($Description);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "buscarRestaurantes":
                        echo ModelRestaurant::buscarRestaurantes();
                        break;
                    case "datosRestaurante":
                        $IdRestaurant = isset($_POST['IdRestaurant']) ? trim($_POST['IdRestaurant']) : null;
                        if (
                            validar::patronnumeros($IdRestaurant)
                        ) {
                            echo ModelRestaurant::datosRestaurante($IdRestaurant);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "editarRestaurante":
                        $IdRestaurant = isset($_POST['IdRestaurant']) ? trim($_POST['IdRestaurant']) : NULL;
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;

                        if (
                            Validar::numeros($IdRestaurant) && Validar::letras($Description)
                        ) {
                            echo ModelRestaurant::editarRestaurante($IdRestaurant, $Description);
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
