<?php

class Generales_PopElementos extends Armado_Plantilla {

    static private $_pop_script = '';

    static function agregar_pop_elemento($elemento) {
        self::$_pop_script .= "\n" . '                ' . $elemento;
    }

    static function asignar_plantilla() {
        if (self::$_pop_script != '') {
            Armado_Cabeceras::colorbox(self::$_pop_script);
            return true;
        }
        return false;
    }

    static function armar_link($id_tabla) {
        return '<a class="iframe_colorbox" href="./index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'accion' => '37', 'id_tabla' => $id_tabla, 'tipo_pagina' => 'pop'), 's') . '"> {TR|o_agregar_registro}</a>';
    }

    static function control_muestra($origen_tb_id, $agregar_registro) {
        $link = 'no';
        if (($agregar_registro == 's') && !isset($_GET['tipo_pagina'])) {
            $validacion_link = new Seguridad_UsuarioValidacion();
            if ($validacion_link->consultaElemento('pagina', $origen_tb_id, 'datos', 'no') == 'datos') {
                $link = 'si';
            }
        }
        return $link;
    }

}
