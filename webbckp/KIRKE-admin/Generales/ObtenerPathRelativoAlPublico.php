<?php

class Generales_ObtenerPathRelativoAlPublico {

    // la funcion 'getcwd' debe estar habilitada en el servidor

    static public function obtener($url_relativo) {

        $url_actual = getcwd();

        chdir($url_relativo);
        $nuevo_url = getcwd();

        chdir($url_actual);

        return $nuevo_url;
    }

}

