<?php

class Componentes_Desplegable_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array(); // parametros del componente
    private $dcp_origen = array(); // datos del componente que contiene los datos de origen
    private $_idComponente;         // id del componente
    private $_idRegistro;           // id del registro
    private $_tb_nombre;
    private $_tb_campo;
    private $_matriz_links;

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    public function set($datos) {
        
        //print_r($datos);
        
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

        $id = $this->_valor['id'];
        $texto = $this->_valor['valor'];

        if (!isset($this->_dcp['link_a_grupo'])) {
            $this->_dcp['link_a_grupo'] = 'n';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }

        $valor = $this->_mostrarValor($id, $this->_dcp['link_a_grupo'], $texto);
        return '<td class="columna'.$ocultar_celulares.'">' . $valor . '</td>';
    }

    private function _registroListadoPie() {
        if (!isset($this->_dcp['ocultar_celulares'])) {
            return '<td class="columna kk_resp_hidden">&nbsp;</td>';
        }
        return false;
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {

            if (!isset($this->_dcp['link_a_grupo'])) {
                $this->_dcp['link_a_grupo'] = 'n';
            }

            $mostrar = $this->_mostrarValor($this->_valor, $this->_dcp['link_a_grupo']);
            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroAlta() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

// encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);
// encabezados para el autocomplete
            if ($this->_dcp['autofiltro'] == 's') {
                Armado_Cabeceras::autocomplete();
            }

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            if ($valor != '') {
                $this->_valor = $valor;
            } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST[$id_campo]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                $this->_valor = $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)];
            } else {
                $this->_valor = '';
            }

            if ($this->_dcp['autofiltro'] == 's') {
                $mostrar = $this->_despelgableLinkAutocomplete();
            } else {
                $mostrar = $this->_despelgableLink($this->_valor);
            }

            return $this->_tituloYComponente($mostrar, Generales_PopElementos::control_muestra($this->_dcp['origen_tb_id'], $this->_dcp['agregar_registro']));
        } else {

            return '';
        }
    }

    private function _registroAltaPrevia() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];
        if (isset($_POST[$id_campo])) {
            $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
        } else {
            $valor = '';
        }

        $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';
        $mostrar .= $this->_mostrarValorPrevia($valor) . '<br>';
        return $this->_tituloYComponente($mostrar);
    }

    private function _registroModificacion() {
        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

// encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);
// encabezados para el autocomplete
            if (isset($this->_dcp['autofiltro']) && ($this->_dcp['autofiltro'] == 's')) {
                Armado_Cabeceras::autocomplete();
            }

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            if ($valor != '') {
                $this->_valor = $valor;
            }

            if (isset($this->_dcp['autofiltro']) && ($this->_dcp['autofiltro'] == 's')) {
                $mostrar = $this->_despelgableLinkAutocomplete();
            } else {
                $mostrar = $this->_despelgableLink($this->_valor);
            }

            return $this->_tituloYComponente($mostrar, Generales_PopElementos::control_muestra($this->_dcp['origen_tb_id'], $this->_dcp['agregar_registro']));
        } else {

            return '';
        }
    }

    private function _registroModificacionPrevia() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];
        if (isset($_POST[$id_campo])) {
            $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
        } else {
            $valor = '';
        }

        $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';
        $mostrar .= $this->_mostrarValorPrevia($valor) . '<br>';
        return $this->_tituloYComponente($mostrar);
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
                <td>' . $this->_despelgableLink($valor, 'valor_' . $this->_dcp['cp_id'], $style) . '</td>
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
    private function _tituloYComponente($mostrar, $link = '') {

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

        if ($link == '') {
            $plantilla['link_agregar_registro'] = '';
        } elseif (($link == 'si')) {
            if ($this->_dcp['autofiltro'] == 'n') {

                if (isset($_GET['accion'])) {
                    $get_accion = '&accion=' . $_GET['accion'];
                } else {
                    $get_accion = '';
                }

                if (isset($_GET['id_tabla_registro'])) {
                    $get_id_tabla_registro = '&id_tabla_registro=' . $_GET['id_tabla_registro'];
                } else {
                    $get_id_tabla_registro = '';
                }

                Generales_PopElementos::agregar_pop_elemento('$( "#cp_' . $this->_dcp['cp_id'] . '" ).load( "index.php?kk_generar=3&componente=Desplegable&archivo=RegistroVer.php&autocomplete=n' . $get_accion . $get_id_tabla_registro . '&id_cp=' . $this->_dcp['cp_id'] . '&q=" + $( "#cp_' . $this->_dcp['cp_id'] . '" ).val() );');
            } else {
                Generales_PopElementos::agregar_pop_elemento(' ');
            }
            $plantilla['link_agregar_registro'] = Generales_PopElementos::armar_link($this->_dcp['origen_tb_id']);
        }

        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _despelgableLink($valor, $nombre_filtro = null, $estilo = '') {

        if ($this->_matrizLinks()) {
            if (Armado_LinkADestino::armadoVolver()) {
                $id_link_filtro = Armado_LinkADestino::armadoIdLinkFiltro();
            }
// permite seleccionar un valor cuando el grupo esta filtrado
            $seleccionado = '';

            if ($nombre_filtro == null) {
                $nombre_filtro = 'cp_' . $this->_dcp['cp_id'];
            }

            if ($this->_dcp['seleccionar_alta'] == 's') {

                $id_campo = 'cp_' . $this->_dcp['cp_id'];

                $campo_error = '';
                if (($this->_dcp['obligatorio'] == 'no_nulo') && ($this->_metodo != 'registroFiltroMenu')) {
                    $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                    $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
                } else {
                    $obligatorio = '';
                }

                if (isset($_GET['accion'])) {
                    $get_accion = '&accion=' . $_GET['accion'];
                } else {
                    $get_accion = '';
                }

                if (isset($_GET['id_tabla_registro'])) {
                    $get_id_tabla_registro = '&id_tabla_registro=' . $_GET['id_tabla_registro'];
                } else {
                    $get_id_tabla_registro = '';
                }

                $select_link = '
                <script type="text/javascript">
                $(document).ready(function(){
                    $( "#' . $nombre_filtro . '" ).load( "index.php?kk_generar=3&componente=Desplegable&archivo=RegistroVer.php&autocomplete=n' . $get_accion . $get_id_tabla_registro . '&id_cp=' . $this->_dcp['cp_id'] . '&q=' . $valor . '" );
                });
                </script>
                <select name="' . $nombre_filtro . '" id="' . $nombre_filtro . '" ' . $obligatorio . ' ' . $estilo . '></select>' . $campo_error;
                return $select_link;
// NO permite seleccionar un valor cuando el grupo esta filtrado
            } else {
                $seleccionado = '';
                if (is_array($this->matriz_links)) {
                    foreach ($this->matriz_links as $linea) {
// selecciona el valor anterior en la edicion
                        if ($linea["id_" . $this->tb_nombre] == $valor) {
                            $seleccionado = $linea[$this->tb_campo];
                            $seleccionado .= '<input type="hidden" name="' . $nombre_filtro . '" id="' . $nombre_filtro . '" value="' . $linea["id_" . $this->tb_nombre] . '" />';
// selecciona el valor del grupo filtrado ver 'armado_link_a_destino'
                        } elseif (isset($id_link_filtro) && ( $linea["id_" . $this->tb_nombre] == $id_link_filtro )) {
                            $seleccionado = $linea[$this->tb_campo];
                            $seleccionado .= '<input type="hidden" name="' . $nombre_filtro . '" id="' . $nombre_filtro . '" value="' . $linea["id_" . $this->tb_nombre] . '" />';
                        }
                    }
                }
                return $seleccionado;
            }
        } else {
            return '{TR|o_la_tabla_origen_no_contiene_datos_o_no_existe}';
        }
    }

    private function _despelgableLinkAutocomplete() {

        if ($this->_matrizLinks()) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            if (Generales_FiltrosOrden::filtrosIdDesplegable('cp') != $this->_dcp['cp_id']) {
                $select_link = '
                <script type="text/javascript">
                $(document).ready(function(){
                    $("#' . $id_campo . '_ajax").autocomplete("index.php?kk_generar=3&componente=Desplegable&archivo=RegistroVer.php&autocomplete=s&id_cp=' . $this->_dcp['cp_id'] . '", { 
                        matchContains: true, 
                        minChars: 1, 
                        mustMatch: false,
                        selectFirst: true,
                        cacheLength: 0,
                        cellSeparator: "<|>",
                        max: 1000,
                    } );
                    $("#' . $id_campo . '_ajax").result(function(event, data, formatted) {
                        $("#' . $id_campo . '").val(data[1]);
                    });
                });
                </script>
                ';

                if (($this->_dcp['obligatorio'] == 'no_nulo') && ($this->_metodo != 'registroFiltroMenu')) {
                    $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                    $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
                } else {
                    $obligatorio = '';
                    $campo_error = '';
                }

                $select_link .= '<input type="text" name="' . $id_campo . '_ajax" id="' . $id_campo . '_ajax" value="' . $this->_mostrarValorPrevia($this->_valor) . '" >';
                $select_link .= '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $this->_valor . '" ' . $obligatorio . ' >';
                $select_link .= $campo_error;
            } else {
                $this->tb_nombre = $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'];
                $this->tb_campo = $this->_dcp['origen_tb_campo'];

                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas($this->tb_nombre);
                $consulta->campos($this->tb_nombre, 'orden');
                $consulta->campos($this->tb_nombre, 'id_' . $this->tb_nombre);
                $consulta->campos($this->tb_nombre, $this->tb_campo);
                $consulta->condiciones('', $this->tb_nombre, 'id_' . $this->tb_nombre, 'iguales', '', '', Generales_FiltrosOrden::filtrosIdDesplegable('id'));
                $valores = $consulta->realizarConsulta();

                $valores[0]['id_' . $this->tb_nombre];
                $valores[0][$this->tb_campo];

                $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';

                $select_link = $this->_mostrarValor(Generales_FiltrosOrden::filtrosIdDesplegable('id'), 's');
                $select_link .= '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valores[0]['id_' . $this->tb_nombre] . '" ' . $obligatorio . ' >';
            }
            return $select_link;
        } else {
            return '{TR|o_la_tabla_origen_no_contiene_datos_o_no_existe}';
        }
    }

    private function _mostrarValor($id_origen, $con_link, $texto = null) {

        if (Generales_ControlProcesosEspeciales::existeProcesoEspecial($this->_dcp['cp_id'])) {

            if (isset($this->_valor['id'])) {
                $id = $this->_valor['id'];
            } else {
                $id = '';
            }

            $this->tb_nombre = $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'];
            $this->tb_campo = $this->_dcp['origen_tb_campo'];

            Generales_ControlProcesosEspeciales::control('valor', $this->_dcp['cp_id'], $id_origen, $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'], $this->_dcp['origen_tb_campo'], $id);
            $texto = Generales_ControlProcesosEspeciales::texto($this->_dcp['cp_id'], $id_origen);
            $valor = $id;
        } else {

            if ($texto == null) {
                $tb_nombre = $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'];
                $tb_campo = $this->_dcp['origen_tb_campo'];

                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas($tb_nombre);
                $consulta->campos($tb_nombre, $tb_campo);
                if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
                    $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_1']);
                }
                if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
                    $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_2']);
                }
                $consulta->condiciones('', $tb_nombre, 'id_' . $tb_nombre, 'iguales', '', '', $id_origen);
                $valor_campo = $consulta->realizarConsulta();

                $texto = $valor_campo[0][$tb_campo];

                if (isset($this->_dcp['nombre_del_campo_1']) && isset($valor_campo[0][$this->_dcp['nombre_del_campo_1']]) && ($valor_campo[0][$this->_dcp['nombre_del_campo_1']] != '')) {
                    $texto .= $this->_dcp['separador_del_campo_1'];
                    $texto .= $valor_campo[0][$this->_dcp['nombre_del_campo_1']];
                }

                if (isset($this->_dcp['nombre_del_campo_1']) && isset($valor_campo[0][$this->_dcp['nombre_del_campo_2']]) && ($valor_campo[0][$this->_dcp['nombre_del_campo_2']] != '')) {
                    $texto .= $this->_dcp['separador_del_campo_2'];
                    $texto .= $valor_campo[0][$this->_dcp['nombre_del_campo_2']];
                }

                $valor = $this->_valor;
            } else {
                $valor = $this->_valor['id'];
            }

            if (($texto != '') && isset($this->_dcp['motrar_id']) && ($this->_dcp['motrar_id'] == 's')) {
                $texto .= ' (' . $id_origen . ')';
            }
        }

        if ($con_link == 's') {
            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'accion' => '45', 'id_tabla' => $this->_dcp['origen_tb_id'], 'id_tabla_registro' => $valor), 's');
            return '<a href="' . $link . '" target="_top">' . $texto . '</a><br>';
        } else {
            return $texto;
        }
    }

    private function _mostrarValorPrevia($id_origen) {
        $texto = '';
        if ($id_origen != '') {
            if (Generales_ControlProcesosEspeciales::control('valor', $this->_dcp['cp_id'], $id_origen)) {

                $texto = Generales_ControlProcesosEspeciales::texto($this->_dcp['cp_id'], $id_origen);
            } else {

                $tb_nombre = $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'];
                $tb_campo = $this->_dcp['origen_tb_campo'];

                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas($tb_nombre);
                $consulta->campos($tb_nombre, $tb_campo);
                if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
                    $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_1']);
                }
                if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
                    $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_2']);
                }
                $consulta->condiciones('', $tb_nombre, 'id_' . $tb_nombre, 'iguales', '', '', $id_origen);
                $valor_campo = $consulta->realizarConsulta();

                $texto = $valor_campo[0][$tb_campo];

                if (isset($this->_dcp['nombre_del_campo_1']) && isset($valor_campo[0][$this->_dcp['nombre_del_campo_1']]) && ($valor_campo[0][$this->_dcp['nombre_del_campo_1']] != '')) {
                    $texto .= $this->_dcp['separador_del_campo_1'];
                    $texto .= $valor_campo[0][$this->_dcp['nombre_del_campo_1']];
                }

                if (isset($this->_dcp['nombre_del_campo_2']) && isset($valor_campo[0][$this->_dcp['nombre_del_campo_2']]) && ($valor_campo[0][$this->_dcp['nombre_del_campo_2']] != '')) {
                    $texto .= $this->_dcp['separador_del_campo_2'];
                    $texto .= $valor_campo[0][$this->_dcp['nombre_del_campo_2']];
                }

                if (($texto != '') && ($this->_dcp['motrar_id'] == 's')) {
                    $texto .= ' (' . $id_origen . ')';
                }
            }

            $texto = strtr($texto, '"', "'");
        }
        return $texto;
    }

    private function _matrizLinks() {

        $this->tb_nombre = $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'];
        $this->tb_campo = $this->_dcp['origen_tb_campo'];

        if (Generales_ControlProcesosEspeciales::existeProcesoEspecial($this->_dcp['cp_id'])) {
            Generales_ControlProcesosEspeciales::control('matriz_links', $this->_dcp['cp_id'], $this->_valor, $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'], $this->_dcp['origen_tb_campo']);
            $this->matriz_links = Generales_ControlProcesosEspeciales::matriz_links($this->_dcp['cp_id'], $this->_valor);
            return true;
        } elseif ($this->_dcp['origen_tb_prefijo']) {

            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($this->tb_nombre);
            $consulta->campos($this->tb_nombre, 'orden');
            $consulta->campos($this->tb_nombre, 'id_' . $this->tb_nombre);
            $consulta->campos($this->tb_nombre, $this->tb_campo);

            if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
                $consulta->campos($this->tb_nombre, $this->_dcp['nombre_del_campo_1']);
            }
            if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
                $consulta->campos($this->tb_nombre, $this->_dcp['nombre_del_campo_2']);
            }

            if (Generales_FiltrosOrden::filtrosIdDesplegable('cp') == $this->_dcp['cp_id']) {
                $id_origen = Generales_FiltrosOrden::filtrosIdDesplegable('id');
                $consulta->condiciones('', $this->tb_nombre, 'id_' . $this->tb_nombre, 'iguales', '', '', $id_origen);
            }

            $condiciones_ant = false;
            if (isset($this->_dcp['autofiltro']) && ($this->_dcp['autofiltro'] == 's') && isset($_GET['q'])) {
                $consulta->condiciones('', $this->tb_nombre, $this->tb_campo, 'coincide', '', '', $_GET['q']);
                
                if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
                    $consulta->condiciones('o', $this->tb_nombre, $this->_dcp['nombre_del_campo_1'], 'coincide', '', '', $_GET['q']);
                }
                if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
                    $consulta->condiciones('o', $this->tb_nombre, $this->_dcp['nombre_del_campo_2'], 'coincide', '', '', $_GET['q']);
                }
                
                if (isset($this->_dcp['motrar_id']) && ($this->_dcp['motrar_id'] == 's')) {
                    $consulta->condiciones('o', $this->tb_nombre, 'id_' . $this->tb_nombre, 'coincide', '', '', $_GET['q']);
                }
                
                if ($this->_dcp['autofiltro_elementos'] != 0) {
                    $consulta->limite(0, $this->_dcp['autofiltro_elementos']);
                }
                $condiciones_ant = true;
            }

