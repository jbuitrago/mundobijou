<?php

class ArchivoBajar {

    private $_nombreArchivo;
    private $_nombreReal;
    private $_directorio;

    public function nombreArchivo($_nombreArchivo) {

        $this->_nombreArchivo = $_nombreArchivo;
    }

    public function directorio($_directorio) {

        $this->_directorio = $_directorio;
    }

    public function bajar() {

        header("Content-type: application/force-download");
        header("Content-type: application/octet-stream");
        header("Content-type: application/download");
        header('Content-disposition: attachment; filename="' . $this->nombreReal() . '"');

        echo @file_get_contents($this->_directorioActual() . '/' . $this->_nombreArchivo);
    }

    public function obtenerNombre() {

        return $this->_directorioActual() . '/' . $this->nombreReal();
    }

    public function nombreReal($nombre = null) {

        if ($nombre == null) {
            $nombre_archivo = $this->_nombreArchivo;
        } else {
            $nombre_archivo = $nombre;
        }

        return preg_replace("/^([0-9]+)[_]([0-9]+)[_]/", "", $nombre_archivo);
    }

    private function _directorioActual() {

        $url_actual = getcwd();
        chdir($this->_directorio);
        $_directorio = getcwd();
        chdir($url_actual);

        return $_directorio;
    }

}
