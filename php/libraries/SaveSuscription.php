<?php
ini_set('display_errors', 1);
require_once(dirname(__DIR__) . '/libraries/rutas.php');
require_once(rutaBase.'php'.DS.'vendor'.DS.'autoload.php');

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $info = file_get_contents('php://input');
    if (!empty($info)) {
        $subscription = json_decode($info, true);

        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();
        
        $endpoint =  $subscription['endpoint'];
        $ph = $subscription['keys']['p256dh'];
        $auth = $subscription['keys']['auth'];



        $consulta = "INSERT INTO Subscription (endpoint, p256dh, auth) 
        SELECT
        '$endpoint' as endpoint, 
        '$ph' as p256dh, 
        '$auth' as auth
        FROM DUAL
        WHERE NOT EXISTS (
            SELECT 1
            FROM Subscription
            WHERE p256dh = '$ph' and auth = '$auth'
        );";
        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        mysqli_close($mysqli);
    }
}