<?php

class Generales_ControlTablasModificadas {

    static public function control($tabla) {

        if (Inicio::confVars('dir_tablas_modificadas') !== false) {

            $tabla = sha1($tabla);

            $archivo_control = Inicio::confVars('dir_tablas_modificadas') . '/' . $tabla . '.cache';

            if (!file_exists($archivo_control)) {
                file_put_contents($archivo_control, ' ');
            } else {
                touch($archivo_control, time());
            }

            $archivo_control_todas = Inicio::confVars('dir_tablas_modificadas') . '/TODAS.cache';

            if (!file_exists($archivo_control_todas)) {
                file_put_contents($archivo_control_todas, ' ');
            } else {
                touch($archivo_control_todas, time());
            }
        }
    }

}
