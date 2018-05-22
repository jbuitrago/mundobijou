<?php

class Componentes_DesplegableTablasVarias_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'texto';
        $parametro['tb_columna_tipo'] = 'nombre_link';
        $parametro['motrar_nombre_tabla'] = 'n';
        $parametro['motrar_id'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['autofiltro'] = 'n';
        $parametro['autofiltro_elementos'] = '0';
        $parametro['filtrar_antiguedad'] = '';
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
