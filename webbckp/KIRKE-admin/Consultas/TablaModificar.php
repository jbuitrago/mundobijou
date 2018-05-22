<?php

class Consultas_TablaModificar {

    static public function armado($archivo, $linea, $tabla, $tabla2) {

        $consulta = new Bases_TablaModificar($archivo, $linea);
        $consulta->tabla($tabla);
        $consulta->tablaNueva($tabla2);
        return $consulta->realizarConsulta();
    }

}
