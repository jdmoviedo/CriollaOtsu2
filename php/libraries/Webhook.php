<?php
ini_set('display_errors', 1);
require_once(dirname(__DIR__) . '/libraries/rutas.php');

// Obtén el contenido del cuerpo de la solicitud
$input = file_get_contents('php://input');
$data = json_decode($input, true);
// Verifica la firma del webhook (opcional pero recomendado)
$headers = getallheaders();
$signature = $headers['X-Event-Checksum'];
//DEV
$secretEvents = 'test_events_4RWxixQZGn96XP3EMHgzqouakQAJMAdi'; // Obtén esto desde tu configuración de Wompi
//PROD
//$secretEvents = 'prod_events_7IMTlqIbjwOh8LN1dc9ImXRhhgwtrNHn'; // Obtén esto desde tu configuración de Wompi
$properties = $data['signature']['properties'];
$values = [];
foreach ($properties as $property) {
    $keys = explode('.', $property);
    $value = $data['data'];
    foreach ($keys as $key) {
        $value = $value[$key];
    }
    $values[] = $value;
}

// Concatena los valores de las propiedades
$cadenaFirma = implode('', $values).$data['timestamp'].$secretEvents;
$calculated_signature = hash("sha256", $cadenaFirma);

if (hash_equals($signature, $calculated_signature)) {
    if ($data['event'] == 'transaction.updated') {
        $transaction = $data['data']['transaction'];
        $IdOrderPayment = intval(str_replace("CO","",$transaction["reference"]));
        $StatusPaymentWompi = $transaction["status"];
        $IdStatusPayment = 0;

        switch ($StatusPaymentWompi) {
            case 'PENDING':
                $IdStatusPayment = 1;
                break;
            case 'APPROVED':
                $IdStatusPayment = 2;
                break;
            case 'DECLINED':
                $IdStatusPayment = 3;
                break;
            case 'VOIDED':
                $IdStatusPayment = 4;
                break;
            case 'ERROR':
                $IdStatusPayment = 5;
                break;
        }
        
        require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
        $conexion = new Conexion();
        $mysqli = $conexion->Conectar();

        $consulta = "INSERT INTO TransactionLog (IdOrderPayment,Information) 
        VALUES ($IdOrderPayment, '$input')";
        
        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        $consulta = "UPDATE OrderPayment
        SET
        IdStatusPayment = $IdStatusPayment
        WHERE IdOrderPayment = $IdOrderPayment";

        $resultado = mysqli_query($mysqli, $consulta) or die("Error en la Consulta SQL: " . $consulta);

        mysqli_close($mysqli);
    }
    // Responde con un status 200
    http_response_code(200);
} else {
    // Firma no válida
    http_response_code(400);
}