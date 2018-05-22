<?php

class BDControlModificaciones {

    static public $tiempo = 0;

    static public function controlMasNuevo($tablas = null) {
        $matriz = glob(VariableGet::sistema('directorio_cache_base_tablas') . '/*');
        if (is_array($matriz)) {
            if (($tablas !== null) && is_array($tablas)) {
                foreach ($tablas as $tabla_controlar) {
                    if (in_array(VariableGet::sistema('directorio_cache_base_tablas') . '/' . sha1($tabla_controlar) . '.cache', $matriz)) {
                        self::$tiempo = filemtime(VariableGet::sistema('directorio_cache_base_tablas') . '/' . sha1($tabla_controlar) . '.cache');
                    }
                }
            }
        }

        return self::$tiempo;
    }

}
