<?php

class Consultas_Tabla {

    static public function RegistroCrear($archivo, $linea, $tabla_prefijo, $orden, $tabla_nombre, $habilitado, $tipo) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_tabla');
        $consulta->campoValor('kirke_tabla', 'id_tabla_prefijo', $tabla_prefijo);
        $consulta->campoValor('kirke_tabla', 'orden', $orden);
        $consulta->campoValor('kirke_tabla', 'tabla_nombre', $tabla_nombre);
        $consulta->campoValor('kirke_tabla', 'habilitado', $habilitado);
        $consulta->campoValor('kirke_tabla', 'tipo', $tipo);
        return $consulta->realizarConsulta();
    }

    static public function RegistroEliminar($archivo, $linea, $id_tabla) {

        $consulta = new Bases_RegistroEliminar($archivo, $linea);
        $consulta->tabla('kirke_tabla');
        $consulta->condiciones('', 'kirke_tabla', 'id_tabla', 'iguales', '', '', $id_tabla);
        return $consulta->realizarConsulta();
    }

    static public function RegistroModificar($archivo, $linea, $id_tabla_prefijo, $tabla_nombre, $habilitado, $id_tabla) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_tabla');
        $consulta->campoValor('kirke_tabla', 'id_tabla_prefijo', $id_tabla_prefijo);
        $consulta->campoValor('kirke_tabla', 'tabla_nombre', $tabla_nombre);
        $consulta->campoValor('kirke_tabla', 'habilitado', $habilitado);
        $consulta->condiciones('', 'kirke_tabla', 'id_tabla', 'iguales', '', '', $id_tabla);
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsulta($archivo, $linea, $tabla_nombre, $id_tabla_prefijo) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_tabla');
        $consulta->campos('kirke_tabla', 'id_tabla');
        $consulta->condiciones('', 'kirke_tabla', 'tabla_nombre', 'iguales', '', '', $tabla_nombre);
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', '', '', $id_tabla_prefijo);
        //$consulta->verConsulta();
        $id_tabla = $consulta->realizarConsulta();

        return $id_tabla[0]['id_tabla'];
    }

    static public function RegistroConsultaTablaNombre($archivo, $linea, $id_tabla) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->condiciones('', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', '', '', $id_tabla);
        $tabla = $consulta->realizarConsulta();

        return $tabla[0]['prefijo'] . '_' . $tabla[0]['tabla_nombre'];
    }

}
