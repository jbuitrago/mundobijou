<?php

class Componentes_FechaSistema_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'numero';
        $parametro['tb_columna_tipo'] = 'fecha';
        $parametro['largo'] = '20';
        $parametro['formato_fecha'] = 'ddmmaaaa';
        $parametro['tipo'] = 'alta';
        $parametro['listado_mostrar'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['filtrar'] = 'n';
        $parametro['mostrar_hora'] = 's'; // se agrega para no hacer una programacion especial en el filtro
        $parametro['tb_campo'] = '';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
