<?php

class Armado_FiltrosPaginas extends Generales_FiltrosOrden {

    static public function armado() {

        // esta consulta es necesaria, ya que se deben recorrer todos los componentes
        // tengan parametros de filtro existente o no
        $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos', $_GET['id_tabla']);

        if (is_array($matriz_componentes)) {

            $armado_botonera = new Armado_Botonera('filtros');

            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
            $boton = $armado_botonera->armar('filtrar', $parametros, '', 's', true);
            $link_formulario = './index.php?' . Generales_VariablesGet::armar($parametros, 's');

            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'eliminar_filtros' => 'si');
            $boton_borrar = $armado_botonera->armar('eliminar', $parametros, 'eliminar_filtros', 's', true);

            $filtros = '<div class="filtros">';
            $filtros .= '<form enctype="multipart/form-data" name="form_filtro" id="form_filtro" target="_self" method="post" action="' . $link_formulario . '">';
            $filtros .= '<input name="kk_control_filtros" type="hidden" value="s" />';
            $filtros .= '
                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                    <tr class="linea_titulo_filtro">
                        <td class="linea_titulo_izq_filtro"></td>
                        <td class="titulo_tabla filtro_columna_campo">{TR|t_campo}</td>
                        <td class="titulo_tabla filtro_columna_aplicar">{TR|t_filtro_a_aplicar}</td>
                        <td class="titulo_tabla filtro_columna_valor">{TR|t_valor}</td>
                        <td class="titulo_tabla filtro_columna_eliminar"></td>
                        <td class="linea_titulo_der_filtro"></td>
                    </tr>
            ';

            $contador_linea = 1;
            $valor = '';
            foreach ($matriz_componentes as $k => $dcp) {
                $valor_comp_nvo = '';
                $cont = 0;
                if (
                        isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post']) && is_array($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post'])
                ) {
                    $valor_comp = '';
                    foreach ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post'] as $id => $valor) {
                        if ($valor['id'] == $dcp['cp_id']) {
                            if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post'][$id]['valor'])) {
                                $valor_comp[$cont]['valor'] = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post'][$id]['valor'];
                            }
                            if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post'][$id]['parametro'])) {
                                $valor_comp[$cont]['condicion'] = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][self::$_nivelActual]['filtros']['filtros_post'][$id]['parametro'];
                                $cont++;
                            }
                        }
                    }
                    if ((count($valor_comp) == 1) && isset($valor_comp[0]['condicion']) && ($valor_comp[0]['condicion'] != '')) {
                        $valor_comp_nvo['valor'] = $valor_comp[0]['valor'];
                        $valor_comp_nvo['condicion'] = $valor_comp[0]['condicion'];
                    } else {
                        $valor_comp_nvo = $valor_comp;
                    }
                }

                $filtros_elemento = Generales_LlamadoAComponentesYTraduccion::armar('RegistroVer', 'registroFiltroCampo', $valor_comp_nvo, $dcp, $dcp['cp_nombre'], $dcp['cp_id']);

                if ($filtros_elemento !== false) {

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
            $filtros .= '<div class="contenido_separador_color"></div>';
            $filtros .= '<div class="botonera">' . $boton . $boton_borrar['contenido'] . '</div>';
            $filtros .= '</form>';
            $filtros .= '</div>';

            return $filtros;
        } else {

            return false;
        }
    }

}
