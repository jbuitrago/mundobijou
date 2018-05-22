<?php

class Acciones_ComponenteAlta extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '3', 'id_tabla' => $_GET['id_tabla'], 'componente' => $_GET['componente']);
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '5', 'id_tabla' => $_GET['id_tabla']);
        $armado_botonera->armar('volver', $parametros);

        return Generales_LlamadoAComponentesYTraduccion::armar('ComponenteVer', 'componenteAlta', '', '', $_GET['componente']);
    }

}

