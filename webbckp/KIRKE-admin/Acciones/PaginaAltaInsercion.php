<?php

class Acciones_PaginaAltaInsercion {

    private $tabla_nombre;
    private $tabla_prefijo;
    private $id_menu;
    private $tabla_prefijo_texto;
    private $tabla_tipo;
    private $habilitada;
    private $proceso_especial;
    private $idioma = array();
    private $_idiomaTexto = array();
    private $_id_tabla_crear;
    private $_id_insertado_rel = array();
    private $_id_insertado_trd = array();
    private $_id_componente;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        // obtiencion de etiquetas de idiomas
        if (is_array(Inicio::confVars('idiomas'))) {
            $contador = 0;
            foreach (Inicio::confVars('idiomas') as $value) {
                $this->idioma[$contador] = $value;
                $this->_idiomaTexto[$contador] = $_POST['etiqueta_' . $value];
                $contador++;
            }
        }

        $this->tabla_nombre = Generales_LimpiarTextos::alfanumericoGuiones($_POST['tabla_nombre']);
        $this->tabla_prefijo = $_POST['tabla_prefijo'];
        $this->tabla_tipo = $_POST['tipo'];
        $this->habilitada = $_POST['habilitada'];
        $this->proceso_especial = $_POST['proceso_especial'];

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        // controla si la tabla ya existe
        $tabla_existente = Consultas_Tabla::RegistroConsulta(__FILE__, __LINE__, $this->tabla_nombre, $this->tabla_prefijo);
        if ($tabla_existente != '') {
            $parametros = array('kk_generar' => '0', 'accion' => '27');
            $armado_botonera->armar('redirigir', $parametros);
        }

        $this->_insertarConfiguracion();
        $this->_agregarTabla();
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        if ($this->tabla_tipo == 'menu') {
            $parametros = array('kk_generar' => '0', 'accion' => '65', 'id_tabla' => $this->_id_tabla_crear, 'intermedia_tb_id' => $this->_id_insertado_rel['id'], 'id_tabla_trd' => $this->_id_insertado_trd['id']);
        } elseif ($this->tabla_tipo == 'tabuladores') {
            $parametros = array('kk_generar' => '0', 'accion' => '68', 'id_tabla' => $this->_id_tabla_crear, 'intermedia_tb_id' => $this->_id_insertado_rel['id'], 'id_tabla_trd' => $this->_id_insertado_trd['id']);
        } else {
            $parametros = array('kk_generar' => '0', 'accion' => '5', 'id_tabla' => $this->_id_tabla_crear);
        }

        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _insertarConfiguracion() {

        $orden = Consultas_ObtenerRegistroMaximo::armado('kirke_tabla', 'orden');

        // crear pagina
        $id_insertado = Consultas_Tabla::RegistroCrear(__FILE__, __LINE__, $this->tabla_prefijo, $orden[0]['orden'] + 1, $this->tabla_nombre, $this->habilitada, $this->tabla_tipo, $this->proceso_especial);
        if (($this->tabla_tipo == 'menu') || ($this->tabla_tipo == 'tabuladores')) {
            $this->_id_insertado_rel = Consultas_Tabla::RegistroCrear(__FILE__, __LINE__, $this->tabla_prefijo, $orden[0]['orden'] + 2, $this->tabla_nombre . '_rel', $this->habilitada, $this->tabla_tipo . '_rel');
            $this->_id_insertado_trd = Consultas_Tabla::RegistroCrear(__FILE__, __LINE__, $this->tabla_prefijo, $orden[0]['orden'] + 3, $this->tabla_nombre . '_trd', $this->habilitada, $this->tabla_tipo . '_trd');
        }
        if ($this->tabla_tipo == 'tabuladores') {
            $this->_id_insertado_trd = Consultas_Tabla::RegistroCrear(__FILE__, __LINE__, $this->tabla_prefijo, $orden[0]['orden'] + 4, $this->tabla_nombre . '_prd', $this->habilitada, $this->tabla_tipo . '_prd');
        }

        $this->_id_tabla_crear = $id_insertado['id'];

        if (is_array($this->idioma)) {
            // carga las etiquetas de la página
            foreach ($this->idioma as $key => $value) {

                // crear parametro idiomas
                Consultas_TablaNombreIdioma::RegistroCrear(__FILE__, __LINE__, $this->_id_tabla_crear, $this->idioma[$key], $this->_idiomaTexto[$key]);
            }
        }
        return true;
    }

