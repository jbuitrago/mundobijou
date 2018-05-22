<?php

class Componentes_DesplegableTablasVarias_ComponenteVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $campo_relacionado;

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
        $this->campo_relacionado = $this->_tablasCampos();
        $this->campo_relacionado_ver = '<div class="contenido_margen"></div>
      <div class="contenido_titulo">{TR|o_tablas_a_agrupar}</div>
      <div class="contenido_7">' . $this->campo_relacionado . '
      <div id="desplegable_cont_0" class="DesplegableTablasVarias_cont"><select name="desplegable_0" id="desplegable_0" class="DesplegableTablasVarias"></select></div>
      <div id="desplegable_cont_1" class="DesplegableTablasVarias_cont"><select name="desplegable_1" id="desplegable_1" class="DesplegableTablasVarias"></select></div>
      <div id="desplegable_cont_2" class="DesplegableTablasVarias_cont"><select name="desplegable_2" id="desplegable_2" class="DesplegableTablasVarias"></select></div>
      <div id="desplegable_cont_3" class="DesplegableTablasVarias_cont"><select name="desplegable_3" id="desplegable_3" class="DesplegableTablasVarias"></select></div>
      <div id="desplegable_cont_4" class="DesplegableTablasVarias_cont"><select name="desplegable_4" id="desplegable_4" class="DesplegableTablasVarias"></select></div>
      <div id="desplegable_cont_5" class="DesplegableTablasVarias_cont"><select name="desplegable_5" id="desplegable_5" class="DesplegableTablasVarias"></select></div>
      <div id="desplegable_cont_6" class="DesplegableTablasVarias_cont"><select name="desplegable_6" id="desplegable_6" class="DesplegableTablasVarias"></select></div>
      <div id="desplegable_cont_7" class="DesplegableTablasVarias_cont"><select name="desplegable_7" id="desplegable_7" class="DesplegableTablasVarias"></select></div>
      <div id="desplegable_cont_8" class="DesplegableTablasVarias_cont"><select name="desplegable_8" id="desplegable_8" class="DesplegableTablasVarias"></select></div>
      <div id="desplegable_cont_9" class="DesplegableTablasVarias_cont"><select name="desplegable_9" id="desplegable_9" class="DesplegableTablasVarias"></select></div>
      </div>';
        return $this->_armado();
    }

    private function _componenteModificacion() {
        $this->campo_relacionado_ver = '';
        return $this->_armado();
    }

    private function _armado() {

        // encabezado necesario para validar la accion con javascript
        Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

        if ($this->_dcp['motrar_nombre_tabla'] == 's') {
            $plantilla['mnt_n'] = '';
            $plantilla['mnt_s'] = 'checked="checked"';
        } else {
            $plantilla['mnt_n'] = 'checked="checked"';
            $plantilla['mnt_s'] = '';
        }
        if ($this->_dcp['motrar_id'] == 's') {
            $plantilla['mi_n'] = '';
            $plantilla['mi_s'] = 'checked="checked"';
        } else {
            $plantilla['mi_n'] = 'checked="checked"';
            $plantilla['mi_s'] = '';
        }
        if ($this->_dcp['obligatorio'] == 'no_nulo') {
            $plantilla['co_n'] = '';
            $plantilla['co_s'] = 'checked="checked"';
        } else {
            $plantilla['co_n'] = 'checked="checked"';
            $plantilla['co_s'] = '';
        }
        if ($this->_dcp['autofiltro'] == 's') {
            $plantilla['aut_n'] = '';
            $plantilla['aut_s'] = 'checked="checked"';
        } else {
            $plantilla['aut_n'] = 'checked="checked"';
            $plantilla['aut_s'] = '';
        }
        if (!isset($this->_dcp['autofiltro_elementos'])) {
            $plantilla['autofiltro_elementos'] = '0';
        } else {
            $plantilla['autofiltro_elementos'] = $this->_dcp['autofiltro_elementos'];
        }
        if (!isset($this->_dcp['filtrar_antiguedad'])) {
            $plantilla['filtrar_antiguedad'] = '';
        } else {
            $plantilla['filtrar_antiguedad'] = $this->_dcp['filtrar_antiguedad'];
        }
        if (!isset($this->_dcp['campo'])) {
            $plantilla['campo'] = '';
        } else {
            $plantilla['campo'] = $this->_dcp['campo'];
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
        
        $plantilla['etiqueta'] = Generales_EtiquetaIdiomasComponenteVer::get($this->_dcp);
        $plantilla['tb_campo'] = $this->_dcp['tb_campo'];
        $plantilla['campo_relacionado_ver'] = $this->campo_relacionado_ver;

        if ($this->campo_relacionado === false) {
            $ver = '<div class="texto_advertencia_pagina" align="center">{TR|o_no_hay_tablas_para_relacionar}</div>';
        } else {
            $ver = Armado_PlantillasInternas::componentes('componente', $this->_nombreComponente, $plantilla);
        }

        return $ver;
    }

    // reordena los valores de los parámetro en una matriz utilizable
    private function _tablasCampos() {

        $matriz_tb_campo = Consultas_Componente::RegistroConsultaTabla(__FILE__, __LINE__, '', true);

        if ($matriz_tb_campo) {
            if ($matriz_tb_campo) {
                $tb_campo = '<select name="origen_cp_id" id="origen_cp_id">';
                foreach ($matriz_tb_campo as $linea) {
                    $tb_campo .= '<option value="' . $linea['id_componente'] . '">' . $linea['tabla_nombre'] . ' - ' . $linea['tabla_campo'] . '</option>';
                }
                $tb_campo .= '</select>';
            }
        } else {
            return false;
        }

        return $tb_campo;
    }

}
