<?php

class Acciones_PrefijoAlta extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        if (isset($_GET['id_tabla'])) {
            $id_tabla = $_GET['id_tabla'];
        } else {
            $id_tabla = '';
        }

        $parametros = array('kk_generar' => '0', 'accion' => '34', 'id_tabla' => $id_tabla);
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '35');
        $armado_botonera->armar('volver', $parametros);

        return Armado_PlantillasInternas::acciones(basename(__FILE__));
    }

}

