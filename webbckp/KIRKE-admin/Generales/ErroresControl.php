<?php

class Generales_ErroresControl {

    public static $_ver;
    public static $_clases;
    public static $_clasesSegundos;
    public static $_clasesTiempoInicio;

    static public function setError($tipo, $error, $detalle, $archivo, $linea, $clase, $metodo, $funcion) {

        if (Inicio::confVars('errores_control') == 's') {
            self::$_ver[] = "<pre>" .
                    "Tipo:       $tipo <br />" .
                    "Archivo:    $archivo <br />" .
                    "Linea:      $linea <br />" .
                    "Clase:      $clase <br />" .
                    "Metodo:     $metodo <br />" .
                    "Funcion:    $funcion <br />" .
                    "Hora:       " . @date('Y.m.d - G:i:s') . "<br />" .
                    "</pre>" .
                    "Error:      $error <br />" .
                    "<pre>" .
                    "</pre>" .
                    "Detalle:    $detalle<br />" .
                    "<pre>" .
                    "</pre>";
        }
    }

    static public function setClase($directorio, $nombre) {
        if (self::$_clasesSegundos != '') {
            $segundos = microtime(true) - self::$_clasesSegundos;
        } else {
            $segundos = 0;
            self::$_clasesTiempoInicio = microtime(true);
        }
        self::$_clases[] = '↑_ ' . number_format($segundos, 7) . " | [$directorio] $nombre()\n";
        self::$_clasesSegundos = microtime(true);
    }

    static public function obtener() {

        if (Inicio::confVars('errores_control') == 's') {
            $ver_mensaje = "<div class=\"errores\">";
            if (count(self::$_ver) > 0) {
                $ver_mensaje .= str_repeat("-", 36) . "<br />";
                $ver_mensaje .= "| Errores Bases de Datos |<br />";
                $ver_mensaje .= str_repeat("-", 36) . "<br />";
                foreach (self::$_ver as $id => $valor) {
                    $ver_mensaje .= nl2br($valor);
                    $ver_mensaje .= "/";
                    $ver_mensaje .= str_repeat("-", 35) . "\n";
                }
            }

            $ver_mensaje .= "<br /><br /><br />";
            $ver_mensaje .= str_repeat("-", 29) . "<br />";
            $ver_mensaje .= "| Variables Externas |<br />";
            $ver_mensaje .= str_repeat("-", 29) . "<br />";

            $ver_mensaje .= "<pre>";
            $ver_mensaje .= "\nVariables GET:\n";
            foreach ($_GET as $key => $value) {
                if (!is_array($value)) {
                    $ver_mensaje .= "   " . $key . " = " . htmlentities($value) . "\n";
                } else {
                    $valor_array = print_r($value, true);
                    $ver_mensaje .= "   " . $key . " =";
                    $ver_mensaje .= "<blockquote>" . htmlentities($valor_array) . "</blockquote>\n";
                }
            };

            $ver_mensaje .= "</pre>";
            $ver_mensaje .= "/";
            $ver_mensaje .= str_repeat("-", 28) . "\n";
            $ver_mensaje .= "<pre>";

            $ver_mensaje .= "\nVariables POST:\n";
            if (isset($_POST)) {
                foreach ($_POST as $key => $value) {
                    if (!is_array($value)) {
                        $ver_mensaje .= "   " . $key . " = " . htmlentities($value) . "\n";
                    } else {
                        $valor_array = print_r($value, true);
                        $ver_mensaje .= "   " . $key . " =";
                        $ver_mensaje .= "<blockquote>" . htmlentities($valor_array) . "</blockquote>\n";
                    }
                }
            }

            $ver_mensaje .= "</pre>";
            $ver_mensaje .= "/";
            $ver_mensaje .= str_repeat("-", 28) . "\n";
            $ver_mensaje .= "<pre>";

            $ver_mensaje .= "\nVariables FILES:\n";
            if (isset($_FILES)) {
                foreach ($_FILES as $key => $value) {
                    if (!is_array($value)) {
                        $ver_mensaje .= "   " . $key . " = " . htmlentities($value) . "\n";
                    } else {
                        $valor_array = print_r($value, true);
                        $ver_mensaje .= "   " . $key . " =";
                        $ver_mensaje .= "<blockquote>" . htmlentities($valor_array) . "</blockquote>\n";
                    }
                }
            }

            $ver_mensaje .= "</pre>";
            $ver_mensaje .= "/";
            $ver_mensaje .= str_repeat("-", 28) . "\n";
            $ver_mensaje .= "<pre>";

            $ver_mensaje .= "\nVariables SESSION:\n";
            if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]) && is_array($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')])) {
                foreach ($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')] as $key => $value) {
                    if (!is_array($value)) {
                        $ver_mensaje .= "   " . $key . " = " . htmlentities($value) . "\n";
                    } else {
                        $valor_array = print_r($value, true);
                        $ver_mensaje .= "   " . $key . " =";
                        $ver_mensaje .= "<blockquote>" . htmlentities($valor_array) . "</blockquote>\n";
                    }
                }
            }
            $ver_mensaje .= "</pre>";
            $ver_mensaje .= "/";
            $ver_mensaje .= str_repeat("-", 28) . "\n";
            $ver_mensaje .= "<pre>";

