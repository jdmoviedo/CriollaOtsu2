<?php
ini_set('display_errors', 1);
require_once(dirname(__DIR__) . '/libraries/rutas.php');
require_once(rutaBase . 'php/libraries/sesion.php');
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {

    if (isset($_POST['select'])) {
        require_once(rutaBase . 'php/libraries/validaciones.php');

        require_once(rutaBase . 'php/model/ModelCargarSelect.php');
        $select = $_POST['select'];
        switch ($select) {
            case 'cargarSubModulos':
                $valores = isset($_POST['valores']) ? json_decode($_POST['valores']) : (object)[];
                if ($valores) {
                    $IdModule = $valores->modulo;
                    if (Validar::numeros($IdModule)) {
                        echo ModelCargarSelect::cargarSubModulos($IdModule);
                    } else {
                        $respuesta['status'] = "0";
                        echo json_encode($respuesta);
                    }
                } else {
                    echo ModelCargarSelect::cargarSubModulos();
                }
                break;
            case 'cargarModulos':
                echo ModelCargarSelect::cargarModulos();
                break;
            case 'cargarRestaurantes':
                echo ModelCargarSelect::cargarRestaurantes();
                break;
            case 'cargarTipoProductos':
                echo ModelCargarSelect::cargarTipoProductos();
                break;
            case 'cargarIngredientes':
                echo ModelCargarSelect::cargarIngredientes();
                break;
            default:
                echo "0_o";
                break;
        }
    } else {
        echo "0_o";
    }
} else {
}
