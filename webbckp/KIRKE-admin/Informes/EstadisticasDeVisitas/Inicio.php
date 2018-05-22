<?php

class Informes_EstadisticasDeVisitas_Inicio extends Armado_Plantilla {

    public function armado() {

        $titulo = 'estadisticas_de_visitas';

        // traduccion del titulo
        $titulo_traducido = Generales_Traduccion::traduccion('{TR|o_' . $titulo . '}', Inicio::path() . '/Informes/' . $_GET['informe'] . '/', Generales_Idioma::obtener(), '/\{TR\|([a-z])_(.*?)\}/');

        // titulo de la pagina
        $this->_armadoPlantillaSet('titulo', $titulo_traducido);

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('general'));

        $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '4', 'informe' => 'EstadisticasDeVisitas', 'pagina' => 'ListadoTotal'), 's');


        $contenido_cuerpo = '<div class="contenido_separador"></div>
      <div class="contenido_margen"></div>
      <div class="contenido_solo_titulo">
      <ul class="mn_bt_menu">
        <li class="mn_bt_boton"><a href="' . 
                './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '4', 'informe' => 'EstadisticasDeVisitas', 'pagina' => 'ListadoAltas'), 's') . 
                '" class="mn_bt_siguiente"><span>{TR|o_listado_altas}</span></a></li>
        <li class="mn_bt_boton"><a href="' . 
                './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '4', 'informe' => 'EstadisticasDeVisitas', 'pagina' => 'ListadoModificaciones'), 's') . 
                '" class="mn_bt_siguiente"><span>{TR|o_listado_modificaciones}</span></a></li>
        <li class="mn_bt_boton"><a href="' . 
                './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '4', 'informe' => 'EstadisticasDeVisitas', 'pagina' => 'ListadoBajas'), 's') . 
                '" class="mn_bt_siguiente"><span>{TR|o_listado_bajas}</span></a></li>
      </ul>
      </div>
      ';

        // traduccion del componente
        return Generales_Traduccion::traduccion($contenido_cuerpo, Inicio::path() . '/Informes/' . $_GET['informe'] . '/', Generales_Idioma::obtener(), '/\{TR\|([a-z])_(.*?)\}/');
    }

}
