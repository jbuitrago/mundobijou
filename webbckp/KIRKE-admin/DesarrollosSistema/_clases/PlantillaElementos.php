<?php

class PlantillaElementos extends PlantillaReemplazos {

    static private $_nombreArchivo;
    static private $_formulario;

    static public function plantilla($plantilla, $nombreArchivo) {

        self::$_nombreArchivo = $nombreArchivo;

        // Quitar php embebido
        $plantilla = str_replace(array('<?', '<?php', '?>'), '', $plantilla);

        // HTML reemplazo errores del formulario ===========================

        $plantilla = str_replace("{html_errores}", "<?=\$this->_htmlErrores()?>", $plantilla);

        // HTML reemplazo cierre del formulario ============================

        $plantilla = str_replace("{/html_form}", "</form>", $plantilla);

        // FOREACH =========================================================

        $plantilla = preg_replace_callback("/\{foreach[\s]+(key=([a-zA-Z0-9._.-]+)[\s]+){0,1}(item=([a-zA-Z0-9._.-]+)[\s]+){0,1}from=(\\$){1}((\\$){0,1}(#){0,1}([a-zA-Z0-9._.-]+)((\[[a-zA-Z0-9._.]+\]|\[\"[a-zA-Z0-9._.]+\"\]|\['[a-zA-Z0-9._.]+'\])*)[\s]*)\}/", array('PlantillaElementos', '_foreach'), $plantilla);

        // IF, ELSE IF =====================================================

        $plantilla = preg_replace_callback("/\{(if|elseif)[\s]+((\\$){1}((\\$){0,1}(#){0,1}([a-zA-Z0-9._.-]+)((\[[a-zA-Z0-9._.]+\]|\[\"[a-zA-Z0-9._.]+\"\]|\['[a-zA-Z0-9._.]+'\])*))|([^=.>.<.!.\\$.\{.\}]+))[\s]+([a-zA-Z.=.>.<.!]+)[\s]+((\\$){1}((\\$){0,1}(#){0,1}([a-zA-Z0-9._.-]+)((\[[a-zA-Z0-9._.]+\]|\[\"[a-zA-Z0-9._.]+\"\]|\['[a-zA-Z0-9._.]+'\])*))|([^=.>.<.!.\\$.\{.\}]+))[\s]*\}/", array('PlantillaElementos', '_if_elseif'), $plantilla);

        // LINK ============================================================

        $plantilla = preg_replace_callback("/{#link(.*?)\}/", array('PlantillaElementos', '_linksTemplate'), $plantilla);

        // TABLE ===========================================================

        $plantilla = preg_replace_callback("/\{html_(table)[\s]*tpl=\"([a-zA-Z0-9._.-]+)\"[\s]*loop=(\\$){1}(\\$){0,1}(#){0,1}([a-zA-Z0-9._.-]+)((\[[a-zA-Z0-9._.]+\]|\[\"[a-zA-Z0-9._.]+\"\]|\['[a-zA-Z0-9._.]+'\])*)[\s]*cols=\"([0-9]+)\"[\s]*rows=\"([0-9]+)\"[\s]*show_all=\"(yes|no)\"[\s]*\}/", array('PlantillaElementos', '_tableTemplate'), $plantilla);

        // TEMPLATE ========================================================

        $plantilla = preg_replace_callback("/\{template[\s]*tpl=(.*?)[\s]*\}/", array('PlantillaElementos', '_templateInterno'), $plantilla);

        // Quitar comentarios
        $patron[0] = "/\{\*(.*?)\*}/";
        $reemplazo[0] = "";

        // HTML reemplazo ciclo de valores =================================

        $patron[1] = "/\{cycle[\s]*values=\"([a-zA-Z0-9._.\-.;.:.,.\s]+)\"[\s]*\}/";
        $reemplazo[1] = "<?=\$this->_cycle( '\${1}' )?>";

        // HTML reemplazo etiqueta formulario ==============================

        $patron[2] = "/\{html_error[\s]*name=\"([a-zA-Z0-9._.-]+)\"[\s]*\}/";
        $reemplazo[2] = "<?=\$this->_htmlError( '\${1}' )?>";

        // TEMPLATE SECCION ===============================================

        $patron[3] = "/\{template[\s]*section}/";
        $reemplazo[3] = "<?=\$this->_plantilla( '', 's' )?>";

        // HTML reemplazo index_marco alternativo =========================

        $patron[4] = "/\{index_marco[\s]*tpl=\"([a-zA-Z0-9._.-]+)\"[\s]*\}/";
        $reemplazo[4] = "<?=\$this->_indexMarco( '\${1}' )?>";

        // REEMPLAZOS =====================================================

        $plantilla = preg_replace($patron, $reemplazo, $plantilla);

        // VARIABLES =======================================================

        $plantilla = preg_replace_callback("/\{(\\$){1}((\\$){0,1}(#){0,1}([a-zA-Z0-9._.-]+)((\[[a-zA-Z0-9._.]+\]|\[\"[a-zA-Z0-9._.]+\"\]|\['[a-zA-Z0-9._.]+'\])*)[\s]*)((\|.*?)*)\}/", array('PlantillaElementos', '_variablesTemplate'), $plantilla);

        // HTML reemplazo etiquetas HTML ==================================

        $plantilla = preg_replace_callback("/\{html_([a-zA-Z._]+)[\s]*(.*?)[\s]*\}/", array('PlantillaElementos', '_htmlReemplazar'), $plantilla);

        // CIERRE DE CICLOS ===============================================
        // reemplazo foreach
        $plantilla = str_replace("{foreachelse}", "<?php }}else{if(true){ ?>", $plantilla);
        $plantilla = str_replace("{/foreach}", "<?php } } ?>", $plantilla);

        // reemplazo if
        $plantilla = str_replace("{else}", "<?php }else{ ?>", $plantilla);
        $plantilla = str_replace("{/if}", "<?php } ?>", $plantilla);

        return $plantilla;
    }

