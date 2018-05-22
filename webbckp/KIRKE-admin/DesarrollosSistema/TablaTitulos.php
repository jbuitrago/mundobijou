<?php

class DesarrollosSistema_TablaTitulos {

    Static public $titulosOrden = false;
    static public $tituloPredIdentificador;
    static public $tituloPredOrden;

    public function texto($tabla, $titulo, $identificador, $alinear) {
        return '<td class="columna" '.$alinear.'><span class="titulo_tabla">' . $this->armado($tabla, $titulo, $identificador, $alinear) . '</span></td>';
    }

    public function id_registro($tabla, $titulo, $identificador, $alinear) {
        return '<td class="columna" '.$alinear.'><span class="titulo_tabla">' . $this->armado($tabla, $titulo, $identificador, $alinear) . '</span></td>';
    }

    public function link($tabla, $titulo, $identificador, $alinear) {
        return '<td class="columna" '.$alinear.'><span class="titulo_tabla">' . $this->armado($tabla, $titulo, $identificador, $alinear) . '</span></td>';
    }

    public function orden($tabla, $titulo, $identificador, $alinear) {
        return '<td class="columna_ancho_orden"><span class="titulo_tabla">' . $this->armado($tabla, $titulo, $identificador, $alinear) . '</span></td>';
    }

    public function editar($tabla, $titulo, $identificador, $alinear) {
        return '<td class="columna_ancho_editar">' . $titulo . '</td>';
    }

    public function siguiente($tabla, $titulo, $identificador, $alinear) {
        return '<td class="columna_ancho_siguiente">' . $titulo . '</td>';
    }

    public function nuevo($tabla, $titulo, $identificador, $alinear) {
        return '<td class="columna_ancho_nuevo">' . $titulo . '</td>';
    }

    public function ver($tabla, $titulo, $identificador, $alinear) {
        return '<td class="columna_ancho_ver">' . $titulo . '</td>';
    }

    public function eliminar($tabla, $titulo, $identificador, $alinear) {
        return '<td class="columna_ancho_eliminar">' . $titulo . '</td>';
    }

    private function armado($tabla, $titulo, $identificador, $alinear) {

        if (self::$titulosOrden == false) {
            return $titulo;
        }

        $orden = '';
        $num_orden = '';
        $img_orden = '';

        if (isset($_GET['kk_tabla'])) {
            $get_variables = $_GET;
            unset($get_variables['kk_tabla']);
        }else{
            $get_variables = $_GET;
        }
        
        unset($get_variables['id_menu_link']);

        $variables_link = array_merge($get_variables, array('kk_tabla' => $tabla, 'kk_orden_listado' => $identificador, 'kk_orden_listado_dir' => '1'));

        $link = './index.php?' . Generales_VariablesGet::armar($variables_link, 's');

        $obtenerOrden = DesarrollosSistema_FiltrosOrden::obtenerOrden($tabla);

        if (($obtenerOrden !== false) && is_array($obtenerOrden)) {

            foreach ($obtenerOrden as $key => $valores) {

                if ($identificador == $valores['identificador']) {

                    $orden = DesarrollosSistema_FiltrosOrden::$ordenValoresNum[$valores['parametro']];
                    $img_orden = $orden;
                    if (($key == 0) && (DesarrollosSistema_FiltrosOrden::$mismoCampoOrden) && ($orden == 1)) {
                        $orden = 2;
                        $img_orden = 1;
                    } elseif (($key == 0) && (DesarrollosSistema_FiltrosOrden::$mismoCampoOrden) && ($orden == 2)) {
                        $orden = 1;
                        $img_orden = 2;
                    } elseif (($key == 0) && (!DesarrollosSistema_FiltrosOrden::$mismoCampoOrden) && ($orden == 1)) {
                        $orden = 2;
                        $img_orden = 1;
                    }

                    $variables_link = array_merge($get_variables, array('kk_tabla' => $tabla, 'kk_orden_listado' => $identificador, 'kk_orden_listado_dir' => $orden));

                    $link = './index.php?' . Generales_VariablesGet::armar($variables_link, 's');

                    $num_orden = ' <span class="titulo_tabla_link_orden">[' . ($key + 1) . ']</span> ';

                    $img_orden = '<img border="0" src="./Plantillas/' . Inicio::confVars('plantilla') . '/img/tit_tabla_orden_' . $img_orden . '.png" align="absmiddle">';
                }
            }
        }

        return '<a href="' . $link . '" class="titulo_tabla_link">' . $img_orden . $titulo . $num_orden . '</a>';
    }

}
