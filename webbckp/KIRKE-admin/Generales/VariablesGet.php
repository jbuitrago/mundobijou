<?php

class Generales_VariablesGet {

    static public function obtener($encriptar_url, $encriptar = null) {

        if ((Inicio::confVars('encriptar_url') == 's') && isset($encriptar) && ($encriptar == 's')) {

            $url_obj = new Seguridad_EncriptacionVariablesGet();
            $url_desencriptada = $url_obj->desencriptarUrl();

            return $url_desencriptada;
        } else {
            return $_GET;
        }
    }

    static public function armar($valores, $encriptar = null) {
        if (is_array($valores)) {
            $url = '';
            foreach ($valores as $k => $v) {
                if (!is_array($v)) {
                    $url .= '&' . $k . '=' . $v;
                }
            }
            $url = substr($url, 1);
            if ((Inicio::confVars('encriptar_url') == 's') && isset($encriptar) && ($encriptar == 's')) {
                $url_obj = new Seguridad_EncriptacionVariablesGet();
                $url_encriptada = $url_obj->encriptarUrl($url);
                return $url_encriptada;
            } else {
                return $url;
            }
        } else {
            return false;
        }
    }

    static public function obtenerArray($encriptar = null) {

        if ((Inicio::confVars('encriptar_url') == 's') && isset($encriptar) && ($encriptar == 's')) {
            $url_obj = new Seguridad_EncriptacionVariablesGet();
            $variables_get = $url_obj->desencriptarUrl();
        } else {
            if (isset($_GET)) {
                $variables_get = $_GET;
            } else {
                $variables_get = array();
            }
        }

        return $variables_get;
    }

}
