<?php

class CacheVariables {

    static public function cache($archivo_cache, $tiempo_segundos, $variable) {

        if ((file_exists($archivo_cache)) && (time() < filemtime($archivo_cache) + $tiempo_segundos)) {

            return unserialize(file_get_contents($archivo_cache, FILE_USE_INCLUDE_PATH));
        } elseif (file_exists($archivo_cache)) {
            // control ### verificar que es mas eficiente el hacer el touch insertar directamente el contenido
            if (unserialize(file_get_contents($archivo_cache, FILE_USE_INCLUDE_PATH)) == $variable) {
                touch($archivo_cache, time());
            } else {
                file_put_contents($archivo_cache, serialize($variable), LOCK_EX);
            }
            return $variable;
        } else {

            file_put_contents($archivo_cache, serialize($variable), LOCK_EX);
            return $variable;
        }
    }

    static public function control($archivo_cache, $tiempo_segundos) {

        if (
                !file_exists($archivo_cache) ||
                ( (filemtime($archivo_cache) + $tiempo_segundos) < time() )
        ) {

            return false;
        }

        return true;
    }

    static public function controlObtener($archivo_cache, $tiempo_segundos = 0) {

        if (
                self::control($archivo_cache, $tiempo_segundos)
        ) {

            return false;
        } else {

            return unserialize(file_get_contents($archivo_cache, FILE_USE_INCLUDE_PATH));
        }
    }

    static public function obtener($archivo_cache) {

        return unserialize(file_get_contents($archivo_cache, FILE_USE_INCLUDE_PATH));
    }

}

