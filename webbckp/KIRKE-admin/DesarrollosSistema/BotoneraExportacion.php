<?php

class DesarrollosSistema_BotoneraExportacion {

    static public function armado($tabla) {

        $formatos = array('Excel', 'PDF', 'CVS', 'XML', 'HTML');
        $ver = '<div id="linea_exportacion_formatos" ></div>' . "\n";
        $ver .= '<div id="cuadro_exportacion_formatos">';
        foreach ($formatos as $formato) {
            $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => $_GET['kk_generar'], 'kk_desarrollo' => $_GET['kk_desarrollo'], '0' => $_GET['0'], 'kk_tabla' => $tabla, 'kk_exportar_formato' => strtolower($formato)), 's');
            $ver .= '<a href="' . $link . '"><div class="exportacion_formatos"> ' . $formato . '</div></a>';
        }
        $ver .= '<div class="texto_exportacion_formatos">{TR|o_exportacion}</div>';
        $ver .= '</div>';

        return $ver;
    }

}
