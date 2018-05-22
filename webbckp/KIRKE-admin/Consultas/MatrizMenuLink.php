<?php

class Consultas_MatrizMenuLink {

    static public function armado($id_menu) {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_menu');
        $consulta->tablas('kirke_menu_link');
        $consulta->tablas('kirke_menu_link_nombre');
        $consulta->campos('kirke_menu_link', 'id_menu_link');
        $consulta->campos('kirke_menu_link_nombre', 'menu_link_nombre');
        $consulta->campos('kirke_menu_link', 'id_menu');
        $consulta->campos('kirke_menu_link', 'id_elemento');
        $consulta->campos('kirke_menu_link', 'elemento');
        $consulta->campos('kirke_menu_link', 'orden');
        $consulta->campos('kirke_menu_link', 'habilitado');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->unionIzquierdaTablas('id1', 'kirke_menu_link', '', 'kirke_tabla', '');
        $consulta->unionIzquierdaCondiciones('id1', '', 'kirke_menu_link', 'id_elemento', 'iguales', 'kirke_tabla', 'id_tabla');
        $consulta->unionIzquierdaCondiciones('id1', 'y', 'kirke_menu_link', 'elemento', 'iguales', '', '', 'pagina');
        $consulta->unionIzquierdaSubTablas('id1', 's_id1', 'kirke_tabla', '', 'kirke_tabla_prefijo', '', 'kirke_tabla_prefijo');
        $consulta->unionIzquierdaSubCondiciones('id1', 's_id1', '', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->condiciones('', 'kirke_menu', 'id_menu', 'iguales', 'kirke_menu_link', 'id_menu');
        $consulta->condiciones('y', 'kirke_menu_link', 'id_menu_link', 'iguales', 'kirke_menu_link_nombre', 'id_menu_link');
        $consulta->condiciones('y', 'kirke_menu', 'id_menu', 'iguales', '', '', $id_menu);
        $consulta->orden('kirke_menu_link', 'orden');
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

}

