<?php

class Consultas_ComponenteParametro {

    static private $_consultasRealizadas = Array();

    static public function RegistroCrear($archivo, $linea, $id_componente, $parametro, $valor) {

        if (!isset(self::$_consultasRealizadas['RegistroCrear'][$id_componente][$parametro][$valor])) {

            $consulta = new Bases_RegistroCrear($archivo, $linea);
            $consulta->tabla('kirke_componente_parametro');
            $consulta->campoValor('kirke_componente_parametro', 'id_componente', $id_componente);
            $consulta->campoValor('kirke_componente_parametro', 'parametro', $parametro);
            $consulta->campoValor('kirke_componente_parametro', 'valor', $valor);
            $resutado = $consulta->realizarConsulta();

            self::$_consultasRealizadas['RegistroCrear'][$id_componente][$parametro][$valor] = $resutado;

            return $resutado;
        } else {

            return self::$_consultasRealizadas['RegistroCrear'][$id_componente][$parametro][$valor];
        }
    }

    static public function RegistroEliminar($archivo, $linea, $id_componente, $parametro, $valor = null) {

        if (!isset(self::$_consultasRealizadas['RegistroEliminar'][$id_componente][$parametro][$valor])) {

            $consulta = new Bases_RegistroEliminar($archivo, $linea);
            $consulta->tabla('kirke_componente_parametro');
            $consulta->condiciones('', 'kirke_componente_parametro', 'id_componente', 'iguales', '', '', $id_componente);
            $consulta->condiciones('y', 'kirke_componente_parametro', 'parametro', 'iguales', '', '', $parametro);
            if (isset($valor)) {
                $consulta->condiciones('y', 'kirke_componente_parametro', 'valor', 'iguales', '', '', $valor);
            }
            $resutado = $consulta->realizarConsulta();

            self::$_consultasRealizadas['RegistroEliminar'][$id_componente][$parametro][$valor] = $resutado;

            return $resutado;
        } else {

            return self::$_consultasRealizadas['RegistroEliminar'][$id_componente][$parametro][$valor];
        }
    }

    static public function RegistroConsulta($archivo, $linea, $id_componente, $parametro, $valor = null) {

        $consulta = new Bases_RegistroConsulta($archivo, $linea);
        $consulta->tablas('kirke_componente_parametro');
        $consulta->campos('kirke_componente_parametro', 'id_componente_parametro');
        $consulta->campos('kirke_componente_parametro', 'valor');
        $consulta->condiciones('', 'kirke_componente_parametro', 'id_componente', 'iguales', '', '', $id_componente);
        $consulta->condiciones('y', 'kirke_componente_parametro', 'parametro', 'iguales', '', '', $parametro);
        if ($valor != null) {
            $consulta->condiciones('y', 'kirke_componente_parametro', 'valor', 'iguales', '', '', $valor);
        }
        //$consulta->verConsulta();
        return $consulta->realizarConsulta();
    }

    static public function RegistroModificar($archivo, $linea, $id_componente, $parametro, $valor = null) {

        $consulta = new Bases_RegistroModificar($archivo, $linea);
        $consulta->tabla('kirke_componente_parametro');
        $consulta->campoValor('kirke_componente_parametro', 'valor', $valor);
        $consulta->condiciones('', 'kirke_componente_parametro', 'id_componente', 'iguales', '', '', $id_componente);
        $consulta->condiciones('y', 'kirke_componente_parametro', 'parametro', 'iguales', '', '', $parametro);
        return $consulta->realizarConsulta();
    }

}
