<?php

class Consultas_ObtenerTablaNombreTipo {

    static public function armado($id_tabla = null, $idioma = null) {

        if (!isset($id_tabla)) {
            $id_tabla = $_GET['id_tabla'];
        } else {
            $id_tabla = $id_tabla;
        }

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_prefijo');
        if (!$idioma) {
            $consulta->tablas('kirke_tabla_nombre_idioma');
        }
        $consulta->campos('kirke_tabla', 'id_tabla');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->campos('kirke_tabla', 'tipo');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->campos('kirke_tabla_prefijo', 'id_tabla_prefijo');
        if (!$idioma) {
            $consulta->campos('kirke_tabla_nombre_idioma', 'tabla_nombre_idioma');
        }
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', '', '', $id_tabla);
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        if (!$idioma && ($idioma != 'sin_idioma')) {
            $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_tabla_nombre_idioma', 'id_tabla');
            $consulta->condiciones('y', 'kirke_tabla_nombre_idioma', 'idioma_codigo', 'iguales', '', '', Generales_Idioma::obtener());
        } elseif ($idioma != 'sin_idioma') {
            $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_tabla_nombre_idioma', 'id_tabla');
            $consulta->condiciones('y', 'kirke_tabla_nombre_idioma', 'idioma_codigo', 'iguales', '', '', $idioma);
        }
        //$consulta->verConsulta();
        $matriz = $consulta->realizarConsulta();

        $datos_tabla['id_tabla'] = $matriz[0]['id_tabla'];
        $datos_tabla['nombre'] = $matriz[0]['tabla_nombre'];
        $datos_tabla['tipo'] = $matriz[0]['tipo'];
        $datos_tabla['prefijo'] = $matriz[0]['prefijo'];
        $datos_tabla['id_prefijo'] = $matriz[0]['id_tabla_prefijo'];

        if (!$idioma) {
            $datos_tabla['nombre_idioma'] = $matriz[0]['tabla_nombre_idioma'];
        }

        return $datos_tabla;
    }

}

