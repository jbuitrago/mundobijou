<?php

class Componentes_FechaSistema_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_campoAncho = array();
    private $_formatoFecha;

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
        if ($this->_valor) {
            $fecha = $this->_formateoFecha($this->_valor);
        } else {
            $fecha = '<span class="texto_claro">&lt; {TR|m_sin_fecha} &gt;</span>';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'">' . $fecha . '</td>';
    }

    private function _registroListadoPie() {
        if (!isset($this->_dcp['ocultar_celulares'])) {
            return '<td class="columna kk_resp_hidden">&nbsp;</td>';
        }
        return false;
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {

            if ($this->_valor) {
                $fecha = $this->_formateoFecha($this->_valor);
            } else {
                $fecha = '<span class="texto_claro">{TR|o_sin_fecha}</span>';
            }
            return $this->_tituloYComponente($fecha);
        } else {

            return '';
        }
    }

    private function _registroAlta() {
        return false;
    }

    private function _registroAltaPrevia() {
        return false;
    }

    private function _registroModificacion() {
        return false;
    }

    private function _registroModificacionPrevia() {
        return false;
    }

    private function _registroFiltroCampo() {

        // encabezado necesario para validar la accion con javascript
        Armado_Cabeceras::armadoComponentes($this->_nombreComponente, __FILE__);

        if (isset($this->_dcp['filtrar']) && ($this->_dcp['filtrar'] == 's')) {

            if (isset($this->_valor[2]['condicion'])) {
                $valores = explode(';', $valor1 = $this->_valor[2]['valor']);
                if (count($valores) > 1) {
                    $condicion = 'rango';
                    $valor1 = $valores[0];
                    $valor2 = $valores[1];
                } else {
                    $condicion = $this->_valor[2]['condicion'];
                    $valor1 = $this->_valor[2]['valor'];
                    $valor2 = '';
                }
            } elseif (isset($this->_valor[1]['condicion'])) {
                $condicion = $this->_valor[0]['condicion'];
                $valor1 = $this->_valor[1]['valor'];
                $valor2 = '';
            } elseif (isset($this->_valor['condicion'])) {
                $condicion = $this->_valor['condicion'];
                $valor1 = '';
                $valor2 = '';
            } else {
                $condicion = '';
                $valor1 = '';
                $valor2 = '';
            }

            if (($condicion == '') || ($condicion == 'nulo') || ($condicion == 'no_nulo')) {
                $style1 = 'style="display:none"';
            } else {
                $style1 = '';
            }
            if ($condicion != 'rango') {
                $style2 = 'style="display:none"';
            } else {
                $style2 = '';
            }

            $mostrar1 = $this->_codigojQuery('cp_' . $this->_dcp['cp_id'], $valor1);
            $mostrar1 .= '<input size="12" type=text id="cp_' . $this->_dcp['cp_id'] . '" name="valor_' . $this->_dcp['cp_id'] . '" value="' . $valor1 . '" />';

            $mostrar2 = $this->_codigojQuery('cp_' . $this->_dcp['cp_id'] . '_2', $valor2);
            $mostrar2 .= '<input size="12" type=text id="cp_' . $this->_dcp['cp_id'] . '_2" name="valor_' . $this->_dcp['cp_id'] . '_2" value="' . $valor2 . '" />';

            $template = '
                <td><div class="filtros_texto">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</div>
                <input name="parametro_' . $this->_dcp['cp_id'] . '" id="parametro_' . $this->_dcp['cp_id'] . '" type="hidden" />
                </td>
                <td>' . $this->_registroFiltroCampoOpciones($condicion) . '</td>
                <td>
                    <div ' . $style1 . ' id="valor_' . $this->_dcp['cp_id'] . '" >' . $mostrar1 . '</div>
                    <div ' . $style2 . ' id="valor_' . $this->_dcp['cp_id'] . '_2" >' . $mostrar2 . '</div>
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

        $valores[0] = 'fecha_iguales';
        $valores[1] = 'fecha_mayor';
        $valores[2] = 'fecha_menor';
        $valores[3] = 'fecha_rango';
        $valores[4] = 'nulo';
        $valores[5] = 'no_nulo';

        switch ($condicion) {
            case 'mayor':
                $condicion = 'fecha_mayor';
                break;
            case 'menor':
                $condicion = 'fecha_menor';
                break;
            case 'rango':
                $condicion = 'fecha_rango';
                break;
            case 'no_consulta_bd':
                $condicion = 'fecha_iguales';
                break;
        }

        return Armado_SelectFiltros::armado($this->_dcp['cp_id'], $valores, $descripciones, $condicion);
    }

    // metodos especiales
    private function _tituloYComponente($mostrar) {

        $plantilla['idioma_generales_idioma'] = $this->_dcp['idioma_' . Generales_Idioma::obtener()];
        $plantilla['mostrar'] = $mostrar;

        if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
            $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
        }

        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _formateoFecha($fecha_sin_formato) {
        if ($fecha_sin_formato) {
            switch ($this->_dcp['formato_fecha']) {
                case 'ddmmaaaa':
                    $fecha = @date('d-m-Y H:i:s', $fecha_sin_formato);
                    break;
                case 'ddmmaa':
                    $fecha = @date('d-m-y H:i:s', $fecha_sin_formato);
                    break;
                case 'mmddaaaa':
                    $fecha = @date('m-d-Y H:i:s', $fecha_sin_formato);
                    break;
                case 'mmddaa':
                    $fecha = @date('m_d-y H:i:s', $fecha_sin_formato);
                    break;
                case 'aammdd':
                    $fecha = @date('y-m-d H:i:s', $fecha_sin_formato);
                    break;
            }
        }
        return $fecha;
    }

    private function _formateoFechaJS() {

        if (!isset($this->_dcp['formato_fecha']))
            $this->_dcp['formato_fecha'] = 'ddmmaaaa';

        switch ($this->_dcp['formato_fecha']) {
            case 'ddmmaaaa':
                $fecha = 'dd-mm-yy';
                break;
            case 'ddmmaa':
                $fecha = 'dd-mm-y';
                break;
            case 'mmddaaaa':
                $fecha = 'mm-dd-yy';
                break;
            case 'mmddaa':
                $fecha = 'mm-dd-y';
                break;
            case 'aammdd':
                $fecha = 'y-mm-dd';
                break;
            case 'aaaammdd':
                $fecha = 'yy-mm-dd';
                break;
        }

        return $fecha;
    }

    private function _codigojQuery($id_campo, $fecha) {

        if (!isset($this->_dcp['mostrar_hora']) || ($this->_dcp['mostrar_hora'] == 'n')) {
            $llamado_jQuery = "$('#" . $id_campo . "').datepicker({";
        } else {
            $llamado_jQuery = " $('#" . $id_campo . "').datetimepicker({ 
                controlType: 'select',
                timeOnlyTitle: '{TR|o_seleccione_una_hora}',
                timeText: '{TR|o_horario}',
                hourText: '{TR|o_hora}',
                minuteText: '{TR|o_minutos}',
                secondText: '{TR|o_segundos}',
                millisecText: '{TR|o_milisegundos}',
                timezoneText: '{TR|o_zona_horaria}',
            ";
        }

        $ajuste_calendario = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '3', 'componente' => 'Fecha', 'archivo' => 'calendar.gif', 'traducir' => 'n'));

        return "
