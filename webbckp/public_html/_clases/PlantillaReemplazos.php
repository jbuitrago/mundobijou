<?php

class PlantillaReemplazos extends PlantillaFunciones {

    private $_nombreArchivo;
    private $_nombrePlantilla = false;
    private $_var_tpl = array();
    private $_var_tpl_glob = array();
    private $_controlTpl = false;
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

    public function levantarCache($segundos_tablas = false) {

        if ($segundos_tablas === false) {
            return false;
        }

        $plantilla_archivo_cache = $this->_nombreCache(debug_backtrace(false));

        if (
                file_exists($plantilla_archivo_cache) &&
                (
                (
                ($segundos_tablas > 0) &&
                ((filemtime($plantilla_archivo_cache) + $segundos_tablas) >= time())
                ) ||
                (
                (is_array($segundos_tablas)) &&
                (filemtime($plantilla_archivo_cache) >= BDControlModificaciones::controlMasNuevo())
                )
                ) &&
                VariableGet::sistema('generar_cache') &&
                !VariableGet::sistema('mostrar_errores') &&
                (count($_POST) == 0)
        ) {
            // levanta el cache HTML
            echo file_get_contents($plantilla_archivo_cache);
            return false;
        } elseif (
                ($segundos_tablas > 0) &&
                VariableGet::sistema('generar_cache') &&
                !VariableGet::sistema('mostrar_errores') &&
                (count($_POST) == 0)
        ) {
            touch($plantilla_archivo_cache, time());
            $this->_generar_cache_html = true;
            return true;
        } else {
            return true;
        }
    }

