<?php

class PlantillaFunciones {

    static private $_cyclePosicion = 0;

    protected function _modificadores($valor, $modificadores = null) {

        if ($modificadores != '') {

            $modificadoresArmar = explode('|', $modificadores);

            if (is_array($modificadoresArmar)) {

                foreach ($modificadoresArmar as $modificador) {
                    $modificador = str_replace('<#1#>', "'", $modificador);
                    $modificador = str_replace('<#2#>', '"', $modificador);

                    if (preg_match("/truncate:([[:digit:]]+)(:[\"|'](.*?)[\"|'])?(:(true|false))?$/", $modificador)) {
                        $largo = preg_replace("/truncate:([[:digit:]]+)(:[\"|'](.*?)[\"|'])?(:(true|false))?$/", '${1}', $modificador);
                        if (strlen($valor) > $largo) {
                            $final = preg_replace("/truncate:([[:digit:]]+)(:[\"|'](.*?)[\"|'])?(:(true|false))?$/", '${3}', $modificador);
                            $valor = mb_substr($valor, 0, $largo);
                            if (preg_replace("/truncate:([[:digit:]]+)(:[\"|'](.*?)[\"|'])?(:(true|false))?$/", '${5}', $modificador) == 'true') {
                                $valor = mb_substr($valor, 0, -(mb_strlen($valor) - mb_strrpos($valor, ' ')));
                            }
                            $valor .= $final;
                        }
                    } elseif ($modificador == 'nl2br') {
                        $valor = nl2br($valor);
                    } elseif (preg_match("/date_format:([\"|'](.*?)[\"|'])?$/", $modificador)) {
                        $valor = date(preg_replace("/date_format:([\"|'](.*?)[\"|'])?$/", '${2}', $modificador), $valor);
                    } elseif (preg_match("/number_format:([[:digit:]]+)(:[\"|'](.*?)[\"|'])?(:[\"|'](.*?)[\"|'])?$/", $modificador)) {
                        $valores_number = preg_replace("/number_format:([[:digit:]]+)(:[\"|'](.*?)[\"|'])?(:[\"|'](.*?)[\"|'])?$/", '${1}, "${3}", "${5}"', $modificador);
                        eval('$valor = number_format(' . $valor . ', ' . $valores_number . ');');
                    } elseif ($modificador == 'upper') {
                        $valor = strtoupper($valor);
                    } elseif ($modificador == 'lower') {
                        $valor = strtolower($valor);
                    } elseif ($modificador == 'capitalize') {
                        $valor = ucfirst($valor);
                    } elseif (preg_match("/truncate_p:([[:digit:]])+$/", $modificador)) {
                        $parrafos = preg_split("/(<\/[p|P]\s*\/?>)|(<\s*br\s*\/?>\s*<\s*br\s*\/?>)/", $valor);
                        $parrafos = preg_replace("/<[p|P](.*?)>/", "", $parrafos);
                        $parrafos = array_slice($parrafos, 0, preg_replace("/truncate_p:([[:digit:]])+$/", '${1}', $modificador));
                        $valor = implode('<br /><br />', $parrafos);
                    } elseif (preg_match("/strip_tags(:[\"|'](.*?)[\"|'])?/", $modificador)) {
                        if ($modificador == 'strip_tags') {
                            $valor = strip_tags($valor);
                        } else {
                            $valor = strip_tags($valor, preg_replace("/strip_tags(:[\"|'](.*?)[\"|']){1}/", '${2}', $modificador));
                        }
                    } elseif (preg_match("/default:[\"|'](.*?)[\"|']$/", $modificador)) {
                        if (!isset($valor) || (trim($valor) == '')) {
                            $valor = preg_replace("/default:[\"|'](.*?)[\"|']$/", '${1}', $modificador);
                        }
                    } elseif (preg_match("/regex_replace:[\"|'](.*?)[\"|']:[\"|'](.*?)[\"|']$/", $modificador)) {
                        $valor = preg_replace(preg_replace("/regex_replace:[\"|'](.*?)[\"|']:[\"|'](.*?)[\"|']$/", '${1}', $modificador), preg_replace("/regex_replace:[\"|'](.*?)[\"|']:[\"|'](.*?)[\"|']$/", '${2}', $modificador), $valor);
                    } elseif (preg_match("/replace:[\"|'](.*?)[\"|']:[\"|'](.*?)[\"|']$/", $modificador)) {
                        $valor = str_replace(preg_replace("/replace:[\"|'](.*?)[\"|']:[\"|'](.*?)[\"|']$/", '${1}', $modificador), preg_replace("/replace:[\"|'](.*?)[\"|']:[\"|'](.*?)[\"|']$/", '${2}', $modificador), $valor);
                    } elseif (preg_match("/string_format:([\"|'](.*?)[\"|']){1}$/", $modificador)) {
                        $valor = sprintf(preg_replace("/string_format:([\"|'](.*?)[\"|']){1}$/", '${2}', $modificador), $valor);
                    } elseif (preg_match("/escape:([\"|'](.*?)[\"|']){1,1}$/", $modificador)) {
                        $tipo = preg_replace("/escape:([\"|'](.*?)[\"|']){1,1}$/", '${2}', $modificador);
                        switch ($tipo) {
                            case 'html':
                                $valor = html_entity_decode($valor);
                                break;
                            case 'htmlall':
                                $valor = htmlspecialchars($valor);
                                break;
                            case 'url':
                                $valor = urlencode($valor);
                                break;
                            case 'quotes':
                                $valor = addslashes($valor);
                                break;
                        }
                    }
                }
            }
        }

        return $valor;
    }

