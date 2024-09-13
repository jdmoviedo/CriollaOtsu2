<?php
ini_set('display_errors', 1);
require_once(dirname(__DIR__) . '/libraries/rutas.php');
require_once(rutaBase . 'php' . DS . 'vendor' . DS . 'autoload.php');

use Minishlink\WebPush\WebPush;
use Minishlink\WebPush\Subscription;

// Claves VAPID
$publicKey = 'BMuL4Tdd0fSwg3ixxDK2A_Dmi74gFzQqqJgmEehNjJQaoHQe8wcxKFsxvRJi1BNMrAftNPah-4pEzyI2-AlO_BQ';
$privateKey = 'YTSGfAcPXeQhYWzjfhYDCKo_wa4kq1jMy8bFWC4W-VA';

$auth = [
    'VAPID' => [
        'subject' => 'mailto:juan0802@gmail.com',
        'publicKey' => $publicKey,
        'privateKey' => $privateKey,
    ],
];

$webPush = new WebPush($auth);

require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
$conexion = new Conexion();
$mysqli = $conexion->Conectar();

date_default_timezone_set('America/Bogota');
$fechaActual = date('Y-m-d');

$dataSubcription = array();

$consulta = "SELECT * FROM Subscription";

$resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

if ($resultado) {
    if (mysqli_num_rows($resultado) > 0) {
        while ($data = mysqli_fetch_assoc($resultado)) {
            $dataSubcription[] = array(
                'endpoint' => $data['endpoint'],
                'p256dh' => $data['p256dh'],
                'auth' => $data['auth'],
                'IdSubscription' => $data['IdSubscription'],
            );
        }
    }
}

$consultaValidacion = "SELECT 
O.IdOrder,
GROUP_CONCAT(COALESCE(IdSubscription, '') SEPARATOR ';') as IdSubscription
FROM `Order` O
LEFT JOIN SubscriptionHasOrder SHO ON SHO.IdOrder = O.IdOrder
Where IdStatusOrder = 1 AND IdPaymentMethod IN (1,2)
GROUP BY O.IdOrder
UNION ALL
SELECT 
O.IdOrder,
GROUP_CONCAT(COALESCE(IdSubscription, '') SEPARATOR ';') as IdSubscription
FROM `Order` O
LEFT JOIN SubscriptionHasOrder SHO ON SHO.IdOrder = O.IdOrder
Where O.IdStatusOrder = 1 
AND EXISTS(
    SELECT
    *
    FROM OrderPayment OP     
    WHERE OP.IdOrder = O.IdOrder AND OP.IdStatusPayment = 2
)
GROUP BY O.IdOrder";

$resultadoValidacion = mysqli_query($mysqli, $consultaValidacion) or die("Error en la Consulta SQL: " . $consultaValidacion);

if ($resultadoValidacion) {
    if (mysqli_num_rows($resultadoValidacion) > 0) {

        while ($dataOrder = mysqli_fetch_assoc($resultadoValidacion)) {
            $IdOrder = $dataOrder["IdOrder"];
            $IdSubscriptionValidate = array_filter(explode(";", $dataOrder["IdSubscription"]));
            if (count($dataSubcription) > 0) {
                for ($i = 0; $i < count($dataSubcription); $i++) {
                    $IdSubscription = $dataSubcription[$i]["IdSubscription"];

                    $subscription = Subscription::create([
                        'endpoint' => $dataSubcription[$i]['endpoint'],
                        'keys' => [
                            'p256dh' => $dataSubcription[$i]['p256dh'],
                            'auth' => $dataSubcription[$i]['auth'],
                        ],
                    ]);

                    $payload = json_encode([
                        'title' => 'Tienes una nueva orden',
                        'body' => 'Se ha ingresado una nueva orden #' . $IdOrder,
                        'icon' => "https://admin.restaurantescriollayotsu.com/img/logo.png",
                        'url' => 'https://admin.restaurantescriollayotsu.com/Order'
                    ]);

                    $options = [
                        'TTL' => 600, // Tiempo de vida en segundos
                        'urgency' => 'high', // Urgencia de la notificación: 'very-low', 'low', 'normal', 'high'
                        'topic' => 'general', // Tema de la notificación
                        'batchSize' => 200, // Tamaño del lote de envío
                    ];

                    if (!in_array($IdSubscription, $IdSubscriptionValidate)) {
                        $report = $webPush->sendOneNotification(
                            $subscription,
                            $payload,
                            $options
                        );

                        if ($report->isSuccess()) {
                            $consulta = "INSERT INTO SubscriptionHasOrder (IdSubscription, IdOrder) 
                                VALUES ($IdSubscription,$IdOrder)";
                            $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);
                        } else {
                            echo 'Error al enviar la notificación : ' . $report->getReason() . "\n";
                        }
                    }
                }
            }
        }
    }
}
mysqli_close($mysqli);
