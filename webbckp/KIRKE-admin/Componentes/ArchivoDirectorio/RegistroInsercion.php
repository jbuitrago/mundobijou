<?php

class Componentes_ArchivoDirectorio_RegistroInsercion {

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
        $_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
        if (is_array($this->_dcp)) {
            $this->_dcp = array_merge($_pv, $this->_dcp);
        } else {
            $this->_dcp = $_pv;
        }
    }

    public function get() {

        if (isset($_GET['guardar']) && ($_GET['guardar'] == 'dir')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            // si se pide no subir la imagen o no se envía contenido

            if (isset($_POST[$id_campo][1]) && ($_POST[$id_campo][1] == 'no_subir')) {
                return false;
            } elseif (isset($_POST[$id_campo][1]) && ($_POST[$id_campo][1] == 'eliminar')) {
                $this->_obtenerDirectorio();
                // elimina el archivo anterior 'no temporal'
                $this->_eliminarArchivo($this->_dcp['directorio'], $this->_idRegistro . '_' . $this->_dcp['cp_id']);
                return '';
            } else {
                if (is_uploaded_file($_FILES[$id_campo]['tmp_name'][0])) {
                    $this->_obtenerDirectorio();
                    move_uploaded_file($_FILES[$id_campo]['tmp_name'][0], $this->_dcp['directorio'] . '/' . $this->_idRegistro . '_' . $this->_dcp['cp_id'] . '_' . $_FILES[$id_campo]['name'][0]);
                    $this->_valor = $this->_idRegistro . '_' . $this->_dcp['cp_id'] . '_' . $_FILES[$id_campo]['name'][0];
                    return Bases_InyeccionSql::consulta($this->_valor);
                } else {
                    return false;
                }
            }
            return false;
        }

        if (isset($this->_valor['0'])) {
            $archivo_nombre = $this->_valor['0'];   // nombre del archivo
        } else {
            $archivo_nombre = '';
        }
        if (isset($this->_valor['1'])) {
            $accion = $this->_valor['1'];   // accion a realizar con el archivo
        } else {
            $accion = '';
        }

        if ($accion == 'actualizar') {

            $this->_obtenerDirectorio();

            // elimina el archivo anterior 'no temporal'
            $this->_eliminarArchivo($this->_dcp['directorio'], $this->_idComponente . '_' . $this->_idRegistro);

            // renombro el archvo para que passe a ser definitivo
            $archivo_nombre_matriz = explode("_", $archivo_nombre);
            $archivo_nombre_matriz = array_slice($archivo_nombre_matriz, 3);

            $archivo_nombre_definitivo = $this->_idComponente . '_' . $this->_idRegistro . '_' . implode('_', $archivo_nombre_matriz);
            $archivo_nombre_definitivo = preg_replace("/[^a-zA-Z0-9.()]/", '_', $archivo_nombre_definitivo);

            if (file_exists($this->_dcp['directorio'] . '/' . $archivo_nombre)) {
                rename($this->_dcp['directorio'] . '/' . $archivo_nombre, $this->_dcp['directorio'] . '/' . $archivo_nombre_definitivo);
            }

            $this->_valor = $archivo_nombre_definitivo;
        } elseif ($accion == 'eliminar') {

            $this->_obtenerDirectorio();

            $archivo = $this->_idComponente . '_' . $this->_idRegistro;

            // elimina el archivo anterior 'no temporal'
            $this->_eliminarArchivo($this->_dcp['directorio'], $archivo);

            // elimina el archivo anterior 'temporal'
            $this->_eliminarArchivo($this->_dcp['directorio'], 't_' . $archivo);

            $this->_valor = '';
        } else {

            // si no se elimina ni se sube ningun archivo, no se incluye el campo en la insercion
            return false;
        }

        return Bases_InyeccionSql::consulta($this->_valor);
    }

    private function _obtenerDirectorio() {

        $buscar = array("//", "\\\\");
        $sustituir = array("/", "\\");
        $this->_dcp['directorio'] = str_replace($buscar, $sustituir, $this->_dcp['directorio']);

        // obtengo el directirio donde se guardaran ala imaganes
        $url_actual = getcwd();
        chdir(Inicio::pathPublico());
        chdir($this->_dcp['directorio']);
        $this->_dcp['directorio'] = getcwd();
        chdir($url_actual);
    }

    private function _eliminarArchivo($directorio, $archivo) {

        // elimina el archivo anterior 'no temporal'
        $matriz = glob($directorio . '/' . $archivo . '_*.*');
        if (is_array($matriz)) {
            foreach ($matriz as $dir_nombre_archivo) {
                if (is_file($dir_nombre_archivo)) {
                    unlink($dir_nombre_archivo);
                }
            }
        }
    }

}