    protected function _htmlError($nombre = null) {

        if ($nombre !== null) {
            return "<div id='VC_" . $nombre . "' class=\"VC_error\"></div>";
        } else {
            return false;
        }
    }

    protected function _htmlErrores() {

        return "<div id='VF_todos' class=\"VC_error\"></div>";
    }

    protected function _indexMarco($nombre = null) {

        if ($nombre !== null) {
            VariableSet::indexMarco($nombre);
        } else {
            return false;
        }
    }

    public function _htmlCaptcha($formulario, $class = null, $estilo = null) {

        $captcha = new ArmadoCaptcha();
        $captcha->formulario($formulario);
        echo $captcha->mostrar($class, $estilo);
    }

    public function _cycle($valores) {

        $array_valores = explode(",", $valores);

        if (isset($array_valores[self::$_cyclePosicion + 1])) {
            self::$_cyclePosicion += 1;
        } else {
            self::$_cyclePosicion = 0;
        }
        return $array_valores[self::$_cyclePosicion];
    }

    static public function _obtenerNombreTpl($nombre, $tipo = null) {

        $url_actual = getcwd();
        chdir(VariableGet::sistema('directorios_php'));
        $directorio = getcwd();
        chdir($url_actual);
        $archivo_php = $directorio . '/' . $nombre . '.php';

        $url_actual = getcwd();
        chdir(VariableGet::sistema('directorio_plantillas'));
        $directorio = getcwd();
        chdir($url_actual);
        $archivo_tpl = $directorio . '/' . $nombre . '.tpl';

        if ($tipo !== true) {
            // si no es seccion tambien tiene que controlar el directorio de plantillas alternativo
            $url_actual = getcwd();
            chdir(VariableGet::sistema('directorio_plantillas_varias'));
            $directorio = getcwd();
            chdir($url_actual);
            $archivo_varias_tpl = $directorio . '/' . $nombre . '.tpl';
        }

        if (file_exists($archivo_php)) {
            if ($tipo !== true) {
                return $archivo_php;
            } else {
                return array($archivo_php, 'php');
            }
        } elseif (file_exists($archivo_tpl)) {
            return $archivo_tpl;
        } elseif (isset($archivo_varias_tpl) && file_exists($archivo_varias_tpl) && ($tipo !== true)) {
            return $archivo_varias_tpl;
        } else {
            if ($nombre != '') {
                ReporteErrores::error(__FILE__, __LINE__, 'archivo', $nombre);
            }
            return false;
        }
    }

