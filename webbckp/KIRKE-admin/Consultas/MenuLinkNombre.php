<?php

class Consultas_MenuLinkNombre {

    static public function RegistroCrear($archivo, $linea, $id_menu_link, $idioma, $idioma_texto) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_menu_link_nombre');
        $consulta->campoValor('kirke_menu_link_nombre', 'id_menu_link', $id_menu_link);
        $consulta->campoValor('kirke_menu_link_nombre', 'idioma_codigo', $idioma);
        $consulta->campoValor('kirke_menu_link_nombre', 'menu_link_nombre', $idioma_texto);
        return $consulta->realizarConsulta();
    }
    
    static public function RegistroModificar($archivo, $linea, $id_menu_link, $idioma, $idioma_texto) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_menu_link_nombre');
        $consulta->campoValor('kirke_menu_link_nombre', 'menu_link_nombre', $idioma_texto);
        $consulta->condiciones('', 'kirke_menu_link_nombre', 'id_menu_link', 'iguales', '', '', $id_menu_link);
        $consulta->condiciones('y', 'kirke_menu_link_nombre', 'idioma_codigo', 'iguales', '', '', $idioma);
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
        
    }

    static public function RegistroConsultaIdMenuLink($archivo, $linea, $id_menu_link) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu_link');
        $consulta->tablas('kirke_menu_link_nombre');
        $consulta->campos('kirke_menu_link_nombre', 'menu_link_nombre');
        $consulta->campos('kirke_menu_link_nombre', 'idioma_codigo');
        $consulta->condiciones('', 'kirke_menu_link', 'id_menu_link', 'iguales', 'kirke_menu_link_nombre', 'id_menu_link');
        $consulta->condiciones('y', 'kirke_menu_link', 'id_menu_link', 'iguales', '', '', $id_menu_link);
        $consulta->condiciones('y', 'kirke_menu_link_nombre', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaIdMenu($archivo, $linea, $id_menu) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu_link');
        $consulta->tablas('kirke_menu_link_nombre');
        $consulta->campos('kirke_menu_link', 'id_elemento');
        $consulta->campos('kirke_menu_link', 'elemento');
        $consulta->campos('kirke_menu_link', 'id_menu_link');
        $consulta->campos('kirke_menu_link_nombre', 'menu_link_nombre');
        $consulta->condiciones('', 'kirke_menu_link', 'id_menu_link', 'iguales', 'kirke_menu_link_nombre', 'id_menu_link');
        $consulta->condiciones('y', 'kirke_menu_link', 'id_menu_link', 'iguales', '', '', $id_menu);
        $consulta->condiciones('y', 'kirke_menu_link_nombre', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        $consulta->orden('kirke_menu_link', 'orden');
        return $consulta->realizarConsulta();
    }

}

