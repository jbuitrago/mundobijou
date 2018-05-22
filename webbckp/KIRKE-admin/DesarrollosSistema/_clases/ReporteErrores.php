<?php

class ReporteErrores {

    static public function error($tipo, $error0, $error1 = null, $error2 = null, $error3 = null) {

        if (VariableGet::sistema('mostrar_errores') === true) {

            switch ($tipo) {
                case 'archivo':
                    $error = '[No se puede obtener el siguiente archivo : <strong>' . $error0 . '</strong> (".php" o ".tpl") ]' . "\n";
                    break;
                case 'base':
                    $error = '[Exceso en ejecución de consulta : ( ' . $error0 . ' / ' . $error1 . ' ) <strong>tiempo : ' . $error3 . '</strong> | ' . $error2 . "]\n";
                    break;
            }

            if (VariableGet::sistema('mostrar_errores') === true) {
                echo $error;
            }
        }
    }

}
