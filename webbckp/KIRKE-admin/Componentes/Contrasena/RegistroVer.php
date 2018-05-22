<?php

class Componentes_Contrasena_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
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
        return $this->_valor;
    }

    private function _registroListadoCabezal() {
        return '<td class="columna" class="columna_ancho_15">&nbsp;<span class="titulo_tabla">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</span></td>';
    }

    private function _registroListadoCuerpo() {
        if (strlen($this->_valor) > 22) {
            $mostrar = substr($this->_valor, 0, 22) . '...';
            $title = ' title="' . $this->_valor . '"';
        } elseif ($this->_valor == '') {
            $mostrar = '<span class="texto_claro">&lt; {TR|m_sin_datos} &gt;</span>';
            $title = '';
        } else {
            $mostrar = $this->_valor;
            $title = '';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna columna_ancho_15'.$ocultar_celulares.'" ' . $title . ' >' . $mostrar . '</td>';
    }

    private function _registroListadoPie() {
        return '<td class="columna" class="columna_ancho_15">&nbsp;</td>';
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {

            if ($this->_valor == '') {
                $clave = '{TR|o_la_clave_no_existe}';
            } else {
                $clave = '{TR|o_la_clave_ya_existe}';
            }

            return $this->_tituloYComponente($clave);
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
            } else {
                $valor = '';
            }

            if ($this->_dcp['obligatorio'] == 'no_nulo') {
                $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
            } else {
                $obligatorio = '';
                $campo_error = '';
            }
            if (isset($this->_dcp['largo']) && ($this->_dcp['largo'] < 80)) {
                $largo_campo = $this->_dcp['largo'];
            } else {
                $largo_campo = 80;
            }

            $mostrar = '<input type="text" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" size="' . $largo_campo . '" control="CampoTexto" ' . $obligatorio . '/>' . $campo_error;
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAltaPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if ($_POST[$id_campo]) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />' . $valor;

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            // recupero de los valores obtenidos al volver de la vista previa
            if (isset($_POST[$id_campo]) && (Generales_Post::obtener($_POST[$id_campo], 'h') != '')) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
                if ($this->_valor == '') {
                    $clave = '{TR|o_la_clave_no_existe}';
                } else {
                    $clave = '{TR|o_la_clave_ya_existe}';
                }
            }
            if (isset($this->_dcp['largo']) && ($this->_dcp['largo'] < 80)) {
                $largo_campo = $this->_dcp['largo'];
            } else {
                $largo_campo = 80;
            }
            if (($this->_dcp['obligatorio'] == 'no_nulo') && ($valor != '')) {
                $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
            } else {
                $obligatorio = '';
                $campo_error = '';
            }

            $mostrar = '<input type="text" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" size="' . $largo_campo . '" control="CampoTexto" ' . $obligatorio . '/>' . $campo_error . ' ' . $clave;
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacionPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if ($_POST[$id_campo]) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />' . $valor;

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroFiltroCampo() {

        if (isset($this->_dcp['filtrar']) && ($this->_dcp['filtrar'] == 's')) {

            if (isset($this->_valor['condicion'])) {
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

        $descripciones[0] = '{TR|o_nulo}';
        $descripciones[1] = '{TR|o_no_nulo}';

        $valores[0] = 'nulo';
        $valores[1] = 'no_nulo';

        return Armado_SelectFiltros::armado($this->_dcp['cp_id'], $valores, $descripciones, $condicion);
    }

// metodos especiales
    private function _tituloYComponente($mostrar) {

        if ($this->_dcp['obligatorio'] == 'nulo') {
            $plantilla['obligatorio'] = '';
            if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
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

    private function _codigoHtml($variable_control, $valor) {

        if ($variable_control == 'n') {
            $buscados = array('<', '>');
            $reemplazantes = array('&lt;', '&gt;');
            return str_replace($buscados, $reemplazantes, $valor);
        } else {
            return Generales_Post::obtener($this->_valor);
        }
    }

}
