<?php

class Inicio {

    private static $_pathPublico;
    private static $_pathAdministrador;
    private static $_confVars = array();
    private static $_ejecutado = false;

    public static function obtenerConfiguracion($path_publico, $path_conf, $arch_conf) {

        if (self::$_ejecutado === false) {

            self::$_ejecutado = true;

            self::$_pathPublico = $path_publico;
            self::$_pathAdministrador = getcwd();

            $url_actual = getcwd();
            chdir($path_conf);
            $path_configuracion = getcwd();
            chdir($url_actual);

            if (file_exists($path_configuracion . '/' . $arch_conf)) {
                include($path_configuracion . '/' . $arch_conf);
            } else {
                die('Archivo de Configuracion');
            }

            self::$_confVars['bases_tipo'] = $var['basedatos_tipo'];
            self::$_confVars['plantilla'] = $var['plantilla'];
            self::$_confVars['estilos'] = $var['estilos'];
            self::$_confVars['archivos_externos'] = $var['archivos_externos'];
            self::$_confVars['errores_control'] = $var['errores_control'];
            self::$_confVars['idiomas'] = explode(",", $var['idiomas']);
            self::$_confVars['encriptar_url'] = $var['encriptar_url'];
            self::$_confVars['nombre_servidor'] = $var['nombre_servidor'];
            self::$_confVars['mail_origen'] = $var['mail_origen'];
            self::$_confVars['servidor'] = $var['servidor'];
            self::$_confVars['basedatos'] = $var['basedatos'];
            self::$_confVars['usuario'] = $var['usuario'];
            self::$_confVars['clave'] = $var['clave'];

            if(isset($var['generar_log'])){
                self::$_confVars['generar_log'] = $var['generar_log'];
            }else{
                self::$_confVars['generar_log'] = false;
            }
            
            // reportará todos los errores
            if (self::$_confVars['errores_control'] == 's') {
                ini_set('display_errors', 1);
                error_reporting(E_ALL);
            } elseif (self::$_confVars['errores_control'] == 'n') {
                ini_set('display_errors', 0);
                error_reporting(0);
            }
            
            require_once( self::$_pathAdministrador . '/Bases/' . self::$_confVars['bases_tipo'] . '/Conexion.php' );
            Bases_Conexion::consulta(
                self::$_confVars['servidor'], self::$_confVars['basedatos'], self::$_confVars['usuario'], self::$_confVars['clave']
            );
            
            if(isset($var['dir_tablas_modificadas'])){
                $url_actual = getcwd();
                chdir(self::$_pathAdministrador);
                if( @chdir($var['dir_tablas_modificadas']) ){
                    self::$_confVars['dir_tablas_modificadas'] = getcwd();
                }else{
                    if (Inicio::confVars('errores_control') == 's') {
                        die('Problema con variable de configuracion: $dir_tablas_modificadas');
                    }
                    self::$_confVars['dir_tablas_modificadas'] = false;
                }
                chdir($url_actual);
            }else{
                self::$_confVars['dir_tablas_modificadas'] = false;
            }
            
        }
    }

    public static function path() {
        return self::$_pathAdministrador;
    }

    public static function pathPublico() {
        return self::$_pathPublico;
    }

    public static function confVars($nombre, $key = null) {
        if ($key === NULL) {
            return self::$_confVars[$nombre];
        } else {
            return self::$_confVars[$nombre][$key];
        }
    }

    public static function usuario($nombre) {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario'][$nombre])) {
            return $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario'][$nombre];
        } else {
            return false;
        }
    }

}

function __autoload($class_name) {

    $seccion = explode('_', $class_name);
    $clase = substr($class_name, strlen($seccion[0]) + 1);

    if (file_exists(Inicio::path() . '/' . $seccion[0] . '/' . $clase . '.php')) {
        require_once Inicio::path() . '/' . $seccion[0] . '/' . $clase . '.php';
    } elseif ($seccion[0] == 'Componentes') {
        if (file_exists(Inicio::path() . '/' . $seccion[0] . '/Componente.php')) {
            require_once Inicio::path() . '/' . $seccion[0] . '/Componente.php';
        }
    } elseif ($seccion[0] == 'Bases') {
        if (file_exists(Inicio::path() . '/' . $seccion[0] . '/' . Inicio::confVars('bases_tipo') . '/' . $clase . '.php')) {
            require_once Inicio::path() . '/' . $seccion[0] . '/' . Inicio::confVars('bases_tipo') . '/' . $clase . '.php';
        }
    } elseif ($seccion[0] == 'Informes') {
        if (file_exists(Inicio::path() . '/Informes/' . $seccion[1] . '/index.php')) {
            if (!isset($seccion[2])) {
                require_once Inicio::path() . '/Informes/' . $seccion[1] . '/index.php';
            } else {
                require_once Inicio::path() . '/Informes/' . $seccion[1] . '/' . $seccion[2] . '.php';
            }
        }
    } elseif ($_GET['kk_generar'] == '6') {
        // llamada de clases de desarrollos
        if (file_exists(Inicio::path() . '/Desarrollos/' . $_GET['kk_desarrollo'] . '/_clases_sitio/' . $class_name . '.php')) {
            require_once Inicio::path() . '/Desarrollos/' . $_GET['kk_desarrollo'] . '/_clases_sitio/' . $class_name . '.php';
        } elseif (file_exists(Inicio::path() . '/DesarrollosSistema/_clases/' . $class_name . '.php')) {
            require_once Inicio::path() . '/DesarrollosSistema/_clases/' . $class_name . '.php';
        }
    }

    if (Inicio::confVars('errores_control') == 's') {
        Generales_ErroresControl::setClase($seccion[0], $clase);
    }
}
