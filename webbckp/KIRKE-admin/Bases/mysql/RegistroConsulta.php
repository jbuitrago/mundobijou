<?php

class Bases_RegistroConsulta {

    private $_archivo;
    private $_linea;
    private $_tablas = array();
    private $_campos;
    private $_condiciones;
    private $_condicionesArray;
    private $_orden;
    private $_grupo = '';
    private $_limite;
    private $_contador;
    private $_tablaDerecha = array();
    private $_tablaDerechaDif = array();
    private $_condicionesDerecha = array();
    private $_tablaDerechaSub = array();
    private $_tablaDerechaDifSub = array();
    private $_condicionesDerechaSub = array();
    private $_unionTablas = array();
    private $_verConsulta = false;
    private $_verErrores = false;
    private $_cont_union = 0;
    private $_consulta_directa = false;

    function __construct($archivo = null, $linea = null) {
        $this->_archivo = $archivo;
        $this->_linea = $linea;
    }

    public function tablas($tabla, $prefijo = null) {
        if (isset($prefijo)) {
            $tabla = strtolower($prefijo) . '_' . strtolower($tabla);
        } else {
            $tabla = strtolower($tabla);
        }
        $this->_tablas[] = $tabla;
    }

    public function campos($tabla, $campo, $etiqueta = null, $tipo = null) {
        $this->_campos = Bases_Registro__campos::armado($this->_campos, $tabla, $campo, $etiqueta, $tipo);
    }

    public function camposTodos() {
        $this->_campos = ' * ';
    }

    public function camposLimpiar() {
        $this->_campos = '';
    }

    public function cadena($valor, $nombre) {
        if ($this->_campos) {
            $campos_existentes = ',';
        } else {
            $campos_existentes = '';
        }
        $this->_campos = $this->_campos . $campos_existentes . " '" . $valor . "' AS " . $nombre;
    }

    public function contador($nombre_contador) {
        $this->_contador = "COUNT(*) AS " . $nombre_contador;
    }

    public function contadorTotal($consulta, $nombre_contador) {
        $consulta = 'SELECT COUNT(*) AS ' . $nombre_contador . ' FROM (' . $consulta . ') AS tabla;';
        return $this->controlesConsulta($consulta);
    }

    public function sumaTotal($consulta, $nombre_campo) {
        $consulta = 'SELECT SUM(' . $nombre_campo . ') AS ' . $nombre_campo . ' FROM (' . $consulta . ') AS  tabla;';
        return $this->controlesConsulta($consulta);
    }

    public function condicionesAgrupacionInicio($condicion = null) {
        $this->_condiciones = Bases_Registro__condiciones::armadoAgrupacionInicio($this->_condiciones, $condicion);
    }

    public function condicionesAgrupacionFin() {
        $this->_condiciones = Bases_Registro__condiciones::armadoAgrupacionFin($this->_condiciones);
    }

    public function condiciones($condicion = null, $tabla1, $campo1, $relacion, $tabla2 = null, $campo2 = null, $cadena = null, $agrupacion = null) {
        $this->_condiciones = Bases_Registro__condiciones::armado($this->_condiciones, $condicion, $tabla1, $campo1, $relacion, $tabla2, $campo2, $cadena);
    }

    public function condicionesArrayEn($condicion = null, $tabla, $campo, $array_datos) {
        $this->_condicionesArray = Bases_Registro__condicionesArray::armado('en', $this->_condicionesArray, $condicion, $tabla, $campo, $array_datos);
    }

    public function condicionesArrayNoEn($condicion = null, $tabla, $campo, $array_datos) {
        $this->_condicionesArray = Bases_Registro__condicionesArray::armado('no_en', $this->_condicionesArray, $condicion, $tabla, $campo, $array_datos);
    }

    public function orden($tabla, $campo = null, $sentido = null) {
        $this->_orden = Bases_Registro__orden::armado($this->_orden, $tabla, $campo, $sentido);
    }

