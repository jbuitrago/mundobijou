<?php

class Componentes_Contrasena_RegistroInsercion {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;

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
        if (($this->_dcp['tipo'] == 'md5') && ($this->_valor != '')) {
            return md5(Bases_InyeccionSql::consulta($this->_valor));
        } elseif (($this->_dcp['tipo'] == 'sha1') && ($this->_valor != '')) {
            return sha1(Bases_InyeccionSql::consulta($this->_valor));
        } else {
            return false;
        }
    }

}