    static private function _variablesTemplate($n) {

        if (isset($n[6])) {
            $elementos_array = str_replace('"', "'", $n[6]);
        }
        if (isset($n[8])) {
            $modificadores = str_replace("'", '<#1#>', $n[8]);
            $modificadores = str_replace('"', '<#2#>', $modificadores);
        }
        if (substr($n[2], 0, 1) == '$') {
            $var_template = $n[2];
        } else {
            $var_template = "''";
        }

        $valor = self::_variableArmado("s", $n[1] . $n[3] . $n[4], $n[5], $elementos_array, $var_template);

        return "<?=@\$this->_modificadores(" . $valor . ", '" . $modificadores . "')?>";
    }

    static private function _htmlReemplazar($coincidencias) {

        $tipo = $coincidencias[1];

        $nombre = '';
        $nombre_compuesto = '';
        $nombre_compuesto_union = '';
        if (preg_match('/name=([a-zA-Z0-9._.\-.\..\[.\].\\$.\'.\"\#]+)(\s|$)/', $coincidencias[2], $coincidencia)) {
            $elementos_nombre = explode('.', $coincidencia[1]);
            if (is_array($elementos_nombre)) {
                foreach ($elementos_nombre as $elemento_nombre) {
                    if (preg_match("/(\\$){1}(\\$){0,1}(#){0,1}([a-zA-Z0-9._.-]+)((\[[a-zA-Z0-9._.]+\]|\[\"[a-zA-Z0-9._.]+\"\]|\['[a-zA-Z0-9._.]+'\])*)/", $elemento_nombre, $n)) {
                        if (!isset($n[5])) {
                            $elementos_array = '';
                        } else {
                            $elementos_array = str_replace('"', "'", $n[5]);
                        }
                        $nombre .= '<?=' . self::_variableArmado("s", $n[1] . $n[2] . $n[3], $n[4], $elementos_array, $n[2] . $n[4] . $elementos_array) . '?>';
                        $nombre_compuesto .= $nombre_compuesto_union . self::_variableArmado("s", $n[1] . $n[2] . $n[3], $n[4], $elementos_array, $n[2] . $n[4] . $elementos_array);
                    } elseif (preg_match('/\"([a-zA-Z0-9._.\-]+)\"/', $elemento_nombre, $elemento_nombre_final)) {
                        $nombre .= $elemento_nombre_final[1];
                        $nombre_compuesto .= $nombre_compuesto_union . '"' . $elemento_nombre_final[1] . '"';
                    }
                    $nombre_compuesto_union = '.';
                }
            }
        }

        if (preg_match('/class=\"([a-zA-Z0-9._.\-.\s]+)\"/', $coincidencias[2], $coincidencia)) {
            $class = $coincidencia[1];
        } else {
            $class = '';
        }

        if (preg_match('/style=\"([a-zA-Z0-9._.\-.;.:.\s]+)\"/', $coincidencias[2], $coincidencia)) {
            $estilo = $coincidencia[1];
        } else {
            $estilo = '';
        }

        if (($tipo == "form") || ($tipo == "sform")) {
            return self::_htmlArmadoForm($tipo, $nombre, $class, $estilo);
        }

        if ($tipo == "captcha_img") {
            return "<?=\$this->_htmlCaptcha('" . self::$_formulario . "')?>";
        }

        if (preg_match("/options=(\\$){1}(\\$){0,1}(#){0,1}([a-zA-Z0-9._.\-]+)((\[[a-zA-Z0-9._.\-]+\]|\[\"[a-zA-Z0-9._.\-]+\"\]|\['[a-zA-Z0-9._.\-]+'\])*)/", $coincidencias[2], $n)) {
            if (!isset($n[5])) {
                $elementos_array = '';
            } else {
                $elementos_array = str_replace('"', "'", $n[5]);
            }
            $opciones = self::_variableArmado("s", $n[1] . $n[2] . $n[3], $n[4], $elementos_array, $n[2] . $n[4] . $elementos_array);
        } else {
            $opciones = "''";
            $opciones_php = "''";
        }

        if (preg_match("/selected=(\\$){1}(\\$){0,1}(#){0,1}([a-zA-Z0-9._.\-]+)((\[[a-zA-Z0-9._.\-]+\]|\[\"[a-zA-Z0-9._.\-]+\"\]|\['[a-zA-Z0-9._.\-]+'\])*)/", $coincidencias[2], $n)) {
            if (!isset($n[5])) {
                $elementos_array = '';
            } else {
                $elementos_array = str_replace('"', "'", $n[5]);
            }
            $seleccionado = self::_variableArmado("s", $n[1] . $n[2] . $n[3], $n[4], $elementos_array, $n[2] . $n[4] . $elementos_array);
        } else {
            $seleccionado = "''";
        }

        if (preg_match('/separator=\"(.*?)\"/', $coincidencias[2], $coincidencia)) {
            $html = $coincidencia[1];
        } else {
            $html = '';
        }

        if (preg_match("/value=(\\$){1}(\\$){0,1}(#){0,1}([a-zA-Z0-9._.\-]+)((\[[a-zA-Z0-9._.\-]+\]|\[\"[a-zA-Z0-9._.\-]+\"\]|\['[a-zA-Z0-9._.\-]+'\])*)/", $coincidencias[2], $n) && ($seleccionado == "''")) {
            if (!isset($n[5])) {
                $elementos_array = '';
            } else {
                $elementos_array = str_replace('"', "'", $n[5]);
            }
            $seleccionado = self::_variableArmado("s", $n[1] . $n[2] . $n[3], $n[4], $elementos_array, $n[2] . $n[4] . $elementos_array);
        } elseif (preg_match('/value=\"([a-zA-Z0-9._.\-.;.:.\s]+)\"/', $coincidencias[2], $coincidencia)) {
            $seleccionado = '"' . $coincidencia[1] . '"';
        }

        if (preg_match('/width=\"([0-9.%.m]+)\"/', $coincidencias[2], $coincidencia)) {
            $width = $coincidencia[1];
        } else {
            $width = '';
        }

        if (preg_match('/rows=\"([0-9]+)\"/', $coincidencias[2], $coincidencia)) {
            $rows = $coincidencia[1];
        } else {
            $rows = '';
        }

        return self::_htmlArmado($tipo, $nombre, $opciones, $seleccionado, $class, $estilo, $html, $width, $rows, $nombre_compuesto);
    }

