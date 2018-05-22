<?php

class Componentes_Numero_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;                    // valor del compenente en tabla
    private $_metodo;                   // metodo a llamar
    private $_dcp = array();            // parametros del componente
    private $_idComponente;            // id del componente
    private $_idRegistro;              // id del registro

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
        return number_format($this->_valor, $this->_dcp['decimales'], ',', ' ');
    }

    private function _registroListadoCabezal() {
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
        
        return Armado_RegistroListadoCabezal::armado($this->_dcp['cp_id'], $this->_dcp['tb_campo'], $this->_dcp['idioma_' . Generales_Idioma::obtener()], $ocultar_celulares, 'right');
    }

    private function _registroListadoCuerpo() {
        if (isset($this->_valor) && ($this->_valor != '') && is_numeric($this->_valor)) {
            $mostrar = number_format($this->_valor, $this->_dcp['decimales'], ',', ' ');
        } else {
            $mostrar = '';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden"';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna '.$ocultar_celulares.'" align="right">' . $mostrar . '&nbsp;&nbsp;</td>';
    }

    private function _registroListadoPie() {
        if ($this->_dcp['totales_mostrar'] == 's') {
            $consulta_suma = new Bases_RegistroConsulta(__FILE__, __LINE__);
            //$consulta_cont->verConsulta();
            $consulta_suma_total = $consulta_suma->sumaTotal(Generales_FiltrosOrden::obtenerConsulta(), $this->_dcp['tb_campo']);
            
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
            
            return '<td class="columna'.$ocultar_celulares.'" align="right">' . $consulta_suma_total[0][$this->_dcp['tb_campo']] . '&nbsp;&nbsp;</td>';
        } else {
            return false;
        }
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {

            if (isset($this->_valor) && ($this->_valor != '') && is_numeric($this->_valor)) {
                $valor = number_format($this->_valor, $this->_dcp['decimales'], ',', ' ');
            } else {
                $valor = '';
            }
            return $this->_tituloYComponente($valor);
        } else {

            return '';
        }
    }

    private function _registroAlta() {

        if (!isset($this->_dcp['ocultar_edicion']) || ($this->_dcp['ocultar_edicion'] == 'n')) {

            // encabezado necesario para validar la accion con javascript
            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

            $id_campo = 'cp_' . $this->_dcp['cp_id'];

            // recupero de los valores obtenidos al volver de la vista previa
            if (isset($_POST[$id_campo]) && (Generales_Post::obtener($_POST[$id_campo], 'h') != "")) {
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } elseif (($this->_dcp['predefinir_ultimo_val_cargado'] == 's') && !isset($_POST[$id_campo]) && isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)])) {
                $valor = $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)];
            } else {
                $valor = "";
            }

            if ($this->_dcp['obligatorio'] == 'nulo') {
                $plantilla['obligatorio'] = '';
                $obligatorio = '';
                $campo_error = '';
            } else {
                $plantilla['obligatorio'] = '<span class="VC_campo_requerido">&#8226;</span> ';
                $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
            }

            // largo con decimales mas los espacio por miles, coma y negativo
            $largo = $this->_dcp['largo'] + floor($this->_dcp['largo'] / 3) + $this->_dcp['decimales'] + 2;
            $control = $this->_dcp['largo'] . ',' . $this->_dcp['decimales'];
            $mostrar = '<input type="text" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" size="' . $largo . '" maxlength="' . $largo . '" style="text-align:right" filtro="numeros" control="CampoNumero" control_valor="' . $control . '" ' . $obligatorio . '/>' . $campo_error;
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

            $buscar = array(',', ' ');
            $reemplazos = array('.', '');
            $valor_control = str_replace($buscar, $reemplazos, $valor);

            if ($valor_control > 0) {
                $valor_mostrar = $valor;
            } elseif ($valor_control == '') {
                $valor_mostrar = '<span class="texto_claro">&lt; {TR|m_sin_datos} &gt;</span>';
            } else {
                $valor_mostrar = '<font color="#FF0000">' . $valor . '</font>';
            }

            $mostrar = $valor_mostrar . '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';

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
                $valor = Generales_Post::obtener($_POST[$id_campo], 'h');
            } else {
                $valor = '';
            }

            // recupero de los valores obtenidos al volver de la vista previa
            if ($this->_valor != '') {
                $valor = number_format($this->_valor, $this->_dcp['decimales'], ',', ' ');
            }
            if ($this->_dcp['obligatorio'] == 'no_nulo') {
                $obligatorio = 'no_nulo="{TR|o_debe_ingresar_un_dato}" ';
                $campo_error = '<div class="VC_error" id="VC_' . $id_campo . '"></div>';
            } else {
                $obligatorio = '';
                $campo_error = '';
            }

            // largo con decimales mas los espacio por miles, coma y negativo
            $largo = $this->_dcp['largo'] + floor($this->_dcp['largo'] / 3) + $this->_dcp['decimales'] + 2;
            $control = $this->_dcp['largo'] . ',' . $this->_dcp['decimales'];
            $mostrar = '<input type="text" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" size="' . $largo . '" maxlength="' . $largo . '" style="text-align:right" filtro="numeros" control="CampoNumero" control_valor="' . $control . '" ' . $obligatorio . '/>' . $campo_error;
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

            $buscar = array(',', ' ');
            $reemplazos = array('.', '');
            $valor_control = str_replace($buscar, $reemplazos, $valor);

            if ($valor_control > 0) {
                $valor_mostrar = $valor;
            } elseif ($valor_control == '') {
                $valor_mostrar = '<span class="texto_claro">&lt; {TR|m_sin_datos} &gt;</span>';
            } else {
                $valor_mostrar = '<font color="#FF0000">' . $valor . '</font>';
            }

            $mostrar = $valor_mostrar . '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $valor . '" />';

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
                $style1 = 'display:none;';
            } else {
                $style1 = '';
            }
            if ($condicion != 'rango') {
                $style2 = 'display:none;';
            } else {
                $style2 = '';
            }

            Armado_Cabeceras::armadoComponentes($this->_nombreComponente, 'RegistroVerFiltros.js');

            $template = '
                <td><div class="filtros_texto">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</div>
                <input name="parametro_' . $this->_dcp['cp_id'] . '" id="parametro_' . $this->_dcp['cp_id'] . '" type="hidden" />
                </td>
                <td>' . $this->_registroFiltroCampoOpciones($condicion) . '</td>
                <td>
                    <input style="text-align:right;' . $style1 . '" type="text" name="valor_' . $this->_dcp['cp_id'] . '" id="valor_' . $this->_dcp['cp_id'] . '" value="' . $valor1 . '" class="filtro_Texto" />
                    <input style="text-align:right;' . $style2 . '" type="text" name="valor_' . $this->_dcp['cp_id'] . '_2" id="valor_' . $this->_dcp['cp_id'] . '_2" value="' . $valor2 . '" class="filtro_Texto" />
                </td>
                <td><div class="bt_tb_eliminar_filtro" filtro_eliminar_id="' . $this->_dcp['cp_id'] . '"></div>
                <script type="text/javascript">
                  $(document).ready(function() {
                    $("#valor_' . $this->_dcp['cp_id'] . '").keypress(function(e) {
                        NumeroegistroVerFiltros(e, \'0123456789\');
                    });
                    $("#valor_' . $this->_dcp['cp_id'] . '_2").keypress(function(e) {
                        NumeroegistroVerFiltros(e, \'0123456789\');
                    });
                  });
                </script>
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

        if ($this->_dcp['obligatorio'] == 'no_nulo') {
            $obligatorio = '<span class="VC_campo_requerido">&#8226;</span> ';
            $plantilla['ocultar'] = '';
        } else {
            $obligatorio = '';
            if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
                $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
            }
        }
        $plantilla['idioma_generales_idioma'] = $obligatorio . ' ' . $this->_dcp['idioma_' . Generales_Idioma::obtener()];
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
