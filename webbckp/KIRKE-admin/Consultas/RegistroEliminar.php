<?php

class Consultas_RegistroEliminar {

    static public function armado($archivo, $linea, $tabla, $campo, $valor) {

        $consulta = new Bases_RegistroEliminar($archivo, $linea);
        $consulta->tabla($tabla);
        $consulta->condiciones('', $tabla, $campo, 'iguales', '', '', $valor);
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

}

