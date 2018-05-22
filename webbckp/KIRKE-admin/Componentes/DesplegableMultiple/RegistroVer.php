<?php

class Componentes_DesplegableMultiple_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array(); // parametros del componente
    private $dcp_origen = array(); // datos del componente que contiene los datos de origen
    private $_idComponente;         // id del componente
    private $_idRegistro;           // id del registro
    private $id_relaciones_param; // parametros de los componentes relacionados
    private $_componentesDesplegables; // todos los componentes que conformar los desplegables

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

            $this->_componentesDesplegables();
            $this->_valoresDesplegables();
            $mostrar = $this->_armadoRelacionados();
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
            if (Generales_FiltrosOrden::filtrosIdDesplegable('id') !== false) {
                $this->_valor = Generales_FiltrosOrden::filtrosIdDesplegable('id');
                $this->_componentesDesplegables();
                $this->_valoresDesplegables();
                $mostrar = $this->_armadoRelacionados();
            } elseif (isset($_POST[$id_campo])) {
                $this->_valor = Generales_Post::obtener($_POST[$id_campo], 'h');
                $this->_componentesDesplegables();
                $this->_valoresDesplegables();
                $mostrar = $this->_armadoDesplegables();
            } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST[$id_campo]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                $this->_valor = $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)];
                $this->_componentesDesplegables();
                $this->_valoresDesplegables();
                $mostrar = $this->_armadoDesplegables();
            } else {
                $this->_componentesDesplegables();
                $ident = count($this->_componentesDesplegables) - 1;
                $mostrar = $this->_desplegableAlta($ident);
            }

            $campo_error = '';
            if ($this->_dcp['obligatorio'] == 'no_nulo') {
                $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
            } else {
                $obligatorio = '';
            }

            $mostrar .= '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $this->_valor . '"  ' . $obligatorio . '/>' . $campo_error;

            return $this->_tituloYComponente($mostrar, Generales_PopElementos::control_muestra($this->_dcp['origen_tb_id'], $this->_dcp['agregar_registro']));
        } else {

            return '';
        }
    }

    private function _registroAltaPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $this->_valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $this->_valor = '';
            }

            $this->_componentesDesplegables();
            $this->_valoresDesplegables();
            $mostrar = $this->_armadoRelacionados();
            $mostrar .= '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $this->_valor . '" />';

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
            if (isset($_POST[$id_campo])) {
                $this->_valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            }
            $this->_componentesDesplegables();
            $this->_valoresDesplegables();
            $mostrar = $this->_armadoDesplegables();
            $mostrar .= '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $this->_valor . '" />';

            return $this->_tituloYComponente($mostrar, Generales_PopElementos::control_muestra($this->_dcp['origen_tb_id'], $this->_dcp['agregar_registro']));
        } else {

            return '';
        }
    }

    private function _registroModificacionPrevia() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $this->_valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $this->_valor = '';
            }

            $this->_componentesDesplegables();
            $this->_valoresDesplegables();
            $mostrar = $this->_armadoRelacionados();
            $mostrar .= '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $this->_valor . '" />';

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroFiltroCampo() {
        return false;
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
            $nombre_filtro = 'cp_' . $this->_dcp['cp_id'];
            Generales_PopElementos::agregar_pop_elemento('valor = $( "#' . $nombre_filtro . '" ).val();
                if( valor != "" ){
                    fn_DesplegableMultiple(' . $this->_dcp['cp_id'] . ', 1, $( "#' . $nombre_filtro . '_1" ).val());
                    $("#cp_' . $this->_dcp['cp_id'] . '_0").val(valor);
                }');
            $plantilla['link_agregar_registro'] = Generales_PopElementos::armar_link($this->_dcp['origen_tb_id']);
        }

        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _mostrarValor($id_origen, $con_link, $texto = null) {

        $tb_campo = $this->_dcp['origen_tb_campo'];

        $tb_nombre = $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'];

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

        if ($texto == null) {
            $texto = $texto;
            $valor = $this->_valor;
        } else {
            $valor = $this->_valor['id'];
        }

        if ($con_link == 's') {
            return '<a href="./index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'accion' => '45', 'id_tabla' => $this->_dcp['origen_tb_id'], 'id_tabla_registro' => $valor), 's') . '" target="_top">' . $texto . '</a><br>';
        } elseif (isset($tb_campo)) {
            return $texto;
        }
    }

    private function _componentesDesplegables() {

        $cp_ids = explode(',', $this->_dcp['origen_cp_id_otros']);
        array_unshift($cp_ids, $this->_dcp['origen_cp_id']);
        foreach ($cp_ids as $clave => $cp_id) {
            $this->id_relaciones_param[] = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($cp_ids[$clave]);
        }
        $componente_data[0]['id_act'] = $this->_dcp['origen_cp_id'];
        $componente_data[0]['id_rel'] = $this->_dcp['cp_id'];

        $cont = 0;
        foreach ($this->id_relaciones_param as $ident => $valor) {

            if (isset($this->id_relaciones_param[$ident - 1]['tb_id']) && isset($this->id_relaciones_param[$ident]['cp_id'])) {
                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas('kirke_componente_parametro');
                $consulta->tablas('kirke_componente');
                $consulta->campos('kirke_componente_parametro', 'id_componente');
                $consulta->condiciones('', 'kirke_componente_parametro', 'valor', 'iguales', '', '', $this->id_relaciones_param[$ident]['cp_id']);
                $consulta->condiciones('y', 'kirke_componente_parametro', 'id_componente', 'iguales', 'kirke_componente', 'id_componente');
                $consulta->condiciones('y', 'kirke_componente', 'id_tabla', 'iguales', '', '', $this->id_relaciones_param[$ident - 1]['tb_id']);
                //$consulta->verConsulta();
                $comp_relacionados = $consulta->realizarConsulta();

                $componente_data[$cont]['id_act'] = $this->id_relaciones_param[$ident]['cp_id'];
                $componente_data[$cont]['id_rel'] = $comp_relacionados[0]['id_componente'];
            }

            $cont++;
        }

        $this->_componentesDesplegables = $componente_data;
    }

    private function _valoresDesplegables() {

        if (is_array($this->_componentesDesplegables)) {
            $valor_seleccionado = $this->_valor;
            foreach ($this->_componentesDesplegables as $ident => $valor) {
                $tb_nombre = $this->id_relaciones_param[$ident]['tb_prefijo'] . '_' . $this->id_relaciones_param[$ident]['tb_nombre'];
                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas($tb_nombre);
                $consulta->campos($tb_nombre, 'id_' . $tb_nombre, 'valor');
                $consulta->campos($tb_nombre, $this->id_relaciones_param[$ident]['tb_campo'], 'texto');
                if (isset($this->id_relaciones_param[$ident + 1]) && is_array($this->id_relaciones_param[$ident + 1])) {
                    $consulta->campos($tb_nombre, 'id_' . $this->id_relaciones_param[$ident + 1]['tb_prefijo'] . '_' . $this->id_relaciones_param[$ident + 1]['tb_nombre'], 'id_siguiente');
                }
                if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
                    $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_1']);
                }
                if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
                    $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_2']);
                }
                $consulta->condiciones('', $tb_nombre, 'id_' . $tb_nombre, 'iguales', '', '', $valor_seleccionado);
                $consulta->orden($tb_nombre, $this->id_relaciones_param[$ident]['tb_campo']);
                //$consulta->verConsulta();
                $valores_obtenidos = $consulta->realizarConsulta();

                if (isset($this->id_relaciones_param[$ident + 1]) && isset($valores_obtenidos[0]['id_siguiente'])) {
                    $valor_seleccionado = $valores_obtenidos[0]['id_siguiente'];
                }

                $texto = $valores_obtenidos[0]['texto'];

                if (isset($this->_dcp['nombre_del_campo_1']) && isset($valores_obtenidos[0][$this->_dcp['nombre_del_campo_1']]) && ($valores_obtenidos[0][$this->_dcp['nombre_del_campo_1']] != '')) {
                    if (isset($this->_dcp['separador_del_campo_1']) && ($this->_dcp['separador_del_campo_1'] != '')) {
                        $texto .= $this->_dcp['separador_del_campo_1'];
                    }
                    $texto .= $valores_obtenidos[0][$this->_dcp['nombre_del_campo_1']];
                }

                if (isset($this->_dcp['nombre_del_campo_2']) && isset($valores_obtenidos[0][$this->_dcp['nombre_del_campo_2']]) && ($valores_obtenidos[0][$this->_dcp['nombre_del_campo_2']] != '')) {
                    if (isset($this->_dcp['separador_del_campo_2']) && ($this->_dcp['separador_del_campo_2'] != '')) {
                        $texto .= $this->_dcp['separador_del_campo_2'];
                    }
                    $texto .= $valores_obtenidos[0][$this->_dcp['nombre_del_campo_2']];
                }

                $this->_componentesDesplegables[$ident]['valor'] = $valores_obtenidos[0]['valor'];
                $this->_componentesDesplegables[$ident]['texto'] = $texto;
            }
        } else {
            return false;
        }
    }

    private function _armadoDesplegables() {

        $_componentesDesplegables = $this->_componentesDesplegables;
        krsort($_componentesDesplegables);

        $inicio = true;
        foreach ($_componentesDesplegables as $ident => $valor) {

            $tb_nombre = $this->id_relaciones_param[$ident]['tb_prefijo'] . '_' . $this->id_relaciones_param[$ident]['tb_nombre'];
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas($tb_nombre);
            $consulta->campos($tb_nombre, 'id_' . $tb_nombre, 'valor');
            $consulta->campos($tb_nombre, $this->id_relaciones_param[$ident]['tb_campo'], 'texto');

            if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
                $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_1']);
            }
            if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
                $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_2']);
            }

            if ($inicio === true) {
                $inicio = false;
            } else {
                $tb_nombre2 = $this->id_relaciones_param[$ident + 1]['tb_prefijo'] . '_' . $this->id_relaciones_param[$ident + 1]['tb_nombre'];
                $consulta->condiciones('', $tb_nombre, 'id_' . $tb_nombre2, 'iguales', '', '', $_componentesDesplegables[$ident + 1]['valor']);
            }

            $consulta->orden($tb_nombre, $this->id_relaciones_param[$ident]['tb_campo']);
            //$consulta->verConsulta();
            $valores_obtenidos = $consulta->realizarConsulta();

            $id_elemento = $this->_dcp['cp_id'] . '_' . $ident;
            $armado_desplegable[$ident] = '';
            $armado_desplegable[$ident] .= '<div id="cp_div_' . $id_elemento . '">' . "\n";
            $armado_desplegable[$ident] .= '<select name="cp_name_' . $id_elemento . '" id="cp_' . $this->_dcp['cp_id'] . '_' . $ident . '" id_cp="' . $this->_dcp['cp_id'] . '" elemento="' . $ident . '" class="cp_DesplegableMultiple">' . "\n";

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

            if (is_array($valores_obtenidos)) {
                foreach ($valores_obtenidos as $valor_obtenido) {

                    if ($valor_obtenido['valor'] == $_componentesDesplegables[$ident]['valor']) {
                        $seleccionado = 'selected="selected"';
                    } else {
                        $seleccionado = '';
                    }

                    $texto = $valor_obtenido['texto'];

                    if ($nombre_del_campo_1 && isset($valor_obtenido[$this->_dcp['nombre_del_campo_1']]) && ($valor_obtenido[$this->_dcp['nombre_del_campo_1']] != '')) {
                        $texto .= $separador_del_campo_1;
                        $texto .= $valor_obtenido[$this->_dcp['nombre_del_campo_1']];
                    }

                    if ($nombre_del_campo_2 && isset($valor_obtenido[$this->_dcp['nombre_del_campo_2']]) && ($valor_obtenido[$this->_dcp['nombre_del_campo_2']] != '')) {
                        $texto .= $separador_del_campo_2;
                        $texto .= $valor_obtenido[$this->_dcp['nombre_del_campo_2']];
                    }

                    $armado_desplegable[$ident] .= '<option value="' . $valor_obtenido['valor'] . '" ' . $seleccionado . '>' . $texto . '</option>';
                    $armado_desplegable[$ident] .= "\n";
                }
            }

            $armado_desplegable[$ident] .= '</select>' . "\n";

            $tabla_relacionada_nombre = Consultas_ObtenerTablaNombreTipo::armado($this->id_relaciones_param[$ident]['tb_id']);
            $armado_desplegable[$ident] .= $tabla_relacionada_nombre['nombre_idioma'];
            $armado_desplegable[$ident] .= ' / ';
            $armado_desplegable[$ident] .= $this->id_relaciones_param[$ident]['idioma_' . Generales_Idioma::obtener()];

            $armado_desplegable[$ident] .= '</div>' . "\n";
        }

        return implode('', $armado_desplegable);
    }

    private function _armadoRelacionados() {

        $_componentesDesplegables = $this->_componentesDesplegables;
        krsort($_componentesDesplegables);

        $valores_seleccionados = '';
        foreach ($_componentesDesplegables as $ident => $valor) {

            if ($ident != 0) {
                $valores_seleccionados .= $valor['texto'];
            } else {
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'accion' => '45', 'id_tabla' => $this->_dcp['origen_tb_id'], 'id_tabla_registro' => $this->_valor), 's');
                $valores_seleccionados .= '<a href="' . $link . '" target="_top">' . $valor['texto'] . '</a>';
            }

            $tabla_relacionada_nombre = Consultas_ObtenerTablaNombreTipo::armado($this->id_relaciones_param[$ident]['tb_id']);
            $valores_seleccionados .= ' &nbsp;&nbsp;&nbsp;&nbsp; [ ';
            $valores_seleccionados .= $tabla_relacionada_nombre['nombre_idioma'];
            $valores_seleccionados .= ' &nbsp;/&nbsp; ';
            $valores_seleccionados .= $this->id_relaciones_param[$ident]['idioma_' . Generales_Idioma::obtener()];
            $valores_seleccionados .= ' ]';

            $valores_seleccionados .= '<br />';
        }

        return $valores_seleccionados;
    }

    private function _desplegableAlta($ident, $ajax = false, $valor = null) {

        $id_elemento = $this->_dcp['cp_id'] . '_' . $ident;

        $armado_desplegable = '';
        if (!$ajax) {
            $armado_desplegable .= '<div id="cp_div_' . $id_elemento . '">' . "\n";
        }
        $armado_desplegable .= '<select name="cp_name_' . $id_elemento . '" id="cp_' . $this->_dcp['cp_id'] . '_' . $ident . '" id_cp="' . $this->_dcp['cp_id'] . '" elemento="' . $ident . '" class="cp_DesplegableMultiple">' . "\n";

        //Elementos select
        $armado_desplegable .= '<option value="">{TR|o_seleccione_una_opcion}</option>' . "\n";
        $tb_nombre = $this->id_relaciones_param[$ident]['tb_prefijo'] . '_' . $this->id_relaciones_param[$ident]['tb_nombre'];

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($tb_nombre);
        $consulta->campos($tb_nombre, 'id_' . $tb_nombre, 'valor');
        $consulta->campos($tb_nombre, $this->id_relaciones_param[$ident]['tb_campo'], 'texto');

        if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
            $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_1']);
        }
        if (isset($this->_dcp['nombre_del_campo_2']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
            $consulta->campos($tb_nombre, $this->_dcp['nombre_del_campo_2']);
        }

        if (isset($this->id_relaciones_param[$ident + 1]) && isset($valor)) {
            $tb_nombre2 = $this->id_relaciones_param[$ident + 1]['tb_prefijo'] . '_' . $this->id_relaciones_param[$ident + 1]['tb_nombre'];
            $consulta->condiciones('', $tb_nombre, 'id_' . $tb_nombre2, 'iguales', '', '', $valor);
        }

        $consulta->orden($tb_nombre, $this->id_relaciones_param[$ident]['tb_campo']);
        //$consulta->verConsulta();
        $valores_obtenidos = $consulta->realizarConsulta();

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

        if (is_array($valores_obtenidos)) {
            foreach ($valores_obtenidos as $valor_obtenido) {

                $texto = $valor_obtenido['texto'];

                if ($nombre_del_campo_1 && isset($valor_obtenido[$this->_dcp['nombre_del_campo_1']]) && ($valor_obtenido[$this->_dcp['nombre_del_campo_1']] != '')) {
                    $texto .= $separador_del_campo_1;
                    $texto .= $valor_obtenido[$this->_dcp['nombre_del_campo_1']];
                }

                if ($nombre_del_campo_2 && isset($valor_obtenido[$this->_dcp['nombre_del_campo_2']]) && ($valor_obtenido[$this->_dcp['nombre_del_campo_2']] != '')) {
                    $texto .= $separador_del_campo_2;
                    $texto .= $valor_obtenido[$this->_dcp['nombre_del_campo_2']];
                }

                $armado_desplegable .= '<option value="' . $valor_obtenido['valor'] . '">' . $texto . '</option>' . "\n";
            }
        }

        $armado_desplegable .= '</select>' . "\n";

        $tabla_relacionada_nombre = Consultas_ObtenerTablaNombreTipo::armado($this->id_relaciones_param[$ident]['tb_id']);
        $armado_desplegable .= $tabla_relacionada_nombre['nombre_idioma'];
        $armado_desplegable .= ' / ';
        $armado_desplegable .= $this->id_relaciones_param[$ident]['idioma_' . Generales_Idioma::obtener()];

        if (!$ajax) {
            $armado_desplegable .= '</div>' . "\n";

            for ($i = ($ident - 1); $i >= 0; $i--) {

                $id_elemento = $this->_dcp['cp_id'] . '_' . $i;
                $armado_desplegable .= '<div id="cp_div_' . $id_elemento . '" style="display: none;">' . "\n";
                $armado_desplegable .= '<select name="cp_name_' . $id_elemento . '" id="cp_' . $id_elemento . '" id_cp="' . $this->_dcp['cp_id'] . '" elemento="' . $i . '" class="cp_DesplegableMultiple">' . "\n";
                $armado_desplegable .= '</select>' . "\n";

                $tabla_relacionada_nombre = Consultas_ObtenerTablaNombreTipo::armado($this->id_relaciones_param[$i]['tb_id']);
                $armado_desplegable .= $tabla_relacionada_nombre['nombre_idioma'];
                $armado_desplegable .= ' / ';
                $armado_desplegable .= $this->id_relaciones_param[$i]['idioma_' . Generales_Idioma::obtener()];

                $armado_desplegable .= '</div>' . "\n";
            }
        }

        return $armado_desplegable;
    }

    private function _consultaAjax() {

        $this->_componentesDesplegables();

        $ident = $this->_dcp['elemento_consultaAjax'] - 1;

        return $this->_desplegableAlta($ident, true, $this->_valor);
    }

}
