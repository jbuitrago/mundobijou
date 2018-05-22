<?php

class Armado_DesplegableOcultos extends Armado_Plantilla {

    static private $_id_cp = array();

    static public function mostrarOcultos() {
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'])) {
            $_nivelActual = $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosNivelActual'];
        } else {
            $_nivelActual = 0;
        }
        if (isset($_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['FiltrosOrden'][$_nivelActual]['filtros']['ocultar_campos'])) {
            return true;
        } else {
            return false;
        }
    }

    public function armar() {

        $armado_plantilla = new Armado_Plantilla();
        $armado_plantilla->_armadoPlantillaSet('cabeceras', '       <script type="text/javascript" language="javascript" src="./js/ocultar_vista.js"></script>' . "\n");

        $id_cp_ocultos_existentes = Consultas_ComponentesOcultos::RegistroConsulta();
        $id_cp_existentes = array();
        if (is_array($id_cp_ocultos_existentes)) {
            foreach ($id_cp_ocultos_existentes as $componentes_ocultos) {
                $texto = explode('_', $componentes_ocultos['atributo_valor']);
                $id_cp_existentes[] = $texto[1];
            }
        }
        if (count(self::$_id_cp) != 0) {
            $cantidad_cp_ocultos = 0;
            $ocultos_inicialmente = '';
            $desplegable_titulo = '
                <div id="mostrar_ocultos"';
            if (count($id_cp_existentes) == 0) {
                $desplegable_titulo .= ' style="display:none;"';
            }
            $desplegable_titulo .= '>
                    <ul class="ocultos">
                        <li><a href="">{TR|o_mostrar_ocultos}</a>
                            <ul>
                                <li><a id="mostrar_todos">{TR|o_mostrar_todos}</a></li>' . "\n                                ";
            if (is_array(self::$_id_cp)) {
                foreach (self::$_id_cp as $id => $titulo) {
                    $desplegable_titulo .= '<li><a id="li_ocultar_' . $id . '" class="ocultos_mostrar" id_mostrar_cp="' . $id . '"';
                    if (array_search($id, $id_cp_existentes) === false) {
                        $desplegable_titulo .= ' style="display:none;"';
                    } else {
                        $ocultos_inicialmente .= "\n                    $('#ocultar_cp_lin_" . $id . "').fadeOut('slow');";
                        $cantidad_cp_ocultos++;
                    }
                    if (strlen($titulo) > 10) {
                        $titulo = substr($titulo, 0, 10) . '...';
                    }
                    $desplegable_titulo .= '>' . $titulo . '</a></li>' . "\n                                ";
                }
            }
            $desplegable_titulo .= '
                            </ul>
                        </li>
                    </ul>
                </div>';

            $desplegable_titulo .= '
                <script type="text/javascript" language="javascript">
                $(function () {' . $ocultos_inicialmente . '
                });
                var cantidad_cp_ocultos = ' . $cantidad_cp_ocultos . ';
                </script>
            ';

            $this->_armadoPlantillaSet('ocultos', $desplegable_titulo, 'unico');
        } else {
            return false;
        }
    }

    static public function cargaIdComponente($id_cp, $titulo) {
        self::$_id_cp[$id_cp] = $titulo;
    }

    // cuando se elimina un componente se debe eliminar el registro del mismo (ajax)
    static public function eliminarComponenteOculto($tabla, $id_cp) {
        Consultas_ComponentesOcultos::RegistroEliminar($tabla, $id_cp);
    }

    // cuando se elimina un componente se debe agregar el registro del mismo (ajax)
    static public function agregarComponenteOculto($tabla, $id_cp) {
        Consultas_ComponentesOcultos::RegistroCrear($tabla, $id_cp);
    }
    
    // cuando se elimina un componente se debe agregar el registro del mismo (ajax)
    static public function eliminarComponenteOcultoTodos($tabla) {
        Consultas_ComponentesOcultos::RegistroEliminarTodos($tabla);
    }

}
