<?php

class Acciones_RegistroExportacion {//extends Armado_Plantilla {

    static private $_tablaNombre;
    static private $_tablaNombre_texto;
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

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion->consultaElemento('pagina', $_GET['id_tabla'], 'ver');

        self::$_tiempo_limite = time() + ini_get('max_execution_time') - 1;

        // se obtiene el nombre de la pagina
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();

        self::$_tablaNombre_texto = $datos_tabla['nombre_idioma'];

        self::$_tablaNombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
        $tabla_tipo = $datos_tabla['tipo'];

        // listado de componentes
        self::$_matrizComponentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos');

        // llamo al metodo del tipo de tabla correspondiente
        switch ($_GET['tipo']) {
            case 'excel':
                self::$_tipo = 'excel';
                eval('self::excel(self::_verRegistros' . ucwords($tabla_tipo) . '());');
                break;
            case 'pdf':
                self::$_tipo = 'pdf';
                eval('self::pdf(self::_verRegistros' . ucwords($tabla_tipo) . '());');
                break;
            case 'cvs':
                self::$_tipo = 'cvs';
                eval('self::cvs(self::_verRegistros' . ucwords($tabla_tipo) . '());');
                break;
            case 'xml':
                self::$_tipo = 'xml';
                eval('self::xml(self::_verRegistros' . ucwords($tabla_tipo) . '());');
                break;
            case 'html':
                self::$_tipo = 'html';
                eval('self::html(self::_verRegistros' . ucwords($tabla_tipo) . '());');
                break;
            case 'sql':
                self::$_tipo = 'sql';
                self::$_titulos[0] = '';
                self::$_titulos[1] = '';
                eval('self::sql(self::_verRegistros' . ucwords($tabla_tipo) . '());');
                break;
            default:
                exit;
        }
    }

    // tipo de tabla registros

    static private function _verRegistrosRegistros() {

        if (!isset($_GET['id_registro'])) {
            $consulta_registros = Generales_FiltrosOrden::obtenerConsulta();
            $consulta_registros = str_ireplace("\n", '', $consulta_registros);

            $consulta_registros = Bases_ConsultaModificarObtenerTodo::consulta($consulta_registros);

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->consultaDirecta($consulta_registros);
            $matriz = $consulta->realizarConsulta();
        } else {
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas(self::$_tablaNombre);
            $consulta->camposTodos();
            $consulta->condiciones('', self::$_tablaNombre, 'id_' . self::$_tablaNombre, 'iguales', '', '', (int) $_GET['id_registro']);
            $matriz = $consulta->realizarConsulta();
        }

        $id_nuevo = 0;
        if (is_array(self::$_matrizComponentes)) {
            $id_columna_izq = 0;
            $id_columna_izq_sub = 0;
            foreach (self::$_matrizComponentes as $id => $value) {
                $tabla_columna[$id_nuevo]['tb_columna_tipo'] = 'componente';
                $tabla_columna[$id_nuevo]['parametros'] = $value;
                $tabla_columna[$id_nuevo]['tb_campo_id'] = 'id_' . self::$_tablaNombre;
                $id_nuevo++;
            }

            //==== armo la tabla =======================
            if ($matriz !== false) {

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

    // tipo de tabla variables

    static private function _verRegistrosVariables() {

        // creo una matriz con los campos de los componentes de la pagina
        $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos');

        // obtencion de valores
        $matriz_valores = Generales_ObtenerValoresTbVariables::armado(self::$_tablaNombre, $matriz_componentes);

        $armar_lineas = '';
        $registro_linea = '';

        if (is_array($matriz_componentes)) {
            $cont_lineas = 0;
            $i = 0;
            foreach ($matriz_componentes as $id => $value) {

                if (isset($matriz_valores[$value['tb_campo']])) {
                    $tb_campo = $matriz_valores[$value['tb_campo']];
                } else {
                    $tb_campo = '';
                }

                $cp_valor = Generales_LlamadoAComponentesYTraduccion::armar('RegistroExportar', self::$_tipo, $tb_campo, $value, $value['cp_nombre'], $value['cp_id'], '', false);
                switch (self::$_tipo) {
                    case 'excel':
                        if ($cp_valor !== false) {
                            $registro_linea .= $cp_valor['valor'];
                            if ($i == 0) {
                                self::$_titulos .= '<td bgcolor="#b37171">' . $cp_valor['titulo'] . '</td>';
                            }
                        }
                        break;
                    case 'pdf':
                        if ($cp_valor !== false) {
                            self::$_titulos[$cont_lineas] = $cp_valor['titulo'];
                            self::$_valores[$cont_lineas] = $cp_valor['valor'];
                            if (isset($cp_valor['imagen'])) {
                                self::$_imagen[$cont_lineas] = $cp_valor['imagen'];
                            }
                            if (isset($cp_valor['imagen_tipo'])) {
                                self::$_imagen_tipo[$cont_lineas] = $cp_valor['imagen_tipo'];
                            }
                        }
                        break;
                    case 'cvs':
                        if ($cp_valor !== false) {
                            $registro_linea .= $cp_valor['valor'];
                            if ($i == 0) {
                                self::$_titulos .= str_replace(';', ',', $cp_valor['titulo']) . ';';
                            }
                        }
                        break;
                    case 'xml':
                        $registro_linea .= $cp_valor;
                        break;
                    case 'html':
                        if ($cp_valor !== false) {
                            $registro_linea .= $cp_valor['valor'];
                            if ($i == 0) {
                                self::$_titulos .= '<td>' . $cp_valor['titulo'] . '</td>';
                            }
                        }
                        break;
                    case 'sql':
                        if ($cp_valor !== false) {
                            $registro_linea .= $cp_valor['valor'];
                            if ($i == 0) {
                                $cp_titulo = Generales_LlamadoAComponentesYTraduccion::armar('RegistroExportar', 'sqlEstructura', $tb_campo, $value, $value['cp_nombre'], $value['cp_id']);
                                self::$_titulos[0] .= $cp_titulo[0];
                                self::$_titulos[1] .= $cp_titulo[1];
                            }
                        }
                        break;
                }
                $cont_lineas++;
            }
            switch (self::$_tipo) {
                case 'excel':
                    $armar_lineas .= '<tr>' . $registro_linea . '</tr>' . "\n";
                    break;
                case 'pdf':
                    self::$_salto_linea[$cont_lineas - 1] = true;
                    break;
                case 'cvs':
                    $registro_linea = substr($registro_linea, 0, -1);
                    $armar_lineas .= $registro_linea . "\n";
                    break;
                case 'xml':
                    $armar_lineas .= '  <dato_xml>' . "\n" . $registro_linea . '  </dato_xml>' . "\n";
                    break;
                case 'html':
                    $armar_lineas .= '<tr>' . $registro_linea . '</tr>' . "\n";
                    break;
                case 'sql':
                    $registro_linea = substr($registro_linea, 0, -2);
                    $armar_lineas .= '(' . $registro_linea . ' ),' . "\n";
                    break;
            }
        }

        return $armar_lineas;
    }

    static private function _lineas() {

        if (self::$_tipo != 'pdf') {
            $archivo_nombre = Inicio::path() . '/tmp/RegistroExportacion_' . microtime() . '_' . rand() . '.tmp';
        }

        if (is_array(self::$_matriz)) {

            $cont_lineas = 0;
            $cont_lineas_salto = false;

            for ($i = 0; $i < count(self::$_matriz); $i++) {

                $linea = self::$_matriz[$i];

                $registro_linea = '';
                $cont_columna = 0;

                for ($j = 0; $j < self::$_columnas; $j++) {

                    if (self::$_campos[$j]['tb_columna_tipo'] == 'componente') {
                        unset($valor_campo);

                        if (isset($linea[self::$_campos[$j]['parametros']['tb_campo']])) {
                            $valor_campo = $linea[self::$_campos[$j]['parametros']['tb_campo']];
                        } else {
                            $valor_campo = '';
                        }

                        if (isset($valor_campo)) {
                            $cp_valor = Generales_LlamadoAComponentesYTraduccion::armar('RegistroExportar', self::$_tipo, $valor_campo, self::$_campos[$j]['parametros'], self::$_campos[$j]['parametros']['cp_nombre'], self::$_campos[$j]['parametros']['cp_id'], $linea[self::$_campos[$j]['tb_campo_id']], true);
                            switch (self::$_tipo) {
                                case 'excel':
                                    if ($cp_valor !== false) {
                                        $registro_linea .= iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cp_valor['valor']);
                                        if ($i == 0) {
                                            self::$_titulos .= '<td bgcolor="#b37171">' . iconv('UTF-8', 'ISO-8859-1//TRANSLIT', $cp_valor['titulo']) . '</td>';
                                        }
                                    }
                                    break;
                                case 'pdf':
                                    if ($cp_valor !== false) {
                                        self::$_titulos[$cont_lineas] = $cp_valor['titulo'];
                                        self::$_valores[$cont_lineas] = $cp_valor['valor'];
                                        if (isset($cp_valor['imagen'])) {
                                            self::$_imagen[$cont_lineas] = $cp_valor['imagen'];
                                        }
                                        if (isset($cp_valor['imagen_tipo'])) {
                                            self::$_imagen_tipo[$cont_lineas] = $cp_valor['imagen_tipo'];
                                        }
                                        $cont_lineas_salto = $cont_lineas;
                                    }
                                    break;
                                case 'cvs':
                                    if ($cp_valor !== false) {
                                        $registro_linea .= $cp_valor['valor'];
                                        if ($i == 0) {
                                            self::$_titulos .= str_replace(';', ',', $cp_valor['titulo']) . ';';
                                        }
                                    }
                                    break;
                                case 'xml':
                                    $registro_linea .= $cp_valor;
                                    break;
                                case 'html':
                                    if ($cp_valor !== false) {
                                        $registro_linea .= $cp_valor['valor'];
                                        if ($i == 0) {
                                            self::$_titulos .= '<td>' . $cp_valor['titulo'] . '</td>';
                                        }
                                    }
                                    break;
                                case 'sql':
                                    if ($cp_valor !== false) {
                                        $registro_linea .= $cp_valor['valor'];
                                        if ($i == 0) {
                                            $cp_titulo = Generales_LlamadoAComponentesYTraduccion::armar('RegistroExportar', 'sqlEstructura', $valor_campo, self::$_campos[$j]['parametros'], self::$_campos[$j]['parametros']['cp_nombre'], self::$_campos[$j]['parametros']['cp_id'], $linea[self::$_campos[$j]['tb_campo_id']], true);
                                            self::$_titulos[0] .= $cp_titulo[0];
                                            self::$_titulos[1] .= $cp_titulo[1];
                                        }
                                    }
                                    break;
                            }
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
                    case 'sql':
                        file_put_contents($archivo_nombre, '(' . substr($registro_linea, 0, -2) . ' ),' . "\n", FILE_APPEND | LOCK_EX);
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
        header('Content-disposition: attachment; filename="' . self::$_tablaNombre . '.xls"');
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
        $pdf->asignarTitulo(self::$_tablaNombre_texto);
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
        $pdf->Output(self::$_tablaNombre . '.pdf', 'D');
        exit;
    }

    static private function cvs($contenido) {
        header("Content-Type: text/cvs");
        header("Content-type: application/force-download");
        header("Content-type: application/octet-stream");
        header("Content-type: application/download");
        header('Content-disposition: attachment; filename="' . self::$_tablaNombre . '.cvs"');
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
        header('Content-disposition: attachment; filename="' . self::$_tablaNombre . '.xml"');
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
        header('Content-disposition: attachment; filename="' . self::$_tablaNombre . '.html"');
        $archivo = '<!DOCTYPE html>' . "\n";
        $archivo .= '<html>' . "\n";
        $archivo .= '<head>' . "\n";
        $archivo .= '<title>' . self::$_tablaNombre_texto . '</title>' . "\n";
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

    static private function sql($contenido) {
        header("Content-Type: text/sql");
        header("Content-type: application/force-download");
        header("Content-type: application/octet-stream");
        header("Content-type: application/download");
        header('Content-disposition: attachment; filename="' . self::$_tablaNombre . '.sql"');
        $archivo = 'CREATE TABLE IF NOT EXISTS `' . self::$_tablaNombre . '` (' . "\n";
        $archivo .= '  `id_' . self::$_tablaNombre . '` int(12) NOT NULL AUTO_INCREMENT,' . "\n";
        $archivo .= self::$_titulos[0];
        $archivo .= '  PRIMARY KEY (`id_' . self::$_tablaNombre . '`)' . "\n";
        $archivo .= ') ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;' . "\n\n";
        $archivo .= 'INSERT INTO `' . self::$_tablaNombre . '` (';
        $archivo .= substr(self::$_titulos[1], 0, -2) . ' ';
        $archivo .= ') VALUES ' . "\n";
        $archivo .= substr($contenido, 0, -2);
        $archivo .= ';';
        die($archivo);
    }

}
