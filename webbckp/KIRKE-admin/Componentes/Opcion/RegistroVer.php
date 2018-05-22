<?php

class Componentes_Opcion_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;

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
        }else{
            $ocultar_celulares = '';
        }
        
        return Armado_RegistroListadoCabezal::armado($this->_dcp['cp_id'], $this->_dcp['tb_campo'], $this->_dcp['idioma_' . Generales_Idioma::obtener()], $ocultar_celulares);
    }

    private function _registroListadoCuerpo() {
        if ($this->_valor == '1') {
            $imagen = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '3', 'componente' => 'Opcion', 'archivo' => 'cp_Opcion_1.png', 'traducir' => 'n'));
            $mostrar = '<img src="' . $imagen . '" alt="{TR|o_si}" /> ';
        } elseif ($this->_valor == '0') {
            $imagen = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '3', 'componente' => 'Opcion', 'archivo' => 'cp_Opcion_0.png', 'traducir' => 'n'));
            $mostrar = '<img src="' . $imagen . '" alt="{TR|o_no}" /> ';
        } else {
            $imagen = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '3', 'componente' => 'Opcion', 'archivo' => 'cp_Opcion_nulo.png', 'traducir' => 'n'));
            $mostrar = '<img src="' . $imagen . '" alt="{TR|o_sin_valor}" /> ';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden"';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'">' . $mostrar . '</td>';
    }

    private function _registroListadoPie() {
        if (!isset($this->_dcp['ocultar_celulares'])) {
            return '<td class="columna kk_resp_hidden">&nbsp;</td>';
        }
        return false;
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {
            if ($this->_valor == '1') {
                $mostrar = '{TR|o_si}';
            } elseif ($this->_valor == '0') {
                $mostrar = '<font color="#FF0000">{TR|o_no}</font>';
            } else {
                $mostrar = '{TR|o_sin_valor}';
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
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST[$id_campo]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                $valor = $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)];
            } else {
                $valor = '';
            }

            if (( $valor == '1') || ($this->_dcp['activado'] == 's')) {
                $muestra_activado = 'checked="checked"';
            } else {
                $muestra_activado = '';
            }

            $mostrar = '<input type="checkbox" name="' . $id_campo . '" id="' . $id_campo . '" value="1" ' . $muestra_activado . ' />';
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

            if ($valor == '1') {
                $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="1" />';
                $mostrar .= '{TR|o_si}';
            } else {
                $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="0" />';
                $mostrar .= "{TR|o_no}";
            }
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            if ($valor == '0') {
                $muestra_activado = '';
            } elseif (( $valor == '1') || ($this->_valor == '1')) {
                $muestra_activado = 'checked="checked"';
            } else {
                $muestra_activado = '';
            }
            $mostrar = '<input type="checkbox" name="' . $id_campo . '" id="' . $id_campo . '" value="1" ' . $muestra_activado . ' />';
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

            if ($valor == '1') {
                $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="1" />';
                $mostrar .= '{TR|o_si}';
            } else {
                $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="0" />';
                $mostrar .= '{TR|o_no}';
            }
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroFiltroCampo() {

        if (isset($this->_dcp['filtrar']) && ($this->_dcp['filtrar'] == 's')) {
            if (isset($this->_valor['valor']) && isset($this->_valor['condicion']) && ($this->_valor['valor'] == 1) && ($this->_valor['condicion'] == 'iguales')) {
                $condicion = 'activo';
            } elseif (isset($this->_valor['valor']) && isset($this->_valor['condicion']) && ($this->_valor['valor'] == 0) && ($this->_valor['condicion'] == 'iguales')) {
                $condicion = 'inactivo';
            } elseif (isset($this->_valor['condicion'])) {
                $condicion = $this->_valor['condicion'];
            } else {
                $condicion = '';
            }
            $template = '
                <td><div class="filtros_texto">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</div>
                <input name="parametro_' . $this->_dcp['cp_id'] . '" id="parametro_' . $this->_dcp['cp_id'] . '" type="hidden" />
                </td>
                <td>' . $this->_registroFiltroCampoOpciones($condicion) . '</td>
                <td></td>
                <td><div class="bt_tb_eliminar_filtro" filtro_eliminar_id="' . $this->_dcp['cp_id'] . '"></div></td>
            ';

            return $template;
        } else {

            return false;
        }
    }

    private function _registroFiltroCampoOpciones($condicion) {

        $descripciones[0] = '{TR|o_si}';
        $descripciones[1] = '{TR|o_no}';
        $descripciones[2] = '{TR|o_con_estado}';
        $descripciones[3] = '{TR|o_sin_estado}';

        $valores[0] = 'activo';
        $valores[1] = 'inactivo';
        $valores[2] = 'no_nulo';
        $valores[3] = 'nulo';

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

}
