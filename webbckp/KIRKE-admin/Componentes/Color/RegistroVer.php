<?php

class Componentes_color_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    static private $link_a_destino = array();
    private $_caracteres_falta_alerta = 5;

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
        return $this->_valor;
    }

    private function _registroListadoCabezal() {

        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        } else {
            $ocultar_celulares = '';
        }

        return Armado_RegistroListadoCabezal::armado($this->_dcp['cp_id'], $this->_dcp['tb_campo'], $this->_dcp['idioma_' . Generales_Idioma::obtener()], $ocultar_celulares);
    }

    private function _registroListadoCuerpo() {
        if (($this->_valor == '') || !is_string($this->_valor)) {
            $mostrar = '<span class="texto_claro">&lt; {TR|m_sin_color} &gt;</span>';
            $title = '';
        } else {
            $mostrar = '<div style="height: 13px; width: 17px; float: left; margin-right: 10px; border-style:solid; border-width: thin; border-color:#CCC; background-color: #' . $this->_valor . '"></div>';
            $title = '';
        }

        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden"';
        } else {
            $ocultar_celulares = '';
        }

        return '<td class="columna' . $ocultar_celulares . '" ' . $title . '><div class="tabla_ocultar_sobrante">' . $mostrar . '</div></td>';
    }

    private function _registroListadoPie() {
        if (!isset($this->_dcp['ocultar_celulares'])) {
            return '<td class="columna kk_resp_hidden">&nbsp;</td>';
        }
        return false;
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {
            if (($this->_valor == '') || !is_string($this->_valor)) {
                $mostrar = '<span class="texto_claro">&lt; {TR|m_sin_color} &gt;</span>';
                return $this->_tituloYComponente($mostrar);
            } else {
                $mostrar = '
                <style>
                    #divp_' . $this->_dcp['cp_id'] . ' {
                        height: 50px;
                        width: 50px;
                        font-weight:normal;
                        background-color: #' . Generales_Post::obtener($this->_valor) . ';
                        display: table;
                        border-style:solid;
                        border-width: thin;
                        border-color:#CCC;
                    }
                    #divh_' . $this->_dcp['cp_id'] . ' {
                        display: table-cell;
                        vertical-align: middle;
                        text-align: center;

                    }
                </style>
                ';
                $mostrar .= '<div id="divp_' . $this->_dcp['cp_id'] . '"><div id="divh_' . $this->_dcp['cp_id'] . '">' . Generales_Post::obtener($this->_valor) . '</div></div>';
                return $this->_tituloYComponente($mostrar);
            }
        } else {

            return '';
        }
    }

    private function _registroAlta() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST[$id_campo]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                $valor = $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)];
            } else {
                $valor = $this->_dcp['color_inicial'];
            }

            $campo_error = '';
            if ($this->_dcp['obligatorio'] == 'no_nulo') {
                $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
                $js_obligatorio = '';
            } else {
                $obligatorio = '';
                $js_obligatorio = 'required:false, ';
            }

            $mostrar = '
                <button class="jscolor {' . $js_obligatorio . 'valueElement:\'' . $id_campo . '\', onFineChange:\'setTextColor(this)\'}" style="height: 30px; width: 30px; border-style:solid; border-width: thin; border-color:#CCC;"></button>
                <input name="' . $id_campo . '" id="' . $id_campo . '" value="' . Generales_Post::obtener($this->_valor) . '"  control="CampoTexto" maxlength="6" filtro="ABCDEFabcdef0123456789" ' . $obligatorio . '>
            ';

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAltaPrevia() {
        return $this->_vistaPrevia();
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            // recupero de los valores obtenidos al volver de la vista previa
            if (isset($_POST[$id_campo]) && Generales_Post::obtener($_POST[$id_campo], 'h') != '') {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = Generales_Post::obtener($this->_valor, 'h');
            }

            if ($this->_dcp['obligatorio'] == 'no_nulo') {
                $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
                $js_obligatorio = '';
            } else {
                $obligatorio = '';
                $campo_error = '';
                $js_obligatorio = 'required:false, ';
            }

            $mostrar = '
                <button class="jscolor {' . $js_obligatorio . 'valueElement:\'' . $id_campo . '\', onFineChange:\'setTextColor(this)\'}" style="height: 30px; width: 30px; border-style:solid; border-width: thin; border-color:#CCC;">  </button>
                <input name="' . $id_campo . '" id="' . $id_campo . '" value="' . Generales_Post::obtener($this->_valor) . '"  control="CampoTexto" maxlength="6" filtro="ABCDEFabcdef0123456789" ' . $obligatorio . '>
            ';

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacionPrevia() {
        return $this->_vistaPrevia();
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
                <td><input ' . $style . ' type="text" name="valor_' . $this->_dcp['cp_id'] . '" id="valor_' . $this->_dcp['cp_id'] . '" value="' . $valor . '" class="filtro_Texto" /></td>
                <td><div class="bt_tb_eliminar_filtro" filtro_eliminar_id="' . $this->_dcp['cp_id'] . '"></div></td>
            ';

            return $template;
        } else {

            return false;
        }
    }

    private function _registroFiltroCampoOpciones($condicion) {

        $descripciones[0] = '{TR|o_igual_a}';
        $descripciones[1] = '{TR|o_contiene}';
        $descripciones[2] = '{TR|o_no_contiene}';
        $descripciones[3] = '{TR|o_nulo}';
        $descripciones[4] = '{TR|o_no_nulo}';

        $valores[0] = 'semejante';
        $valores[1] = 'coincide';
        $valores[2] = 'no_coincide';
        $valores[3] = 'nulo';
        $valores[4] = 'no_nulo';

        return Armado_SelectFiltros::armado($this->_dcp['cp_id'], $valores, $descripciones, $condicion);
    }

