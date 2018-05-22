<?php

class Componentes_PaginaMenu_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array();         // parametros del componente
    private $_idComponente;         // id del componente
    private $_idRegistro;           // id del registro
    private $_tabla_menu;
    private $_tabla_menu_nombre_idioma;
    private static $_parametros_tabla_menu = array();

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
        $this->_tabla_menu = Consultas_Tabla::RegistroConsultaTablaNombre(__FILE__, __LINE__, $this->_dcp['id_tabla']);
        $parametros_tabla = Consultas_TablaParametros::RegistroConsultaTodos(__FILE__, __LINE__, $this->_dcp['id_tabla']);
        if (is_array($parametros_tabla)) {
            foreach ($parametros_tabla as $valor) {
                self::$_parametros_tabla_menu[$valor['parametro']] = $valor['valor'];
            }
        }
        $tabla_menu_nombre_idioma = Consultas_TablaNombreIdioma::RegistroConsulta(__FILE__, __LINE__, Generales_Idioma::obtener(), $this->_dcp['id_tabla']);
        $this->_tabla_menu_nombre_idioma = $tabla_menu_nombre_idioma[0]['tabla_nombre_idioma'];
    }

    public function get() {
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _registroValor() {
        return $this->_valor;
    }

    private function _registroListadoCabezal() {
        return '<td class="columna"><span class="titulo_tabla">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</span></td>';
    }

    private function _registroListadoCuerpo() {
        $mostrar = $this->_valor;
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden"';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'">' . $mostrar . '</td>';
    }

    private function _registroListadoPie() {
        return false;
    }

    private function _registroVer() {
        $mostrar = $this->_mostrarValor($this->_idRegistro);
        return $this->_tituloYComponente($mostrar);
    }

    private function _registroAlta() {

        // encabezado necesario para validar la accion con javascript
        Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

        $id_campo = 'cp_' . $this->_dcp['cp_id'];
        if (isset($_POST[$id_campo])) {
            $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
        } else {
            $valor = '';
        }

        if (!is_array($valor)) {
            $matriz_seleccionados = '';
        } else {
            $valores = $valor;
            $i = 1;
            if (is_array($valores)) {
                foreach ($valores as $valor) {
                    $matriz_seleccionados[$i] = Generales_Post::obtener($_POST[$id_campo][$i - 1], 'h');
                    $i++;
                }
            }
        }
        $mostrar = $this->_selectMultipleLink($matriz_seleccionados);
        return $this->_tituloYComponente($mostrar);
    }

    private function _registroAltaPrevia() {
        $mostrar = $this->_vistaPrevia();
        return $this->_tituloYComponente($mostrar);
    }

    private function _registroModificacion() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];
        if (isset($_POST[$id_campo])) {
            $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
        } else {
            $valor = '';
        }

        $valores = array();
        if (!is_array($valor)) {
            $matriz_menu = $this->_obtenerMatrizMenu($this->_tabla_menu, $this->_idRegistro);
            $i = 1;
            if (is_array($matriz_menu)) {
                foreach ($matriz_menu as $valor) {
                    $valores[$i] = $valor['id_menu'];
                    $i++;
                }
            }
        } else {
            foreach ($valor as $id => $valor_id) {
                $valores[$id + 1] = $valor_id;
            }
        }

        $mostrar = $this->_selectMultipleLink($valores);
        return $this->_tituloYComponente($mostrar);
    }

    private function _registroModificacionPrevia() {
        $mostrar = $this->_vistaPrevia();
        return $this->_tituloYComponente($mostrar);
    }

    private function _registroFiltroCampo() {

        if (isset($this->_dcp['filtrar']) && ($this->_dcp['filtrar'] == 's')) {

            if (isset($this->_valor['valor'])) {
                if (!is_array($this->_valor['valor'])) {
                    $this->_valor['valor'] = explode(';', $this->_valor['valor']);
                }
                $valor = '';
                $i = 1;
                if (is_array($this->_valor['valor'])) {
                    foreach ($this->_valor['valor'] as $valor_id) {
                        $valor[$i] = $valor_id;
                        $i++;
                    }
                }
            } else {
                $valor = '';
            }
            if (isset($this->_valor['condicion'])) {
                $condicion = $this->_valor['condicion'];
            } else {
                $condicion = '';
            }

            if (($condicion == '') || ($condicion == 'nulo') || ($condicion == 'no_nulo')) {
                $style = " <script type=\"text/javascript\"> $(document).ready(function() { $('#valor_" . $this->_dcp['cp_id'] . "' ).hide(); });</script> ";
            } else {
                $style = '';
            }

            $template = '
                <td><div class="filtros_texto">' . $this->_tabla_menu_nombre_idioma . '</div>
                <input name="parametro_' . $this->_dcp['cp_id'] . '" id="parametro_' . $this->_dcp['cp_id'] . '" type="hidden" />
                </td>
                <td>' . $this->_registroFiltroCampoOpciones($condicion) . '</td>
                <td><div id="valor_' . $this->_dcp['cp_id'] . '">' . $this->_selectMultipleLink($valor, '200', 'valor_') . '</div></td>
                <td><div class="bt_tb_eliminar_filtro" filtro_eliminar_id="' . $this->_dcp['cp_id'] . '">' . $style . '</div></td>
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

        $plantilla['mostrar'] = $mostrar;

        // identifica si tiene mascara o filtro
        if ($this->_dcp['obligatorio'] == 'nulo') {
            $plantilla['obligatorio'] = '';
            if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
                $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
            }
        } else {
            $plantilla['obligatorio'] = '<span class="VC_campo_requerido">&#8226;</span> ';
            $plantilla['ocultar'] = '';
            if (($this->_metodo != 'registro_alta_previa') && ($this->_metodo != 'registro_modificacion_previa')) {
                $plantilla['div_mensaje'] = '<div id="VC_cp_' . $this->_dcp['cp_id'] . '"></div>';
            }
        }

        $plantilla['menu_nombre_idioma'] = $this->_tabla_menu_nombre_idioma;

        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($this->_dcp['intermedia_tb_id'], 'sin_idioma');
            $intermedia_tb = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
            $plantilla['campo_nombre'] = '<br /><span class="texto_nombre_campos">( ' . $intermedia_tb . '.id_' . $this->_tabla_menu . ' ) </span><span class="texto_id_campos"> ( ' . $this->_dcp['cp_id'] . ' )</span>';
        } else {
            $plantilla['campo_nombre'] = '';
        }

        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _vistaPrevia() {
        $cp_campo = 'cp_' . $this->_dcp['cp_id'];
        $mostrar = '';
        $mostrar = $this->_mostrarValoresPost();
        if (isset($_POST[$cp_campo]) && is_array($_POST[$cp_campo])) {
            foreach ($_POST[$cp_campo] as $id => $elemento) {
                $mostrar .= '<input type="hidden" name="' . $cp_campo . '[' . $id . ']" id="' . $cp_campo . '[' . $id . ']" value="' . $elemento . '" />';
                $mostrar .= '<br>';
            }
        }
        return $mostrar;
    }

    private function _selectMultipleLink($matriz_seleccionados = null, $ancho = '500', $nombre_filtro = 'cp_') {

        $matriz_menu = $this->_obtenerMatrizMenu($this->_tabla_menu);

        if (is_array($matriz_menu)) {
            $select_link = '<select name="' . $nombre_filtro . $this->_dcp['cp_id'] . '[]" id="cp_' . $this->_dcp['cp_id'] . '" data-placeholder="{TR|o_escriba_y_seleccione_un_elemento}" style="width:' . $ancho . 'px;" multiple class="chosen-select">';
            foreach ($matriz_menu as $linea) {
                $seleccionado = '';
                if ($matriz_seleccionados) {
                    if (array_search($linea["id_menu"], $matriz_seleccionados)) {
                        $seleccionado = 'selected';
                    }
                }
                $texto = $this->_obtenerNombreMenu($linea["nivel1_orden"], $linea["nivel2_orden"], $linea["nivel3_orden"], $linea["nivel4_orden"], $linea["nivel5_orden"], $linea["nivel6_orden"], $linea["nivel7_orden"], $linea["nivel8_orden"], $linea["nivel9_orden"], $linea["nivel10_orden"]);
                $select_link .= '<option value="' . $linea['id_menu'] . '" ' . $seleccionado . '>' . $texto . '</option>' . "\n";
                $seleccionado = '';
            }
            $select_link .= '</select>' . "\n";
            // funcion asignación sin resultados
            $select_link .= '<script type="text/javascript">' . "\n";
            $select_link .= '   $("#cp_' . $this->_dcp['cp_id'] . '").chosen({' . "\n";
            $select_link .= '       no_results_text: "{TR|o_no_se_ha_encontrado_ningun_elemento}",' . "\n";
            $select_link .= '       disable_search_threshold: 2,' . "\n";
            $select_link .= '   });' . "\n";
            $select_link .= '</script>' . "\n";

            Armado_Cabeceras::chosen();

            return $select_link;
        } else {
            return '{TR|o_la_tabla_origen_no_contiene_datos_o_no_existe}';
        }
    }

    private function _mostrarValoresPost() {

        if (isset($_POST['cp_' . $this->_dcp['cp_id']])) {
            $array_post = $_POST['cp_' . $this->_dcp['cp_id']];
        } else {
            $array_post = '';
        }

        $texto = '';
        if (is_array($array_post)) {
            foreach ($array_post as $id_menu) {
                $matriz_menu = $this->_obtenerMatrizMenu($this->_tabla_menu, '', $id_menu);
                if (is_array($matriz_menu)) {
                    foreach ($matriz_menu as $linea) {
                        $texto .= $this->_obtenerNombreMenu($linea["nivel1_orden"], $linea["nivel2_orden"], $linea["nivel3_orden"], $linea["nivel4_orden"], $linea["nivel5_orden"], $linea["nivel6_orden"], $linea["nivel7_orden"], $linea["nivel8_orden"], $linea["nivel9_orden"], $linea["nivel10_orden"]) . '<br />';
                    }
                }
            }
        }

        if ($texto != '') {
            return $texto;
        } else {
            return '{TR|o_sin_valores}';
        };
    }

    private function _mostrarValor($id_registro) {

        $matriz_menu = $this->_obtenerMatrizMenu($this->_tabla_menu, $id_registro);

        $texto = '';
        if (is_array($matriz_menu)) {
            foreach ($matriz_menu as $linea) {
                $texto .= $this->_obtenerNombreMenu($linea["nivel1_orden"], $linea["nivel2_orden"], $linea["nivel3_orden"], $linea["nivel4_orden"], $linea["nivel5_orden"], $linea["nivel6_orden"], $linea["nivel7_orden"], $linea["nivel8_orden"], $linea["nivel9_orden"], $linea["nivel10_orden"]) . '<br />';
            }
        } else {
            $texto = '{TR|o_sin_valores}';
        }
        return $texto;
    }

    private function _obtenerMatrizMenu($tabla_menu, $id_registro = '', $id_menu = '') {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($tabla_menu);
        $consulta->tablas($tabla_menu . '_trd');
        $consulta->campos($tabla_menu, 'id_' . $tabla_menu, 'id_menu');
        $consulta->campos($tabla_menu, 'nivel1_orden');
        $consulta->campos($tabla_menu, 'nivel2_orden');
        $consulta->campos($tabla_menu, 'nivel3_orden');
        $consulta->campos($tabla_menu, 'nivel4_orden');
        $consulta->campos($tabla_menu, 'nivel5_orden');
        $consulta->campos($tabla_menu, 'nivel6_orden');
        $consulta->campos($tabla_menu, 'nivel7_orden');
        $consulta->campos($tabla_menu, 'nivel8_orden');
        $consulta->campos($tabla_menu, 'nivel9_orden');
        $consulta->campos($tabla_menu, 'nivel10_orden');
        $consulta->campos($tabla_menu . '_trd', 'menu_nombre');
        $consulta->campos($tabla_menu . '_rel', 'id_' . $tabla_menu . '_rel', 'cantidad', 'contador');
        $consulta->condiciones('', $tabla_menu, 'id_' . $tabla_menu, 'iguales', $tabla_menu . '_trd', 'id_' . $tabla_menu);
        $consulta->condiciones('y', $tabla_menu . '_trd', 'idioma', 'iguales', '', '', Generales_Idioma::obtener());
        if ($id_registro != '') {
            $consulta->condiciones('y', $tabla_menu . '_rel', 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $id_registro);
        }
        if ($id_menu != '') {
            $consulta->condiciones('y', $tabla_menu . '_rel', 'id_' . $tabla_menu, 'iguales', '', '', $id_menu);
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 10) {
            $consulta->condiciones('y', $tabla_menu, 'nivel10_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 9) {
            $consulta->condiciones('y', $tabla_menu, 'nivel9_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 8) {
            $consulta->condiciones('y', $tabla_menu, 'nivel8_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 7) {
            $consulta->condiciones('y', $tabla_menu, 'nivel7_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 6) {
            $consulta->condiciones('y', $tabla_menu, 'nivel6_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 5) {
            $consulta->condiciones('y', $tabla_menu, 'nivel5_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 4) {
            $consulta->condiciones('y', $tabla_menu, 'nivel4_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 3) {
            $consulta->condiciones('y', $tabla_menu, 'nivel3_orden', 'nulo');
        }
        if (self::$_parametros_tabla_menu['numero_niveles'] < 2) {
            $consulta->condiciones('y', $tabla_menu, 'nivel2_orden', 'nulo');
        }
        $consulta->unionIzquierdaTablas('relacion', $tabla_menu, '', $tabla_menu . '_rel');
        $consulta->unionIzquierdaCondiciones('relacion', '', $tabla_menu, 'id_' . $tabla_menu, 'iguales', $tabla_menu . '_rel', 'id_' . $tabla_menu);
        $consulta->grupo($tabla_menu, 'id_' . $tabla_menu);
        $consulta->orden($tabla_menu, 'nivel1_orden');
        $consulta->orden($tabla_menu, 'nivel2_orden');
        $consulta->orden($tabla_menu, 'nivel3_orden');
        $consulta->orden($tabla_menu, 'nivel4_orden');
        $consulta->orden($tabla_menu, 'nivel5_orden');
        $consulta->orden($tabla_menu, 'nivel6_orden');
        $consulta->orden($tabla_menu, 'nivel7_orden');
        $consulta->orden($tabla_menu, 'nivel8_orden');
        $consulta->orden($tabla_menu, 'nivel9_orden');
        $consulta->orden($tabla_menu, 'nivel10_orden');

        return $consulta->realizarConsulta();
    }

    private function _obtenerNombreMenu($nivel1_orden, $nivel2_orden, $nivel3_orden, $nivel4_orden, $nivel5_orden, $nivel6_orden, $nivel7_orden, $nivel8_orden, $nivel9_orden, $nivel10_orden) {

        $texto = '';
        $matriz_menu = $this->_obtenerMatrizMenu($this->_tabla_menu);

        foreach ($matriz_menu as $linea) {

            $nivel_habilitados = 'nivel'.self::$_parametros_tabla_menu['niveles_habilitados'].'_orden';
            if ($$nivel_habilitados == '') {
                return false;
            }

            if (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($nivel1_orden != '') &&
                    ($linea['nivel2_orden'] == '')
            ) {
                $texto .= $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($nivel2_orden != '') &&
                    ($linea['nivel3_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($nivel3_orden != '') &&
                    ($linea['nivel4_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($nivel4_orden != '') &&
                    ($linea['nivel5_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($nivel5_orden != '') &&
                    ($linea['nivel6_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($linea['nivel6_orden'] == $nivel6_orden) &&
                    ($nivel6_orden != '') &&
                    ($linea['nivel7_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($linea['nivel6_orden'] == $nivel6_orden) &&
                    ($linea['nivel7_orden'] == $nivel7_orden) &&
                    ($nivel7_orden != '') &&
                    ($linea['nivel8_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($linea['nivel6_orden'] == $nivel6_orden) &&
                    ($linea['nivel7_orden'] == $nivel7_orden) &&
                    ($linea['nivel8_orden'] == $nivel8_orden) &&
                    ($nivel8_orden != '') &&
                    ($linea['nivel9_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($linea['nivel6_orden'] == $nivel6_orden) &&
                    ($linea['nivel7_orden'] == $nivel7_orden) &&
                    ($linea['nivel8_orden'] == $nivel8_orden) &&
                    ($linea['nivel9_orden'] == $nivel9_orden) &&
                    ($nivel9_orden != '') &&
                    ($linea['nivel10_orden'] == '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            } elseif (
                    ($linea['nivel1_orden'] == $nivel1_orden) &&
                    ($linea['nivel2_orden'] == $nivel2_orden) &&
                    ($linea['nivel3_orden'] == $nivel3_orden) &&
                    ($linea['nivel4_orden'] == $nivel4_orden) &&
                    ($linea['nivel5_orden'] == $nivel5_orden) &&
                    ($linea['nivel6_orden'] == $nivel6_orden) &&
                    ($linea['nivel7_orden'] == $nivel7_orden) &&
                    ($linea['nivel8_orden'] == $nivel8_orden) &&
                    ($linea['nivel9_orden'] == $nivel9_orden) &&
                    ($linea['nivel10_orden'] == $nivel10_orden) &&
                    ($nivel10_orden != '')
            ) {
                $texto .= ' &gt; ' . $linea['menu_nombre'];
            }
        }
        
        return $texto;
    }

}