// filtro por antiguedad
            if (isset($this->_dcp['filtrar_antiguedad']) && ($this->_dcp['filtrar_antiguedad'] != '') && ($this->_dcp['campo'] != '')) {

                $ant = explode(',', $this->_dcp['filtrar_antiguedad']);

                if (!isset($ant[0]))
                    $ant[0] = 0;
                if (!isset($ant[1]))
                    $ant[1] = 0;
                if (!isset($ant[2]))
                    $ant[2] = 0;
                if (!isset($ant[3]))
                    $ant[3] = 0;
                if (!isset($ant[4]))
                    $ant[4] = 0;
                if (!isset($ant[5]))
                    $ant[5] = 0;
                $fecha_limite = mktime(date('G') - $ant[3], date('i') - $ant[4], date('s') - $ant[5], date('m') - $ant[1], date('d') - $ant[0], date('Y') - $ant[2]);

                if ($condiciones_ant === false) {
                    $consulta->condiciones('', $this->tb_nombre, $this->_dcp['campo'], 'mayor', '', '', $fecha_limite);
                    $condiciones_ant = true;
                } else {
                    $consulta->condiciones('y', $this->tb_nombre, $this->_dcp['campo'], 'mayor', '', '', $fecha_limite);
                    $condiciones_ant = true;
                }
            }
