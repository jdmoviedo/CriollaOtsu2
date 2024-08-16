<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'validaciones.php');
        require_once(rutaBase . 'php' . DS . 'controller' . DS . 'ControllerLogin.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelModule.php');
        $permisos = Sesion::GetParametro('permisos');

        if ($permisos) {
            if (ControllerLogin::verificarLogin("Module")) {
                $peticion = $_POST['peticion'];
                switch ($peticion) {
                    case "crearModulo":
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;
                        $Icon = isset($_POST['Icon']) ? trim($_POST['Icon']) : NULL;

                        if (
                            Validar::letras($Description) && Validar::requerido($Icon)
                        ) {
                            echo ModelModule::crearModulo($Description, $Icon);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "buscarModulos":
                        echo ModelModule::buscarModulos();
                        break;
                    case "datosModulo":
                        $IdModule = isset($_POST['IdModule']) ? trim($_POST['IdModule']) : null;
                        if (
                            validar::patronnumeros($IdModule)
                        ) {
                            echo ModelModule::datosModulo($IdModule);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "editarModulo":
                        $IdModule = isset($_POST['IdModule']) ? trim($_POST['IdModule']) : NULL;
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;
                        $Icon = isset($_POST['Icon']) ? trim($_POST['Icon']) : NULL;

                        if (
                            Validar::numeros($IdModule) && Validar::letras($Description) && Validar::requerido($Icon)
                        ) {
                            echo ModelModule::editarModulo($IdModule, $Description, $Icon);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "cambiarEstado":
                        $IdModule = isset($_POST['IdModule']) ? trim($_POST['IdModule']) : null;
                        $IdStatus = isset($_POST['IdStatus']) ? trim($_POST['IdStatus']) : null;
                        if (
                            validar::patronnumeros($IdModule) && validar::patronnumeros($IdStatus)
                        ) {
                            echo ModelModule::cambiarEstado($IdModule, $IdStatus);
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
