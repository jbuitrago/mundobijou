<?php

class Elementos_ElementosDesarrollos {

    private $_archivo;
    private $_directorio;
    private $_extencion;

    public function __construct() {

        $partes_ruta = pathinfo($_GET['archivo']);

        $this->_extencion = $partes_ruta['extension'];
        $extenciones = array('gif', 'jpg', 'png', 'js', 'html', 'htm', 'css', 'txt', 'xml', 'pdf');

        if (in_array($this->_extencion, $extenciones)) {

            $this->_archivo = $partes_ruta['basename'];

            if ($this->_extencion == 'css') {
                $dir = 'css';
            } elseif ($this->_extencion == 'js') {
                $dir = 'js';
            } elseif ($this->_extencion == 'xml') {
                $dir = 'xml';
            } elseif ($this->_extencion == 'pdf') {
                $dir = 'pdf';
            } elseif ($this->_extencion == 'gif' || $this->_extencion == 'jpg' || $this->_extencion == 'png') {
                $dir = 'img';
            } elseif ($this->_extencion == 'html' || $this->_extencion == 'htm' || $this->_extencion == 'txt') {
                $dir = 'archivos';
            }

            $this->_directorio = Inicio::path() . '/Desarrollos/' . $_GET['kk_desarrollo'] . '/' . $dir;

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

        readfile($this->_directorio . '/' . $this->_archivo);
        exit();
    }

}

