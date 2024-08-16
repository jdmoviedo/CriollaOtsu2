<?php
/**
 *
 */
class archivos
{
	public static function eliminar_archivo( $rutaArchivo ){
		if( file_exists( $rutaArchivo ) ){
			if( @unlink( $rutaArchivo ) === true ){
				return true;
			}else{
				return false;
			}
		}else{
			return true;
		}
	}

	public static function verificar_imagen($rutaArchivo)
	{
		if (is_file($rutaArchivo) && filesize($rutaArchivo) > 0) {
			try {
				$imagen = new Imagick($rutaArchivo);
				if ($imagen->valid()) {
					return true;
				} else {
					return false;
				}
			} catch (Exception $e) {
				return false;
			}
		} else {
			return false;
		}
	}
}
?>