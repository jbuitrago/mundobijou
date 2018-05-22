<?php

class PlantillaReemplazos extends PlantillaFunciones {

    private $_nombreArchivo;
    private $_nombrePlantilla = false;
    private $_var_tpl = array();
    private $_var_tpl_glob = array();
    private $_controlTpl = false;
    private $_cache = 0;
    private $_contenidoArchivo = '';
    private $_generar_cache_html = false;
    private static $_seccionTplCont = 0;
    private static $_es_seccion = true;
    public static $cacheMarco = 0;
    public static $cacheMarcoNombre;
    public static $cacheMarcoGenerar = false;

    function __construct($nombre = null) {
        if ($nombre !== null) {
            $this->_nombreArchivo = $nombre;
        }
    }

    public function nombreArchivo($nombre) {
        $this->_nombreArchivo = $nombre;
    }

    public function nombrePlantillaMarco($nombre) {
        VariableSet::indexMarco($nombre);
    }

    public function nombrePlantilla($nombre) {
        $this->_nombrePlantilla = $nombre;
    }

    public function asignar($variable, $valor = NULL) {
        $this->_var_tpl[$variable] = $valor;
    }

    public function asignarGlobal($variable, $valor = NULL) {
        VariableSet::globales($variable, $valor);
    }

    public function controlTpl() {
        $this->_controlTpl = true;
    }

    public function modificarArchivoMarco($nombre) {
        VariableSet::indexMarco($nombre);
    }

    public function contenidoArchivo($contenido) {
        $this->_contenidoArchivo = $contenido;
    }

    public function cache($segundos) {
        $this->_cache = $segundos;
    }

    public function levantarCache($segundos) {

        if ($this->_cache == 0) {
            return false;
        }

        $plantilla_archivo_cache = $this->_nombreCache(debug_backtrace(false));

        if (
                ($this->_cache > 0) &&
                VariableGet::sistema('generar_cache') &&
                !VariableGet::sistema('mostrar_errores') &&
                (count($_POST) == 0) &&
                file_exists($plantilla_archivo_cache) &&
                ((filemtime($plantilla_archivo_cache) + $this->_cache) > time())
        ) {

            // levanta el cache HTML
            echo file_get_contents($plantilla_archivo_cache, FILE_USE_INCLUDE_PATH);
            return true;
        } elseif (
                ($this->_cache > 0) &&
                VariableGet::sistema('generar_cache') &&
                !VariableGet::sistema('mostrar_errores') &&
                (count($_POST) == 0)
        ) {

            $this->_generar_cache_html = true;
        }
    }

    public function levantarCacheMarco() {

        if ($this->_cache == 0) {
            return false;
        }

        $plantilla_archivo_cache_marco = $this->_nombreCache(debug_backtrace(false), 'm.');

        if (
                (self::$_es_seccion == true) &&
                ($this->_cache > 0) &&
                VariableGet::sistema('generar_cache') &&
                !VariableGet::sistema('mostrar_errores') &&
                (count($_POST) == 0)
        ) {
            if (
                    file_exists($plantilla_archivo_cache_marco) &&
                    ((filemtime($plantilla_archivo_cache_marco) + $this->_cache) > time())
            ) {
                $plantilla = file_get_contents($plantilla_archivo_cache_marco, FILE_USE_INCLUDE_PATH);
                echo $plantilla;
                exit;
            } else {
                self::$cacheMarcoNombre = $plantilla_archivo_cache_marco;
                self::$cacheMarcoGenerar = true;
            }
        }
    }

