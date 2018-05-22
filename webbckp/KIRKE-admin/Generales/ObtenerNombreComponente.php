<?php

class Generales_ObtenerNombreComponente {

    static public function get($archivo) {
        $path_parts = pathinfo($archivo);
        $array_nvo = explode(DIRECTORY_SEPARATOR, $path_parts['dirname']);
        return array_pop($array_nvo);
    }

}
