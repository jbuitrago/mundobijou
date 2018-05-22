<?php

class Acciones_ComponenteAltaInsercion {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        $met_valor_param[2]['cp_nombre'] = $_GET['componente'];

        $contenido_cuerpo = Componentes_Componente::componente($_GET['componente'], 'ComponenteInsercion', $met_valor_param);

        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();
        $parametros = array('kk_generar' => '0', 'accion' => '5', 'id_tabla' => $_GET['id_tabla']);
        $armado_botonera->armar('redirigir', $parametros);
    }

    public function crearModificarColumna($tabla, $campo_nombre, $tb_campo_anterior, $tipo_dato, $largo = null, $es_nulo = true, $es_indice = false, $incremental = false) {

        // crear campo en tabla
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, $campo_nombre, $tipo_dato, $largo, $es_nulo, $es_indice, $incremental);
        return true;
    }

    public function consultaRegistro($tabla, $campo_nombre, $tb_campo_anterior = null) {

        $orden = Consultas_ObtenerRegistroMaximo::armado($tabla, 'orden');

        $campos_registro_crear = array(
            'orden' => $orden[0]['orden'] + 1,
            'variables' => $campo_nombre,
            'valores' => '',
        );
        Consultas_RegistroCrear::armado(__FILE__, __LINE__, $tabla, $campos_registro_crear);

        return true;
    }

    public function consultaParametro($id_componente, $parametro, $valor) {

        Consultas_ComponenteParametro::RegistroCrear(__FILE__, __LINE__, $id_componente, $parametro, $valor);

        return true;
    }

    public function crearComponente($id_tabla, $componente, $tb_campo, $orden = '') {

        // verifico que tenga campo, ya que puede ser un simple separador
        if ($tb_campo != '') {
            // controla si la tabla ya existe
            $componente_existente = Consultas_Componente::RegistroConsultaInsercion(__FILE__, __LINE__, $id_tabla, $tb_campo);

            if ($componente_existente != '') {

                // redireccion ya que el componene existe
                $armado_botonera = new Armado_Botonera();

                $parametros = array('kk_generar' => '0', 'accion' => '5', 'id_tabla' => $id_tabla);
                $armado_botonera->armar('redirigir', $parametros);
            }
        }

        // crear componente
        if ($orden == '') {
            $orden = Consultas_ObtenerRegistroMaximo::armado('kirke_componente', 'orden');
            $orden = $orden[0]['orden'] + 1;
        }
        $id_insertado = Consultas_Componente::RegistroCrear(__FILE__, __LINE__, $id_tabla, $orden, $componente, $tb_campo);

        return $id_insertado['id'];
    }

    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|ComponenteAltaInsercion';

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
