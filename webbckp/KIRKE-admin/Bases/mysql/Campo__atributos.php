<?php

class Bases_Campo__atributos {

    static public function armado($campo_nuevo, $tipo, $largo = null, $nulo, $incremental, $es_indice = false, $campo_anterior = null) {

        $atributos = ' `' . $campo_nuevo . '` ';

        switch ($tipo) {
            case 'numero':
                $atributos .= ' INT (' . $largo . ') ';
                break;
            case 'texto':
                $atributos .= ' VARCHAR (' . $largo . ') ';
                break;
            case 'texto_largo':
                $atributos .= ' LONGTEXT ';
                break;
            case 'decimal':
                $largo_datos = explode(',', $largo);
                $atributos .= ' DECIMAL (' . ($largo_datos[0]) . ',' . $largo_datos[1] . ') ';
                break;
        }

        switch ($nulo) {
            case true:
                $atributos .= ' NULL ';
                break;
            case false:
                $atributos .= ' NOT NULL ';
                break;
        }

        switch ($incremental) {
            case true:
                $atributos .= ' AUTO_INCREMENT ';
                break;
            case false:
                $atributos .= '';
                break;
        }

        if (($es_indice === true) && ($campo_anterior !== null)) {
            $atributos .= ', DROP INDEX `' . $campo_anterior . '` , ADD INDEX `' . $campo_nuevo . '` ( `' . $campo_nuevo . '` ) ';
        } elseif ($es_indice === true) {
            $atributos .= ', ADD INDEX `' . $campo_nuevo . '` ( `' . $campo_nuevo . '` ) ';
        }

        return $atributos;
    }

}