    public function obtenerPlantilla($imprimir = true) {

        $this->_var_tpl_glob = VariableControl::getArrayGlobales();

        $plantilla_archivo_cache = $this->_nombreCache(debug_backtrace(false));

        // verifico si hay que generar un nuevo cache HTML
        if ($this->_generar_cache_html) {
            ob_start();
        }

        // obtengo las plantillas de secciones u otras			
        if (($this->_nombreArchivo == VariableGet::globales('seccion_actual')) && ($this->_nombrePlantilla === false)) {
            $plantilla_archivo = $this->_obtenerDirectorio(VariableGet::sistema('directorio_plantillas')) . '/' . $this->_nombreArchivo . '.tpl';
        } elseif ($this->_nombrePlantilla !== false) {
            $plantilla_archivo = $this->_obtenerDirectorio(VariableGet::sistema('directorio_plantillas')) . '/' . $this->_nombrePlantilla . '.tpl';
        } else {
            // busca en directorio alternativo si no es seccion
            $plantilla_archivo = $this->_obtenerDirectorio(VariableGet::sistema('directorio_plantillas_varias')) . '/' . $this->_nombreArchivo . '.tpl';
        }

        $plantilla_archivo_compilado = $this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_compilados')) . '/' . sha1($this->_nombreArchivo) . '.cache';

        // verifico si hay que levantar el cache PHP o generarlo
        if (
                !VariableGet::sistema('mostrar_errores') &&
                VariableGet::sistema('generar_cache') &&
                file_exists($plantilla_archivo) &&
                file_exists($plantilla_archivo_compilado) &&
                ( filemtime($plantilla_archivo_compilado) > filemtime($plantilla_archivo) )
        ) {
            $plantilla = file_get_contents($plantilla_archivo_compilado, FILE_USE_INCLUDE_PATH);
        } else {
            if (file_exists($plantilla_archivo) && ($this->_contenidoArchivo == '')) {
                $plantilla = file_get_contents($plantilla_archivo, FILE_USE_INCLUDE_PATH);
            } else {
                $plantilla = $this->_contenidoArchivo;
            }

            // armado plantilla
            $plantilla = PlantillaElementos::plantilla($plantilla, $this->_nombreArchivo);

            // guarda el cache del template compilado
            if (
                    !VariableGet::sistema('mostrar_errores') &&
                    VariableGet::sistema('generar_cache')
            ) {
                file_put_contents($plantilla_archivo_compilado, $plantilla, LOCK_EX);
            }
        }

        // mostrar plantilla para control
        if ($this->_controlTpl) {
            echo MostrarErrores::plantilla($plantilla, $this->_nombreArchivo, $this->_var_tpl);
        }

        if ($imprimir) {
            // para armar la plantilla
            eval("?>" . $plantilla);
            /* // descomentar para controlar los problemas en la plantilla
              echo $plantilla;
              // */
        } else {
            self::$_es_seccion = false;
            return $plantilla;
        }

        // guarda la cache de template como HTML
        if ($this->_generar_cache_html) {
            echo $plantilla_archivo_cache;
            echo $this->_nombreArchivo . $variablesGet;
            $plantilla_cache = ob_get_contents();
            ob_end_clean();
            echo $plantilla_cache;
            file_put_contents($plantilla_archivo_cache, $plantilla_cache, LOCK_EX);
        }


        self::$_es_seccion = false;
    }

    private function _plantilla($nombre = null, $seccion = null) {

        if ($seccion == 's') {
            if (self::$_seccionTplCont < 5) {
                echo VariableGet::seccion();
                self::$_seccionTplCont++;
            } else {
                return false;
            }
        }

        if ($this->_obtenerNombreTpl($nombre)) {

            if (substr($this->_obtenerNombreTpl($nombre), -4, 4) == '.php') {

                include($this->_obtenerNombreTpl($nombre));
            } else {

                $plantilla = file_get_contents($this->_obtenerNombreTpl($nombre), FILE_USE_INCLUDE_PATH);
                $plantilla = PlantillaElementos::plantilla($plantilla, $this->_nombreArchivo);

                // para armar la plantilla
                return eval("?>" . $plantilla);
            }
        } else {
            return false;
        }
    }

    private function _obtenerDirectorio($directorio) {

        $url_actual = getcwd();
        chdir($directorio);
        $directorio = getcwd();
        chdir($url_actual);

        return $directorio;
    }

    private function _nombreCache($archivo_real, $prefijo = '') {

        // si no se envio el nombre del archivo, se toma el nombre real
        if (!$this->_nombreArchivo) {
            // nombre real del archivo, este se toma directamente del script que llamo al metodo
            //$archivo_real = debug_backtrace(false);
            $this->_nombreArchivo = preg_replace("/([a-zA-Z0-9._.-.]+)\.php/", "\${1}", basename($archivo_real[0]['file']));
        }

        // control de archivo en cache
        $variablesGet = '';
        if (isset($_GET) && is_array($_GET)) {
            $variablesGet = implode('-', $_GET);
        }

        return $this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_plantillas')) . '/' . $prefijo . sha1($this->_nombreArchivo . $variablesGet) . '.cache';
    }

}
