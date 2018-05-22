<?php

class Acciones_ComponenteModificacionInsercion {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // creo una matriz con los campos de los componentes de la pagina
        $matriz_componentes = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($_GET['cp_id']);

        // parametros del componente
        $met_valor_param[2] = $matriz_componentes;

        // llamada al componente
        $contenido_cuerpo_componente = Componentes_Componente::componente($matriz_componentes['cp_nombre'], 'ComponenteInsercion', $met_valor_param);

        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }        
        
        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '5', 'id_tabla' => $_GET['id_tabla']);
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _componenteNombre() {

        $matriz = Consultas_Componente::RegistroConsulta(__FILE__, __LINE__, $_GET['cp_id']);
        return $matriz[0]['componente'];
    }

    public function crearModificarColumna($tabla, $campo_nombre, $tb_campo_anterior, $tipo_dato, $largo = null, $es_nulo = true, $es_indice = false, $incremental = false) {

        // crear campo en tabla
        Consultas_CampoModificar::armado(__FILE__, __LINE__, $tabla, $tb_campo_anterior, $campo_nombre, $tipo_dato, $largo, $es_nulo, $es_indice, $incremental);
        return true;
    }

    public function consultaRegistro($tabla, $campo, $tb_campo_anterior) {

        $valores = $campo;
        Consultas_RegistroModificar::armado(__FILE__, __LINE__, $tabla, array('variables' => $valores), 'variables', $tb_campo_anterior);
    }

    public function consultaParametro($id_componente, $parametro, $valor) {

        // verifico si existe el parametro del componente
        $consulta = Consultas_ComponenteParametro::RegistroConsulta(__FILE__, __LINE__, $id_componente, $parametro);

        if (isset($consulta[0]['id_componente_parametro'])) {

            // modifico el parametro del componente
            $consulta = Consultas_ComponenteParametro::RegistroModificar(__FILE__, __LINE__, $id_componente, $parametro, $valor);
        } else {

            // agregar los registros de los parametros del componente
            Consultas_ComponenteParametro::RegistroCrear(__FILE__, __LINE__, $id_componente, $parametro, $valor);
        }

        return true;
    }

    public function consultaParametroEliminar($id_componente, $parametro) {

        // verifico si existe el parametro del componente
        $consulta = Consultas_ComponenteParametro::RegistroConsulta(__FILE__, __LINE__, $id_componente, $parametro);

        if (isset($consulta[0]['id_componente_parametro'])) {

            // elimino los parametros del componente
            Consultas_ComponenteParametro::RegistroEliminar(__FILE__, __LINE__, $id_componente, $parametro);
        }

        return true;
    }

    public function tbCampoAnterior() {

        $matriz = Consultas_Componente::RegistroConsulta(__FILE__, __LINE__, $_GET['cp_id']);
        return $matriz[0]['tabla_campo'];
    }

    public function modificarTablaCampo($valor) {

        // controla si la tabla ya existe
        $componente_existente = Consultas_Componente::RegistroConsultaInsercion(__FILE__, __LINE__, $_GET['id_tabla'], $valor, $_GET['cp_id']);

        if ($componente_existente != '') {

            // redireccion ya que el componene existe
            $armado_botonera = new Armado_Botonera();

            $parametros = array('kk_generar' => '0', 'accion' => '5', 'id_tabla' => $_GET['id_tabla']);
            $armado_botonera->armar('redirigir', $parametros);
        }

        Consultas_Componente::RegistroModificar(__FILE__, __LINE__, $_GET['cp_id'], $valor);
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|ComponenteModificacionInsercion';

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
