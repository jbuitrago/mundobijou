<?php

class Consultas_Tmp {

    static public function RegistroConsulta($archivo, $linea, $id_registro, $id_componente) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_tmp');
        $consulta->campos('kirke_tmp', 'contenido');
        $consulta->condiciones('', 'kirke_tmp', 'id_tmp', 'iguales', '', '', $id_registro);
        $consulta->condiciones('y', 'kirke_tmp', 'id_componente', 'iguales', '', '', $id_componente);
        return $consulta->realizarConsulta();
    }

    static public function RegistroCrear($archivo, $linea, $id_componente, $contenido) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_tmp');
        $consulta->campoValor('kirke_tmp', 'fecha', microtime(true));
        $consulta->campoValor('kirke_tmp', 'id_componente', $id_componente);
        $consulta->campoValor('kirke_tmp', 'contenido', $contenido);
        return $consulta->realizarConsulta();
    }

}

