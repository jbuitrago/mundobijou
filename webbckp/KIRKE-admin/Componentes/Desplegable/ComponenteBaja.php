<?php

class Componentes_Desplegable_ComponenteBaja {

    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;

    public function set($datos) {

        $this->_valor = $datos[0];
        $this->_metodo = $datos[1];
        $this->_dcp = $datos[2];
        $this->_idComponente = $datos[3];
        $this->_idRegistro = $datos[4];
    }

    public function get() {

        Consultas_TablaParametros::RegistroEliminarValor(__FILE__, __LINE__, $this->_idComponente);

        return true;
    }

}

