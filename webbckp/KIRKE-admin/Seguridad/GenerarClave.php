<?php

class Seguridad_GenerarClave {

    static public function armar($largo = 8) {

        $alfanumerico_opciones = array(
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'e', 'f', 'g',
            'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r',
            's', 't', 'u', 'v', 'w', 'x', 'y', 'z'
        );
        $otras_opciones = array(
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        );
        $opciones = array_merge($alfanumerico_opciones, $otras_opciones);

        $clave = '';

        mt_srand(lcg_value() * 10000 + time());
        $clave .= $alfanumerico_opciones[array_rand($alfanumerico_opciones)];

        if (( $largo -= 2 ) > 0) {
            while ($largo-- > 0) {
                $clave .= $opciones[array_rand($opciones)];
            }
        }

        $clave .= $alfanumerico_opciones[array_rand($alfanumerico_opciones)];

        return $clave;
    }

}

