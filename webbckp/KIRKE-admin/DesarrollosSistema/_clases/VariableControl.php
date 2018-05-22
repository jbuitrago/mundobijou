<?php

class VariableControl {

    private static $_sistema = Array();
    private static $_globales = Array();
    private static $_get;
    private static $_getId = 0;
    private static $_seccion;
    private static $_indexMarco = 'index_marco';
    private static $_bloquearSistema = false;
    private static $_bloquearGet = false;
    private static $_bloquearSeccion = false;
    private static $_getParam;
    private static $_bloquearGetParam = false;
    private static $_globalesSitioNombre = false;

    // Variables del sistema

    protected static function setSistema($variable, $valor) {
        if (self::$_bloquearSistema === false) {
            self::$_sistema[$variable] = $valor;
        }
    }

    protected static function getSistema($variable) {
        if (isset(self::$_sistema[$variable])) {
            return self::$_sistema[$variable];
        } else {
            return false;
        }
    }

    public static function bloquearSistema() {
        self::$_bloquearSistema = true;
    }

    // Variables globales {$#variable}

    protected static function setGlobales($variable, $valor, $sitio_nombre_inicio = NULL) {
        self::$_globales[$variable] = $valor;
        if (($variable == 'sitio_nombre') && empty($sitio_nombre_inicio)) {
            self::$_globalesSitioNombre = true;
        }
    }

    protected static function getGlobales($variable) {
        if (isset(self::$_globales[$variable])) {
            return self::$_globales[$variable];
        } else {
            return false;
        }
    }

    public static function getArrayGlobales() {
        return self::$_globales;
    }

    public static function getSitioNombre() {
        return self::$_globalesSitioNombre;
    }

    // Variables $_GET

    public static function setGet($valor, $id = null) {
        if (empty($id)) {
            $_GET[] = $valor;
        } else {
            $_GET[$id] = $valor;
        }
    }

    // Variables $_GET (parametros)

    public static function setGetParam($valor) {
        if (self::$_bloquearGetParam === false) {
            $valor = explode('&', $valor);
            foreach ($valor as $valor_param) {
                $valor_param_sep = explode('=', $valor_param);
                self::$_getParam[trim($valor_param_sep[0])] = trim($valor_param_sep[1]);
            }
        }
    }

    public static function getParam($id) {
        if (isset($id) && (self::$_getParam[$id])) {
            return self::$_getParam[$id];
        } else {
            return false;
        }
    }

    public static function bloquearGetParam() {
        self::$_bloquearGetParam = true;
    }

    // Variable contenido seccion

    protected static function setSeccion($valor) {
        if (self::$_bloquearSeccion === false) {
            self::$_seccion = $valor;
            self::$_bloquearSeccion = true;
        }
    }

    protected static function getSeccion() {
        return self::$_seccion;
    }

    // Variable nombre marco

    protected static function setIndexMarco($valor) {
        self::$_indexMarco = $valor;
    }

    protected static function getIndexMarco() {
        return self::$_indexMarco;
    }

    // Obtencion de variables get originales

    protected static function getOriginal($elemento) {

        $variables_get = explode('/', ArmadoLinks::getCacheURL());

        if (isset($variables_get[$elemento + 1 + VariableGet::sistema('seccion_actual_nivel')])) {
            return $variables_get[$elemento + 1 + VariableGet::sistema('seccion_actual_nivel')];
        }
    }

}
