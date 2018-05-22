<?php

class ArchivoObtenerDatos {

    private $_nombre_archivo;
    private $_directorio;
    private $_separador = ';';
    private $_contieneTitulos = false;
    private $_campoInicioLineaEliminar = 0;
    private $_campoFinLineaEliminar = 0;
    private $_eliminarComillasInicio_fin = false;
    private $_mostrarErrores = true;

    public function archivo($nombre_archivo) {
        $this->_nombreArchivo = $nombre_archivo;
    }

    public function directorio($directorio) {
        $this->_directorio = $directorio . '/';
    }

    public function separador($separador) {
        $this->_separador = $separador;
    }

    public function contieneTitulos() {
        $this->_contieneTitulos = true;
    }

    public function camposInicioLineaEliminar($campo_inicio_linea_eliminar) {
        $this->_campoInicioLineaEliminar = $campo_inicio_linea_eliminar;
    }

    public function camposFinLineaEliminar($campo_fin_linea_eliminar) {
        $this->_campoFinLineaEliminar = $campo_fin_linea_eliminar;
    }

    public function eliminarComillasInicioFin() {
        $this->_eliminarComillasInicioFin = true;
    }

    public function mostrarErrores() {
        $this->_mostrarErrores = true;
    }

    public function obtenerDatos() {

        $linea_numero = 0;

        if (file_exists($this->_directorio . $this->_nombreArchivo)) {

            $fp = fopen($this->_directorio . $this->_nombreArchivo, "r");

            while (!feof($fp)) {

                $linea_original = fgets($fp);

                // eliminacion de primeros y ultimos elementos de las lineas
                if ($this->_campoInicioLineaEliminar != 0) {
                    $linea_original = substr($linea_original, $this->_campoInicioLineaEliminar);
                }
                if ($this->_campoFinLineaEliminar != 0) {
                    $linea_original = substr($linea_original, 0, -$this->_campoFinLineaEliminar);
                }

                unset($linea);

                if (trim($linea_original) != '') {

                    $linea = explode($this->_separador, mb_convert_encoding($linea_original, "UTF-8", "ASCII,JIS,UTF-8,EUC-JP,SJIS,ISO-8859-1,UTF-16LE"));
                }

                $elemento = 0;

                if (isset($linea) && is_array($linea)) {
                    foreach ($linea as $campo) {

                        $reemplazo = array("\n", "\r");
                        $campo = str_replace($reemplazo, '', trim($campo));

                        if ($this->_eliminarComillasInicioFin) {
                            if ((substr($campo, 0, 1) == "\"") && (substr($campo, 0, 1) == "\"")) {
                                $campo = substr($campo, 1);
                            }
                            if ((substr($campo, -1, 1) == "\"") && (substr($campo, -1, 1) == "\"")) {
                                $campo = substr($campo, 0, -1);
                            }
                        }

                        if (($this->_contieneTitulos === true) && ($linea_numero == 0)) {
                            $titulos[$elemento] = trim($campo);
                        } else {

                            if (($this->_contieneTitulos === true) && ($linea_numero != 0)) {
                                $id = trim($titulos[$elemento]);
                            } else {
                                $id = trim($elemento);
                            }

                            $array[$linea_numero][$id] = $campo;
                        }
                        $elemento++;
                    }
                }
                $linea_numero++;
            }

            fclose($fp);

            return $array;
        } else {
            return false;
        }
    }

}
