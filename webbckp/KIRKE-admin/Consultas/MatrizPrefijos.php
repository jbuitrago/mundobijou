<?php

class Consultas_MatrizPrefijos {

    static public function armado($id_prefijo = null) {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->campos('kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->campos('kirke_tabla_prefijo', 'orden');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        if ($id_prefijo) {
            $consulta->condiciones('', 'kirke_tabla_prefijo', 'id_tabla_prefijo', 'iguales', '', '', $id_prefijo);
        }
        $consulta->orden('kirke_tabla_prefijo', 'orden');
        return $consulta->realizarConsulta();
    }

}

