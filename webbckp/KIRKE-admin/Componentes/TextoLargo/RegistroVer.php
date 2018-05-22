<?php

class Componentes_TextoLargo_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_caracteres_falta_alerta = 10;
    private $_codigoJS = '';

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
        if (($this->_valor == '') || !is_string($this->_valor)) {
            $mostrar = '<span class="texto_claro">&lt; {TR|m_sin_datos} &gt;</span>';
            $title = '';
        } elseif (strlen($this->_valor) > 100) {
            $mostrar = substr($this->_valor, 0, 100) . '...';
            if (strlen($this->_valor) > 400) {
                $title = 'title="' . substr($this->_valor, 0, 400) . '..."';
            } else {
                $title = 'title="' . $this->_valor . '"';
            }
        } else {
            $mostrar = $this->_valor;
            $title = '';
        }
        $mostrar = $this->_verLinksTexto($mostrar);
        
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

            $mostrar = nl2br($this->_codigoHtml($this->_dcp['permite_html'], Generales_Post::obtener($this->_valor)));
            $mostrar = $this->_verLinksTexto($mostrar);
            return $this->_tituloYComponente($mostrar);
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

            $this->_codigoJS = $this->_codigoJS();

            $mostrar = '<textarea name="' . $id_campo . '" id="' . $id_campo . '" rows="' . $this->_dcp['alto'] . '" style="width:90%;" control="CampoTextoLargo" ' . $obligatorio . '>' . $valor . '</textarea>' . $campo_error;
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAltaPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = "cp_" . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';
            $mostrar .= nl2br($this->_codigoHtml($this->_dcp['permite_html'], $valor));

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

            $id_campo = "cp_" . $this->_dcp['cp_id'];
            // recupero de los valores obtenidos al volver de la vista previa
            if (isset($_POST[$id_campo]) && (Generales_Post::obtener($_POST[$id_campo], 'h') != '')) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = Generales_Post::obtener($this->_valor, 'h');
            }
            $valor = $this->_codigoHtml($this->_dcp['permite_html'], $valor);

            if ($this->_dcp['obligatorio'] == 'no_nulo') {
                $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
            } else {
                $obligatorio = '';
                $campo_error = '';
            }

            $mostrar_reemplazo = $this->_codigoHtml($this->_dcp['permite_html'], $valor);

            $this->_codigoJS = $this->_codigoJS();

            $mostrar = '<textarea name="' . $id_campo . '" id="' . $id_campo . '" rows="' . $this->_dcp['alto'] . '" style="width:90%;" control="CampoTextoLargo" ' . $obligatorio . '>' . $valor . '</textarea>' . $campo_error;
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacionPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            $valor = Generales_Post::obtener($_POST[$id_campo], 'h');

            $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';
            $mostrar .= nl2br($this->_codigoHtml($this->_dcp['permite_html'], $valor));

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
                <td><input ' . $style . ' type="text" name="valor_' . $this->_dcp['cp_id'] . '" id="valor_' . $this->_dcp['cp_id'] . '" value="' . $valor . '" class="filtro_Texto" /></td>
                <td><div class="bt_tb_eliminar_filtro" filtro_eliminar_id="' . $this->_dcp['cp_id'] . '"></div></td>
            ';

            return $template;
        } else {

            return false;
        }
    }

    private function _registroFiltroCampoOpciones($condicion) {

        $descripciones[0] = '{TR|o_contiene}';
        $descripciones[1] = '{TR|o_no_contiene}';
        $descripciones[2] = '{TR|o_nulo}';
        $descripciones[3] = '{TR|o_no_nulo}';

        $valores[0] = 'coincide';
        $valores[1] = 'no_coincide';
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

        $caracteres = strlen($this->_valor);
        $palabras = (count(preg_split('/\b[\s,\.\-:;]*/', $this->_valor)) - 1) / 2;

        $tamano_actual = '[{TR|m_caracteres}: ' . $caracteres . ' | {TR|m_palabras}: ' . $palabras . ']';

        $tamano_actual_js = $this->_codigoJS;

        $plantilla['idioma'] = $this->_dcp['idioma_' . Generales_Idioma::obtener()];
        $plantilla['tamanio'] = '<br /><span class="texto_claro">[{TR|m_largo}: ' . $this->_dcp['largo'] . ' {TR|m_caracteres}]</span>';
        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            $plantilla['campo_nombre'] = '<br /><span class="texto_nombre_campos">( ' . $this->_dcp['tb_campo'] . ' ) </span><span class="texto_id_campos"> ( ' . $this->_dcp['cp_id'] . ' )</span>';
        } else {
            $plantilla['campo_nombre'] = '';
        }
        $plantilla['mostrar'] = $mostrar;
        $plantilla['tamanio_actual'] = '<br /><span class="texto_claro"><span id="cp_' . $this->_dcp['cp_id'] . '_texto" class="texto_claro">' . $tamano_actual . '</span></span>' . $tamano_actual_js;

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

    private function _codigoJS() {

        return "
            <script type=\"text/javascript\">
            $(document).ready(function() {
                    $('#cp_" . $this->_dcp['cp_id'] . "').CampoTexto({
                        'caracteres' : '{TR|m_caracteres}',
                        'palabras' : '{TR|m_palabras}',
                        'limite_maximo' : " . $this->_dcp['largo'] . ",
                        'caracteres_falta_alerta' : " . $this->_caracteres_falta_alerta . "
                    });
            });
            </script>
        ";
    }

    private function _verLinksTexto($texto) {

        if (isset($this->_dcp['link_mail']) && ($this->_dcp['link_mail'] == 's')) {
            $texto = preg_replace_callback("/[a-zA-Z0-9_\-\.]+@[a-zA-Z0-9\_\-\.]+\.[a-zA-Z0-9\_\-\.]+/", array('Componentes_TextoLargo_RegistroVer', '_verLinksTextoMail'), $texto);
        }

        if (isset($this->_dcp['link_url']) && ($this->_dcp['link_url'] == 's')) {
            $texto = preg_replace_callback("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", array('Componentes_TextoLargo_RegistroVer', '_verLinksTextoURL'), $texto);
        }

        return $texto;
    }

    static private function _verLinksTextoMail($coincidencias) {

        return '<a href="mailto:' . $coincidencias[0] . '">' . $coincidencias[0] . '</a>';
    }

    static private function _verLinksTextoURL($coincidencias) {

        return '<a target="blank" href="' . $coincidencias[0] . '">' . $coincidencias[0] . '</a>';
    }

}
