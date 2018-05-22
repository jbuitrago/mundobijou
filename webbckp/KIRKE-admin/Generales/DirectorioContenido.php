<?php

class Generales_DirectorioContenido {

    static public function directorioContenido($direccion) {

        $archivos = array();
        if (is_dir($direccion)) {
            $gestor = opendir($direccion);
            while (false !== ($archivo = readdir($gestor))) {
                // elimino los resultados que son directorios
                if (($archivo != '.') && ($archivo != '..'))
                    $archivos[] = $archivo;
            }
            closedir($gestor);
        }
        return $archivos;
    }

}

