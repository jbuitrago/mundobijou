<?php

class Armado_UsuarioNombreApellido extends Armado_Plantilla {

    private $_usuarioNombre;
    private $_usuarioApellido;

    public function __construct() {

        $this->_armado();
        $this->_armadoPlantillaSet('usuario_nombre', $this->_usuarioNombre);
        $this->_armadoPlantillaSet('usuario_apellido', $this->_usuarioApellido);
    }

    private function _armado() {

        $this->_usuarioNombre = Inicio::usuario('nombre');
        $this->_usuarioApellido = Inicio::usuario('apellido');
    }

}

