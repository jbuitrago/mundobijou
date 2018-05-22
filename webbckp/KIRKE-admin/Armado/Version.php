<?php

class Armado_Version extends Armado_Plantilla {

    public function __construct() {

        $version = Version::version;
        $this->_armadoPlantillaSet('version', $version);
    }

}

