<?php

class Generales_Idioma extends Generales_DirectorioContenido {

    static public function asignarInicio() {

        $idioma = Consultas_UsuarioAtributo::RegistroConsulta(__FILE__, __LINE__, Inicio::usuario('id'), 'idioma');
        $idioma = $idioma[0]['atributo_valor'];

        if (array_search($idioma, $id = Inicio::confVars('idiomas')) === false) {
            $idioma = $id[0];
        }

        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['idioma'] = $idioma;
        setcookie(hash('sha256', Inicio::confVars('basedatos')).'_'.Inicio::usuario('id')."_KIRKEidioma", $idioma, time() + 2592000);
    }

    static public function obtener() {

        if ($_GET['accion'] == 'Inicio') {
            if (isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos')).'_'.Inicio::usuario('id')."_KIRKEidioma"]) && (array_search($_COOKIE[hash('sha256', Inicio::confVars('basedatos')).'_'.Inicio::usuario('id')."_KIRKEidioma"], $id = Inicio::confVars('idiomas')) === false)) {
                return $id[0];
            } elseif (isset($_COOKIE[hash('sha256', Inicio::confVars('basedatos')).'_'.Inicio::usuario('id')."_KIRKEidioma"])) {
                return $_COOKIE[hash('sha256', Inicio::confVars('basedatos')).'_'.Inicio::usuario('id')."_KIRKEidioma"];
            } else {
                $id = Inicio::confVars('idiomas');
                return $id[0];
            }
        }

        if(isset($_SESSION['KIRKE-admin'])){
            return $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['idioma'];
        }else{
            return false;
        }
    }

    
    static public function asignarPorUsuario($idioma) {
    
        if (array_search($idioma, $id = Inicio::confVars('idiomas')) === false) {
            $idioma = $id[0];
        }

        $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['idioma'] = $idioma;
        setcookie(hash('sha256', Inicio::confVars('basedatos')).'_'.Inicio::usuario('id')."_KIRKEidioma", $idioma, time() + 2592000);
    
    }
    
}
