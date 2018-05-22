<?php

class Consultas_Componente {

    static private $_consultasRealizadas = Array();

    static public function RegistroConsulta($archivo, $linea, $id_componente) {

        if (!isset(self::$_consultasRealizadas['RegistroConsulta'][$id_componente])) {

            $consulta = new Bases_RegistroConsulta($archivo, $linea);
            $consulta->tablas('kirke_componente');
            $consulta->campos('kirke_componente', 'id_tabla');
            $consulta->campos('kirke_componente', 'componente');
            $consulta->campos('kirke_componente', 'tabla_campo');
            $consulta->condiciones('', 'kirke_componente', 'id_componente', 'iguales', '', '', $id_componente);
            $resutado = $consulta->realizarConsulta();

            self::$_consultasRealizadas['RegistroConsulta'][$id_componente] = $resutado;

            return $resutado;
        } else {

            return self::$_consultasRealizadas['RegistroConsulta'][$id_componente];
        }
    }

    static public function RegistroConsultaTabla($archivo, $linea, $id_tabla, $tabla_actual = false) {

        if (!isset(self::$_consultasRealizadas['RegistroConsultaTabla'][$id_tabla])) {

            $consulta = new Bases_RegistroConsulta($archivo, $linea);
            $consulta->tablas('kirke_tabla');
            $consulta->tablas('kirke_componente');
            $consulta->campos('kirke_tabla', 'id_tabla');
            $consulta->campos('kirke_tabla', 'tabla_nombre');
            $consulta->campos('kirke_componente', 'id_componente');
            $consulta->campos('kirke_componente', 'tabla_campo');
            $consulta->condiciones('', 'kirke_tabla', 'id_tabla', 'iguales', 'kirke_componente', 'id_tabla');
            if ($tabla_actual === false) {
                $consulta->condiciones('y', 'kirke_tabla', 'id_tabla', 'distintos', '', '', $id_tabla);
            }
            $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'distintos', '', '', 'variables');
            $consulta->condiciones('y', 'kirke_componente', 'tabla_campo', 'distintos', '', '', '');
            $consulta->orden('kirke_tabla', 'tabla_nombre');
            $consulta->orden('kirke_componente', 'tabla_campo');
            //$consulta->verConsulta();
            $resutado = $consulta->realizarConsulta();

            self::$_consultasRealizadas['RegistroConsultaTabla'][$id_tabla] = $resutado;

            return $resutado;
        } else {

            return self::$_consultasRealizadas['RegistroConsultaTabla'][$id_tabla];
        }
    }

    static public function RegistroModificar($archivo, $linea, $id_componente, $valor) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_componente');
        $consulta->campoValor('kirke_componente', 'tabla_campo', $valor);
        $consulta->condiciones('', 'kirke_componente', 'id_componente', 'iguales', '', '', $id_componente);
        return $consulta->realizarConsulta();
    }

    static public function RegistroCrear($archivo, $linea, $id_tabla, $orden, $componente, $tb_campo) {

        $consulta = new Bases_RegistroCrear($archivo, $linea);
        $consulta->tabla('kirke_componente');
        $consulta->campoValor('kirke_componente', 'id_tabla', $id_tabla);
        $consulta->campoValor('kirke_componente', 'orden', $orden);
        $consulta->campoValor('kirke_componente', 'componente', $componente);
        $consulta->campoValor('kirke_componente', 'tabla_campo', $tb_campo);
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroConsultaInsercion($archivo, $linea, $id_tabla, $tb_campo, $id_componente = null) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_componente');
        $consulta->campos('kirke_componente', 'id_componente');
        $consulta->condiciones('', 'kirke_componente', 'id_tabla', 'iguales', '', '', $id_tabla);
        $consulta->condiciones('y', 'kirke_componente', 'tabla_campo', 'iguales', '', '', $tb_campo);
        if ($id_componente !== null) {
            $consulta->condiciones('y', 'kirke_componente', 'id_componente', 'distintos', '', '', $id_componente);
        }
        //echo $consulta->verConsulta();
        $id_componente = $consulta->realizarConsulta();

        return $id_componente[0]['id_componente'];
    }

}
