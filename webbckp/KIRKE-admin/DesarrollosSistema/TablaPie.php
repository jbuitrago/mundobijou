<?php

class DesarrollosSistema_TablaPie {

    public function texto($contenido, $alinear) {
        return '<td class="columna" ' . $alinear . '>' . $contenido . '</td>';
    }

    public function id_registro($contenido, $alinear) {
        return '<td class="columna" ' . $alinear . '>' . $contenido . '</td>';
    }

    public function link($contenido, $alinear) {
        return '<td class="columna" ' . $alinear . '>' . $contenido . '</td>';
    }

    public function orden($contenido, $alinear) {
        return '<td class="columna_predefinida">' . $contenido . '</td>';
    }

    public function editar($contenido, $alinear) {
        return '<td id="columna_predefinida">' . $contenido . '</td>';
    }

    public function siguiente($contenido, $alinear) {
        return '<td class="columna_predefinida">' . $contenido . '</td>';
    }

    public function nuevo($contenido, $alinear) {
        return '<td class="columna_predefinida">' . $contenido . '</td>';
    }

    public function ver($contenido, $alinear) {
        return '<td class="columna_predefinida">' . $contenido . '</td>';
    }

    public function eliminar($contenido, $alinear) {
        return '<td class="columna_predefinida">' . $contenido . '</td>';
    }

    public function linkDestinoIdCp($contenido, $alinear) {
        return '<td class="columna_predefinida">' . $contenido . '</td>';
    }

}
