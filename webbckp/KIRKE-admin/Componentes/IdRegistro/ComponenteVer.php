<?php

class Componentes_IdRegistro_ComponenteVer extends Armado_Plantilla {

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

        $plantilla['tb_campo'] = $this->_dcp['tb_campo'];
        if (isset($this->_dcp['listado_mostrar']) && ($this->_dcp['listado_mostrar'] == 's')) {
            $plantilla['lm_n'] = '';
            $plantilla['lm_s'] = 'checked="checked"';
        } else {
            $plantilla['lm_n'] = 'checked="checked"';
            $plantilla['lm_s'] = '';
        }
        if ($this->_dcp['filtrar'] == 's') {
            $plantilla['f_n'] = '';
            $plantilla['f_s'] = 'checked="checked"';
        } else {
            $plantilla['f_n'] = 'checked="checked"';
            $plantilla['f_s'] = '';
        }
        if ($this->_dcp['ocultar_edicion'] == 's') {
            $plantilla['oe_n'] = '';
            $plantilla['oe_s'] = 'checked="checked"';
        } else {
            $plantilla['oe_n'] = 'checked="checked"';
            $plantilla['oe_s'] = '';
        }
        if ($this->_dcp['ocultar_vista'] == 's') {
            $plantilla['ov_n'] = '';
            $plantilla['ov_s'] = 'checked="checked"';
        } else {
            $plantilla['ov_n'] = 'checked="checked"';
            $plantilla['ov_s'] = '';
        }

        $plantilla['etiqueta'] = Generales_EtiquetaIdiomasComponenteVer::get($this->_dcp);

        return Armado_PlantillasInternas::componentes('componente', $this->_nombreComponente, $plantilla);
    }

}
