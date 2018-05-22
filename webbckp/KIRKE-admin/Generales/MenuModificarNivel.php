<?php

class Generales_MenuModificarNivel {

    private static $_id_menu;
    private static $_tabla_nombre;
    private static $_nivel;
    private static $_datos;
    private static $_menu_sistema;

    static public function nivel_subir($tabla_nombre, $id_menu, $menu_sistema = false) {

        self::$_menu_sistema = $menu_sistema;
        self::$_id_menu = $id_menu;
        self::$_tabla_nombre = $tabla_nombre;

        $control = self::obtener_nivel_datos();
        if ($control !== false) {
            self::subir_nivel();
        }
    }

    static public function nivel_bajar($tabla_nombre, $id_menu, $menu_sistema = false) {

        self::$_menu_sistema = $menu_sistema;
        self::$_id_menu = $id_menu;
        self::$_tabla_nombre = $tabla_nombre;

        $control = self::obtener_nivel_datos();
        if ($control !== false) {
            self::bajar_nivel();
        }
    }

    static private function obtener_nivel_datos() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas(self::$_tabla_nombre);
        $consulta->campos(self::$_tabla_nombre, 'nivel1_orden');
        $consulta->campos(self::$_tabla_nombre, 'nivel2_orden');
        $consulta->campos(self::$_tabla_nombre, 'nivel3_orden');
        $consulta->campos(self::$_tabla_nombre, 'nivel4_orden');
        if (self::$_menu_sistema === false) {
            $consulta->campos(self::$_tabla_nombre, 'nivel5_orden');
            $consulta->campos(self::$_tabla_nombre, 'nivel6_orden');
            $consulta->campos(self::$_tabla_nombre, 'nivel7_orden');
            $consulta->campos(self::$_tabla_nombre, 'nivel8_orden');
            $consulta->campos(self::$_tabla_nombre, 'nivel9_orden');
            $consulta->campos(self::$_tabla_nombre, 'nivel10_orden');
        }
        if (self::$_menu_sistema === false) {
            $consulta->condiciones('', self::$_tabla_nombre, 'id_' . self::$_tabla_nombre, 'iguales', '', '', self::$_id_menu);
        }else{
            $consulta->condiciones('', self::$_tabla_nombre, 'id_menu', 'iguales', '', '', self::$_id_menu);
        }
        $consulta->orden(self::$_tabla_nombre, 'nivel1_orden');
        $consulta->orden(self::$_tabla_nombre, 'nivel2_orden');
        $consulta->orden(self::$_tabla_nombre, 'nivel3_orden');
        $consulta->orden(self::$_tabla_nombre, 'nivel4_orden');
        if (self::$_menu_sistema === false) {
            $consulta->orden(self::$_tabla_nombre, 'nivel5_orden');
            $consulta->orden(self::$_tabla_nombre, 'nivel6_orden');
            $consulta->orden(self::$_tabla_nombre, 'nivel7_orden');
            $consulta->orden(self::$_tabla_nombre, 'nivel8_orden');
            $consulta->orden(self::$_tabla_nombre, 'nivel9_orden');
            $consulta->orden(self::$_tabla_nombre, 'nivel10_orden');
        }
        //$consulta->verConsulta();
        $valor = $consulta->realizarConsulta();
        $valor = $valor[0];

        if (is_array($valor)) {
            self::$_datos = $valor;
        } else {
            self::$_datos = false;
        }

