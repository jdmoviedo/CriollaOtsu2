<?php
ini_set('display_errors', 1);
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (isset($_POST['peticion'])) {
        require_once(dirname(__DIR__) . '/libraries/rutas.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'sesion.php');
        require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'validaciones.php');
        require_once(rutaBase . 'php' . DS . 'controller' . DS . 'ControllerLogin.php');
        require_once(rutaBase . 'php' . DS . 'model' . DS . 'ModelOrder.php');
        $permisos = Sesion::GetParametro('permisos');

        if ($permisos) {
            if (ControllerLogin::verificarLogin("Order")) {
                $peticion = $_POST['peticion'];
                switch ($peticion) {                 
                    case "buscarPedidos":
                        echo ModelOrder::buscarPedidos();
                        break;
                    case "detalleOrden":
                        $IdOrder = isset($_POST['IdOrder']) ? trim($_POST['IdOrder']) : null;
                        if (
                            validar::patronnumeros($IdOrder)
                        ) {
                            echo ModelOrder::detalleOrden($IdOrder);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "cambiarEstado":
                        $IdOrder = isset($_POST['IdOrder']) ? trim($_POST['IdOrder']) : null;
                        $IdStatusOrder = isset($_POST['IdStatusOrder']) ? trim($_POST['IdStatusOrder']) : null;
                        if (
                            validar::numeros($IdOrder) && validar::numeros($IdStatusOrder)
                        ) {
                            echo ModelOrder::cambiarEstado($IdOrder, $IdStatusOrder);
                        } else {
                            $respuesta['status'] = "0";
                            echo json_encode($respuesta);
                        }
                        break;
                    case "logOrden":
                        $IdOrder = isset($_POST['IdOrder']) ? trim($_POST['IdOrder']) : null;
                        if (
                            validar::patronnumeros($IdOrder)
                        ) {
                            echo ModelOrder::logOrden($IdOrder);
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
