<?php

class Generales_MenuObtenerNivel {

    static public function nivel($nivel1 = null, $nivel2 = null, $nivel3 = null, $nivel4 = null, $nivel5 = null, $nivel6 = null, $nivel7 = null, $nivel8 = null, $nivel9 = null, $nivel10 = null) {

        if ($nivel10 != '') {
            return 10;
        }
        if ($nivel9 != '') {
            return 9;
        }
        if ($nivel8 != '') {
            return 8;
        }
        if ($nivel7 != '') {
            return 7;
        }
        if ($nivel6 != '') {
            return 6;
        }
        if ($nivel5 != '') {
            return 5;
        }
        if ($nivel4 != '') {
            return 4;
        }
        if ($nivel3 != '') {
            return 3;
        }
        if ($nivel2 != '') {
            return 2;
        }
        return 1;
    }

}
