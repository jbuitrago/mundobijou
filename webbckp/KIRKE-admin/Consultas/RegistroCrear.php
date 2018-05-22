<?php

class Consultas_RegistroCrear {

    static public function armado($archivo, $linea, $tabla, $campos) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla($tabla);
        foreach ($campos as $nombre => $valor) {
            $consulta->campoValor($tabla, $nombre, $valor);
        }
        return $consulta->realizarConsulta();
    }

}

