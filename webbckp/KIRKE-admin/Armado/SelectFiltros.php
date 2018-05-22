<?php

class Armado_SelectFiltros {

    static public function armado($cp_id, $valores, $descripciones, $condicion) {

        $select_matriz = '';
        foreach ($valores as $id => $valor) {
            $seleccionado = '';
            if ($valores[$id] == $condicion) {
                $select_matriz .= '
                <script type="text/javascript">
                  $(document).ready(function() {
                    $(".filtro_no_seleccionado.' . $cp_id . '").attr(\'id\', \'filtro_seleccionado_' . $cp_id . '\');
                    $(".filtro_no_seleccionado.' . $cp_id . '").removeClass( "filtro_no_seleccionado" ).addClass( "filtro_seleccionado" );
                    $("#parametro_' . $cp_id . '").val( "' . $condicion . '" );
                  });
                </script>
                ';
                $seleccionado = ' ' . $cp_id;
            }

            $select_matriz .= '<div filtro_tipo="' . $valores[$id] . '" filtro_tipo_id="' . $cp_id . '" class="filtro_no_seleccionado' . $seleccionado . '">' . $descripciones[$id] . '</div>';
        }

        return $select_matriz;
    }

}
