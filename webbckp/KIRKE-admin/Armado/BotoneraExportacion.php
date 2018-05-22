<?php

class Armado_BotoneraExportacion {

    static public function armado() {

        $formatos = array('Excel', 'PDF', 'CVS', 'XML', 'HTML', 'SQL');
        $ver = '<script type="text/javascript" language="javascript" src="./js/exportacion_formatos.js"></script>' . "\n";
        $ver .= '<div id="linea_exportacion_formatos" ></div>' . "\n";
        //id_registro
        if (isset($_GET['id_tabla_registro']) && ($_GET['id_tabla_registro'] > 0)) {
            $id_registro = 'id_registro="' . $_GET['id_tabla_registro'] . '"';
        } else {
            $id_registro = '';
        }
        $ver .= '<div id="cuadro_exportacion_formatos" id_tabla="' . $_GET['id_tabla'] . '" ' . $id_registro . '>';
        foreach ($formatos as $formato) {
            $ver .= '<div tipo_archivo="' . strtolower($formato) . '" class="exportacion_formatos">' . $formato . '</div>';
        }
        $ver .= '<div class="texto_exportacion_formatos">{TR|o_exportacion}</div>';
        $ver .= '</div>';

        $botonera2 = new Armado_Botonera2();
        $botonera2->set($ver);
    }

}
