<?php

class Componentes_OpcionMultipleCheck_ComponenteVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $campo_relacionado;
    private $crear_tabla;

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
    <div class="contenido_titulo">{TR|o_relacion}</div>
    <div class="contenido_7">&nbsp;' . $this->campo_relacionado . '</div>';

        $this->crear_tabla = '<div class="contenido_margen"></div>
    <div class="contenido_titulo"><span class="VC_campo_requerido">&#8226;</span> {TR|o_nombre_de_la_tabla_de_relacion}</div>
    <div class="contenido_7">' . Armado_PrefijoSelect::armado($this->_dcp['tb_prefijo']) . '&nbsp;&nbsp;<input type="text" name="nombre_tabla_relacion" id="nombre_tabla_relacion" size="25" value="' . $this->_dcp['tb_nombre'] . '" maxlength="25" no_nulo="{TR|o_debe_ingresar_un_dato}" filtro="abcdefghijklmnopqrstuvwxyz0123456789_" /> <div class="VC_error" id="VC_nombre_tabla_relacion"></div></div>';

        return $this->_armado();
    }

    private function _componenteModificacion() {
        $this->campo_relacionado_ver = '';
        $this->crear_tabla = '';
        return $this->_armado();
    }

    private function _armado() {

        // encabezado necesario para validar la accion con javascript
        Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

        $plantilla['etiqueta'] = Generales_EtiquetaIdiomasComponenteVer::get($this->_dcp);
        if ($this->_dcp['eliminar_tb_relacionada'] == 's') {
            $plantilla['et_n'] = '';
            $plantilla['et_s'] = 'checked="checked"';
        } else {
            $plantilla['et_n'] = 'checked="checked"';
            $plantilla['et_s'] = '';
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
        if ($this->_dcp['filtrar_texto'] == 's') {
            $plantilla['ft_n'] = '';
            $plantilla['ft_s'] = 'checked="checked"';
        } else {
            $plantilla['ft_n'] = 'checked="checked"';
            $plantilla['ft_s'] = '';
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
        if ($this->_dcp['agregar_registro'] == 's') {
            $plantilla['ar_n'] = '';
            $plantilla['ar_s'] = 'checked="checked"';
        } else {
            $plantilla['ar_n'] = 'checked="checked"';
            $plantilla['ar_s'] = '';
        }
        
        if (!isset($this->_dcp['separador_del_campo_1'])) {
            $plantilla['separador_del_campo_1'] = '';
        } else {
            $plantilla['separador_del_campo_1'] = $this->_dcp['separador_del_campo_1'];
        }
        
        if (!isset($this->_dcp['nombre_del_campo_1'])) {
            $plantilla['nombre_del_campo_1'] = '';
        } else {
            $plantilla['nombre_del_campo_1'] = $this->_dcp['nombre_del_campo_1'];
        }        

        if (!isset($this->_dcp['separador_del_campo_2'])) {
            $plantilla['separador_del_campo_2'] = '';
        } else {
            $plantilla['separador_del_campo_2'] = $this->_dcp['separador_del_campo_2'];
        }
        
        if (!isset($this->_dcp['nombre_del_campo_2'])) {
            $plantilla['nombre_del_campo_2'] = '';
        } else {
            $plantilla['nombre_del_campo_2'] = $this->_dcp['nombre_del_campo_2'];
        }

        if ($this->campo_relacionado === false) {
            $ver = '<div class="texto_advertencia_pagina" align="center">{TR|o_no_hay_tablas_para_relacionar}</div>';
        } else {

            $plantilla['crear_tabla'] = $this->crear_tabla;
            $plantilla['campo_relacionado_ver'] = $this->campo_relacionado_ver;

            $ver = Armado_PlantillasInternas::componentes('componente', $this->_nombreComponente, $plantilla);
        }

        return $ver;
    }

    // reordena los valores de los parámetro en una matriz utilizable
    private function _tablasCampos() {

        $matriz_tb_campo = Consultas_Componente::RegistroConsultaTabla(__FILE__, __LINE__, $_GET['id_tabla']);

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
