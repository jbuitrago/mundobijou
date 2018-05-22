<?php

class Elementos_Desarrollos extends Armado_Plantilla {

    public function __construct() {

        $this->_cabezal();
        $this->_menu();
        $this->_menuPredefinido();
        $this->_usuarioNombreApellido();
        $this->_cuerpo();
        $this->_version();
        $this->_pie();
        $this->_errores();
        $this->_impresion();
    }

    private function _cabezal() {
        new Armado_Cabezal();
    }

    private function _menu() {
        if ($_GET['accion'] != 'inicio') {
            new Armado_Menu();
        }
    }

    private function _menuPredefinido() {
        if ($_GET['accion'] != 'inicio') {
            new Armado_MenuPredefinido();
        }
    }

    private function _usuarioNombreApellido() {
        new Armado_UsuarioNombreApellido();
    }

    private function _cuerpo() {

        // armado de cabeceras generales
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('general'));
        require_once Inicio::path() . '/DesarrollosSistema/_sistema/index.php';
        $this->_armadoPlantillaSet('cuerpo', $plantilla_seccion);
    }

    private function _Botonera2() {
        new Armado_Botonera2();
    }
    
    private function _version() {
        new Armado_Version();
    }

    private function _pie() {
        new Armado_Pie();
    }
    
    private function _errores() {
        new Armado_Errores();
    }

    private function _impresion() {

        $impresion = new Armado_plantilla();
        echo $impresion->imprimir();
    }

}

