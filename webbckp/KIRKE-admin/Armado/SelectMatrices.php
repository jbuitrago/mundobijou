<?php

class Armado_SelectMatrices {

    static public function armado($campo_nombre, $valores, $descripciones, $valor_seleccionado = null, $primero_mensaje = null, $primero_valor = null) {

        $select_matriz = '<select name="' . $campo_nombre . '" id="' . $campo_nombre . '">';

        if ($primero_mensaje == 'vacio') {
            $select_matriz .= '<option value="' . $primero_valor . '"></option>';
        } elseif ($primero_mensaje != '') {
            $select_matriz .= '<option value="' . $primero_valor . '">' . $primero_mensaje . '</option>';
        }

        foreach ($valores as $id => $valor) {
            $seleccionado = '';
            if ($valores[$id] == $valor_seleccionado)
                $seleccionado = 'selected="selected"';
            $select_matriz .= '<option value="' . $valores[$id] . '" ' . $seleccionado . '>' . $descripciones[$id] . '</option>';
        }

        $select_matriz .= '</select>';

        return $select_matriz;
    }

}
