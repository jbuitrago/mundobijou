<?php

class Componentes_DesplegableMultiple_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'numero';
        $parametro['origen_cp_id_otros'] = '';
        $parametro['link_a_grupo'] = 'n'; // si es 's', debe guardarse el valor igualmente, para que funciones
        $parametro['tb_columna_tipo'] = 'nombre_link';
        $parametro['seleccionar_alta'] = 's';
        $parametro['listado_mostrar'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['sufijo'] = '';
        $parametro['autofiltro'] = '';
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
