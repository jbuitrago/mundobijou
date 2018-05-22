<?php

class BDConexion {

    static private $conexion = false;

    static public function consulta() {

        if (self::$conexion == false) {

            if(class_exists('PathUrl')){
                require_once PathUrl::$conf . '_configuraciones/bases.php';
            }else{
                $bases['servidor'] = Inicio::confVars('servidor');
                $bases['base'] = Inicio::confVars('basedatos');
                $bases['usuario'] = Inicio::confVars('usuario');
                $bases['clave'] = Inicio::confVars('clave');
            }
            
            // control de valores de conexion a base de datos
            if (
                    ( $bases['servidor'] != '' ) && ( $bases['base'] != '' ) && ( $bases['usuario'] != '' ) && ( $bases['clave'] != '' )
            ) {
                self::$conexion = true;

                $clase_de_bd = 'BD' . ucfirst(VariableGet::sistema('tipo_base')) . 'Conexion';
                eval('$bd = ' . $clase_de_bd . "::conectar('" . $bases['servidor'] . "','" . $bases['base'] . "','" . $bases['usuario'] . "','" . $bases['clave'] . "');");
                return $bd;
            } else {
                if ((VariableGet::sistema('mostrar_errores') === true) && ($bases['base'] != '') && !isset($_GET['kk_administracion'])) {
                    echo 'No se han ingresado todas las variables de configuración de la base de datos';
                }
                return false;
            }
        } else {

            return false;
        }
    }

}
