<?php

// este archivo solo existira en los componentes con baja especial 
class Componentes_PaginaMenu_RegistroBaja {

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

        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($this->_dcp['intermedia_tb_id'], 'sin_idioma');
        $intermedia_tb = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];

        // elimino el campo de la tabla
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $intermedia_tb, 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], $this->_idRegistro);

        return false;
    }

}

