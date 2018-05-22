<?php

class Consultas_RegistroCantidad extends Bases_RegistroConsulta {

    static public function Consulta($archivo, $linea, $tabla, $campo) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas($tabla);
        $consulta->campos($tabla, $campo, 'elemento');
        $consulta->contador('cantidad');
        $consulta->grupo($tabla, $campo);
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

}
