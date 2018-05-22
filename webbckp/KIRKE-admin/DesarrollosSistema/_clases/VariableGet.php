<?php

class VariableGet extends VariableControl {

    // Variables del sistema
    public static function sistema($variable) {
        return parent::getSistema($variable);
    }

    // Variables globales {$#variable}
    public static function globales($variable) {
        return parent::getGlobales($variable);
    }

    // Variable contenido seccion
    public static function seccion() {
        return parent::getSeccion();
    }

    // Variable nombre marco
    public static function indexMarco() {
        return parent::getIndexMarco();
    }

    // Variable nombre marco
    public static function original($variable) {
        return parent::getOriginal($variable);
    }

}
