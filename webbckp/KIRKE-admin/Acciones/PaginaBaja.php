<?php

class Acciones_PaginaBaja {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // creo una matriz con los campos de los componentes de la pagina
        $componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado('todos');

        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado($_GET['id_tabla']);
        $tabla_tipo = $datos_tabla['tipo'];
        
        // borro del los atributos del usuario si tiene oculto algun componente de la tabla
        Armado_DesplegableOcultos::eliminarComponenteOcultoTodos($_GET['id_tabla']);

        // elimino los componentes con sus propias herramientas
        if (is_array($componentes)) {
            foreach ($componentes as $id => $dcp) {

                // llama al componente para eliminarlo
                $llamado_componente = Generales_LlamadoAComponentesYTraduccion::armar('ComponenteBaja', '', '', $dcp, $dcp['cp_nombre'], $dcp['cp_id']);

                // si el objeto anterior devuelve true
                if ($llamado_componente == true) {

                    // elimina la columna si la tabla es tipo 'registro'  o crea el registro para
                    // cargar el valor de la variable
                    if ($tabla_tipo == 'registros') {

                        // elimino el campo de la tabla
                        Consultas_CampoEliminar::armado(__FILE__, __LINE__, $dcp['tb_prefijo'] . '_' . $dcp['tb_nombre'], $dcp['tb_campo']);
                    } elseif ($tabla_tipo == 'variables') {

                        // elimino el campo de la tabla
                        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $dcp['tb_nombre'], 'variables', $dcp['tb_campo']);
                    }
                }

                // condiciones para eliminar los registros que definen al componente
                // elimino el componente de 'kirke_componente'
                Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_componente', 'id_componente', $dcp['cp_id']);

                // elimino el componente de 'kirke_componente_parametro'
                Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_componente_parametro', 'id_componente', $dcp['cp_id']);
            }
        }

        // Consulta nombre de tabla y nombre de campo
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $tabla_nombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];

        // elimino el campo de la tabla
        Consultas_TablaEliminar::armado(__FILE__, __LINE__, $tabla_nombre);

        if (($tabla_tipo == 'menu') || $tabla_tipo == 'tabuladores') {

            Consultas_TablaEliminar::armado(__FILE__, __LINE__, $tabla_nombre . '_trd');
            Consultas_TablaEliminar::armado(__FILE__, __LINE__, $tabla_nombre . '_rel');

            $consulta = new Bases_RegistroEliminar(__FILE__, __LINE__);
            $consulta->tabla('kirke_tabla');
            $consulta->condiciones('', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', '', '', $datos_tabla['id_prefijo']);
            $consulta->condiciones('y', 'kirke_tabla', 'tabla_nombre', 'iguales', '', '', $datos_tabla['nombre'] . '_trd');
            $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'iguales', '', '', $tabla_tipo . '_trd');
            //$consulta->verConsulta();
            $consulta->realizarConsulta();

            $consulta = new Bases_RegistroEliminar(__FILE__, __LINE__);
            $consulta->tabla('kirke_tabla');
            $consulta->condiciones('', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', '', '', $datos_tabla['id_prefijo']);
            $consulta->condiciones('y', 'kirke_tabla', 'tabla_nombre', 'iguales', '', '', $datos_tabla['nombre'] . '_rel');
            $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'iguales', '', '', $tabla_tipo . '_rel');
            //$consulta->verConsulta();
            $consulta->realizarConsulta();

            if ($tabla_tipo == 'tabuladores') {
                
                Consultas_TablaEliminar::armado(__FILE__, __LINE__, $tabla_nombre . '_prd');

                $consulta = new Bases_RegistroEliminar(__FILE__, __LINE__);
                $consulta->tabla('kirke_tabla');
                $consulta->condiciones('', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', '', '', $datos_tabla['id_prefijo']);
                $consulta->condiciones('y', 'kirke_tabla', 'tabla_nombre', 'iguales', '', '', $datos_tabla['nombre'] . '_prd');
                $consulta->condiciones('y', 'kirke_tabla', 'tipo', 'iguales', '', '', $tabla_tipo . '_prd');
                //$consulta->verConsulta();
                $consulta->realizarConsulta();
                
                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas('kirke_tabla_parametro');
                $consulta->campos('kirke_tabla_parametro', 'valor');
                $consulta->condiciones('', 'kirke_tabla_parametro', 'parametro', 'iguales', '', '', 'cp_id');
                $consulta->condiciones('y', 'kirke_tabla_parametro', 'id_tabla', 'iguales', '', '', $_GET['id_tabla']);
                //$consulta->verConsulta();
                $parametros_tabla = $consulta->realizarConsulta();

                // condiciones para eliminar los registros que definen al componente necesario para que se puedan cargar los tabuladores
                // elimino el componente de 'kirke_componente'
                Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_componente', 'id_componente', $parametros_tabla[0]['valor']);

                // elimino el componente de 'kirke_componente_parametro'
                Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_componente_parametro', 'id_componente', $parametros_tabla[0]['valor']);
            }
        }

        // eliminacion de los roles relacionados con la pagina
        Consultas_RollDetalle::RegistroEliminar(__FILE__, __LINE__, $_GET['id_tabla']);

        // condiciones para la eliminacion de los nombres de los links del menu
        $matriz_link_nombre = Consultas_MenuLink::RegistroConsultaIdTabla(__FILE__, __LINE__, $_GET['id_tabla']);

        // elimino los nombres de los links
        if (is_array($matriz_link_nombre)) {
            foreach ($matriz_link_nombre as $id => $value) {
                Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_menu_link_nombre', 'id_menu_link', $value['id_menu_link']);
            }
        }

        // condiciones para la eliminacion
        // elimino los links de la pagina
        Consultas_MenuLink::RegistroEliminar(__FILE__, __LINE__, $_GET['id_tabla']);

        // elimino los nombres de la pagina
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_tabla_nombre_idioma', 'id_tabla', $_GET['id_tabla']);

        // elimino los parametros de la pagina
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_tabla_parametro', 'id_tabla', $_GET['id_tabla']);

        // elimino la pagina
        Consultas_Tabla::RegistroEliminar(__FILE__, __LINE__, $_GET['id_tabla']);
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '30', 'id_tabla' => $_GET['id_tabla']);
        $armado_botonera->armar('redirigir', $parametros);
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|PaginaBaja';
        
        $contenido .= '|id_tabla:' . $_GET['id_tabla'];

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}