    static private function _foreach($n) {

        if ($n[2] != '') {
            $key = '$' . $n[2] . ' => ';
        } else {
            $key = '';
        }

        if ($n[4] != '') {
            $item = '$' . $n[4];
        } else {
            $item = '';
        }

        if (!isset($n[10])) {
            $elementos_array = '';
        } else {
            $elementos_array = str_replace('"', "'", $n[10]);
        }
        $from = self::_variableArmado("s", $n[5] . $n[7] . $n[8], $n[9], $elementos_array, $n[6]);

        return "<?php if( is_array( " . $from . " ) ) { foreach ( " . $from . " as  " . $key . " " . $item . " ) { ?>";
    }

    static private function _if_elseif($n) {

        if (isset($n[8])) {
            $elementos_array_1 = str_replace('"', "'", $n[8]);
        }
        if (isset($n[18])) {
            $elementos_array_2 = str_replace('"', "'", $n[18]);
        }

        if (substr($n[4], 0, 1) == '$') {
            $var_template_1 = $n[4];
        } else {
            $var_template_1 = "''";
        }
        if (substr($n[14], 0, 1) == '$') {
            $var_template_2 = $n[14];
        } else {
            $var_template_2 = "''";
        }

        if ($n[7] == '') {
            $nombre_1 = str_replace('"', "'", $n[10]);
        } else {
            $nombre_1 = $n[7];
        }
        if (isset($n[20])) {
            $nombre_2 = str_replace('"', "'", $n[20]);
        } else {
            $nombre_2 = $n[17];
        }

        $elemento_1 = self::_variableArmado("n", $n[3] . $n[5] . $n[6], $nombre_1, $elementos_array_1, $var_template_1);

        $elemento_2 = self::_variableArmado("n", $n[13] . $n[15] . $n[16], $nombre_2, $elementos_array_2, $var_template_2);

        if (($n[10] == "''") || ($n[10] == '""')) {
            $elemento_1 = "''";
        } elseif ($n[10] != '') {
            $elemento_1 = $n[10];
        }

        if (isset($n[20]) && (($n[20] == "''") || ($n[20] == '""'))) {
            $elemento_2 = "''";
        } elseif (isset($n[20]) && $n[20] != '') {
            $elemento_2 = $n[20];
        }

        if ($n[1] == 'if') {
            $tipo = 'if';
        } elseif ($n[1] == 'elseif') {
            $tipo = '} elseif';
        }

        return "<?php " . $tipo . "( " . $elemento_1 . " " . $n[11] . " " . $elemento_2 . " ){ ?>";
    }

