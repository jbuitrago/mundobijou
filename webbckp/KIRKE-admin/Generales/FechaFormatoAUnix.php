<?php

class Generales_FechaFormatoAUnix {

    static public function armado($valor, $con_hora = null, $formato_fecha = null) {

        if ($valor == '') {
            return '';
        }

        if ($formato_fecha == null) {
            $formato_fecha = 'ddmmaaaa';
        }

        switch ($formato_fecha) {
            case 'ddmmaaaa':
                $fecha['d'] = substr($valor, 0, 2);
                $fecha['m'] = substr($valor, 3, 2);
                $fecha['a'] = substr($valor, 6, 4);
                break;
            case 'ddmmaa':
                $fecha['d'] = substr($valor, 0, 2);
                $fecha['m'] = substr($valor, 3, 2);
                $fecha['a'] = substr($valor, 6, 2);
                break;
            case 'mmddaaaa':
                $fecha['d'] = substr($valor, 3, 2);
                $fecha['m'] = substr($valor, 0, 2);
                $fecha['a'] = substr($valor, 6, 4);
                break;
            case 'mmddaa':
                $fecha['d'] = substr($valor, 3, 2);
                $fecha['m'] = substr($valor, 0, 2);
                $fecha['a'] = substr($valor, 6, 2);
                break;
            case 'aammdd':
                $fecha['d'] = substr($valor, 6, 2);
                $fecha['m'] = substr($valor, 3, 2);
                $fecha['a'] = substr($valor, 0, 2);
                break;
            case 'aaaammdd':
                $fecha['d'] = substr($valor, 8, 2);
                $fecha['m'] = substr($valor, 5, 2);
                $fecha['a'] = substr($valor, 0, 4);
                break;
        }

        if (isset($con_hora) && ($con_hora == 's')) {
            $horario = explode(' ', $valor);
            $horario = explode(':', $horario[1]);
            $hora = $horario[0];
            $minutos = $horario[1];
        } else {
            $hora = 0;
            $minutos = 0;
        }

        if (mktime($hora, $minutos, 0, $fecha['m'], $fecha['d'], $fecha['a'])) {
            $fecha_post = mktime($hora, $minutos, 0, $fecha['m'], $fecha['d'], $fecha['a']);
        } else {
            $fecha_post = 0;
        }

        return $fecha_post;
    }

}
