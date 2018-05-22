<?php

class BDObtenerOrden {

    static public function consulta($tabla) {

        BDConexion::consulta();

        $clase_de_bd = 'BD' . ucfirst(VariableGet::sistema('tipo_base')) . 'ObtenerOrden';
        eval('$bd = ' . $clase_de_bd . '::consulta($tabla);');

        return $bd;
    }

}
