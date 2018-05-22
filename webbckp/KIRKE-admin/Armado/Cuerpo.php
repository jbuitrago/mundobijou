<?php

class Armado_Cuerpo extends Armado_Plantilla {

    public function __construct() {

        $nombre_clase = 'Acciones_' . $_GET['accion'];
        $armado_cuerpo = new $nombre_clase();
        $cuerpo = $armado_cuerpo->armado();

        $this->_armadoPlantillaSet('cuerpo', $cuerpo);
    }

}

