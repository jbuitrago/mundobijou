<?php

class BDMysqlInsercion {

    private $_contador = 0;
    private $_tablaNombre;
    private $_camposValores = array();

    public function tablaNombre($tabla_nombre) {

        $this->_tablaNombre = $tabla_nombre;
    }

    public function insercion($campo_nombre, $valor) {

        $this->_camposValores[$this->_contador]['campo'] = $campo_nombre;
        $this->_camposValores[$this->_contador]['valor'] = BDMysqlConsulta::validaciones($valor);

        $this->_contador++;
    }

    public function obtenerQuery() {

        if (isset($this->_tablaNombre)) {

            $armado_query = "INSERT INTO `" . $this->_tablaNombre . "` (\n";
            $armado_query .= $this->_armadoCampos();
            $armado_query .= "  ,`orden`\n";
            $armado_query .= ")\nVALUES\n(\n";
            $armado_query .= $this->_armadoValores();
            $armado_query .= ",   '" . BDMysqlObtenerOrden::consulta($this->_tablaNombre) . "'\n";
            $armado_query .= ");\n";

            return $armado_query;
        } else {
            return false;
        }
    }

    private function _armadoCampos() {

        $campos = '';
        $coma = ',';

        foreach ($this->_camposValores as $key => $valor) {
            if (!isset($this->_camposValores[$key + 1])) {
                $coma = '';
            }
            $campos .= "  `" . $valor['campo'] . "` " . $coma . "\n";
        }

        return $campos;
    }

    private function _armadoValores() {

        $valores = '';
        $coma = ' ';

        foreach ($this->_camposValores as $valor) {
            $valores .= $coma . "  '" . $valor['valor'] . "'\n";
            $coma = ',';
        }

        return $valores;
    }

}
