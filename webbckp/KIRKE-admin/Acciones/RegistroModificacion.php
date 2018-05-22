<?php

class Acciones_RegistroModificacion extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('pagina', $_GET['id_tabla'], 'datos');

        Generales_FiltrosOrden::actualizarIdAccion($_GET['id_tabla_registro']);
        Generales_FiltrosOrden::armar();

        Armado_Titulo::forzarTitulo(Generales_FiltrosOrden::obtenerMenuLinkTitulo());

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

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
        
        // se obtiene el nombre de la pagina
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $this->_tablaNombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];
        
        // llamado al javascript especial de la pagina
        if (file_exists(Inicio::path() . '/ProcesosEspeciales/RegistroModificacion-' . $this->_tablaNombre . '.js')) {
            Armado_Cabeceras::jsProcesosEspeciales('RegistroModificacion', $this->_tablaNombre);
        }

        $parametros = array('kk_generar' => '0', 'accion' => '43', 'id_tabla' => $_GET['id_tabla'], 'id_tabla_registro' => $_GET['id_tabla_registro'], 'guardar' => 'dir');
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '43', 'id_tabla' => $_GET['id_tabla'], 'id_tabla_registro' => $_GET['id_tabla_registro'], 'guardar' => 'dir', 'siguiente' => 'ver');
        $armado_botonera->armar('guardar_ver', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '44', 'id_tabla' => $_GET['id_tabla'], 'id_tabla_registro' => $_GET['id_tabla_registro']);
        $armado_botonera->armar('vista_previa', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla']);
        $armado_botonera->armar('volver', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '42', 'id_tabla' => $_GET['id_tabla'], 'id_tabla_registro' => $_GET['id_tabla_registro']);
        $armado_botonera->armar('refrescar', $parametros);

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

        $componente_armado = '<div class="contenido_separador"></div>';

        if ($matriz_componentes) {

            foreach ($matriz_componentes as $id => $value) {
                if (isset($matriz_valores[$value['tb_campo']])) {
                    $valor = $matriz_valores[$value['tb_campo']];
                } else {
                    $valor = '';
                }

                $componente_armado .= Generales_LlamadoAComponentesYTraduccion::armar('RegistroVer', 'registroModificacion', $valor, $value, $value['cp_nombre'], $value['cp_id'], $_GET['id_tabla_registro']);

                if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
                    if ($value['idioma_' . Generales_Idioma::obtener()] == '') {
                        $value['idioma_' . Generales_Idioma::obtener()] = '---';
                    }
                    Armado_DesplegableOcultos::cargaIdComponente($value['cp_id'], $value['idioma_' . Generales_Idioma::obtener()]);
                }
            }
        }

        if (Armado_DesplegableOcultos::mostrarOcultos() === true) {
            $desplegable_ocultos = new Armado_DesplegableOcultos();
            $desplegable_ocultos->armar();
        }

        Generales_PopElementos::asignar_plantilla();

        return $componente_armado;
    }

}