            $ver_mensaje .= "\nVariables COOKIE:\n";
            if (isset($_COOKIE)) {
                foreach ($_COOKIE as $key => $value) {
                    if (!is_array($value)) {
                        $ver_mensaje .= "   " . $key . " = " . htmlentities($value) . "\n";
                    } else {
                        $valor_array = print_r($value, true);
                        $ver_mensaje .= "   " . $key . " =";
                        $ver_mensaje .= "<blockquote>" . htmlentities($valor_array) . "</blockquote>\n";
                    }
                }
            }

            $ver_mensaje .= "</pre>";
            $ver_mensaje .= "/";
            $ver_mensaje .= str_repeat("-", 28) . "\n";
            $ver_mensaje .= "<pre>";

            $ver_mensaje .= "\nVariables SERVER:\n";
            $ver_mensaje .= "\nSERVER[SERVER_SOFTWARE]:      " . $_SERVER['SERVER_SOFTWARE'];
            $ver_mensaje .= "\nSERVER[SERVER_NAME]:          " . $_SERVER['SERVER_NAME'];
            $ver_mensaje .= "\nSERVER[SERVER_ADDR]:          " . $_SERVER['SERVER_ADDR'];
            $ver_mensaje .= "\nSERVER[SERVER_PORT]:          " . $_SERVER['SERVER_PORT'];
            $ver_mensaje .= "\nSERVER[REMOTE_ADDR]:          " . $_SERVER['REMOTE_ADDR'];
            $ver_mensaje .= "\nSERVER[DOCUMENT_ROOT]:        " . $_SERVER['DOCUMENT_ROOT'];
            if(isset($_SERVER['SERVER_ADMIN'])){
                $ver_mensaje .= "\nSERVER[SERVER_ADMIN]:         <a href=\"mailto:" . $_SERVER['SERVER_ADMIN'] . "\">" . $_SERVER['SERVER_ADMIN'] . "</a>";
            }else{
                $ver_mensaje .= "\nSERVER[SERVER_ADMIN]:         no configurado";
            }
            $ver_mensaje .= "\nSERVER[SCRIPT_FILENAME]:      " . $_SERVER['SCRIPT_FILENAME'];
            $ver_mensaje .= "\nSERVER[REQUEST_METHOD]:       " . $_SERVER['REQUEST_METHOD'];
            $ver_mensaje .= "\nSERVER[SCRIPT_NAME]:          " . $_SERVER['SCRIPT_NAME'];
            $ver_mensaje .= "\nSERVER[HTTP_USER_AGENT]:      " . $_SERVER['HTTP_USER_AGENT'];

            $ver_mensaje .= "</pre>";
            $ver_mensaje .= "/";
            $ver_mensaje .= str_repeat("-", 28) . "\n";
            $ver_mensaje .= "<pre>";

            $ver_mensaje .= "\nClases:\n";
            if (is_array(self::$_clases)) {
                foreach (self::$_clases as $valor) {
                    $ver_mensaje .= $valor;
                }
            }
            $total_microsegundos = (microtime(true) - self::$_clasesTiempoInicio);
            $ver_mensaje .= 'Total = ' . number_format($total_microsegundos, 8) . "\n";
            $ver_mensaje .= "</pre>";

            $ver_mensaje .= "</div>";

            return $ver_mensaje;
        } else {

            return false;
        }
    }

}

