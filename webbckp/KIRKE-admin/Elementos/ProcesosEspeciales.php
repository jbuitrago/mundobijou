<?php

class Elementos_ProcesosEspeciales {

    private $_accion;
    private $_tabla;

    public function __construct() {

        if ((($_GET['accion'] != 'RegistroModificacion') && ($_GET['accion'] != 'RegistroAlta')) || !isset($_GET['tabla'])) {
            return false;
        } else {
            $this->_accion = $_GET['accion'];
            $this->_tabla = $_GET['tabla'];
            $this->_mostrar();
        }
    }

    private function _mostrar() {
        die(file_get_contents(Inicio::path() . '/ProcesosEspeciales/' . $this->_accion . '-' . $this->_tabla . '.js'));
    }

}
