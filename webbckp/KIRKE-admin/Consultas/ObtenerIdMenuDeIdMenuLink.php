<?php

class Consultas_ObtenerIdMenuDeIdMenuLink {

    static public function armado($id_menu_link) {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_menu_link');
        $consulta->campos('kirke_menu_link', 'id_menu');
        $consulta->condiciones('', 'kirke_menu_link', 'id_menu_link', 'iguales', '', '', $id_menu_link);
        $id_menu_link = $consulta->realizarConsulta();

        return $id_menu_link[0]['id_menu'];
    }

}

