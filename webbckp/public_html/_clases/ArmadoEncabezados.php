<?php

class ArmadoEncabezados {

    private static $encabezados_estaticos = array();

    public static function set() {

        // Trabajo con las URL para obtener el nombre del sitio
        ArmadoLinks::setCacheURL();
        $url = ArmadoLinks::getCacheURL();

        $secciones = '';
        if (VariableControl::getSitioNombre() === false) {
            if (is_string($url) && ($url != '')) {
                $secciones = strtr($url, array('/' => ' | ', '-' => ' - '));
            }
            if (isset($_GET['id'])) {
                $secciones = substr($secciones, 0, -(strlen($_GET['id']) + 3));
            }
        }

        $sitio_nombre = VariableGet::globales('sitio_nombre') . $secciones;

        if ((VariableGet::sistema('subniveles_inferiores_css_js') != false) && (VariableGet::sistema('subniveles_inferiores_css_js') != '')) {
            $url_subnivel = '/' . VariableGet::sistema('subniveles_inferiores_css_js');
        } else {
            $url_subnivel = '';
        }

        $encabezados = '
        <meta charset="UTF-8">
        <title>' . $sitio_nombre . '</title>
        <link rel="icon" href="' . $url_subnivel . '/favicon.ico" type="image/x-icon">
        <link rel="shortcut icon" href="' . $url_subnivel . '/favicon.ico" type="image/x-icon">
        <meta name="title" CONTENT="' . $sitio_nombre . '">
        <link rel="canonical" href="' . self::urlActual() . '" />
        <meta name="description" CONTENT="' . VariableGet::globales('sitio_description') . '">
        <meta name="keywords" CONTENT="' . VariableGet::globales('sitio_palabras_claves') . '">
        <meta name="generator" content="KIRKE-framework ' . Version::get() . '" >
        <meta name="robots" CONTENT="all">
        <meta name="rating" content="General">' . RedesSociales::obtenerDatos() . '
        <link rel="stylesheet" type="text/css" href="' . $url_subnivel . '/css/estilos.css">';

        if (ArmadoFormulario::existeFormulario()) {
            $encabezados .= '
        <link rel="stylesheet" type="text/css" href="' . $url_subnivel . '/css/formulario.css">';
        }

        $encabezados .= '
        <script type="text/javascript" language="javascript" src="' . $url_subnivel . '/js/jquery.js"></script>';
        if (ArmadoFormulario::existeFormulario()) {
            $encabezados .= '
        <script type="text/javascript" language="javascript" src="' . $url_subnivel . '/js/formulario.js"></script>
        <script type="text/javascript" language="javascript" src="' . $url_subnivel . '/js/formulario_validaciones.js"></script>';
        }

        if (ArmadoFormulario::existeFormulario()) {
            $encabezados .= '
        <script type="text/javascript">
        $(document).ready(function(){';
            $encabezados .= '
        formulario();';
            $encabezados .= '
        });
        </script>';
        }

        if (count(self::$encabezados_estaticos) > 0) {
            $encabezados .= implode("\n", self::$encabezados_estaticos) . "\n";
        }
        VariableSet::globales('kk_encabezados', $encabezados);
    }

    private static function urlActual() {
        if (!empty($s['HTTPS'])) {
            $scheme = $_SERVER['REQUEST_SCHEME'];
        } else {
            $scheme = 'http';
        }
        if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI']) && isset($_SERVER['QUERY_STRING'])) {
            return $scheme . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'];
        } else {
            return false;
        }
    }

    public static function encabezadosEstaticos($encabezado) {
        self::$encabezados_estaticos[] = $encabezado;
    }

}
