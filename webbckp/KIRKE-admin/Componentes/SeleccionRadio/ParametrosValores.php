<?php

class Componentes_SeleccionRadio_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'numero';
        $parametro['link_a_grupo'] = 'n'; // si es 's', debe guardarse el valor igualmente, para que funcione
        $parametro['tb_columna_tipo'] = 'nombre_link';
        $parametro['sufijo'] = '';
        $parametro['seleccionar_alta'] = 's';
        $parametro['listado_mostrar'] = 'n';
        $parametro['filtrar'] = 's';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['predefinir_ultimo_val_cargado'] = 'n';
        $parametro['tb_campo'] = '';
        $parametro['agregar_registro'] = 'n';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
