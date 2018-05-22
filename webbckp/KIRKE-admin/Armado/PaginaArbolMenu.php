<?php

class Armado_PaginaArbolMenu {

    static public function _armarMenu($menus, $numero_niveles, $niveles_habilitados) {
        $menu_armar = '';
        $nivel_ant = array('', '', '', '');

        if (is_array($menus)) {
            foreach ($menus as $id => $linea) {
                $nivel_act = array($linea['nivel1_orden'], $linea['nivel2_orden'], $linea['nivel3_orden'], $linea['nivel4_orden'], $linea['nivel5_orden'], $linea['nivel6_orden'], $linea['nivel7_orden'], $linea['nivel8_orden'], $linea['nivel9_orden'], $linea['nivel10_orden']);
                $nivel = Generales_MenuObtenerNivel::nivel($linea['nivel1_orden'], $linea['nivel2_orden'], $linea['nivel3_orden'], $linea['nivel4_orden'], $linea['nivel5_orden'], $linea['nivel6_orden'], $linea['nivel7_orden'], $linea['nivel8_orden'], $linea['nivel9_orden'], $linea['nivel10_orden']);

                $linea_siguiente = false;
                if (isset($menus[$id + 1])) {
                    $linea_control = array_slice($linea, 1, ($nivel));
                    $linea_control['nivel' . $nivel . '_orden'] = $linea_control['nivel' . $nivel . '_orden'] + 1;
                    $id_2 = $id + 1;
                    do {
                        $menus_control = array_slice($menus[$id_2], 1, $nivel);
                        $resultado_control = array_diff_assoc($linea_control, $menus_control);
                        if (count($resultado_control) == 0) {
                            $linea_siguiente = $menus[$id_2];
                            break;
                        }
                        $id_2++;
                    } while (isset($menus[$id_2]));
                }

                $menu_armar .= self::_nivelIr($numero_niveles, $niveles_habilitados, $nivel_ant, $nivel_act, $linea['id_menu'], $linea['menu_nombre'], $linea['cantidad'], $nivel, $linea_siguiente);
                $nivel_ant = $nivel_act;
            }
            // cierre del menu
            $menu_armar .= self::_cerrarMenu($nivel_act);
        }
        return $menu_armar;
    }

    static private function _nivelIr($numero_niveles, $niveles_habilitados, $nivel_ant, $nivel_act, $id_menu, $menu_nombre, $cantidad, $nivel, $linea_siguiente) {
        $subir = '';
        $bajar = '';
        for ($i = 0; $i < $numero_niveles; ++$i) {
            if (isset($nivel_ant[$i]) && ($nivel_ant[$i] != '') && ($nivel_act[$i] == '')) {
                $bajar .= '</ul>' . "\n";
            }
            if (($nivel_act[$i] != '') && ($nivel_ant[$i] == '')) {
                $subir .= '<ul>' . "\n";
            }
        }

        if ($niveles_habilitados <= $nivel) {
            $cantidad_subir = ' <span class="tree_cantidad_gris">[' . $cantidad . ']</span>';
        } elseif (($niveles_habilitados > $nivel) && ($numero_niveles != $nivel)) {
            $cantidad_subir = '';
            $cantidad = 'n';
        } else {
            $cantidad_subir = ' <span class="tree_cantidad_gris">[' . $cantidad . ']</span>';
        }

        if ($nivel_act[$nivel - 1] > 1) {
            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'valor_sistema' => '0', 'menu_subir' => $id_menu);
            $link = './index.php?' . Generales_VariablesGet::armar($parametros, 's');
            $menu_subir = '<span onclick="menu_subir_bajar(\'' . $link . '\')"><img src="./Plantillas/' . Inicio::confVars('plantilla') . '/img/menu_subir.png" class="menu_subir" style="width:7px;" /></span>';
        } else {
            $menu_subir = '<span ><img src="./Plantillas/' . Inicio::confVars('plantilla') . '/img/menu_nada.png" style="width:7px;" /></span>';
        }

        $menu_bajar = '<span ><img src="./Plantillas/' . Inicio::confVars('plantilla') . '/img/menu_nada.png" style="width:7px;margin-right:3px;" /></span>';
        if ($linea_siguiente !== false) {
            if (
                    isset($linea_siguiente['nivel' . $nivel . '_orden']) && ($nivel_act[$nivel - 1] == ($linea_siguiente['nivel' . $nivel . '_orden'] - 1))
            ) {
                $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'valor_sistema' => '0', 'menu_bajar' => $id_menu);
                $link = './index.php?' . Generales_VariablesGet::armar($parametros, 's');
                $menu_bajar = '<span onclick="menu_subir_bajar(\'' . $link . '\')"><img src="./Plantillas/' . Inicio::confVars('plantilla') . '/img/menu_bajar.png" class="menu_bajar" class="menu_subir" style="width:7px;margin-right:3px" /></span>';
            }
        }

        $subir .= '<li id="' . $id_menu . '"  class="folder" data="cantidad: \'' . $cantidad . '\', nivel: \'' . $nivel . '\'"><span><span><span style="background-color: #F0F0F0;">' . $menu_subir . $menu_bajar . '</span>&nbsp;' . $menu_nombre . '</span>' . $cantidad_subir . '  </span>' . "\n";

        return $bajar . $subir;
    }

    static private function _cerrarMenu($nivel_act) {
        if ($nivel_act[9] != '') {
            return '</ul></ul></ul></ul></ul></ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[8] != '') {
            return '</ul></ul></ul></ul></ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[7] != '') {
            return '</ul></ul></ul></ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[6] != '') {
            return '</ul></ul></ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[5] != '') {
            return '</ul></ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[4] != '') {
            return '</ul></ul></ul></ul></ul>';
        } elseif ($nivel_act[3] != '') {
            return '</ul></ul></ul></ul>';
        } elseif ($nivel_act[2] != '') {
            return '</ul></ul></ul>';
        } elseif ($nivel_act[1] != '') {
            return '</ul></ul>';
        } elseif ($nivel_act[0] != '') {
            return '</ul>';
        }
    }

}
