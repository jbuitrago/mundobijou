<?php

class Componentes_SeleccionRadio_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;                // metodo a llamar
    private $_dcp = array(); // parametros del componente
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
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden"';
        }else{
            $ocultar_celulares = '';
        }
        
        return Armado_RegistroListadoCabezal::armado($this->_dcp['cp_id'], $this->_dcp['tb_campo'], $this->_dcp['idioma_' . Generales_Idioma::obtener()], $ocultar_celulares);
    }

    private function _registroListadoCuerpo() {

        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden"';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'">' . $this->_valor['valor'] . '</td>';
    }

    private function _registroListadoPie() {
        if (!isset($this->_dcp['ocultar_celulares'])) {
            return '<td class="columna kk_resp_hidden">&nbsp;</td>';
        }
        return false;
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {

            $mostrar = $this->_mostrarValor($this->_valor, 's');
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
                $valor = $_COOKIE[hash('sha256', Inicio::confVars('basedatos') . '_' . Inicio::usuario('id') . "_cp-puvc_" . $this->_idComponente)];
            } else {
                $valor = '';
            }

            $this->_valor = $valor;

            $mostrar = '            <script type="text/javascript">
               $(document).ready(function(){
                $( "#mostrar_' . $this->_dcp['cp_id'] . '" ).load( "index.php?kk_generar=3&componente=SeleccionRadio&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '&tipo=alta&valor=' . $this->_valor . '" );
               });
            </script>' . "\n";

            Generales_PopElementos::agregar_pop_elemento('
                var valor = $("input:radio[name=cp_' . $this->_dcp['cp_id'] . ']:checked").val();
                $( "#mostrar_' . $this->_dcp['cp_id'] . '" ).load( "index.php?kk_generar=3&componente=SeleccionRadio&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '&tipo=alta&valor=" + valor );
            ');

            return $this->_tituloYComponente($mostrar, Generales_PopElementos::control_muestra($this->_dcp['origen_tb_id'], $this->_dcp['agregar_registro']));
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

            $mostrar = '<input type="hidden" name="' . $id_campo . '" id="' . $id_campo . '" value="' . $_POST['cp_' . $this->_dcp['cp_id']] . '" />';
            $mostrar .= $this->_mostrarValorPrevia($valor);
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

            if ($valor != '') {
                $this->_valor = $valor;
            }

            $mostrar = '            <script type="text/javascript">
               $(document).ready(function(){
                $( "#mostrar_' . $this->_dcp['cp_id'] . '" ).load( "index.php?kk_generar=3&componente=SeleccionRadio&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '&tipo=modificacion&valor=' . $this->_valor . '" );
               });
            </script>' . "\n";

            Generales_PopElementos::agregar_pop_elemento('
                var valor = $("input:radio[name=cp_' . $this->_dcp['cp_id'] . ']:checked").val();
                $( "#mostrar_' . $this->_dcp['cp_id'] . '" ).load( "index.php?kk_generar=3&componente=SeleccionRadio&archivo=RegistroVer.php&id_cp=' . $this->_dcp['cp_id'] . '&tipo=modificacion&valor=" + valor );
            ');

            return $this->_tituloYComponente($mostrar, Generales_PopElementos::control_muestra($this->_dcp['origen_tb_id'], $this->_dcp['agregar_registro']));
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
            $mostrar .= $this->_mostrarValorPrevia($valor);
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
                <td>' . $this->_despelgableLink($valor, 'valor_' . $this->_dcp['cp_id'], '', $style) . '</td>
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
        // identifica si tiene mascara o filtro

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
            Generales_PopElementos::agregar_pop_elemento(' ');
            $plantilla['link_agregar_registro'] = Generales_PopElementos::armar_link($this->_dcp['origen_tb_id']);
        }

        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _despelgableLink($valor, $nombre_filtro = null, $alta = null, $estilo = '') {

        if ($alta == 'alta') {
            $alta = true;
        } else {
            $alta = false;
        }

        if ($this->_dcp['origen_tb_prefijo']) {
            $tb_nombre = $this->_dcp['origen_tb_prefijo'] . "_" . $this->_dcp['origen_tb_nombre'];
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

            $consulta->orden($tb_nombre, 'orden');
            $matriz_links = $consulta->realizarConsulta();
        }

        if ($nombre_filtro == null) {
            $nombre_filtro = 'cp_' . $this->_dcp['cp_id'];
        }

        if (is_array($matriz_links)) {
            if (Armado_LinkADestino::armadoVolver()) {
                Armado_LinkADestino::armadoActual();
                $id_link_filtro = Armado_LinkADestino::armadoIdLinkFiltro();
            } else {
                $id_link_filtro = '';
            }

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

            // permite seleccionar un valor cuando el grupo esta filtrado
            $seleccionado = '';
            if ($this->_dcp['seleccionar_alta'] == 's') {
                $select_link = '<div  id="' . $nombre_filtro . '" ' . $estilo . '>';
                foreach ($matriz_links as $linea) {
                    // selecciona el valor anterior en la edicion
                    if ($linea["id_" . $tb_nombre] == $valor) {
                        $seleccionado = 'checked="checked"';
                        // selecciona el valor del grupo filtrado ver 'armado_link_a_destino'
                    } elseif (isset($id_link_filtro) && ( $linea["id_" . $tb_nombre] == $id_link_filtro )) {
                        $seleccionado = 'checked="checked"';
                    } elseif ($alta == true) {
                        $seleccionado = 'checked="checked"';
                        $alta = false;
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

                    $select_link .= '<input name="' . $nombre_filtro . '" type="radio" value="' . $linea["id_" . $tb_nombre] . '" ' . $seleccionado . ' />' . $texto . '<br />';
                    $seleccionado = '';
                }
                $select_link .= '</div>';

                return $select_link;
                // NO permite seleccionar un valor cuando el grupo esta filtrado
            } else {
                foreach ($matriz_links as $linea) {
                    // selecciona el valor anterior en la edicion
                    if ($linea["id_" . $tb_nombre] == $valor) {
                        $seleccionado = $linea[$tb_campo];
                        $seleccionado .= '<input type="hidden" name="' . $nombre_filtro . '" id="' . $nombre_filtro . '" value="' . $linea["id_" . $tb_nombre] . '" />';
                        // selecciona el valor del grupo filtrado ver 'armado_link_a_destino'
                    } elseif ($id_link_filtro && ( $linea["id_" . $tb_nombre] == $id_link_filtro )) {
                        $seleccionado = $linea[$tb_campo];
                        $seleccionado .= '<input type="hidden" name="' . $nombre_filtro . '" id="' . $nombre_filtro . '" value="' . $linea["id_" . $tb_nombre] . '" />';
                    }
                }
                return $seleccionado;
            }
        } else {
            return '{TR|o_la_tabla_origen_no_contiene_datos_o_no_existe}';
        }
    }

    private function _mostrarValor($id_origen, $con_link, $texto = null) {
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

        if ($con_link == 's') {
            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'accion' => '45', 'id_tabla' => $this->_dcp['origen_tb_id'], 'id_tabla_registro' => $valor), 's');
            return '<a href="' . $link . '" target="_top">' . $texto . '</a><br>';
        } else {
            if (isset($tb_campo) && isset($valor_campo[0][$tb_campo])) {

                $texto = $valor_campo[0][$tb_campo];

                if (isset($this->_dcp['separador_del_campo_1']) && ($this->_dcp['separador_del_campo_1'] != '')) {
                    $texto .= $this->_dcp['separador_del_campo_1'];
                }

                if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
                    $texto .= $valor_campo[0][$this->_dcp['nombre_del_campo_1']];
                }

                if (isset($this->_dcp['separador_del_campo_2']) && ($this->_dcp['separador_del_campo_2'] != '')) {
                    $texto .= $this->_dcp['separador_del_campo_2'];
                }

                if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
                    $texto .= $valor_campo[0][$this->_dcp['nombre_del_campo_2']];
                }

                return $texto;
            } else {
                return false;
            }
        }
    }

    private function _mostrarValorPrevia($id_origen) {
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

        if (isset($this->_dcp['separador_del_campo_1']) && ($this->_dcp['separador_del_campo_1'] != '')) {
            $texto .= $this->_dcp['separador_del_campo_1'];
        }

        if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_1'] != '')) {
            $texto .= $valor_campo[0][$this->_dcp['nombre_del_campo_1']];
        }

        if (isset($this->_dcp['separador_del_campo_2']) && ($this->_dcp['separador_del_campo_2'] != '')) {
            $texto .= $this->_dcp['separador_del_campo_2'];
        }

        if (isset($this->_dcp['nombre_del_campo_1']) && ($this->_dcp['nombre_del_campo_2'] != '')) {
            $texto .= $valor_campo[0][$this->_dcp['nombre_del_campo_2']];
        }

        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'accion' => '45', 'id_tabla' => $this->_dcp['origen_tb_id'], 'id_tabla_registro' => $this->_valor), 's');
        return $texto . '<br>';
    }

    private function _consultaAjax() {

        $valor = explode('_', $this->_valor);

        if (is_array($valor) && ($valor[1] == 'alta')) {
            echo $this->_despelgableLink($valor[0], '', 'alta');
        } else {
            echo $this->_despelgableLink($valor[0]);
        }
    }

}
