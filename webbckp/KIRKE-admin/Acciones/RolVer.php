<?php

class Acciones_RolVer extends Armado_Plantilla {

    private $_rolDetalle;           // roles del usuario
    private $_accionesEspeciales;   // roles por acciones
    private $_paginas;              // roles por paginas

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '50', 'id_rol' => $_GET['id_rol']);
        $armado_botonera->armar('editar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '49');
        $armado_botonera->armar('volver', $parametros);

        $pagina_alta_cuerpo = $this->_rolDetalle();
        $pagina_alta_cuerpo .= $this->_rolPaginaMostrar();
        $pagina_alta_cuerpo .= $this->_rolInformeMostrar();
        $pagina_alta_cuerpo .= $this->_rolDesarrollosMostrar();

        return $pagina_alta_cuerpo;
    }

    private function _rolDetalle() {

        // rol descripcion
        $rol_descripcion = Consultas_Roll::RegistroConsultaIdRoll(__FILE__, __LINE__, $_GET['id_rol']);

        $plantilla['rol_nombre'] = $rol_descripcion[0]['rol'];
        $plantilla['rol_descripcion'] = $rol_descripcion[0]['descripcion'];

        return Armado_PlantillasInternas::acciones('RolVer_rolDetalle', $plantilla);
    }

    private function _rolPaginaMostrar() {

        // rol descripcion
        $rol_detalle = Consultas_RollDetalle::RegistroConsulta(__FILE__, __LINE__, $_GET['id_rol']);

        $matriz_paginas = new Consultas_MatrizPaginas();
        $paginas = $matriz_paginas->armado();

        if (is_array($paginas)) {

            $acciones = Armado_PlantillasInternas::acciones('RolVer_rolPaginaMostrar');

            foreach ($paginas as $valor) {

                $no_habilitado = '';
                $ver = '';
                $datos = '';
                $insercion = '';

                if (is_array($rol_detalle)) {
                    foreach ($rol_detalle as $rol_valor) {
                        if ($valor['id_tabla'] == $rol_valor['id_elemento']) {
                            if ($rol_valor['permiso'] == 'no_habilitado') {
                                $no_habilitado = "x";
                            } elseif ($rol_valor['permiso'] == 'ver') {
                                $ver = "x";
                            } elseif ($rol_valor['permiso'] == 'datos') {
                                $datos = "x";
                            } elseif ($rol_valor['permiso'] == 'insercion') {
                                $insercion = "x";
                            }
                        }
                    }
                }

                $plantilla['tabla_prefijo'] = $valor['prefijo'];
                $plantilla['tabla_nombre_idioma'] = $valor['tabla_nombre_idioma'];
                $plantilla['tabla_nombre'] = $valor['tabla_nombre'];
                $plantilla['no_habilitado'] = $no_habilitado;
                $plantilla['ver'] = $ver;
                $plantilla['datos'] = $datos;
                $plantilla['insercion'] = $insercion;

                $acciones .= Armado_PlantillasInternas::acciones('RolVer_rolPaginaMostrar_detalle', $plantilla);

                unset($plantilla);
            }
        }

        return $acciones;
    }

    private function _rolInformeMostrar() {

        // rol descripcion
        $rol_detalle = Consultas_RollDetalle::RegistroConsulta(__FILE__, __LINE__, $_GET['id_rol'], 'informe');

        $directorios = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Informes/');

        if (is_array($directorios)) {
            asort($directorios);
            $cont = 0;
            foreach ($directorios as $linea) {

                $no_directorio = stripos($linea, '.');

                if ($no_directorio === false) {

                    $direccion = Inicio::path() . '/Informes/' . $linea . '/';

                    $archivo = 'configuraciones.php';
                    $leer_archivo = new Generales_ArchivoVariables();
                    $componente_configuraciones = $leer_archivo->archivoLeer($direccion, $archivo, 'RolVer TXT 1');

                    $archivo = Generales_Idioma::obtener() . '.php';
                    $leer_archivo = new Generales_ArchivoVariables();
                    $componente_traduccion = $leer_archivo->archivoLeer($direccion, $archivo, 'RolVer TXT 2');

                    $componente_datos[$cont]['nombre_directorio'] = $linea;
                    $componente_datos[$cont]['nombre_informe'] = ucfirst($componente_traduccion['nombre_informe']);
                    $componente_datos[$cont]['id_informe'] = $componente_configuraciones['id_informe'];
                    $componente_datos[$cont]['seguridad'] = $componente_configuraciones['seguridad'];
                    $cont++;
                }
            }
        }

        $informes = '';
        if (is_array($componente_datos)) {

            $informes = Armado_PlantillasInternas::acciones('RolVer_rolInformeMostrar');

            foreach ($componente_datos as $valor) {

                $no_habilitado = '';
                $ver = '';

                if (is_array($rol_detalle)) {
                    foreach ($rol_detalle as $rol_valor) {
                        if ($valor['id_informe'] == $rol_valor['id_elemento']) {
                            if ($rol_valor['permiso'] == 'no_habilitado') {
                                $no_habilitado = 'x';
                            } elseif ($rol_valor['permiso'] == 'ver') {
                                $ver = 'x';
                            }
                        }
                    }
                }

                $plantilla['nombre_informe'] = $valor['nombre_informe'];
                $plantilla['no_habilitado'] = $no_habilitado;
                $plantilla['ver'] = $ver;

                $informes .= Armado_PlantillasInternas::acciones('RolVer_rolInformeMostrar_detalle', $plantilla);

                unset($plantilla);
            }
        }

        return $informes;
    }

    private function _rolDesarrollosMostrar() {

        // rol descripcion
        $rol_detalle = Consultas_RollDetalle::RegistroConsulta(__FILE__, __LINE__, $_GET['id_rol'], 'desarrollo');

        $acciones = '';

        $desarrollos = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Desarrollos/');

        if (is_array($desarrollos)) {

            $cont = 0;
            foreach ($desarrollos as $desaroollo_dir) {

                $paginas_desarrollos = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Desarrollos/' . $desaroollo_dir . '/_plantillas/');
                $titulo = true;

                if (is_array($paginas_desarrollos)) {
                    foreach ($paginas_desarrollos as $paginas_desarrollos_dir) {

                        $paginas_desarrollos_dir = substr($paginas_desarrollos_dir, 0, -4);
                        if ($titulo) {
                            $componente_datos[$cont]['titulo_desarrollo'] = mb_strtoupper(strtr($desaroollo_dir, '_', ' '));
                        }
                        $componente_datos[$cont]['id_desarrollo'] = $desaroollo_dir . ':' . $paginas_desarrollos_dir;
                        $componente_datos[$cont]['nombre_desarrollo'] = ucfirst(strtr($paginas_desarrollos_dir, '_', ' '));

                        $titulo = false;
                        $cont++;
                    }
                }
            }
        }

        $desarrollos = '';
        if (is_array($componente_datos)) {

            $desarrollos = Armado_PlantillasInternas::acciones('RolVer_rolDesarrolloMostrar');

            foreach ($componente_datos as $valor) {

                $no_habilitado = 'x';
                $habilitado = '';

                if (is_array($rol_detalle)) {
                    foreach ($rol_detalle as $rol_valor) {

                        if ($valor['id_desarrollo'] == $rol_valor['id_elemento']) {

                            if ($rol_valor['permiso'] == 'ver') {
                                $no_habilitado = '';
                                $habilitado = 'x';
                            }
                        }
                    }
                }

                if (isset($valor['titulo_desarrollo'])) {
                    $plantilla['nombre_desarrollo'] = $valor['titulo_desarrollo'];
                    $desarrollos .= Armado_PlantillasInternas::acciones('RolVer_rolDesarrolloMostrar_titulos', $plantilla);
                }

                $plantilla['nombre_desarrollo'] = $valor['nombre_desarrollo'];
                $plantilla['no_habilitado'] = $no_habilitado;
                $plantilla['habilitado'] = $habilitado;

                $desarrollos .= Armado_PlantillasInternas::acciones('RolVer_rolDesarrolloMostrar_detalle', $plantilla);

                unset($plantilla);
            }
        }

        return $desarrollos;
    }

}