        if (self::$_menu_sistema === false) {
            self::$_nivel = Generales_MenuObtenerNivel::nivel($valor['nivel1_orden'], $valor['nivel2_orden'], $valor['nivel3_orden'], $valor['nivel4_orden'], $valor['nivel5_orden'], $valor['nivel6_orden'], $valor['nivel7_orden'], $valor['nivel8_orden'], $valor['nivel9_orden'], $valor['nivel10_orden']);
        }else{
            self::$_nivel = Generales_MenuObtenerNivel::nivel($valor['nivel1_orden'], $valor['nivel2_orden'], $valor['nivel3_orden'], $valor['nivel4_orden']);
        }
    }

    static private function subir_nivel() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas(self::$_tabla_nombre);
        $consulta->campos(self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden');
        $consulta->condiciones('', self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', 'iguales', '', '', (self::$_datos['nivel' . self::$_nivel . '_orden'] - 1));
        if (self::$_nivel > 1) {
            for ($i = 1; $i < self::$_nivel; $i++) {
                $consulta->condiciones('y', self::$_tabla_nombre, 'nivel' . $i . '_orden', 'iguales', '', '', self::$_datos['nivel' . $i . '_orden']);
            }
        }
        //$consulta->verConsulta();
        $control = $consulta->realizarConsulta();

        if (!is_array($control)) {
            return false;
        }

        $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
        $consulta->tabla(self::$_tabla_nombre);
        $consulta->campoValor(self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', '0');
        $consulta->condiciones('', self::$_tabla_nombre, 'nivel1_orden', 'iguales', '', '', self::$_datos['nivel1_orden']);
        if (self::$_nivel > 1) {
            for ($i = 2; $i <= self::$_nivel; $i++) {
                $consulta->condiciones('y', self::$_tabla_nombre, 'nivel' . $i . '_orden', 'iguales', '', '', self::$_datos['nivel' . $i . '_orden']);
            }
        }
        //$consulta->verConsulta();
        $consulta->realizarConsulta();

        $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
        $consulta->tabla(self::$_tabla_nombre);
        $consulta->campoValor(self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', self::$_datos['nivel' . self::$_nivel . '_orden']);
        $consulta->condiciones('', self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', 'iguales', '', '', (self::$_datos['nivel' . self::$_nivel . '_orden'] - 1));
        if (self::$_nivel > 1) {
            for ($i = 1; $i < self::$_nivel; $i++) {
                $consulta->condiciones('y', self::$_tabla_nombre, 'nivel' . $i . '_orden', 'iguales', '', '', self::$_datos['nivel' . $i . '_orden']);
            }
        }
        //$consulta->verConsulta();
        $consulta->realizarConsulta();

        $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
        $consulta->tabla(self::$_tabla_nombre);
        $consulta->campoValor(self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', (self::$_datos['nivel' . self::$_nivel . '_orden'] - 1));
        $consulta->condiciones('', self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', 'iguales', '', '', '0');
        if (self::$_nivel > 1) {
            for ($i = 1; $i < (self::$_nivel - 1); $i++) {
                $consulta->condiciones('y', self::$_tabla_nombre, 'nivel' . $i . '_orden', 'iguales', '', '', self::$_datos['nivel' . $i . '_orden']);
            }
        }
        //$consulta->verConsulta();
        $consulta->realizarConsulta();
    }

    static private function bajar_nivel() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas(self::$_tabla_nombre);
        $consulta->campos(self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden');
        $consulta->condiciones('', self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', 'iguales', '', '', (self::$_datos['nivel' . self::$_nivel . '_orden'] + 1));
        if (self::$_nivel > 1) {
            for ($i = 1; $i < self::$_nivel; $i++) {
                $consulta->condiciones('y', self::$_tabla_nombre, 'nivel' . $i . '_orden', 'iguales', '', '', self::$_datos['nivel' . $i . '_orden']);
            }
        }
        //$consulta->verConsulta();
        $control = $consulta->realizarConsulta();

        if (!is_array($control)) {
            return false;
        }

        $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
        $consulta->tabla(self::$_tabla_nombre);
        $consulta->campoValor(self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', '0');
        $consulta->condiciones('', self::$_tabla_nombre, 'nivel1_orden', 'iguales', '', '', self::$_datos['nivel1_orden']);
        if (self::$_nivel > 1) {
            for ($i = 2; $i <= self::$_nivel; $i++) {
                $consulta->condiciones('y', self::$_tabla_nombre, 'nivel' . $i . '_orden', 'iguales', '', '', self::$_datos['nivel' . $i . '_orden']);
            }
        }
        //$consulta->verConsulta();
        $consulta->realizarConsulta();

        $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
        $consulta->tabla(self::$_tabla_nombre);
        $consulta->campoValor(self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', self::$_datos['nivel' . self::$_nivel . '_orden']);
        $consulta->condiciones('', self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', 'iguales', '', '', (self::$_datos['nivel' . self::$_nivel . '_orden'] + 1));
        if (self::$_nivel > 1) {
            for ($i = 1; $i < self::$_nivel; $i++) {
                $consulta->condiciones('y', self::$_tabla_nombre, 'nivel' . $i . '_orden', 'iguales', '', '', self::$_datos['nivel' . $i . '_orden']);
            }
        }
        //$consulta->verConsulta();
        $consulta->realizarConsulta();

        $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
        $consulta->tabla(self::$_tabla_nombre);
        $consulta->campoValor(self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', (self::$_datos['nivel' . self::$_nivel . '_orden'] + 1));
        $consulta->condiciones('', self::$_tabla_nombre, 'nivel' . self::$_nivel . '_orden', 'iguales', '', '', '0');
        if (self::$_nivel > 1) {
            for ($i = 1; $i < (self::$_nivel - 1); $i++) {
                $consulta->condiciones('y', self::$_tabla_nombre, 'nivel' . $i . '_orden', 'iguales', '', '', self::$_datos['nivel' . $i . '_orden']);
            }
        }
        //$consulta->verConsulta();
        $consulta->realizarConsulta();
    }

}
