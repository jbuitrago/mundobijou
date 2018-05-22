<?php

class PDF extends FPDF {

    private $_titulo = '';
    private $B;
    private $I;
    private $U;
    private $HREF = '';
    private $ALIGN = '';

    public function asignarTitulo($titulo) {
        $this->_titulo = $titulo;
    }

    public function Header() {
        $this->Image(Inicio::path() . '/FPDF/logo_mail.jpg', 170, 8, 33, 0, '', 'http://www.kirke.ws');
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $this->_titulo), 0, 0, 'L');
        $this->Ln(20);
    }

    public function Footer() {
        $this->SetY(-15); // Posición: a 1,5 cm del final
        $this->SetFont('Arial', 'I', 8);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
        $this->Ln(5);
        $this->SetFont('Arial', 'B', 8);
        $this->SetTextColor(255, 0, 0);
        $this->Cell(0, 10, iconv('UTF-8', 'ISO-8859-1//TRANSLIT', 'www.kirke.ws – Amenábar 2310 3ª 14 - Buenos Aires – Argentina - Tel: ++ 54 (11) 4781 - 8871 / ++ 54 (11) 15 – 6285 - 8483'), 0, 0, 'C');
    }

    public function PDF($orientation = 'P', $unit = 'mm', $size = 'A4') {
        // Llama al constructor de la clase padre
        $this->FPDF($orientation, $unit, $size);
        // Iniciación de variables
        $this->B = 0;
        $this->I = 0;
        $this->U = 0;
        $this->HREF = '';
    }

    public function WriteHTML($html) {
        // Intérprete de HTML
        $html = str_replace("\n", ' ', $html);
        $a = preg_split('/<(.*)>/U', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        foreach ($a as $i => $e) {
            if ($i % 2 == 0) {
                // Text
                if ($this->HREF) {
                    $this->PutLink($this->HREF, $e);
                } elseif ($this->ALIGN == 'center') {
                    $this->Cell(0, 5, $e, 0, 1, 'C');
                } else {
                    $this->Write(5, $e);
                }
            } else {
                // Etiqueta
                if ($e[0] == '/') {
                    $this->CloseTag(strtoupper(substr($e, 1)));
                } else {
                    // Extraer atributos
                    $a2 = explode(' ', $e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach ($a2 as $v) {
                        if (preg_match('/([^=]*)=["\']?([^"\']*)/', $v, $a3)) {
                            $attr[strtoupper($a3[1])] = $a3[2];
                        }
                    }
                    $this->OpenTag($tag, $attr);
                }
            }
        }
    }

    private function OpenTag($tag, $attr) {
        // Etiqueta de apertura
        if ($tag == 'B' || $tag == 'I' || $tag == 'U') {
            $this->SetStyle($tag, true);
        }
        if ($tag == 'A') {
            $this->HREF = $attr['HREF'];
        }
        if ($tag == 'BR') {
            $this->Ln(5);
        }
        if (isset($attr['ALIGN']) && ($tag == 'P')) {
            $this->ALIGN = $attr['ALIGN'];
        }
        if ($tag == 'HR') {
            if (isset($attr['WIDTH']) && ($attr['WIDTH'] != '')) {
                $Width = $attr['WIDTH'];
            } else {
                $Width = $this->w - $this->lMargin - $this->rMargin;
            }
            $this->Ln(2);
            $x = $this->GetX();
            $y = $this->GetY();
            $this->SetLineWidth(0.4);
            $this->Line($x, $y, $x + $Width, $y);
            $this->SetLineWidth(0.2);
            $this->Ln(2);
        }
    }

    private function CloseTag($tag) {
        // Etiqueta de cierre
        if ($tag == 'B' || $tag == 'I' || $tag == 'U') {
            $this->SetStyle($tag, false);
        }
        if ($tag == 'A') {
            $this->HREF = '';
        }
        if ($tag == 'P') {
            $this->ALIGN = '';
        }
    }

    private function SetStyle($tag, $enable) {
        // Modificar estilo y escoger la fuente correspondiente
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach (array('B', 'I', 'U') as $s) {
            if ($this->$s > 0) {
                $style .= $s;
            }
        }
        $this->SetFont('', $style);
    }

    private function PutLink($URL, $txt) {
        // Escribir un hiper-enlace
        $this->SetTextColor(0, 0, 255);
        $this->SetStyle('U', true);
        $this->Write(5, $txt, $URL);
        $this->SetStyle('U', false);
        $this->SetTextColor(0);
    }

}
