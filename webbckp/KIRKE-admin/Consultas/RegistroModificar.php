<?php

class Consultas_RegistroModificar {

    static public function armado($archivo, $linea, $tabla, $campos, $condicion_campo, $condicion_valor) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla($tabla);
        foreach ($campos as $nombre => $valor) {
            $consulta->campoValor($tabla, $nombre, $valor);
        }
        $consulta->condiciones('', $tabla, $condicion_campo, 'iguales', '', '', $condicion_valor);
        return $consulta->realizarConsulta();
    }

}

