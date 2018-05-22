/**
 * Se utiliza como sistema de template, que muestre las acciones de los botones.
 *
 * @category   Funciones_Sitios_Institucionales
 * @package    Sitios
 * @copyright  2010 KIRKE
 * @license    GPL
 * @version    Release: 2.0
 * @link       http://kirke.ws
 * @since      Function available since Release 1.0
 * @deprecated
 */

function a_pagina(restaurar, objActual) {

    eval("parent.location='" + objActual.options[objActual.selectedIndex].value + "'");

    if (restaurar) {
        objActual.selectedIndex = 0;
    }

}

