<?php

class Componentes_OpcionesPredefinidas_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'texto';
        $parametro['tb_columna_tipo'] = 'nombre';
        $parametro['listado_mostrar'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['filtrar'] = 's';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['predefinir_ultimo_val_cargado'] = 'n';
        $parametro['tb_campo'] = '';
        $parametro['valor_1'] = '';
        $parametro['valor_2'] = '';
        $parametro['valor_3'] = '';
        $parametro['valor_4'] = '';
        $parametro['valor_5'] = '';
        $parametro['valor_6'] = '';
        $parametro['valor_7'] = '';
        $parametro['valor_8'] = '';
        $parametro['valor_9'] = '';
        $parametro['valor_10'] = '';
        $parametro['valor_11'] = '';
        $parametro['valor_12'] = '';
        $parametro['valor_13'] = '';
        $parametro['valor_14'] = '';
        $parametro['valor_15'] = '';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
