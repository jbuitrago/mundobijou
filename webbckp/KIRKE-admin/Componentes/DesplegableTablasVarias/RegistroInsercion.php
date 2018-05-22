<?php

class Componentes_DesplegableTablasVarias_RegistroInsercion {

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
        if (preg_match('/([0-9]+;[a-z0-9._]+)/', trim($this->_valor), $val) !== false) {
            if (isset($val[1])) {
                if ($this->_dcp['predefinir_ultimo_val_cargado'] == 's') {
                    setcookie(hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente), Bases_InyeccionSql::consulta($val[1]), time() + 2592000);
                }
                return Bases_InyeccionSql::consulta($val[1]);
            } else {
                return '';
            }
        } else {
            return false;
        }
    }

}
