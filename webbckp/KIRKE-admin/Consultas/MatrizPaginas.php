<?php

class Consultas_MatrizPaginas {

    static public function armado() {

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_nombre_idioma');
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->campos('kirke_tabla_nombre_idioma', 'tabla_nombre_idioma');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->campos('kirke_tabla', 'id_tabla');
        $consulta->campos('kirke_tabla', 'orden');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->campos('kirke_tabla', 'tipo');
        if (Inicio::usuario('tipo') != 'administrador general') {
            $consulta->condiciones('', 'kirke_tabla', 'habilitado', 'iguales', '', '', 's');
        }
        $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'distintos', '', '', 'componente');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_tabla_nombre_idioma', 'id_tabla');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->condiciones('y', 'kirke_tabla_nombre_idioma', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        $consulta->orden('kirke_tabla_prefijo', 'prefijo');
        $consulta->orden('kirke_tabla', 'tabla_nombre');
        return $consulta->realizarConsulta();
    }

}

