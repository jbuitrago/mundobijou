<?php

class Armado_PlantillaImpresion extends Generales_Traduccion {

    protected static $_variablesPlantilla = array();

    protected function _plantillaImpresion() {

        if (isset($_GET['tipo_pagina']) && ($_GET['tipo_pagina'] == 'pop')) {
            $plantilla_html = 'pop.tpl';
        } elseif ($_GET['accion'] != 'Inicio') {
            $plantilla_html = 'pagina.tpl';
        } else {
            $plantilla_html = 'inicio.tpl';
        }

        $html_pagina = $this->_obtenerSubPlantilla($plantilla_html);

        $i = 0;
        foreach (self::$_variablesPlantilla as $elemento => $valores) {
            $contenido_concatenado = '';
            foreach ($valores as $identificador) {
                $contenido_concatenado = $contenido_concatenado . $identificador;
            }
            $etiqueta_reemplazo[$i] = "{PL|" . $elemento . "}";
            $contenido_etiqueta_html[$i] = $contenido_concatenado;

            $i++;
        }

        $pagina_armada = str_replace($etiqueta_reemplazo, $contenido_etiqueta_html, $html_pagina);

        // reemplazo de los comodines no utilizados
        $reemplazo = "/{[P][L][|](.*?)}/";
        $pagina_armada = preg_replace($reemplazo, '', $pagina_armada);

        // reemplazo las imagenes de la plantilla
        $patron = "/{[I][M][G][|](.*?)}/";
        $pagina_armada = preg_replace($patron, '<img src="./Plantillas/' . Inicio::confVars('plantilla') . '/img/${1}" border="0">', $pagina_armada);

        return self::traduccion($pagina_armada, Inicio::path() . '/Traducciones/', Generales_Idioma::obtener(), '/\{TR\|([a-z])_(.*?)\}/');
    }

    private function _obtenerSubPlantilla($archivo) {
        if (file_exists(Inicio::pathPublico() . '/Plantillas/' . Inicio::confVars('plantilla') . '/tpl/' . $archivo)) {
            return file_get_contents(Inicio::pathPublico() . '/Plantillas/' . Inicio::confVars('plantilla') . '/tpl/' . $archivo);
        } else {
            echo 'problema en PlantillaImpresion';
        }
    }

}
