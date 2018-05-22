<?php

class Bases_CampoEliminar {

    private $_archivo;
    private $_linea;
    private $_tabla;
    private $_campo;
    private $_verConsulta = false;
    private $_verErrores = false;

    function __construct($archivo = null, $linea = null) {
        $this->_archivo = $archivo;
        $this->_linea = $linea;
    }

    public function campo($campo) {
        $this->_campo = strtolower($campo);
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
        $consulta = "ALTER TABLE `" . $this->_tabla . "` DROP `" . $this->_campo . "`;";

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