    public function grupo($tabla, $campo) {
        if ($this->_grupo == '') {
            $this->_grupo = ' GROUP BY ' . $tabla . '.' . $campo . "\n";
        } else {
            $this->_grupo .= ', ' . $tabla . '.' . $campo . "\n";
        }
    }

    public function limite($inicio, $cantidad = null) {
        $this->_limite = ' LIMIT ' . $inicio;
        if ($cantidad) {
            $this->_limite .= ',' . $cantidad;
        }
    }

    public function unionIzquierdaTablas($id_union, $tabla, $prefijo = null, $tabla_der, $prefijo_der = null, $etiqueta = null) {
        if ($prefijo) {
            $tabla = strtolower($prefijo) . '_' . strtolower($tabla);
        } else {
            $tabla = strtolower($tabla);
        }
        if ($prefijo_der) {
            $tabla_der = strtolower($prefijo_der) . '_' . strtolower($tabla_der);
        } else {
            $tabla_der = strtolower($tabla_der);
        }
        $this->_tablaDerecha[$id_union]['izq'] = $tabla;
        $this->_tablaDerecha[$id_union]['der'] = $tabla_der;
        $this->_tablaDerecha[$id_union]['etiqueta'] = $etiqueta;
    }

    public function unionIzquierdaTablasDiferentes($id_union, $tabla, $prefijo = null, $tabla_der, $prefijo_der = null, $etiqueta = null) {
        if ($prefijo) {
            $tabla = strtolower($prefijo) . '_' . strtolower($tabla);
        } else {
            $tabla = strtolower($tabla);
        }
        if ($prefijo_der) {
            $tabla_der = strtolower($prefijo_der) . '_' . strtolower($tabla_der);
        } else {
            $tabla_der = strtolower($tabla_der);
        }
        $this->_tablaDerechaDif[$id_union]['izq'] = $tabla;
        $this->_tablaDerechaDif[$id_union]['der'] = $tabla_der;
        $this->_tablaDerechaDif[$id_union]['etiqueta'] = $etiqueta;
    }

    public function unionIzquierdaCondiciones($id_union, $condicion = null, $tabla1, $campo1, $relacion, $tabla2 = null, $campo2 = null, $cadena = null, $agrupacion = null) {
        $this->_condicionesDerecha[$id_union][$this->_cont_union] = Bases_Registro__condiciones::armado(' ', $condicion, $tabla1, $campo1, $relacion, $tabla2, $campo2, $cadena, $agrupacion);
        $this->_cont_union++;
    }

    public function unionIzquierdaSubTablas($id_union, $id_union_sub, $tabla, $prefijo = null, $tabla_der, $prefijo_der = null, $etiqueta = null) {
        if ($prefijo) {
            $tabla = strtolower($prefijo) . '_' . strtolower($tabla);
        } else {
            $tabla = strtolower($tabla);
        }
        if ($prefijo_der) {
            $tabla_der = strtolower($prefijo_der) . '_' . strtolower($tabla_der);
        } else {
            $tabla_der = strtolower($tabla_der);
        }
        $this->_tablaDerechaSub[$id_union][$this->_cont_union]['izq'] = $tabla;
        $this->_tablaDerechaSub[$id_union][$this->_cont_union]['der'] = $tabla_der;
        $this->_tablaDerechaSub[$id_union][$this->_cont_union]['etiqueta'] = $etiqueta;
        $this->_tablaDerechaSub[$id_union][$this->_cont_union]['id_union_sub'] = $id_union_sub;
        $this->_cont_union++;
    }

    public function unionIzquierdaSubTablasDiferentes($id_union, $id_union_sub, $tabla, $prefijo = null, $tabla_der, $prefijo_der = null, $etiqueta = null) {
        if ($prefijo != '') {
            $tabla = strtolower($prefijo) . '_' . strtolower($tabla);
        } else {
            $tabla = strtolower($tabla);
        }
        if ($prefijo_der != '') {
            $tabla_der = strtolower($prefijo_der) . '_' . strtolower($tabla_der);
        } else {
            $tabla_der = strtolower($tabla_der);
        }
        $this->_tablaDerechaDifSub[$id_union][$this->_cont_union]['izq'] = $tabla;
        $this->_tablaDerechaDifSub[$id_union][$this->_cont_union]['der'] = $tabla_der;
        $this->_tablaDerechaDifSub[$id_union][$this->_cont_union]['etiqueta'] = $etiqueta;
        $this->_tablaDerechaDifSub[$id_union][$this->_cont_union]['id_union_sub'] = $id_union_sub;
        $this->_cont_union++;
    }

