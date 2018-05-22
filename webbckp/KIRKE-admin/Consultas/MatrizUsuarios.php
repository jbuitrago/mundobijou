<?php

class Consultas_MatrizUsuarios {

    static public function armado($es_administrador) {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_usuario');
        $consulta->campos('kirke_usuario', 'id_usuario');
        $consulta->campos('kirke_usuario', 'orden');
        $consulta->campos('kirke_usuario', 'apellido');
        $consulta->campos('kirke_usuario', 'nombre');
        $consulta->campos('kirke_usuario', 'usuario');
        $consulta->orden('kirke_usuario', 'orden');
        if($es_administrador === false){
            $consulta->condiciones('', 'kirke_usuario', 'id_usuario', 'distintos', '', '', '1');
        }
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

}