    static private function _linksTemplate($n) {

        $valor = substr($n[0], 7, -1);
        $valor = strtr($valor, array("['" => '[', '["' => '[', "']" => ']', '"]' => ']'));

        $valores = explode('/', $valor);

        $array_links = 'array( ';
        $cont = 0;
        foreach ($valores as $valor_nivel) {
            $valores_nivel = explode(':', $valor_nivel);
            $array_links .= $cont . ' => array( ';
            $cont2 = 0;
            foreach ($valores_nivel as $valores_nivel_elemento) {
                preg_match_all("/([\$]{0,2})([#]{0,1})([a-zA-Z0-9._.\-]+)(.*)|(.*?)/", $valores_nivel_elemento, $var, PREG_SET_ORDER);

                if (($var[0][1] == '') && ($var[0][2] == '')) {
                    $valor_a_array = $cont2 . ' => "' . $var[0][0] . '", ';
                } else {
                    $var[0][4] = strtr($var[0][4], array('[' => "['", ']' => "']"));
                    $var[0][0] = strtr($var[0][0], array('[' => "['", ']' => "']"));
                    if ($var[0][1] == '$$') {
                        $var[0][0] = substr($var[0][0], 1);
                    } else {
                        $var[0][0] = '""';
                    }
                    $valor_a_array = $cont2 . ' => ' . self::_variableArmado("n", $var[0][1] . $var[0][2], $var[0][3], $var[0][4], $var[0][0]) . ', ';
                }

                $array_links .= $valor_a_array;
                $cont2++;
            }
            $array_links .= ' ), ';
            $cont++;
        }
        $array_links .= ' ) ';

        return "<?=\$this->_links( " . $array_links . " )?>";
    }

