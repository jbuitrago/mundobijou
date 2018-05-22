<?php

class Armado_Errores extends Armado_Plantilla {

    public function __construct() {

        $errores = Generales_ErroresControl::obtener();
        $this->_armadoPlantillaSet('errores', $errores);
    }

}

