<?php

class MostrarErrores {

    private static $_var_tpl = array();
    private static $_var_tpl_glob = array();

    function __construct() {
        self::$_var_tpl_glob = VariableControl::getArrayGlobales();
    }

    public static function plantilla($plantilla, $_nombreArchivo, $_var_tpl) {

        $plantilla = str_replace(array('<br>', '<br />'), '', $plantilla);
        $plantilla = str_replace('$this->', '$control->', $plantilla);

        $plantilla = htmlspecialchars($plantilla);

        $gris = "<span style=\"color:#666666;\">";
        $negro = "<span style=\"color:#000000;\">";
        $verde = "<span style=\"color:#006600;\">";
        $azul = "<span style=\"color:#0000FF;\">";
        $rojo = "<span style=\"color:#FF0000;\">";
        $celeste = "<span style=\"color:#0066CC;\">";
        $cierre = "</span>";

        $valores_fun = $rojo . '&lt;?php' . $cierre . '<br />';

        // funaciones

        $valores_fun .= '<br />' . $azul . '//== Funciones plantilla ===' . $cierre . '<br /><br />';
        $valores_fun .= "include('_sistema/MostrarErroresPlantilla.php');<br />";
        $valores_fun .= "\$control = new MostrarErroresPlantilla();<br /><br />'";
        // armado de las variables

        $valores_fun .= $azul . '//== Variables globales ===' . $cierre . '<br /><br />';
        $muestraValoresG = new MostrarArray();
        foreach (self::$_var_tpl_glob as $nombre => $valor) {
            $valores_fun .= $muestraValoresG->arrayVer($valor, "\$var_tpl['" . $nombre . "']");
        }
        $valores_fun .= '<br />';
        $valores_fun .= $azul . '//== Variables internas ===' . $cierre . '<br /><br />';
        $muestraValoresI = new MostrarArray();
        foreach ($_var_tpl as $nombre => $valor) {
            $valores_fun .= $muestraValoresI->arrayVer($valor, "\$var_tpl['" . $nombre . "']");
        }
        $valores_fun .= '<br />' . $azul . '//== Resultado plantilla ===' . $cierre . '<br />';
        $valores_fun .= '<br />' . $rojo . '?&gt;' . $cierre . '<br /><br />';

        // armado del php

        $inicio = "<br><br><span style=\"font-family:Verdana,Arial,Helvetica,sans-serif;font-size:12px;color:#FF0000;\">";
        $inicio .= str_repeat("=", 14) . " Control plantilla: " . $negro . $_nombreArchivo . $cierre . " (inicio) ";
        $inicio .= str_repeat("=", 14) . "<br><br>" . $gris;
        $cierreControl = $cierre . "<br><br>";
        $cierreControl .= str_repeat("=", 15) . " Control plantilla: " . $negro . $_nombreArchivo . $cierre . " (fin) ";
        $cierreControl .= str_repeat("=", 15) . "===============" . $cierre . "<br><br>";

        $plantilla = str_replace("&lt;?php", $rojo . "&lt;?php" . $cierre, $plantilla);
        $plantilla = str_replace("&lt;?=", $rojo . "&lt;?=" . $cierre, $plantilla);
        $plantilla = str_replace("&lt;?", $rojo . "&lt;?" . $cierre, $plantilla);
        $plantilla = str_replace("?&gt;", $rojo . "?&gt;" . $cierre, $plantilla);
        $plantilla = str_replace(" = ", $rojo . " = " . $cierre, $plantilla);
        $plantilla = str_replace(" == ", $rojo . " == " . $cierre, $plantilla);
        $plantilla = str_replace(" === ", $rojo . " === " . $cierre, $plantilla);
        $plantilla = str_replace(" != ", $rojo . " != " . $cierre, $plantilla);
        $plantilla = str_replace(" &lt;= ", $rojo . " &lt;= " . $cierre, $plantilla);
        $plantilla = str_replace(" &gt;= ", $rojo . " &gt;= " . $cierre, $plantilla);
        $plantilla = str_replace(" * ", $rojo . " * " . $cierre, $plantilla);
        $plantilla = str_replace(" / ", $rojo . " / " . $cierre, $plantilla);
        $plantilla = str_replace(" + ", $rojo . " + " . $cierre, $plantilla);
        $plantilla = str_replace(" - ", $rojo . " - " . $cierre, $plantilla);
        $plantilla = str_replace(" =&gt; ", $rojo . " =&gt; " . $cierre, $plantilla);
        $plantilla = str_replace(" -&gt; ", $rojo . " -&gt; " . $cierre, $plantilla);
        $plantilla = str_replace("if(", $azul . "if(" . $cierre, $plantilla);
        $plantilla = str_replace("){", $azul . "){" . $cierre, $plantilla);
        $plantilla = str_replace(") {", $azul . ") {" . $cierre, $plantilla);
        $plantilla = str_replace(") ", $azul . ") " . $cierre, $plantilla);
        $plantilla = str_replace(" )", $azul . " )" . $cierre, $plantilla);
        $plantilla = str_replace("fn_modificadores(", $azul . "fn_modificadores(" . $cierre, $plantilla);
        $plantilla = str_replace("fn_links(", $azul . "fn_links(" . $cierre, $plantilla);
        $plantilla = str_replace("foreach (", $azul . "foreach (" . $cierre, $plantilla);
        $plantilla = str_replace("is_array( ", $azul . "is_array( " . $cierre, $plantilla);
        $plantilla = str_replace("}}else{", $azul . "}}else{" . $cierre, $plantilla);
        $plantilla = str_replace("}else{", $azul . "}else{" . $cierre, $plantilla);
        $plantilla = str_replace("}", $azul . "}" . $cierre, $plantilla);
        $plantilla = str_replace(" as ", $azul . " as " . $cierre, $plantilla);
        $plantilla = str_replace("true", $verde . "true" . $cierre, $plantilla);

        $plantilla = preg_replace("/(&quot;[a-zA-Z0-9._.-]+&quot;)/", $verde . "\${1}" . $cierre, $plantilla);
        $plantilla = preg_replace("/(\\$[a-zA-Z0-9._.-]+)((\['[a-zA-Z0-9._.-]+'\])+)/", $negro . "\${1}" . $cierre . $verde . "\${2}" . $cierre, $plantilla);
        $plantilla = preg_replace("/(\\$[a-zA-Z0-9._.-]+) /", $verde . "\${1}" . $cierre, $plantilla);
        $plantilla = preg_replace("/(\\$[a-zA-Z0-9._.-]+)<span/", $verde . "\${1}" . $cierre . "<span", $plantilla);

        return $inicio . $verde . $valores_fun . $cierre . nl2br($plantilla) . $cierreControl;
    }

}
