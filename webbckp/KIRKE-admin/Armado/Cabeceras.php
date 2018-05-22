<?php

class Armado_Cabeceras extends Armado_Plantilla {

    private static $_armadoComponentesCont = 0;
    private static $_armadoComponentesMatriz = Array();
    private static $_autocomplete = false;
    private static $_chosen = false;
    private static $_dynatree = false;

    static public function armado($tipo, $filtros = null) {
        switch ($tipo) {
            case 'general':
                $cabecera = self::_generales();
                $cabecera .= self::_cssGenerales();
                $cabecera .= self::_jsGenerales();
                break;
            case 'tabla':
                $cabecera = self::_generales();
                $cabecera .= self::_cssGenerales();
                $cabecera .= self::_cssTabla();
                $cabecera .= self::_jsGenerales();
                $cabecera .= self::_jsTabla();
                if (($filtros != null) && ($filtros == 'si')) {
                    $cabecera .= self::_jsTablaFiltros();
                    //$cabecera .= self::_cssFormularios();
                    $cabecera .= self::_jsFormularios();
                }
                break;
            case 'formulario':
                $cabecera = self::_generales();
                $cabecera .= self::_cssGenerales();
                $cabecera .= self::_cssFormularios();
                $cabecera .= self::_jsGenerales();
                $cabecera .= self::_jsFormularios();
                break;
            case 'tabla_formulario':
                $cabecera = self::_generales();
                $cabecera .= self::_cssGenerales();
                $cabecera .= self::_cssTabla();
                $cabecera .= self::_cssFormularios();
                $cabecera .= self::_jsGenerales();
                $cabecera .= self::_jsTabla();
                $cabecera .= self::_jsFormularios();
                if (($filtros != null) && ($filtros == 'si')) {
                    $cabecera .= self::_jsTablaFiltros();
                    //$cabecera .= self::_cssFormularios();
                    $cabecera .= self::_jsFormularios();
                }
                break;
        }
        return $cabecera;
    }

    static private function _generales() {
        $cabecera = '<title>KIRKE-admin</title>' . "\n";
        $cabecera .= '      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />' . "\n";
        $cabecera .= '      <link rel="icon" href="./favicon.ico" type="image/x-icon" />' . "\n";
        $cabecera .= '      <link rel="shortcut icon" href="./favicon.ico" type="image/x-icon" />' . "\n";
        return $cabecera;
    }

    static private function _cssGenerales() {
        $cabecera = '      <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/estilos.css">' . "\n";
        $cabecera .= '      <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/botones.css">' . "\n";
        $cabecera .= '      <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/menu_estatico.css">' . "\n";
        $cabecera .= '      <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/menu_dinamico.css">' . "\n";
        return $cabecera;
    }

    static private function _cssTabla() {
        return '        <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/tablas.css">' . "\n";
    }

    static private function _cssFormularios() {
        $cabecera = '       <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/formularios.css">' . "\n";
        $cabecera .= '      <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/jquery-ui-1.8.18.custom.css">' . "\n";
        return $cabecera;
    }

    static private function _jsGenerales() {
        $cabecera = '      <script type="text/javascript" language="javascript" src="./js/jquery.min.js"></script>' . "\n";
        $cabecera .= '      <script type="text/javascript" language="javascript" src="./js/menu_estatico.js"></script>' . "\n";
        $cabecera .= '      <script type="text/javascript" language="javascript" src="./js/menu_dinamico.js"></script>' . "\n";
        $cabecera .= '      <script type="text/javascript" language="javascript" src="./js/menu.js"></script>' . "\n";
        return $cabecera;
    }

    static private function _jsTabla() {
        return '        <script type="text/javascript" language="javascript" src="./js/acciones_registros.js"></script>' . "\n";
    }

    static private function _jsFormularios() {
        $cabecera = '      <script type="text/javascript" language="javascript" src="./js/formularios.js"></script>' . "\n";
        $cabecera .= '      <script type="text/javascript" language="javascript" src="./js/jquery-ui.min.js"></script>' . "\n";
        $cabecera .= '      <script type="text/javascript" language="javascript" src="./js/jquery-ui-timepicker-addon.js"></script>' . "\n";
        return $cabecera;
    }

