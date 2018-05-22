<?php

class Armado_Plantilla extends Armado_PlantillaImpresion {

    protected function _armadoPlantillaSet($elemento, $contenido, $opcion = null, $identificador = null) {

        if ($identificador == null){
            $identificador = 0;
        }

        if ($opcion == null) {

            // agregar un nuevo item al elemento
            if (!isset(parent::$_variablesPlantilla[$elemento])) {
                $identificador = 0;
            } else {
                $identificador = count(parent::$_variablesPlantilla[$elemento]);
            }

            parent::$_variablesPlantilla[$elemento][$identificador] = $contenido;
        } elseif ($opcion == 'unico') {

            // agraga el elemento ya que no existe
            parent::$_variablesPlantilla[$elemento][$identificador] = $contenido;
        } elseif (($opcion == 'contenido_unico')) {

            // agrega el elemento entre etiquetas
            $contenido['inicio'] = '';
            $contenido['fin'] = '';

            // obtengo el valor cargado con anterioridad
            if (isset(parent::$_variablesPlantilla[$elemento][$identificador])) {
                $obtener_valores = parent::$_variablesPlantilla[$elemento][$identificador];
            } else {
                $obtener_valores = '';
            }

            if ($obtener_valores) {
                // se saco las etiquetas a valor obtenido con anterioriodad
                $valores_limpio = str_replace($contenido['inicio'], '', $obtener_valores);
                $valores_limpio = str_replace($contenido['fin'], '', $valores_limpio);

                // al valor limpio le concateno el nuevo contenido
                $valores_limpio .= $contenido['contenido'];
            } else {
                $valores_limpio = $contenido['contenido'];
            }

            $agregar = $contenido['inicio'];
            $agregar .= $valores_limpio;
            $agregar .= $contenido['fin'];

            parent::$_variablesPlantilla[$elemento][$identificador] = $agregar;
        }
    }

    public function imprimir() {

        return $this->_plantillaImpresion();
    }

}

