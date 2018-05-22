<?php

class Componentes_DesplegableNumeros_RegistroVer extends Armado_Plantilla {

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
        if (Generales_ControlProcesosEspeciales::existeProcesoEspecial($this->_dcp['cp_id'])) {
            $align = 'left';
        } else {
            $align = 'right';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
        
        return Armado_RegistroListadoCabezal::armado($this->_dcp['cp_id'], $this->_dcp['tb_campo'], $this->_dcp['idioma_' . Generales_Idioma::obtener()], $ocultar_celulares, $align);
    }

    private function _registroListadoCuerpo() {

        if (Generales_ControlProcesosEspeciales::existeProcesoEspecial($this->_dcp['cp_id'])) {
            Generales_ControlProcesosEspeciales::control('valor', $this->_dcp['cp_id'], $this->_valor, $this->_dcp['tb_prefijo'] . "_" . $this->_dcp['tb_nombre'], $this->_dcp['tb_campo'], $this->_idRegistro);
            $mostrar = Generales_ControlProcesosEspeciales::texto($this->_dcp['cp_id'], $this->_valor);
            $align = 'left';
        } else {
            $mostrar = $this->_valor;
            $align = 'right';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'" align="' . $align . '">' . $mostrar . '&nbsp;</td>';
    }

    private function _registroListadoPie() {
        if ($this->_dcp['totales_mostrar'] == 's') {
            $consulta_suma = new Bases_RegistroConsulta(__FILE__, __LINE__);
            //$consulta_cont->verConsulta();
            $consulta_suma_total = $consulta_suma->sumaTotal(Generales_FiltrosOrden::obtenerConsulta(), $this->_dcp['tb_campo']);
            if (Generales_ControlProcesosEspeciales::existeProcesoEspecial($this->_dcp['cp_id'])) {
                $align = 'left';
            } else {
                $align = 'right';
            }
            
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
            
            return '<td class="columna'.$ocultar_celulares.'" align="' . $align . '">' . $consulta_suma_total[0][$this->_dcp['tb_campo']] . '&nbsp;&nbsp;</td>';
        } else {
            return false;
        }
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {
            if (Generales_ControlProcesosEspeciales::control('valor', $this->_dcp['cp_id'], $this->_valor)) {
                $mostrar = Generales_ControlProcesosEspeciales::texto($this->_dcp['cp_id'], $this->_valor);
            } else {
                $mostrar = $this->_valor;
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
            // encabezados para el autocomplete
            if ($this->_dcp['autofiltro'] == 's') {
                Armado_Cabeceras::autocomplete();
            }

            $id_campo = 'cp_' . $this->_dcp['cp_id'];
            if (isset($_POST[$id_campo])) {
                $this->_valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST[$id_campo]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                $this->_valor = $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)];
            } elseif (($this->_dcp['valor_predefinido'] != '') && ($this->_valor == '')) {
                $this->_valor = $this->_dcp['valor_predefinido'];
            } else {
                $this->_valor = '';
            }

            if ($this->_dcp['autofiltro'] == 's') {
                $mostrar = $this->_despelgableLinkAutocomplete();
            } else {

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

                $mostrar = '
                <script type="text/javascript">
                $(document).ready(function(){
                    $( "#' . $id_campo . '" ).load( "index.php?kk_generar=3&componente=Desplegable&archivo=RegistroVer.php&autocomplete=n' . $get_accion . $get_id_tabla_registro . '&id_cp=' . $this->_dcp['cp_id'] . '&q=' . $this->_valor . '" );
                });
                </script>
                <select name="' . $id_campo . '" id="' . $id_campo . '" ' . $obligatorio . '></select>' . $campo_error;
            }

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

            if (Generales_ControlProcesosEspeciales::control('valor', $this->_dcp['cp_id'], $valor)) {
                $mostrar .= Generales_ControlProcesosEspeciales::texto($this->_dcp['cp_id'], $valor);
            } else {
                $mostrar .= $valor;
            }

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroModificacion() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezados para el autocomplete
            if ($this->_dcp['autofiltro'] == 's') {
                Armado_Cabeceras::autocomplete();
            }

            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            // recupero de los valores obtenidos al volver de la vista previa
            if (isset($_POST[$id_campo]) && Generales_Post::obtener($_POST[$id_campo], 'h') != '') {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = Generales_Post::obtener($this->_valor, 'h');
            }

            if ($this->_dcp['autofiltro'] == 's') {
                $mostrar = $this->_despelgableLinkAutocomplete();
            } else {

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

                $mostrar = '
                <script type="text/javascript">
                $(document).ready(function(){
                    $( "#' . $id_campo . '" ).load( "index.php?kk_generar=3&componente=Desplegable&archivo=RegistroVer.php&autocomplete=n' . $get_accion . $get_id_tabla_registro . '&id_cp=' . $this->_dcp['cp_id'] . '&q=' . $valor . '" );
                });
                </script>
                <select name="' . $id_campo . '" id="' . $id_campo . '" ' . $obligatorio . '></select>' . $campo_error;
            }

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

            if (Generales_ControlProcesosEspeciales::control('valor', $this->_dcp['cp_id'], $valor)) {
                $mostrar .= Generales_ControlProcesosEspeciales::texto($this->_dcp['cp_id'], $valor);
            } else {
                $mostrar .= $valor;
            }

            return $this->_tituloYComponente($mostrar);
        } else {

            return '';
        }
    }

