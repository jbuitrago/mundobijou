<?php

class Generales_LimpiarTextos {

    static public function alfanumericoGuiones($texto) {
        $texto = strtolower(trim($texto));
        $texto = str_replace(" ", "_", $texto);
        $texto = preg_replace("/[^a-zA-Z0-9_-]/", '', $texto);
        return strtolower($texto);
    }

}

