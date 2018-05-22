<?php

class Bases_TablaCrear {

    private $_archivo;
    private $_linea;
    private $_tabla;
    private $_prefijo;
    private $_verConsulta = false;
    private $_verErrores = false;

    function __construct($archivo = null, $linea = null) {
        $this->_archivo = $archivo;
        $this->_linea = $linea;
    }

    public function tabla($tabla, $prefijo = null) {
        if ($prefijo) {
            $this->_tabla = strtolower($prefijo) . '_' . strtolower($tabla);
        } else {
            $this->_tabla = strtolower($tabla);
        }
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
        $consulta = "CREATE TABLE `" . $this->_tabla . "` (`id_" . $this->_tabla . "` INT( 12 ) NOT NULL AUTO_INCREMENT PRIMARY KEY, `orden` INT( 12 ) NOT NULL) ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;";

        if ($this->_verConsulta) {
            echo '<br>#--<br>' . $consulta . '<br>--#<br>';
        }

        // realizo la consulta
        $resultado = mysql_query($consulta);

        if ($this->_verErrores) {
            echo '<br>#--<br>' . mysql_error() . '<br>--#<br>';
        }

        if (isset($resultado)) {

            return true;
        } else {
            // control de errores
            Generales_ErroresControl::setError('Base de Datos', mysql_error(), $consulta, $this->_archivo, $this->_linea, __CLASS__, __METHOD__, __FUNCTION__);
            return false;
        }
    }

}

