<?php

class Acciones_RegistroTabuladoresInsercion {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('pagina', $_GET['id_tabla'], 'datos');

        if (is_array(Inicio::confVars('idiomas'))) {
            $contador = 0;
            foreach (Inicio::confVars('idiomas') as $key => $value) {
                if (trim($_POST['etiqueta_' . $value]) != '') {
                    $idioma[$contador] = $value;
                    $idioma_texto[$contador] = $_POST['etiqueta_' . $value];
                    $contador++;
                }
                if (isset($_POST['valores_pred_' . $value]) && ($_POST['valores_pred_' . $value] != '')) {
                    $valores_post[$value] = $_POST['valores_pred_' . $value];
                }
            }
            if (count(Inicio::confVars('idiomas')) > 1) {
                $valores_post['multi'] = $_POST['valores_pred_multi'];
            }
        }

        $tabla = Consultas_TablaNombreIdioma::RegistroConsultaPrefijo(__FILE__, __LINE__, $_GET['id_tabla']);
        $tabla_nombre = $tabla[0]['prefijo'] . '_' . $tabla[0]['tabla_nombre'];

        $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
        $consulta->tablas('kirke_tabla_parametro');
        $consulta->campos('kirke_tabla_parametro', 'parametro');
        $consulta->campos('kirke_tabla_parametro', 'valor');
        $consulta->condiciones('', 'kirke_tabla_parametro', 'id_tabla', 'iguales', '', '', $_GET['id_tabla']);
        //$consulta->verConsulta();
        $matriz = $consulta->realizarConsulta();

        if (is_array($matriz)) {
            foreach ($matriz as $valor) {
                $parametros[$valor['parametro']] = $valor['valor'];
            }
        }

