<?php

class Generales_EtiquetaIdiomasComponenteVer {

    public static function get($matriz_idiomas) {

        // reordena los valores de los parámetro en una matriz utilizable
        foreach (Inicio::confVars('idiomas') as $key => $value) {
            if (isset($matriz_idiomas['idioma_' . $value])) {
                $matriz_idioma[$value] = $matriz_idiomas['idioma_' . $value];
            }
        }

        if (isset($matriz_idioma)) {
            $etiqueta = Armado_EtiquetaIdiomas::armar('etiqueta', $matriz_idioma);
        } else {
            $etiqueta = Armado_EtiquetaIdiomas::armar('etiqueta');
        }

        return $etiqueta;
    }

}

