<?php

class ArmadoEncabezados {

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

        $encabezados = '<meta charset="UTF-8">
<title>' . $sitio_nombre . '</title>
<link rel="icon" href="/favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">
<meta name="title" CONTENT="' . $sitio_nombre . '">
<meta name="description" CONTENT="' . VariableGet::globales('sitio_description') . '">
<meta name="Keywords" CONTENT="' . VariableGet::globales('sitio_palabras_claves') . '">
<meta name="Language" CONTENT="Spanish">
<meta name="Revisit" CONTENT="30 days">
<meta name="author" content="www.kirke.ws" >
<meta name="robots" CONTENT="all">
<meta name="rating" content="General">
<link rel="stylesheet" type="text/css" href="/css/estilos.css">
';

        if (ArmadoFormulario::existeFormulario()) {
            $encabezados .= '<link rel="stylesheet" type="text/css" href="/css/formulario.css">
';
        }

        $encabezados .= '<script type="text/javascript" language="javascript" src="/js/jquery.js"></script>
';

        if (ArmadoFormulario::existeFormulario()) {
            $encabezados .= '<script type="text/javascript" language="javascript" src="/js/formulario.js"></script>
<script type="text/javascript" language="javascript" src="/js/formulario_validaciones.js"></script>
';
        }

        if (ArmadoFormulario::existeFormulario()) {
            $encabezados .= '<script type="text/javascript">
$(document).ready(function(){
';
            $encabezados .= '	formulario();
';

            $encabezados .= '});
</script>		
';
        }

        VariableSet::globales('kk_encabezados', $encabezados);
    }

}
