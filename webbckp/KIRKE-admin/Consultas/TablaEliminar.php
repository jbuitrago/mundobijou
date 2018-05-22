<?php

class Consultas_TablaEliminar {

    static public function armado($archivo, $linea, $tabla) {

        $consulta = new Bases_TablaEliminar(__FILE__, __LINE__);
        $consulta->tabla($tabla);
        return $consulta->realizarConsulta();
    }

}

