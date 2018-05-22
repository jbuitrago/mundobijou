<?php

class Componentes_ImagenDirectorio_ParametrosValores {

    static private $_parametros;

    public static function set() {

        $parametro['tipo_dato'] = 'texto_largo';
        $parametro['directorio'] = '../upload_imagenes';
        $parametro['alto_final'] = '';
        $parametro['ancho_final'] = '';
        $parametro['listado_mostrar'] = 'n';
        $parametro['obligatorio'] = 'nulo';
        $parametro['eliminar_imagenes'] = 'n';
        $parametro['filtrar'] = 'n';
        $parametro['ocultar_edicion'] = 'n';
        $parametro['ocultar_vista'] = 'n';
        $parametro['tb_campo'] = '';
        $parametro['separar_en_directorios'] = 'n';
        $parametro['prefijo_1'] = '';
        $parametro['ancho_1'] = '';
        $parametro['alto_1'] = '';
        $parametro['prefijo_2'] = '';
        $parametro['ancho_2'] = '';
        $parametro['alto_2'] = '';
        $parametro['prefijo_3'] = '';
        $parametro['ancho_3'] = '';
        $parametro['alto_3'] = '';
        $parametro['prefijo_4'] = '';
        $parametro['ancho_4'] = '';
        $parametro['alto_4'] = '';
        $parametro['prefijo_5'] = '';
        $parametro['ancho_5'] = '';
        $parametro['alto_5'] = '';
        $parametro['prefijo_6'] = '';
        $parametro['ancho_6'] = '';
        $parametro['alto_6'] = '';
        $parametro['prefijo_7'] = '';
        $parametro['ancho_7'] = '';
        $parametro['alto_7'] = '';
        $parametro['prefijo_8'] = '';
        $parametro['ancho_8'] = '';
        $parametro['alto_8'] = '';
        $parametro['prefijo_9'] = '';
        $parametro['ancho_9'] = '';
        $parametro['alto_9'] = '';
        $parametro['prefijo_10'] = '';
        $parametro['ancho_10'] = '';
        $parametro['alto_10'] = '';
        $parametro['color_fondo'] = 'FFFFFF';
        $parametro['img_ancho_ver'] = '500';
        
        self::$_parametros = $parametro;
    }

    public static function get() {

        return self::$_parametros;
    }

}
