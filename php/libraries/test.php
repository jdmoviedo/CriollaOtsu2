<?php
require_once(dirname(__DIR__).'/libraries/rutas.php');
require_once(rutaBase.'php'.DS.'vendor'.DS.'autoload.php');
use Minishlink\WebPush\VAPID;

$vapidKeys = VAPID::createVapidKeys();


echo $vapidKeys['publicKey']."<br>";
echo $vapidKeys['privateKey'];