    public function unionIzquierdaSubCondiciones($id_union, $id_union_sub, $condicion = null, $tabla1, $campo1, $relacion, $tabla2 = null, $campo2 = null, $cadena = null, $agrupacion = null) {
        $this->_condicionesDerechaSub[$id_union][$id_union_sub][$this->_cont_union] = Bases_Registro__condiciones::armado('', $condicion, $tabla1, $campo1, $relacion, $tabla2, $campo2, $cadena, $agrupacion);
        $this->_cont_union++;
    }

    public function unionTabla($tablas) {
        $this->_unionTablas[] = $tablas;
    }

    public function consultaDirecta($consulta) {
        $this->_consulta_directa = $consulta;
    }

    // controles //
    public function verConsulta() {
        $this->_verConsulta = true;
    }

    public function verErrores() {
        $this->_verErrores = true;
    }

    public function realizarConsulta() {
        if ($this->_consulta_directa === false) {
            // armo la consulta
            return $this->controlesConsulta(self::obtenerConsulta());
        } else {
            return $this->controlesConsulta(self::obtenerConsulta());
        }
    }

    public function obtenerConsulta() {

        if ($this->_consulta_directa === false) {

            if (($this->_campos != '') && ($this->_contador != '')) {
                $campos = $this->_contador . ', ' . $this->_campos;
            } elseif (($this->_campos == '') && ($this->_contador != '')) {
                $campos = $this->_contador;
            } else {
                $campos = $this->_campos;
            }

            $tablas = Bases_Registro__tablas::armado($this->_tablas, $this->_tablaDerecha, $this->_tablaDerechaDif, $this->_condicionesDerecha, $this->_tablaDerechaSub, $this->_tablaDerechaDifSub, $this->_condicionesDerechaSub);

            if ($this->_condiciones || $this->_condicionesArray) {
                $where = ' WHERE ';
            } else {
                $where = '';
            }

            // armo la consulta
            return "SELECT " . "\n" . $campos . " FROM " . $tablas . $where . $this->_condiciones . $this->_condicionesArray . $this->_grupo . $this->_orden . $this->_limite . "\n";
        } else {
            return $this->_consulta_directa . $this->_orden . $this->_limite;
        }
    }

    public function obtenerConsultaUnionTodos($tabla_nombre = null) {

        if ($this->_condiciones) {
            $where = ' WHERE ';
        } else {
            $where = '';
        }

        if ($tabla_nombre == null) {
            $tabla_nombre = 'kk_tabla_union';
        }

        $unionTablas = implode(' UNION ALL ', $this->_unionTablas);
        $consulta = 'SELECT * FROM (' . "\n" . $unionTablas . "\n" . ') AS ' . $tabla_nombre . ' ' . $where . $this->_condiciones . $this->_grupo . $this->_orden . $this->_limite . ';';
        return $this->controlesConsulta($consulta);
    }

    public function obtenerConsultaUnionDistintos($tabla_nombre = null) {

        if ($this->_condiciones) {
            $where = ' WHERE ' . "\n";
        } else {
            $where = '';
        }

        $unionTablas = implode(' UNION DISTINCT ', $this->_unionTablas);
        $consulta = 'SELECT * FROM (' . $unionTablas . ') AS ' . $tabla_nombre . ' ' . $where . $this->_condiciones . $this->_grupo . $this->_orden . $this->_limite . ';';
        return $this->controlesConsulta($consulta);
    }

    private function controlesConsulta($consulta) {

        if ($this->_verConsulta) {
            echo '<br />#--<br />' . nl2br($consulta) . '<br />--#<br />';
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
