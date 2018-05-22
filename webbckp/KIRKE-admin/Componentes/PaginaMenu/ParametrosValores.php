<?php

class Componentes_PaginaMenu_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'numero';
        $parametro['tb_columna_tipo'] = 'nombre_link';
        $parametro['insercion_especial'] = 's';
        $parametro['modificacion_especial'] = 'n';
        $parametro['obligatorio'] = 'no_nulo';
        $parametro['eliminar_tb_relacionada'] = 'n';
        $parametro['filtrar'] = 's';
        $parametro['tb_nombre'] = '';
        $parametro['tb_prefijo'] = '';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
