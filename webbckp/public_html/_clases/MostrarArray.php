<?php

class MostrarArray {

    private $_armado;

    public function arrayVer($array, $arrayNombre) {

        $this->_armado = '';

        if (is_array($array)) {
            return $this->_arrayArmar($array, $arrayNombre) . "<br />";
        } else {
            return $arrayNombre . ' = "' . $array . '";' . "<br />";
        }
    }

    private function _arrayArmar($array, $nombreArray) {

        reset($array);

        while (list($key, $value) = each($array)) {
            if (is_numeric($key)) {
                $verKey = "[" . $key . "]";
            } else {
                $verKey = "['" . $key . "']";
            }

            if (is_array($value)) {
                $this->_arrayArmar($value, $nombreArray . $verKey);
            } else {
                $this->_armado .= $nombreArray . $verKey . " = ";
                if (is_string($value)) {
                    $this->_armado .= "'" . $value . "';<br />";
                } else if ($value === false) {
                    $this->_armado .= "false;<br />";
                } else if ($value === NULL) {
                    $this->_armado .= "null;<br />";
                } else if ($value === true) {
                    $this->_armado .= "true;<br />";
                } else {
                    $this->_armado .= $value . ";<br />";
                }
            }
        }

        return $this->_armado;
    }

}