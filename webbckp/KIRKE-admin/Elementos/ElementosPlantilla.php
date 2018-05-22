<?php

class Elementos_ElementosPlantilla {

    private $_archivo;
    private $_directorio;
    private $_extencion;

    public function __construct() {

        $partes_ruta = pathinfo($_GET['archivo']);

        $this->_extencion = $partes_ruta['extension'];
        $extenciones = array('ico', 'gif', 'jpg', 'png', 'js', 'html', 'htm', 'css', 'txt', 'xml', 'pdf');

        if (in_array($this->_extencion, $extenciones)) {

            $this->_archivo = $partes_ruta['basename'];

            // verifico que no sea javascript
            if ($this->_extencion != 'js') {
                $this->_directorio = Inicio::pathPublico() . '/Plantillas/' . Inicio::confVars('plantilla');
            } else {
                $this->_directorio = Inicio::pathPublico() . '/Plantillas/' . Inicio::confVars('plantilla') . '/js';
            }

            $this->_mostrar();
        } else {

            return false;
        }
    }

    private function _mostrar() {

        switch ($this->_extencion) {
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
            case 'pdf':
                Header("Content-Type:application/pdf");
                break;
            case 'txt':
                Header("Content-Type: text/plain");
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
            case 'xml':
                Header("Content-Type:application/xhtml+xml; charset=utf-8");
                break;
            case 'ico':
                Header("Content-Type: image/x-icon");
                break;
        }

        readfile($this->_directorio . '/' . $this->_archivo);
        exit();
    }

}

