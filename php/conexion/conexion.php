<?php

class conexion
{

	// JDMOVIEDO
	function conectar()
	{

		require_once(dirname(__DIR__) . "/conexion/params.php");
		$hostname_conexion = HOST_DB;
		$username_conexion = USER_DB;
		$password_conexion = PASSWORD_DB;
		$bd = NAME_DB;
		//Creando la conexión, nuevo objeto mysqli
		@$mysqli = new mysqli($hostname_conexion, $username_conexion, $password_conexion, $bd);
		$mysqli->set_charset("utf8");
		//Si sucede algún error la función muere e imprimir el error
		if ($mysqli->connect_error) {
			die("Error en la conexion : " . $mysqli->connect_errno .
				"-" . $mysqli->connect_error);
		}
		//Si nada sucede retornamos la conexión
		return $mysqli;
	}
}
