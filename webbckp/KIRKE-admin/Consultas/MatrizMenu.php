<?php

class Consultas_MatrizMenu {

    static public function armado($id_menu = null, $idioma = null) {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_menu');
        $consulta->tablas('kirke_menu_nombre');
        $consulta->campos('kirke_menu', 'id_menu');
        $consulta->campos('kirke_menu', 'nivel1_orden');
        $consulta->campos('kirke_menu', 'nivel2_orden');
        $consulta->campos('kirke_menu', 'nivel3_orden');
        $consulta->campos('kirke_menu', 'nivel4_orden');
        $consulta->campos('kirke_menu_nombre', 'menu_nombre');
        $consulta->campos('kirke_menu_nombre', 'idioma_codigo');
        $consulta->condiciones('', 'kirke_menu', 'id_menu', 'iguales', 'kirke_menu_nombre', 'id_menu');
        $consulta->orden('kirke_menu', 'nivel1_orden');
        $consulta->orden('kirke_menu', 'nivel2_orden');
        $consulta->orden('kirke_menu', 'nivel3_orden');
        $consulta->orden('kirke_menu', 'nivel4_orden');

        if (!$id_menu || ($id_menu && $idioma)) {
            $consulta->condiciones('y', 'kirke_menu_nombre', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        };
        if ($id_menu) {
            $consulta->condiciones('y', 'kirke_menu', 'id_menu', 'iguales', '', '', $id_menu);
        }

        return $consulta->realizarConsulta();
    }

}

