<?php

class Componentes_IdRegistro_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_colorFondo = 1;
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
        return false;
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
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'">' . $this->_valor . '&nbsp;&nbsp;</td>';
    }

    private function _registroListadoPie() {
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'" class="columna_ancho_15">&nbsp;</td>';
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {

            return $this->_tituloYComponente();
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
    
    private function _tituloYComponente() {

        $plantilla['idioma'] = $this->_dcp['idioma_' . Generales_Idioma::obtener()];
        if ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['tipo'] == 'administrador general') {
            $plantilla['campo_nombre'] = '<br /><span class="texto_nombre_campos">( ' . $this->_dcp['tb_campo'] . ' ) </span><span class="texto_id_campos"> ( ' . $this->_dcp['cp_id'] . ' )</span>';
        } else {
            $plantilla['campo_nombre'] = '';
        }
        $plantilla['mostrar'] = $this->_idRegistro;
        
        if(Armado_DesplegableOcultos::mostrarOcultos()===true){
            $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
        }
        
        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

}
