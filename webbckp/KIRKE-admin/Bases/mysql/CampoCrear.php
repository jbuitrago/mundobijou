<?php

class Bases_CampoCrear {

    private $_archivo;
    private $_linea;
    private $_tabla;
    private $_campoNuevo;
    private $_tipo;
    private $_largo;
    private $_nulo = true;
    private $_incremental = false;
    private $_esIndice = false;
    private $_verConsulta = false;
    private $_verErrores = false;

    function __construct($archivo = null, $linea = null) {
        $this->_archivo = $archivo;
        $this->_linea = $linea;
    }

    public function tabla($tabla) {
        $this->_tabla = strtolower($tabla);
    }

    public function campo($campo_nuevo) {
        $this->_campoNuevo = strtolower($campo_nuevo);
    }

    public function tipo($tipo) {
        $this->_tipo = $tipo;
    }

    public function largo($largo) {
        $this->_largo = $largo;
    }

    public function no_nulo() {
        $this->_nulo = false;
    }

    public function incremental() {
        $this->_incremental = true;
    }
    
    public function es_indice() {
        $this->_esIndice = true;
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
        $consulta = "ALTER TABLE `" . $this->_tabla . "` ADD " . Bases_Campo__atributos::armado($this->_campoNuevo, $this->_tipo, $this->_largo, $this->_nulo, $this->_incremental, $this->_esIndice) . ";";

        //die($consulta);
        
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

