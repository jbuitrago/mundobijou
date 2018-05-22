<?php

class Componentes_PaginaTabuladores_ParametrosValores {

    static private $_parametros;

    public static function set() {
        self::$_parametros = array();
    }

    public static function get() {

        return self::$_parametros;
    }

}