    private function _registroFiltroCampo() {

        if (isset($this->_dcp['filtrar']) && ($this->_dcp['filtrar'] == 's')) {

            if (isset($this->_valor[2]['condicion'])) {
                $condicion = 'rango';
                $valor1 = $this->_valor[0]['valor'];
                $valor2 = $this->_valor[1]['valor'];
            } elseif (isset($this->_valor['condicion'])) {
                $condicion = $this->_valor['condicion'];
                $valor1 = $this->_valor['valor'];
                $valor2 = '';
            } else {
                $condicion = '';
                $valor1 = '';
                $valor2 = '';
            }

            if (($condicion == '') || ($condicion == 'nulo') || ($condicion == 'no_nulo')) {
                $style = 'display:none;';
            } else {
                $style = '';
            }
            if ($condicion != 'rango') {
                $style2 = 'display:none;';
            } else {
                $style2 = '';
            }

            $desplegable1 = Armado_SelectNumeros::armado('valor_' . $this->_dcp['cp_id'], $valor1, $this->_dcp['minimo'], $this->_dcp['maximo'], $this->_dcp['intervalo'], '', '', 'filtro_Texto', $style);
            $desplegable2 = Armado_SelectNumeros::armado('valor_' . $this->_dcp['cp_id'] . '_2', $valor2, $this->_dcp['minimo'], $this->_dcp['maximo'], $this->_dcp['intervalo'], '', '', 'filtro_Texto', $style2);

            $template = '
                <td><div class="filtros_texto">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</div>
                <input name="parametro_' . $this->_dcp['cp_id'] . '" id="parametro_' . $this->_dcp['cp_id'] . '" type="hidden" />
                </td>
                <td>' . $this->_registroFiltroCampoOpciones($condicion) . '</td>
                <td>
                    ' . $desplegable1 . $desplegable2 . '
                </td>
                <td><div class="bt_tb_eliminar_filtro" filtro_eliminar_id="' . $this->_dcp['cp_id'] . '"></div>
                </td>
            ';

            return $template;
        } else {

            return false;
        }
    }

