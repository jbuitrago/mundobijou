<?php

class Armado_Botonera2 extends Armado_Plantilla {

    static private $contenido = '';
    
    public function set($contenido) {

        self::$contenido .= $contenido;
    }
    
    public function get() {

        $this->_armadoPlantillaSet('botonera2', self::$contenido);
    }

}

