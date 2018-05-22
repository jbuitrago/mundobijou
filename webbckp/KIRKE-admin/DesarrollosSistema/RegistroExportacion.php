<?php

class DesarrollosSistema_RegistroExportacion {

    static private $_archivoNombre;
    static private $_matrizComponentes = array();
    static private $_matriz = array();
    static private $_campos = array();
    static private $_columnas;
    static private $_lineas;
    static private $_tipo;
    static private $_titulos = '';
    static private $_valores = '';
    static private $_imagen = '';
    static private $_imagen_tipo = '';
    static private $_salto_linea = '';
    static private $_tiempo_limite;

    public function armado($campos, $matriz, $nombre_archivo) {

        self::$_archivoNombre = $nombre_archivo;

        self::$_tiempo_limite = time() + ini_get('max_execution_time') - 1;

        // listado de campos
        self::$_matrizComponentes = $campos;

        // llamo al metodo del tipo de tabla correspondiente
        switch ($_GET['kk_exportar_formato']) {
            case 'excel':
                self::$_tipo = 'excel';
                self::excel(self::_verRegistrosRegistros($matriz));
                break;
            case 'pdf':
                self::$_tipo = 'pdf';
                self::pdf(self::_verRegistrosRegistros($matriz));
                break;
            case 'cvs':
                self::$_tipo = 'cvs';
                self::cvs(self::_verRegistrosRegistros($matriz));
                break;
            case 'xml':
                self::$_tipo = 'xml';
                self::xml(self::_verRegistrosRegistros($matriz));
                break;
            case 'html':
                self::$_tipo = 'html';
                self::html(self::_verRegistrosRegistros($matriz));
                break;
            default:
                exit;
        }
    }

