<?php

/**
 * Clase y funciones para utilidades necesarias
 */
class Utilidades
{

	public static function conversion($tipo, $valor)
	{
		// Los tipos de conversion
		// pixel_to_mm
		switch ($tipo) {
			case 'pixel_to_mm':
				return $valor * 0.2645833333;
				break;

			case 'mm_to_pixel':
				return $valor * 3.7795275591;
				// return $valor / 0.189393939394;
				break;

			default:
				# code...
				break;
		}
	}

	public static function remover_acentos($cadena)
	{
		$restringidos = array('À', 'Á', 'Â', 'Ã', 'Ä', 'Å', 'Æ', 'Ç', 'È', 'É', 'Ê', 'Ë', 'Ì', 'Í', 'Î', 'Ï', 'Ð', 'Ñ', 'Ò', 'Ó', 'Ô', 'Õ', 'Ö', 'Ø', 'Ù', 'Ú', 'Û', 'Ü', 'Ý', 'ß', 'à', 'á', 'â', 'ã', 'ä', 'å', 'æ', 'ç', 'è', 'é', 'ê', 'ë', 'ì', 'í', 'î', 'ï', 'ñ', 'ò', 'ó', 'ô', 'õ', 'ö', 'ø', 'ù', 'ú', 'û', 'ü', 'ý', 'ÿ', 'Ā', 'ā', 'Ă', 'ă', 'Ą', 'ą', 'Ć', 'ć', 'Ĉ', 'ĉ', 'Ċ', 'ċ', 'Č', 'č', 'Ď', 'ď', 'Đ', 'đ', 'Ē', 'ē', 'Ĕ', 'ĕ', 'Ė', 'ė', 'Ę', 'ę', 'Ě', 'ě', 'Ĝ', 'ĝ', 'Ğ', 'ğ', 'Ġ', 'ġ', 'Ģ', 'ģ', 'Ĥ', 'ĥ', 'Ħ', 'ħ', 'Ĩ', 'ĩ', 'Ī', 'ī', 'Ĭ', 'ĭ', 'Į', 'į', 'İ', 'ı', 'Ĳ', 'ĳ', 'Ĵ', 'ĵ', 'Ķ', 'ķ', 'Ĺ', 'ĺ', 'Ļ', 'ļ', 'Ľ', 'ľ', 'Ŀ', 'ŀ', 'Ł', 'ł', 'Ń', 'ń', 'Ņ', 'ņ', 'Ň', 'ň', 'ŉ', 'Ō', 'ō', 'Ŏ', 'ŏ', 'Ő', 'ő', 'Œ', 'œ', 'Ŕ', 'ŕ', 'Ŗ', 'ŗ', 'Ř', 'ř', 'Ś', 'ś', 'Ŝ', 'ŝ', 'Ş', 'ş', 'Š', 'š', 'Ţ', 'ţ', 'Ť', 'ť', 'Ŧ', 'ŧ', 'Ũ', 'ũ', 'Ū', 'ū', 'Ŭ', 'ŭ', 'Ů', 'ů', 'Ű', 'ű', 'Ų', 'ų', 'Ŵ', 'ŵ', 'Ŷ', 'ŷ', 'Ÿ', 'Ź', 'ź', 'Ż', 'ż', 'Ž', 'ž', 'ſ', 'ƒ', 'Ơ', 'ơ', 'Ư', 'ư', 'Ǎ', 'ǎ', 'Ǐ', 'ǐ', 'Ǒ', 'ǒ', 'Ǔ', 'ǔ', 'Ǖ', 'ǖ', 'Ǘ', 'ǘ', 'Ǚ', 'ǚ', 'Ǜ', 'ǜ', 'Ǻ', 'ǻ', 'Ǽ', 'ǽ', 'Ǿ', 'ǿ', 'Ά', 'ά', 'Έ', 'έ', 'Ό', 'ό', 'Ώ', 'ώ', 'Ί', 'ί', 'ϊ', 'ΐ', 'Ύ', 'ύ', 'ϋ', 'ΰ', 'Ή', 'ή');
		$permitidos = array('A', 'A', 'A', 'A', 'A', 'A', 'AE', 'C', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'D', 'N', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'Y', 's', 'a', 'a', 'a', 'a', 'a', 'a', 'ae', 'c', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'n', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'y', 'y', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'G', 'g', 'G', 'g', 'G', 'g', 'H', 'h', 'H', 'h', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'IJ', 'ij', 'J', 'j', 'K', 'k', 'L', 'l', 'L', 'l', 'L', 'l', 'L', 'l', 'l', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'OE', 'oe', 'R', 'r', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'W', 'w', 'Y', 'y', 'Y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 's', 'f', 'O', 'o', 'U', 'u', 'A', 'a', 'I', 'i', 'O', 'o', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'U', 'u', 'A', 'a', 'AE', 'ae', 'O', 'o', 'Α', 'α', 'Ε', 'ε', 'Ο', 'ο', 'Ω', 'ω', 'Ι', 'ι', 'ι', 'ι', 'Υ', 'υ', 'υ', 'υ', 'Η', 'η');
		return str_replace($restringidos, $permitidos, $cadena);
	}

	/*function quitar_tildes($cadena) {
		$no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
		$permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
		$texto = str_replace($no_permitidas, $permitidas ,$cadena);
		return $texto;
	}*/

	public static function format_money($number, $fractional = false)
	{
		if ($fractional) {
			$number = sprintf('%.2f', $number);
		}
		while (true) {
			$replaced = preg_replace('/(-?\d+)(\d\d\d)/', '$1.$2', $number);
			if ($replaced != $number) {
				$number = $replaced;
			} else {
				break;
			}
		}
		return $number;
	}

	public static function FillZero($campo, $digitos)
	{
		return str_pad($campo, $digitos, '0', STR_PAD_LEFT);
	}

	// Ordenamiento de array de 2 niveles, 2 parametro, puede ser por posicion o nombre del valor
	public static function array_sort($array, $posicion, $order = SORT_ASC)
	{
		$new_array = array();
		$sortable_array = array();

		if (count($array) > 0) {
			foreach ($array as $k => $v) {
				if (is_array($v)) {
					foreach ($v as $k2 => $v2) {
						if ($k2 == $posicion) {
							$sortable_array[$k] = $v2;
						}
					}
				} else {
					$sortable_array[$k] = $v;
				}
			}

			switch ($order) {
				case SORT_ASC:
					asort($sortable_array);
					break;
				case SORT_DESC:
					arsort($sortable_array);
					break;
			}

			foreach ($sortable_array as $k => $v) {
				$new_array[$k] = $array[$k];
			}
		}

		return $new_array;
	}

	public static function reemplazar_ultimo($buscar, $remplazar, $texto)
	{
		$pos = strrpos($texto, $buscar);
		if ($pos !== false) {
			$texto = substr_replace($texto, $remplazar, $pos, strlen($buscar));
		}
		return $texto;
	}

	public static function icono_archivo($tipo_archivo)
	{
		$icon = "";
		switch ($tipo_archivo) {
			case 'audio/aac':
			case 'audio/midi audio/x-midi':
			case 'audio/mpeg':
			case 'audio/ogg':
			case 'audio/opus':
			case 'audio/wav':
			case 'audio/webm':
			case 'audio/3gpp':
			case 'audio/3gpp2':
			case 'audio/x-ms-wma':
			case 'audio/mp4':
			case 'audio/x-wav':
				$icon = "<i class='fas fa-2x fa-file-audio' style='color:#0F5268;margin-right:10px;'></i>";
				break;
			case 'video/x-msvideo':
			case 'video/mpeg':
			case 'video/ogg':
			case 'video/mp2t':
			case 'video/webm':
			case 'video/mp4':
			case 'video/3gpp':
			case 'video/3gpp2':
			case 'video/x-fli':
			case 'video/h261':
			case 'video/h263':
			case 'video/h264':
			case 'video/x-ms-wm':
			case 'video/quicktime':
				$icon = "<i class='fas fa-2x fa-file-video' style='color:#E04A3A;margin-right:10px;'></i>";
				break;
			case 'image/png':
			case 'image/svg+xml':
			case 'image/x-rgb':
			case 'image/tiff':
			case 'image/webp':
			case 'image/bmp':
			case 'image/gif':
			case 'image/x-icon':
			case 'image/jpeg':
				$icon = "<i class='fas fa-2x fa-file-image' style='color:#6EAAB3;margin-right:10px;'></i>";
				break;
			case 'application/pdf':
			case 'application/postscript':
				$icon = "<i class='fas fa-2x fa-file-pdf' style='color:#F34646;margin-right:10px;'></i>";
				break;
			case 'application/x-7z-compressed':
			case 'application/x-rar-compressed':
			case 'application/zipp':
			case 'application/x-bzip':
			case 'application/x-bzip2':
				$icon = "<i class='fas fa-2x fa-file-archive' style='color:#80358E;margin-right:10px;'></i>";
				break;
			case 'application/msword':
			case 'application/vnd.oasis.opendocument.text':
				$icon = "<i class='fas fa-2x fa-file-word' style='color:#295294;margin-right:10px;'></i>";
				break;
			case 'application/vnd.ms-excel':
			case 'application/vnd.oasis.opendocument.spreadsheet':
			case 'text/csv':
				$icon = "<i class='fas fa-2x fa-file-excel' style='color:#1F6E43;margin-right:10px;'></i>";
				break;
			case 'application/vnd.ms-powerpoint':
				$icon = "<i class='fas fa-2x fa-file-powerpoint' style='color:#F9C939;margin-right:10px;'></i>";
				break;
			case 'text/plain':
			case 'text/html':
			case 'text/css':
			case 'application/javascript':
			case 'application/json':
			case 'application/xml':
				$icon = "<i class='fas fa-2x fa-file-alt' style='color:#FFFFFF;margin-right:10px;'></i>";
				break;
			default:
				$icon = "<i class='fas fa-2x fa-file' style='color:#E8D792;margin-right:10px;'></i>";
				break;
		}

		return $icon;
	}

	public static function Hash($password)
	{
		return password_hash($password, PASSWORD_DEFAULT);
	}

	public static function VerificarHash($password, $hash)
	{
		return password_verify($password, $hash);
	}
}
