<?php

class Componentes_ImagenDirectorio_ComponenteBaja {

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
        $_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
        $this->_dcp = array_merge($_pv, $this->_dcp);
        $this->_directorio = $this->_obtenerDirectorio();
    }

    public function get() {

        // elimina las imagenes
        if ($this->_dcp['eliminar_imagenes'] == 's') {
            // elimina los archivos en uso del componente
            $this->_eliminarArchivo($this->_directorio, $this->_idComponente);
            $this->_eliminarArchivo($this->_directorio, '/t_' . $this->_idComponente);
        }

        //elimino el contador
        if (file_exists($this->_directorio . '/.' . $this->_dcp['cp_id'] . '_id_tmp_contador.kirke')) {
            unlink($this->_directorio . '/.' . $this->_dcp['cp_id'] . '_id_tmp_contador.kirke');
        }

        return true;
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
                if (is_file($dir_nombre_archivo)) {
                    unlink($dir_nombre_archivo);
                }
                $nombre_archivo_encontrado = basename($dir_nombre_archivo);
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
