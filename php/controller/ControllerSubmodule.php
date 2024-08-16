<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'validaciones.php');
        require_once(rutaBase . 'php' . DS . 'controller' . DS . 'ControllerLogin.php');        
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelSubmodule.php');
        $permisos = Sesion::GetParametro('permisos');

        if ($permisos) {
            if (ControllerLogin::verificarLogin("Submodule")) {
                $peticion = $_POST['peticion'];
                switch ($peticion) {
                    case "crearSubmodulo":
                        $IdModule = isset($_POST['selectModulo']) ? $_POST['selectModulo'] : NULL;
                        $Submodule = isset($_POST['Submodule']) ? trim($_POST['Submodule']) : NULL;
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;

                        if (
                            Validar::numeros($IdModule) && Validar::letras($Description) && Validar::letras($Submodule) && Validar::letras($Description)
                        ) {
                            echo ModelSubmodule::crearSubmodulo($IdModule, $Description, $Submodule);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "buscarSubmodulos":
                        echo ModelSubmodule::buscarSubmodulos();
                        break;
                    case "datosSubmodulo":
                        $IdSubmodule = isset($_POST['IdSubmodule']) ? trim($_POST['IdSubmodule']) : null;
                        if (
                            validar::patronnumeros($IdSubmodule)
                        ) {
                            echo ModelSubmodule::datosSubmodulo($IdSubmodule);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "editarSubmodulo":
                        $IdSubmodule = isset($_POST['IdSubmodule']) ? trim($_POST['IdSubmodule']) : NULL;
                        $IdModule = isset($_POST['selectModulo']) ? $_POST['selectModulo'] : NULL;
                        $Submodule = isset($_POST['Submodule']) ? trim($_POST['Submodule']) : NULL;
                        $Description = isset($_POST['Description']) ? trim($_POST['Description']) : NULL;

                        if (
                            Validar::numeros($IdSubmodule) && Validar::numeros($IdModule) && Validar::letras($Description)
                            && Validar::letras($Submodule)
                        ) {
                            echo ModelSubmodule::editarSubmodulo($IdSubmodule,  $IdModule, $Description, $Submodule);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "cambiarEstado":
                        $IdSubmodule = isset($_POST['IdSubmodule']) ? trim($_POST['IdSubmodule']) : null;
                        $IdStatus = isset($_POST['IdStatus']) ? trim($_POST['IdStatus']) : null;
                        if (
                            validar::numeros($IdSubmodule) && validar::numeros($IdStatus)
                        ) {
                            echo ModelSubmodule::cambiarEstado($IdSubmodule, $IdStatus);
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
