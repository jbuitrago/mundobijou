<?php

class Acciones_RegistroVer extends Armado_Plantilla {

    private $_validacion;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $this->_validacion = $validacion->consultaElemento('pagina', $_GET['id_tabla'], 'ver');

        Generales_FiltrosOrden::actualizarIdAccion($_GET['id_tabla_registro']);
        Generales_FiltrosOrden::armar();

        Armado_Titulo::forzarTitulo(Generales_FiltrosOrden::obtenerMenuLinkTitulo());

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('general'));

        $armado_botonera = new Armado_Botonera();

        $this->_armadoPlantillaSet('datos_pagina', 'url="' . $_SERVER['REQUEST_URI'] . '"');

        if (isset($_GET['id_ocultar_cp'])) {
            Armado_DesplegableOcultos::agregarComponenteOculto($_GET['id_tabla'], $_GET['id_ocultar_cp']);
            exit;
        } elseif (isset($_GET['id_mostrar_cp'])) {
            Armado_DesplegableOcultos::eliminarComponenteOculto($_GET['id_tabla'], $_GET['id_mostrar_cp']);
            exit;
        } elseif (isset($_GET['mostrar_todo'])) {
            Armado_DesplegableOcultos::eliminarComponenteOcultoTodos($_GET['id_tabla']);
            exit;
        }

        if ($this->_validacion == 'datos') {
            $parametros = array('kk_generar' => '0', 'accion' => '42', 'id_tabla' => $_GET['id_tabla'], 'id_tabla_registro' => $_GET['id_tabla_registro']);
            $armado_botonera->armar('editar', $parametros);
        }

        $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
        $armado_botonera->armar('volver', $parametros);

        Armado_BotoneraExportacion::armado();

        // armado de links de relaciones entre paginas para volver a la
        // pagina que la linkeo
        if (Armado_LinkADestino::armadoVolver() !== false) {
            $parametros = Armado_LinkADestino::armadoVolver();
            $armado_botonera->armar('volver_a_registros', $parametros, '', 's');
        }

        // se obtiene el nombre de la pagina y el id de la misma
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $tabla_nombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
        $tabla_tipo = $datos_tabla['tipo'];

        // creo una matriz con los campos de los componentes de la pagina
        $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos');

        // llamo al metodo del tipo de tabla correspondiente
        if ($tabla_tipo == 'variables') {
            $matriz_valores = Generales_ObtenerValoresTbVariables::armado($tabla_nombre, $matriz_componentes);
        } elseif ($tabla_tipo == 'registros') {
            $matriz_valores = Generales_ObtenerValoresTbRegistros::armado($tabla_nombre, $matriz_componentes);
        }

        if (is_array($matriz_componentes)) {

            $componente_armado = '';
            foreach ($matriz_componentes as $id => $value) {

                if (isset($matriz_valores[$value['tb_campo']])) {
                    $valor = $matriz_valores[$value['tb_campo']];
                } else {
                    $valor = '';
                }

                $componente_armado .= Generales_LlamadoAComponentesYTraduccion::armar('RegistroVer', 'registroVer', $valor, $value, $value['cp_nombre'], $value['cp_id'], $_GET['id_tabla_registro']);

                if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
                    if ($value['idioma_' . Generales_Idioma::obtener()] == '') {
                        $value['idioma_' . Generales_Idioma::obtener()] = '---';
                    }
                    Armado_DesplegableOcultos::cargaIdComponente($value['cp_id'], $value['idioma_' . Generales_Idioma::obtener()]);
                }
            }
        }

        $links_a_relacionados = $this->_registrosDestinoIdCp();

        if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
            $desplegable_ocultos = new Armado_DesplegableOcultos();
            $desplegable_ocultos->armar();
        }

        return $componente_armado . $links_a_relacionados;
    }

    private function _registrosDestinoIdCp() {

        $matriz = Consultas_TablaParametros::RegistroConsultaValor(__FILE__, __LINE__, $_GET['id_tabla']);

        if (is_array($matriz)) {
            $contenido = Armado_PlantillasInternas::acciones(basename(__FILE__));

            foreach ($matriz as $value) {

                $dcp = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($value['valor']);

                if (isset($dcp['tb_id'])) {

                    $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($dcp['tb_id']);
                    $link = Armado_LinkADestino::armadoSiguiente($value['valor'], $_GET['id_tabla_registro'], '41', $datos_tabla['nombre_idioma'], true);
                    
                    Armado_Cabeceras::colorbox('');
                    $plantilla['LinkADestino'] = '<a class="iframe_colorbox cboxElement" href="'.$link.'&tipo_pagina=pop&vista=ver"> '.$datos_tabla['nombre_idioma'].'</a>';
                    
                    $contenido .= Armado_PlantillasInternas::acciones('RegistroVer_detalle', $plantilla);
                }

                unset($plantilla);
            }

            return $contenido;
        } else {
            return '';
        }
    }

}
