<?php

class Bases_Registro__camposValores {

    static public function armado($campos_valores = null, $tabla, $nuevo_campo, $nuevo_valor) {

        if ($campos_valores) {
            $campos_valores_existentes = ',';
        } else {
            $campos_valores_existentes = '';
        }
        if($nuevo_valor != 'NULL'){
            $nuevo_valor = "'" . $nuevo_valor . "'";
        }else{
            $nuevo_valor = 'NULL';
        }

        $campo_valor = " `" . $tabla . "`.`" . $nuevo_campo . "` = " . $nuevo_valor . " ";

        $consulta = $campos_valores . $campos_valores_existentes . $campo_valor;

        return $consulta;
    }

}