    static private function _templateInterno($coincidencias) {

        if (preg_match("/(\\$){1}(\\$){0,1}(#){0,1}([a-zA-Z0-9._.\-]+)((\[[a-zA-Z0-9._.\-]+\]|\[\"[a-zA-Z0-9._.\-]+\"\]|\['[a-zA-Z0-9._.\-]+'\])*)/", $coincidencias[1], $n)) {
            if (!isset($n[5])) {
                $elementos_array = '';
            } else {
                $elementos_array = str_replace('"', "'", $n[5]);
            }
            $plantilla = self::_variableArmado("s", $n[1] . $n[2] . $n[3], $n[4], $elementos_array, $n[2] . $n[4] . $elementos_array);
        } elseif (preg_match('/\"([a-zA-Z0-9._.\-.;.:.\s]+)\"/', $coincidencias[1], $coincidencia)) {
            $plantilla = '"' . $coincidencia[1] . '"';
        }

        return "<?=\$this->_plantilla( " . $plantilla . " )?>";
    }

    static private function _tableTemplate($n) {

        if (!isset($n[7])) {
            $elementos_array = '';
        } else {
            $elementos_array = str_replace('"', "'", $n[7]);
        }

        return "<?=\$this->_table( '" . $n[2] . "', " . self::_variableArmado("n", $n[3] . $n[4] . $n[5], $n[6], $elementos_array, $n[2] . $n[4] . $elementos_array) . ", '" . $n[9] . "', '" . $n[10] . "', '" . $n[11] . "' )?>";
    }

    static public function _variableArmado($es_variable, $tipo, $nombre, $parametros, $valor = null) {

        switch ($tipo) {
            case "":
                if ($es_variable == 'n') {
                    return $nombre;
                } elseif ($es_variable == 's') {
                    if (!isset($valor)) {
                        return array();
                    }
                }
                break;
            case "$":
                return '@$this->_var_tpl["' . $nombre . '"]' . $parametros;
                break;
            case "$#":
                return '@$this->_var_tpl_glob["' . $nombre . '"]' . $parametros;
                break;
        }
        return $valor;
    }

