<?php

class Consultas_MenuNombre {

    static public function RegistroCrear($archivo, $linea, $id_menu, $idioma, $idioma_texto) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_menu_nombre');
        $consulta->campoValor('kirke_menu_nombre', 'id_menu', $id_menu);
        $consulta->campoValor('kirke_menu_nombre', 'idioma_codigo', $idioma);
        $consulta->campoValor('kirke_menu_nombre', 'menu_nombre', $idioma_texto);
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsulta($archivo, $linea, $id_menu) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_menu');
        $consulta->tablas('kirke_menu_nombre');
        $consulta->campos('kirke_menu_nombre', 'menu_nombre');
        $consulta->condiciones('', 'kirke_menu', 'id_menu', 'iguales', 'kirke_menu_nombre', 'id_menu');
        $consulta->condiciones('y', 'kirke_menu', 'id_menu', 'iguales', '', '', $id_menu);
        $consulta->condiciones('y', 'kirke_menu_nombre', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        return $consulta->realizarConsulta();
    }

}

