<?php

class Armado_FiltrosMenuPaginas {

    static public function armado($id_pagina, $menu_link_parametros) {

        // esta consulta es necesaria, ya que se deben recorrer todos los componentes
        // tengan parametros de filtro existente o no
        $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos', $id_pagina);

        if (is_array($matriz_componentes)) {
            $filtros = '
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr class="linea_titulo">
                        <td class="linea_titulo_izq"></td>
                        <td class="titulo_tabla filtro_columna_campo">{TR|t_campo}</td>
                        <td class="titulo_tabla filtro_columna_aplicar">{TR|t_filtro_a_aplicar}</td>
                        <td class="titulo_tabla filtro_columna_valor">{TR|t_valor}</td>
                        <td class="titulo_tabla filtro_columna_eliminar"></td>
                        <td class="linea_titulo_der"></td>
                    </tr>
            ';

            $filtros .= '<tr>';

            $contador_linea = 1;
            $contador_orden_campos = 0;

            foreach ($matriz_componentes as $k => $dcp) {

                // para que fuerce la muestra de los filtros
                $dcp['filtrar'] = 's';
                // fuerza que no genere problemas los campos obligatorios
                $dcp['obligatorio'] = 'nulo';

                $valor = '';

                if (is_array($menu_link_parametros)) {

                    $id_varias_cond = 0;
                    foreach ($menu_link_parametros as $k2 => $v) {
                        if (($v['tipo'] == 'filtro') && ($dcp['cp_id'] == $v['id'])) {
                            if (
                                    (isset($menu_link_parametros[$k2 + 1]['id']) && ($menu_link_parametros[$k2 + 1]['tipo'] == 'filtro') && ($menu_link_parametros[$k2 + 1]['id'] == $v['id'])) ||
                                    (
                                    (isset($menu_link_parametros[$k2 - 1]['id']) && ($menu_link_parametros[$k2 - 1]['tipo'] == 'filtro') && ($menu_link_parametros[$k2 - 1]['id'] == $v['id'])) &&
                                    (!isset($menu_link_parametros[$k2 + 1]['id']) || (($menu_link_parametros[$k2 + 1]['id'] != $v['id']) && ($menu_link_parametros[$k2 + 1]['tipo'] == 'filtro')))
                                    )
                            ) {
                                $valor[$id_varias_cond]['valor'] = $v['valor'];
                                $valor[$id_varias_cond]['condicion'] = $v['parametro'];
                                $id_varias_cond++;
                            } else {
                                $valor['valor'] = $v['valor'];
                                $valor['condicion'] = $v['parametro'];
                            }
                        }
                    }
                }

                $filtros_elemento = Generales_LlamadoAComponentesYTraduccion::armar('RegistroVer', 'registroFiltroCampo', $valor, $dcp, $dcp['cp_nombre'], $dcp['cp_id']);

                if ($filtros_elemento != false) {

                    $color_linea = ($contador_linea % 2) + 1;
                    $filtros .= '<tr class="filtros_fila_' . $color_linea . '">';
                    $filtros .= '<td></td>';
                    $filtros .= $filtros_elemento;
                    $filtros .= '<td></td>';
                    $filtros .= '</tr>';

                    $contador_linea++;
                }

                if (isset($dcp['tb_campo']) && ($dcp['tb_campo'] != '')) {
                    $orden_campos[$contador_orden_campos]['tb_campo'] = $dcp['tb_campo'];
                    $orden_campos[$contador_orden_campos]['tb_campo_nombre'] = $dcp['idioma_' . Generales_Idioma::obtener()];
                    $orden_campos[$contador_orden_campos]['cp_id'] = $dcp['cp_id'];
                    $contador_orden_campos++;
                }
            }

            $filtros .= '</table>';

            $parametros['filtros'] = $filtros;
        } else {
            $parametros['filtros'] = NULL;
        }

        // armado del orden
        $ocultar_campos['si'] = '';
        $ocultar_campos['no'] = ' checked="checked"';
        if (is_array($menu_link_parametros)) {
            $orden_id = 1;
            foreach ($menu_link_parametros as $v) {
                $tipo = $v['tipo'];
                if ($tipo == 'orden') {
                    if ($v['parametro'] == 'ascendente') {
                        $orden['asc' . $orden_id] = ' checked="checked"';
                        $orden['desc' . $orden_id] = '';
                        $orden['campos_desp' . $orden_id] = $v['valor'];
                    } elseif ($v['parametro'] == 'descendente') {
                        $orden['asc' . $orden_id] = '';
                        $orden['desc' . $orden_id] = ' checked="checked"';
                        $orden['campos_desp' . $orden_id] = $v['valor'];
                    }
                    $orden_id++;
                } elseif ($tipo == 'filtro_general') {
                    if ($v['parametro'] == 'si') {
                        $filtro_general_si = true;
                    } elseif ($v['parametro'] == 'no') {
                        $filtro_general_si = false;
                    }
                }
            }
            if ($tipo == 'ocultar_campos') {
                if ($v['parametro'] == 'si') {
                    $ocultar_campos['si'] = ' checked="checked"';
                    $ocultar_campos['no'] = '';
                } elseif ($v['parametro'] == 'no') {
                    $ocultar_campos['si'] = '';
                    $ocultar_campos['no'] = ' checked="checked"';
                }
            }
            if (!isset($orden['asc1'])) {
                $orden['asc1'] = ' checked="checked"';
                $orden['desc1'] = '';
                $orden['campos_desp1'] = '';
            }
            if (!isset($orden['asc2'])) {
                $orden['asc2'] = ' checked="checked"';
                $orden['desc2'] = '';
                $orden['campos_desp2'] = '';
            }
            if (!isset($orden['asc3'])) {
                $orden['asc3'] = ' checked="checked"';
                $orden['desc3'] = '';
                $orden['campos_desp3'] = '';
            }
            if (!isset($filtro_general_si)) {
                $filtro_general_si = true;
            }
        } else {
            $orden['asc1'] = ' checked="checked"';
            $orden['desc1'] = '';
            $orden['asc2'] = ' checked="checked"';
            $orden['desc2'] = '';
            $orden['asc3'] = ' checked="checked"';
            $orden['desc3'] = '';
            $filtro_general_si = true;
        }
        $parametros['orden'] = $orden;
        if (!isset($filtro_general_si) || ($filtro_general_si === true)) {
            $filtro_general['si'] = ' checked="checked"';
            $filtro_general['no'] = '';
        } else {
            $filtro_general['si'] = '';
            $filtro_general['no'] = ' checked="checked"';
        }
        $parametros['filtro_general'] = $filtro_general;
        $parametros['ocultar_campos'] = $ocultar_campos;

        if (isset($orden_campos) && is_array($orden_campos)) {
            for ($i = 1; $i <= 3; $i++) {
                $desplegable = '<select id="orden_campo_' . $i . '" name="orden_campo_' . $i . '">';
                $desplegable .= '<option value="">seleccione una opcion</option>';
                $seleccionado = '';
                if (
                        (
                        ($i == 1) &&
                        !isset($orden['campos_desp' . $i])
                        ) ||
                        (isset($orden['campos_desp' . $i]) && ($orden['campos_desp' . $i] == 'orden'))
                ) {
                    $seleccionado = '  selected="selected"';
                }
                $desplegable .= '<option value="orden" ' . $seleccionado . '>Orden</option>';
                foreach ($orden_campos as $v) {
                    if (!isset($orden['campos_desp' . $i]) || ($v['tb_campo'] != $orden['campos_desp' . $i])) {
                        $seleccionado = '';
                    } else {
                        $seleccionado = '  selected="selected"';
                    }
                    $desplegable .= '<option value="' . $v['cp_id'] . '|' . $v['tb_campo'] . '" ' . $seleccionado . '>' . $v['tb_campo_nombre'] . '</option>';
                }
                $desplegable .= '</select>';
                $parametros['desplegable' . $i] = $desplegable;
            }
        }

        return $parametros;
    }

}
