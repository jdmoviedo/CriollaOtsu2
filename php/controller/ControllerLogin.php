<?php
ini_set('display_errors', 1);
//validamos la peticion ajax
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        $peticion = trim($_POST['peticion']);
        require_once(dirname(__DIR__) . '/libraries/rutas.php');        
        require_once(rutaBase . 'php/libraries/sesion.php');        
        require_once(rutaBase . 'php/libraries/validaciones.php');        
        require_once(rutaBase . 'php/model/ModelLogin.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'utilidades.php');
        switch ($peticion) {
            case 'login':
                $usuario = isset($_POST['usuarioLogin']) ? trim(mb_strtoupper($_POST['usuarioLogin'], "UTF-8")) : null;
                $contrasenia = isset($_POST['contraseniaLogin']) ? trim($_POST['contraseniaLogin']) : null;

                if (
                    validar::requerido($usuario) &&
                    validar::requerido($contrasenia)
                ) {
                    echo $response = ModelLogin::login($usuario, $contrasenia);
                } else {
                    $response['status'] = "0";
                    echo json_encode($response);
                }

                break;
            case 'logout':
                $respuesta = sesion::cerrarsesion();
                echo json_encode($respuesta);
                break;
            case 'updatePassword':
                $password = isset($_POST['password']) ? trim($_POST['password']) : null;
                if (Validar::requerido($password)) {
                    echo ModelLogin::UpdatePassword($password);
                } else {
                    $respuesta['status'] = "0";
                    echo json_encode($respuesta);
                }
                break;
            default:

                break;
        }
    }
}
/**
 *
 */
class ControllerLogin
{

    public static function verificarLogin($submoduloPermitido)
    {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php/libraries/sesion.php');
        $arrayPermisos = sesion::getparametro('permisos');
        $token = sesion::getparametro('token');
        if ($arrayPermisos != null && $token == "dqtQS2cBmGd8MbyMCHBj3Dq38Xm89vVyxxum4aySt9witAwBN9") {
            for ($i = 0; $i < count($arrayPermisos); $i++) {
                if (count($arrayPermisos[$i]["submodulos"]) > 0) {
                    for ($j = 0; $j < count($arrayPermisos[$i]["submodulos"]); $j++) {
                        $submodulo = $arrayPermisos[$i]["submodulos"][$j];
                        $arraySubmodulo = explode('|JUAN|', $submodulo);
                        $submoduloVerificar = $arraySubmodulo[1];
                        if ($submoduloVerificar == $submoduloPermitido) {
                            return true;
                        }
                    }
                }
            }
        }
        return false;
    }

    public static function verificarSesion()
    {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php/libraries/sesion.php');
        $arrayPermisos = sesion::getparametro('permisos');
        $token = sesion::getparametro('token');
        if ($arrayPermisos != null && $token == "dqtQS2cBmGd8MbyMCHBj3Dq38Xm89vVyxxum4aySt9witAwBN9") {
            if (count($arrayPermisos) > 0) {
                return true;
            } else {
                return false;
            }
        }
        return false;
    }

    public static function menunav()
    {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php/libraries/sesion.php');
        $arrayPermisos = sesion::getparametro('permisos');
        $token = sesion::getparametro('token');
        $html = "";

        if ($token == "dqtQS2cBmGd8MbyMCHBj3Dq38Xm89vVyxxum4aySt9witAwBN9") {
            for ($i = 0; $i < count($arrayPermisos); $i++) {
                $html .= '<div class="nav-item has-sub text-capitalize"><a class="cursor-pointer"><i class="' . $arrayPermisos[$i]["icono"] . '"></i><span>' . $arrayPermisos[$i]["modulo"] . '</span></a>';

                for ($j = 0; $j < count($arrayPermisos[$i]["submodulos"]); $j++) {
                    $submodulo = $arrayPermisos[$i]["submodulos"][$j];
                    $arraySubmodulo = explode('|JUAN|', $submodulo);
                    $html .= '<div class="submenu-content"><a href="' . $arraySubmodulo[1] . '" class="menu-item"><i class="ik ik-corner-down-right nav-icon"></i>' . $arraySubmodulo[0] . '</a></div>';
                }

                $html .= '</div>';
            }
        }

        return $html;
    }
}
