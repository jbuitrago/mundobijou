<?php

class Generales_InformeNombre {

    static public function obtener($id_informe) {

        $directorios = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Informes/');
        asort($directorios);

        if (is_array($directorios)) {

            foreach ($directorios as $linea) {

                $direccion = Inicio::path() . '/Informes/' . $linea . '/';

                $archivo = 'configuraciones.php';
                $leer_archivo = new Generales_ArchivoVariables();
                $informe_configuracion = $leer_archivo->archivoLeer($direccion, $archivo, 'InformeNombre');

                if ($informe_configuracion['id_informe'] = $id_informe) {
                    return $linea;
                }
            }
        }
    }

}

