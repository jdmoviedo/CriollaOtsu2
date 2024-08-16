<?php

/**
 * clase para validar datos recibidos en php
 */
class validar
{
	public static function longitud($valor, $minimo, $maximo)
	{
		$caracteres = strlen(trim($valor));
		if ($caracteres >= $minimo && $caracteres <= $maximo) {
			return true;
		} else {
			return false;
		}
	}

	public static function requerido($valor)
	{
		$caracteres = strlen(trim($valor));
		if ($caracteres > 0) {
			return true;
		} else {
			return false;
		}
	}

	public static function correo($valor)
	{
		if (filter_var($valor, FILTER_VALIDATE_EMAIL)) {
			/*$domain = explode('@', $valor);
			if (checkdnsrr($domain[1])){
				return true;
			}else{
				return false;
			}*/
			return true;
		} else {
			return false;
		}
	}

	public static function letras($valor)
	{
		$patronAlfaNumerico = "/^[a-zA-ZáéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙñÑŃń\s]+$/";
		if (preg_match($patronAlfaNumerico, $valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function alfanumerico($valor)
	{
		$patronAlfaNumerico = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙ\s]+$/";
		if (preg_match($patronAlfaNumerico, $valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function direccion($valor)
	{
		$patronDireccion = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚäëïöüÄËÏÖÜàèìòùÀÈÌÒÙñÑŃńº#.\-\s]+$/";
		if (preg_match($patronDireccion, $valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function numeros($valor)
	{
		if (is_numeric($valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function password($valor)
	{
		$patronAlfaNumerico = "/^[a-zA-Z0-9]+$/";
		if (preg_match($patronAlfaNumerico, $valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function patroncedulas($valor)
	{
		$patronAN = "/^[a-zA-Z0-9]+$/";
		if (preg_match($patronAN, $valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function patronnumeros($valor)
	{
		$patronNumerico = "/^[0-9]+$/";
		if (preg_match($patronNumerico, $valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function patronalfanumerico1($valor)
	{
		$patronAN = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚüÜñÑŃń&$,\/.\-\s]+$/";
		if (preg_match($patronAN, $valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function patronalfanumerico2($valor)
	{
		$patronAN = "/^[a-zA-Z0-9\/_]+$/";
		if (preg_match($patronAN, $valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function patron_alfanumerico_sintomas($valor)
	{
		$patronAN = "/^[a-zA-Z0-9áéíóúÁÉÍÓÚüÜñÑŃń,()%\/.\-\s]+$/";
		if (preg_match($patronAN, $valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function patronalfanumericocontraseña($valor)
	{
		$patronAN = "/^[a-zA-Z0-9]+$/";
		if (preg_match($patronAN, $valor)) {
			return true;
		} else {
			return false;
		}
	}

	public static function entero($valor)
	{
		if (filter_var($valor, FILTER_VALIDATE_INT) === 0 || !filter_var($valor, FILTER_VALIDATE_INT) === false) {
			return true;
		} else {
			return false;
		}
	}

	public static function valoresnumericos($valor, $valores)
	{
		$numerosvalidos = explode(",", $valores);
		if (in_array($valor, $numerosvalidos)) {
			return true;
		} else {
			return false;
		}
	}

	public static function array_numerico($array)
	{
		$patronNumerico = "/^[0-9]+$/";
		if (preg_match($patronNumerico, implode('', $array))) {
			return true;
		} else {
			return false;
		}
	}

	public static function array_requerido($array)
	{
		if (count($array) > 0) {
			return true;
		} else {
			return false;
		}
	}

	public static function valoresnumericosmultiples($valoresIngresados, $valoresValidos)
	{
		$banderaValidacion = true;
		$arryValoresIngresados = explode(",", $valoresIngresados);
		$arryValoresValidos = explode(",", $valoresValidos);
		foreach ($arryValoresIngresados as $valor) {
			if (!in_array($valor, $arryValoresValidos)) {
				$banderaValidacion = false;
				break;
			}
		}
		return $banderaValidacion;
	}


	public static function valorenvalores($valor, $valores)
	{
		$arrayValores = explode(',', $valores);
		if (in_array($valor, $arrayValores)) {
			return true;
		} else {
			return false;
		}
	}

	public static function float($valor, $separadorDecimales)
	{
		if ($valor == "0" || filter_var($valor, FILTER_VALIDATE_FLOAT, array('options' => array('decimal' => $separadorDecimales)))) {
			return true;
		} else {
			return false;
		}
	}

	public static function fecha($fecha, $separador, $formato)
	{
		//fecha->string de la fecha con dia, mes y año en el
		//orden que quiera en formato numerico ej: 17-12-2001, 2001/12/17
		//separador->un caracter unico que separa el dia mes y año
		//para los casos anteriores fue - y /
		//formato el orden en que se da el dia mes año se establece
		//mediante las letras d(dia) m(mes) a(año) para los casos
		//seria dma y amd
		$arryFecha = explode($separador, $fecha);
		if (count($arryFecha) == 3) {
			switch ($formato) {
				case 'dma':
					$dia = $arryFecha[0];
					$mes = $arryFecha[1];
					$anio = $arryFecha[2];

					break;
				case 'dam':
					$dia = $arryFecha[0];
					$mes = $arryFecha[2];
					$anio = $arryFecha[1];
					break;
				case 'mad':
					$dia = $arryFecha[2];
					$mes = $arryFecha[0];
					$anio = $arryFecha[1];
					break;
				case 'mda':
					$dia = $arryFecha[1];
					$mes = $arryFecha[0];
					$anio = $arryFecha[2];
					break;
				case 'adm':
					$dia = $arryFecha[1];
					$mes = $arryFecha[2];
					$anio = $arryFecha[0];
					break;
				case 'amd':
					$dia = $arryFecha[2];
					$mes = $arryFecha[1];
					$anio = $arryFecha[0];
					break;
				case 'am':
					$mes = $arryFecha[1];
					$anio = $arryFecha[0];
					break;
				default:
					# code...
					break;
			}
			if (checkdate($mes, $dia, $anio)) {
				return true;
			} else {
				return false;
			}
		} else if (count($arryFecha) == 2) {
			switch ($formato) {
				case 'dm':
					$dia = $arryFecha[0];
					$mes = $arryFecha[1];
					break;
				case 'da':
					$dia = $arryFecha[0];
					$anio = $arryFecha[1];
					break;
				case 'ma':
					$mes = $arryFecha[0];
					$anio = $arryFecha[1];
					break;
				case 'md':
					$dia = $arryFecha[1];
					$mes = $arryFecha[0];
					break;
				case 'ad':
					$dia = $arryFecha[1];
					$anio = $arryFecha[0];
					break;
				case 'am':
					$mes = $arryFecha[1];
					$anio = $arryFecha[0];
					break;

				default:
					# code...
					break;
			}
			if (checkdate($mes, 15, $anio)) {
				return true;
			} else {
				return false;
			}
		} else {
			//var_dump($arryFecha);
			return false;
		}
	}
	//validar ip ipv4
	public static function ip($ip, $tipo)
	{
		switch ($tipo) {
			case 'IPV4':
				$tipoIp = "FILTER_FLAG_IPV4";
				break;
			case 'IPV6':
				$tipoIp = "FILTER_FLAG_IPV6";
				break;
			default:
				$tipoIp = "FILTER_FLAG_IPV4";
				break;
		}
		if (filter_var($ip, FILTER_VALIDATE_IP, $tipoIp)) {
			return true;
		} else {
			return false;
		}
	}
	//validar un numero entre un rango incluyendo los limites del rango
	public static function numeroenrango($numero, $numeroMinimo, $numeroMaximo)
	{
		$boolNumero = self::patronnumeros($numero);
		if ($boolNumero) {
			if ($numero < $numeroMinimo || $numero > $numeroMaximo) {
				return false;
			} else {
				return true;
			}
		} else {
			return false;
		}
	}

	public static function tipoarchivo($typefile, $tipoArray)
	{

		$arrayAdobe = array(
			'application/pdf',
			'application/postscript',
			'image/vnd.adobe.photoshop',
		);

		$arrayBinarios = array('application/octet-stream',);

		$arrayComprension = array(
			'application/x-bzip',
			'application/x-bzip2',
			'application/epub+zip',
		);

		$arrayMicrosoft = array(
			'application/msword',
			'application/vnd.ms-powerpoint',
			'application/vnd.ms-excel',
			'application/rtf',
			'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
			'text/csv',
		);

		$arrayOpenOffice = array(
			'application/vnd.oasis.opendocument.text',
			'application/vnd.oasis.opendocument.spreadsheet',
		);

		$arrayTextoWeb = array(
			'text/plain',
			'text/html',
			'text/html',
			'text/html',
			'text/css',
			'text/csv',
			'application/javascript',
			'application/json',
			'application/xml',
			'application/x-shockwave-flash',
			'video/x-flv',
		);

		$arrayImagenes = array(
			'image/png',
			'image/jpeg',
			'image/jpg',
			'image/gif',
			'image/bmp',
			'image/vnd.microsoft.icon',
			'image/tiff',
			'image/tiff',
			'image/svg+xml',
			'image/x-icon',
		);

		switch ($tipoArray) {
			case 1:
				if (in_array($typefile, $arrayAdobe)) {
					return true;
				} else {
					return false;
				}
				break;
			case 2:
				if (in_array($typefile, $arrayBinarios)) {
					return true;
				} else {
					return false;
				}
				break;
			case 3:
				if (in_array($typefile, $arrayComprension)) {
					return true;
				} else {
					return false;
				}
				break;
			case 4:
				if (in_array($typefile, $arrayMicrosoft)) {
					return true;
				} else {
					return false;
				}
				break;
			case 5:
				if (in_array($typefile, $arrayOpenOffice)) {
					return true;
				} else {
					return false;
				}
				break;
			case 6:
				if (in_array($typefile, $arrayTextoWeb)) {
					return true;
				} else {
					return false;
				}
				break;
			case 7:
				if (in_array($typefile, $arrayImagenes)) {
					return true;
				} else {
					return false;
				}
				break;
			default:
				return false;
				break;
		}
	}

	public static function hora($hora, $formato)
	{
		// $formato es un string => 12H o 24H
		// if( empty($formato) && empty($hora) ){
		switch ($formato) {
			case '12H':
				$rta = preg_match('#^(1[0-2]|0?[1-9]):[0-5][0-9] (AM|PM|am|pm)$#', $hora);
				$time = $rta == 1 ? true : false;
				break;

			case '24H':
				$rta = preg_match('#^([01]?[0-9]|2[0-3]):[0-5][0-9](:[0-5][0-9])?$#', $hora);
				$time = $rta == 1 ? true : false;
				break;

			default:
				# code...
				$time = false;
				break;
		}
		// }else{
		//   $time = false;
		// }
		return $time;
	}
}
