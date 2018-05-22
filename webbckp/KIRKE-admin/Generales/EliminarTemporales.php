<?php

class Generales_EliminarTemporales {

    static public function eliminar() {

        $matriz = glob(self::_obtenerDirectorio() . '/*.*');
        if (is_array($matriz)) {
            foreach ($matriz as $dir_nombre_archivo) {
                // elimina los archivos de mas de 5 minutos
                if (filemtime($dir_nombre_archivo) < (time() - (5 * 60))) {
                    unlink($dir_nombre_archivo);
                }
            }
        }
    }

    static private function _obtenerDirectorio() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('tmp');
        $directorio = getcwd();
        chdir($url_actual);

        return $directorio;
    }

}

