<?php

class Consultas_MenuLinkParametro {

    static public function RegistroCrear($archivo, $linea, $id_menu_link, $tipo, $id_element, $parametro, $valor) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_menu_link_parametro');
        $consulta->campoValor('kirke_menu_link_parametro', 'id_menu_link', $id_menu_link);
        $consulta->campoValor('kirke_menu_link_parametro', 'tipo', $tipo);
        $consulta->campoValor('kirke_menu_link_parametro', 'id', $id_element);
        $consulta->campoValor('kirke_menu_link_parametro', 'parametro', $parametro);
        $consulta->campoValor('kirke_menu_link_parametro', 'valor', $valor);
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsulta($archivo, $linea, $id_menu_link) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu_link');
        $consulta->tablas('kirke_menu_link_parametro');
        $consulta->campos('kirke_menu_link', 'id_elemento');
        $consulta->campos('kirke_menu_link', 'elemento');
        $consulta->campos('kirke_menu_link_parametro', 'id_menu_link');
        $consulta->campos('kirke_menu_link_parametro', 'tipo');
        $consulta->campos('kirke_menu_link_parametro', 'id');
        $consulta->campos('kirke_menu_link_parametro', 'parametro');
        $consulta->campos('kirke_menu_link_parametro', 'valor');
        $consulta->condiciones('', 'kirke_menu_link_parametro', 'id_menu_link', 'iguales', '', '', $id_menu_link);
        $consulta->condiciones('y', 'kirke_menu_link', 'id_menu_link', 'iguales', 'kirke_menu_link_parametro', 'id_menu_link');
        $consulta->condiciones('y', 'kirke_menu_link', 'habilitado', 'iguales', '', '', 's');
        return $consulta->realizarConsulta();
    }

}

