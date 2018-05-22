<?php

class Bases_InyeccionSql {

    static function consulta($resultado) {
        return Generales_Post::obtener($resultado, 'c');
    }

}

