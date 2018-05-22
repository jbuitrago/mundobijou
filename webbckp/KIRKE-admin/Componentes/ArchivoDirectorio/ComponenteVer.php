<?php

class Componentes_ArchivoDirectorio_ComponenteVer extends Armado_Plantilla {

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
        if ($this->_metodo == 'componenteAlta') {
            $directorio = '../upload_archivos';
            $plantilla['crear_directorio'] = "<div class=\"contenido_separador\"></div>
      <div class=\"contenido_margen\"></div>
      <div class=\"contenido_titulo\"><span class='VC_campo_requerido'>&#8226;</span> {TR|o_directorio}</div>
      <div class=\"contenido_7\"><input type='text' name='directorio' id='directorio' size='50' value='" . $directorio . "' /><div class=\"VC_error\" id=\"VC_directorio\"></div><br>{TR|o_ej}: \"../upload_archivos\" ({TR|o_debe_ser_una_url_relativa_a_este_sistema_que_apunte_a_un_directorio_con_los_permisos_correctos})</div>";
        } else {
            $plantilla['crear_directorio'] = '';
        }
        if ($this->_dcp['eliminar_archivos'] == 's') {
            $plantilla['ea_n'] = '';
            $plantilla['ea_s'] = 'checked="checked"';
        } else {
            $plantilla['ea_n'] = 'checked="checked"';
            $plantilla['ea_s'] = '';
        }
        if ($this->_dcp['obligatorio'] == 'no_nulo') {
            $plantilla['co_n'] = '';
            $plantilla['co_s'] = 'checked="checked"';
        } else {
            $plantilla['co_n'] = 'checked="checked"';
            $plantilla['co_s'] = '';
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
