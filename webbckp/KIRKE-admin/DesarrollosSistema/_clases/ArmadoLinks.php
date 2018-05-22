<?php

class ArmadoLinks extends PlantillaFunciones {

    static private $_variablesURL;
    static private $_subdirectorios = false;
    static private $_cacheURL;
    static private $_directorioCache;
    static private $_archivoNombre = '';

    public static function url($valores) {

        return parent::_links($valores);
    }

    public static function setVariablesGet() {

        // asigno las variables de URL de pagina anterior y limpio para comenzar a cargar nuevamente
        self::setVariablesURL();

        unset($_GET);
        $variables_get = explode("/", self::urlNvaLimpia());
        array_shift($variables_get);

        // para guardar el cache de URLs segun su nivel, para el armado del XML
        if ((count($variables_get) - VariableGet::sistema('seccion_actual_nivel')) > 1) {
            self::$_subdirectorios = true;
        }

        if (VariableGet::sistema('seccion_actual_nivel') == 0) {
            $posicion_get = 0;
        } else {
            $posicion_get = -VariableGet::sistema('seccion_actual_nivel');
        }

        // genera todas las variables $_GET para el sistema
        foreach ($variables_get as $valor) {

            VariableControl::setGet($valor, $posicion_get);
            $posicion_get++;
        }

        // obtiene el ultimo numero que aparece en la URL sin ".html"
        // para ser usado como $_GET['id']
        preg_match("/[0-9]+$/", self::urlNvaLimpia(), $id);

        if (count($id) > 0) {

            $id = (int) $id[0];
            VariableControl::setGet($id, 'id');
        }

        // obtiene los parametros que se envian por URL
        if (strpos($_SERVER['REQUEST_URI'], '?') !== false) {

            $valor_param = explode('?', $_SERVER['REQUEST_URI']);
            VariableControl::setGetParam($valor_param[1]);
        }

        VariableControl::bloquearGetParam();
    }

    public static function setCacheURL() {

        $urlNvaLimpia = self::urlNvaLimpia();

        if (self::$_archivoNombre == '') {
            self::$_archivoNombre = sha1($urlNvaLimpia) . '.cache';
        }

        if (self::$_subdirectorios === false) {
            // si es solo un nivel principal	
            self::$_directorioCache = '/principal/';
        } else {
            // si es un nivel secundario y primer nivel con variables
            self::$_directorioCache = '/secundario/';
        }

        if (self::_getVariablesURL($urlNvaLimpia) !== false) {

            if ($urlNvaLimpia != self::_getVariablesURL($urlNvaLimpia)) {

                // guarda el cache
                self::$_cacheURL = self::_getVariablesURL($urlNvaLimpia);

                // armado cache URL

                $archivo_cache_url = VariableGet::sistema('directorio_cache_links') . self::$_directorioCache . 'url_' . self::$_archivoNombre;

                if (!file_exists($archivo_cache_url)) {

                    file_put_contents($archivo_cache_url, self::_getVariablesURL($urlNvaLimpia), LOCK_EX);
                } elseif ((filemtime($archivo_cache_url) + (60 * 60 * 2)) < time()) {

                    if (file_get_contents($archivo_cache_url) != self::_getVariablesURL($urlNvaLimpia)) {

                        touch($archivo_cache_url, time());
                    } {
                        file_put_contents($archivo_cache_url, self::_getVariablesURL($urlNvaLimpia), LOCK_EX);
                    }
                }
            }

            // armado cache XML        
            if (VariableGet::sistema('generar_cache')) {

                $archivo_cache_xml = VariableGet::sistema('directorio_cache_links') . self::$_directorioCache . 'xml_' . self::$_archivoNombre;

                if (!file_exists($archivo_cache_xml)) {

                    file_put_contents($archivo_cache_xml, $urlNvaLimpia, LOCK_EX);
                } else {

                    touch($archivo_cache_xml, time());
                }
            }
        }
    }

    public static function getCacheURL() {

        if (self::$_cacheURL != '') {
            return self::$_cacheURL;
        } else {

            if (self::$_archivoNombre == '') {
                $urlNvaLimpia = self::urlNvaLimpia();
                self::$_archivoNombre = sha1($urlNvaLimpia) . '.cache';
            }

            $archivo_cache_url_principal = VariableGet::sistema('directorio_cache_links') . self::$_directorioCache . '/principal/url_' . self::$_archivoNombre;
            $archivo_cache_url_secundario = VariableGet::sistema('directorio_cache_links') . self::$_directorioCache . '/secundario/url_' . self::$_archivoNombre;

            if (file_exists($archivo_cache_url_principal)) {

                return file_get_contents($archivo_cache_url_principal);
            } elseif (file_exists($archivo_cache_url_secundario)) {

                return file_get_contents($archivo_cache_url_secundario);
            }

            return false;
        }
    }

