<?php

class MostrarErroresPlantilla {

    private $_nombreArchivo;
    private $_var_tpl = array();
    private $_var_tpl_glob = array();
    private $_controlTpl = false;

    function __construct() {
        
    }

    function fn_links($link) {
        if (is_array($link)) {
            if ($_linksSeo == false) {
                $concatenador = '?';
                $urlNva = '';
                foreach ($link as $nombre => $valor) {
                    $urlNva .= $concatenador . $nombre . '=' . urlencode($valor);
                    $concatenador = '&';
                }
                return 'index.php' . htmlentities($urlNva);
            } else {
                // armar URL SEO
            }
        }
    }

    function fn_modificadores($tipo, $nombre, $parametros, $modificadores, $variable = null) {
        switch ($tipo) {
            case "":
                eval('$valor = $this->_var_tpl["' . $nombre . '"]' . $parametros . ';');
                break;
            case "$":
                $valor = $variable;
                break;
            case "#":
                eval('$valor = $this->_var_tpl_glob["' . $nombre . '"]' . $parametros . ';');
                break;
        }
        if ($modificadores != '') {
            $modificadoresArmar = explode('|', $modificadores);
            if (is_array($modificadoresArmar)) {
                foreach ($modificadoresArmar as $modificador) {
                    switch ($modificador) {
                        case "strip_tags":
                            $valor = strip_tags($valor);
                            break;
                        case "upper":
                            $valor = strtoupper($valor);
                            break;
                        case "lower":
                            $valor = strtolower($valor);
                            break;
                        case "capitalize":
                            $valor = ucfirst($valor);
                            break;
                        case "nl2br":
                            $valor = nl2br($valor);
                            break;
                    }
                    if (substr($modificador, 0, 11) == 'date_format') {
                        $valores = explode(':', $modificadores);
                        $valor = date(trim($valores[1], "'\""), $valor);
                    }
                    if (substr($modificador, 0, 8) == 'truncate') {
                        $valores = explode(':', $modificadores);
                        if (strlen($valor) > $valores[1]) {
                            $valor = substr(si1, 0, $valores[1]) . trim($valores[2], "'\"");
                        }
                    }
                    if (substr($modificador, 0, 7) == 'default') {
                        $valores = explode(':', $modificadores);
                        if (trim($valor) == '') {
                            $valor = trim($valores[1], "'\"");
                        }
                    }
                    if (substr($modificador, 0, 13) == 'regex_replace') {
                        $valores = explode(':', $modificadores);
                        $valor = preg_replace(trim($valores[1], "'\""), trim($valores[2], "'\""), $valor);
                    }
                    if (substr($modificador, 0, 7) == 'replace') {
                        $valores = explode(':', $modificadores);
                        $valor = str_replace(trim($valores[1], "'\""), trim($valores[2], "'\""), $valor);
                    }
                    if (substr($modificador, 0, 13) == 'string_format') {
                        $valores = explode(':', $modificadores);
                        $valor = sprintf(trim($valores[1], "'\""), $valor);
                    }
                    if (substr($modificador, 0, 6) == 'escape') {
                        $valores = explode(':', $modificadores);
                        switch ($valores[1]) {
                            case "html":
                                $valor = html_entity_decode($valor);
                                break;
                            case "htmlall":
                                $valor = htmlspecialchars($valor);
                                break;
                            case "url":
                                $valor = urldecode($valor);
                                break;
                            case "quotes":
                                $valor = addslashes($valor);
                                break;
                        }
                    }
                }
            }
        }
        return $valor;
    }

}
