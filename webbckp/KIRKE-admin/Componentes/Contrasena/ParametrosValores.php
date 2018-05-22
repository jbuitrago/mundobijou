<?php

class Componentes_Contrasena_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'texto';
        $parametro['largo'] = '40';
        $parametro['largo_minimo'] = '8';
        $parametro['largo_maximo'] = '12';
        $parametro['tipo'] = 'sha1';
        $parametro['tb_columna_tipo'] = 'nombre';
        $parametro['obligatorio'] = 'nulo';
        $parametro['filtrar'] = 'n';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['tb_campo'] = '';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
