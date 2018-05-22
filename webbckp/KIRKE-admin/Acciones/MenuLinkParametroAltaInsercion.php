<?php

class Acciones_MenuLinkParametroAltaInsercion extends Armado_Plantilla {

    public function armado() {

        //print_r($_POST);
        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        $this->_insertarParametros();

        Armado_Menu::reinciarMenu();
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }
        
        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '21', 'id_menu' => $_GET['id_menu']);
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _insertarParametros() {

        $id_tabla = Consultas_MenuLink::RegistroConsultaIdMenuLink(__FILE__, __LINE__, $_GET['id_menu_link']);
        $id_tabla = $id_tabla[0]['id_elemento'];

        // elimino los parametro existentes para
        // volver a cargarlos en el paso siguiente
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_menu_link_parametro', 'id_menu_link', $_GET['id_menu_link']);

        // inserto los ordenes de la pagina
        for ($i = 1; $i <= 3; $i++) {
            $insertar_orden = false;
            if (isset($_POST['orden_campo_' . $i]) && ($_POST['orden_campo_' . $i] == 'orden')) {
                $val_filtro_val = 'orden';
                $val_filtro_id = '';
                $insertar_orden = true;
            } elseif (isset($_POST['orden_campo_' . $i]) && ($_POST['orden_campo_' . $i] != '') && (explode('|', $_POST['orden_campo_' . $i]))) {
                $val_filtro_valores = explode('|', $_POST['orden_campo_' . $i]);
                $val_filtro_val = $val_filtro_valores[1];
                $val_filtro_id = $val_filtro_valores[0];
                $insertar_orden = true;
            }
            if ($insertar_orden === true) {
                Consultas_MenuLinkParametro::RegistroCrear(__FILE__, __LINE__, $_GET['id_menu_link'], 'orden', $val_filtro_id, $_POST['kk_orden' . $i], $val_filtro_val);
            }
        }

        // inserto si se muestra filtro general o no
        Consultas_MenuLinkParametro::RegistroCrear(__FILE__, __LINE__, $_GET['id_menu_link'], 'filtro_general', $id_tabla, $_POST['kk_filtro_general'], '');

        // inserto si se muestra ocultar campos o no
        Consultas_MenuLinkParametro::RegistroCrear(__FILE__, __LINE__, $_GET['id_menu_link'], 'ocultar_campos', $id_tabla, $_POST['kk_ocultar_campos'], '');

        foreach ($_POST as $id_post => $valor_post) {
            $identificador = explode("_", $id_post);
            if ($identificador[0] == 'parametro') {
                $pre_filtros[$identificador[1]]['parametro'] = $valor_post;
                $pre_filtros[$identificador[1]]['id'] = $identificador[1];
            } elseif ($identificador[0] == 'valor') {
                $pre_filtros[$identificador[1]]['id'] = $identificador[1];
                if (!isset($identificador[2])) {
                    $pre_filtros[$identificador[1]]['valor'] = $valor_post;
                } elseif (isset($identificador[2]) && ($identificador[2] == '2') && (($_POST['parametro_' . $identificador[1]] == 'rango') || ($_POST['parametro_' . $identificador[1]] == 'fecha_rango'))) {
                    $pre_filtros[$identificador[1]]['valor'] .= ';' . $valor_post;
                }
            }
        }

        $id_carga = 0;
        if (isset($pre_filtros)) {
            foreach ($pre_filtros as $valor) {
                if (!isset($valor['valor'])) {
                    $valor['valor'] = '';
                }

                $val_filtro = Generales_FiltrosOrden::ValidacionFiltro($valor['parametro'], $valor['valor'], $valor['id'], $id_carga);
                if ($val_filtro !== false) {
                    foreach ($val_filtro['filtros'] as $key => $val_filtro_valor) {
                        if (!is_array($val_filtro_valor['valor'])) {
                            $valor = $val_filtro_valor['valor'];
                        } else {
                            $valor = implode(';', $val_filtro_valor['valor']);
                        }
                        Consultas_MenuLinkParametro::RegistroCrear(__FILE__, __LINE__, $_GET['id_menu_link'], 'filtro', $val_filtro_valor['id'], $val_filtro_valor['parametro'], $valor);
                    }
                    $id_carga = $val_filtro['id_carga'];
                    $id_carga++;
                }
            }
        }

        if (!isset($_GET['alta'])) {
            if (is_array(Inicio::confVars('idiomas'))) {
                $contador = 0;
                foreach (Inicio::confVars('idiomas') as $key => $value) {
                    $idioma[$contador] = $value;
                    $idioma_texto[$contador] = $_POST['etiqueta_' . $value];
                    $contador++;
                }
            }

            // carga las etiquetas del link
            if (isset($idioma) && is_array($idioma)) {
                foreach ($idioma as $key => $value) {

                    // crear parametro idiomas
                    Consultas_MenuLinkNombre::RegistroModificar(__FILE__, __LINE__, $_GET['id_menu_link'], $idioma[$key], $idioma_texto[$key]);
                }
            }
        }
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|MenuLinkParametroAltaInsercion';

        $contenido .= '|get:';
        $coma = '';
        foreach ($_GET as $id => $valor) {
            if (is_array($valor)) {
                $valor = implode(';' . $valor);
            }
            $contenido .= $coma . '[' . $id . ':' . $valor . ']';
            $coma = ',';
        }

        $contenido .= '|post:';
        $coma = '';
        foreach ($_POST as $id => $valor) {
            if (is_array($valor)) {
                $valor = implode(';' . $valor);
            }
            $contenido .= $coma . '[' . $id . ':' . $valor . ']';
            $coma = ',';
        }

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}
