<?php

class Armado_Tabla {

    private $_campos = array();
    private $_matriz = array();
    private $_columnas;
    private $_lineas;
    private $_sinDatosPie = false;
    static private $_limiteMostrarCelulares = 2;

    public function __construct() {

        // para los listado de sistema donde no se usa el $_GET['id_tabla']
        if (!isset($_GET['id_tabla'])) {
            $_GET['id_tabla'] = '';
        }
    }

    public function armar($campos, $resultado_matriz, $elemento_inicio = false, $elementos_cantidad = false, $con_form = true) {

        $this->_campos = $campos;
        $this->_matriz = $resultado_matriz;
        $this->_columnas = count($campos);
        $this->_lineas = count($resultado_matriz);

        if ($con_form === true) {
            $armar_tabla = '<form id="tablesForm">' . "\n";
        } else {
            $armar_tabla = '';
        }
        $armar_tabla .= '<table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n\n";

        $armar_tabla .= $this->_titulos();

        if ($this->_lineas > 0) {
            $armar_tabla .= $this->_lineas($elemento_inicio, $elementos_cantidad);
        } else {
            $armar_tabla .= $this->_sinResultados();
        }

        $armar_tabla .= $this->_pie();


        $armar_tabla .= '</table>' . "\n";
        if ($con_form === true) {
            $armar_tabla .= '</form>' . "\n";
        }

        return $armar_tabla;
    }

    private function _titulos() {

        $armar_titulo = "\n";

        for ($i = 0; $i < $this->_columnas; $i++) {

            $armar_titulos = '';
            if ($this->_campos[$i]['tb_columna_tipo'] == 'componente') {

                if ($i < self::$_limiteMostrarCelulares) {
                    $this->_campos[$i]['parametros']['mostrar_celulares'] = true;
                }

                if ($i < self::$_limiteMostrarCelulares) {
                    $this->_campos[$i]['parametros']['ocultar_celulares'] = false;
                }

                $armar_titulos .= Generales_LlamadoAComponentesYTraduccion::armar('RegistroVer', 'registroListadoCabezal', '', $this->_campos[$i]['parametros'], $this->_campos[$i]['parametros']['cp_nombre']);
            } else {

                if (!isset($_GET['vista'])) {

                    $titulo = $this->_campos[$i]['tb_titulo_idioma'];
                    if (isset($this->_campos[$i]['tb_columna_ancho'])) {
                        $ancho = $this->_campos[$i]['tb_columna_ancho'];
                    } else {
                        $ancho = '';
                    }

                    $metodo = $this->_campos[$i]['tb_columna_tipo'];
                    $armado_tabla_cabezales = new Armado_TablaCabezales();
                    $armar_titulos = $armado_tabla_cabezales->$metodo($titulo, $ancho);
                } else {
                    $armar_titulos = '';
                }
            }

            $armar_titulo .= $armar_titulos . "\n";
        }

        return '<tr class="linea_titulo"><td class="linea_titulo_izq"></td>' . $armar_titulo . '<td class="linea_titulo_der"></td></tr>' . "\n\n";
    }

