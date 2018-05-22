<?php

class Bases_RegistroCrear {

    private $_archivo;
    private $_linea;
    private $_tabla;
    private $_campos;
    private $_valores;
    private $_verConsulta = false;
    private $_verErrores = false;

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

    public function campoValor($tabla, $campo, $valor) {
        $this->_campos = Bases_Registro__campos::armado($this->_campos, $tabla, $campo);
        $this->_valores = Bases_Registro__valores::armado($this->_valores, $valor);
    }

    // controles //

    public function verConsulta() {
        $this->_verConsulta = true;
    }

    public function verErrores() {
        $this->_verErrores = true;
    }

    public function realizarConsulta() {

        // armo la consulta
        $consulta = "INSERT INTO " . $this->_tabla . " (" . $this->_campos . ") VALUES (" . $this->_valores . ");";

        if ($this->_verConsulta) {
            echo '<br>#--<br>' . $consulta . '<br>--#<br>';
        }

        // realizo la consulta
        $resultado = mysql_query($consulta);

        if ($this->_verErrores) {
            echo '<br>#--<br>' . mysql_error() . '<br>--#<br>';
        }

        // control de errores
        if (!isset($resultado)) {
            Generales_ErroresControl::setError('Base de Datos', mysql_error(), $consulta, $this->_archivo, $this->_linea, __CLASS__, __METHOD__, __FUNCTION__);
            return false;
        }

        if (isset($resultado)) {
            // devuelvo la consulta
            $registro_insertado['id'] = @mysql_insert_id();
            return $registro_insertado;
        } else {
            return false;
        }
    }

}
