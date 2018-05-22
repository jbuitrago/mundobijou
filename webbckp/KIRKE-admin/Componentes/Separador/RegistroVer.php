<?php

class Componentes_Separador_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_colorFondo = 1;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    static private $link_a_destino = array();

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
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _registroValor() {
        return false;
    }

    private function _registroListadoCabezal() {
        return false;
    }

    private function _registroListadoCuerpo() {
        return false;
    }

    private function _registroListadoPie() {
        return '<td class="columna" class="columna_ancho_15">&nbsp;</td>';
    }

    private function _registroVer() {
        return $this->_tituloYComponente();
    }

    private function _registroAlta() {
        return $this->_tituloYComponente();
    }

    private function _registroAltaPrevia() {
        return $this->_tituloYComponente();
    }

    private function _registroModificacion() {
        return $this->_tituloYComponente();
    }

    private function _registroModificacionPrevia() {
        return $this->_tituloYComponente();
    }

    private function _registroFiltroCampo() {
        return false;
    }
    
// metodos especiales
    private function _tituloYComponente() {

        if (isset($this->_dcp['color_fondo'])) {
            $this->_colorFondo = $this->_dcp['color_fondo'];
        }
        $plantilla['color_fondo'] = $this->_colorFondo;
        
        if(Armado_DesplegableOcultos::mostrarOcultos()===true){
            $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
        }
        
        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

}

