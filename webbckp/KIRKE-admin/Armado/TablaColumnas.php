<?php

class Armado_TablaColumnas {

    static private $_mostrarOrden = true;
    static private $_linkDestinoCantidad;

    static public function texto($valor, $variable_link, $accion, $tb_campo, $otro_valor = null, $extra = null) {
        return '<td class="columna ' . $extra . '">&nbsp;' . $valor . '</td>';
    }

    static public function id_registro($valor, $variable_link, $accion, $tb_campo, $otro_valor = null, $extra = null) {
        return '<td class="columna ' . $extra . '">&nbsp;' . $valor . '</td>';
    }

    static public function link($valor, $variable_link, $accion, $tb_campo, $otro_valor, $extra = null) {
        return '<td class="columna ' . $extra . '"><div url="' . $_GET['kk_generar'] . ',' . $accion . ',' . $otro_valor['pagina'] . ',' . $otro_valor['tb_id'] . ',' . $otro_valor['valor_sistema'] . '"  class="bt_tb_link">&nbsp;' . $valor . '</div></td>';
    }

    static public function opcion($valor, $variable_link, $accion, $tb_campo, $otro_valor = null, $extra = null) {
        // valores '', '0', '1'
        return '<td class="columna ' . $extra . '"><div class="bt_tb_opcion' . $valor . '"></td>';
    }

    static public function orden($valor, $variable_link, $accion, $tb_campo, $otro_valor, $extra = null) {

        if ($extra !== null) {
            $extra = ',' . $extra;
        } else {
            $extra = '';
        }

        $armar_lineas = '';

        $ver = Generales_FiltrosOrden::obtenerOrden();

        if ((count($ver) != 0) && ($ver[0]['valor'] != 'orden')) {
            self::$_mostrarOrden = false;
        }

        if (self::$_mostrarOrden) {

            if (isset($otro_valor['orden_sig'])) {

                //orden actual
                $orden_act = $otro_valor['orden_act'];
                $id_orden_act = $otro_valor['id_orden_act'];

                //orden siguiente
                $orden_sig = $otro_valor['orden_sig'];
                $id_orden_sig = $otro_valor['id_orden_sig'];

                $armar_lineas = '<div class="bt_tb_bajar kk_resp_hidden" url="' . $_GET['kk_generar'] . ',' . $_GET['id_tabla'] . ',' . $accion . ',' . $orden_act . ',' . $id_orden_act . ',' . $orden_sig . ',' . $id_orden_sig . $extra . '"></div>';
            } else {
                $armar_lineas = '<div class="bt_tb_bajar_nulo"></div>';
            }

            if (isset($otro_valor['orden_ant'])) {

                //orden actual
                $orden_act = $otro_valor['orden_act'];
                $id_orden_act = $otro_valor['id_orden_act'];

                //orden anterior
                $orden_ant = $otro_valor['orden_ant'];
                $id_orden_ant = $otro_valor['id_orden_ant'];

                $armar_lineas .= '<div class="bt_tb_subir" url="' . $_GET['kk_generar'] . ',' . $_GET['id_tabla'] . ',' . $accion . ',' . $orden_act . ',' . $id_orden_act . ',' . $orden_ant . ',' . $id_orden_ant . $extra . '"></div>';
            } else {
                $armar_lineas .= '<div class="bt_tb_subir_nulo">&nbsp;</div>';
            }
        } else {

            $armar_lineas = $otro_valor['orden_act'];
        }

        return '<td class="columna_predefinida kk_resp_hidden">' . $armar_lineas . '</td>';
    }

    static public function editar($valor, $variable_link, $accion, $tb_campo, $otro_valor = null, $extra = null) {

        if ($extra !== null) {
            $extra = ',' . $extra;
        } else {
            $extra = '';
        }

        // si existe 'variable_link' cambia el nombre de la variable
        // se usa en la tabla registros ya que el nombre del id_xxx
        // cambia segun la tabla
        // $variable_link = $variable_link;
        if ($valor != '') {
            if ($variable_link == '') {
                $variable_link = $tb_campo;
            }
            return '<td class="columna_predefinida"><div url="' . $_GET['kk_generar'] . ',' . $_GET['id_tabla'] . ',' . $variable_link . ',' . $valor . ',' . $accion . $extra . '" class="bt_tb_editar"></div></td>';
        } else {
            return '<td class="columna_predefinida"></td>';
        }
    }

