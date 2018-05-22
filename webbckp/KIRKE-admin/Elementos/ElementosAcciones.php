<?php

class Elementos_ElementosAcciones extends Generales_Traduccion {

    private $_archivo;
    private $_directorio;
    private $_extencion;
    private $_extencionLimpia;

    public function __construct() {

        $partes_ruta = pathinfo($_GET['archivo']);

        $this->_extencion = $partes_ruta['extension'];

        $extencion_limpia = explode("?", $this->_extencion);
        $this->_extencionLimpia = $extencion_limpia[0];

        $extenciones = array('js');

        if (in_array($this->_extencionLimpia, $extenciones)) {

            $this->_archivo = $partes_ruta['basename'];

            if ($partes_ruta['dirname'] == '.') {
                $partes_ruta['dirname'] = '';
            }

            $this->_directorio = Inicio::path() . '/Acciones/' . $partes_ruta['dirname'];

            $this->_mostrar();
        } else {

            return false;
        }
    }

    private function _mostrar() {

        switch ($this->_extencionLimpia) {
            case 'ico':
                Header("Content-Type: image/x-icon");
                break;
            case 'gif':
                Header("Content-Type: image/gif");
                break;
            case 'jpg':
                Header("Content-Type: image/jpg");
                break;
            case 'png':
                Header("Content-Type: image/png");
                break;
            case 'js':
                Header("Content-Type: application/x-javascript");
                break;
            case 'html':
                Header("Content-Type: text/html; charset=utf-8");
                break;
            case 'htm':
                Header("Content-Type: text/html; charset=utf-8");
                break;
            case 'css':
                Header("Content-Type: text/css");
                break;
            case 'txt':
                Header("Content-Type: text/plain");
                break;
            case 'xml':
                Header("Content-Type:application/xhtml+xml; charset=utf-8");
                break;
            case 'pdf':
                Header("Content-Type:application/pdf");
                break;
        }

        $idioma = Generales_Idioma::obtener();

        $elemento = file_get_contents($this->_directorio . '/' . $this->_archivo);

        echo $self::traduccion($elemento, Inicio::path() . '/Traducciones/', $idioma, '/\{TR\|([a-z])_(.*?)\}/');

        exit();
    }

}

