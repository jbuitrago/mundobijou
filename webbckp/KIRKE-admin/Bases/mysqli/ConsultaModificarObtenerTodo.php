<?php

class Bases_ConsultaModificarObtenerTodo {

    static public function consulta($consulta) {
        return preg_replace("/SELECT(.*?)FROM/si", 'SELECT * FROM ', $consulta);
    }

}
