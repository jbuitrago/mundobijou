<?php

class Armado_TablaPie {

    public function texto($contenido) {
        return '<td class="columna">' . $contenido . '</td>';
    }

    public function id_registro($contenido) {
        return '<td class="columna"></td>';
    }

    public function link($contenido) {
        return '<td class="columna">' . $contenido . '</td>';
    }

    public function opcion($contenido) {
        return '<td class="columna">' . $contenido . '</td>';
    }
    
    public function orden($contenido) {
        return '<td class="columna_predefinida kk_resp_hidden"></td>';
    }

    public function editar($contenido) {
        return "<td id='columna_predefinida'></td>";
    }

    public function siguiente($contenido) {
        return '<td class="columna_predefinida">' . $contenido . '</td>';
    }

    public function nuevo($contenido) {
        return '<td class="columna_predefinida">' . $contenido . '</td>';
    }

    public function ver($contenido) {
        return '<td class="columna_predefinida"></td>';
    }

    public function eliminar($contenido) {
        return '<td class="columna_predefinida"></td>';
    }

    public function linkDestinoIdCp($contenido) {
        return '<td class="columna_predefinida"></td>';
    }

}

