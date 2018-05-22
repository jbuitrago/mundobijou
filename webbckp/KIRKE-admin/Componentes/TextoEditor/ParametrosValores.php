<?php

class Componentes_TextoEditor_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'texto_largo';
        $parametro['tb_columna_tipo'] = 'nombre';
        $parametro['listado_mostrar'] = 'n';
        $parametro['alto'] = '200';
        $parametro['menu'] = 'n';
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
