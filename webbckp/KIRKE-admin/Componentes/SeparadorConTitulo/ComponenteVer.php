<?php

class Componentes_SeparadorConTitulo_ComponenteVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    public function set($datos = NULL) {
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
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

// metodos de acciones

    private function _componenteAlta() {
        $this->_dcp['cp_nombre'] = $this->_nombreComponente;
        return $this->_armado();
    }

    private function _componenteModificacion() {
        return $this->_armado();
    }

    private function _armado() {

        // encabezado necesario para validar la accion con javascript
        Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

        $plantilla['c_0'] = '';
        $plantilla['c_1'] = '';
        $plantilla['c_2'] = '';
        $plantilla['c_3'] = '';
        
        if ($this->_dcp['color_fondo'] == 0) {
            $plantilla['c_0'] = 'checked="checked"';
        } elseif ($this->_dcp['color_fondo'] == 1) {
            $plantilla['c_1'] = 'checked="checked"';
        } elseif ($this->_dcp['color_fondo'] == 2) {
            $plantilla['c_2'] = 'checked="checked"';
        } elseif ($this->_dcp['color_fondo'] == 3) {
            $plantilla['c_3'] = 'checked="checked"';
        }

        $plantilla['etiqueta'] = Generales_EtiquetaIdiomasComponenteVer::get($this->_dcp);

        return Armado_PlantillasInternas::componentes('componente', $this->_nombreComponente, $plantilla);
    }

}
