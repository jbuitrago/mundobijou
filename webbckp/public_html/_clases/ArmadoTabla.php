<?php

class ArmadoTabla extends PlantillaFunciones {

    private $_nombreArchivo;
    private $_completarFilas = false;
    private $_tablaInicio = "<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">";
    private $_columnas;
    private $_filas;
    private $_conBordes = true;
    private $_contenido;
    private $_datos = Array();
    private $_bordes = Array();
    private $_key = 0;
    private $_contenidoVacio = false;
    private $_extensionPlantilla = 'tpl';
    private $_directorioRelativo = '';
    private $_elementosPlantilla = array('tabla', 'superior_izquierdo', 'superior_intermedio', 'superior_centro', 'superior_derecho', 'intermedio_izquierdo', 'intermedio_centro', 'intermedio_derecho', 'medio_izquierdo', 'medio_intermedio', 'medio_centro', 'medio_derecho', 'inferior_izquierdo', 'inferior_intermedio', 'inferior_centro', 'inferior_derecho', 'contenido', 'contenido_vacio');
    private $_contadorElementos = 0;
    private $_var_tpl_glob = array();

    public function __construct() {

        $this->_bordes['superior']['izquierdo'] = '';
        $this->_bordes['superior']['intermedio'] = '';
        $this->_bordes['superior']['centro'] = '';
        $this->_bordes['superior']['derecho'] = '';
        $this->_bordes['intermedio']['izquierdo'] = '';
        $this->_bordes['intermedio']['centro'] = '';
        $this->_bordes['intermedio']['derecho'] = '';
        $this->_bordes['medio']['izquierdo'] = '';
        $this->_bordes['medio']['intermedio'] = '';
        $this->_bordes['medio']['centro'] = '';
        $this->_bordes['medio']['derecho'] = '';
        $this->_bordes['inferior']['izquierdo'] = '';
        $this->_bordes['inferior']['intermedio'] = '';
        $this->_bordes['inferior']['centro'] = '';
        $this->_bordes['inferior']['derecho'] = '';
    }

    public function asignarGlobal($variable, $valor = NULL) {
        VariableSet::globales($variable, $valor);
    }

    public function nombreArchivo($nombre_archivo) {
        $this->_nombreArchivo = $nombre_archivo;
    }

    public function datos($datos) {
        $this->_datos = $datos;
    }

    public function nColumnas($columnas) {
        $this->_columnas = $columnas;
    }

    public function nFilas($filas) {
        $this->_filas = $filas;
    }

    public function completarFilas() {
        $this->_completarFilas = true;
    }

    private function armarTemplate() {

        $plantilla_archivo = $this->_obtenerNombreTpl($this->_nombreArchivo);

        if (file_exists($plantilla_archivo)) {

            $plantilla = file_get_contents($plantilla_archivo);

            // reemplazo de elementos
            foreach ($this->_elementosPlantilla as $valor) {

                preg_match("/\\{" . $valor . "}(.*?)\\{\\/" . $valor . "\\}/is", $plantilla, $tpl_elemento);

                if (isset($tpl_elemento[1])) {
                    switch ($valor) {
                        case 'tabla':
                            $this->_tablaInicio = $tpl_elemento[1];
                            break;
                        case 'contenido':
                            $this->_contenido = $tpl_elemento[1];
                            break;
                        case 'contenido_vacio':
                            $this->_contenidoVacio = $tpl_elemento[1];
                            break;
                        default:
                            $elementos = explode("_", $valor);
                            $this->_borde($elementos[0], $elementos[1], $tpl_elemento[1]);
                    }
                }
            }
        }
    }

    private function _borde($vertical, $horizontal, $contenido = NULL) {
        $this->_bordes[$vertical][$horizontal] = $contenido;
        $this->_contadorElementos++;
    }

