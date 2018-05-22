<?php

class Bases_Registro__orden {

    static public function armado($orden_ant = null, $tabla, $campo, $sentido = null) {

        if (isset($tabla) && isset($campo) && ($tabla != '') && ($campo != '')) {

            if ($orden_ant !== null) {
                $orden_existentes = ',';
            } else {
                $orden_existentes = ' ORDER BY ';
            }

            if ($sentido === null) {
                $ordenar = ' ASC ' . "\n";
            } elseif ($sentido && ($sentido == 'ascendente')) {
                $ordenar = ' ASC ' . "\n";
            } elseif ($sentido && ($sentido == 'descendente')) {
                $ordenar = ' DESC ' . "\n";
            }

            if ($campo) {
                $consulta = $orden_ant . $orden_existentes . '`' . $tabla . '`.`' . $campo . '`' . $ordenar;
            } else {
                $consulta = $orden_ant . $orden_existentes . ' ' . $tabla . ' ' . $ordenar;
            }

            return $consulta;
        } else {
            return $orden_ant;
        }
    }

}
