<?php

class Generales_Traduccion {

    static private $_matrizTraduccion = array();
    static private $_identificador;

    static public function traduccion($texto, $direccion, $archivo, $patron_busqueda) {

        $archivo = $archivo . ".php";
        self::$_identificador = $direccion . $archivo;

        if (!isset(self::$_matrizTraduccion[self::$_identificador])) {
            self::$_matrizTraduccion[self::$_identificador] = Generales_ArchivoVariables::archivoLeer($direccion, $archivo, 'Traduccion');
        }

        return preg_replace_callback($patron_busqueda, 'Generales_Traduccion::traducir', $texto);
    }

    static private function traducir($coincidencias) {

        $traducido = self::$_matrizTraduccion[self::$_identificador][$coincidencias[2]];

        switch ($coincidencias[1]) {
            case 'n':
                return $traducido;
                break;
            case 'o':
                return ucfirst($traducido);
                break;
            case 's':
                return ucwords($traducido);
                break;
            case 't':
                return strtoupper($traducido);
                break;
            case 'm':
                return strtolower($traducido);
                break;
        }

        return false;
    }

}
