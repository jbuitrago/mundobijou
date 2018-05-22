<?php

class Armado_MenuLinkParametro {

    static private $_arrayFiltros;
    static private $_arrayOrden;

    static public function mostrarOpciones($id_pagina, $menu_link_parametros) {

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

            foreach ($matriz_componentes as $k => $dcp) {

                $valor = '';

                if (is_array($menu_link_parametros)) {

                    foreach ($menu_link_parametros as $k2 => $v) {

                        if ($dcp['cp_id'] == $v['id']) {
                            $valor['tipo'] = $v['tipo'];
                            $valor['parametro'] = $v['parametro'];
                            $valor['valor'] = $v['valor'];
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
            }

            $filtros .= '</table>';

            $parametros['filtros'] = $filtros;
        } else {
            $parametros['filtros'] = NULL;
        }

        // armado del orden
        if (is_array($menu_link_parametros)) {
            foreach ($menu_link_parametros as $k => $v) {
                $tipo = $v['tipo'];
                if ($tipo == 'orden') {
                    if ($v['parametro'] == 'ascendente') {
                        $orden['asc'] = ' checked="checked"';
                        $orden['desc'] = '';
                    } elseif ($v['parametro'] == 'descendente') {
                        $orden['asc'] = '';
                        $orden['desc'] = ' checked="checked"';
                    }
                }
            }
        } else {
            $orden['asc'] = ' checked="checked"';
            $orden['desc'] = '';
        }
        $parametros['orden'] = $orden;

        return $parametros;
    }

}
