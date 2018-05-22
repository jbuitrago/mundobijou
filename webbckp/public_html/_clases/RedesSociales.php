<?php

class RedesSociales {

    private static $cont_imagen = 1;
    private static $meta_tag = array();

    public static function obtenerDatos() {

        if (count(self::$meta_tag) > 0) {
            ksort(self::$meta_tag);
            return implode("\n", self::$meta_tag);
        } else {
            return '';
        }
    }

    public static function titulo($titulo) {
        self::$meta_tag['01_titulo'] = '<meta property="og:title" content="' . str_replace(array('"', '>', '<'), '', $titulo) . '">';
    }

    public static function descripcion($descripcion) {
        self::$meta_tag['02_descripcion'] = '<meta property="og:description" content="' . str_replace(array('"', '>', '<'), '', $descripcion) . '">';
    }

    public static function url() {
        self::$meta_tag['03_url'] = '<meta property="og:url" content="' . self::urlActual() . $_SERVER['REQUEST_URI'] . $_SERVER['QUERY_STRING'] . '">';
    }

    public static function imagen($imagen, $directorio = '') {
        self::$meta_tag['04_imagen_' . self::$cont_imagen] = '<meta property="og:image" content="' . self::urlActual() . $directorio . $imagen . '">';
        self::$cont_imagen++;
    }

    public static function tipo($tipo) {
        self::$meta_tag['05_tipo'] = '<meta property="og:type" content="' . str_replace(array('"', '>', '<'), '', $tipo) . '">';
    }

    public static function video($url) {
        self::$meta_tag['06_video'] = '<meta property="og:video" content="' . self::urlActual() . $url . '">';
    }

    public static function idioma($idioma) {
        self::$meta_tag['07_idioma'] = '<meta property="og:locale" content="' . $idioma . '">';
    }

    public static function tituloSitio($titulo_sitio) {
        self::$meta_tag['08_titulo_sitio'] = '<meta property="og:site_name" content="' . $titulo_sitio . '">';
    }

    // Facebook

    public static function FacebookIdAdministrador($id_administrador) {
        self::$meta_tag['09_facebook_id_administrador'] = '<meta property="fb:admins" content="' . $id_administrador . '">';
    }

    public static function FacebookIdFanPage($id_fanpage) {
        self::$meta_tag['10_facebook_id_fan_page'] = '<meta property="fb:page_id" content="' . $id_fanpage . '">';
    }

    // Twitter

    public static function TwitterCard($card) {
        self::$meta_tag['11_twitter_card'] = '<meta property="twitter:card" content="' . $card . '">';
    }

    public static function TwitterSite($site) {
        self::$meta_tag['12_twitter_site'] = '<meta property="twitter:site" content="' . $site . '">';
    }

    public static function TwitterCreator($creator) {
        self::$meta_tag['13_twitter_creator'] = '<meta property="twitter:creator" content="' . $creator . '">';
    }

    // generales

    private static function urlActual() {
        if (!empty($s['HTTPS'])) {
            $scheme = $_SERVER['REQUEST_SCHEME'];
        } else {
            $scheme = 'http';
        }
        return $scheme . '://' . $_SERVER['HTTP_HOST'];
    }

}
