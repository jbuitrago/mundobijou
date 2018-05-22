<?php

class Componentes_OpcionesPredefinidas_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_opciones = array();

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
        for ($i = 1; $i <= 15; $i++) {
            if ($this->_dcp['valor_' . $i] != '') {
                $this->_opciones[$this->_dcp['valor_' . $i]] = $this->_dcp['etiqueta_' . $i . '_' . Generales_Idioma::obtener()];
            }
        }
    }

    public function get() {
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _registroValor() {
        return $this->_valor;
    }

    private function _registroListadoCabezal() {
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
        
        return Armado_RegistroListadoCabezal::armado($this->_dcp['cp_id'], $this->_dcp['tb_campo'], $this->_dcp['idioma_' . Generales_Idioma::obtener()], $ocultar_celulares, 'left');
    }

    private function _registroListadoCuerpo() {
        if (isset($this->_opciones[$this->_valor])) {
            $valor = $this->_opciones[$this->_valor];
        } else {
            $valor = '<span class="texto_claro">&lt; {TR|m_sin_valor} &gt;</span>';
        }
        if (strlen($valor) > 100) {
            $mostrar = substr($valor, 0, 100) . '...';
            $title = 'title="' . $valor . '"';
        } else {
            $mostrar = $valor;
            $title = '';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden"';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'" ' . $title . '><div class="tabla_ocultar_sobrante">' . $mostrar . '</div></td>';
    }

    private function _registroListadoPie() {
        if (!isset($this->_dcp['ocultar_celulares'])) {
            return '<td class="columna kk_resp_hidden">&nbsp;</td>';
        }
        return false;
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {
            if (isset($this->_opciones[$this->_valor])) {
                $mostrar = $valor = $this->_opciones[$this->_valor];
            } else {
                $mostrar = '';
            }
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAlta() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $this->_valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST[$id_campo]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                $this->_valor = $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)];
            } else {
                $this->_valor = '';
            }

            $mostrar = $this->_armadoOpciones($id_campo, $this->_valor);

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAltaPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';
            $mostrar .= $this->_opciones[$valor];
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            // recupero de los valores obtenidos al volver de la vista previa
            if (isset($_POST[$id_campo]) && Generales_Post::obtener($_POST[$id_campo], 'h') != '') {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = Generales_Post::obtener($this->_valor, 'h');
            }

            $mostrar = $this->_armadoOpciones('cp_' . $this->_dcp['cp_id'], $this->_valor);

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacionPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';

            if (isset($this->_opciones[$valor])) {
                $mostrar .= $this->_opciones[$valor];
            }

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroFiltroCampo() {

        if (isset($this->_dcp['filtrar']) && ($this->_dcp['filtrar'] == 's')) {

            if (isset($this->_valor['valor'])) {
                $valor = $this->_valor['valor'];
            } else {
                $valor = '';
            }
            if (isset($this->_valor['condicion'])) {
                $condicion = $this->_valor['condicion'];
            } else {
                $condicion = '';
            }

            if (($condicion == '') || ($condicion == 'nulo') || ($condicion == 'no_nulo')) {
                $style = ' style="display:none"';
            } else {
                $style = '';
            }

            $template = '
                <td><div class="filtros_texto">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</div>
                <input name="parametro_' . $this->_dcp['cp_id'] . '" id="parametro_' . $this->_dcp['cp_id'] . '" type="hidden" />
                </td>
                <td>' . $this->_registroFiltroCampoOpciones($condicion) . '</td>
                <td><div id="valor_' . $this->_dcp['cp_id'] . '" ' . $style . '>' . $this->_armadoOpciones('valor_' . $this->_dcp['cp_id'], $valor) . '</div></td>
                <td><div class="bt_tb_eliminar_filtro" filtro_eliminar_id="' . $this->_dcp['cp_id'] . '"></div></td>
            ';

            return $template;
        } else {

            return false;
        }
    }

    private function _registroFiltroCampoOpciones($condicion) {

        $descripciones[0] = '{TR|o_igual_a}';
        $descripciones[1] = '{TR|o_distinto_a}';
        $descripciones[2] = '{TR|o_nulo}';
        $descripciones[3] = '{TR|o_no_nulo}';

        $valores[0] = 'iguales';
        $valores[1] = 'distintos';
        $valores[2] = 'nulo';
        $valores[3] = 'no_nulo';

        return Armado_SelectFiltros::armado($this->_dcp['cp_id'], $valores, $descripciones, $condicion);
    }

// metodos especiales
    private function _tituloYComponente($mostrar) {

        if ($this->_dcp['obligatorio'] == 'nulo') {
            $plantilla['obligatorio'] = '';
            if(Armado_DesplegableOcultos::mostrarOcultos()===true){
                $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
            }
        } else {
            $plantilla['obligatorio'] = '<span class="VC_campo_requerido">&#8226;</span> ';
            $plantilla['ocultar'] = '';
        }
        $plantilla['idioma_generales_idioma'] = $this->_dcp['idioma_' . Generales_Idioma::obtener()];
        $plantilla['mostrar'] = $mostrar;

        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            $plantilla['campo_nombre'] = '<br /><span class="texto_nombre_campos">( ' . $this->_dcp['tb_campo'] . ' ) </span><span class="texto_id_campos"> ( ' . $this->_dcp['cp_id'] . ' )</span>';
        } else {
            $plantilla['campo_nombre'] = '';
        }
        
        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _armadoOpciones($id_campo, $valor) {

        $opciones = '';
        for ($i = 1; $i <= 15; $i++) {
            if ($this->_dcp['valor_' . $i] != '') {
                if ($this->_dcp['valor_' . $i] == $valor) {
                    $seleccionado = 'checked="checked"';
                } else {
                    $seleccionado = '';
                }
                $opciones .= '<input name="' . $id_campo . '" id="' . $id_campo . '" type="radio" value="' . $this->_dcp['valor_' . $i] . '" ' . $seleccionado . ' />' . $this->_dcp['etiqueta_' . $i . '_' . Generales_Idioma::obtener()] . '<br />';
            }
        }
        return $opciones;
    }

}