        // si debería venir una familia y no llega
        if (isset($parametros['id_cp_rel']) && ($_GET['id_registro'] == '')) {
            $armado_botonera = new Armado_Botonera();
            $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'valor_sistema' => 0);
            $armado_botonera->armar('redirigir', $parametros);
        }

        if (isset($parametros['id_cp_rel'])) {
            $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
            $consulta->tablas('kirke_componente_parametro');
            $consulta->tablas('kirke_componente');
            $consulta->tablas('kirke_tabla');
            $consulta->tablas('kirke_tabla_prefijo');
            $consulta->campos('kirke_componente_parametro', 'valor');
            $consulta->campos('kirke_componente', 'tabla_campo');
            $consulta->campos('kirke_tabla', 'tabla_nombre');
            $consulta->campos('kirke_tabla_prefijo', 'prefijo');
            $consulta->condiciones('', 'kirke_componente', 'id_componente', 'iguales', 'kirke_componente_parametro', 'id_componente');
            $consulta->condiciones('y', 'kirke_componente', 'id_tabla', 'iguales', 'kirke_tabla', 'id_tabla');
            $consulta->condiciones('y', 'kirke_tabla', 'id_tabla_prefijo', 'iguales', 'kirke_tabla_prefijo', 'id_tabla_prefijo');
            $consulta->condiciones('y', 'kirke_componente_parametro', 'id_componente', 'iguales', '', '', $parametros['id_cp_rel']);
            //$consulta->verConsulta();
            $familias_tab_parametros = $consulta->realizarConsulta();

            $tabla_familia_nombre = $familias_tab_parametros[0]['prefijo'] . '_' . $familias_tab_parametros[0]['tabla_nombre'];
        }

        if ($_GET['id_tabulador'] == '') {
            // crear tabuladores
            $orden = Consultas_ObtenerRegistroMaximo::armado($tabla_nombre, 'orden');
            $consulta = new Bases_RegistroCrear(__FILE__, __LINE__);
            $consulta->tabla($tabla_nombre);
            $consulta->campoValor($tabla_nombre, 'orden', ($orden[0]['orden'] + 1));
            if (isset($parametros['id_cp_rel'])) {
                $consulta->campoValor($tabla_nombre, 'id_' . $tabla_familia_nombre, $_GET['id_registro']);
            }
            //$consulta->verConsulta();
            $id_insertado = $consulta->realizarConsulta();
        } else {
            $id_insertado = $_GET['id_tabulador'];
        }

        if (is_array($idioma)) {
            foreach ($idioma as $key => $value) {

                $orden = Consultas_ObtenerRegistroMaximo::armado($tabla_nombre . '_trd', 'orden');

                if ($_GET['id_tabulador'] == '') {

                    // crear tabuladores idiomas
                    $consulta = new Bases_RegistroCrear(__FILE__, __LINE__);
                    $consulta->tabla($tabla_nombre . '_trd');
                    $consulta->campoValor($tabla_nombre . '_trd', 'orden', ($orden[0]['orden'] + 1));
                    $consulta->campoValor($tabla_nombre . '_trd', 'id_' . $tabla_nombre, $id_insertado['id']);
                    $consulta->campoValor($tabla_nombre . '_trd', 'idioma', $idioma[$key]);
                    $consulta->campoValor($tabla_nombre . '_trd', 'tabulador_nombre', $idioma_texto[$key]);
                    //$consulta->verConsulta();
                    $consulta->realizarConsulta();
                } else {

                    // modificar tabuladores idiomas
                    $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
                    $consulta->tabla($tabla_nombre . '_trd');
                    $consulta->campoValor($tabla_nombre . '_trd', 'tabulador_nombre', $idioma_texto[$key]);
                    $consulta->condiciones('', $tabla_nombre . '_trd', 'id_' . $tabla_nombre, 'iguales', '', '', $_GET['id_tabulador']);
                    $consulta->condiciones('y', $tabla_nombre . '_trd', 'idioma', 'iguales', '', '', $idioma[$key]);
                    //$consulta->verConsulta();
                    $consulta->realizarConsulta();
                }
            }
        }

        if (isset($valores_post) && is_array($valores_post)) {

            $orden_consulta = Consultas_ObtenerRegistroMaximo::armado($tabla_nombre . '_prd', 'orden');
            $orden = $orden_consulta[0]['orden'] + 1;

            if ($_GET['id_tabulador'] == '') {
                foreach ($valores_post as $idioma_post => $idioma_valores) {
                    foreach ($idioma_valores as $id_valor => $valor_pred) {
                        // crear tabuladores valores predefinidos idiomas
                        $consulta = new Bases_RegistroCrear(__FILE__, __LINE__);
                        $consulta->tabla($tabla_nombre . '_prd');
                        $consulta->campoValor($tabla_nombre . '_prd', 'orden', $orden);
                        $consulta->campoValor($tabla_nombre . '_prd', 'id_' . $tabla_nombre, $id_insertado['id']);
                        $consulta->campoValor($tabla_nombre . '_prd', 'idioma', $idioma_post);
                        $consulta->campoValor($tabla_nombre . '_prd', 'valor', $valor_pred);
                        $consulta->campoValor($tabla_nombre . '_prd', 'id_tab_prd', ($id_valor + 1));
                        //$consulta->verConsulta();
                        $consulta->realizarConsulta();
                        $orden++;
                    }
                }
            } else {

                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas($tabla_nombre . '_prd');
                $consulta->campos($tabla_nombre . '_prd', 'idioma');
                $consulta->campos($tabla_nombre . '_prd', 'valor');
                $consulta->campos($tabla_nombre . '_prd', 'id_tab_prd');
                $consulta->condiciones('', $tabla_nombre . '_prd', 'id_' . $tabla_nombre, 'iguales', '', '', $_GET['id_tabulador']);
                $consulta->orden($tabla_nombre . '_prd', 'orden');
                //$consulta->verConsulta();
                $tabulador_valores_consulta = $consulta->realizarConsulta();

                if (is_array($tabulador_valores_consulta)) {
                    foreach ($tabulador_valores_consulta as $tvc) {
                        if (isset($valores_post[$tvc['idioma']][($tvc['id_tab_prd'] - 1)]) && ($valores_post[$tvc['idioma']][($tvc['id_tab_prd'] - 1)] == $tvc['id_tab_prd'])) {
                            unset($valores_post[$tvc['idioma']][($tvc['id_tab_prd'] - 1)]);
                        } elseif (isset($valores_post[$tvc['idioma']][($tvc['id_tab_prd'] - 1)]) && ($valores_post[$tvc['idioma']][($tvc['id_tab_prd'] - 1)] != $tvc['id_tab_prd'])) {
                            $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
                            $consulta->tabla($tabla_nombre . '_prd');
                            $consulta->campoValor($tabla_nombre . '_prd', 'valor', $valores_post[$tvc['idioma']][($tvc['id_tab_prd'] - 1)]);
                            $consulta->condiciones('', $tabla_nombre . '_prd', 'id_' . $tabla_nombre, 'iguales', '', '', $_GET['id_tabulador']);
                            $consulta->condiciones('y', $tabla_nombre . '_prd', 'id_tab_prd', 'iguales', '', '', $tvc['id_tab_prd']);
                            $consulta->condiciones('y', $tabla_nombre . '_prd', 'idioma', 'iguales', '', '', $tvc['idioma']);
                            //$consulta->verConsulta();
                            $consulta->realizarConsulta();
                            unset($valores_post[$tvc['idioma']][($tvc['id_tab_prd'] - 1)]);
                        } elseif (!isset($valores_post[$tvc['idioma']][($tvc['id_tab_prd'] - 1)])) {
                            $consulta = new Bases_RegistroEliminar(__FILE__, __LINE__);
                            $consulta->tabla($tabla_nombre . '_prd');
                            $consulta->condiciones('', $tabla_nombre . '_prd', 'id_' . $tabla_nombre, 'iguales', '', '', $_GET['id_tabulador']);
                            $consulta->condiciones('y', $tabla_nombre . '_prd', 'id_tab_prd', 'iguales', '', '', $tvc['id_tab_prd']);
                            $consulta->condiciones('y', $tabla_nombre . '_prd', 'idioma', 'iguales', '', '', $tvc['idioma']);
                            //$consulta->verConsulta();
                            $consulta->realizarConsulta();
                        }
                    }
                }
                foreach ($valores_post as $idioma_post => $idioma_valores) {
                    foreach ($idioma_valores as $id_valor => $valor_pred) {
                        // crear tabuladores valores predefinidos idiomas
                        $consulta = new Bases_RegistroCrear(__FILE__, __LINE__);
                        $consulta->tabla($tabla_nombre . '_prd');
                        $consulta->campoValor($tabla_nombre . '_prd', 'orden', $orden);
                        $consulta->campoValor($tabla_nombre . '_prd', 'id_' . $tabla_nombre, $_GET['id_tabulador']);
                        $consulta->campoValor($tabla_nombre . '_prd', 'idioma', $idioma_post);
                        $consulta->campoValor($tabla_nombre . '_prd', 'valor', $valor_pred);
                        $consulta->campoValor($tabla_nombre . '_prd', 'id_tab_prd', ($id_valor + 1));
                        //$consulta->verConsulta();
                        $consulta->realizarConsulta();
                        $orden++;
                    }
                }
            }
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'valor_sistema' => 0, 'id_registro' => $_GET['id_registro']);
        $armado_botonera->armar('redirigir', $parametros);
    }

}
