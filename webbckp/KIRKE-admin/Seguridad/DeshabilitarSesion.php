<?php

/**
 * Descripcion de la Clase
 *
 * @category Kirke
 * @package Seguridad
 * @subpackage Seguridad
 * @copyright Copyright (c)  Registro 2007-01-25
 * @license GPL
 * @version Release: @package_version@
 * @link http://sourceforge.net/projects/kirke-ws/
 * @link http://kirke.ws/KIRKE-admin/
 * @since Class available since Release 0.6.0
 * @author Nicolas Nirich
 */
class Seguridad_DeshabilitarSesion {

    static private $_deshabilitada = false;

    public static function deshabilitar() {

        if (self::$_deshabilitada == false) {

            session_write_close();
            self::$_deshabilitada = true;
        }
    }

}

