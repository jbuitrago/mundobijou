<?php

class Acciones_Menu extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();
        
        if (isset($_GET['menu_bajar'])) {
            Generales_MenuModificarNivel::nivel_bajar('kirke_menu', $_GET['menu_bajar'], true);
            Armado_Menu::reinciarMenu();
            $armado_botonera = new Armado_Botonera();
            $parametros = array('kk_generar' => '0', 'accion' => '15');
            $armado_botonera->armar('redirigir', $parametros);
        } elseif (isset($_GET['menu_subir'])) {
            Generales_MenuModificarNivel::nivel_subir('kirke_menu', $_GET['menu_subir'], true);
            Armado_Menu::reinciarMenu();
            $armado_botonera = new Armado_Botonera();
            $parametros = array('kk_generar' => '0', 'accion' => '15');
            $armado_botonera->armar('redirigir', $parametros);
        }

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '16');
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '15');
        $armado_botonera->armar('volver', $parametros);
        
        Armado_Cabeceras::dynatree();
        
        $plantilla['menu_arbol'] = Armado_ArbolMenu::get();
        $plantilla['menu_etiqueta'] = Armado_EtiquetaIdiomas::armar('etiqueta');

        return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
    }

}

