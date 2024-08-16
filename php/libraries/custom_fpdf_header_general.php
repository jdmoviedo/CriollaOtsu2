<?php
require_once(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'libraries' . DIRECTORY_SEPARATOR . 'custom_fpdf_tabla.php');
/**
 *
 */
class custom_fpdf_header_general extends tabla_fpdf
{

	protected $bLogos = false;
	protected $bHorizontal = false;
	protected $titulo = "";
	protected $version = "";
	protected $subtitulo = "";

	function setBooleanLogo($booleanLogos)
	{
		$this->bLogos = $booleanLogos;
	}

	function setBooleanHorizontal($booleanHorizontal)
	{
		$this->bHorizontal = $booleanHorizontal;
	}

	function setTitulo($sTitulo)
	{
		$this->titulo = $sTitulo;
	}

	function setVersion($sVersion)
	{
		$this->version = $sVersion;
	}

	function setSubTitulo($sSubTitulo)
	{
		$this->subtitulo = $sSubTitulo;
	}


	function Header()
	{
		$anchoAreaImprimible = $this->GetPageWidth() - 20;
		$bLogos = $this->bLogos;
		$datosTitulo = $this->titulo;
		$datosVersion = $this->version;
		$bHorizontal = $this->bHorizontal;
		$datosSubTitulo = $this->subtitulo;
		$GetY = 0;

		if ($bLogos) {
			// Logo
			$this->Image(rutaBase . '/img/logo.png', 38, 13, 35);
		}
		// Arial bold 15
		$this->SetFont('robotocondensed', 'B', 14);
		// Movernos a la derecha
		$GetY = $this->GetY();
		$this->Cell(90, 15, "", 1);
		// Título
		if ($bHorizontal) {
			$this->MultiCell(190, 15, utf8_decode($datosTitulo), 1, "C");
		} else {
			$this->MultiCell(130, 15, utf8_decode($datosTitulo), 1, "C");
		}

		//fuente
		if ($datosVersion != "") {
			$this->SetFont('robotocondensed', 'B', 10);
			// Version
			$this->SetY($GetY);
			$arrayDatosVersion = explode('|VERSION|', $datosVersion);

			if ($arrayDatosVersion > 0) {
				for ($i = 0; $i < count($arrayDatosVersion); $i++) {
					if ($bHorizontal) {
						$this->Cell(280, 15, "", 0);
					} else {
						$this->Cell(220, 15, "", 0);
					}
					$this->MultiCell(55.6, 15 / count($arrayDatosVersion), utf8_decode($arrayDatosVersion[$i]), 1, 'C', false);
				}
			}
		}

		if ($datosSubTitulo != "") {
			$this->SetFont('robotocondensed', 'B', 8);
			if ($bHorizontal) {
				$this->MultiCell($anchoAreaImprimible, 5, utf8_decode($datosSubTitulo), 1, "C", false);
			} else {
				$this->MultiCell($anchoAreaImprimible, 5, utf8_decode($datosSubTitulo), 1, "C", false);
			}
		}
	}
}
