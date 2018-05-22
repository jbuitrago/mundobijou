<?php

class Consultas_TablaCrear {

    static public function armado($archivo, $linea, $tabla, $prefijo) {

        $consulta = new Bases_TablaCrear($archivo, $linea);
        $consulta->tabla($tabla, $prefijo);
        return $consulta->realizarConsulta();
    }

}

