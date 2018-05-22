<?php

class Acciones_RolAlta extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('formulario'));

        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '47');
        $armado_botonera->armar('guardar', $parametros);

        $parametros = array('kk_generar' => '0', 'accion' => '49');
        $armado_botonera->armar('volver', $parametros);

        $pagina_alta_cuerpo = Armado_PlantillasInternas::acciones('RolAlta_rolDetalle');
        $pagina_alta_cuerpo .= $this->rolPaginas();
        $pagina_alta_cuerpo .= $this->rolInformes();
        $pagina_alta_cuerpo .= $this->rolDesarrollos();

        return $pagina_alta_cuerpo;
    }

    public function rolPaginas() {

        $matriz_paginas = new Consultas_MatrizPaginas();
        $paginas = $matriz_paginas->armado();

        $acciones = Armado_PlantillasInternas::acciones('RolAlta_rolPaginas');

        if (is_array($paginas)) {
            foreach ($paginas as $valor) {

                $plantilla['tabla_prefijo'] = $valor['prefijo'];
                $plantilla['tabla_nombre_idioma'] = $valor['tabla_nombre_idioma'];
                $plantilla['tabla_nombre'] = $valor['tabla_nombre'];
                $plantilla['id_tabla'] = $valor['id_tabla'];
                $acciones .= Armado_PlantillasInternas::acciones('RolAlta_rolPaginas_detalle', $plantilla);

                unset($plantilla);
            }
        }

        return $acciones;
    }

    public function rolInformes() {

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
                    $componente_configuraciones = $leer_archivo->archivoLeer($direccion, $archivo, 'RolAlta TXT 1');

                    $archivo = Generales_Idioma::obtener() . '.php';
                    $leer_archivo = new Generales_ArchivoVariables();
                    $componente_traduccion = $leer_archivo->archivoLeer($direccion, $archivo, 'RolAlta TXT 2');

                    $componente_datos[$cont]['nombre_directorio'] = $linea;
                    $componente_datos[$cont]['nombre_informe'] = ucfirst($componente_traduccion['nombre_informe']);
                    $componente_datos[$cont]['id_informe'] = $componente_configuraciones['id_informe'];
                    $componente_datos[$cont]['seguridad'] = $componente_configuraciones['seguridad'];
                    $cont++;
                }
            }
        }
        
        $acciones = '';

        if (isset($componente_datos) && is_array($componente_datos)) {

            $acciones = Armado_PlantillasInternas::acciones('RolAlta_rolInformes');

            foreach ($componente_datos as $valor) {

                $plantilla['nombre_informe'] = $valor['nombre_informe'];
                $plantilla['id_informe'] = $valor['id_informe'];
                $acciones .= Armado_PlantillasInternas::acciones('RolAlta_rolInformes_detalle', $plantilla);
            }
        }

        return $acciones;
    }

    public function rolDesarrollos() {

        $acciones = Armado_PlantillasInternas::acciones('RolAlta_rolDesarrollos');

        $desarrollos = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Desarrollos/');

        if (is_array($desarrollos)) {
            foreach ($desarrollos as $desarrollo_dir) {

                $paginas_desarrollos = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Desarrollos/' . $desarrollo_dir . '/_plantillas/');

                $plantilla['nombre_desarrollo'] = mb_strtoupper($desarrollo_dir);
                $acciones .= Armado_PlantillasInternas::acciones('RolAlta_rolDesarrollos_titulos', $plantilla);

                if (is_array($paginas_desarrollos)) {
                    foreach ($paginas_desarrollos as $paginas_desarrollos_dir) {

                        $paginas_desarrollos_dir = substr($paginas_desarrollos_dir, 0, -4);

                        $plantilla['id_desarrollo'] = $desarrollo_dir . ':' . $paginas_desarrollos_dir;

                        $paginas_desarrollos_dir = ucfirst(strtr($paginas_desarrollos_dir, '_', ' '));
                        $plantilla['nombre_desarrollo'] = $paginas_desarrollos_dir;

                        $acciones .= Armado_PlantillasInternas::acciones('RolAlta_rolDesarrollos_detalle', $plantilla);
                    }
                }
            }
        }

        return $acciones;
    }

}

