<?php

class Consultas_Usuario {

    static public function RegistroConsulta($archivo, $linea, $id_usuario, $campos) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_usuario');
        if (strpos($campos, 'id_usuario') !== false) {
            $consulta->campos('kirke_usuario', 'id_usuario');
        }
        if (strpos($campos, 'nombre') !== false) {
            $consulta->campos('kirke_usuario', 'nombre');
        }
        if (strpos($campos, 'apellido') !== false) {
            $consulta->campos('kirke_usuario', 'apellido');
        }
        if (strpos($campos, 'usuario') !== false) {
            $consulta->campos('kirke_usuario', 'usuario');
        }
        if (strpos($campos, 'mail') !== false) {
            $consulta->campos('kirke_usuario', 'mail');
        }
        if (strpos($campos, 'telefono') !== false) {
            $consulta->campos('kirke_usuario', 'telefono');
        }
        if (strpos($campos, 'habilitado') !== false) {
            $consulta->campos('kirke_usuario', 'habilitado');
        }
        $consulta->condiciones('', 'kirke_usuario', 'id_usuario', 'iguales', '', '', $id_usuario);
        return $consulta->realizarConsulta();
    }

    static public function RegistroModificarMailTelefono($archivo, $linea, $id_usuario, $mail, $telefono) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_usuario');
        $consulta->campoValor('kirke_usuario', 'mail', $mail);
        $consulta->campoValor('kirke_usuario', 'telefono', $telefono);
        $consulta->condiciones('', 'kirke_usuario', 'id_usuario', 'iguales', '', '', $id_usuario);
        return $consulta->realizarConsulta();
    }

    static public function RegistroModificarUsuarioClave($archivo, $linea, $id_usuario, $clave) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_usuario');
        $consulta->campoValor('kirke_usuario', 'clave', $clave);
        $consulta->condiciones('', 'kirke_usuario', 'id_usuario', 'iguales', '', '', $id_usuario);
        return $consulta->realizarConsulta();
    }

    static public function RegistroModificarTodo($archivo, $linea, $id_usuario, $nombre, $apellido, $mail, $telefono, $habilitado, $nueva_clave = null) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_usuario');
        $consulta->campoValor('kirke_usuario', 'nombre', $nombre);
        $consulta->campoValor('kirke_usuario', 'apellido', $apellido);
        $consulta->campoValor('kirke_usuario', 'mail', $mail);
        $consulta->campoValor('kirke_usuario', 'telefono', $telefono);
        $consulta->campoValor('kirke_usuario', 'habilitado', $habilitado);
        if ($nueva_clave !== false) {
            $consulta->campoValor('kirke_usuario', 'clave', $nueva_clave);
        }
        $consulta->condiciones('', 'kirke_usuario', 'id_usuario', 'iguales', '', '', $id_usuario);
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroCrear($archivo, $linea, $orden, $nombre, $apellido, $usuario, $clave_encriptada, $email, $telefono, $habilitado) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_usuario');
        $consulta->campoValor('kirke_usuario', 'orden', $orden);
        $consulta->campoValor('kirke_usuario', 'nombre', $nombre);
        $consulta->campoValor('kirke_usuario', 'apellido', $apellido);
        $consulta->campoValor('kirke_usuario', 'usuario', $usuario);
        $consulta->campoValor('kirke_usuario', 'clave', $clave_encriptada);
        $consulta->campoValor('kirke_usuario', 'mail', $email);
        $consulta->campoValor('kirke_usuario', 'telefono', $telefono);
        $consulta->campoValor('kirke_usuario', 'habilitado', $habilitado);
        return $consulta->realizarConsulta();
    }
    
    static public function ControlUsuario($archivo, $linea, $usuario) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_usuario');
        $consulta->campos('kirke_usuario', 'id_usuario');
        $consulta->condiciones('', 'kirke_usuario', 'usuario', 'iguales', '', '', $usuario);
        return $consulta->realizarConsulta();
    }

}