    public function levantarCacheMarco($segundos_tablas = false, $prefijo = '') {

        if ($segundos_tablas === false) {
            return false;
        }

        if ($prefijo != '') {
            $prefijo = $prefijo . '.';
        }

        $plantilla_archivo_cache = $this->_nombreCache(debug_backtrace(false), 'm.' . $prefijo);

        if (($this->_nombreArchivo == VariableGet::globales('seccion_actual')) && ($this->_nombrePlantilla === false)) {
            $archivo_tpl_php = $this->_nombreArchivo;
        } elseif ($this->_nombrePlantilla !== false) {
            $archivo_tpl_php = $this->_nombrePlantilla;
        }

        $mostrar_cache = true;

        //*
        // solo controla el archivo tpl y php de la seccion, no controla el marco y los sub_templates
        if (file_exists($plantilla_archivo_cache)) {
            if (
                    file_exists($this->_obtenerDirectorio(VariableGet::sistema('directorio_plantillas')) . '/' . $archivo_tpl_php . '.tpl') &&
                    filemtime($plantilla_archivo_cache) < filemtime($this->_obtenerDirectorio(VariableGet::sistema('directorio_plantillas')) . '/' . $archivo_tpl_php . '.tpl')
            ) {
                $mostrar_cache = false;
            }
            if (
                    file_exists($this->_obtenerDirectorio(VariableGet::sistema('directorios_php')) . '/' . $archivo_tpl_php . '.php') &&
                    filemtime($plantilla_archivo_cache) < filemtime($this->_obtenerDirectorio(VariableGet::sistema('directorios_php')) . '/' . $archivo_tpl_php . '.php')
            ) {
                $mostrar_cache = false;
            }
        } else {
            $mostrar_cache = false;
        }
        /* /
          // controla todos los archivos tpl y php es vastante mas lento que la opcion superior
          $tmp_tpl_php = 0;
          foreach (glob($this->_obtenerDirectorio(VariableGet::sistema('directorio_plantillas'), GLOB_NOSORT) . '/*.tpl') as $nombre_fichero) {
          if(filemtime($nombre_fichero)>$tmp_tpl_php){
          $tmp_tpl_php = filemtime($nombre_fichero);
          }
          }
          foreach (glob($this->_obtenerDirectorio(VariableGet::sistema('directorio_plantillas_varias'), GLOB_NOSORT) . '/*.tpl') as $nombre_fichero) {
          if(filemtime($nombre_fichero)>$tmp_tpl_php){
          $tmp_tpl_php = filemtime($nombre_fichero);
          }
          }
          foreach (glob($this->_obtenerDirectorio(VariableGet::sistema('directorios_php'), GLOB_NOSORT) . '/*.php') as $nombre_fichero) {
          if(filemtime($nombre_fichero)>$tmp_tpl_php){
          $tmp_tpl_php = filemtime($nombre_fichero);
          }
          }
          if(filemtime($plantilla_archivo_cache) < $tmp_tpl_php){
          $mostrar_cache = false;
          }
          // */


        if (
                file_exists($plantilla_archivo_cache) &&
                $mostrar_cache &&
                (
                (
                is_numeric($segundos_tablas) &&
                ((filemtime($plantilla_archivo_cache) + $segundos_tablas) >= time())
                ) ||
                (
                ($segundos_tablas == '*') &&
                file_exists(VariableGet::sistema('directorio_cache_base_tablas') . '/TODAS.cache') &&
                (filemtime($plantilla_archivo_cache) >= filemtime(VariableGet::sistema('directorio_cache_base_tablas') . '/TODAS.cache'))
                ) ||
                (
                is_array($segundos_tablas) &&
                (filemtime($plantilla_archivo_cache) >= BDControlModificaciones::controlMasNuevo($segundos_tablas))
                )
                ) &&
                (self::$_es_seccion === true) &&
                VariableGet::sistema('generar_cache') &&
                !VariableGet::sistema('mostrar_errores') &&
                (count($_POST) == 0)
        ) {
            // levanta el cache HTML
            touch($plantilla_archivo_cache, time());
            echo file_get_contents($plantilla_archivo_cache);

            $this->_borrarCacheViejo();

            exit;
        }
        self::$cacheMarcoNombre = $plantilla_archivo_cache;
        self::$cacheMarcoGenerar = true;
        return true;
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
            $plantilla = file_get_contents($plantilla_archivo_compilado);
        } else {
            if (file_exists($plantilla_archivo) && ($this->_contenidoArchivo == '')) {
                $plantilla = file_get_contents($plantilla_archivo);
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

                $plantilla = file_get_contents($this->_obtenerNombreTpl($nombre));
                $plantilla = PlantillaElementos::plantilla($plantilla, $this->_nombreArchivo);

                // para armar la plantilla
                return eval("?>" . $plantilla);
            }
        } else {
            return false;
        }
    }

    /**
     * Obtencion del directorio.
     *
     * @return string $directorio
     */
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

        if (VariableControl::getParam('p')) {
            $variablesGet .= VariableControl::getParam('p');
        }

        return $this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_plantillas')) . '/' . $prefijo . sha1($this->_nombreArchivo . $variablesGet) . '.cache';
    }

    /**
     * Obtencion del directorio.
     *
     * @return string $directorio
     */
    private function _borrarCacheViejo() {

        $fecha = file_get_contents($this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_sistema')) . '/borrar_cache_fecha.cache');

        if ($fecha > mktime(0, 0, 0, date("m"), date("d"), date("Y"))) {
            $matriz = glob($this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_plantillas')) . '/*.cache');
            if (is_array($matriz)) {
                foreach ($matriz as $dir_nombre_archivo) {
                    //tomar archivos por fecha y borrar los que tengan mas de un mes
                    if (fileatime($dir_nombre_archivo) < (time() - 30 * 24 * 60 * 60)) {
                        unlink($dir_nombre_archivo);
                    }
                }
            }
            file_put_contents($this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_sistema')) . '/borrar_cache_fecha.cache', mktime(0, 0, 0, date("m"), date("d"), date("Y")));
        } elseif ($fecha == '') {
            file_put_contents($this->_obtenerDirectorio(VariableGet::sistema('directorio_cache_sistema')) . '/borrar_cache_fecha.cache', mktime(0, 0, 0, date("m"), date("d") - 1, date("Y")));
        }
    }

    /**
     * Obtencion del directorio.
     *
     * @return string $directorio
     */
    private function _obtenerDirectorio($directorio) {

        $url_actual = getcwd();
        chdir($directorio);
        $directorio = getcwd();
        chdir($url_actual);

        return $directorio;
    }

}
