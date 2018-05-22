<?php

class Consultas_TablaPrefijo {

    static public function RegistroConsulta($archivo, $linea, $id_tabla_prefijo = null, $datos_consulta = null) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->campos('kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        if (isset($datos_consulta) && ($datos_consulta == 'descripcion')) {
            $consulta->campos('kirke_tabla_prefijo', 'descripcion');
        }
        if (isset($id_tabla_prefijo)) {
            $consulta->condiciones('', 'kirke_tabla_prefijo', 'id_tabla_prefijo', 'iguales', '', '', $id_tabla_prefijo);
        }
        return $consulta->realizarConsulta();
    }

    static public function RegistroCrear($archivo, $linea, $orden, $prefijo, $descripcion) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_tabla_prefijo');
        $consulta->campoValor('kirke_tabla_prefijo', 'orden', $orden);
        $consulta->campoValor('kirke_tabla_prefijo', 'prefijo', $prefijo);
        $consulta->campoValor('kirke_tabla_prefijo', 'descripcion', $descripcion);
        return $consulta->realizarConsulta();
    }

}

