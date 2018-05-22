<?php

class Armado_ComponenteSelect extends Armado_Plantilla {

    public function armar() {

        $directorios = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Componentes/');
        asort($directorios);

        foreach ($directorios as $linea) {

            $no_directorio = stripos($linea, '.');

            if ($no_directorio === false) {

                if (substr($linea, 0, 6) != 'Pagina') {

                    $direccion = Inicio::path() . '/Componentes/' . $linea . '/';

                    $archivo = Generales_Idioma::obtener() . '.php';

                    $leer_archivo = new Generales_ArchivoVariables();
                    $componente_traduccion = $leer_archivo->archivoLeer($direccion, $archivo, 'ComponenteSelect');

                    $componente_datos[$linea] = $componente_traduccion['nombre_componente'];
                }
            }
        }

        // Script para que haga el salto de pagina
        $nuevo_js = '      <script type="text/javascript" language="javascript" src="./js/desplegable_link.js"></script>' . "\n";

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', $nuevo_js);

        $componente_select = '<br><br>&nbsp;&nbsp;&nbsp;{TR|o_agregar_componente}<br><br>';
        $componente_select .= '&nbsp;&nbsp;&nbsp;<select name="componente_nvo" onChange="a_pagina(0,this)">';
        $componente_select .= '<option value="" selected>{TR|o_seleccione_un_componente}</option>';

        // reordeno los elementos del array para que se vean en orden alfabético
        $componente_datos = array_flip($componente_datos);
        ksort($componente_datos);
        $componente_datos = array_flip($componente_datos);

        foreach ($componente_datos as $id => $valor) {
            $valores = array('kk_generar' => '0', 'accion' => '2', 'componente' => $id, 'id_tabla' => $_GET['id_tabla']);
            $componente_select .= '<option value="./index.php?' . Generales_VariablesGet::armar($valores, 's') . '">' . $valor . ' </option>';
        }
        $componente_select .= '</select>';

        return $componente_select;
    }

}