    public static function guardarLinkURL($urlNvaLimpia, $urlNva) {

        if (!isset($_SESSION['kk_sistema']['LinkURL'][$urlNvaLimpia]) && ($urlNvaLimpia != '')) {
            $_SESSION['kk_sistema']['LinkURL'][$urlNvaLimpia] = $urlNva;
        }
    }

    public static function setVariablesURL() {
        if (isset($_SESSION['kk_sistema']['LinkURL'])) {
            self::$_variablesURL = $_SESSION['kk_sistema']['LinkURL'];
            unset($_SESSION['kk_sistema']['LinkURL']);
        }
    }

    private static function _getVariablesURL($variable) {

        if (isset(self::$_variablesURL[$variable])) {

            return self::$_variablesURL[$variable];
        } else {

            return false;
        }
    }

    public static function urlNvaLimpia() {

        $url = explode('?', $_SERVER['REQUEST_URI']);
        return substr($url[0], 0, -5);
    }

    public static function generar_xml() {

        self::generar_xml_nvo('principal');
        self::generar_xml_nvo('secundario');
    }

    public static function generar_xml_nvo($nivel) {

        if ($nivel == 'principal') {
            $tiempo = 30;
            $changefreq = 'monthly';
            $priority = '1.0';
        } elseif ($nivel == 'secundario') {
            $tiempo = 7;
            $changefreq = 'weekly';
            $priority = '0.8';
        }


        // datos del archivo de cache de control
        $directorio_cache_control = VariableGet::sistema('directorio_cache_links');
        $archivo_cache_control = 'xml_' . $nivel . '.cache';

        // controlo si ya se hizo en control de la hora actual
        if (!file_exists($directorio_cache_control . '/' . $archivo_cache_control)) {

            // elimino el archivo de control anterior
            foreach (@glob($directorio_cache_control . 'xml_' . $nivel . '_*') as $eliminar_cache_control) {
                unlink($eliminar_cache_control);
            }

            // se crea el archivo de control nuevamente
            file_put_contents($directorio_cache_control . '/' . $archivo_cache_control, date('Y-m-d h:i:s'), LOCK_EX);

            $elementos_sitemap = '';
            foreach (@glob(VariableGet::sistema('directorio_cache_links') . '/' . $nivel . '/xml_*.cache') as $recorrer_archivos) {
                // 60 x 60 x 24 = un dia (86400)
                if (filemtime($recorrer_archivos) > (time() - (86400 * $tiempo))) {

                    $contenido = file_get_contents($recorrer_archivos);

                    $elementos_sitemap .= '   <url>
      <loc>'.$_SERVER['REQUEST_SCHEME'].'://' . $_SERVER['SERVER_NAME'] . $contenido . '.html' . '</loc>
      <lastmod>' . date("Y-m-d", filemtime($recorrer_archivos)) . '</lastmod>
      <changefreq>' . $changefreq . '</changefreq>
      <priority>' . $priority . '</priority>
   </url>
';
                }
            }

            // armo el sitemap
            self::generar_sitemap_automatico_xml($elementos_sitemap, $nivel);

            // si no existe el archivo de control, lo genero
            $archivo_control = 'sitemap.cache';
            if (!file_exists($directorio_cache_control . '/' . $archivo_control)) {
                file_put_contents($directorio_cache_control . '/' . $archivo_control, date('Y-m-d h:i:s'), LOCK_EX);
                self::generar_sitemap_xml();
            }

            // si no existe el archivo de control, lo genero
            $archivo_control = 'robots_xml.cache';
            if (!file_exists($directorio_cache_control . '/' . $archivo_control)) {
                file_put_contents($directorio_cache_control . '/' . $archivo_control, date('Y-m-d h:i:s'), LOCK_EX);
                self::generar_robots_xml();
            }
        }
    }

    public static function generar_sitemap_automatico_xml($elementos_sitemap, $nivel) {

        file_put_contents('sitemap_automatico_' . $nivel . '.xml', '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
' . $elementos_sitemap . '</urlset>
		', LOCK_EX);
    }

    public static function generar_sitemap_xml() {

        file_put_contents('sitemap.xml', '<?xml version="1.0" encoding="UTF-8"?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
   <sitemap>
      <loc>'.$_SERVER['REQUEST_SCHEME'].'://' . $_SERVER['SERVER_NAME'] . '/sitemap_automatico_principal.xml</loc>
	  <loc>'.$_SERVER['REQUEST_SCHEME'].'://' . $_SERVER['SERVER_NAME'] . '/sitemap_automatico_secundario.xml</loc>
   </sitemap>
</sitemapindex>
		', LOCK_EX);
    }

    public static function generar_robots_xml() {

        file_put_contents('robots.txt', '# robots.txt for http://kirke.ws/

# Mapa del sitio en:
Sitemap: '.$_SERVER['REQUEST_SCHEME'].'://' . $_SERVER['SERVER_NAME'] . '/sitemap.xml

# Ocultar para todos los robots de busqueda:
User-agent: *
		', LOCK_EX);
    }

}
