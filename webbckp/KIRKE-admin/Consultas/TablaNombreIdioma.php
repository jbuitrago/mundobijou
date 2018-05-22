<?php

class Consultas_TablaNombreIdioma {

    static public function RegistroConsulta($archivo, $linea, $idioma, $id_tabla = null, $tipo = 'todas') {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_nombre_idioma');
        $consulta->campos('kirke_tabla', 'id_tabla');
        $consulta->campos('kirke_tabla_nombre_idioma', 'tabla_nombre_idioma');
        $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'distintos', '', '', 'componente');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_tabla_nombre_idioma', 'id_tabla');
        $consulta->condiciones('y', 'kirke_tabla_nombre_idioma', 'idioma_codigo', 'iguales', '', '', $idioma);
        if (isset($id_tabla)) {
            $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', '', '', $id_tabla);
        }
        if($tipo == 'registros'){
            $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'iguales', '', '', 'registros');
        }elseif($tipo == 'variables'){
            $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'iguales', '', '', 'variables');
        }elseif($tipo == 'menu'){
            $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'iguales', '', '', 'menu');
        }elseif($tipo == 'tabuladores'){
            $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'iguales', '', '', 'tabuladores');
        }
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaPrefijo($archivo, $linea, $id_tabla) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_nombre_idioma');
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->campos('kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->campos('kirke_tabla', 'id_tabla');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->campos('kirke_tabla', 'habilitado');
        $consulta->campos('kirke_tabla', 'tipo');
        $consulta->campos('kirke_tabla_nombre_idioma', 'idioma_codigo');
        $consulta->campos('kirke_tabla_nombre_idioma', 'tabla_nombre_idioma');
        $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'distintos', '', '', 'componente');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_tabla_nombre_idioma', 'id_tabla');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'iguales', '', '', $id_tabla);
        $consulta->orden('kirke_tabla_prefijo', 'prefijo');
        $consulta->orden('kirke_tabla', 'tabla_nombre');
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroCrear($archivo, $linea, $id_tabla_crear, $idioma, $idioma_texto) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_tabla_nombre_idioma');
        $consulta->campoValor('kirke_tabla_nombre_idioma', 'id_tabla', $id_tabla_crear);
        $consulta->campoValor('kirke_tabla_nombre_idioma', 'idioma_codigo', $idioma);
        $consulta->campoValor('kirke_tabla_nombre_idioma', 'tabla_nombre_idioma', $idioma_texto);
        return $consulta->realizarConsulta();
    }

    static public function RegistroModificar($archivo, $linea, $idioma_texto, $id_tabla, $idioma_codigo) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_tabla_nombre_idioma');
        $consulta->campoValor('kirke_tabla_nombre_idioma', 'tabla_nombre_idioma', $idioma_texto);
        $consulta->condiciones('', 'kirke_tabla_nombre_idioma', 'id_tabla', 'iguales', '', '', $id_tabla);
        $consulta->condiciones('y', 'kirke_tabla_nombre_idioma', 'idioma_codigo', 'iguales', '', '', $idioma_codigo);
        return $consulta->realizarConsulta();
    }

}

