<?php

class Consultas_ComponentesOcultos {

    static public function RegistroConsulta($id_tabla = null, $id_usuario = null) {

        if ($id_tabla == null) {
            $id_tabla = $_GET['id_tabla'];
        }

        if ($id_usuario == null) {
            $id_usuario = Inicio::usuario('id');
        }

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_usuario_atributo');
        $consulta->campos('kirke_usuario_atributo', 'atributo_valor');
        $consulta->condiciones('', 'kirke_usuario_atributo', 'id_usuario', 'iguales', '', '', $id_usuario);
        $consulta->condiciones('y', 'kirke_usuario_atributo', 'atributo_nombre', 'iguales', '', '', 'cp_oculto');
        $consulta->condiciones('y', 'kirke_usuario_atributo', 'atributo_valor', 'coincide_izq', '', '', $id_tabla . '_');
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroCrear($id_tabla = null, $id_componente, $id_usuario = null) {

        if ($id_tabla == null) {
            $id_tabla = $_GET['id_tabla'];
        }

        if ($id_usuario == null) {
            $id_usuario = Inicio::usuario('id');
        }

        $consulta = new Bases_RegistroCrear(__FILE__, __LINE__);
        $consulta->tabla('kirke_usuario_atributo');
        $consulta->campoValor('kirke_usuario_atributo', 'id_usuario', $id_usuario);
        $consulta->campoValor('kirke_usuario_atributo', 'atributo_nombre', 'cp_oculto');
        $consulta->campoValor('kirke_usuario_atributo', 'atributo_valor', $id_tabla . '_' . $id_componente);
        //$consulta->verConsulta();
        $resutado = $consulta->realizarConsulta();
    }

    static public function RegistroEliminar($id_tabla = null, $id_componente, $id_usuario = null) {

        if ($id_tabla == null) {
            $id_tabla = $_GET['id_tabla'];
        }

        if ($id_usuario == null) {
            $id_usuario = Inicio::usuario('id');
        }

        $consulta = new Bases_RegistroEliminar(__FILE__, __LINE__);
        $consulta->tabla('kirke_usuario_atributo');
        $consulta->condiciones('', 'kirke_usuario_atributo', 'id_usuario', 'iguales', '', '', $id_usuario);
        $consulta->condiciones('y', 'kirke_usuario_atributo', 'atributo_nombre', 'iguales', '', '', 'cp_oculto');
        $consulta->condiciones('y', 'kirke_usuario_atributo', 'atributo_valor', 'iguales', '', '', $id_tabla . '_' . $id_componente);
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }
    
    static public function RegistroEliminarTodos($id_tabla = null, $id_usuario = null) {

        if ($id_tabla == null) {
            $id_tabla = $_GET['id_tabla'];
        }

        if ($id_usuario == null) {
            $id_usuario = Inicio::usuario('id');
        }

        $consulta = new Bases_RegistroEliminar(__FILE__, __LINE__);
        $consulta->tabla('kirke_usuario_atributo');
        $consulta->condiciones('', 'kirke_usuario_atributo', 'id_usuario', 'iguales', '', '', $id_usuario);
        $consulta->condiciones('y', 'kirke_usuario_atributo', 'atributo_nombre', 'iguales', '', '', 'cp_oculto');
        $consulta->condiciones('y', 'kirke_usuario_atributo', 'atributo_valor', 'coincide_izq', '', '', $id_tabla . '_');
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

}
