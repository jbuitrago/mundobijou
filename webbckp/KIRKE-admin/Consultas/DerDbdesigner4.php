<?php

class Consultas_DerDbdesigner4 {

    static public function matrizTablas($archivo, $linea) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_tabla');
        $consulta->tablas('kirke_tabla_prefijo');
        $consulta->tablas('kirke_componente');
        $consulta->campos('kirke_tabla', 'id_tabla');
        $consulta->campos('kirke_tabla_prefijo', 'prefijo');
        $consulta->campos('kirke_tabla', 'tabla_nombre');
        $consulta->campos('kirke_tabla', 'tipo');
        $consulta->campos('kirke_componente', 'tabla_campo');
        $consulta->campos('kirke_componente', 'id_componente');
        $consulta->condiciones('', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
        $consulta->condiciones('y', 'kirke_componente', 'id_tabla', 'iguales', 'kirke_tabla', 'id_tabla');
        $consulta->orden('kirke_tabla_prefijo', 'prefijo');
        $consulta->orden('kirke_tabla', 'tabla_nombre');
        $consulta->orden('kirke_componente', 'tabla_campo');
        return $consulta->realizarConsulta();
    }

    static public function matrizParametros($archivo, $linea, $componente_id) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_componente');
        $consulta->tablas('kirke_componente_parametro');
        $consulta->campos('kirke_componente_parametro', 'parametro');
        $consulta->campos('kirke_componente_parametro', 'valor');
        $consulta->condiciones('', 'kirke_componente', 'id_componente', 'iguales', 'kirke_componente_parametro', 'id_componente');
        $consulta->condiciones('y', 'kirke_componente_parametro', 'id_componente', 'iguales', '', '', $componente_id);
        $consulta->orden('kirke_componente_parametro', 'parametro');
        return $consulta->realizarConsulta();
    }

}

