<?php

class Componentes_Componente {

    static public function componente($componente, $clase = null, $datos = null) {

        // los componentes no deben tener acceso a la sesion
        Seguridad_DeshabilitarSesion::deshabilitar();

        if ($clase == null) {
            $clase = $_GET['accion'];
        }
        include_once(dirname(__FILE__) . '/' . $componente . '/' . $clase . '.php');

        $clase = 'Componentes_' . $componente . '_' . $clase;

        $componente = new $clase();
        $componente->set($datos);
        return $componente->get();
    }

}

