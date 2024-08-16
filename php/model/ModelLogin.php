<?php

class ModelLogin
{
	public static function login($usuario, $contrasenia)
	{
		require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');

		$conexion = new Conexion();
		$mysqli = $conexion->Conectar();

		$usuario = mysqli_real_escape_string($mysqli, $usuario);

		$sql = "SELECT
		IdUser,
		Names,
		Password,
		IdStatusUser,
		Home
		FROM User
		WHERE Username = '$usuario';";

		$rtdo = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

		if (mysqli_num_rows($rtdo) == 1) {
			$data = mysqli_fetch_object($rtdo);

			$estado = $data->IdStatusUser;
			if ($estado == 1) {
				$Password = $data->Password;
				$verificarContrasenia = Utilidades::VerificarHash($contrasenia, $Password);
				if ($verificarContrasenia) {

					$respuesta['status'] = "1";
					$IdUser = $data->IdUser;
					$respuesta['IdUser'] = $IdUser;
					$usuario = $data->Names;
					$home = $data->Home;
					$permisos =  self::PermisosUsuario($IdUser);
					
					if ($permisos) {
						$datosSesion = array(
							'id' => $IdUser,
							'usuario' => $usuario,
							'permisos' => $permisos,							
							'timeout' => time(),
							'token' => "dqtQS2cBmGd8MbyMCHBj3Dq38Xm89vVyxxum4aySt9witAwBN9",
						);
						Sesion::CrearSesion($datosSesion);

						$sql = "SELECT
						Submodule
						FROM Submodule sw
						WHERE sw.IdSubmodule = $home";
						$resultado = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);
						if (mysqli_num_rows($resultado) == 1) {
							$respuesta['url'] = mysqli_fetch_array($resultado)['Submodule'];
						}
					}
				} else {
					$respuesta['status'] = "4"; //contraseña erronea
				}
			} else {
				$respuesta['status'] = "3"; // usuario no activo
			}
		} else {
			$respuesta['status'] = "2"; //usuario no existe
		}
		return json_encode($respuesta);
		mysqli_close($mysqli);
	}

	public static function PermisosUsuario($IdUser)
	{
		//funcion para obtener los modulos y submodulos
		$conexion = new Conexion();
		$mysqli = $conexion->Conectar();

		$IdUser = mysqli_real_escape_string($mysqli, $IdUser);

		$arrayPermisos = array();

		$sqlpermisos = "SELECT 
		MW.Description as modulo,
		MW.Icon as icono,
		group_concat(CONCAT(SMW.Description,'|JUAN|',SMW.Submodule) SEPARATOR '|SEPARATOR|') as submodulos
		from 
		UserHasSubmodule USW
		JOIN Submodule SMW ON SMW.IdSubmodule  = USW.IdSubmodule
		JOIN Module MW ON MW.IdModule = SMW.IdModule 
		WHERE USW.IdUser = $IdUser
		group by MW.IdModule,MW.Icon
		order by USW.IdSubmodule";

		$rtopermisos = mysqli_query($mysqli, $sqlpermisos) or die("Error en la Consulta SQL: " . $sqlpermisos);

		//submodulos
		if (mysqli_num_rows($rtopermisos) > 0) {
			while ($data_permisos = mysqli_fetch_array($rtopermisos)) {
				$modulo = $data_permisos['modulo'];
				$icono = $data_permisos['icono'];
				$submodulos = explode('|SEPARATOR|', $data_permisos['submodulos']);
				$arrayPermisos[] = array("modulo" => $modulo, "icono" => $icono, "submodulos" => $submodulos);
			}
		}

		if (count($arrayPermisos) > 0) {
			$permisos = $arrayPermisos;
		} else {
			$permisos = false;
		}
		//retorno los permisos encontrados
		return $permisos;
	}

	public static function UpdatePassword($password)
	{
		require_once(rutaBase . 'php' . DS . 'conexion' . DS . 'conexion.php');
		require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'utilidades.php');
		require_once(rutaBase . 'php' . DS . 'libraries' . DS . 'sesion.php');

		$conexion = new Conexion();
		$mysqli = $conexion->Conectar();

		$usuario = Sesion::GetParametro('id');

		date_default_timezone_set('America/Bogota');
		$fechaHoraActual = date('Y-m-d H:i:s');

		$password = $mysqli->real_escape_string($password);
		$password = Utilidades::Hash($password);

		$sql = "UPDATE User 
        SET Password = '$password'
        WHERE IdUser = $usuario;";

		$resultado = mysqli_query($mysqli, $sql) or die("Error en la Consulta SQL: " . $sql);

		if ($resultado) {
			$respuesta['status'] = "1";
		} else {
			$respuesta['status'] = "0";
		}

		mysqli_close($mysqli);
		return json_encode($respuesta);
	}
}