    private function _lineas($elemento_inicio = false, $elementos_cantidad = false) {

        $armar_lineas_predefinida = '';

        $armar_lineas = '';
        if (is_array($this->_matriz)) {

            $color_linea = 1;

            if ($elemento_inicio === false) {
                $elemento_inicio = 0;
            }

            if ($elementos_cantidad === false) {
                $elementos_cantidad = count($this->_matriz) - $elemento_inicio;
            }

            for ($i = $elemento_inicio; $i < $elementos_cantidad; $i++) {

                if (!isset($this->_matriz[$i])) {
                    break;
                }

                $linea = $this->_matriz[$i];

                for ($j = 0; $j < $this->_columnas; $j++) {

                    $valor_campo = array();
                    if ($this->_campos[$j]['tb_columna_tipo'] == 'componente') {
                        if (!isset($this->_campos[$j]['parametros']['origen_cp_id'])) {
                            if (isset($linea[$this->_campos[$j]['parametros']['tb_campo']])) {
                                $valor_campo = $linea[$this->_campos[$j]['parametros']['tb_campo']];
                            }
                        } else {
                            $valor_campo = array();
                            if (isset($linea[$this->_campos[$j]['parametros']['tb_campo']])) {
                                $valor_campo['id'] = $linea[$this->_campos[$j]['parametros']['tb_campo']];
                                $valor_campo['valor'] = $linea['kk_' . $this->_campos[$j]['parametros']['tb_campo']];
                            } else {
                                $valor_campo['id'] = '';
                                $valor_campo['valor'] = '';
                            }
                        }

                        if ($j < self::$_limiteMostrarCelulares) {
                            $this->_campos[$j]['parametros']['ocultar_celulares'] = false;
                        }

                        $registro = Generales_LlamadoAComponentesYTraduccion::armar('RegistroVer', 'registroListadoCuerpo', $valor_campo, $this->_campos[$j]['parametros'], $this->_campos[$j]['parametros']['cp_nombre'], $this->_campos[$j]['parametros']['cp_id'], $linea[$this->_campos[$j]['tb_campo_id']], true);
                    } else {

                        if (!isset($_GET['vista'])) {

                            $otro_valor = '';

                            if (isset($this->_campos[$j]['tb_campo']) && isset($linea[$this->_campos[$j]['tb_campo']])) {
                                $valor = $linea[$this->_campos[$j]['tb_campo']];
                            } else {
                                $valor = '';
                            }
                            if (isset($this->_campos[$j]['variable_link'])) {
                                $variable_link = $this->_campos[$j]['variable_link'];
                            } else {
                                $variable_link = '';
                            }
                            if (isset($this->_campos[$j]['accion'])) {
                                $accion = $this->_campos[$j]['accion'];
                            } else {
                                $accion = '';
                            }
                            if (isset($this->_campos[$j]['tb_campo'])) {
                                $tb_campo = $this->_campos[$j]['tb_campo'];
                            } else {
                                $tb_campo = '';
                            }

                            $extra = '';
                            switch ($this->_campos[$j]['tb_columna_tipo']) {
                                case 'orden':
                                    $otro_valor['orden_act'] = $this->_matriz[$i][$this->_campos[$j]['tb_campo']];
                                    $otro_valor['id_orden_act'] = $this->_matriz[$i][$this->_campos[$j]['tb_campo_id']];
                                    if (isset($this->_matriz[$i + 1])) {
                                        $otro_valor['orden_sig'] = $this->_matriz[$i + 1][$this->_campos[$j]['tb_campo']];
                                        $otro_valor['id_orden_sig'] = $this->_matriz[$i + 1][$this->_campos[$j]['tb_campo_id']];
                                    }
                                    if (isset($this->_matriz[$i - 1])) {
                                        $otro_valor['orden_ant'] = $this->_matriz[$i - 1][$this->_campos[$j]['tb_campo']];
                                        $otro_valor['id_orden_ant'] = $this->_matriz[$i - 1][$this->_campos[$j]['tb_campo_id']];
                                    }
                                    if (isset($this->_campos[$j]['extra'])) {
                                        $extra = $this->_matriz[$i][$this->_campos[$j]['extra']];
                                    }
                                    break;
                                case 'editar':
                                    if (isset($this->_campos[$j]['extra'])) {
                                        $extra = $this->_matriz[$i][$this->_campos[$j]['extra']];
                                    }
                                    break;
                                case 'eliminar':
                                    if (isset($this->_campos[$j]['extra'])) {
                                        $extra = $this->_matriz[$i][$this->_campos[$j]['extra']];
                                    }
                                    break;
                                case 'link':
                                    if (isset($this->_campos[$j]['valor_sistema'])) {
                                        $otro_valor['valor_sistema'] = $this->_campos[$j]['valor_sistema'];
                                    } else {
                                        $otro_valor['valor_sistema'] = '';
                                    }
                                    if (isset($this->_campos[$j]['id']) && isset($linea[$this->_campos[$j]['id']])) {
                                        $otro_valor['tb_id'] = $linea[$this->_campos[$j]['id']];
                                    } else {
                                        $otro_valor['tb_id'] = '';
                                    }
                                    if (isset($this->_campos[$j]['id'])) {
                                        $otro_valor['pagina'] = $this->_campos[$j]['id'];
                                    } else {
                                        $otro_valor['pagina'] = '';
                                    }
                                    break;
                                case 'linkDestinoIdCp':
                                    if (isset($this->_campos[$j]['id_link_componente'])) {
                                        $otro_valor['id_link_componente'] = $this->_campos[$j]['id_link_componente'];
                                    }
                                    if (isset($this->_campos[$j]['sufijo'])) {
                                        $tb_campo = $tb_campo . '_' . $this->_campos[$j]['sufijo'];
                                    }
                                    if (isset($this->_campos[$j]['link_a_grupo_cantidad'])) {
                                        $otro_valor['link_a_grupo_cantidad'] = $this->_campos[$j]['link_a_grupo_cantidad'];
                                    }
                                    if (isset($this->_campos[$j]['tabla_relacionada'])) {
                                        $otro_valor['tabla_relacionada'] = $this->_campos[$j]['tabla_relacionada'];
                                    }
                                    break;
                            }

                            $tb_columna_tipo = $this->_campos[$j]['tb_columna_tipo'];

                            $registro = Armado_TablaColumnas::$tb_columna_tipo($valor, $variable_link, $accion, $tb_campo, $otro_valor, $extra);
                        } else {

                            $registro = '';
                        }
                    }

                    $armar_lineas_predefinida .= $registro;
                }


                $armar_lineas .= '<tr class="tbla_linea_' . (($color_linea % 2) + 1) . '"><td class="tbla_linea_izq"></td>' . $armar_lineas_predefinida . '<td></td></tr>' . "\n";

                $armar_lineas_predefinida = '';

                $color_linea++;
            }
        }

        return $armar_lineas . "\n";
    }

