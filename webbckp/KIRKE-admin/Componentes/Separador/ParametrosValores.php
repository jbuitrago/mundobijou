<?php

class Componentes_Separador_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['color_fondo'] = '1';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
