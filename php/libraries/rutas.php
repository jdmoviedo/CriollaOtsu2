<?php

mb_internal_encoding("UTF-8");
define('DS', DIRECTORY_SEPARATOR);

$host = $_SERVER['HTTP_HOST'];
$protocolo = isset($_SERVER['HTTPS']) == "on" ? 'https:'.DS.DS : 'http:'.DS.DS;

$urlBase = $_SERVER['SERVER_NAME'];
$rutaBase = substr(dirname(__DIR__), 0, -3);

define('urlBase', $protocolo.$host.DS);
define('rutaBase', $rutaBase);
