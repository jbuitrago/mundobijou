<?php

class Acciones_MenuLinkAlta extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '19', 'id_menu' => $_GET['id_menu']);
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '21', 'id_menu' => $_GET['id_menu']);
        $armado_botonera->armar('volver', $parametros);

        $plantilla['etiqueta_idiomas'] = Armado_EtiquetaIdiomas::armar('etiqueta');

        $checked = 'checked="checked"';

        $select = Armado_PaginaSelect::armado('id_tabla_registros', 'registros');
        if ($select !== false) {
            $plantilla['pagina_select_registros'] = '
                <div class="contenido_separador"></div>
                <div class="contenido_margen"></div>
                <div class="contenido_titulo">{TR|o_paginas_de_registros}</div>
                <div class="contenido_1"><input type="radio" name="elemento" value="pagina_registros" ' . $checked . ' /></div>
                <div class="contenido_6">' . $select . '</div>';
            $checked = '';
        } else {
            $plantilla['pagina_select_registros'] = '';
        }

        $select = Armado_PaginaSelect::armado('id_tabla_variables', 'variables');
        if ($select !== false) {
            $plantilla['pagina_select_variables'] = '
                <div class="contenido_separador"></div>
                <div class="contenido_margen"></div>
                <div class="contenido_titulo">{TR|o_paginas_de_variables}</div>
                <div class="contenido_1"><input type="radio" name="elemento" value="pagina_variables" ' . $checked . ' /></div>
                <div class="contenido_6">' . $select . '</div>';
            $checked = '';
        } else {
            $plantilla['pagina_select_variables'] = '';
        }

        $select = Armado_PaginaSelect::armado('id_tabla_menu', 'menu');
        if ($select !== false) {
            $plantilla['pagina_select_menu'] = '
                <div class="contenido_separador"></div>
                <div class="contenido_margen"></div>
                <div class="contenido_titulo">{TR|o_menus}</div>
                <div class="contenido_1"><input type="radio" name="elemento" value="pagina_menu" ' . $checked . ' /></div>
                <div class="contenido_6">' . $select . '</div>';
            $checked = '';
        } else {
            $plantilla['pagina_select_menu'] = '';
        }

        $select = Armado_PaginaSelect::armado('id_tabla_tabuladores', 'tabuladores');
        if ($select !== false) {
            $plantilla['pagina_select_tabuladores'] = '
                <div class="contenido_separador"></div>
                <div class="contenido_margen"></div>
                <div class="contenido_titulo">{TR|o_tabuladores}</div>
                <div class="contenido_1"><input type="radio" name="elemento" value="pagina_tabuladores" ' . $checked . ' /></div>
                <div class="contenido_6">' . $select . '</div>';
            $checked = '';
        } else {
            $plantilla['pagina_select_tabuladores'] = '';
        }
        
        $select = Armado_InformeSelect::armado();
        if ($select !== false) {
            $plantilla['informe_select'] = '
                <div class="contenido_separador"></div>
                <div class="contenido_margen"></div>
                <div class="contenido_titulo">{TR|o_informe}</div>
                <div class="contenido_1"><input type="radio" name="elemento" value="informe" ' . $checked . ' /></div>
                <div class="contenido_6">' . $select . '</div>';
            $checked = '';
        } else {
            $plantilla['informe_select'] = '';
        }

        $select = Armado_DesarrolloSelect::armado();
        if ($select !== false) {
            $plantilla['desarrollo_select'] = '
                <div class="contenido_separador"></div>
                <div class="contenido_margen"></div>
                <div class="contenido_titulo">{TR|o_desarrollo}</div>
                <div class="contenido_1"><input type="radio" name="elemento" value="desarrollo" ' . $checked . ' /></div>
                <div class="contenido_6">' . $select . '</div>';
            $checked = '';
        } else {
            $plantilla['desarrollo_select'] = '';
        }

        return Armado_PlantillasInternas::acciones(basename(__FILE__), $plantilla);
    }

}