    static public function siguiente($valor, $variable_link, $accion, $tb_campo, $otro_valor = null, $extra = null) {
        // si existe 'variable_link' cambia el nombre de la variable
        // se usa en la tabla registros ya que el nombre del id_xxx
        // cambia segun la tabla
        // $variable_link = $variable_link;
        if ($variable_link == '') {
            $variable_link = $tb_campo;
        }
        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'id_tabla' => $_GET['id_tabla'], $variable_link => $valor, 'accion' => $accion), 's');
        return '<td class="columna_predefinida"><a href="' . $link . '" class="bt_tb_siguiente"></a></td>';
    }

    static public function nuevo($valor, $variable_link, $accion, $tb_campo, $otro_valor = null, $extra = null) {
        // si existe 'variable_link' cambia el nombre de la variable
        // se usa en la tabla registros ya que el nombre del id_xxx
        // cambia segun la tabla
        // $variable_link = $variable_link;
        if ($variable_link == '') {
            $variable_link = $tb_campo;
        }
        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'id_tabla' => $_GET['id_tabla'], $variable_link => $valor, 'accion' => $accion), 's');
        return '<td class="columna_predefinida"><a href="' . $link . '" class="bt_tb_nuevo"></a></td>';
    }

    static public function ver($valor, $variable_link, $accion, $tb_campo, $otro_valor = null, $extra = null) {
        return '<td class="columna_predefinida"><div url="' . $_GET['kk_generar'] . ',' . $_GET['id_tabla'] . ',' . $variable_link . ',' . $valor . ',' . $accion . '" class="bt_tb_ver"></div></td>';
    }

    static public function eliminar($valor, $variable_link, $accion, $tb_campo, $otro_valor = null, $extra = null) {

        if ($extra !== null) {
            $extra = ',' . $extra;
        } else {
            $extra = '';
        }

        // si existe 'variable_link' cambia el nombre de la variable
        // se usa en la tabla registros ya que el nombre del id_xxx
        // cambia segun la tabla
        // $variable_link = $variable_link;
        if ($variable_link == '') {
            $variable_link = $tb_campo;
        }
        return '<td class="columna_predefinida"><div mensaje="{TR|o_confirma_la_eliminacion}" url="' . $_GET['kk_generar'] . ',' . $_GET['id_tabla'] . ',' . $variable_link . ',' . $valor . ',' . $accion . $extra . '" class="bt_tb_eliminar"></div></td>';
    }

    static public function linkDestinoIdCp($valor, $variable_link, $accion, $tb_campo, $otro_valor, $extra = null) {
        $campo = $otro_valor['tabla_relacionada'] . '_' . $tb_campo;
        if (!isset(self::$_linkDestinoCantidad[$campo]) && !isset($otro_valor['link_a_grupo_cantidad'])) {
            self::$_linkDestinoCantidad[$campo] = Armado_LinkADestino::armadoSiguienteCantidad($otro_valor['tabla_relacionada'], $tb_campo);
        }

        if (isset($otro_valor['link_a_grupo_cantidad'])) {
            $valor_cantidad = null;
        } elseif (isset(self::$_linkDestinoCantidad[$campo][$valor])) {
            $valor_cantidad = self::$_linkDestinoCantidad[$campo][$valor];
        } else {
            $valor_cantidad = 0;
        }

        $contenido = Armado_LinkADestino::armadoSiguiente($otro_valor['id_link_componente'], $valor, 41, $valor_cantidad);

        return '<td class="columna_predefinida ' . $extra . '">' . $contenido . '</td>';
    }

}
