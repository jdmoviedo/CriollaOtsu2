<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'validaciones.php');
        require_once(rutaBase . 'php' . DS . 'controller' . DS . 'ControllerLogin.php');        
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelUser.php');
        $permisos = Sesion::GetParametro('permisos');

        if ($permisos) {
            if (ControllerLogin::verificarLogin("User")) {
                $peticion = $_POST['peticion'];
                switch ($peticion) {
                    case "crearUsuario":
                        $Names = isset($_POST['Names']) ? trim($_POST['Names']) : NULL;  
                        $UserName = isset($_POST['UserName']) ? trim($_POST['UserName']) : NULL;  
                        $contrasenia = isset($_POST['password']) ? trim($_POST['password']) : NULL;
                        $contrasenia1 = isset($_POST['password1']) ? trim($_POST['password1']) : NULL;

                        if (
                            Validar::letras($Names) && Validar::letras($UserName) && $contrasenia === $contrasenia1
                        ) {
                            echo ModelUser::crearUsuario(
                                $Names,
                                $UserName,
                                $contrasenia,
                            );
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "buscarUsuarios":
                        require_once(rutaBase . 'php/model/ModelUser.php');
                        echo ModelUser::buscarUsuarios();
                        break;
                    case "cambiarEstado":
                        $IdUser = isset($_POST['IdUser']) ? trim($_POST['IdUser']) : null;
                        $estado = isset($_POST['estado']) ? trim($_POST['estado']) : null;
                        if (
                            validar::patronnumeros($IdUser) && validar::patronnumeros($estado)
                        ) {
                            require_once(rutaBase . 'php/model/ModelUser.php');

                            echo ModelUser::cambiarEstado($IdUser, $estado);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "datosUsuario":
                        $IdUser = isset($_POST['IdUser']) ? trim($_POST['IdUser']) : null;
                        if (
                            validar::patronnumeros($IdUser)
                        ) {
                            require_once(rutaBase . 'php/model/ModelUser.php');
                            echo ModelUser::datosUsuario($IdUser);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "editarUsuario":
                        $IdUser = isset($_POST['IdUser']) ? trim($_POST['IdUser']) : NULL;
                        $Names = isset($_POST['Names']) ? trim($_POST['Names']) : NULL;  

                        if (
                            Validar::numeros($IdUser) && Validar::letras($Names)
                        ) {
                            require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelUser.php');
                            echo ModelUser::editarUsuario(
                                $IdUser,
                                $Names
                            );
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "asignarSubmodulo":
                        $IdUser = isset($_POST['IdUser']) ? trim($_POST['IdUser']) : null;
                        $home = isset($_POST['selectHome']) ? trim($_POST['selectHome']) : NULL;
                        $submodulos = isset($_POST['selectSubModulos']) ? $_POST['selectSubModulos'] : [];

                        if (
                            validar::numeros($IdUser) && validar::numeros($home) && validar::array_requerido($submodulos)
                        ) {
                            require_once(rutaBase . 'php/model/ModelUser.php');

                            echo ModelUser::asignarSubmodulo($IdUser, $home, $submodulos);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "cargarAsignacion":
                        $IdUser = isset($_POST['IdUser']) ? trim($_POST['IdUser']) : null;

                        if (
                            validar::numeros($IdUser)
                        ) {
                            require_once(rutaBase . 'php/model/ModelUser.php');
                            echo ModelUser::cargarAsignacion($IdUser);
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