<script type=\"text/javascript\">
  $(document).ready(function() {

           " . $llamado_jQuery . " 
		dateFormat: '" . $this->_formateoFechaJS() . "',
                weekHeader: '{TR|o_semana_2_caracteres}',
                closeText: '{TR|o_cerrar}',
                currentText: '\'{TR|o_ahora}',
		dayNames: ['{TR|o_domingo}', '{TR|o_lunes}', '{TR|o_martes}', '{TR|o_miercoles}', '{TR|o_jueves}', '{TR|o_viernes}', '{TR|o_sabado}'],
		dayNamesMin: ['{TR|o_dom_l}', '{TR|o_lun_l}', '{TR|o_mar_l}', '{TR|o_mie_l}', '{TR|o_jue_l}', '{TR|o_vie_l}', '{TR|o_sab_l}'],
		dayNamesShort: ['{TR|o_dom}', '{TR|o_lun}', '{TR|o_mar}', '{TR|o_mie}', '{TR|o_jue}', '{TR|o_vie}', '{TR|o_sab}'],
		firstDay: 1, // primer día de la semana (Lunes)
		monthNames: ['{TR|o_enero}', '{TR|o_febrero}', '{TR|o_marzo}', '{TR|o_abril}', '{TR|o_mayo}', '{TR|o_junio}', '{TR|o_julio}', '{TR|o_agosto}', '{TR|o_septiembre}', '{TR|o_octubre}', '{TR|o_noviembre}', '{TR|o_diciembre}'],
		monthNamesShort: ['{TR|o_ene}', '{TR|o_feb}', '{TR|o_mar}', '{TR|o_abr}', '{TR|o_may}', '{TR|o_jun}', '{TR|o_jul}', '{TR|o_ago}', '{TR|o_sep}', '{TR|o_oct}', '{TR|o_nov}', '{TR|o_dic}'],
		navigationAsDateFormat: true,
                showButtonPanel: true,
                changeMonth: true,
		changeYear: true,
                showWeek: true,
		firstDay: 1,
                showOn: 'button',
		buttonImage: '" . $ajuste_calendario . "',
		buttonImageOnly: true
	});

  });
</script>";
    }

}
