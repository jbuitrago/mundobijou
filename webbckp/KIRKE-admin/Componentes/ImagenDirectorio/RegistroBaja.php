<?php

class Componentes_ImagenDirectorio_RegistroBaja {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_directorio;

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    public function set($datos) {

        $this->_valor = $datos[0];
        $this->_metodo = $datos[1];
        $this->_dcp = $datos[2];
        $this->_idComponente = $datos[3];
        $this->_idRegistro = $datos[4];
        $this->_directorio = $this->_obtenerDirectorio();
    }

    public function get() {
        // elimina la imagen anterior 'no temporal'
        $this->_eliminarArchivo($this->_directorio, $this->_idComponente . '_' . $this->_idRegistro);

        return false;
    }

    private function _eliminarArchivo($directorio, $archivo) {
        $this->_eliminarArchivoDirectorio($directorio, $directorio, $archivo);
        $matriz_subdir = glob($directorio . '/*', GLOB_ONLYDIR);
        if (is_array($matriz_subdir)) {
            foreach ($matriz_subdir as $matriz_subdir_nombre) {
                $this->_eliminarArchivoDirectorio($directorio, $matriz_subdir_nombre, $archivo);
            }
        }
    }

    private function _eliminarArchivoDirectorio($directorio, $dub_directorio, $archivo) {
        // elimina el archivo anterior 'no temporal'
        $matriz = glob($dub_directorio . '/' . $archivo . '_*.*');
        if (is_array($matriz)) {
            foreach ($matriz as $dir_nombre_archivo) {
                $nombre_archivo_encontrado = basename($dir_nombre_archivo);
                if (is_file($dir_nombre_archivo)) {
                    unlink($dir_nombre_archivo);
                    for ($i = 1; $i <= 10; $i++) {
                        if (isset($this->_dcp['prefijo_' . $i]) && ($this->_dcp['prefijo_' . $i] != '')) {
                            $imagen_sub = $dub_directorio . '/' . $this->_dcp['prefijo_' . $i] . '_' . $nombre_archivo_encontrado;
                            if (is_file($imagen_sub)) {
                                unlink($imagen_sub);
                            }
                        }
                    }
                }
                if (is_file($directorio . '/.' . $this->_dcp['cp_nombre'] . '.kirke/kk_' . $nombre_archivo_encontrado)) {
                    unlink($directorio . '/.' . $this->_dcp['cp_nombre'] . '.kirke/kk_' . $nombre_archivo_encontrado);
                }
            }
        }
    }

    private function _obtenerDirectorio() {

        // guardo la imagen original en el directorio de destino con nombre t_g_[id-tabla]_[id-registro]_xxx
        $url_actual = getcwd();
        chdir(Inicio::pathPublico());
        chdir($this->_dcp['directorio']);
        $directorio = getcwd();
        chdir($url_actual);

        return $directorio;
    }

}
