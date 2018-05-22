<?php

class Consultas_MatrizRoles {

    static public function armado() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_rol');
        $consulta->campos('kirke_rol', 'id_rol');
        $consulta->campos('kirke_rol', 'orden');
        $consulta->campos('kirke_rol', 'rol');
        $consulta->condiciones('', 'kirke_rol', 'id_rol', 'mayor', '', '', '2');
        $consulta->orden('kirke_rol', 'orden');
        return $consulta->realizarConsulta();
    }

}

