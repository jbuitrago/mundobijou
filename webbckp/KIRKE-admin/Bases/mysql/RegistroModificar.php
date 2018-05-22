<?php

class Bases_RegistroModificar {

    private $_archivo;
    private $_linea;
    private $_tablas = array();
    private $_camposValores;
    private $_condiciones;
    private $_verConsulta = false;
    private $_verErrores = false;

    function __construct($archivo = null, $linea = null) {
        $this->_archivo = $archivo;
        $this->_linea = $linea;
    }

    public function tabla($tabla, $prefijo = null) {
        if (isset($prefijo)) {
            $tabla = strtolower($prefijo) . '_' . strtolower($tabla);
        } else {
            $tabla = strtolower($tabla);
        }
        $this->_tablas[] = $tabla;
    }

    public function campoValor($tabla, $campo, $valor) {
        $this->_camposValores = Bases_Registro__camposValores::armado($this->_camposValores, $tabla, $campo, $valor);
    }
    
    public function condicionesAgrupacionInicio($condicion = null) {
        $this->_condiciones = Bases_Registro__condiciones::armadoAgrupacionInicio($this->_condiciones, $condicion);
    }

    public function condicionesAgrupacionFin() {
        $this->_condiciones = Bases_Registro__condiciones::armadoAgrupacionFin($this->_condiciones);
    }

    public function condiciones($condicion, $tabla1, $campo1, $relacion, $tabla2, $campo2, $cadena = null) {
        $this->_condiciones = Bases_Registro__condiciones::armado($this->_condiciones, $condicion, $tabla1, $campo1, $relacion, $tabla2, $campo2, $cadena);
    }

    // controles //

    public function verConsulta() {
        $this->_verConsulta = true;
    }

    public function verErrores() {
        $this->_verErrores = true;
    }

    public function realizarConsulta() {

        if ($this->_condiciones) {
            $this->_condiciones = ' WHERE ' . $this->_condiciones;
        }

        $tablas_existentes = '';
        $tablas = '';
        foreach ($this->_tablas as $tabla) {
            $tablas .= $tablas_existentes . ' `' . $tabla . '` ' . "\n";
            $tablas_existentes = ',';
        }
        
        // armo la consulta
        $consulta = "UPDATE " . $tablas . " SET " . $this->_camposValores . " " . $this->_condiciones . ";";

        if ($this->_verConsulta) {
            echo '<br>#--<br>' . $consulta . '<br>--#<br>';
        }

        // realizo la consulta
        $resultado = mysql_query($consulta);


        if ($this->_verErrores) {
            echo '<br>#--<br>' . mysql_error() . '<br>--#<br>';
        }

        // control de errores
        if (!$resultado) {
            Generales_ErroresControl::setError('Base de Datos', mysql_error(), $consulta, $this->_archivo, $this->_linea, __CLASS__, __METHOD__, __FUNCTION__);
            return false;
        }

        if (@mysql_num_rows($resultado)) {
            while ($linea = mysql_fetch_array($resultado, MYSQL_ASSOC)) {
                $resultado_matriz[] = $linea;
            }
        }

        if (isset($resultado_matriz)) {
            // devuelvo la consulta
            return $resultado_matriz;
        } else {
            return false;
        }
    }

}
