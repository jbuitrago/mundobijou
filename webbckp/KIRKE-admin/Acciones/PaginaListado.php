<?php

class Acciones_PaginaListado extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('tabla'));

        // botones de navegacion
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '27');
        $armado_botonera->armar('nuevo', $parametros);

        $parametros = array('kk_generar' => '2', 'tipo' => 'DerDbdesigner4');
        $armado_botonera->armar('exportar_der', $parametros);

        // datos necesarios para armar la tabla:
        $id_columna = 0;
        // columna nombre tabla
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '5';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_id}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'id_tabla';
        $id_columna++;

        // columna nombre tabla
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '10';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_prefijo_tabla}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'prefijo';
        $id_columna++;

        // columna componente
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'link';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '20';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_nombre_tabla_componentes}';
        $tabla_columnas[$id_columna]['id'] = 'id_tabla';
        $tabla_columnas[$id_columna]['tb_campo'] = 'tabla_nombre';
        $tabla_columnas[$id_columna]['accion'] = '5';
        $tabla_columnas[$id_columna]['tabla_ext_nombre'] = '';
        $tabla_columnas[$id_columna]['campo_ext_nombre'] = '';
        $id_columna++;

        // columna registro
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'link';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '20';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_nombre_pagina_registro}';
        $tabla_columnas[$id_columna]['id'] = 'id_tabla';
        $tabla_columnas[$id_columna]['tb_campo'] = 'tabla_nombre_idioma';
        $tabla_columnas[$id_columna]['accion'] = '41';
        $tabla_columnas[$id_columna]['tabla_ext_nombre'] = '';
        $tabla_columnas[$id_columna]['campo_ext_nombre'] = '';
        $tabla_columnas[$id_columna]['valor_sistema'] = '0';   // esta variable la uso para eliminar los FiltrosOrden desde PaginaListado
        $id_columna++;

        // columna tipo
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_tipo}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'tipo';
        $id_columna++;

        // columna tipo
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[$id_columna]['tb_columna_ancho'] = '';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_proceso_especial}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'procesos_especiales';
        $id_columna++;

        // columna editar
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'editar';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_editar}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'id_tabla';
        $tabla_columnas[$id_columna]['accion'] = '31';
        $id_columna++;
        // columna eliminar
        $tabla_columnas[$id_columna]['tb_columna_tipo'] = 'eliminar';
        $tabla_columnas[$id_columna]['tb_titulo_idioma'] = '{TR|o_eliminar}';
        $tabla_columnas[$id_columna]['tb_campo'] = 'id_tabla';
        $tabla_columnas[$id_columna]['accion'] = '29';
        $id_columna++;

        // query para armar la consulta
        $paginas = Consultas_MatrizPaginas::armado();

        $paginas_nvas = array();

        if (is_array($paginas)) {
            $cont_paginas_nvas = 0;
            foreach ($paginas as $pagina_datos) {
                $paginas_nvas[$cont_paginas_nvas]['tabla_nombre_idioma'] = $pagina_datos['tabla_nombre_idioma'];
                $paginas_nvas[$cont_paginas_nvas]['prefijo'] = $pagina_datos['prefijo'];
                $paginas_nvas[$cont_paginas_nvas]['id_tabla'] = $pagina_datos['id_tabla'];
                $paginas_nvas[$cont_paginas_nvas]['orden'] = $pagina_datos['orden'];
                if (($pagina_datos['tipo'] != 'menu') && ($pagina_datos['tipo'] != 'tabuladores')) {
                    $paginas_nvas[$cont_paginas_nvas]['tabla_nombre'] = $pagina_datos['tabla_nombre'];
                } else {
                    $paginas_nvas[$cont_paginas_nvas]['tabla_nombre'] = '';
                }
                $paginas_nvas[$cont_paginas_nvas]['tipo'] = $pagina_datos['tipo'];
                if (file_exists(Inicio::path() . '/ProcesosEspeciales/' . $pagina_datos['prefijo'] . '_' . $pagina_datos['tabla_nombre'] . '.php')) {
                    $paginas_nvas[$cont_paginas_nvas]['procesos_especiales'] = $pagina_datos['prefijo'] . '_' . $pagina_datos['tabla_nombre'];
                }
                $cont_paginas_nvas++;
            }
        }
        
        // armado de la tabla
        $armar_tabla = new Armado_Tabla();
        $armar_tabla->sinDatosPie();
        $tabla_armado = $armar_tabla->armar($tabla_columnas, $paginas_nvas);

        // informes ===================================================

        $directorios = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Informes/');
        asort($directorios);

        if (is_array($directorios)) {

            $listado_informe = '<div class="contenido_margen"></div><div class="contenido_solo_titulo">{TR|o_informes}</div><div class="contenido_separador"></div>';

            foreach ($directorios as $linea) {

                $no_directorio = stripos($linea, '.');

                if ($no_directorio === false) {

                    $direccion = Inicio::path() . '/Informes/' . $linea . '/';

                    if (file_exists($direccion . Generales_Idioma::obtener() . '.php')) {
                        $archivo = Generales_Idioma::obtener() . '.php';
                    } else {
                        $idiomas = Inicio::confVars('idiomas');
                        $archivo = $idiomas[0] . '.php';
                    }

                    $leer_archivo = new Generales_ArchivoVariables();
                    $componente_traduccion = $leer_archivo->archivoLeer($direccion, $archivo, 'PaginaListado');

                    $directorio = $linea;
                    $nombre_informe = ucfirst($componente_traduccion['nombre_informe']);

                    $link = './index.php?' . Generales_VariablesGet::armar(array('kk_generar' => '4', 'informe' => 'EstadisticasDeVisitas', 'pagina' => 'Inicio'));

                    $listado_informe .= '<div class="contenido_margen"></div><div class="contenido_solo_titulo"><a href="' . $link . '" >' . $nombre_informe . '</a></div><div class="contenido_separador"></div>';
                }
            }
        }


        // Desarrollos ===================================================

        $desarrollos = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Desarrollos/');

        if (is_array($desarrollos)) {
            asort($desarrollos);

            $listado_desarrollos = '<div class="contenido_margen"></div><div class="contenido_solo_titulo">{TR|o_desarrollos}</div><div class="contenido_separador"></div>';

            foreach ($desarrollos as $desaroollo_dir) {

                $paginas_desarrollos = Generales_DirectorioContenido::directorioContenido(Inicio::path() . '/Desarrollos/' . $desaroollo_dir . '/_plantillas/');
                $listado_desarrollos .= '<div class="contenido_margen"></div><div class="contenido_solo_titulo">&nbsp;&nbsp;' . mb_strtoupper($desaroollo_dir) . '</div><div class="contenido_separador"></div>';

                if (is_array($paginas_desarrollos)) {
                    asort($paginas_desarrollos);

                    foreach ($paginas_desarrollos as $paginas_desarrollos_dir) {

                        $paginas_desarrollos_dir = substr($paginas_desarrollos_dir, 0, -4);
                        $nombre_informe = ucfirst(strtr($paginas_desarrollos_dir, '_', ' '));
                        $url = 'index.php?' . Generales_VariablesGet::armar(array('kk_generar' => 6, 'kk_desarrollo' => $desaroollo_dir, '0' => $paginas_desarrollos_dir), 's');
                        $listado_desarrollos .= '<div class="contenido_margen"></div><div class="contenido_solo_titulo">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="' . $url . '" >' . $nombre_informe . '</a></div><div class="contenido_separador"></div>';
                    }
                }
            }
        }

        return $tabla_armado . $listado_informe . $listado_desarrollos;
    }

}
