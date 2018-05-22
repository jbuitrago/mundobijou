<?php

class Componentes_DesplegableNumeros_ComponenteVer extends Armado_Plantilla {

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
        return $this->_armado();
    }

    private function _componenteModificacion() {
        return $this->_armado();
    }

    private function _armado() {

        // encabezado necesario para validar la accion con javascript
        Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

        $plantilla['tb_campo'] = $this->_dcp['tb_campo'];
        if ($this->_dcp['listado_mostrar'] == 's') {
            $plantilla['lm_n'] = '';
            $plantilla['lm_s'] = 'checked="checked"';
        } else {
            $plantilla['lm_n'] = 'checked="checked"';
            $plantilla['lm_s'] = '';
        }
        if ($this->_dcp['totales_mostrar'] == 's') {
            $plantilla['vt_n'] = '';
            $plantilla['vt_s'] = 'checked="checked"';
        } else {
            $plantilla['vt_n'] = 'checked="checked"';
            $plantilla['vt_s'] = '';
        }
        if ($this->_dcp['obligatorio'] == 'no_nulo') {
            $plantilla['co_n'] = '';
            $plantilla['co_s'] = 'checked="checked"';
        } else {
            $plantilla['co_n'] = 'checked="checked"';
            $plantilla['co_s'] = '';
        }
        $plantilla['etiqueta'] = Generales_EtiquetaIdiomasComponenteVer::get($this->_dcp);

        if ($this->_dcp['minimo'] != '') {
            $plantilla['numeros_minimo'] = $this->_dcp['minimo'];
        } else {
            $plantilla['numeros_minimo'] = '0';
        }
        if ($this->_dcp['maximo'] != '') {
            $plantilla['numeros_maximo'] = $this->_dcp['maximo'];
        } else {
            $plantilla['numeros_maximo'] = '100';
        }
        if ($this->_dcp['intervalo'] != '') {
            $plantilla['numeros_intervalo'] = $this->_dcp['intervalo'];
        } else {
            $plantilla['numeros_intervalo'] = '1';
        }
        if ($this->_dcp['filtrar'] == 's') {
            $plantilla['f_n'] = '';
            $plantilla['f_s'] = 'checked="checked"';
        } else {
            $plantilla['f_n'] = 'checked="checked"';
            $plantilla['f_s'] = '';
        }
        if ($this->_dcp['valor_predefinido'] != '') {
            $plantilla['valor_predefinido'] = $this->_dcp['valor_predefinido'];
        } else {
            $plantilla['valor_predefinido'] = '';
        }
        if ($this->_dcp['autofiltro'] == 's') {
            $plantilla['aut_n'] = '';
            $plantilla['aut_s'] = 'checked="checked"';
        } else {
            $plantilla['aut_n'] = 'checked="checked"';
            $plantilla['aut_s'] = '';
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
        if ($this->_dcp['predefinir_ultimo_val_cargado'] == 's') {
            $plantilla['puvc_n'] = '';
            $plantilla['puvc_s'] = 'checked="checked"';
        } else {
            $plantilla['puvc_n'] = 'checked="checked"';
            $plantilla['puvc_s'] = '';
        }
        
        return Armado_PlantillasInternas::componentes('componente', $this->_nombreComponente, $plantilla);
    }

}
