<?php

class Armado_CantidadPorPagina {

    static private $cantidad = 50;
    static private $cantidad_min = 5;
    static private $cantidad_max = 100;

    static public function armado() {

        $ver = '<form name="form_cantidad_por_pagina" id="form_cantidad_por_pagina" target="_self" method="post" action="">';
        $ver .= '<div class="cantidad_por_pagina_contenedor">{TR|o_cantidad_por_pagina}: <select name="cantidad_por_pagina" id="cantidad_por_pagina" class="cantidad_por_pagina">';
        for ($i = self::$cantidad_min; $i <= self::$cantidad_max; $i = $i + 5) {
            if ($i == self::$cantidad) {
                $seleccionado = ' selected="selected"';
            } else {
                $seleccionado = '';
            }
            $ver .= '<option value="' . $i . '"' . $seleccionado . '>' . $i . '</option>';
        }
        $ver .= '</select></div></form>';

        $botonera2 = new Armado_Botonera2();
        $botonera2->set($ver);
    }

    static public function cantidad() {

        $cantidad_por_pagina = Consultas_UsuarioAtributo::RegistroConsulta(__FILE__, __LINE__, Inicio::usuario('id'), 'cantidad_por_pagina_' . $_GET['id_tabla']);

        if (isset($_POST['cantidad_por_pagina']) && ((int) $_POST['cantidad_por_pagina'] != $cantidad_por_pagina[0]['atributo_valor'])) {
            if (((int) $_POST['cantidad_por_pagina'] <= self::$cantidad_max) && ((int) $_POST['cantidad_por_pagina'] >= self::$cantidad_min)) {
                if (is_array($cantidad_por_pagina)) {
                    Consultas_UsuarioAtributo::RegistroModificar(__FILE__, __LINE__, Inicio::usuario('id'), 'cantidad_por_pagina_' . $_GET['id_tabla'], (int) $_POST['cantidad_por_pagina']);
                } else {
                    Consultas_UsuarioAtributo::RegistroCrear(__FILE__, __LINE__, Inicio::usuario('id'), 'cantidad_por_pagina_' . $_GET['id_tabla'], (int) $_POST['cantidad_por_pagina']);
                }
                $nuevo_valor = (int) $_POST['cantidad_por_pagina'];
            }
        }

        if (isset($nuevo_valor)) {
            self::$cantidad = $nuevo_valor;
        } elseif (is_array($cantidad_por_pagina)) {
            self::$cantidad = $cantidad_por_pagina[0]['atributo_valor'];
        }

        return self::$cantidad;
    }

}
