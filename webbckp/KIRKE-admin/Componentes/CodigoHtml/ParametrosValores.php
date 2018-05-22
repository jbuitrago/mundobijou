<?php

class Componentes_CodigoHtml_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'texto_largo';
        $parametro['tb_columna_tipo'] = 'nombre';
        $parametro['listado_mostrar'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['filtrar'] = 's';
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
