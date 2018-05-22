<?php

class Consultas_ObtenerRegistroMaximo {

    static public function armado($tablas, $registro) {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas($tablas);
        $consulta->campos($tablas, $registro, 'orden', 'maximo');
        return $consulta->realizarConsulta();
    }

}

