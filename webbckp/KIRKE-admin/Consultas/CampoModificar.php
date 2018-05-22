<?php

class Consultas_CampoModificar {

    static public function armado($archivo, $linea, $tabla, $campo, $campo_nvo, $tipo, $largo = null, $es_nulo = true, $es_indice = false, $incremental = false) {

        $consulta = new Bases_CampoModificar($archivo, $linea);
        $consulta->tabla($tabla);
        $consulta->campo($campo);
        $consulta->campo_nuevo($campo_nvo);
        $consulta->tipo($tipo);
        if (isset($largo)) {
            $consulta->largo($largo);
        }
        if ($incremental === true) {
            $consulta->incremental();
        }
        if ($es_nulo === false) {
            $consulta->no_nulo();
        }
        if ($es_indice === true) {
            $consulta->es_indice();
        }
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

}
