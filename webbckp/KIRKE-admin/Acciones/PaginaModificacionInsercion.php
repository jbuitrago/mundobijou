<?php

class Acciones_PaginaModificacionInsercion {

    private $_tablaNombreNvo;
    private $_tablaPrefijoNvo;
    private $_tablaPrefijoTextoNvo;
    private $_tablaNombre;
    private $_tablaPrefijoTexto;
    private $_tabla_tipo;
    private $_habilitada;
    private $proceso_especial;
    private $_idioma = array();
    private $_idiomaTexto = array();

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        if (is_array(Inicio::confVars('idiomas'))) {
            $contador = 0;
            foreach (Inicio::confVars('idiomas') as $key => $value) {
                $this->_idioma[$contador] = $value;
                $this->_idiomaTexto[$contador] = $_POST['etiqueta_' . $value];
                $contador++;
            }
        }

        $this->_tablaNombreNvo = Generales_LimpiarTextos::alfanumericoGuiones($_POST['tabla_nombre']);
        $this->_tablaPrefijoNvo = $_POST['tabla_prefijo'];

        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        $this->_tablaNombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];

        $this->_habilitada = $_POST['habilitada'];

        // obtener el prefijo de la tabla en texto
        $matriz = Consultas_TablaPrefijo::RegistroConsulta(__FILE__, __LINE__, $this->_tablaPrefijoNvo);
        $this->_tablaPrefijoTextoNvo = $matriz[0]['prefijo'];

        $matriz = Consultas_TablaNombreIdioma::RegistroConsultaPrefijo(__FILE__, __LINE__, $_GET['id_tabla']);
        $this->_tabla_tipo = $matriz[0]['tipo'];
        $this->_tablaPrefijoTexto = $matriz[0]['prefijo'];

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $this->_insertarConfiguracion();
        $this->_modificarTabla();

        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }
        
        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        if ($this->_tabla_tipo == 'menu') {
            $parametros = array('kk_generar' => '0', 'accion' => '65', 'id_tabla' => $_GET['id_tabla']);
        } else {
            $parametros = array('kk_generar' => '0', 'accion' => '30');
        }

        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _insertarConfiguracion() {

        // modifica nombre de la pagina
        Consultas_Tabla::RegistroModificar(__FILE__, __LINE__, $this->_tablaPrefijoNvo, $this->_tablaNombreNvo, $this->_habilitada, $_GET['id_tabla'], $this->proceso_especial);

        // modifica las etiquetas de la página
        foreach ($this->_idioma as $key => $value) {

            // modificar parametro idiomas
            Consultas_TablaNombreIdioma::RegistroModificar(__FILE__, __LINE__, $this->_idiomaTexto[$key], $_GET['id_tabla'], $this->_idioma[$key]);
        }

        $id_componente = Consultas_TablaIdModificar::VerSiExiste(__FILE__, __LINE__, 'id_' . $this->_tablaNombre);

        if ($id_componente !== false) {

            Consultas_TablaIdModificar::RegistroModificar(__FILE__, __LINE__, $id_componente, 'id_' . $this->_tablaPrefijoTextoNvo . '_' . $this->_tablaNombreNvo);
        }

        return true;
    }

    private function _modificarTabla() {

        // modifica nombre de la pagina
        Consultas_CampoModificar::armado(__FILE__, __LINE__, $this->_tablaNombre, 'id_' . $this->_tablaNombre, 'id_' . $this->_tablaPrefijoTextoNvo . '_' . $this->_tablaNombreNvo, 'numero', '12', false);
        
        $tabla = $this->_tablaNombre;
        $tabla2 = $this->_tablaPrefijoTextoNvo . '_' . $this->_tablaNombreNvo;

        // crear campo en tabla
        Consultas_TablaModificar::armado(__FILE__, __LINE__, $tabla, $tabla2);

        if (($this->_tabla_tipo == 'menu') || ($this->_tabla_tipo == 'tabuladores')) {
            $this->_modificarTablaExtras($tabla, $tabla2);
        }

        $this->_modificarComponenteIdRegistros($tabla, $tabla2);

        return true;
    }

    private function _modificarTablaExtras($tabla, $tabla2) {
        // modificacon de los nombres de las tablas relacionadas
        Consultas_TablaModificar::armado(__FILE__, __LINE__, $tabla . '_rel', $tabla2 . '_rel');
        Consultas_TablaModificar::armado(__FILE__, __LINE__, $tabla . '_trd', $tabla2 . '_trd');

        // modificacion de los id de las tablas relacionadas
        Consultas_CampoModificar::armado(__FILE__, __LINE__, $tabla2 . '_rel', 'id_' . $tabla . '_rel', 'id_' . $tabla2 . '_rel', 'numero', '12', true, true);
        Consultas_CampoModificar::armado(__FILE__, __LINE__, $tabla2 . '_trd', 'id_' . $tabla . '_trd', 'id_' . $tabla2 . '_trd', 'numero', '12', true, true);

        // modificacion de los id de la tabla original dentro de las tablas relacionadas
        Consultas_CampoModificar::armado(__FILE__, __LINE__, $tabla2 . '_rel', 'id_' . $tabla, 'id_' . $tabla2, 'numero', '12');
        Consultas_CampoModificar::armado(__FILE__, __LINE__, $tabla2 . '_trd', 'id_' . $tabla, 'id_' . $tabla2, 'numero', '12');

        if ($this->_tabla_tipo == 'tabuladores') {

            // modificacon de los nombres de las tablas relacionadas
            Consultas_TablaModificar::armado(__FILE__, __LINE__, $tabla . '_prd', $tabla2 . '_prd');

            // modificacion de los id de las tablas relacionadas
            Consultas_CampoModificar::armado(__FILE__, __LINE__, $tabla2 . '_prd', 'id_' . $tabla . '_prd', 'id_' . $tabla2 . '_prd', 'numero', '12', true, true);

            // modificacion de los id de la tabla original dentro de las tablas relacionadas
            Consultas_CampoModificar::armado(__FILE__, __LINE__, $tabla2 . '_prd', 'id_' . $tabla, 'id_' . $tabla2, 'numero', '12');
        }
    }

    private function _modificarComponenteIdRegistros($tabla, $tabla2) {

        $consulta = new Bases_RegistroModificar(__FILE__, __LINE__);
        $consulta->tabla('kirke_componente');
        $consulta->campoValor('kirke_componente', 'tabla_campo', 'id_' . $tabla2);
        $consulta->condiciones('', 'kirke_componente', 'tabla_campo', 'iguales', '', '', 'id_' . $tabla);
        return $consulta->realizarConsulta();
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|PaginaModificacionInsercion';
        
        $contenido .= '|tabla_nombre:' . $this->_tablaNombre.'->'.$this->_tablaNombreNvo;
        $contenido .= '|tabla_prefijo:' . $this->_tablaPrefijoTexto.'->'.$this->_tablaPrefijoTextoNvo;
        $contenido .= '|tipo:' . $this->tabla_tipo;
        $contenido .= '|habilitada:' . $this->_habilitada;
        //$contenido .= '|proceso_especial:' . $this->proceso_especial;

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}
