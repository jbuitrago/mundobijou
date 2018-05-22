<?php

class Consultas_UsuarioRoll {

    static public function RegistroCrear($archivo, $linea, $id_usuario, $id_rol) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_usuario_rol');
        $consulta->campoValor('kirke_usuario_rol', 'id_usuario', $id_usuario);
        $consulta->campoValor('kirke_usuario_rol', 'id_rol', $id_rol);
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsulta($archivo, $linea, $id_usuario) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_usuario_rol');
        $consulta->campos('kirke_usuario_rol', 'id_rol');
        $consulta->condiciones('', 'kirke_usuario_rol', 'id_usuario', 'iguales', '', '', $id_usuario);
        return $consulta->realizarConsulta();
    }

}

