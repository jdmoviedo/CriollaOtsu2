<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'validaciones.php');
        require_once(rutaBase . 'php' . DS . 'controller' . DS . 'ControllerLogin.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelIngredient.php');
        $permisos = Sesion::GetParametro('permisos');

        if ($permisos) {
            if (ControllerLogin::verificarLogin("Ingredient")) {
                $peticion = $_POST['peticion'];
                switch ($peticion) {
                    case "crearIngrediente":
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;

                        if (
                            Validar::requerido($Description)
                        ) {
                            echo ModelIngredient::crearIngrediente($Description);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "buscarIngredientes":
                        echo ModelIngredient::buscarIngredientes();
                        break;
                    case "datosIngrediente":
                        $IdIngredient = isset($_POST['IdIngredient']) ? trim($_POST['IdIngredient']) : null;
                        if (
                            validar::patronnumeros($IdIngredient)
                        ) {
                            echo ModelIngredient::datosIngrediente($IdIngredient);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "editarIngrediente":
                        $IdIngredient = isset($_POST['IdIngredient']) ? trim($_POST['IdIngredient']) : NULL;
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;

                        if (
                            Validar::numeros($IdIngredient) && Validar::requerido($Description)
                        ) {
                            echo ModelIngredient::editarIngrediente($IdIngredient, $Description);
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
