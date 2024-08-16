<?php
require_once(dirname(__DIR__).'/libraries/rutas.php');
require_once(rutaBase.'php'.DS.'vendor'.DS.'autoload.php');
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
class excel
{
	public static function array_to_excel( $titulo, $array ){
		//return count($array[0]);
		$letra = 'A';
		for ($i=0; $i < count($array[0]); $i++) {
			$arrayLetrasColumnas[] = $letra++;
		}

		//configuracion de archivo de salida excel
		$prefijoNombreArchivo = date('YmdHis')."_";
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set document properties
		$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		    ->setLastModifiedBy('Maarten Balliauw')
		    ->setTitle('Office 2007 XLSX Test Document')
		    ->setSubject('Office 2007 XLSX Test Document')
		    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		    ->setKeywords('office 2007 openxml php')
		    ->setCategory('Test result file');
		$sheet = $spreadsheet->getActiveSheet();

		$filaActual = 1;
		//recorro las filas
		foreach ( $array as $fila ) {
			foreach ( $fila as $posicionColumna => $campo ) {
				$sheet->setCellValue($arrayLetrasColumnas[$posicionColumna].$filaActual,$campo);
			}
			$filaActual++;
		}

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('data');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Xlsx)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$prefijoNombreArchivo.$titulo.'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		//$writer->save('php://output');
		ob_start();
		$writer->save('php://output');
		$xlsData = ob_get_contents();
		ob_end_clean();

		$respuesta =  array(
			'status' => '1',
			'filename' => $prefijoNombreArchivo.$titulo.'.xlsx',
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData),
			'base64' => base64_encode($xlsData)
		);
		return $respuesta;
	}

	public static function array_to_excel_titulo( $nombreArchivo, $titulo, $array ){
		//return count($array[0]);
		$letra = 'A';
		for ($i=0; $i < count($array[0]); $i++) {
			$arrayLetrasColumnas[] = $letra;
			$ultimaLetra = $letra;
			$letra++;
		}

		//configuracion de archivo de salida excel
		$prefijoNombreArchivo = date('YmdHis')."_";
		// Create new Spreadsheet object
		$spreadsheet = new Spreadsheet();

		// Set document properties
		$spreadsheet->getProperties()->setCreator('Maarten Balliauw')
		    ->setLastModifiedBy('Maarten Balliauw')
		    ->setTitle('Office 2007 XLSX Test Document')
		    ->setSubject('Office 2007 XLSX Test Document')
		    ->setDescription('Test document for Office 2007 XLSX, generated using PHP classes.')
		    ->setKeywords('office 2007 openxml php')
		    ->setCategory('Test result file');
		$sheet = $spreadsheet->getActiveSheet();

		//la fila 1 la destino para el titulo
		//combinamos las celdas en el rango que vamos a trabajar para el titulo
		$style = array(
			'alignment' => array(
				'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
			)
		);
		$spreadsheet->getActiveSheet()->mergeCells('A1:'. $ultimaLetra.'1')->getStyle("A1:B1")->applyFromArray($style);
		//asignamos el valor a la primer celdas del rango combinado
		$sheet->setCellValue('A1',$titulo);

		// $style = array(
		// 	'alignment' => array(
		// 		'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
		// 	)
		// );

		// $sheet->getStyle("A1:B1")->applyFromArray($style);

		$filaActual = 2;
		//recorro las filas
		foreach ( $array as $fila ) {
			foreach ( $fila as $posicionColumna => $campo ) {
				$sheet->setCellValue($arrayLetrasColumnas[$posicionColumna].$filaActual,$campo);
			}
			$filaActual++;
		}

		// Rename worksheet
		$spreadsheet->getActiveSheet()->setTitle('data');

		// Set active sheet index to the first sheet, so Excel opens this as the first sheet
		$spreadsheet->setActiveSheetIndex(0);

		// Redirect output to a client’s web browser (Xlsx)
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.$prefijoNombreArchivo.$nombreArchivo.'.xlsx"');
		header('Cache-Control: max-age=0');
		// If you're serving to IE 9, then the following may be needed
		header('Cache-Control: max-age=1');

		// If you're serving to IE over SSL, then the following may be needed
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
		header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
		header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
		header('Pragma: public'); // HTTP/1.0

		$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
		//$writer->save('php://output');
		ob_start();
		$writer->save('php://output');
		$xlsData = ob_get_contents();
		ob_end_clean();

		$respuesta =  array(
			'status' => '1',
			'filename' => $prefijoNombreArchivo.$nombreArchivo.'.xlsx',
			'file' => "data:application/vnd.ms-excel;base64,".base64_encode($xlsData)
		);
		return $respuesta;
	}
}
?>