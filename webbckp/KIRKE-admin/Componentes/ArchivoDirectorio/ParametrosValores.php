<?php

class Componentes_ArchivoDirectorio_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'texto_largo';
        $parametro['directorio'] = '../upload_archivos';
        $parametro['obligatorio'] = 'nulo';
        $parametro['eliminar_archivos'] = 'n';
        $parametro['filtrar'] = 'n';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['tb_campo'] = '';

        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
