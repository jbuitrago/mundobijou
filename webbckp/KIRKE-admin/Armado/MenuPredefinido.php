<?php

class Armado_MenuPredefinido extends Armado_Plantilla {

    public function __construct() {

        if (!isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['menu_predefinido'])) {
            $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['menu_predefinido'] = $this->_armarMenu();
        }
        $this->_armadoPlantillaSet('menu_predefinido', $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['menu_predefinido']);
    }

    private function _armarMenu() {

        $url_base = './index.php?';

        $nivel1 = '';
        $nivel2 = '';
        $cont = 1;

        if (( Inicio::usuario('tipo') == 'administrador general' ) || ( Inicio::usuario('tipo') == 'administrador de usuarios' )) {

            if (Inicio::usuario('tipo') == 'administrador general') {

                $nivel1 .= '<div class="mn_est_t" id_num="' . $cont . '">{TR|s_paginas}</div>' . "\n";
                $nivel2 .= '<div id="mn_est_' . $cont . '" class="mn_est_n2_int">' . "\n";
                $nivel2 .= $this->_tituloAccion('{TR|s_paginas}', 30, 2);
                $nivel2 .= $this->_tituloAccion('{TR|s_menus}', 15, 2);
                $nivel2 .= $this->_tituloAccion('{TR|s_prefijos}', 35, 2);
                $nivel2 .= '</div>' . "\n";
                $cont++;

                $nivel1 .= '<div class="mn_est_t" id_num="' . $cont . '">{TR|s_usuarios}</div>' . "\n";
                $nivel2 .= '<div id="mn_est_' . $cont . '" class="mn_est_n2_int">' . "\n";
                $nivel2 .= $this->_tituloAccion('{TR|s_usuarios}', 57, 2);
                $nivel2 .= $this->_tituloAccion('{TR|s_roles}', 49, 2);
                $nivel2 .= '</div>' . "\n";
                $cont++;
            } elseif (Inicio::usuario('tipo') == 'administrador de usuarios') {

                $nivel1 .= $this->_tituloAccion('{TR|s_usuarios}', 57, 1);
            }

            $nivel1 .= '<div class="mn_est_t" id_num="' . $cont . '">{TR|s_administrar}</div>' . "\n";
            $nivel2 .= '<div id="mn_est_' . $cont . '" class="mn_est_n2_int">' . "\n";
            $nivel2 .= $this->_tituloAccion('{TR|s_datos_personales}', 13, 2);
            $nivel2 .= $this->_tituloAccion('{TR|s_configuracion_personal}', 10, 2);
            $nivel2 .= $this->_tituloAccion('{TR|s_usuario_y_clave}', 63, 2);
            $nivel2 .= '</div>' . "\n";
            $cont++;
        } else {

            $nivel1 .= $this->_tituloAccion('{TR|s_datos_personales}', 13, 1);
            $nivel1 .= $this->_tituloAccion('{TR|s_configuracion_personal}', 10, 1);
            $nivel1 .= $this->_tituloAccion('{TR|s_usuario_y_clave}', 63, 1);
        }

        $nivel1 .= $this->_tituloAccion('{TR|s_salir}', 53, 1);

        $menu = '<div class="mn_est_n1">' . $nivel1 . '</div>' . "\n";
        $menu .= '<div class="mn_est_n2">' . $nivel2 . '</div>' . "\n";

        return $menu;
    }

    private function _tituloAccion($titulo, $accion, $nivel) {

        if ($nivel == 1) {
            $estilo = 'mn_est_t';
        } else {
            $estilo = 'mn_est_link';
        }

        $url = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '0', 'accion' => $accion), 's');
        return '<a href="' . $url . '" class="' . $estilo . '">' . $titulo . '</a>' . "\n";
    }

}
