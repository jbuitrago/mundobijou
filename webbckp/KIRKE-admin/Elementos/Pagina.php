<?php

class Elementos_Pagina {

    public function __construct() {

        $this->_cabezal();
        $this->_menu();
        $this->_menuPredefinido();
        $this->_usuarioNombreApellido();
        $this->_cuerpo();
        $this->_titulo();
        $this->_botonera2();
        $this->_version();
        $this->_errores();
        $this->_impresion();
    }

    private function _cabezal() {
        new Armado_Cabezal();
    }

    private function _menu() {
        if ($_GET['accion'] != 'Inicio') {
            new Armado_Menu();
        }
    }

    private function _menuPredefinido() {
        if ($_GET['accion'] != 'Inicio') {
            new Armado_MenuPredefinido();
        }
    }

    private function _titulo() {
        new Armado_Titulo();
    }

    private function _usuarioNombreApellido() {
        new Armado_UsuarioNombreApellido();
    }

    private function _cuerpo() {
        new Armado_Cuerpo();
    }

    private function _botonera2() {
        $botonera2 = new Armado_Botonera2();
        $botonera2->get();
    }

    private function _version() {
        new Armado_Version();
    }

    private function _errores() {
        new Armado_Errores();
    }

    private function _impresion() {

        $impresion = new Armado_Plantilla();
        echo $impresion->imprimir();
    }

}