    static private function _verRegistrosRegistros($matriz) {

        $id_nuevo = 0;
        if (is_array(self::$_matrizComponentes)) {
            $id_columna_izq = 0;
            $id_columna_izq_sub = 0;
            foreach (self::$_matrizComponentes as $id => $value) {
                if (($value['tipo'] != 'orden') && ($value['tipo'] != 'editar') && ($value['tipo'] != 'siguiente') && ($value['tipo'] != 'nuevo') && ($value['tipo'] != 'ver') && ($value['tipo'] != 'eliminar')) {
                    $tabla_columna[$id_nuevo]['titulo'] = $value['titulo'];
                    $tabla_columna[$id_nuevo]['tb_campo'] = $value['tb_campo'];
                    if (isset($value['modificadores'])) {
                        $tabla_columna[$id_nuevo]['modificadores'] = $value['modificadores'];
                    } else {
                        $tabla_columna[$id_nuevo]['modificadores'] = '';
                    }
                }
                $id_nuevo++;
            }

            //==== armo la tabla =======================
            if ($matriz !== false) {

                $ver = '';
                
                self::$_campos = $tabla_columna;
                self::$_columnas = count($tabla_columna);
                self::$_lineas = count($matriz);
                self::$_matriz = $matriz;

                if (self::$_lineas > 0) {
                    $ver = self::_lineas();
                }

                return $ver;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    static private function _lineas() {

        if (self::$_tipo != 'pdf') {
            $archivo_nombre = Inicio::path() . '/tmp/RegistroExportacion_' . microtime() . '_' . rand() . '.tmp';
        }

        if (is_array(self::$_matriz)) {

            $cont_lineas = 0;
            $cont_lineas_salto = false;

            foreach (self::$_matriz as $i => $linea) {

                $registro_linea = '';
                $cont_columna = 0;

                foreach (self::$_campos as $campos) {

                    unset($valor_campo);

                    if (isset($linea[$campos['tb_campo']])) {
                        $modificadores = new DesarrollosSistema_Modificadores();
                        $valor_campo = $modificadores->modificadores($linea[$campos['tb_campo']], $campos['modificadores'], true);
                    } else {
                        $valor_campo = '';
                    }

                    $titulo = $campos['titulo'];

                    if (isset($valor_campo)) {

                        switch (self::$_tipo) {
                            case 'excel':
                                $registro_linea .= iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '<td bgcolor="#eeeeec">' . str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($valor_campo, '<a>')) . '</td>');
                                if ($i == 0) {
                                    self::$_titulos .= '<td bgcolor="#b37171">' . str_replace(array("\r\n", "\n", "\r"), ' ', strip_tags($titulo, '<a>')) . '</td>';
                                }
                                break;
                            case 'pdf':
                                self::$_titulos[$cont_lineas] = $titulo;
                                self::$_valores[$cont_lineas] = $valor_campo;
                                $cont_lineas_salto = $cont_lineas;
                                break;
                            case 'cvs':
                                $registro_linea .= str_replace(';', ',', str_replace(array("\r\n", "\n", "\r"), ' ', $valor_campo)) . ';';
                                if ($i == 0) {
                                    self::$_titulos .= str_replace(';', ',', str_replace("\n", ' ', $titulo)) . ';';
                                }
                                break;
                            case 'xml':
                                $registro_linea .= '    <' . $titulo . '><![CDATA[' . $valor_campo . ']]></' . $titulo . '>' . "\n";
                                break;
                            case 'html':
                                $registro_linea .= '<td>' . $valor_campo . '</td>';
                                if ($i == 0) {
                                    self::$_titulos .= '<td>' . $titulo . '</td>';
                                }
                                break;
                        }
                    }
                    $cont_lineas++;
                }

                switch (self::$_tipo) {
                    case 'excel':
                        file_put_contents($archivo_nombre, '<tr>' . $registro_linea . '</tr>' . "\n", FILE_APPEND | LOCK_EX);
                        break;
                    case 'pdf':
                        self::$_salto_linea[$cont_lineas_salto] = true;
                        $cont_lineas_salto = false;
                        break;
                    case 'cvs':
                        file_put_contents($archivo_nombre, substr($registro_linea, 0, -1) . "\n", FILE_APPEND | LOCK_EX);
                        break;
                    case 'xml':
                        file_put_contents($archivo_nombre, '  <dato_xml>' . "\n" . $registro_linea . '  </dato_xml>' . "\n", FILE_APPEND | LOCK_EX);
                        break;
                    case 'html':
                        file_put_contents($archivo_nombre, '<tr>' . $registro_linea . '</tr>' . "\n", FILE_APPEND | LOCK_EX);
                        break;
                }

                unset($registro_linea);

                if ((self::$_tipo != 'pdf') && (self::$_tiempo_limite <= time())) {
                    return file_get_contents($archivo_nombre);
                } elseif ((self::$_tipo == 'pdf') && (self::$_tiempo_limite <= (time() + ceil(ini_get('max_execution_time') * 0.5)))) {
                    return false;
                }
            }
        }

        if (self::$_tipo != 'pdf') {
            return file_get_contents($archivo_nombre);
        } else {
            return true;
        }
    }

    static private function excel($contenido) {
        header("Content-Type: text/xls");
        header("Content-type: application/force-download");
        header("Content-type: application/octet-stream");
        header("Content-type: application/download");
        header('Content-disposition: attachment; filename="' . self::$_archivoNombre . '.xls"');
        $archivo = '<table border="1" cellspacing="0" cellpadding="1">' . "\n";
        $archivo .= '<tr>';
        $archivo .= self::$_titulos;
        $archivo .= '</tr>' . "\n";
        $archivo .= $contenido;
        $archivo .= '</table>' . "\n";
        die($archivo);
    }

    static private function pdf($contenido) {
        require_once(Inicio::path() . '/FPDF/fpdf.php');
        require_once(Inicio::path() . '/FPDF/Documento.php');
        $pdf = new PDF();
        $pdf->asignarTitulo(self::$_archivoNombre);
        $pdf->AliasNbPages();
        $pdf->AddPage();
        foreach (self::$_valores as $i => $contenido) {
            $pdf->SetFont('Arial', '', 8);
            $pdf->SetFontSize(10);
            $pdf->WriteHTML(iconv('UTF-8', 'ISO-8859-1//TRANSLIT', '<b>' . self::$_titulos[$i] . ':</b> ' . self::$_valores[$i]));
            if (isset(self::$_imagen[$i])) {
                $pdf->Ln(7);
                if (file_exists(self::$_imagen[$i])) {
                    if (!isset(self::$_imagen_tipo[$i])) {
                        $pdf->Image(self::$_imagen[$i], null, null, 33, 0);
                    } else {
                        $pdf->Image(self::$_imagen[$i], null, null, 33, 0, self::$_imagen_tipo[$i]);
                    }
                }
            }
            $pdf->Ln(7);
            if (isset(self::$_salto_linea[$i])) {
                //$pdf->AddPage();
                $pdf->Ln(3);
                $pdf->WriteHTML('<HR>');
                $pdf->Ln(7);
            }
        }
        $pdf->Output(self::$_archivoNombre . '.pdf', 'D');
        exit;
    }

    static private function cvs($contenido) {
        header("Content-Type: text/cvs");
        header("Content-type: application/force-download");
        header("Content-type: application/octet-stream");
        header("Content-type: application/download");
        header('Content-disposition: attachment; filename="' . self::$_archivoNombre . '.cvs"');
        $archivo = '';
        self::$_titulos = substr(self::$_titulos, 0, -1);
        $archivo .= self::$_titulos;
        $archivo .= "\n" . $contenido;
        die($archivo);
    }

    static private function xml($contenido) {
        header("Content-Type: text/xml");
        header("Content-type: application/force-download");
        header("Content-type: application/octet-stream");
        header("Content-type: application/download");
        header('Content-disposition: attachment; filename="' . self::$_archivoNombre . '.xml"');
        $archivo = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $archivo .= '<datos_xml>' . "\n";
        $archivo .= $contenido;
        $archivo .= '</datos_xml>' . "\n";
        die($archivo);
    }

    static private function html($contenido) {
        header("Content-Type: text/html");
        header("Content-type: application/force-download");
        header("Content-type: application/octet-stream");
        header("Content-type: application/download");
        header('Content-disposition: attachment; filename="' . self::$_archivoNombre . '.html"');
        $archivo = '<!DOCTYPE html>' . "\n";
        $archivo .= '<html>' . "\n";
        $archivo .= '<head>' . "\n";
        $archivo .= '<title>' . self::$_archivoNombre . '</title>' . "\n";
        $archivo .= '<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";
        $archivo .= '</head>' . "\n";
        $archivo .= '<body>' . "\n";
        $archivo .= '<table>' . "\n";
        $archivo .= '<tr>';
        $archivo .= self::$_titulos;
        $archivo .= '</tr>' . "\n";
        $archivo .= $contenido;
        $archivo .= '</table>' . "\n";
        $archivo .= '</body>' . "\n";
        $archivo .= '</html>' . "\n";
        die($archivo);
    }

}
