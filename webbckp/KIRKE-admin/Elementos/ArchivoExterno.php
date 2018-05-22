<?php

class Elementos_ArchivoExterno {

    public function __construct() {

        $parametros = $this->_seguridad();

        $tipo = $_GET['tipo'];
        $clase = 'Archivos_' . $tipo;

        $objeto = new $clase();

        $objeto->set($parametros);
    }

    private function _seguridad() {

        if (isset($_GET['id_componente'])) {
            $dcp = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($_GET['id_componente']);
        } else {
            $dcp = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado();
        }
        // si se comprueba que el usuario esta habilitado

        return $dcp;
    }

}

