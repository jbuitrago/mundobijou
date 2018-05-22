<?php

class Componentes_OpcionMultipleCheck_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array();         // parametros del componente
    private $dcp_origen = array(); // datos del componente que contiene los datos de origen
    private $_idComponente;         // id del componente
    private $_idRegistro;           // id del registro

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

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {

            $valores = $this->_mostrarValor('valores');
            $mostrar = '';
            if (is_array($valores)) {
                foreach ($valores as $valor) {

                    $texto = $valor[$this->_dcp['origen_tb_campo']];

                    if (isset($this->_dcp['nombre_del_campo_1']) && isset($valor[$this->_dcp['nombre_del_campo_1']]) && isset($valor[$this->_dcp['nombre_del_campo_1']]) && ($valor[$this->_dcp['nombre_del_campo_1']] != '')) {
                        $texto .= $this->_dcp['separador_del_campo_1'];
                        $texto .= $valor[$this->_dcp['nombre_del_campo_1']];
                    }

                    if (isset($this->_dcp['nombre_del_campo_2']) && isset($valor[$this->_dcp['nombre_del_campo_2']]) && isset($valor[$this->_dcp['nombre_del_campo_2']]) && ($valor[$this->_dcp['nombre_del_campo_2']] != '')) {
                        $texto .= $this->_dcp['separador_del_campo_2'];
                        $texto .= $valor[$this->_dcp['nombre_del_campo_2']];
                    }

                    $mostrar .= $texto . '<br>';
                }
            } else {
                $mostrar = '{TR|o_sin_valores}';
            }
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAlta() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            if (isset($_POST['cp_' . $this->_dcp['cp_id']]) && is_array($_POST['cp_' . $this->_dcp['cp_id']])) {
                $valores = $_POST['cp_' . $this->_dcp['cp_id']];
                $i = 1;
                if (is_array($valores)) {
                    foreach ($valores as $valor) {
                        $matriz_seleccionados[$i] = $_POST['cp_' . $this->_dcp['cp_id']][$i - 1];
                        $i++;
                    }
                }
            } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST['cp_' . $this->_dcp['cp_id']]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                $valores = explode(',', $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)]);
                $i = 1;
                if (is_array($valores)) {
                    foreach ($valores as $valor) {
                        $matriz_seleccionados[$i] = $valor;
                        $i++;
                    }
                }
            }

            if (isset($matriz_seleccionados) && is_array($matriz_seleccionados)) {
                $valores = implode(",", $matriz_seleccionados);
            } else {
                $valores = '';
            }

            $mostrar = '            <script type="text/javascript">
               $(document).ready(function(){
                $( "#mostrar_' . $this->_dcp['cp_id'] . '" ).load( "index.php?kk_generar=3&componente=OpcionMultipleCheck&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '&valor=' . $valores . '" );
               });
            </script>' . "\n";

            Generales_PopElementos::agregar_pop_elemento('
                var valores = "";
                var checkbox_coma = "";
                $(\'input[name="cp_' . $this->_dcp['cp_id'] . '[]"]:checked\').each(function() {
                        valores += checkbox_coma + ($(this).val());
                        checkbox_coma = ",";
                });
                $( "#mostrar_' . $this->_dcp['cp_id'] . '" ).load( "index.php?kk_generar=3&componente=OpcionMultipleCheck&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '&valor=" + valores );
            ');

            return $this->_tituloYComponente($mostrar, Generales_PopElementos::control_muestra($this->_dcp['origen_tb_id'], $this->_dcp['agregar_registro']));
        } else {

            return '';
        }
    }

    private function _registroAltaPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $mostrar = $this->_vistaPrevia();
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $matriz_seleccionados = '';
            if (isset($_POST['cp_' . $this->_dcp['cp_id']]) && is_array($_POST['cp_' . $this->_dcp['cp_id']])) {
                $valores = $_POST['cp_' . $this->_dcp['cp_id']];
                $i = 1;
                if (is_array($valores)) {
                    foreach ($valores as $valor) {
                        $matriz_seleccionados[$i] = $_POST['cp_' . $this->_dcp['cp_id']][$i - 1];
                        $i++;
                    }
                }
            } else {
                $valores = $this->_mostrarValor('id');
                $i = 1;
                if (is_array($valores)) {
                    foreach ($valores as $valor) {
                        $matriz_seleccionados[$i] = $valor['id_' . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre']];
                        $i++;
                    }
                }
            }

            if (isset($matriz_seleccionados) && is_array($matriz_seleccionados)) {
                $valores = implode(",", $matriz_seleccionados);
            } else {
                $valores = '';
            }

            $mostrar = '            <script type="text/javascript">
               $(document).ready(function(){
                $( "#mostrar_' . $this->_dcp['cp_id'] . '" ).load( "index.php?kk_generar=3&componente=OpcionMultipleCheck&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '&valor=' . $valores . '" );
               });
            </script>' . "\n";

            Generales_PopElementos::agregar_pop_elemento('
                var valores = "";
                var checkbox_coma = "";
                $(\'input[name="cp_' . $this->_dcp['cp_id'] . '[]"]:checked\').each(function() {
                        valores += checkbox_coma + ($(this).val());
                        checkbox_coma = ",";
                });
                $( "#mostrar_' . $this->_dcp['cp_id'] . '" ).load( "index.php?kk_generar=3&componente=OpcionMultipleCheck&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '&valor=" + valores );
            ');

            return $this->_tituloYComponente($mostrar, Generales_PopElementos::control_muestra($this->_dcp['origen_tb_id'], $this->_dcp['agregar_registro']));
        } else {

            return '';
        }
    }

    private function _registroModificacionPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $mostrar = $this->_vistaPrevia();
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroFiltroCampo() {

        if (isset($this->_dcp['filtrar']) && ($this->_dcp['filtrar'] == 's')) {

            if ($this->_dcp['filtrar_texto'] == 'n') {

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
            } else {
                if (isset($this->_valor['valor'])) {
                    $valor = $this->_valor['valor'];
                } else {
                    $valor = '';
                }
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
                <td>';
            if ($this->_dcp['filtrar_texto'] == 'n') {
                $template .= '<div id="valor_' . $this->_dcp['cp_id'] . '" ' . $style . '>' . $this->_selectMultipleLink($valor, 'valor_') . '</div>';
            } else {
                if (($condicion == '') || ($condicion == 'nulo') || ($condicion == 'no_nulo')) {
                    $style = ' style="display:none"';
                } else {
                    $style = '';
                }
                $template .= '<input ' . $style . ' type="text" name="valor_' . $this->_dcp['cp_id'] . '" id="valor_' . $this->_dcp['cp_id'] . '" value="' . $valor . '" class="filtro_Texto" />';
            }
            $template .= '</td>
                <td><div class="bt_tb_eliminar_filtro" filtro_eliminar_id="' . $this->_dcp['cp_id'] . '"></div></td>
            ';
            return $template;
        } else {

            return false;
        }
    }

    private function _registroFiltroCampoOpciones($condicion) {

        if ($this->_dcp['filtrar_texto'] == 'n') {

            $descripciones[0] = '{TR|o_igual_a}';
            $descripciones[1] = '{TR|o_distinto_a}';
            $descripciones[2] = '{TR|o_nulo}';
            $descripciones[3] = '{TR|o_no_nulo}';

            $valores[0] = 'iguales';
            $valores[1] = 'distintos';
            $valores[2] = 'nulo';
            $valores[3] = 'no_nulo';
        } else {

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
        }
        return Armado_SelectFiltros::armado($this->_dcp['cp_id'], $valores, $descripciones, $condicion);
    }

// metodos especiales
    private function _tituloYComponente($mostrar, $link = '') {

        $plantilla['mostrar'] = $mostrar;

        if ($this->_dcp['obligatorio'] == 'nulo') {
            $plantilla['obligatorio'] = '';
            if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
                $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
            }
        } else {
            $plantilla['obligatorio'] = '<span class="VC_campo_requerido">&#8226;</span> ';
            $plantilla['ocultar'] = '';
            $plantilla['div_mensaje'] = '<div id="VC_cp_' . $this->_dcp['cp_id'] . '"></div>';
        }
        $plantilla['idioma_generales_idioma'] = $this->_dcp['idioma_' . Generales_Idioma::obtener()];

        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($this->_dcp['intermedia_tb_id'], 'sin_idioma');
            $intermedia_tb = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
            $plantilla['campo_nombre'] = '<br /><span class="texto_nombre_campos">( ' . $intermedia_tb . '.id_' . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'] . ' ) </span><span class="texto_id_campos"> ( ' . $this->_dcp['cp_id'] . ' )</span>';
        } else {
            $plantilla['campo_nombre'] = '';
        }

        if ($link == '') {
            $plantilla['link_agregar_registro'] = '';
        } elseif (($link == 'si')) {
            Generales_PopElementos::agregar_pop_elemento(' ');
            $plantilla['link_agregar_registro'] = Generales_PopElementos::armar_link($this->_dcp['origen_tb_id']);
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

    private function _selectMultipleLink($matriz_seleccionados = null, $nombre_filtro = 'cp_') {

        $tb_nombre = $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'];
        $tb_campo = $this->_dcp['origen_tb_campo'];

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($tb_nombre);
        $consulta->campos($tb_nombre, 'orden');
        $consulta->campos($tb_nombre, 'id_' . $tb_nombre);
        $consulta->campos($tb_nombre, $tb_campo);

        if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
            $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_1']);
        }
        if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
            $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_2']);
        }

        $consulta->orden($tb_nombre, 'orden');
        $matriz_links = $consulta->realizarConsulta();

        $cont = 0;
        if (is_array($matriz_links)) {
            $select_link = '';

            if (isset($this->_dcp['separador_del_campo_1']) && ($this->_dcp['separador_del_campo_1'] != '')) {
                $separador_del_campo_1 = $this->_dcp['separador_del_campo_1'];
            } else {
                $separador_del_campo_1 = '';
            }

            if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
                $nombre_del_campo_1 = true;
            } else {
                $nombre_del_campo_1 = false;
            }

            if (isset($this->_dcp['separador_del_campo_2']) && ($this->_dcp['separador_del_campo_2'] != '')) {
                $separador_del_campo_2 = $this->_dcp['separador_del_campo_2'];
            } else {
                $separador_del_campo_2 = '';
            }

            if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
                $nombre_del_campo_2 = true;
            } else {
                $nombre_del_campo_2 = false;
            }

            foreach ($matriz_links as $linea) {
                $seleccionado = '';
                if ($matriz_seleccionados) {
                    if (array_search($linea["id_" . $tb_nombre], $matriz_seleccionados)) {
                        $seleccionado = "checked=\"checked\"";
                    }
                }

                $texto = $linea[$tb_campo];

                if ($nombre_del_campo_1 && isset($linea[$this->_dcp['nombre_del_campo_1']]) && ($linea[$this->_dcp['nombre_del_campo_1']] != '')) {
                    $texto .= $separador_del_campo_1;
                    $texto .= $linea[$this->_dcp['nombre_del_campo_1']];
                }

                if ($nombre_del_campo_2 && isset($linea[$this->_dcp['nombre_del_campo_2']]) && ($linea[$this->_dcp['nombre_del_campo_2']] != '')) {
                    $texto .= $separador_del_campo_2;
                    $texto .= $linea[$this->_dcp['nombre_del_campo_2']];
                }

                $select_link .= '<input name="' . $nombre_filtro . $this->_dcp['cp_id'] . '[]" id="cp_' . $this->_dcp['cp_id'] . '[' . $cont . ']" type="checkbox" value="' . $linea["id_" . $tb_nombre] . '" ' . $seleccionado . ' />' . $texto . '<br />';
                $seleccionado = '';
                $cont++;
            }
            return $select_link;
        } else {
            return '{TR|o_la_tabla_origen_no_contiene_datos_o_no_existe}';
        }
    }

    private function _mostrarValoresPost() {

        $tb_nombre = $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'];

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($tb_nombre);
        $consulta->campos($tb_nombre, 'id_' . $tb_nombre);
        $consulta->campos($tb_nombre, $this->_dcp['origen_tb_campo']);

        if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
            $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_1']);
        }
        if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
            $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_2']);
        }

        $valor_origen = $consulta->realizarConsulta();

        if (isset($_POST['cp_' . $this->_dcp['cp_id']])) {
            $array_post = $_POST['cp_' . $this->_dcp['cp_id']];
        } else {
            $array_post = '';
        }

        $valor_campo = '';

        if (is_array($valor_origen) && is_array($array_post)) {

            if (isset($this->_dcp['separador_del_campo_1']) && ($this->_dcp['separador_del_campo_1'] != '')) {
                $separador_del_campo_1 = $this->_dcp['separador_del_campo_1'];
            } else {
                $separador_del_campo_1 = '';
            }

            if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
                $nombre_del_campo_1 = true;
            } else {
                $nombre_del_campo_1 = false;
            }

            if (isset($this->_dcp['separador_del_campo_2']) && ($this->_dcp['separador_del_campo_2'] != '')) {
                $separador_del_campo_2 = $this->_dcp['separador_del_campo_2'];
            } else {
                $separador_del_campo_2 = '';
            }

            if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
                $nombre_del_campo_2 = true;
            } else {
                $nombre_del_campo_2 = false;
            }

            foreach ($valor_origen as $key => $valor) {

                $valor_compracion = $valor_origen[$key]['id_' . $tb_nombre];

                if (array_search($valor_compracion, $array_post) !== false) {

                    $texto = $valor_origen[$key][$this->_dcp['origen_tb_campo']];

                    if ($nombre_del_campo_1 && isset($valor_origen[$key][$this->_dcp['nombre_del_campo_1']]) && ($valor_origen[$key][$this->_dcp['nombre_del_campo_1']] != '')) {
                        $texto .= $separador_del_campo_1;
                        $texto .= $valor_origen[$key][$this->_dcp['nombre_del_campo_1']];
                    }

                    if ($nombre_del_campo_2 && isset($valor_origen[$key][$this->_dcp['nombre_del_campo_2']]) && ($valor_origen[$key][$this->_dcp['nombre_del_campo_2']] != '')) {
                        $texto .= $separador_del_campo_2;
                        $texto .= $valor_origen[$key][$this->_dcp['nombre_del_campo_2']];
                    }

                    $valor_campo .= $texto . '<br>';
                }
            }
        } else {
            $valor_campo = '{TR|o_sin_valores}';
        }

        return $valor_campo;
    }

    private function _mostrarValor($elemento) {

        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($this->_dcp['intermedia_tb_id'], 'sin_idioma');
        $tb_nombre = $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'];
        $intermedia_tb = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre']);
        $consulta->tablas($intermedia_tb);
        $consulta->tablas($tb_nombre);

        if ($elemento == 'id') {
            $consulta->campos($tb_nombre, "id_" . $tb_nombre);
        } elseif ($elemento == 'valores') {
            $consulta->campos($tb_nombre, $this->_dcp['origen_tb_campo']);
        }

        if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
            $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_1']);
        }
        if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
            $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_2']);
        }

        $consulta->condiciones('', $intermedia_tb, 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre']);
        $consulta->condiciones('y', $intermedia_tb, 'id_' . $tb_nombre, 'iguales', $tb_nombre, 'id_' . $tb_nombre);
        $consulta->condiciones('y', $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'id_' . $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'], 'iguales', '', '', $this->_idRegistro);

        $valor_origen = $consulta->realizarConsulta();

        return $valor_origen;
    }

    private function _consultaAjax() {

        if ($this->_valor != '') {
            $matriz_seleccionados = explode(',', $this->_valor);
            array_unshift($matriz_seleccionados, '');
        } else {
            $matriz_seleccionados = '';
        }

        echo $this->_selectMultipleLink($matriz_seleccionados);
    }

}
