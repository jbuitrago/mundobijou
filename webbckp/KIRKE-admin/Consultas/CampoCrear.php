<?php

class Consultas_CampoCrear {

    static public function armado($archivo, $linea, $tabla, $campo, $tipo, $largo = null, $es_nulo = true, $es_indice = false, $incremental = false) {

        $consulta = new Bases_CampoCrear($archivo, $linea);
        $consulta->tabla($tabla);
        $consulta->campo($campo);
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
        return $consulta->realizarConsulta();
    }

}
