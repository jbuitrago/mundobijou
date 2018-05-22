<?php

class Seguridad_EncriptacionVariablesGet {

    // encriptacion de la URL

    static public function encriptarUrl($url) {

        $url_encriptada = Seguridad_Encriptacion::encriptar($url);
        return 'var_kk=' . $url_encriptada;
    }

    // desencriptacion de la URL

    static public function desencriptarUrl() {

        $valor_desencriptado = Seguridad_Encriptacion::desencriptar($_GET['var_kk']);
        parse_str($valor_desencriptado, $vars_matriz);

        // elimino la variable var, ya que se autoagrega a los links
        // armando una cadena demaciado larga
        unset($vars_matriz['var_kk']);

        return $vars_matriz;
    }

}

