<?php

class Bases_Registro__valores {

    static public function armado($valores = null, $nuevo_valor) {

        if ($valores) {
            $valores_existentes = ',';
        } else {
            $valores_existentes = '';
        }

        if ($nuevo_valor != 'NULL') {
            $campo = " '" . $nuevo_valor . "' ";
        } else {
            $campo = " NULL ";
        }

        $consulta = $valores . $valores_existentes . $campo;

        return $consulta;
    }

}

