<?php

class Consultas_Roll {

    static public function RegistroConsulta($archivo, $linea) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_rol');
        $consulta->campos('kirke_rol', 'id_rol');
        $consulta->campos('kirke_rol', 'rol');
        $consulta->campos('kirke_rol', 'descripcion');
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaIdRoll($archivo, $linea, $id_rol) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_rol');
        $consulta->campos('kirke_rol', 'rol');
        $consulta->campos('kirke_rol', 'descripcion');
        $consulta->condiciones('', 'kirke_rol', 'id_rol', 'iguales', '', '', $id_rol);
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaIdUsuario($archivo, $linea, $id_usuario) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_rol');
        $consulta->tablas('kirke_usuario_rol');
        $consulta->campos('kirke_rol', 'id_rol');
        $consulta->campos('kirke_rol', 'rol');
        $consulta->campos('kirke_rol', 'descripcion');
        $consulta->condiciones('', 'kirke_usuario_rol', 'id_usuario', 'iguales', '', '', $id_usuario);
        $consulta->condiciones('y', 'kirke_rol', 'id_rol', 'iguales', 'kirke_usuario_rol', 'id_rol');
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaUsuario($archivo, $linea, $usuario) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_usuario');
        $consulta->tablas('kirke_usuario_rol');
        $consulta->tablas('kirke_rol');
        $consulta->campos('kirke_usuario', 'id_usuario');
        $consulta->campos('kirke_usuario', 'nombre');
        $consulta->campos('kirke_usuario', 'apellido');
        $consulta->campos('kirke_usuario', 'clave');
        $consulta->campos('kirke_usuario', 'usuario');
        $consulta->campos('kirke_rol', 'id_rol');
        $consulta->condiciones('', 'kirke_usuario', 'usuario', 'iguales', '', '', $usuario);
        $consulta->condiciones('y', 'kirke_usuario', 'habilitado', 'iguales', '', '', 's');
        return $consulta->realizarConsulta();
    }

    static public function RegistroCrear($archivo, $linea, $orden, $rol, $detalle) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_rol');
        $consulta->campoValor('kirke_rol', 'orden', $orden);
        $consulta->campoValor('kirke_rol', 'rol', $rol);
        $consulta->campoValor('kirke_rol', 'descripcion', $detalle);
        return $consulta->realizarConsulta();
    }

    static public function RegistroModificar($archivo, $linea, $rol, $detalle, $id_rol) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_rol');
        $consulta->campoValor('kirke_rol', 'rol', $rol);
        $consulta->campoValor('kirke_rol', 'descripcion', $detalle);
        $consulta->condiciones('', 'kirke_rol', 'id_rol', 'iguales', '', '', $id_rol);
        return $consulta->realizarConsulta();
    }

}

