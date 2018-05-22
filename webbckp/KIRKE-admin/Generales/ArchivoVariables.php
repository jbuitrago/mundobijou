<?php

class Generales_ArchivoVariables {

    static public function archivoLeer($direccion, $archivo, $clase = null) {

        //controlo si el Archivo Existe
        if (file_exists($direccion . $archivo)) {
            include($direccion . $archivo);
            // obtengo las variables del archivo que se hizo include
            return $var;
        } else {
            if (isset($clase)) {
                $clase = " perteneciente a '$clase'";
            } else {
                $clase = '';
            }
            //en caso de Error NO exponer el Path de acceso, solo el nombre del archivo
            die("No se pudo encontrar el archivo '$archivo'$clase. Verifique el nombre y la ruta de acceso al mismo.");
        }
    }

}
