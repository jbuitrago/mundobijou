<?php

class Armado_PlantillasInternas {

    static public function componentes($tipo, $componente_nombre, $plantilla = null) {

        // obtencion de plantilla
        $archivo = Inicio::path() . '/Componentes/' . ucwords($componente_nombre) . '/' . ucwords($tipo) . 'Ver.tpl';

        if (file_exists($archivo)) {
            $ver = file_get_contents($archivo);
        } else {
            echo 'problema en Armado_PlantillasInternas(componentes)';
        }

        return self::_reemplazos($ver, $plantilla);

    }

    static public function acciones($nombre_archivo, $plantilla = null) {

        if (substr($nombre_archivo, -4) == '.php') {
            $plantilla_nombre = substr($nombre_archivo, 0, -4);
        } else {
            $plantilla_nombre = $nombre_archivo;
        }

        // obtencion de plantilla
        $archivo = Inicio::path() . '/Acciones/' . $plantilla_nombre . '.tpl';

        if (file_exists($archivo)) {
            $ver = file_get_contents($archivo);
        } else {
            echo 'problema en Armado_PlantillasInternas(acciones)';
        }

        return self::_reemplazos($ver, $plantilla);
    }

    static private function _reemplazos($archivo, $plantilla = null) {

        if (isset($plantilla)) {

            // reemplazo de las etiquetas
            $i = 0;
            foreach ($plantilla as $elemento => $valores) {
                $etiqueta[$i] = "/{[P][L][|](" . $elemento . ")}/";
                $contenido[$i] = $valores;
                $i++;
            }
            return preg_replace($etiqueta, $contenido, $archivo);
        }

        return $archivo;
    }

}

