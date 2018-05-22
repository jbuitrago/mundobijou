<?php

class Componentes_UsuarioSistema_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'numero';
        $parametro['tb_columna_tipo'] = 'fecha';
        $parametro['largo'] = '6';
        $parametro['tipo'] = 'alta';
        $parametro['listado_mostrar'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['filtrar'] = 'n';
        $parametro['tb_campo'] = '';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