// metodos especiales

    private function _vistaPrevia() {
        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            if (($valor == '') || !is_string($valor)) {
                $mostrar = '<span class="texto_claro">&lt; {TR|m_sin_datos} &gt;</span>';
            } else {
                $mostrar = '
                <style>
                    #divp_' . $id_campo . ' {
                        height: 50px;
                        width: 50px;
                        font-weight:normal;
                        background-color: #' . $valor . ';
                        display: table;
                        border-style:solid;
                        border-width: thin;
                        border-color:#CCC;
                    }
                    #divh_' . $id_campo . ' {
                        display: table-cell;
                        vertical-align: middle;
                        text-align: center;

                    }
                </style>
                ';
                $mostrar .= '<div id="divp_' . $id_campo . '"><div id="divh_' . $id_campo . '">' . $valor . '</div></div>';
                $mostrar .= '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';
            }

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _tituloYComponente($mostrar, $ocular = null) {

        if ($this->_dcp['obligatorio'] == 'nulo') {
            $plantilla['obligatorio'] = '';
            if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
                $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
            }
        } else {
            $plantilla['obligatorio'] = '<span class="VC_campo_requerido">&#8226;</span> ';
            $plantilla['ocultar'] = '';
        }

        $caracteres = strlen($this->_valor);
        $palabras = (count(preg_split('/\b[\s,\.\-:;]*/', $this->_valor)) - 1) / 2;

        $plantilla['idioma'] = $this->_dcp['idioma_' . Generales_Idioma::obtener()];
        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            $plantilla['campo_nombre'] = '<br /><span class="texto_nombre_campos">( ' . $this->_dcp['tb_campo'] . ' ) </span><span class="texto_id_campos"> ( ' . $this->_dcp['cp_id'] . ' )</span>';
        } else {
            $plantilla['campo_nombre'] = '';
        }
        $plantilla['mostrar'] = $mostrar;

        if ($ocular == null) {
            $plantilla['ocultar_cp'] = '';
        } else {
            $plantilla['ocultar_cp'] = $ocular;
        }

        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

}
