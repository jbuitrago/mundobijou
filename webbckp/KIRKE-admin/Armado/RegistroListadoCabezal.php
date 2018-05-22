<?php

class Armado_RegistroListadoCabezal {

    static public function armado($cp_id, $tb_campo, $idioma, $ocultar_celulares = null, $align = null) {

        $num_orden = '';
        $img_orden = '';

        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'orden_listado' => $cp_id, 'orden_listado_dir' => '1'), 's');

        $obtenerOrden = Generales_FiltrosOrden::obtenerOrden();

        if (($obtenerOrden !== false) && is_array($obtenerOrden)) {

            foreach ($obtenerOrden as $key => $valores) {

                if ($tb_campo == $valores['valor']) {

                    $orden = Generales_FiltrosOrden::$ordenValoresNum[$valores['parametro']];
                    $img_orden = $orden;
                    if (($key == 0) && (Generales_FiltrosOrden::$mismoCampoOrden) && ($orden == 1)) {
                        $orden = 2;
                        $img_orden = 1;
                    } elseif (($key == 0) && (Generales_FiltrosOrden::$mismoCampoOrden) && ($orden == 2)) {
                        $orden = 1;
                        $img_orden = 2;
                    } elseif (($key == 0) && (!Generales_FiltrosOrden::$mismoCampoOrden) && ($orden == 1)) {
                        $orden = 2;
                        $img_orden = 1;
                    }

                    $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'orden_listado' => $valores['cp_id'], 'orden_listado_dir' => $orden), 's');

                    $num_orden = ' <span class="titulo_tabla_link_orden">[' . ($key + 1) . ']</span> ';

                    $img_orden = '<img border="0" src="./Plantillas/' . Inicio::confVars('plantilla') . '/img/tit_tabla_orden_' . $img_orden . '.png" align="absmiddle">';
                }
            }
        }

        if ($align == 'right') {
            $align = 'align="right"';
        }

        return '<td class="columna '.$ocultar_celulares.'" ' . $align . '><div class="tabla_ocultar_sobrante">&nbsp;<a href="' . $link . '" class="titulo_tabla_link">' . $img_orden . $idioma . $num_orden . '</a></div></td>';
    }

}