    private function _agregarTabla() {

        // obtener el prefijo de la tabla en texto
        $matriz = Consultas_TablaPrefijo::RegistroConsulta(__FILE__, __LINE__, $this->tabla_prefijo);
        $this->tabla_prefijo_texto = $matriz[0]['prefijo'];

        // crear tabla para cargar registros
        Consultas_TablaCrear::armado(__FILE__, __LINE__, $this->tabla_nombre, $this->tabla_prefijo_texto);

        if ($this->tabla_tipo == 'variables') {
            $this->_agregarColumnasTablaVariables();
        } elseif ($this->tabla_tipo == 'menu') {
            $this->_agregarColumnasTablaMenu();
        } elseif ($this->tabla_tipo == 'tabuladores') {
            $this->_agregarColumnasTablaTabuladores();
        }
        return true;
    }

    private function _agregarColumnasTablaVariables() {

        $tabla = $this->tabla_prefijo_texto . '_' . $this->tabla_nombre;

        // creo la columna de los nombre de las variables
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'variables', 'texto', '100', false);

        // creo la columna de los valores de las variables
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'valores', 'texto_largo', '100', false);
    }

    private function _agregarColumnasTablaMenu() {

        $tabla = $this->tabla_prefijo_texto . '_' . $this->tabla_nombre;

        // creo las columnas de niveles
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'nivel1_orden', 'numero', '6', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'nivel2_orden', 'numero', '6');
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'nivel3_orden', 'numero', '6');
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'nivel4_orden', 'numero', '6');
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'nivel5_orden', 'numero', '6');
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'nivel6_orden', 'numero', '6');
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'nivel7_orden', 'numero', '6');
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'nivel8_orden', 'numero', '6');
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'nivel9_orden', 'numero', '6');
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'nivel10_orden', 'numero', '6');
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla, 'habilitado', 'texto', '1', false);

        // tabla de relaciones entre la tabla de menu y los registros
        Consultas_TablaCrear::armado(__FILE__, __LINE__, $this->tabla_nombre . '_rel', $this->tabla_prefijo_texto);
        $tabla_rel = $this->tabla_prefijo_texto . '_' . $this->tabla_nombre . '_rel';
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_rel, 'id_' . $tabla, 'numero', '8', false);

        // tabla de traduccion de los menus
        Consultas_TablaCrear::armado(__FILE__, __LINE__, $this->tabla_nombre . '_trd', $this->tabla_prefijo_texto);
        $tabla_trd = $this->tabla_prefijo_texto . '_' . $this->tabla_nombre . '_trd';
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_trd, 'id_' . $tabla, 'numero', '8', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_trd, 'idioma', 'texto', '2', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_trd, 'menu_nombre', 'texto', '200', false);
    }

    private function _agregarColumnasTablaTabuladores() {

        $tabla = $this->tabla_prefijo_texto . '_' . $this->tabla_nombre;

        // tabla de relaciones entre la tabla de tabuladores y los registros
        Consultas_TablaCrear::armado(__FILE__, __LINE__, $this->tabla_nombre . '_rel', $this->tabla_prefijo_texto);
        $tabla_rel = $this->tabla_prefijo_texto . '_' . $this->tabla_nombre . '_rel';
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_rel, 'id_' . $tabla, 'numero', '12', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_rel, 'idioma', 'texto', '2', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_rel, 'tabulador', 'texto', '200', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_rel, 'id_tab_prd', 'numero', '12', false);

        // tabla de traduccion de los tabuladores
        Consultas_TablaCrear::armado(__FILE__, __LINE__, $this->tabla_nombre . '_trd', $this->tabla_prefijo_texto);
        $tabla_trd = $this->tabla_prefijo_texto . '_' . $this->tabla_nombre . '_trd';
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_trd, 'id_' . $tabla, 'numero', '12', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_trd, 'idioma', 'texto', '2', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_trd, 'tabulador_nombre', 'texto', '200', false);

        // tabla de valores predefinidos de los tabuladores
        Consultas_TablaCrear::armado(__FILE__, __LINE__, $this->tabla_nombre . '_prd', $this->tabla_prefijo_texto);
        $tabla_trd = $this->tabla_prefijo_texto . '_' . $this->tabla_nombre . '_prd';
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_trd, 'id_' . $tabla, 'numero', '12', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_trd, 'idioma', 'texto', '5', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_trd, 'valor', 'texto', '200', false);
        Consultas_CampoCrear::armado(__FILE__, __LINE__, $tabla_trd, 'id_tab_prd', 'numero', '12', false);
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|PaginaAltaInsercion';
        
        $contenido .= '|tabla_nombre:' . $this->tabla_nombre;
        $contenido .= '|tabla_prefijo:' . $this->tabla_prefijo;
        $contenido .= '|tipo:' . $this->tabla_tipo;
        $contenido .= '|habilitada:' . $this->habilitada;
        //$contenido .= '|proceso_especial:' . $this->proceso_especial;

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}
