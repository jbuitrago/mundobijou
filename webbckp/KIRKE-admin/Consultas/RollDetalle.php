<?php

class Consultas_RollDetalle {

    static public function RegistroConsulta($archivo, $linea, $id_rol, $elemento = null) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_rol_detalle');
        $consulta->campos('kirke_rol_detalle', 'elemento');
        $consulta->campos('kirke_rol_detalle', 'id_elemento');
        $consulta->campos('kirke_rol_detalle', 'permiso');
        $consulta->condiciones('', 'kirke_rol_detalle', 'id_rol', 'iguales', '', '', $id_rol);
        if ($elemento == null) {
            $consulta->condiciones('y', 'kirke_rol_detalle', 'elemento', 'iguales', '', '', 'pagina');
        } else {
            $consulta->condiciones('y', 'kirke_rol_detalle', 'elemento', 'iguales', '', '', $elemento);
        }
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaIdUsuario($archivo, $linea, $id_usuario, $elemento, $id_elemento, $permiso) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_usuario_rol');
        $consulta->tablas('kirke_rol');
        $consulta->tablas('kirke_rol_detalle');
        $consulta->campos('kirke_rol_detalle', 'id_rol');
        $consulta->condiciones('', 'kirke_usuario_rol', 'id_usuario', 'iguales', '', '', $id_usuario);
        $consulta->condiciones('y', 'kirke_rol_detalle', 'elemento', 'iguales', '', '', $elemento);
        $consulta->condiciones('y', 'kirke_rol_detalle', 'id_elemento', 'iguales', '', '', $id_elemento);
        $consulta->condiciones('y', 'kirke_rol_detalle', 'permiso', 'iguales', '', '', $permiso);
        $consulta->condiciones('y', 'kirke_usuario_rol', 'id_rol', 'iguales', 'kirke_rol', 'id_rol');
        $consulta->condiciones('y', 'kirke_rol', 'id_rol', 'iguales', 'kirke_rol_detalle', 'id_rol');
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroEliminar($archivo, $linea, $id_tabla) {

        $consulta = new Bases_RegistroEliminar($archivo, $linea);
        $consulta->tabla('kirke_rol_detalle');
        $consulta->condiciones('', 'kirke_rol_detalle', 'elemento', 'iguales', '', '', 'pagina');
        $consulta->condiciones('y', 'kirke_rol_detalle', 'id_elemento', 'iguales', '', '', $id_tabla);
        return $consulta->realizarConsulta();
    }

    static public function RegistroCrear($archivo, $linea, $id_roll, $elemento, $id_elemento, $permiso) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_rol_detalle');
        $consulta->campoValor('kirke_rol_detalle', 'id_rol', $id_roll);
        $consulta->campoValor('kirke_rol_detalle', 'elemento', $elemento);
        $consulta->campoValor('kirke_rol_detalle', 'id_elemento', $id_elemento);
        $consulta->campoValor('kirke_rol_detalle', 'permiso', $permiso);
        return $consulta->realizarConsulta();
    }

}

