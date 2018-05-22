<?php

class Componentes_OpcionMultipleCheck_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'numero';
        $parametro['tb_columna_tipo'] = 'nombre_link';
        $parametro['insercion_especial'] = 's';
        $parametro['modificacion_especial'] = 's';
        $parametro['obligatorio'] = 'nulo';
        $parametro['eliminar_tb_relacionada'] = 's';
        $parametro['filtrar'] = 'n';
        $parametro['filtrar_texto'] = 'n';
        $parametro['tb_nombre'] = '';
        $parametro['tb_prefijo'] = '';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['predefinir_ultimo_val_cargado'] = 'n';
        $parametro['agregar_registro'] = 'n';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
