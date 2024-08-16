<?php
// require_once(dirname(__DIR__).'/libraries/rutas.php');
require_once(dirname(__DIR__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'autoload.php');
/**
 *
 */

class custom_fpdf extends FPDF
{

    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';
// Cabecera de página
    /*function Header()
    {
        $anchoAreaImprimible = $this->GetPageWidth()-10;
    // Logo
        $this->Image(rutaBase.'/images/tux.png',10,8,15);
    // Logo
        $this->Image(rutaBase.'/images/ILoveGpl.jpg',25,8,15);
    // Logo
        $this->Image(rutaBase.'/images/gnu.png',40,8,15);
    // Arial bold 15
        $this->SetFont('Arial','B',15);
    // Movernos a la derecha
        $this->Cell(80);
    // Título
        $this->Cell(30,10,'seanjamu.com',0,0,'C');
    //fuente
        $this->SetFont('Arial','',8);
    // Version
        $this->MultiCell(0, 4, utf8_decode("Versión: 1.0\nCode Example\nBy seanjamu"), 0, 'R', false);
        $this->Line(10, 23, $anchoAreaImprimible, 23);
    // Salto de línea
        $this->Ln(6);
    }*/

    // Pie de página
    /*function Footer(){
        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial','I',8);
        // Número de página
        $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
    }*/

    function WriteHTML($html){
        // Intérprete de HTML
        $html = str_replace("\n",' ',$html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
            // Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else
            {
            // Etiqueta
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                // Extraer atributos
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr){
        // Etiqueta de apertura
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF = $attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
    }

    function CloseTag($tag){
        // Etiqueta de cierre
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable){
        // Modificar estilo y escoger la fuente correspondiente
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
        {
            if($this->$s>0)
                $style .= $s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt){
        // Escribir un hiper-enlace
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }

    function TablaMulticell($data, $numeroColumnas, $saltoLinea, $saltoLineaFila, $alineacion, $anchoImprimible, $bordes = false,$upper = true){
        //terminos
        //data=> array con datos a insertar en la tabla
        //numeroColumnas=> numero de columnas que va a tener la tabla
        //saltoLinea=>salto de linea en el tparrafo de cada cedla en caso de que se presenten saltos
        //saltoLineaFila=>separacion adicional entre filas
        //alineacion=>alineacion del texto en cada celda de la tabla
        //anchoImprimible=>ancho de la tabla
        //$bordes=>si se va a dibujar o no los bordes de la tabla
        //posicion mas alta de Y iniciando
        $posicionYAlta = $this->GetY();
        $limiteSuperiorY = $posicionYAlta;
        //si hay que dibujar bordes dibujo el horizontal superior
        // if( $bordes == true ){
        //     $this->Line(10, $limiteSuperiorY, $anchoImprimible+10, $limiteSuperiorY);
        // }
        //posicion de la fila actual
        $posicionYFila = $posicionYAlta;
        //numero de items a iterar
        $numeroItems = count($data);
        //columna inicial por fila
        $columnaActual = 0;
        //ancho de cada columna
        $anchoColumna = $anchoImprimible/$numeroColumnas;
        for ($i=0; $i < $numeroItems; $i++) {
            //inicio la columna actual
            $columnaActual++;
            //si llego a la ultima columna
            if($i%$numeroColumnas == 0){
                //primero debo preguntar si laultima linea esta por fuera de los margenes
                //y cuantos puntos esta por fuera
                $ultimaLinea = round($this->GetPageHeight()-20-$saltoLinea-$saltoLineaFila);
                //si faltan lineas para el salto de pagina el resultado sera negativo
                $lineasFaltantesSalto = ( $posicionYAlta + $saltoLineaFila ) - $ultimaLinea;

                if( $lineasFaltantesSalto > 0 ){
                    //salto de pagina
                    $this->AddPage();
                    //reinicio la posicion y alta
                    $posicionYAlta = 0;

                    //dibujo lineas verticales hasta ese punto
                    if( $bordes == true ){
                        for ($ii=0; $ii <= $numeroColumnas; $ii++) {
                            $this->Line(($i*$anchoColumna)+10, $limiteSuperiorY, ($i*$anchoColumna)+10, $posicionYFila);
                        }
                    }

                    //reinicio el limite superior
                    $limiteSuperiorY = $this->GetY()+$lineasFaltantesSalto;
                    //establezco la posicion de la siguiente fila
                    $posicionYFila = $this->GetY()+$lineasFaltantesSalto+$saltoLineaFila;
                }else{
                    //establezco la posicion de la siguiente fila
                    $posicionYFila=$posicionYAlta+$saltoLineaFila;
                }

                //establezco la posicion en la coordenada Y
                $this->SetY($posicionYFila,false);
                //reinicio la columna actual
                $columnaActual = 0;
                //si hay que dibujar bordes
                if( $bordes == true ){//&& $i != 0
                    //dibujo las lineas horizontales
                    $this->Line(10, $posicionYFila-$saltoLineaFila, $anchoImprimible+10, $posicionYFila-$saltoLineaFila);
                }
            }
            //si ya hay columnas (>0) inserto una celda para posicionar el eje x
            if($columnaActual > 0)$this->Cell($anchoColumna*$columnaActual);
            //inserto la "MutiCell"
            if($upper){
                $this->MultiCell($anchoColumna, $saltoLinea, utf8_decode(mb_strtoupper($data[$i])), 0, $alineacion, false);
            }else{
                $this->MultiCell($anchoColumna, $saltoLinea, utf8_decode($data[$i]), 0, $alineacion, false);
            }
            
            //si al insertar la multicelda el texto crea varias lineas
            //y superan la posicion en Y actual establezco la posicion
            //mas alta como la posicion de Y actual
            if($this->GetY() > $posicionYAlta) $posicionYAlta=$this->GetY();
            //establezco la posicion de la coordenada Y para la siguiente columna
            $this->SetY($posicionYFila,false);
        }
        $posicionYFila = $posicionYAlta;
        //establezco la posicion en la coordenada Y
        $this->SetY($posicionYFila,true);


        //si hay que dibujar bordes
        if( $bordes == true ){
            //dibujo el borde horizontal inferior
            $this->Line(10, $posicionYFila, $anchoImprimible+10, $posicionYFila);
            //dibujo lineas verticales de la tabla
            for ($i=0; $i <= $numeroColumnas; $i++) {
                $this->Line(($i*$anchoColumna)+10, $limiteSuperiorY, ($i*$anchoColumna)+10, $posicionYFila);
            }
        }
    }

    //funciones de libreria sector.php para graficas
    //http://www.fpdf.org/en/script/script28.php
    function Sector($xc, $yc, $r, $a, $b, $style='FD', $cw=true, $o=90)
    {
        $d0 = $a - $b;
        if($cw){
            $d = $b;
            $b = $o - $a;
            $a = $o - $d;
        }else{
            $b += $o;
            $a += $o;
        }
        while($a<0)
            $a += 360;
        while($a>360)
            $a -= 360;
        while($b<0)
            $b += 360;
        while($b>360)
            $b -= 360;
        if ($a > $b)
            $b += 360;
        $b = $b/360*2*M_PI;
        $a = $a/360*2*M_PI;
        $d = $b - $a;
        if ($d == 0 && $d0 != 0)
            $d = 2*M_PI;
        $k = $this->k;
        $hp = $this->h;
        if (sin($d/2))
            $MyArc = 4/3*(1-cos($d/2))/sin($d/2)*$r;
        else
            $MyArc = 0;
        //first put the center
        $this->_out(sprintf('%.2F %.2F m',($xc)*$k,($hp-$yc)*$k));
        //put the first point
        $this->_out(sprintf('%.2F %.2F l',($xc+$r*cos($a))*$k,(($hp-($yc-$r*sin($a)))*$k)));
        //draw the arc
        if ($d < M_PI/2){
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
        }else{
            $b = $a + $d/4;
            $MyArc = 4/3*(1-cos($d/8))/sin($d/8)*$r;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
            $a = $b;
            $b = $a + $d/4;
            $this->_Arc($xc+$r*cos($a)+$MyArc*cos(M_PI/2+$a),
                        $yc-$r*sin($a)-$MyArc*sin(M_PI/2+$a),
                        $xc+$r*cos($b)+$MyArc*cos($b-M_PI/2),
                        $yc-$r*sin($b)-$MyArc*sin($b-M_PI/2),
                        $xc+$r*cos($b),
                        $yc-$r*sin($b)
                        );
        }
        //terminate drawing
        if($style=='F')
            $op='f';
        elseif($style=='FD' || $style=='DF')
            $op='b';
        else
            $op='s';
        $this->_out($op);
    }

    function _Arc($x1, $y1, $x2, $y2, $x3, $y3 )
    {
        $h = $this->h;
        $this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c',
            $x1*$this->k,
            ($h-$y1)*$this->k,
            $x2*$this->k,
            ($h-$y2)*$this->k,
            $x3*$this->k,
            ($h-$y3)*$this->k));
    }
}