    private function _registroFiltroCampoOpciones($condicion) {

        $descripciones[0] = '{TR|o_igual_a}';
        $descripciones[1] = '{TR|o_mayor_a}';
        $descripciones[2] = '{TR|o_menor_a}';
        $descripciones[3] = '{TR|o_rango}';
        $descripciones[4] = '{TR|o_nulo}';
        $descripciones[5] = '{TR|o_no_nulo}';

        $valores[0] = 'iguales';
        $valores[1] = 'mayor';
        $valores[2] = 'menor';
        $valores[3] = 'rango';
        $valores[4] = 'nulo';
        $valores[5] = 'no_nulo';

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

    private function _despelgableLinkAutocomplete() {

        $id_campo = 'cp_' . $this->_dcp['cp_id'];

        $select_link = '
        <script type="text/javascript">
        $(document).ready(function(){
            $("#' . $id_campo . '_ajax").autocomplete("index.php?kk_generar=3&componente=DesplegableNumeros&archivo=RegistroVer.php&autocomplete=s&id_cp=' . $this->_dcp['cp_id'] . '", { 
                matchContains: true, 
                minChars: 1, 
                maxItemsToShow: 12, 
                mustMatch: false,
                selectFirst: true,
                cacheLength: 0,
            } );
            $("#' . $id_campo . '_ajax").result(function(event, data, formatted) {
                $("#' . $id_campo . '").val(data[0]);
            });
        });
        </script>
        ';

        $campo_error = '';

        if (($this->_dcp['obligatorio'] == 'no_nulo') && ($this->_metodo != 'registroFiltroMenu')) {
            $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
            $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
        } else {
            $obligatorio = '';
        }

        $select_link .= '<input type="text" name="' . $id_campo . '_ajax" id="' . $id_campo . '_ajax" value="' . $this->_valor . '" >';
        $select_link .= '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $this->_valor . '" ' . $obligatorio . ' >';

        return $select_link;
    }

    private function _consultaAjax() {

        if (Generales_ControlProcesosEspeciales::control('matriz_links', $this->_dcp['cp_id'], $this->_valor, $this->_dcp['tb_prefijo'] . "_" . $this->_dcp['tb_nombre'], $this->_dcp['tb_campo'])) {
            $array_datos = Generales_ControlProcesosEspeciales::matriz_links($this->_dcp['cp_id'], $this->_valor);

            $select_matriz = '';
            foreach ($array_datos as $array_dato) {
                $seleccionado = '';
// selecciona el valor anterior en la edicion
                if (!Generales_ControlProcesosEspeciales::existeProcesoEspecial($this->_dcp['cp_id']) && ($array_dato['id_' . $this->_dcp['tb_prefijo'] . "_" . $this->_dcp['tb_nombre']] == $this->_valor)) {
                    $seleccionado = 'selected="selected"';
// selecciona el valor pasado por el proceso especial
                } elseif (Generales_ControlProcesosEspeciales::existeProcesoEspecial($this->_dcp['cp_id'])) {
                    if (isset($array_dato["seleccionado"]) && ($array_dato["seleccionado"] == true)) {
                        $seleccionado = 'selected';
                    }
                }
                $select_matriz .= '<option value="' . $array_dato['id_' . $this->_dcp['tb_prefijo'] . "_" . $this->_dcp['tb_nombre']] . '" ' . $seleccionado . '>' . $array_dato[$this->_dcp['tb_campo']] . '</option>';
            }
            return $select_matriz;
        } else {
            $select_numero = '';
            for ($i = $this->_dcp['minimo']; $i <= $this->_dcp['maximo']; $i = $i + $this->_dcp['intervalo']) {
                $seleccionado = '';
                if ($this->_valor == $i) {
                    $seleccionado = 'selected="selected"';
                }
                $select_numero .= '<option value="' . $i . '" ' . $seleccionado . '>' . $i . '</option>';
            }
            return $select_numero;
        }
    }

    private function _consultaAjaxAutocomplete() {

        if ($this->_dcp['minimo'] == NULL) {
            $this->_dcp['minimo'] = 0;
        }
        if ($this->_dcp['maximo'] == NULL) {
            $this->_dcp['maximo'] = 100;
        }
        if ($this->_dcp['intervalo'] == NULL) {
            $this->_dcp['intervalo'] = 1;
        }

        $cont = 1;
        for ($i = $this->_dcp['minimo']; $i <= $this->_dcp['maximo']; $i = $i + $this->_dcp['intervalo']) {
            $posicion = strpos((string) $i, (string) $_GET['q']);
            if (isset($_GET['q']) && ($_GET['q'] != '') && ($posicion == 0) && ($posicion !== false)) {
                echo $i . "\n";
                if ($cont >= 12) {
                    exit;
                }
                $cont++;
            }
        }
    }

}