    public function tablaImp() {

        $this->armarTemplate();

        if ($this->_contadorElementos == 0) {

            $this->_bordes['intermedio']['izquierdo'] = '<tr><td valign="top">';
            $this->_bordes['intermedio']['centro'] = '</td><td valign="top">';
            $this->_bordes['intermedio']['derecho'] = '</td></tr>';
        }

        $this->_var_tpl = $this->_datos;

        echo $this->_tablaInicio;
        echo "<tr>";

        for ($fila = 1; $fila <= $this->_filas + 1; $fila++) {
            for ($j = 1; $j <= 2; $j++) { // hace dos recorridos por linea
                for ($columna = 1; $columna <= $this->_columnas; $columna++) {

                    // no completa la tabla
                    if (($this->_completarFilas == false) && ($columna == 1) && !isset($this->_datos[$this->_key])) {
                        echo $this->_tablaImpCerrar();
                        break 3;
                    }

                    if ($fila == 1) {                                                        //primera fila
                        if ($j == 1) {
                            if ($columna == 1) {                                             // primera columna
                                echo $this->_bordes['superior']['izquierdo'];
                                echo $this->_bordes['superior']['intermedio'];
                                echo $this->_bordes['superior']['centro'];
                            } elseif (($columna > 1) && ($columna < $this->_columnas)) {    // columna intermedia
                                echo $this->_bordes['superior']['intermedio'];
                                echo $this->_bordes['superior']['centro'];
                            } elseif ($columna == $this->_columnas) {                         // ultima columna
                                echo $this->_bordes['superior']['intermedio'];
                                echo $this->_bordes['superior']['derecho'];
                                echo "</tr><tr>";
                            }
                        } else {
                            if ($columna == 1) {                                             // primera columna
                                echo $this->_bordes['intermedio']['izquierdo'];
                                echo $this->_datosInternos();
                            } elseif (($columna > 1) && ($columna < $this->_columnas)) {    // columna intermedia
                                echo $this->_bordes['intermedio']['centro'];
                                echo $this->_datosInternos();
                            } elseif ($columna == $this->_columnas) {                         // ultima columna
                                echo $this->_bordes['intermedio']['centro'];
                                echo $this->_datosInternos();
                                echo $this->_bordes['intermedio']['derecho'];
                                echo "</tr><tr>";
                            }
                        }
                    } elseif (($fila > 1) && ($fila <= $this->_filas)) {                    //fila intermedia
                        if ($j == 1) {
                            if ($columna == 1) {                                             // primera columna
                                echo $this->_bordes['medio']['izquierdo'];
                                echo $this->_bordes['medio']['intermedio'];
                                echo $this->_bordes['medio']['centro'];
                            } elseif (($columna > 1) && ($columna < $this->_columnas)) {    // columna intermedia
                                echo $this->_bordes['medio']['intermedio'];
                                echo $this->_bordes['medio']['centro'];
                            } elseif ($columna == $this->_columnas) {                         // ultima columna
                                echo $this->_bordes['medio']['intermedio'];
                                echo $this->_bordes['medio']['derecho'];
                                echo "</tr><tr>";
                            }
                        } else {
                            if ($columna == 1) {                                             // primera columna
                                echo $this->_bordes['intermedio']['izquierdo'];
                                echo $this->_datosInternos();
                                //echo  eval( "?'>".$this->_datosInternos()."<?" );
                            } elseif (($columna > 1) && ($columna < $this->_columnas)) {    // columna intermedia
                                echo $this->_bordes['intermedio']['centro'];
                                echo $this->_datosInternos();
                                //echo  eval( "?'>".$this->_datosInternos()."<?" );
                            } elseif ($columna == $this->_columnas) {                         // ultima columna
                                echo $this->_bordes['intermedio']['centro'];
                                echo $this->_datosInternos();
                                //echo  eval( "?'>".$this->_datosInternos()."<?" );
                                echo $this->_bordes['intermedio']['derecho'];
                                echo "</tr><tr>";
                            }
                        }
                    } elseif ($fila == $this->_filas + 1) {                                     //ultima fila
                        if ($j == 1) {
                            if ($columna == 1) {                                             // primera columna
                                echo $this->_bordes['inferior']['izquierdo'];
                                echo $this->_bordes['inferior']['intermedio'];
                                echo $this->_bordes['inferior']['centro'];
                            } elseif (($columna > 1) && ($columna < $this->_columnas)) {    // columna intermedia
                                echo $this->_bordes['inferior']['intermedio'];
                                echo $this->_bordes['inferior']['centro'];
                            } elseif ($columna == $this->_columnas) {                         // ultima columna
                                echo $this->_bordes['inferior']['intermedio'];
                                echo $this->_bordes['inferior']['derecho'];
                            }
                        }
                    }
                }
            }
        }
        echo "</tr>";
        echo "</table>";
    }

    private function _tablaImpCerrar() {

        for ($columna = 1; $columna <= $this->_columnas; $columna++) {
            if ($columna == 1) {                                            // primera columna
                echo $this->_bordes['inferior']['izquierdo'];
                echo $this->_bordes['inferior']['intermedio'];
                echo $this->_bordes['inferior']['centro'];
            } elseif (($columna > 1) && ($columna < $this->_columnas)) {  // columna intermedia
                echo $this->_bordes['inferior']['intermedio'];
                echo $this->_bordes['inferior']['centro'];
            } elseif ($columna == $this->_columnas) {                       // ultima columna
                echo $this->_bordes['inferior']['intermedio'];
                echo $this->_bordes['inferior']['derecho'];
            }
        }
    }

    private function _datosInternos() {

        if (
                $this->_contenidoVacio === false
        ) {
            $tabla = $this->_PlantillaReemplazos('contenido_vacio', $this->_key);
        } elseif (
                $this->_contenidoVacio !== false && isset($this->_datos[$this->_key])
        ) {
            $tabla = $this->_PlantillaReemplazos('contenido', $this->_key);
        } else {
            $tabla = $this->_contenidoVacio;
        }

        $this->_key++;

        return $tabla;
    }

    private function _PlantillaReemplazos($tipo, $key) {

        $tpl = new PlantillaReemplazos();
        $tpl->contenidoArchivo($this->_contenido);

        $tpl->nombreArchivo($this->_nombreArchivo . '_' . $tipo);

        foreach ($this->_datos[$key] as $elemento => $valor_elemento) {

            $tpl->asignar($elemento, $valor_elemento);
        }

        return $tpl->obtenerPlantilla();
    }

}
