<?php

class Componentes_OpcionMultiple_ComponenteBaja {

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
        $this->_dcp = array_merge($_pv, $this->_dcp);
    }

    public function get() {

        if ($this->_dcp['eliminar_tb_relacionada'] == 's') {

            // obtengo los datos de la tabla a eliminar, lo hago antes de eliminar el
            // registro ya que sino no podría obtener los datos
            $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($this->_dcp['intermedia_tb_id'], 'sin_idioma');
            $intermedia_tb = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];

            // elimino la tabla intermedia
            Consultas_TablaEliminar::armado(__FILE__, __LINE__, $intermedia_tb);
        }

        // elimino el registro de la pagina
        Consultas_Tabla::RegistroEliminar(__FILE__, __LINE__, $this->_dcp['intermedia_tb_id']);

        return true;
    }

}

