<?php

class Acciones_PaginaAlta extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $ver = $this->_paginaNueva();

        return $ver;
    }

    private function _paginaNueva() {

        if (Armado_PrefijoSelect::armado() !== false) {

            $plantilla['prefijo_select'] = Armado_PrefijoSelect::armado();
            $plantilla['etiqueta_diomas'] = Armado_EtiquetaIdiomas::armar('etiqueta');

            $ver = Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);

            $armado_botonera = new Armado_Botonera();

            $parametros = array('kk_generar' => '0', 'accion' => '28');
            $armado_botonera->armar('guardar', $parametros);

            $parametros = array('kk_generar' => '0', 'accion' => '30');
            $armado_botonera->armar('volver', $parametros);
        } else {

            $ver = '<div class="texto_advertencia_pagina" align="center">{TR|o_antes_pagina_prefijo}</div>';

            $armado_botonera = new Armado_Botonera();

            $parametros = array('kk_generar' => '0', 'accion' => '30');
            $armado_botonera->armar('volver', $parametros);
        }

        return $ver;
    }

}