    static private function _jsTablaFiltros() {
        return '        <script type="text/javascript" language="javascript" src="./js/filtros.js"></script>' . "\n";
    }

    static public function armadoComponentes($componente, $archivo) {

        $nombre = pathinfo($archivo);

        $DS = DIRECTORY_SEPARATOR;
        $extensiones = array('js', 'css');

        foreach ($extensiones as $extension) {
            if (array_search($componente . $nombre['filename'] . $extension, self::$_armadoComponentesMatriz) === false) {
                if (file_exists(Inicio::path() . $DS . 'Componentes' . $DS . $componente . $DS . 'Archivos' . $DS . $nombre['filename'] . '.' . $extension)) {
                    self::$_armadoComponentesMatriz[self::$_armadoComponentesCont] = $componente . $nombre['filename'] . $extension;
                    self::$_armadoComponentesCont++;
                    switch ($extension) {
                        case 'js':
                            $armado_plantilla = new Armado_Plantilla();
                            $armado_plantilla->_armadoPlantillaSet('cabeceras', self::_js('./index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '3', 'componente' => $componente, 'archivo' => $nombre['filename'] . '.' . $extension, 'traducir' => 's'))));
                            break;
                        case 'css':
                            $armado_plantilla = new Armado_Plantilla();
                            $armado_plantilla->_armadoPlantillaSet('cabeceras', self::_css('./index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '3', 'componente' => $componente, 'archivo' => $nombre['filename'] . '.' . $extension, 'traducir' => 's'))));
                            break;
                    }
                }
            }
        }
    }

    static public function jsProcesosEspeciales($accion, $tabla) {
        $armado_plantilla = new Armado_Plantilla();
        $armado_plantilla->_armadoPlantillaSet('cabeceras', self::_js('./index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '8', 'accion' => $accion, 'tabla' => $tabla))));
    }

    static private function _css($link) {
        return '      <link type="text/css" rel="stylesheet" href="' . $link . '">' . "\n";
    }

    static private function _js($link) {
        return '      <script type="text/javascript" language="javascript" src="' . $link . '"></script>' . "\n";
    }

    // SOLO PARA ELEMENTOS PUNTUALES

    static public function autocomplete() {
        if (self::$_autocomplete === false) {
            $armado_plantilla = new Armado_Plantilla();
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <script type="text/javascript" language="javascript" src="./js/autocomplete.js"></script>' . "\n");
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/autocomplete.css">' . "\n");
            self::$_autocomplete = true;
        }
    }

    static public function chosen() {
        if (self::$_chosen === false) {
            $armado_plantilla = new Armado_Plantilla();
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <script type="text/javascript" language="javascript" src="./js/chosen.jquery.min.js"></script>' . "\n");
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/chosen.css">' . "\n");
            self::$_chosen = true;
        }
    }

    static public function dynatree() {
        if (self::$_dynatree === false) {
            $armado_plantilla = new Armado_Plantilla();
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <script type="text/javascript" language="javascript" src="./js/menu_subir_bajar.js"></script>' . "\n");
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <script type="text/javascript" language="javascript" src="./js/jquery.dynatree.js"></script>' . "\n");
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <script type="text/javascript" language="javascript" src="./js/jquery-ui.custom.dynatree.js"></script>' . "\n");
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/ui.dynatree.css">' . "\n");
            self::$_dynatree = true;
        }
    }

    static public function colorbox($elementos) {
        if (self::$_dynatree === false) {
            $armado_plantilla = new Armado_Plantilla();
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <script type="text/javascript" language="javascript" src="./js/jquery.colorbox.js"></script>' . "\n");
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <link type="text/css" rel="stylesheet" href="./Plantillas/' . Inicio::confVars('plantilla') . '/css/colorbox.css">' . "\n");
            $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <script type="text/javascript">
            $(document).ready(function(){
                $(".iframe_colorbox").colorbox({iframe:true, width:"80%", height:"80%"});
            });                    
            function actualizar_componentes(){' . $elementos . "\n" . '            }' . "\n" . '       </script>' . "\n");
            self::$_dynatree = true;
        }
    }

}
