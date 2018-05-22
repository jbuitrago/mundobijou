<?php

class Armado_Menu extends Armado_Plantilla {

    private $_menusTitulos;
    private $_menus;
    private $_nivelDinamico = 0;

    public function __construct() {
        if (!isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['menu'])) {
            $this->_menusTitulos = $this->_armarTitulos(Consultas_Menu::RegistroConsultaTodos(__FILE__, __LINE__));
            $this->_menus = Consultas_Menu::RegistroConsulta(__FILE__, __LINE__);
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['menu'] = $this->_armarMenu(false);
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['menu_movil'] = $this->_armarMenu(true);
        }
        $this->_armadoPlantillaSet('menu', $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['menu']);
        $this->_armadoPlantillaSet('menu_movil', $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['menu_movil']);
    }

    static public function reinciarMenu() {
        unset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['menu']);
    }

    private function _armarTitulos($menusTitulos) {
        $titulos = array();
        if (is_array($menusTitulos)) {
            foreach ($menusTitulos as $titulo) {
                if ($titulo['nivel4_orden'] != '') {
                    $titulos[$titulo['nivel1_orden']][$titulo['nivel2_orden']][$titulo['nivel3_orden']][$titulo['nivel4_orden']]['id_menu'] = $titulo['id_menu'];
                    $titulos[$titulo['nivel1_orden']][$titulo['nivel2_orden']][$titulo['nivel3_orden']][$titulo['nivel4_orden']]['menu_nombre'] = $titulo['menu_nombre'];
                } elseif ($titulo['nivel3_orden'] != '') {
                    $titulos[$titulo['nivel1_orden']][$titulo['nivel2_orden']][$titulo['nivel3_orden']]['id_menu'] = $titulo['id_menu'];
                    $titulos[$titulo['nivel1_orden']][$titulo['nivel2_orden']][$titulo['nivel3_orden']]['menu_nombre'] = $titulo['menu_nombre'];
                } elseif ($titulo['nivel2_orden'] != '') {
                    $titulos[$titulo['nivel1_orden']][$titulo['nivel2_orden']]['id_menu'] = $titulo['id_menu'];
                    $titulos[$titulo['nivel1_orden']][$titulo['nivel2_orden']]['menu_nombre'] = $titulo['menu_nombre'];
                } elseif ($titulo['nivel1_orden'] != '') {
                    $titulos[$titulo['nivel1_orden']]['id_menu'] = $titulo['id_menu'];
                    $titulos[$titulo['nivel1_orden']]['menu_nombre'] = $titulo['menu_nombre'];
                }
            }
        }
        return $titulos;
    }

    private function _armarMenu($movil) {

        if (!$movil) {
            $menu_armar = '<ul class="dropdown">' . "\n";
            $li = 'li';
        } else {
            $menu_armar = '<div class="menu_movil">' . "\n";
            $li = 'div';
        }

        $nivel_ant = array('', '', '', '');

        if (is_array($this->_menus)) {
            foreach ($this->_menus as $linea) {

                $nivel_act = array($linea['nivel1_orden'], $linea['nivel2_orden'], $linea['nivel3_orden'], $linea['nivel4_orden']);

                if ($movil) {
                    if ($nivel_act[1] == '') {
                        $nivel_menu = 1;
                    } elseif ($nivel_act[2] == '') {
                        $nivel_menu = 2;
                    } elseif ($nivel_act[3] == '') {
                        $nivel_menu = 3;
                    } else {
                        $nivel_menu = 4;
                    }
                    $nivel_clase_link = ' class="menu_m_' . $nivel_menu . '_link"';
                    $nivel_clase = ' class="menu_m_' . $nivel_menu . '_menu"';
                } else {
                    $nivel_clase_link = '';
                    $nivel_clase = '';
                }

                $menu_armar .= $this->_nivelIr($nivel_ant, $nivel_act, $movil, $nivel_clase);
                $nivel_ant = $nivel_act;

                if ($linea['elemento'] == 'pagina') {
                    $url = array('kk_generar' => 0, 'accion' => '41', 'id_tabla' => $linea['id_elemento'], 'id_menu_link' => $linea['id_menu_link']);
                } elseif ($linea['elemento'] == 'informe') {
                    $informe = Generales_InformeNombre::obtener($linea['id_elemento']);
                    $url = array('kk_generar' => 4, 'accion' => '41', 'informe' => $informe, 'pagina' => 'Inicio', 'id_menu_link' => $linea['id_menu_link']);
                } elseif ($linea['elemento'] == 'desarrollo') {
                    $desarrollo = explode(':', $linea['id_elemento']);
                    $url = array('kk_generar' => 6, 'kk_desarrollo' => $desarrollo[0], '0' => $desarrollo[1], 'id_menu_link' => $linea['id_menu_link']);
                }
                // se deben concatenar todos los links del nivel
                $menu_armar .= '<' . $li . $nivel_clase_link . '><a href="./index.php?' . Generales_VariablesGet::armar($url, 's') . '">' . $linea['menu_link_nombre'] . '</a></' . $li . '>' . "\n";
            }
            // cierre del menu
            $menu_armar .= $this->_nivelIr($nivel_act, array('', '', '', ''), $movil, '');
        }
        if (!$movil) {
            $menu_armar .= '</ul>' . "\n";
        } else {
            $menu_armar .= '</div>' . "\n";
        }

        return $menu_armar;
    }

    private function _nivelIr($nivel_ant, $nivel_act, $movil, $nivel_clase) {

        $subir = '';
        $bajar = '';

        if ($movil) {
            $li = 'div';
            $ul_cierre = '';
            $ul = '';
            $a = '';
            $a_cierre = '';
            $div_cierre = '</div>';
        } else {
            $li = 'li';
            $ul_cierre = '</ul></li>' . "\n";
            $ul = '<ul>';
            $a = '<a>';
            $a_cierre = '</a>';
            $div_cierre = '';
        }

        for ($i = 0; $i < 4; ++$i) {
            if (($nivel_ant[$i] != '') && ($nivel_ant[$i] != $nivel_act[$i])) {
                $bajar .= $ul_cierre;
            }
            if (($nivel_act[$i] != '') && ($nivel_ant[$i] != $nivel_act[$i])) {
                $menu_nombre = '';
                if ($i == 0) {
                    if (isset($this->_menusTitulos[$nivel_act[0]]['menu_nombre'])) {
                        $menu_nombre = $this->_menusTitulos[$nivel_act[0]]['menu_nombre'];
                    }
                    $subir .= '<' . $li . $nivel_clase . '>' . $a . $menu_nombre . $a_cierre . $ul . $div_cierre . "\n";
                } elseif ($i == 1) {
                    if (isset($this->_menusTitulos[$nivel_act[0]][$nivel_act[1]]['menu_nombre'])) {
                        $menu_nombre = $this->_menusTitulos[$nivel_act[0]][$nivel_act[1]]['menu_nombre'];
                    }
                    $subir .= '<' . $li . $nivel_clase . '>' . $a . $menu_nombre . $a_cierre . $ul . $div_cierre . "\n";
                } elseif ($i == 2) {
                    if (isset($this->_menusTitulos[$nivel_act[0]][$nivel_act[1]][$nivel_act[2]]['menu_nombre'])) {
                        $menu_nombre = $this->_menusTitulos[$nivel_act[0]][$nivel_act[1]][$nivel_act[2]]['menu_nombre'];
                    }
                    $subir .= '<' . $li . $nivel_clase . '>' . $a . $menu_nombre . $a_cierre . $ul . $div_cierre . "\n";
                } elseif ($i == 3) {
                    if (isset($this->_menusTitulos[$nivel_act[0]][$nivel_act[1]][$nivel_act[2]][$nivel_act[3]]['menu_nombre'])) {
                        $menu_nombre = $this->_menusTitulos[$nivel_act[0]][$nivel_act[1]][$nivel_act[2]][$nivel_act[3]]['menu_nombre'];
                    }
                    $subir .= '<' . $li . $nivel_clase . '>' . $a . $menu_nombre . $a_cierre . $ul . $div_cierre . "\n";
                }
            }
        }

        return $bajar . $subir;
    }

}
