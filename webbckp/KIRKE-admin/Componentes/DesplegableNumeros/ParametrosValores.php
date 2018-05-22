<?php

class Componentes_DesplegableNumeros_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'numero';
        $parametro['tb_columna_tipo'] = 'nombre';
        $parametro['largo'] = '3';
        $parametro['listado_mostrar'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['minimo'] = '0';
        $parametro['maximo'] = '100';
        $parametro['intervalo'] = '1';
        $parametro['filtrar'] = 's';
        $parametro['valor_predefinido'] = '';
        $parametro['autofiltro'] = 'n';
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
