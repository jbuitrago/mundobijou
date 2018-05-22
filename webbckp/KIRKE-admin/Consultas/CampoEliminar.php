<?php

class Consultas_CampoEliminar {

    static public function armado($archivo, $linea, $tabla, $campo) {

        $consulta = new Bases_CampoEliminar($archivo, $linea);
        $consulta->tabla($tabla);
        $consulta->campo($campo);
        return $consulta->realizarConsulta();
    }

}

