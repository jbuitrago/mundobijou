<?php

class Componentes_Numero_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'decimal';
        $parametro['tb_columna_tipo'] = 'nombre';
        $parametro['largo'] = '10';
        $parametro['decimales'] = '0';
        $parametro['listado_mostrar'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['filtrar'] = 's';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['predefinir_ultimo_val_cargado'] = 'n';
        $parametro['tb_campo'] = '';
        $parametro['totales_mostrar'] = 'n';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
