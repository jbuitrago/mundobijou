<?php

class Armado_SelectNumeros {

    static public function armado($campo_nombre, $valor_seleccionado = null, $lago_minimo = null, $largo_maximo = null, $paso = null, $primero_mensaje = null, $primero_valor = null, $class=null, $style = null) {

        if ($lago_minimo == NULL) {
            $lago_minimo = 0;
        }
        if ($largo_maximo == NULL) {
            $largo_maximo = 100;
        }
        if ($paso == NULL) {
            $paso = 1;
        }
        if ($style !== null) {
            $style = ' style="' . $style . '"';
        }
        if ($class !== null) {
            $class = ' class="' . $class . '"';
        }
        
        $select_numero = '<select name="' . $campo_nombre . '" id="' . $campo_nombre . '" ' . $class . $style . '>';

        if ($primero_mensaje == 'vacio') {
            $select_numero .= '<option value="' . $primero_valor . '"></option>';
        } elseif ($primero_mensaje != '') {
            $select_numero .= '<option value="' . $primero_valor . '">' . $primero_mensaje . '</option>';
        }

        for ($i = $lago_minimo; $i <= $largo_maximo; $i = $i + $paso) {
            $seleccionado = '';
            if ($valor_seleccionado == $i)
                $seleccionado = 'selected="selected"';
            $select_numero .= '<option value="' . $i . '" ' . $seleccionado . '>' . $i . '</option>';
        }

        $select_numero .= '</select>';

        return $select_numero;
    }

}
