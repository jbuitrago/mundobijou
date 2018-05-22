<?php

class Acciones_Bienvenida extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        // en este caso solo verifico si tiene una sesion
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion->consultaUsuarioSesion();

        Generales_EliminarTemporales::eliminar();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('general'));

        return Armado_PlantillasInternas::acciones(basename(__FILE__));
    }

}
