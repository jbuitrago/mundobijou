<?php

class Acciones_MenuLinkAltaInsercion extends Armado_Plantilla {

    private $_elemento;
    private $_elemento_post;
    private $_idMenu;
    private $_idioma = array();
    private $_idiomaTexto = array();
    private $_idMenuLink;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        Armado_Menu::reinciarMenu();

        if (is_array(Inicio::confVars('idiomas'))) {
            $contador = 0;
            foreach (Inicio::confVars('idiomas') as $key => $value) {
                $this->_idioma[$contador] = $value;
                $this->_idiomaTexto[$contador] = $_POST['etiqueta_' . $value];
                $contador++;
            }
        }

        $elementoPost = $_POST['elemento'];

        if ($elementoPost == 'pagina_registros') {
            $this->_elemento_post = $_POST['id_tabla_registros'];
            $this->_elemento = 'pagina';
        } elseif ($elementoPost == 'pagina_variables') {
            $this->_elemento_post = $_POST['id_tabla_variables'];
            $this->_elemento = 'pagina';
        } elseif ($elementoPost == 'pagina_menu') {
            $this->_elemento_post = $_POST['id_tabla_menu'];
            $this->_elemento = 'pagina';
        } elseif ($elementoPost == 'pagina_tabuladores') {
            $this->_elemento_post = $_POST['id_tabla_tabuladores'];
            $this->_elemento = 'pagina';
        } elseif ($elementoPost == 'informe') {
            $this->_elemento_post = $_POST['id_informe'];
            $this->_elemento = 'informe';
        } elseif ($elementoPost == 'desarrollo') {
            $this->_elemento_post = $_POST['id_desarrollo'];
            $this->_elemento = 'desarrollo';
        }

        $this->_insertarConfiguracion();
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();
        if ($elementoPost == 'pagina_registros') {
            $parametros = array('kk_generar' => '0', 'accion' => '22', 'id_menu_link' => $this->_idMenuLink, 'id_menu' => $_GET['id_menu'], 'alta' => 'si');
        } else {
            $parametros = array('kk_generar' => '0', 'accion' => '21', 'id_menu' => $_GET['id_menu']);
        }
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _insertarConfiguracion() {

        $orden = Consultas_ObtenerRegistroMaximo::armado('kirke_menu_link', 'orden');

        // crear el link
        $id_insertado = Consultas_MenuLink::RegistroCrear(__FILE__, __LINE__, $_GET['id_menu'], $this->_elemento_post, $this->_elemento, $orden[0]['orden'] + 1);
        $this->_idMenuLink = $id_insertado['id'];

        // carga las etiquetas del link
        if (isset($this->_idioma) && is_array($this->_idioma)) {
            foreach ($this->_idioma as $key => $value) {

                // crear parametro idiomas
                Consultas_MenuLinkNombre::RegistroCrear(__FILE__, __LINE__, $this->_idMenuLink, $this->_idioma[$key], $this->_idiomaTexto[$key]);
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

        $contenido .= '|MenuLinkAltaInsercion';

        $contenido .= '|id_menu:' . $_GET['id_menu'];
        $contenido .= '|elemento_post:' . $this->_elemento_post;
        $contenido .= '|elemento:' . $this->_elemento;
        $contenido .= '|habilitada:' . $this->_habilitada;

        $contenido .= '|idiomas:';
        $coma = '';
        if (isset($this->_idioma) && is_array($this->_idioma)) {
            foreach ($this->_idioma as $key => $value) {
                $contenido .= $coma . '[' . $this->_idioma[$key] . ':' . $this->_idiomaTexto[$key] . ']';
                $coma = ',';
            }
        }

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}
