<?php

class Consultas_TablaIdModificar {

    static public function VerSiExiste($archivo, $linea, $nombre_campo) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_componente');
        $consulta->campos('kirke_componente', 'id_componente');
        $consulta->condiciones('', 'kirke_componente', 'tabla_campo', 'iguales', '', '', $nombre_campo);
        $resutado = $consulta->realizarConsulta();

        if (is_array($resutado)) {

            return $resutado[0]['id_componente'];
        } else {

            return false;
        }
    }

    static public function RegistroModificar($archivo, $linea, $id_componente, $valor) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_componente');
        $consulta->campoValor('kirke_componente', 'tabla_campo', $valor);
        $consulta->condiciones('', 'kirke_componente', 'id_componente', 'iguales', '', '', $id_componente);
        return $consulta->realizarConsulta();
    }

}
