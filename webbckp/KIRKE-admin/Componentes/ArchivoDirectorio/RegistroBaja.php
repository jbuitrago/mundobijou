<?php

class Componentes_ArchivoDirectorio_RegistroBaja {

    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_directorio;

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
        $matriz = glob($this->_directorio . '/' . $this->_idComponente . '_' . $this->_idRegistro . '_*.*');
        if (is_array($matriz)) {
            foreach ($matriz as $dir_nombre_archivo) {
                if (is_file($dir_nombre_archivo)) {
                    unlink($dir_nombre_archivo);
                }
            }
        }

        return false;
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

