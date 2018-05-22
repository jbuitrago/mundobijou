<?php

class Consultas_ObtenerRegistroTmpId {

    static public function armado($id_tmp = null) {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tmp');
        $consulta->campos('kirke_tmp', 'contenido');
        $consulta->condiciones('', 'kirke_tmp', 'id_tmp', 'iguales', '', '', $id_tmp);
        $matriz = $consulta->realizarConsulta();

        return $matriz[0]['contenido'];
    }

}

