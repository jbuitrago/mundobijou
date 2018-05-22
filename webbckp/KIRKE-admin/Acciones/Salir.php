<?php

class Acciones_salir {

    public function armado() {

        session_destroy();

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '14');
        $armado_botonera->armar('redirigir', $parametros);
    }

}

