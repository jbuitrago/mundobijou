<?php

class Generales_Post {

    static public function obtener($variable, $opcion = null) {

        if (!get_magic_quotes_gpc()) {
            $variable = $variable;
        } else {
            $variable = stripslashes($variable);
        }

        $buscados = array("&lt;", "&gt;", "&#039;", "&quot;");
        $reemplazantes = array("<", ">", "'", "\"");
        $variable = str_replace($buscados, $reemplazantes, $variable);

        if ($opcion == 'c') {
            $variable = addslashes($variable);
        }

        if ($opcion == 'h') {
            $buscados = array("<", ">", "'", "\"");
            $reemplazantes = array("&lt;", "&gt;", "&#039;", "&quot;");
            $variable = str_replace($buscados, $reemplazantes, $variable);
        }

        return $variable;
    }

}
