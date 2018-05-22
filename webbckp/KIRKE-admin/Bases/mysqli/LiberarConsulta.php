<?php

class Bases_LiberarConsulta {

    static function consulta($resultado) {
        if ($resultado) {
            @mysql_free_result($resultado);
        }
    }

}

