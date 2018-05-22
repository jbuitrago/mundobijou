<?php

class Componentes_OpcionMultiple_RegistroVer extends Armado_Plantilla {

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

            if (is_array($valores) && Generales_ControlProcesosEspeciales::existeProcesoEspecial($this->_dcp['cp_id'])) {

                $tb_nombre = $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'];
                $tb_campo = $this->_dcp['origen_tb_campo'];

                $valores = $this->_mostrarValor('id');
                foreach ($valores as $id) {
                    $valores_id[] = $id['id_' . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre']];
                }

                if (Generales_ControlProcesosEspeciales::control('matriz_links', $this->_dcp['cp_id'], $valores_id, $tb_nombre, $tb_campo)) {

                    $matriz_links = Generales_ControlProcesosEspeciales::matriz_links($this->_dcp['cp_id'], $valores_id);
                    if (is_array($matriz_links)) {

                        $matriz_seleccionados = explode(',', $this->_valor);
                        foreach ($matriz_links as $linea) {
                            $seleccionado = '';
                            array_unshift($matriz_seleccionados, '');
                            if (isset($linea["id_" . $tb_nombre]) && array_search($linea["id_" . $tb_nombre], $matriz_seleccionados)) {
                                $seleccionado = 'selected';
                            }
                            $mostrar .= '<a href="' . $linea["id_" . $tb_nombre] . '" target="_blank" title="{TR|o_registros_relacionados}">' . $linea[$tb_campo] . '</a><br>';
                        }
                    }
                }
            } elseif (is_array($valores)) {

                foreach ($valores as $valor) {

                    if ($this->_dcp['link_a_grupo'] == 'n') {

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
                    } else {
                        $link_armado['kk_generar'] = '0';
                        $link_armado['id_tabla'] = $this->_dcp['origen_tb_id'];
                        $link_armado['id_tabla_registro'] = $valor['id_' . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre']];
                        $link_armado['accion'] = '45';
                        $link = './index.php?' . Generales_VariablesGet::armar($link_armado, 's');

                        $texto = $valor[$this->_dcp['origen_tb_campo']];

                        if (isset($this->_dcp['nombre_del_campo_1']) && isset($valor[$this->_dcp['nombre_del_campo_1']]) && isset($valor[$this->_dcp['nombre_del_campo_1']]) && ($valor[$this->_dcp['nombre_del_campo_1']] != '')) {
                            $texto .= $this->_dcp['separador_del_campo_1'];
                            $texto .= $valor[$this->_dcp['nombre_del_campo_1']];
                        }

                        if (isset($this->_dcp['nombre_del_campo_2']) && isset($valor[$this->_dcp['nombre_del_campo_2']]) && isset($valor[$this->_dcp['nombre_del_campo_2']]) && ($valor[$this->_dcp['nombre_del_campo_2']] != '')) {
                            $texto .= $this->_dcp['separador_del_campo_2'];
                            $texto .= $valor[$this->_dcp['nombre_del_campo_2']];
                        }

                        $mostrar .= '<a href="' . $link . '" target="_blank" title="{TR|o_registros_relacionados}">' . $texto . '</a><br>';
                    }
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
            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);
            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST[$id_campo]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                $valor = explode(',', $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)]);
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
                        if ($this->_dcp['predefinir_ultimo_val_cargado'] != 's') {
                            $matriz_seleccionados[$i] = Generales_Post::obtener($_POST[$id_campo][$i - 1], 'h');
                        } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST[$id_campo]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                            $matriz_seleccionados[$i] = $valor;
                        }
                        $i++;
                    }
                }
            }
            $mostrar = $this->_selectMultipleLink($matriz_seleccionados);

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

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            $matriz_seleccionados = '';
            if (!is_array($valor)) {
                $valores = $this->_mostrarValor('id');
                $i = 1;
                if (is_array($valores)) {
                    foreach ($valores as $valor) {
                        $matriz_seleccionados[$i] = $valor['id_' . $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre']];
                        $i++;
                    }
                }
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

            $template = '
                <td><div class="filtros_texto">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</div>
                <input name="parametro_' . $this->_dcp['cp_id'] . '" id="parametro_' . $this->_dcp['cp_id'] . '" type="hidden" />
                </td>
                <td>' . $this->_registroFiltroCampoOpciones($condicion) . '</td>
                <td>';
            if ($this->_dcp['filtrar_texto'] == 'n') {
                if (($condicion == '') || ($condicion == 'nulo') || ($condicion == 'no_nulo')) {
                    $template .= " <script type=\"text/javascript\"> $(document).ready(function() { $('#valor_" . $this->_dcp['cp_id'] . "' ).hide(); });</script> ";
                }
                $template .= '<div id="valor_' . $this->_dcp['cp_id'] . '">' . $this->_selectMultipleLink($valor, '200', 'valor_', true) . '</div>';
            } else {
                if (($condicion == '') || ($condicion == 'nulo') || ($condicion == 'no_nulo')) {
                    $style = ' style="display:none"';
                } else {
                    $style = '';
                }
                $campo_insert = '';
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

    private function _selectMultipleLink($matriz_seleccionados = null, $ancho = '90%', $nombre_filtro = 'cp_', $es_filtro = false) {

        $tb_nombre = $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'];
        $tb_campo = $this->_dcp['origen_tb_campo'];

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($tb_nombre);
        $consulta->campos($tb_nombre, 'orden');
        $consulta->campos($tb_nombre, 'id_' . $tb_nombre);
        $consulta->campos($tb_nombre, $tb_campo);
        $consulta->orden($tb_nombre, 'orden');
        $matriz_links = $consulta->realizarConsulta();

        if (is_array($matriz_links)) {

            if ($es_filtro === true) {

                $select_link = '<select name="' . $nombre_filtro . $this->_dcp['cp_id'] . '[]" id="cp_' . $this->_dcp['cp_id'] . '" data-placeholder="{TR|o_escriba_y_seleccione_un_elemento}" style="width:' . $ancho . 'px;" multiple class="chosen-select">';
                foreach ($matriz_links as $linea) {
                    $seleccionado = '';
                    if ($matriz_seleccionados) {
                        if (array_search($linea["id_" . $tb_nombre], $matriz_seleccionados)) {
                            $seleccionado = 'selected';
                        }
                    }
                    $select_link .= '<option value="' . $linea["id_" . $tb_nombre] . '" ' . $seleccionado . '>' . $linea[$tb_campo] . '</option>' . "\n";
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
            } else {

                $select_link = '<select name="' . $nombre_filtro . $this->_dcp['cp_id'] . '[]" id="cp_' . $this->_dcp['cp_id'] . '" data-placeholder="{TR|o_escriba_y_seleccione_un_elemento}" style="width:' . $ancho . ';" multiple class="chosen-select"></select>' . "\n";

                if (is_array($matriz_seleccionados)) {
                    $valores = implode(',', $matriz_seleccionados);
                } else {
                    $valores = '';
                }

                Generales_PopElementos::agregar_pop_elemento('
                valor = $("#cp_' . $this->_dcp['cp_id'] . '").val();
                $.ajax({
                 url: "index.php?kk_generar=3&componente=OpcionMultiple&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '&valor="+valor,
                 cache: false
                })
                 .done(function( html ) {
                   $("#cp_' . $this->_dcp['cp_id'] . '").html( html )
                   $("#cp_' . $this->_dcp['cp_id'] . '").trigger("chosen:updated");
                   $("#cp_' . $this->_dcp['cp_id'] . '").chosen({
                       disable_search_threshold: 2,
                   });
                })
            ');

                // funcion asignación sin resultados
                $select_link .= '            <script type="text/javascript">
               $(document).ready(function(){
                $.ajax({
                 url: "index.php?kk_generar=3&componente=OpcionMultiple&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '&valor=' . $valores . '",
                 cache: false
                })
                 .done(function( html ) {
                   $("#cp_' . $this->_dcp['cp_id'] . '").html( html )
                   $("#cp_' . $this->_dcp['cp_id'] . '").chosen({
                       no_results_text: "{TR|o_no_se_ha_encontrado_ningun_elemento}",
                       disable_search_threshold: 2,
                   });
                })
               });
            </script>' . "\n";
            }

            Armado_Cabeceras::chosen();

            return $select_link;
        } else {
            return '{TR|o_la_tabla_origen_no_contiene_datos_o_no_existe}';
        }
    }

    private function _mostrarValoresPost() {

        $tb_nombre = $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'];
        $tb_campo = $this->_dcp['origen_tb_campo'];

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($tb_nombre);
        $consulta->campos($tb_nombre, 'id_' . $tb_nombre);
        $consulta->campos($tb_nombre, $tb_campo);

        if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
            $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_1']);
        }
        if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
            $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_2']);
        }

        $valor_origen = $consulta->realizarConsulta();

        if (Generales_ControlProcesosEspeciales::control('matriz_links', $this->_dcp['cp_id'], $this->_valor, $tb_nombre, $tb_campo)) {
            $valor_origen = Generales_ControlProcesosEspeciales::matriz_links($this->_dcp['cp_id'], $this->_valor);
        }

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

                    $texto = $valor_origen[$key][$tb_campo];

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
            if ($this->_dcp['link_a_grupo'] == 's') {
                $consulta->campos($tb_nombre, "id_" . $tb_nombre);
            }
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

        $tb_nombre = $this->_dcp['origen_tb_prefijo'] . '_' . $this->_dcp['origen_tb_nombre'];
        $tb_campo = $this->_dcp['origen_tb_campo'];

        if (Generales_ControlProcesosEspeciales::control('matriz_links', $this->_dcp['cp_id'], $this->_valor, $tb_nombre, $tb_campo)) {

            $matriz_links = Generales_ControlProcesosEspeciales::matriz_links($this->_dcp['cp_id'], $this->_valor);

            if (is_array($matriz_links)) {

                $matriz_seleccionados = explode(',', $this->_valor);

                foreach ($matriz_links as $linea) {

                    $seleccionado = '';
                    if ($matriz_seleccionados) {
                        array_unshift($matriz_seleccionados, '');
                        if (array_search($linea["id_" . $tb_nombre], $matriz_seleccionados)) {
                            $seleccionado = 'selected';
                        }
                    }
                    echo '<option value="' . $linea["id_" . $tb_nombre] . '" ' . $seleccionado . '>' . $linea[$tb_campo] . '</option>' . "\n";
                }
            }
        } else {

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($tb_nombre);
            $consulta->campos($tb_nombre, 'orden');
            $consulta->campos($tb_nombre, 'id_' . $tb_nombre);
            $consulta->campos($tb_nombre, $tb_campo);

            if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
                $consulta->campos($this->tb_nombre, $this->_dcp['nombre_del_campo_1']);
            }
            if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
                $consulta->campos($this->tb_nombre, $this->_dcp['nombre_del_campo_2']);
            }

            $consulta->orden($tb_nombre, 'orden');
            $matriz_links = $consulta->realizarConsulta();

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

            if (is_array($matriz_links)) {

                $matriz_seleccionados = explode(',', $this->_valor);

                foreach ($matriz_links as $linea) {
                    $seleccionado = '';
                    if ($matriz_seleccionados) {
                        array_unshift($matriz_seleccionados, '');
                        if (array_search($linea["id_" . $tb_nombre], $matriz_seleccionados)) {
                            $seleccionado = 'selected';
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

                    echo '<option value="' . $linea["id_" . $tb_nombre] . '" ' . $seleccionado . '>' . $texto . '</option>' . "\n";
                }
            }
        }
    }

}
