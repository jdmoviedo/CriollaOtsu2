<?php

/**
 * libreria para calculos de tiempo entre periodos
 */
class tiempo
{
	public function intervalos_hh_mm_periodo_tiempo($horaInicial, $horaFinal, $intervaloSegundos, $formatoHoras)
	{
		date_default_timezone_set('America/Bogota');
		$horaInicial = date('Y-m-d H:i:s', strtotime($horaInicial));
		$horaFinal = date('Y-m-d H:i:s', strtotime($horaFinal));

		//echo $horaInicial.' final:'.$horaFinal;
		$arrayIntervalos = array();
		//for ( $i=0; $i < 12; $i++ ) {
		while ($horaInicial <= $horaFinal) {

			//guardo segun el formato
			switch ($formatoHoras) {
				case '12':
					$arrayIntervalos[] = date('h:i A', strtotime($horaInicial));
					break;
				case '24':
					//$arrayIntervalos[] = date('H:i', strtotime($horaInicial));
					$arrayIntervalos[] = date('G:i', strtotime($horaInicial));
					break;
				default:
					$arrayIntervalos[] = date('H:i', strtotime($horaInicial));
					break;
			}
			$horaInicial = date('Y-m-d H:i:s', strtotime('+' . $intervaloSegundos . ' seconds', strtotime($horaInicial)));
		}
		//}
		if (count($arrayIntervalos) > 0) {
			return $arrayIntervalos;
		} else {
			return false;
		}
		// $fechaHoraHasta = strtotime ( '-1 minute' , strtotime ( $HoraActual ) ) ;
		// $fechaHoraHasta = date ( 'H:i' , $fechaHoraHasta );
		// $fechaHoraDesde = strtotime ( '-8 hour' , strtotime ( $fechaHoraActual ) ) ;
		// $fechaHoraDesde = date ( 'Y-m-d H:i:s' , $fechaHoraDesde );
		// $arrayValoresBuscados = array("Ń","ń");
	}

	public function intervalo_segun_horas($arrayHorasTexto)
	{
		//determina el intervalo minimo en minutos que hay entre varios periodos de tiempo
		//arrayOrdenado
		$arrayOrdenado = array();
		while (count($arrayHorasTexto) > 0) {
			$menorValor = 10000;
			foreach ($arrayHorasTexto as $key => $horaTexto) {
				$valorNumericoHora = str_replace(':', '', $horaTexto);

				if ($valorNumericoHora < $menorValor) {
					$menorValor = $valorNumericoHora;
					$posicionMenorValor = $key;
				} else {
				}
			}
			// for ($i = 0; $i < count($arrayHorasTexto); $i++) {
			// 	$valorNumericoHora = str_replace(':', '', $arrayHorasTexto[$i]);

			// 	if ($valorNumericoHora < $menorValor) {
			// 		$menorValor = $valorNumericoHora;
			// 		$posicionMenorValor = $i;
			// 	} else {
			// 	}
			// }
			$arrayOrdenado[] = $menorValor;
			unset($arrayHorasTexto[$posicionMenorValor]);
		}

		sort($arrayOrdenado);

		//determino el intervalo minimo que hay entre los periodos de tiempo
		$intervaloMinimo = 10000;
		for ($i = 0; $i < count($arrayOrdenado); $i++) {
			if (($i + 1) < count($arrayOrdenado)) {

				if (($arrayOrdenado[$i + 1] - $arrayOrdenado[$i]) < $intervaloMinimo) {
					$intervaloMinimo = $arrayOrdenado[$i + 1] - $arrayOrdenado[$i];
				}
			}
		}

		//ordeno el array
		return $intervaloMinimo;
	}

	public static function tiempo_faltante_horas($fechaHoraInicial, $fechaHoraFinal)
	{

		if (strtotime($fechaHoraFinal) > strtotime($fechaHoraInicial)) {
			$fechaHoraInicial = new DateTime($fechaHoraInicial);
			$fechaHoraFinal = new DateTime($fechaHoraFinal);

			$interval = $fechaHoraInicial->diff($fechaHoraFinal);
			$respuesta = array(
				"dias" => $interval->format('%d'),
				"horas" => $interval->format('%h'),
				"minutos" => $interval->format('%i'),
				"segundos" => $interval->format('%s')
			);
			//print_r($respuesta);
		} else {
			$respuesta = false;
		}

		return $respuesta;
	}
}