// filtro segun campo y condicion seleccionada
            if (
                    isset($this->_dcp['campo_filtro']) && ($this->_dcp['campo_filtro'] != '') && ($this->_dcp['condicion'] != '') && ($this->_dcp['condicion'] != 'no_filtrar')
            ) {

                if ($condiciones_ant === false) {
                    $consulta->condiciones('', $this->tb_nombre, $this->_dcp['campo_filtro'], $this->_dcp['condicion'], '', '', $this->_dcp['valor']);
                } else {
                    $consulta->condiciones('y', $this->tb_nombre, $this->_dcp['campo_filtro'], $this->_dcp['condicion'], '', '', $this->_dcp['valor']);
                }
            }
// orden de datos
            if ($this->_dcp['orden'] == 'alfanumerico') {
                $consulta->orden($this->tb_nombre, $this->tb_campo);
            } elseif ($this->_dcp['orden'] == 'predefinido') {
                $consulta->orden($this->tb_nombre, 'orden', 'ascendente');
            } elseif ($this->_dcp['orden'] == 'inverso') {
                $consulta->orden($this->tb_nombre, 'orden', 'descendente');
            }
//$consulta->verConsulta();
            $this->matriz_links = $consulta->realizarConsulta();
            return true;
        } else {
            return false;
        }
    }

    private function _consultaAjax() {
        $select_link = '';
        if (Generales_FiltrosOrden::filtrosIdDesplegable('id') === false) {
            $select_link .= '<option value="" >{TR|m_seleccione_una_opcion}</option>' . "\n";
        }

        if (($this->_matrizLinks() === true) && is_array($this->matriz_links)) {

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

            foreach ($this->matriz_links as $linea) {
// selecciona el valor anterior en la edicion
                $seleccionado = '';
                if (!Generales_ControlProcesosEspeciales::existeProcesoEspecial($this->_dcp['cp_id']) && ($linea["id_" . $this->tb_nombre] == $this->_valor)) {
                    $seleccionado = 'selected';
// selecciona el valor pasado por el proceso especial
                } elseif (Generales_ControlProcesosEspeciales::existeProcesoEspecial($this->_dcp['cp_id'])) {
                    if (isset($linea["seleccionado"]) && ($linea["seleccionado"] == true)) {
                        $seleccionado = 'selected';
                    }
// selecciona el valor del grupo filtrado ver 'armado_link_a_destino'
                } elseif (isset($id_link_filtro) && ( $linea["id_" . $this->tb_nombre] == $id_link_filtro )) {
                    $seleccionado = 'selected';
                }
// mostrar id
                $id = '';
                if (isset($this->_dcp['motrar_id']) && ($this->_dcp['motrar_id'] == 's')) {
                    $id = ' (' . $linea['id_' . $this->tb_nombre] . ')';
                }

                $texto = $linea[$this->tb_campo];

                if ($nombre_del_campo_1 && isset($linea[$this->_dcp['nombre_del_campo_1']]) && ($linea[$this->_dcp['nombre_del_campo_1']] != '')) {
                    $texto .= $separador_del_campo_1;
                    $texto .= $linea[$this->_dcp['nombre_del_campo_1']];
                }

                if ($nombre_del_campo_2 && isset($linea[$this->_dcp['nombre_del_campo_2']]) && ($linea[$this->_dcp['nombre_del_campo_2']] != '')) {
                    $texto .= $separador_del_campo_2;
                    $texto .= $linea[$this->_dcp['nombre_del_campo_2']];
                }

                $select_link .= '<option value="' . $linea["id_" . $this->tb_nombre] . '" ' . $seleccionado . '>' . $texto . $id . '</option>' . "\n";
                $seleccionado = '';
            }
        }

        return $select_link;
    }

    private function _consultaAjaxAutocomplete() {

        if (($this->_matrizLinks() === true) && is_array($this->matriz_links)) {

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

            foreach ($this->matriz_links as $linea) {
                $texto = '';

                if ($this->_dcp['motrar_id'] == 's') {
                    $texto = ' (' . $linea['id_' . $this->tb_nombre] . ')';
                }

                $texto .= $linea[$this->tb_campo];

                if ($nombre_del_campo_1 && isset($linea[$this->_dcp['nombre_del_campo_1']]) && ($linea[$this->_dcp['nombre_del_campo_1']] != '')) {
                    $texto .= $separador_del_campo_1;
                    $texto .= $linea[$this->_dcp['nombre_del_campo_1']];
                }

                if ($nombre_del_campo_2 && isset($linea[$this->_dcp['nombre_del_campo_2']]) && ($linea[$this->_dcp['nombre_del_campo_2']] != '')) {
                    $texto .= $separador_del_campo_2;
                    $texto .= $linea[$this->_dcp['nombre_del_campo_2']];
                }

                echo preg_replace("[\n|\r|\n\r]", ' ', $texto) . '<|>' . $linea['id_' . $this->tb_nombre] . "\n";
            }
        } else {
            echo '';
        }
    }

}