    static protected function _links($valores) {

        if ((VariableGet::sistema('subniveles_inferiores') != false) && (VariableGet::sistema('subniveles_inferiores') != '')) {
            $urlNva = '/' . VariableGet::sistema('subniveles_inferiores');
        } else {
            $urlNva = '';
        }

        foreach ($valores as $valor) {

            $urlNva .= '/';

            if (is_array($valor)) {
                if (count($valor) > 0) {
                    $urlNvaValor = implode('-', $valor);
                } else {
                    $urlNvaValor = $valor[0];
                }
            } else {
                $urlNvaValor = $valor;
            }

            // Limpio las barras que puedan generar problemas de navegacion
            $urlNvaValor = strtr($urlNvaValor, '/', '-');

            $urlNva .= $urlNvaValor;
        }

        $urlNvaLimpia = self::limpiarURL($urlNva);

        ArmadoLinks::guardarLinkURL($urlNvaLimpia, $urlNva);

        return $urlNvaLimpia . '.html';
    }

    public static function limpiarURL($url) {

        $url = str_replace(array('á', 'à', 'â', 'ã', 'ª', 'ä', 'Á', 'À', 'Â', 'Ã', 'Ä'), 'a', $url);
        $url = str_replace(array('Í', 'Ì', 'Î', 'Ï', 'í', 'ì', 'î', 'ï'), 'i', $url);
        $url = str_replace(array('é', 'è', 'ê', 'ë', 'É', 'È', 'Ê', 'Ë'), 'e', $url);
        $url = str_replace(array('ó', 'ò', 'ô', 'õ', 'ö', 'º', 'Ó', 'Ò', 'Ô', 'Õ', 'Ö'), 'o', $url);
        $url = str_replace(array('ú', 'ù', 'û', 'ü', 'Ú', 'Ù', 'Û', 'Ü'), 'u', $url);
        $url = str_replace(array('[', '^', '´', '`', '¨', '~', ']'), '', $url);
        $url = str_replace('ç', 'c', $url);
        $url = str_replace('Ç', 'C', $url);
        $url = str_replace('ñ', 'n', $url);
        $url = str_replace('Ñ', 'N', $url);
        $url = str_replace('Ý', 'Y', $url);
        $url = str_replace('ý', 'y', $url);
        $url = str_replace(array(' ', '&', "\r\n", "\n", '+', '_'), '-', $url);
        $url = preg_replace('/[^\/A-Za-z0-9_-]/', '', $url);
        $url = preg_replace('/[-]+/', '-', $url);

        return strtolower($url);
    }

    protected function _htmlArmadoSelect($opciones, $seleccionado) {

        $armado = '';
        if (is_array($opciones)) {
            foreach ($opciones as $valores) {
                if (isset($valores['valor']) && ( (string) $valores['valor'] == (string) $seleccionado)) {
                    $chequeado = ' selected="selected"';
                } else {
                    $chequeado = '';
                }
                if (isset($valores['etiqueta'])) {
                    $etiqueta = $valores['etiqueta'];
                } else {
                    $etiqueta = '';
                }
                if (isset($valores['valor'])) {
                    $valor = $valores['valor'];
                } else {
                    $valor = '';
                }
                $armado .= '<option value="' . $valor . '" ' . $chequeado . '>' . $etiqueta . '</option>';
            }
        }
        echo $armado;
    }

