<?php

class ReporteErrores {

    static public function error($archivo, $linea, $tipo, $error0, $error1 = null, $error2 = null, $error3 = null) {

        if (VariableGet::sistema('mostrar_errores') === true) {

            $lugar = 'Archivo: ' . $archivo . "\n<br />" . 'Linea: ' . $linea . "\n<br />";

            switch ($tipo) {
                case 'archivo':
                    $error = '[No se puede obtener el siguiente archivo : <strong>' . $error0 . '</strong> (".php" o ".tpl") ]' . "\n<br />" . $lugar;
                    break;
                case 'base':
                    $error = '[Exceso en ejecución de consulta : ( ' . $error0 . ' / ' . $error1 . ' ) <strong>tiempo : ' . $error3 . '</strong> | ' . $error2 . "]\n<br />" . $lugar;
                    break;
            }

            echo $error;
        }
    }

}
