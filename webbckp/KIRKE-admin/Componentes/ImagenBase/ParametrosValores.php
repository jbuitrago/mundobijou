<?php

class Componentes_ImagenBase_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'texto_largo';
        $parametro['largo'] = 200;
        $parametro['alto_final'] = '';
        $parametro['ancho_final'] = '';
        $parametro['listado_mostrar'] = 'n';
        $parametro['eliminar_imagenes'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['tb_campo'] = '';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
