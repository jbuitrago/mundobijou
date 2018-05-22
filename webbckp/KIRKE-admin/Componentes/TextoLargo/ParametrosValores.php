<?php

class Componentes_TextoLargo_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'texto_largo';
        $parametro['largo'] = '100000';
        $parametro['tb_columna_tipo'] = 'nombre';
        $parametro['alto'] = '5';
        $parametro['listado_mostrar'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['permite_html'] = 'n';
        $parametro['filtrar'] = 's';
        $parametro['link_mail'] = 'n';
        $parametro['link_url'] = 'n';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['tb_campo'] = '';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