    private function _sinResultados() {
        return '<div id="tabla_sin_resultados">{TR|s_sin_elementos_cargados}</div>' . "\n";
    }

    private function _pie() {

        $armar_pie = '<tr class="linea_pie">';
        $armar_pie .= '<td class="linea_pie_izq">';
        $armar_pie .= '</td>' . "\n";

        for ($i = 0; $i < $this->_columnas; $i++) {

            if ($this->_campos[$i]['tb_columna_tipo'] == 'componente') {

                if ($i < self::$_limiteMostrarCelulares) {
                    $this->_campos[$i]['parametros']['ocultar_celulares'] = false;
                }

                $armar_pie_cont = Generales_LlamadoAComponentesYTraduccion::armar('RegistroVer', 'registroListadoPie', '', $this->_campos[$i]['parametros'], $this->_campos[$i]['parametros']['cp_nombre']);
                if ($armar_pie_cont !== false) {
                    $armar_pie .= $armar_pie_cont;
                } else {
                    $armar_pie .= '<td class="columna">&nbsp;</td>';
                }
            } else {
                if (!isset($_GET['vista'])) {
                    if (!$this->_sinDatosPie) {
                        $pie = $this->_campos[$i]['tb_titulo_idioma'];
                    } else {
                        $pie = '';
                    }

                    $metodo = $this->_campos[$i]['tb_columna_tipo'];

                    $armado_tabla_pie = new Armado_TablaPie();
                    $armar_pie .= $armado_tabla_pie->$metodo($pie);
                }
            }
        }

        $armar_pie .= '<td class="linea_pie_der">';
        $armar_pie .= '</td>';
        $armar_pie .= '</tr>' . "\n";

        return $armar_pie;
    }

    public function sinDatosPie() {

        $this->_sinDatosPie = true;
    }

}
