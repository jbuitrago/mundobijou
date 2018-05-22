<?php

class Armado_DesarrolloSelect {

    static public function armado($id_desarrollo_param = null) {

        $desarrollos = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Desarrollos/');
        asort($desarrollos);

        if (is_array($desarrollos)) {

            $elementos_ok = false;
            $listado_desarrollo = '<select name="id_desarrollo">';

            if (is_array($desarrollos)) {
                foreach ($desarrollos as $desaroollo_dir) {

                    $paginas_desarrollos = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Desarrollos/' . $desaroollo_dir . '/_plantillas/');

                    if (is_array($paginas_desarrollos)) {
                        foreach ($paginas_desarrollos as $paginas_desarrollos_dir) {

                            $paginas_desarrollos_dir = substr($paginas_desarrollos_dir, 0, -4);
                            $id_desarrollo = $desaroollo_dir . ':' . $paginas_desarrollos_dir;

                            $paginas_desarrollos_dir = ucfirst(strtr($paginas_desarrollos_dir, '_', ' '));
                            $nombre_desarrollo = mb_strtoupper($desaroollo_dir) . ': ' . $paginas_desarrollos_dir;

                            if ($id_desarrollo_param == $id_desarrollo) {
                                $seleccionado = 'selected';
                            } else {
                                $seleccionado = '';
                            }

                            $listado_desarrollo .= '<option value="' . $id_desarrollo . '" ' . $seleccionado . '>' . $nombre_desarrollo . '</option>';
                            $elementos_ok = true;
                        }
                    }
                }
            }

            $listado_desarrollo .= '</select>';

            if ($elementos_ok === true) {
                return $listado_desarrollo;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

}
