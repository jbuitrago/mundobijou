<?php

class Componentes_ImagenDirectorio_ComponenteVer extends Armado_Plantilla {

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

        if ($this->_metodo == 'componenteAlta') {
            $directorio = '../upload_imagenes';
            $plantilla['crear_directorio'] = '<div class="contenido_separador"></div>
      <div class="contenido_margen"></div>
      <div class="contenido_titulo"><span class="VC_campo_requerido">&#8226;</span> {TR|o_directorio}</div>
      <div class="contenido_7"><input type="text" name="directorio" id="directorio" size="50" value="' . $directorio . '" /><div class="VC_error" id="VC_directorio"></div><br>{TR|o_ej}: "../upload_imagenes" ({TR|o_debe_ser_una_url_relativa_a_este_sistema_que_apunte_a_un_directorio_con_los_permisos_correctos})</div>';
        } else {
            $plantilla['crear_directorio'] = '';
        }
        $plantilla['tb_campo'] = $this->_dcp['tb_campo'];
        if ($this->_dcp['listado_mostrar'] == 's') {
            $plantilla['lm_n'] = '';
            $plantilla['lm_s'] = 'checked="checked"';
        } else {
            $plantilla['lm_n'] = 'checked="checked"';
            $plantilla['lm_s'] = '';
        }
        if ($this->_dcp['eliminar_imagenes'] == 's') {
            $plantilla['ei_n'] = '';
            $plantilla['ei_s'] = 'checked="checked"';
        } else {
            $plantilla['ei_n'] = 'checked="checked"';
            $plantilla['ei_s'] = '';
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
        if (!isset($this->_dcp['alto_final'])) {
            $this->_dcp['alto_final'] = '';
        }
        if (!isset($this->_dcp['ancho_final'])) {
            $this->_dcp['ancho_final'] = '';
        }

        $plantilla['etiqueta'] = Generales_EtiquetaIdiomasComponenteVer::get($this->_dcp);
        $plantilla['alto_final'] = $this->_dcp['alto_final'];
        $plantilla['ancho_final'] = $this->_dcp['ancho_final'];

        if ($this->_dcp['separar_en_directorios'] == 'anio') {
            $plantilla['sd_n_s'] = '';
            $plantilla['sd_a_s'] = 'checked="checked"';
            $plantilla['sd_m_s'] = '';
        } elseif ($this->_dcp['separar_en_directorios'] == 'mes') {
            $plantilla['sd_n_s'] = '';
            $plantilla['sd_a_s'] = '';
            $plantilla['sd_m_s'] = 'checked="checked"';
        } else {
            $plantilla['sd_n_s'] = 'checked="checked"';
            $plantilla['sd_a_s'] = '';
            $plantilla['sd_m_s'] = '';
        }

        $plantilla['prefijo_1'] = $this->_dcp['prefijo_1'];
        $plantilla['ancho_1'] = $this->_dcp['ancho_1'];
        $plantilla['alto_1'] = $this->_dcp['alto_1'];
        $plantilla['prefijo_2'] = $this->_dcp['prefijo_2'];
        $plantilla['ancho_2'] = $this->_dcp['ancho_2'];
        $plantilla['alto_2'] = $this->_dcp['alto_2'];
        $plantilla['prefijo_3'] = $this->_dcp['prefijo_3'];
        $plantilla['ancho_3'] = $this->_dcp['ancho_3'];
        $plantilla['alto_3'] = $this->_dcp['alto_3'];
        $plantilla['prefijo_4'] = $this->_dcp['prefijo_4'];
        $plantilla['ancho_4'] = $this->_dcp['ancho_4'];
        $plantilla['alto_4'] = $this->_dcp['alto_4'];
        $plantilla['prefijo_5'] = $this->_dcp['prefijo_5'];
        $plantilla['ancho_5'] = $this->_dcp['ancho_5'];
        $plantilla['alto_5'] = $this->_dcp['alto_5'];
        $plantilla['prefijo_6'] = $this->_dcp['prefijo_6'];
        $plantilla['ancho_6'] = $this->_dcp['ancho_6'];
        $plantilla['alto_6'] = $this->_dcp['alto_6'];
        $plantilla['prefijo_7'] = $this->_dcp['prefijo_7'];
        $plantilla['ancho_7'] = $this->_dcp['ancho_7'];
        $plantilla['alto_7'] = $this->_dcp['alto_7'];
        $plantilla['prefijo_8'] = $this->_dcp['prefijo_8'];
        $plantilla['ancho_8'] = $this->_dcp['ancho_8'];
        $plantilla['alto_8'] = $this->_dcp['alto_8'];
        $plantilla['prefijo_9'] = $this->_dcp['prefijo_9'];
        $plantilla['ancho_9'] = $this->_dcp['ancho_9'];
        $plantilla['alto_9'] = $this->_dcp['alto_9'];
        $plantilla['prefijo_10'] = $this->_dcp['prefijo_10'];
        $plantilla['ancho_10'] = $this->_dcp['ancho_10'];
        $plantilla['alto_10'] = $this->_dcp['alto_10'];
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
        
        return Armado_PlantillasInternas::componentes('componente', $this->_nombreComponente, $plantilla);
    }

}
