<?php

class Armado_InformeSelect {

    static public function armado($id_informe = null) {

        $directorios = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Informes/');
        asort($directorios);

        if (is_array($directorios)) {
            
            $elementos_ok = false;
            $listado_informe = '<select name="id_informe">';

            foreach ($directorios as $linea) {

                $no_directorio = stripos($linea, '.');

                if ($no_directorio === false) {

                    $direccion = Inicio::path() . '/Informes/' . $linea . '/';

                    $archivo = Generales_Idioma::obtener() . '.php';
                    $leer_archivo = new Generales_ArchivoVariables();
                    $informe_traduccion = $leer_archivo->archivoLeer($direccion, $archivo, 'InformeSelect TXT 1');

                    $archivo = 'configuraciones.php';
                    $leer_archivo = new Generales_ArchivoVariables();
                    $informe_configuracion = $leer_archivo->archivoLeer($direccion, $archivo, 'InformeSelect TXT 2');

                    $nombre_informe = ucfirst($informe_traduccion['nombre_informe']);
                    $id_informe_listado = $informe_configuracion['id_informe'];

                    if ($id_informe == $id_informe_listado) {
                        $seleccionado = 'selected';
                    } else {
                        $seleccionado = '';
                    }

                    $listado_informe .= '<option value="' . $id_informe_listado . '" ' . $seleccionado . '>' . $nombre_informe . '</option>';
                    $elementos_ok = true;
                }
            }

            $listado_informe .= '</select>';

            if ($elementos_ok === true) {
                return $listado_informe;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    static public function obtenerNombre($id_informe = null) {

        $directorios = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Informes/');
        asort($directorios);

        if (is_array($directorios)) {

            foreach ($directorios as $linea) {

                $no_directorio = stripos($linea, '.');

                if ($no_directorio === false) {

                    $direccion = Inicio::path() . '/Informes/' . $linea . '/';

                    $archivo = Generales_Idioma::obtener() . '.php';
                    $leer_archivo = new Generales_ArchivoVariables();
                    $informe_traduccion = $leer_archivo->archivoLeer($direccion, $archivo, 'InformeSelect TXT 1');

                    $archivo = 'configuraciones.php';
                    $leer_archivo = new Generales_ArchivoVariables();
                    $informe_configuracion = $leer_archivo->archivoLeer($direccion, $archivo, 'InformeSelect TXT 2');

                    $nombre_informe = ucfirst($informe_traduccion['nombre_informe']);
                    $id_informe_listado = $informe_configuracion['id_informe'];

                    if ($id_informe == $id_informe_listado) {
                        return $nombre_informe;
                    }
                }
            }
        }
    }

}
