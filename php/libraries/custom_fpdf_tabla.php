<?php
// require_once(dirname(__DIR__) . '/libraries/rutas.php');
require(rutaBase . 'php/vendor/autoload.php');
require_once(rutaBase . 'php/libraries/custom_fpdf.php');
/**
 *
 */
class tabla_fpdf extends custom_fpdf
{

	var $widths;
	function SetWidths($w)
	{
		$this->widths = $w;
	}

	var $aligns;
	function SetAligns($a)
	{
		//Establece alineacion a cada celda
		$this->aligns = $a;
	}

	function LinePoint($inicio, $ancho)
	{
		for ($j = $inicio; $j <= $this->GetPageWidth() - $inicio; $j += $ancho) {
			$this->Line($j, $this->GetY() + 5, $j + 1, $this->GetY() + 5);
		}
	}

	function cellColor($fill, $text)
	{
		$this->SetFillColor($fill[0], $fill[1], $fill[2]);
		$this->SetTextColor($text[0], $text[1], $text[2]);
	}

	function cabeceraHorizontal($cabecera, $borde = 1, $alineacion = 'C', $fontsize = 8, $fill = false)
	{
		$this->SetFont('robotocondensed', 'B', $fontsize);
		// $this->SetFillColor(255,255,255);//Fondo verde de celda
		if (!$fill) {
			$this->SetTextColor(0, 0, 0); //Letra color negro
		}
		$nb = 0;
		for ($i = 0; $i < count($cabecera); $i++) {
			$nb = max($nb, $this->NbLines($this->widths[$i], $cabecera[$i]));
		}
		$h = 5 * $nb;
		for ($i = 0; $i < count($cabecera); $i++) {
			$w = $this->widths[$i];
			$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
			//Save the current position
			$x = $this->GetX();
			$y = $this->GetY();
			//Draw the border
			if ($borde) {
				$this->Rect($x, $y, $w, $h);
			}
			//Print the text
			$nbActual = $this->NbLines($this->widths[$i], $cabecera[$i]);
			$text = stripslashes($cabecera[$i]);
			$text = iconv('UTF-8', 'windows-1252', $text);
			// $this->SetY(2);
			$this->MultiCell($w, $h / $nbActual, $text, 0, $a, $fill);
			// $this->CellFitSpace($this->widths[$i],7,utf8_decode($fila[$i]),1,0,$a,$bandera);
			//Put the position to the right of the cell
			if ($i != count($cabecera) - 1) {
				$this->SetXY($x + $w, $y);
			}
		}
	}

	function datosHorizontal($datos, $borde = true, $bold = "", $fontsize = 8, $fill = false, $borderparcial = false, $alto = 5)
	{
		$this->SetFont('robotocondensed', $bold, $fontsize);
		if ($fill == true) {
			$this->SetFillColor(229, 229, 229); //Gris tenue de cada fila
		}
		$this->SetTextColor(3, 3, 3); //Color del texto: Negro
		$bandera = false; //Para alternar el relleno
		// $this->SetFillColor('211','211','211');
		foreach ($datos as $fila) {
			$nb = 0;
			for ($i = 0; $i < count($fila); $i++) {
				$nb = max($nb, $this->NbLines($this->widths[$i], $fila[$i]));
			}
			$h = 5 * $nb;
			// Agregarmos un salto de linea si es necesario
			$this->CheckPageBreak($h);
			for ($i = 0; $i < count($fila); $i++) {
				$w = $this->widths[$i];
				$a = isset($this->aligns[$i]) ? $this->aligns[$i] : 'C';
				//Save the current position
				$x = $this->GetX();
				$y = $this->GetY();
				//Draw the border
				if ($borderparcial) {
					if ($i == count($fila) - 1 || $i == count($fila) - 2) {
						$this->Rect($x, $y, $w, $h);
					}
				} else {
					if ($borde) {
						$this->Rect($x, $y, $w, $h);
					}
				}
				//Print the text
				// $this->SetY(2);
				$nbActual = $this->NbLines($this->widths[$i], $fila[$i]);
				$text = stripslashes($fila[$i]);
				$text = iconv('UTF-8', 'windows-1252', $text);
				$this->MultiCell($w, $h / $nbActual, $text, 0, $a, $fill);
				// $this->CellFitSpace($this->widths[$i],7,utf8_decode($fila[$i]),1,0,$a,$bandera);
				//Put the position to tDhe right of the cell
				$this->SetXY($x + $w, $y);
			}
			$this->Ln($h);
			$bandera = !$bandera; //Alterna el valor de la bandera
		}
		// $this->Ln($h);
	}

	function tablaHorizontal($cabeceraHorizontal, $datosHorizontal)
	{
		$this->cabeceraHorizontal($cabeceraHorizontal);
		$this->Ln();
		$this->datosHorizontal($datosHorizontal);
	}

	function CellFit($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '', $scale = false, $force = true)
	{
		//Get string width
		$str_width = $this->GetStringWidth($txt);

		//Calculate ratio to fit cell
		if ($w == 0) {
			$w = $this->w - $this->rMargin - $this->x;
		}

		$ratio = ($w - $this->cMargin * 2) / $str_width;

		$fit = ($ratio < 1 || ($ratio > 1 && $force));

		if ($fit) {
			if ($scale) {
				//Calculate horizontal scaling
				$horiz_scale = $ratio * 100.0;
				//Set horizontal scaling
				$this->_out(sprintf('BT %.2F Tz ET', $horiz_scale));
			} else {
				//Calculate character spacing in points
				$char_space = ($w - $this->cMargin * 2 - $str_width) / max($this->MBGetStringLength($txt) - 1, 1) * $this->k;
				//Set character spacing
				$this->_out(sprintf('BT %.2F Tc ET', $char_space));
			}
			//Override user alignment (since text will fill up cell)
			$align = '';
		}

		//Pass on to Cell method
		$this->Cell($w, $h, $txt, $border, $ln, $align, $fill, $link);

		//Reset character spacing/horizontal scaling
		if ($fit) {
			$this->_out('BT ' . ($scale ? '100 Tz' : '0 Tc') . ' ET');
		}
	}

