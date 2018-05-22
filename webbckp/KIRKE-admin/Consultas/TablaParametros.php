<?php

class Consultas_TablaParametros {

    static public function RegistroConsulta($archivo, $linea, $id_tabla, $id_componente) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_tabla_parametro');
        $consulta->campos('kirke_tabla_parametro', 'id_tabla_parametro');
        $consulta->condiciones('', 'kirke_tabla_parametro', 'id_tabla', 'iguales', '', '', $id_tabla);
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'tipo', 'iguales', '', '', 'link');
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'parametro', 'iguales', '', '', 'destino_id_cp');
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'valor', 'iguales', '', '', $id_componente);
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaValor($archivo, $linea, $id_tabla) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_tabla_parametro');
        $consulta->campos('kirke_tabla_parametro', 'valor');
        $consulta->condiciones('', 'kirke_tabla_parametro', 'id_tabla', 'iguales', '', '', $id_tabla);
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'tipo', 'iguales', '', '', 'link');
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'parametro', 'iguales', '', '', 'destino_id_cp');
        return $consulta->realizarConsulta();
    }
    
    static public function RegistroConsultaTodos($archivo, $linea, $id_tabla) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_tabla_parametro');
        $consulta->campos('kirke_tabla_parametro', 'tipo', 'link');
        $consulta->campos('kirke_tabla_parametro', 'parametro');
        $consulta->campos('kirke_tabla_parametro', 'valor');
        $consulta->condiciones('', 'kirke_tabla_parametro', 'id_tabla', 'iguales', '', '', $id_tabla);
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }
    
    static public function RegistroCrear($archivo, $linea, $id_tabla, $id_componente) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_tabla_parametro');
        $consulta->campoValor('kirke_tabla_parametro', 'id_tabla', $id_tabla);
        $consulta->campoValor('kirke_tabla_parametro', 'tipo', 'link');
        $consulta->campoValor('kirke_tabla_parametro', 'parametro', 'destino_id_cp');
        $consulta->campoValor('kirke_tabla_parametro', 'valor', $id_componente);
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroCrearCompleto($archivo, $linea, $id_tabla, $tipo, $parametro, $valor) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_tabla_parametro');
        $consulta->campoValor('kirke_tabla_parametro', 'id_tabla', $id_tabla);
        $consulta->campoValor('kirke_tabla_parametro', 'tipo', $tipo);
        $consulta->campoValor('kirke_tabla_parametro', 'parametro', $parametro);
        $consulta->campoValor('kirke_tabla_parametro', 'valor', $valor);
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }
    
    static public function RegistroEliminar($archivo, $linea, $id_tabla, $id_componente) {

        $consulta = new Bases_RegistroEliminar($archivo, $linea);
        $consulta->tabla('kirke_tabla_parametro');
        $consulta->condiciones('', 'kirke_tabla_parametro', 'id_tabla', 'iguales', '', '', $id_tabla);
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'tipo', 'iguales', '', '', 'link');
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'parametro', 'iguales', '', '', 'destino_id_cp');
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'valor', 'iguales', '', '', $id_componente);
        return $consulta->realizarConsulta();
    }

    static public function RegistroEliminarValor($archivo, $linea, $id_componente) {

        $consulta = new Bases_RegistroEliminar($archivo, $linea);
        $consulta->tabla('kirke_tabla_parametro');
        $consulta->condiciones('', 'kirke_tabla_parametro', 'tipo', 'iguales', '', '', 'link');
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'parametro', 'iguales', '', '', 'destino_id_cp');
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'valor', 'iguales', '', '', $id_componente);
        return $consulta->realizarConsulta();
    }

    static public function RegistroModificar($archivo, $linea, $id_tabla, $tipo, $parametro, $valor) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_tabla_parametro');
        $consulta->campoValor('kirke_tabla_parametro', 'valor', $valor);
        $consulta->condiciones('', 'kirke_tabla_parametro', 'id_tabla', 'iguales', '', '', $id_tabla);
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'tipo', 'iguales', '', '', $tipo);
        $consulta->condiciones('y', 'kirke_tabla_parametro', 'parametro', 'iguales', '', '', $parametro);
        return $consulta->realizarConsulta();
    }
    
}