    protected function _htmlArmadoCheckboxes($nombre, $opciones, $seleccionado, $estilo, $class, $html, $nombre_compuesto, $nombre_archivo) {
        if (( strpos($nombre, '<') === false ) && (ArmadoFormulario::getCampo($nombre_archivo, $nombre, 'obligatorio') == 's')) {
            $obligatorio = true;
        } elseif (ArmadoFormulario::getCampo("'.$nombre_archivo.'", "'.$nombre_compuesto.'", "obligatorio") == "s") {
            $nombre = $nombre_compuesto;
            $obligatorio = true;
        } else {
            $obligatorio = false;
        }
        $armado = '';
        if (is_array($opciones)) {
            $cont = 1;
            foreach ($opciones as $valores) {
                $chequeado = '';
                if (isset($valores['valor']) && !is_array($seleccionado) && ($valores['valor'] == $seleccionado)) {
                    $chequeado = ' checked="checked"';
                } elseif (isset($valores['valor']) && is_array($seleccionado) && (array_search($valores['valor'], $seleccionado) !== false)) {
                    $chequeado = ' checked="checked"';
                }
                if (isset($valores['etiqueta'])) {
                    $etiqueta = $valores['etiqueta'];
                } else {
                    $etiqueta = '';
                }
                if (isset($valores['valor'])) {
                    $valor = $valores['valor'];
                } else {
                    $valor = '';
                }
                $armado .= '<input type="checkbox" name="' . $nombre . '[' . $cont . ']" id="' . $nombre . '_' . $cont . '" value="' . $valor . '" ' . $estilo . $class . $chequeado . '>' . $etiqueta;
                if ($cont < count($opciones)) {
                    $armado .= $html;
                }
                $cont++;
            }
        }
        if ($obligatorio == true) {
            $armado .= '<div id="VC_' . $nombre . '" class="VC_error"></div>';
            $armado .= '<div class="kk_html_checkboxes" name="' . $nombre . '" cant="' . ($cont - 1) . '"></div>';
        }
        echo $armado;
    }

    protected function _htmlArmadoRadios($nombre, $opciones, $seleccionado, $estilo, $class, $html, $nombre_compuesto, $nombre_archivo) {
        if (( strpos($nombre, '<') === false ) && (ArmadoFormulario::getCampo($nombre_archivo, $nombre, 'obligatorio') == 's')) {
            $obligatorio = true;
        } elseif (ArmadoFormulario::getCampo("'.$nombre_archivo.'", "'.$nombre_compuesto.'", "obligatorio") == "s") {
            $nombre = $nombre_compuesto;
            $obligatorio = true;
        } else {
            $obligatorio = false;
        }
        $armado = '';
        if (is_array($opciones)) {
            $cont = 1;
            foreach ($opciones as $valores) {
                if ((isset($valores['valor']) && ($valores['valor'] == $seleccionado))) {
                    $chequeado = ' checked="checked"';
                } else {
                    $chequeado = '';
                }
                if (isset($valores['etiqueta'])) {
                    $etiqueta = $valores['etiqueta'];
                } else {
                    $etiqueta = '';
                }
                if (isset($valores['valor'])) {
                    $valor = $valores['valor'];
                } else {
                    $valor = '';
                }
                $armado .= '<input type="radio" name="' . $nombre . '" id="' . $nombre . '_' . $cont . '" value="' . $valor . '"' . $estilo . $class . $chequeado . '><span' . $estilo . $class . '>' . $etiqueta . '</span>';
                if ($cont < count($opciones)) {
                    $armado .= $html;
                }
                $cont++;
            }
        }
        if ($obligatorio == true) {
            $armado .= '<div id="VC_' . $nombre . '" class="VC_error"></div>';
            $armado .= '<div class="kk_html_radios" name="' . $nombre . '" cant="' . ($cont - 1) . '"></div>';
        }
        echo $armado;
    }

    protected function _table($plantilla, $valores, $cols, $rows, $show_all) {

        $tabla = new ArmadoTabla();
        $tabla->nombreArchivo($plantilla);
        $tabla->datos($valores);
        $tabla->nColumnas($cols);
        $tabla->nFilas($rows);
        //$tabla->sinBordes(); // <- en el caso que la tabla no tenga bordes
        if ($show_all == 'yes') {
            $tabla->completarFilas();
        }
        $tabla->tablaImp();
    }

}
