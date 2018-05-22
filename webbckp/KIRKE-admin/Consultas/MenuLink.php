<?php

class Consultas_MenuLink {

    static public function RegistroCrear($archivo, $linea, $id_menu, $id_elemento, $kk_generar, $orden) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_menu_link');
        $consulta->campoValor('kirke_menu_link', 'id_menu', $id_menu);
        $consulta->campoValor('kirke_menu_link', 'id_elemento', $id_elemento);
        $consulta->campoValor('kirke_menu_link', 'elemento', $kk_generar);
        $consulta->campoValor('kirke_menu_link', 'orden', $orden);
        $consulta->campoValor('kirke_menu_link', 'habilitado', 's');
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaIdTabla($archivo, $linea, $id_tabla) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu_link');
        $consulta->campos('kirke_menu_link', 'id_menu_link');
        $consulta->condiciones('', 'kirke_menu_link', 'id_elemento', 'iguales', '', '', $id_tabla);
        $consulta->condiciones('', 'kirke_menu_link', 'elemento', 'iguales', '', '', '0');
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaIdMenu($archivo, $linea, $id_menu) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu_link');
        $consulta->campos('kirke_menu_link', 'id_menu_link');
        $consulta->condiciones('', 'kirke_menu_link', 'id_menu', 'iguales', '', '', $id_menu);
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaIdMenuLink($archivo, $linea, $id_menu_link) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu_link');
        $consulta->campos('kirke_menu_link', 'id_menu');
        $consulta->campos('kirke_menu_link', 'id_elemento');
        $consulta->campos('kirke_menu_link', 'elemento');
        $consulta->condiciones('', 'kirke_menu_link', 'id_menu_link', 'iguales', '', '', $id_menu_link);
        return $consulta->realizarConsulta();
    }

    static public function RegistroEliminar($archivo, $linea, $id_tabla) {

        $consulta = new Bases_RegistroEliminar($archivo, $linea);
        $consulta->tabla('kirke_menu_link');
        $consulta->condiciones('', 'kirke_menu_link', 'id_elemento', 'iguales', '', '', $id_tabla);
        $consulta->condiciones('', 'kirke_menu_link', 'elemento', 'iguales', '', '', '0');
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaIdMenuObtener($archivo, $linea, $id_menu_link) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu_link');
        $consulta->campos('kirke_menu_link', 'id_menu');
        $consulta->condiciones('', 'kirke_menu_link', 'id_menu_link', 'iguales', '', '', $id_menu_link);
        return $consulta->realizarConsulta();
    }

}

