<?php

class DesarrollosSistema_TablaColumnas {

    static private $_mostrarOrden = true;

    public function texto($tabla, $valor, $id, $alinear, $tb_campo, $otro_valor = null) {
        return '<td class="columna" ' . $alinear . '>&nbsp;' . $valor . '</td>';
    }

    public function id_registro($tabla, $valor, $id, $alinear, $tb_campo, $otro_valor = null) {
        return '<td class="columna" ' . $alinear . '>&nbsp;' . $valor . '</td>';
    }

    public function link($tabla, $valor, $id, $alinear, $tb_campo, $valores) {
        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $valores['kk_generar'], 'kk_desarrollo' => $valores['kk_desarrollo'], '0' => $valores['0'], 'kk_tabla' => $tabla, 'kk_id' => $id), 's');
        return '<td class="columna" ' . $alinear . '><a href="' . $link . '" class="bt_tb_link">&nbsp;' . $valor . '</a></td>';
    }

    public function orden($tabla, $orden_act, $identificador, $id_orden_act, $orden_sig, $id_orden_sig, $orden_ant, $id_orden_ant) {

        $armar_lineas = '';

        $ver = DesarrollosSistema_FiltrosOrden::obtenerOrden($tabla);

        if ((count($ver) != 0) && ($ver[0]['identificador'] != $identificador)) {
            self::$_mostrarOrden = false;
        }

        if (self::$_mostrarOrden) {
            if ($orden_sig != '') {
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET['0'], 'kk_tabla' => $tabla, 'kk_orden_act' => $orden_act, 'kk_id_orden_act' => $id_orden_act, 'kk_orden_sig' => $orden_sig, 'kk_id_orden_sig' => $id_orden_sig), 's');
                $armar_lineas = '<a href="' . $link . '" class="bt_tb_bajar"></a>';
            } else {
                $armar_lineas = '<div class="bt_tb_bajar_nulo"></div>';
            }
            if ($orden_ant != '') {
                $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET['0'], 'kk_tabla' => $tabla, 'kk_orden_act' => $orden_act, 'kk_id_orden_act' => $id_orden_act, 'kk_orden_ant' => $orden_ant, 'kk_id_orden_ant' => $id_orden_ant), 's');
                $armar_lineas .= '<a href="' . $link . '" class="bt_tb_subir"></a>';
            } else {
                $armar_lineas .= '<div class="bt_tb_subir_nulo">&nbsp;</div>';
            }
        } else {
            $armar_lineas = $orden_act;
        }

        return '<td class="columna_predefinida">' . $armar_lineas . '</td>';
    }

    public function editar($tabla, $id, $pagina) {

        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $pagina, 'kk_tabla' => $tabla, 'kk_id' => $id), 's');
        return '<td class="columna_predefinida"><a href="' . $link . '" class="bt_tb_editar"></a></td>';
    }

    public function siguiente($tabla, $id, $pagina) {

        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $pagina, 'kk_tabla' => $tabla, 'kk_id' => $id), 's');
        return '<td class="columna_predefinida"><a href="' . $link . '" class="bt_tb_siguiente"></a></td>';
    }

    public function nuevo($tabla, $id, $pagina) {

        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $pagina, 'kk_tabla' => $tabla, 'kk_id' => $id), 's');
        return '<td class="columna_predefinida"><a href="' . $link . '" class="bt_tb_nuevo"></a></td>';
    }

    public function ver($tabla, $id, $pagina) {

        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $pagina, 'kk_tabla' => $tabla, 'kk_id' => $id), 's');
        return '<td class="columna_predefinida"><a href="' . $link . '" class="bt_tb_ver"></a></td>';
    }

    public function eliminar($tabla, $id) {
        return '<td class="columna_predefinida"><div mensaje="{TR|o_confirma_la_eliminacion}" url_id="' . $id . '" class="bt_tb_eliminar"></div></td>';
    }

}
