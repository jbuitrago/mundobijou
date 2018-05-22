<?php

class Consultas_UsuarioAtributo {

    static public function RegistroConsulta($archivo, $linea, $id_usuario, $atributo_nombre = null) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_usuario_atributo');
        $consulta->campos('kirke_usuario_atributo', 'atributo_valor');
        if (!isset($atributo_nombre)) {
            $consulta->campos('kirke_usuario_atributo', 'atributo_nombre');
        }
        $consulta->condiciones('', 'kirke_usuario_atributo', 'id_usuario', 'iguales', '', '', $id_usuario);
        if (isset($atributo_nombre)) {
            $consulta->condiciones('y', 'kirke_usuario_atributo', 'atributo_nombre', 'iguales', '', '', $atributo_nombre);
        }
        return $consulta->realizarConsulta();
    }

    static public function RegistroCrear($archivo, $linea, $id_usuario, $atributo_nombre, $atributo_valor) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_usuario_atributo');
        $consulta->campoValor('kirke_usuario_atributo', 'id_usuario', $id_usuario);
        $consulta->campoValor('kirke_usuario_atributo', 'atributo_nombre', $atributo_nombre);
        $consulta->campoValor('kirke_usuario_atributo', 'atributo_valor', $atributo_valor);
        return $consulta->realizarConsulta();
    }

    static public function RegistroModificar($archivo, $linea, $id_usuario, $atributo_nombre, $atributo_valor) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_usuario_atributo');
        $consulta->campoValor('kirke_usuario_atributo', 'atributo_valor', $atributo_valor);
        $consulta->condiciones('', 'kirke_usuario_atributo', 'id_usuario', 'iguales', '', '', $id_usuario);
        $consulta->condiciones('y', 'kirke_usuario_atributo', 'atributo_nombre', 'iguales', '', '', $atributo_nombre);
        return $consulta->realizarConsulta();
    }

}