	function CellFitSpace($w, $h = 0, $txt = '', $border = 0, $ln = 0, $align = '', $fill = false, $link = '')
	{
		$this->CellFit($w, $h, $txt, $border, $ln, $align, $fill, $link, false, false);
	}

	function MBGetStringLength($s)
	{
		if ($this->CurrentFont['type'] == 'Type0') {
			$len = 0;
			$nbbytes = strlen($s);
			for ($i = 0; $i < $nbbytes; $i++) {
				if (ord($s[$i]) < 128)
					$len++;
				else {
					$len++;
					$i++;
				}
			}
			return $len;
		} else {
			return strlen($s);
		}
	}

	function CheckPageBreak($h)
	{
		//If the height h would cause an overflow, add a new page immediately
		if ($this->GetY() + $h > $this->PageBreakTrigger) {
			$this->AddPage($this->CurOrientation);
		}
	}

	function NbLines($w, $txt)
	{
		//Computes the number of lines a MultiCell of width w will take
		$cw = &$this->CurrentFont['cw'];
		if ($w == 0) {
			$w = $this->w - $this->rMargin - $this->x;
		}
		$wmax = ($w - 2 * $this->cMargin) * 1000 / $this->FontSize;
		$s = str_replace("\r", '', $txt);
		$nb = strlen($s);
		if ($nb > 0 and $s[$nb - 1] == "\n") {
			$nb--;
		}
		$sep = -1;
		$i = 0;
		$j = 0;
		$l = 0;
		$nl = 1;
		while ($i < $nb) {
			$c = $s[$i];
			if ($c == "\n") {
				$i++;
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
				continue;
			}
			if ($c == ' ') {
				$sep = $i;
			}
			$l += $cw[$c];
			if ($l > $wmax) {
				if ($sep == -1) {
					if ($i == $j) {
						$i++;
					}
				} else {
					$i = $sep + 1;
				}
				$sep = -1;
				$j = $i;
				$l = 0;
				$nl++;
			} else {
				$i++;
			}
		}
		return $nl;
	}

	function RoundedRect($x, $y, $w, $h, $r, $style = '', $angle = '1234')
	{
		$k = $this->k;
		$hp = $this->h;
		if ($style == 'F')
			$op = 'f';
		elseif ($style == 'FD' or $style == 'DF')
			$op = 'B';
		else
			$op = 'S';
		$MyArc = 4 / 3 * (sqrt(2) - 1);
		$this->_out(sprintf('%.2f %.2f m', ($x + $r) * $k, ($hp - $y) * $k));

		$xc = $x + $w - $r;
		$yc = $y + $r;
		$this->_out(sprintf('%.2f %.2f l', $xc * $k, ($hp - $y) * $k));
		if (strpos($angle, '2') === false)
			$this->_out(sprintf('%.2f %.2f l', ($x + $w) * $k, ($hp - $y) * $k));
		else
			$this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc, $xc + $r, $yc);

		$xc = $x + $w - $r;
		$yc = $y + $h - $r;
		$this->_out(sprintf('%.2f %.2f l', ($x + $w) * $k, ($hp - $yc) * $k));
		if (strpos($angle, '3') === false)
			$this->_out(sprintf('%.2f %.2f l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
		else
			$this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc, $yc + $r);

		$xc = $x + $r;
		$yc = $y + $h - $r;
		$this->_out(sprintf('%.2f %.2f l', $xc * $k, ($hp - ($y + $h)) * $k));
		if (strpos($angle, '4') === false)
			$this->_out(sprintf('%.2f %.2f l', ($x) * $k, ($hp - ($y + $h)) * $k));
		else
			$this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc, $xc - $r, $yc);

		$xc = $x + $r;
		$yc = $y + $r;
		$this->_out(sprintf('%.2f %.2f l', ($x) * $k, ($hp - $yc) * $k));
		if (strpos($angle, '1') === false) {
			$this->_out(sprintf('%.2f %.2f l', ($x) * $k, ($hp - $y) * $k));
			$this->_out(sprintf('%.2f %.2f l', ($x + $r) * $k, ($hp - $y) * $k));
		} else
			$this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc, $yc - $r);
		$this->_out($op);
	}

	function _Arc($x1, $y1, $x2, $y2, $x3, $y3)
	{
		$h = $this->h;
		$this->_out(sprintf(
			'%.2f %.2f %.2f %.2f %.2f %.2f c ',
			$x1 * $this->k,
			($h - $y1) * $this->k,
			$x2 * $this->k,
			($h - $y2) * $this->k,
			$x3 * $this->k,
			($h - $y3) * $this->k
		));
	}
	//MultiCell with bullet
	function MultiCellBlt($w, $h, $blt, $txt, $border = 0, $align = 'J', $fill = false)
	{
		//Get bullet width including margins
		$blt_width = $this->GetStringWidth($blt) + $this->cMargin * 2;

		//Save x
		$bak_x = $this->x;

		//Output bullet
		$this->Cell($blt_width, $h, $blt, 0, '', $fill);

		//Output text
		$this->MultiCell($w - $blt_width, $h, $txt, $border, $align, $fill);

		//Restore x
		$this->x = $bak_x;
	}
}
