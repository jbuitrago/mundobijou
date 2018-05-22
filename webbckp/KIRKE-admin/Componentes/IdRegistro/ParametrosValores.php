<?php

class Componentes_IdRegistro_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['ocultar_vista'] = 'n';
        $parametro['filtrar'] = 's';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['tb_campo'] = '';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