    static private function _htmlArmado($tipo, $nombre, $opciones, $seleccionado, $class, $estilo, $html, $width = null, $rows = null, $nombre_compuesto) {

        if (strpos($nombre, '<') === false) {
            $nombre_control = false;
        } else {
            $nombre_control = true;
        }

        $error = '';
        $maxlength = '';

        if (($nombre_control === false) && (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'obligatorio') == 's')) {
            $error = '<br /><div id="VC_' . $nombre . '" class="VC_error"></div>';
        } elseif (($tipo != 'captcha') && ($nombre_control === true)) {
            $error = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "obligatorio")=="s"){ ?><br /><div id="VC_' . $nombre . '" class="VC_error"></div><?php } ?>';
        } elseif ($tipo == 'captcha') {
            $error = '<br /><div id="VC_captcha" class="VC_error"></div>';
        }

        if (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'tamano')) {
            $maxlength = ' maxlength="' . ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'tamano') . '"';
        } elseif ($nombre_control === true) {
            $maxlength = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "tamano")){ ?> maxlength="<?=ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "tamano")?>"<?php } ?>';
        }

        $obligatorio = '';
        $valor = '';
        $accion = '';

        if (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'etiqueta') != '') {
            $etiqueta_campo = ' etiqueta="' . ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'etiqueta') . '"';
        } elseif ($nombre_control === true) {
            $etiqueta_campo = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "etiqueta")){ ?> etiqueta="<?=ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "etiqueta")?>"<?php } ?>';
        } else {
            $etiqueta_campo = '';
        }

        if ($class != '') {
            $class = ' class="' . $class . '"';
        } else {
            $class = '';
        }

        if ($width != '') {
            $pixeles = '';
            if (is_numeric($width)) {
                $pixeles = 'px';
            }
            $style_width = 'width:' . $width . $pixeles . ';';
        } else {
            $style_width = '';
        }

        if (($estilo == '') && ($width != '')) {
            $estilo = ' style="' . $style_width . '"';
        } elseif ($estilo != '') {
            $estilo = ' style="' . $style_width . $estilo . '"';
        } else {
            $estilo = '';
        }

        switch ($tipo) {
            case 'input':
                if (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'obligatorio') == 's') {
                    $obligatorio = ' tipo="obligatorio"';
                } elseif ($nombre_control === true) {
                    $obligatorio = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "obligatorio")=="s"){ ?> tipo="obligatorio"<?php } ?>';
                }
                if (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'valor') != '') {
                    $valor = ' valor="' . ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'valor') . '"';
                } elseif ($nombre_control === true) {
                    $valor = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "valor")!=""){ ?> valor="<?=ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "valor")?>"<?php } ?>';
                }
                if (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'filtro') != '') {
                    $accion = ' filtro="' . ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'filtro') . '"';
                } elseif ($nombre_control === true) {
                    $accion = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "filtro")!=""){ ?> filtro="<?=ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "filtro")?>"<?php } ?>';
                }
                if ($seleccionado != "''") {
                    $seleccionado = '<?=' . $seleccionado . '?>';
                } else {
                    $seleccionado = '';
                }
                return '<input type="text" name="' . $nombre . '" id="' . $nombre . '" value="' . $seleccionado . '"' . $class . $estilo . $maxlength . $obligatorio . $valor . $accion . $etiqueta_campo . ' >' . $error;
                break;
            case 'textarea':
                if (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'obligatorio') == 's') {
                    $obligatorio = ' tipo="obligatorio" ';
                } elseif ($nombre_control === true) {
                    $obligatorio = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "obligatorio")=="s"){ ?> tipo="obligatorio"<?php } ?>';
                }
                if ($seleccionado != "''") {
                    $seleccionado = '<?=' . $seleccionado . '?>';
                } else {
                    $seleccionado = '';
                }
                if (is_numeric($rows)) {
                    $rows = ' rows="' . $rows . '"';
                }
                return '<textarea name="' . $nombre . '" id="' . $nombre . '"' . $rows . $class . $estilo . $maxlength . $obligatorio . $etiqueta_campo . '>' . $seleccionado . '</textarea>' . $error;
                break;
            case 'hidden':
                if (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'obligatorio') == 's') {
                    $obligatorio = ' tipo="obligatorio" ';
                } elseif ($nombre_control === true) {
                    $obligatorio = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "obligatorio")=="s"){ ?> tipo="obligatorio"<?php } ?>';
                }
                if ($seleccionado != "''") {
                    $seleccionado = '<?=' . $seleccionado . '?>';
                } else {
                    $seleccionado = '';
                }
                return '<input type="hidden" name="' . $nombre . '" id="' . $nombre . '" value="' . $seleccionado . '"' . $obligatorio . $etiqueta_campo . '>' . $error;
                break;
            case 'password':
                if (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'obligatorio') == 's') {
                    $obligatorio = ' tipo="obligatorio" ';
                } elseif ($nombre_control === true) {
                    $obligatorio = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "obligatorio")=="s"){ ?> tipo="obligatorio"<?php } ?>';
                }
                if ($seleccionado != "''") {
                    $seleccionado = '<?=' . $seleccionado . '?>';
                } else {
                    $seleccionado = '';
                }
                return '<input type="password" name="' . $nombre . '" id="' . $nombre . '" value="' . $seleccionado . '"' . $class . $estilo . $maxlength . $obligatorio . $etiqueta_campo . '>' . $error;
                break;
            case 'file':
                if (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'obligatorio') == 's') {
                    $obligatorio = ' tipo="obligatorio" ';
                } elseif ($nombre_control === true) {
                    $obligatorio = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "obligatorio")=="s"){ ?> tipo="obligatorio"<?php } ?>';
                }
                return '<input type="file" name="' . $nombre . '" id="' . $nombre . '"' . $class . $estilo . $obligatorio . $etiqueta_campo . '>' . $error;
                break;
            case 'select':
                if (ArmadoFormulario::getCampo(self::$_nombreArchivo, $nombre, 'obligatorio') == 's') {
                    $obligatorio = ' tipo="obligatorio" ';
                } elseif ($nombre_control === true) {
                    $obligatorio = '<?php if(ArmadoFormulario::getCampo("' . self::$_nombreArchivo . '", ' . $nombre_compuesto . ', "obligatorio")=="s"){ ?> tipo="obligatorio"<?php } ?>';
                }
                $armado = '<select name="' . $nombre . '" id="' . $nombre . '"' . $class . $estilo . $obligatorio . $etiqueta_campo . ' >';
                $armado .= "<?=\$this->_htmlArmadoSelect( " . $opciones . ", " . $seleccionado . " )?>";
                $armado .= '</select>';
                return $armado . $error;
                break;
            case 'checkboxes':
                $armado = "<?=\$this->_htmlArmadoCheckboxes( '" . $nombre . "', " . $opciones . ", " . $seleccionado . ", '" . $estilo . "', '" . $html . "', '" . $nombre_compuesto . "', '" . self::$_nombreArchivo . "' )?>";
                return $armado;
                break;
            case 'radios':
                $armado = "<?=\$this->_htmlArmadoRadios( '" . $nombre . "', " . $opciones . ", " . $seleccionado . ", '" . $estilo . "', '" . $html . "', '" . $nombre_compuesto . "', '" . self::$_nombreArchivo . "' )?>";
                return $armado;
                break;
            case 'captcha':
                $nombre = 'captcha';
                if ($width == '') {
                    $width = 50;
                }
                if ($seleccionado != "''") {
                    $seleccionado = '<?=' . $seleccionado . '?>';
                } else {
                    $seleccionado = '';
                }
                $armado = '<input type="text" name="' . $nombre . '" id="' . $nombre . '" value="' . $seleccionado . '"' . $class . $estilo . $maxlength . $obligatorio . $valor . $accion . $etiqueta_campo . '>';
                return $armado . $error;
                break;
        }
    }

    static private function _htmlArmadoForm($tipo, $nombre, $class = null, $estilo = null) {

        if ($class != '') {
            $class = ' class="' . $class . '"';
        } else {
            $class = '';
        }

        if (($estilo != '')) {
            $estilo = ' style="' . $estilo . '"';
        } else {
            $estilo = '';
        }

        if ($tipo == 'form') {
            $action = '<?=$_SERVER[\'REQUEST_URI\']?>';
        } elseif ($tipo == 'sform') {
            $action = 'https://<?=$_SERVER[\'HTTP_HOST\']?><?=$_SERVER[\'REQUEST_URI\']?>';
        }

        self::$_formulario = $nombre;

        return '<form method="post" name="' . $nombre . '" id="' . $nombre . '"' . $class . $estilo . ' enctype="multipart/form-data" action="' . $action . '" ><input name="kk_control_form" type="hidden" value="' . $nombre . '">';
    }

}

