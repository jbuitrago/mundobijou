<?php

class Bases_RegistroEliminar {

    private $_archivo;
    private $_linea;
    private $_tabla;
    private $_campos;
    private $_condiciones;
    private $_ver_consulta = false;
    private $_ver_errores = false;

    function __construct($archivo = null, $linea = null) {
        $this->_archivo = $archivo;
        $this->_linea = $linea;
    }

    public function tabla($tabla, $prefijo = null) {
        if ($prefijo) {
            $tabla = strtolower($prefijo) . '_' . strtolower($tabla);
        } else {
            $tabla = strtolower($tabla);
        }
        $this->_tabla = $tabla;
    }

    public function condiciones($condicion, $tabla1, $campo1, $relacion, $tabla2, $campo2, $cadena = null) {
        $this->_condiciones = Bases_Registro__condiciones::armado($this->_condiciones, $condicion, $tabla1, $campo1, $relacion, $tabla2, $campo2, $cadena);
    }

    // controles //

    public function verConsulta() {
        $this->_ver_consulta = true;
    }

    public function verErrores() {
        $this->_ver_errores = true;
    }

    public function realizarConsulta() {

        if ($this->_condiciones) {
            $this->_condiciones = ' WHERE ' . $this->_condiciones;
        }

        // realizo la consulta
        $consulta = "DELETE FROM " . $this->_tabla . " " . $this->_condiciones . ";";

        if ($this->_ver_consulta) {
            echo '<br>#--<br>' . $consulta . '<br>--#<br>';
        }

        // realizo la consulta
        $resultado = mysql_query($consulta);

        if ($this->_ver_errores) {
            echo '<br>#--<br>' . mysql_error() . '<br>--#<br>';
        }

        // control de errores
        if (!$resultado) {
            Generales_ErroresControl::setError('Base de Datos', mysql_error(), $consulta, $this->_archivo, $this->_linea, __CLASS__, __METHOD__, __FUNCTION__);
            return false;
        }
        return true;
    }

}
