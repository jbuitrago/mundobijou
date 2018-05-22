<?php

class Armado_TablaCabezales {

    public function texto($titulo, $ancho, $extra = null) {
        if ($ancho != '') {
            $ancho = 'class="columna_ancho_' . $ancho . '"';
        }
        return '<td class="columna" ' . $ancho . '><span class="titulo_tabla">' . $titulo . '</span></td>';
    }

    public function id_registro($titulo, $ancho, $extra = null) {
        if ($ancho != '') {
            $ancho = 'class="columna_ancho_id' . $ancho . '"';
        }
        return '<td class="columna" ' . $ancho . '><span class="titulo_tabla">' . $titulo . '</span></td>';
    }

    public function link($titulo, $ancho, $extra = null) {
        if ($ancho != '') {
            $ancho = 'class="columna_ancho_' . $ancho . '"';
        }
        return '<td class="columna" ' . $ancho . '><span class="titulo_tabla">' . $titulo . '</span></td>';
    }

    public function opcion($titulo, $ancho, $extra = null) {
        if ($ancho != '') {
            $ancho = 'class="columna_ancho_' . $ancho . '"';
        }
        return '<td class="columna" ' . $ancho . '><span class="titulo_tabla">' . $titulo . '</span></td>';
    }

    public function orden($titulo, $ancho) {

        $orden = '';
        $num_orden = '';
        $img_orden = '';

        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'orden_listado' => 'orden', 'orden_listado_dir' => '1'), 's');

        if ((Generales_FiltrosOrden::obtenerOrden() !== false) && (is_array(Generales_FiltrosOrden::obtenerOrden()))) {

            foreach (Generales_FiltrosOrden::obtenerOrden() as $key => $valores) {

                if ($valores['valor'] == 'orden') {

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

                    $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'orden_listado' => 'orden', 'orden_listado_dir' => $orden), 's');

                    $num_orden = ' <span class="titulo_tabla_link_orden">[' . ($key + 1) . ']</span> ';

                    $img_orden = '<img border="0" src="./Plantillas/' . Inicio::confVars('plantilla') . '/img/tit_tabla_orden_' . $img_orden . '.png" align="absmiddle">';
                }
            }
        }

        $valor = '<a href="' . $link . '" class="titulo_tabla_link">' . $img_orden . $titulo . $num_orden . '</a>';

        return '<td class="columna_ancho_orden kk_resp_hidden"><span class="titulo_tabla">' . $valor . '</span></td>';
    }

    public function editar($titulo, $ancho) {
        return '<td class="columna_ancho_editar"><span>' . $titulo . '</span></td>';
    }

    public function siguiente($titulo, $ancho) {
        return '<td class="columna_ancho_siguiente">' . $titulo . '</td>';
    }

    public function nuevo($titulo, $ancho) {
        return '<td class="columna_ancho_nuevo">' . $titulo . '</td>';
    }

    public function ver($titulo, $ancho) {
        return '<td class="columna_ancho_ver"><span>' . $titulo . '</span></td>';
    }

    public function eliminar($titulo, $ancho) {
        return '<td class="columna_ancho_eliminar"><span>' . $titulo . '</span></td>';
    }

    public function linkDestinoIdCp($titulo, $ancho, $extra = null) {
        return '<td class="columna"><span class="titulo_tabla">' . $titulo . '</span></td>';
    }

}
