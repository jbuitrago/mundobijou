<?php

class Componentes_Texto_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'texto';
        $parametro['largo'] = '50';
        $parametro['tb_columna_tipo'] = 'nombre';
        $parametro['listado_mostrar'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['permite_html'] = 'n';
        $parametro['caracteres_permitidos'] = '';
        $parametro['filtrar'] = 's';
        $parametro['link_mail'] = 'n';
        $parametro['link_url'] = 'n';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['predefinir_ultimo_val_cargado'] = 'n';
        $parametro['tb_campo'] = '';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
