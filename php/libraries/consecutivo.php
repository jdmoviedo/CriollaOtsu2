<?php
/**
 *
 */
class consecutivo
{
	public static function generar_consecutivo($codigo){
		require_once(dirname(__DIR__).'/libraries/rutas.php');
		require_once(rutaBase.'php/conexion/conexion.php');
        $conexion = new conexion();
		$connPgsql = $conexion->conectar();

        //$concepto = pg_escape_string($concepto);
		switch ( $codigo ) {
			case 'HISTORIA':
				$codigo = 'HIS'	;
			break;
			case 'REGISTRO':
				$codigo = 'REG'	;
			break;
			case '1':
				$codigo = 'LAB'	;
			break;
			case 'SO':
				$codigo = 'SOC'	;
			break;
			default:

			break;
		}

        $sql = "UPDATE consecutivo
		SET secuencia=secuencia+1
		WHERE codigo = '".$codigo."' RETURNING secuencia;";

		$resultado = pg_query($connPgsql, $sql) or die("Error en la Consulta SQL");
		$filasafectadas = pg_affected_rows($resultado);
		if( $filasafectadas == 1 ){
			$numeroServicio = pg_fetch_array($resultado)['secuencia'];
			return $numeroServicio;
		}else{
			return false;
		}



        //cierro la conexion
        pg_free_result($resultado);
        pg_close($connPgsql);
	}
}
?>