<?php
/**
*
*/
class fechas
{
	private $arrayMeses,$arrayDias;

	function __construct()
	{
		$this->arrayMeses = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
   					 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');

		$this->arrayDias = array( 'Domingo', 'Lunes', 'Martes',
   				'Miercoles', 'Jueves', 'Viernes', 'Sabado');

		$this->mesesLetras = array('EN', 'FB', 'MR', 'AB', 'MY', 'JN', 'JL', 'AG', 'SP', 'OC','NV', 'DC');
	}

	public function formato1($fechaHora){
		$fechaFormateada = date('d',strtotime($fechaHora))."/".date('m',strtotime($fechaHora))."/".date('Y',strtotime($fechaHora));
		return $fechaFormateada;
	}
	//$arrayDias[date('w',strtotime($fechaHora))].", ".date('d',strtotime($fechaHora))." de ".$arrayMeses[date('m',strtotime($fechaHora))-1]." del ".date('Y',strtotime($fechaHora))." (".date('h:i a', strtotime($fechaHora)).")",
	public function formato2($fechaHora){//lunes 8 de marzo de 2017
		$fechaFormateada = $this->arrayDias[date('w',strtotime($fechaHora))].", ".date('d',strtotime($fechaHora))." de ".$this->arrayMeses[date('m',strtotime($fechaHora))-1]." del ".date('Y',strtotime($fechaHora));
		return $fechaFormateada;
	}

	public function formato3($fechaHora){//lunes 8 de marzo
		$fechaFormateada = $this->arrayDias[date('w',strtotime($fechaHora))]." ".date('d',strtotime($fechaHora))." de ".$this->arrayMeses[date('m',strtotime($fechaHora))-1];
		return $fechaFormateada;
	}

	public function formato4($fechaHora){//marzo 8 de 2017
		$fechaFormateada = $this->arrayMeses[date('m',strtotime($fechaHora))-1]." ".date('d',strtotime($fechaHora))." de ".date('Y',strtotime($fechaHora));
		return $fechaFormateada;
	}

	public function formato5($fechaHora){//dia_mes_año corto - 29_11_17
		$fechaFormateada = date('d',strtotime($fechaHora))."_".date('m',strtotime($fechaHora))."_".date('y',strtotime($fechaHora));
		return $fechaFormateada;
	}

	public function formato6($fechaHora){//
		$fechaFormateada = date('d',strtotime($fechaHora))."-".date('m',strtotime($fechaHora))."-".date('Y',strtotime($fechaHora));
		return $fechaFormateada;
	}

	public function formato7($fechaHora){//8 de marzo de 2017
		$fechaFormateada = date('d',strtotime($fechaHora))." de ".$this->arrayMeses[date('m',strtotime($fechaHora))-1]." de ".date('Y',strtotime($fechaHora));
		return $fechaFormateada;
	}

	public function formato8($fechaHora){//8 de marzo de 2017
		$fechaFormateada = date('Y',strtotime($fechaHora)).date('m',strtotime($fechaHora)).date('d',strtotime($fechaHora));
		return $fechaFormateada;
	}

	public function formato9($fechaHora){
		$fechaFormateada = date('Y',strtotime($fechaHora)).date('m',strtotime($fechaHora)).date('d',strtotime($fechaHora));
		return $fechaFormateada;
	}

	public function formato10($fechaHora){//
		$fechaFormateada = date('Y',strtotime($fechaHora))."-".date('m',strtotime($fechaHora))."-".date('d',strtotime($fechaHora));
		return $fechaFormateada;
	}

	public function formato11($fechaHora){// Retronar la abreviatura del mes
		$fechaFormateada = $this->mesesLetras[date('m',strtotime($fechaHora))-1];
		return $fechaFormateada;
	}

	public function sumardias($fecha,$dias){
		$nuevaFecha = $fecha;
		$nuevaFecha = strtotime ( '+'.$dias.' day' , strtotime ( $fecha ) ) ;
		$nuevaFecha = date ( 'Y-m-d' , $nuevaFecha );
		return $nuevaFecha;
	}

	public function formato12($fechaHora){//8 de marzo de 2017
		$fechaFormateada = date('d',strtotime($fechaHora))." DE ".strtoupper($this->arrayMeses[date('m',strtotime($fechaHora))-1])." DE ".date('Y',strtotime($fechaHora));
		return $fechaFormateada;
	}

	public function formato13($fechaHora)
	{ //08/03/2017 13:23:30
		$fechaFormateada = date('d', strtotime($fechaHora)) . "/" . date('m', strtotime($fechaHora)) . "/" . date('Y', strtotime($fechaHora)) . " " . date('h:i:s a', strtotime($fechaHora));
		return $fechaFormateada;
	}
	// Retorna un array con fecha inicial y fecha final de cada mes en el rango parametrizado
	public function fecha_mensual($fechaInicial,$fechaFinal){
		$objectDiferencia = date_diff(date_create(date('Y-m',strtotime($fechaInicial))), date_create(date('Y-m',strtotime($fechaFinal))));
		$arrayFechas = array();
		if( $objectDiferencia->y > 0 ){
			$meses = $objectDiferencia->y * 12 + $objectDiferencia->m;

			for ($i=0; $i <= $meses ; $i++) {

				$nuevaFecha = strtotime ( '+'.$i.' month' , strtotime ( $fechaInicial ) ) ;
				$nuevaFecha = date ( 'Y-m-d' , $nuevaFecha );

				// Obtenemos el rango inicial de cada mes
				$fecha = new DateTime($nuevaFecha);

				$primer_dia = $fecha->modify('first day of this month');
				$rangoInicial = $primer_dia->format('Y-m-d');

				$ultimo_dia = $fecha->modify('last day of this month');
				$rangoFinal = $ultimo_dia->format('Y-m-d');

				// Almacenamos un array de fecha inicial y final
				$arrayFechas[] = array(
					"inicio" => $rangoInicial,
					"final" => $rangoFinal
				);
			}
		}elseif( $objectDiferencia->m >= 0 ){
			for ($i=0; $i <= $objectDiferencia->m ; $i++) {
				$nuevaFecha = strtotime ( '+'.$i.' month' , strtotime ( $fechaInicial ) ) ;
				$nuevaFecha = date ( 'Y-m-d' , $nuevaFecha );

				// Obtenemos el rango inicial de cada mes
				$fecha = new DateTime($nuevaFecha);

				$primer_dia = $fecha->modify('first day of this month');
				$rangoInicial = $primer_dia->format('Y-m-d');

				$ultimo_dia = $fecha->modify('last day of this month');
				$rangoFinal = $ultimo_dia->format('Y-m-d');

				// Almacenamos un array de fecha inicial y final
				$arrayFechas[] = array(
					"inicio" => $rangoInicial,
					"final" => $rangoFinal
				);
			}
		}

		// Recorrer y obtener los datos retornados
		// foreach ($arrayFechas as $arrayRango) {
		// 	echo $arrayRango['inicio']." - ".$arrayRango['final'];
		// 	echo "<br>";
		// }

		return $arrayFechas;
	}
}
?>