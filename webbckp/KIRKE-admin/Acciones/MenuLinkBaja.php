<?php

class Acciones_MenuLinkBaja extends Armado_Plantilla {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento();

        $id_menu = Consultas_MenuLink::RegistroConsultaIdMenuObtener(__FILE__, __LINE__, $_GET['id_menu_link']);

        $this->_insertarConfiguracion();
        
        Armado_Menu::reinciarMenu();
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '21', 'id_menu' => $id_menu[0]['id_menu'], 'id_menu_link' => $_GET['id_menu_link']);
        $armado_botonera->armar('redirigir', $parametros);
    }

    private function _insertarConfiguracion() {

        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_menu_link_nombre', 'id_menu_link', $_GET['id_menu_link']);

        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_menu_link', 'id_menu_link', $_GET['id_menu_link']);

        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, 'kirke_menu_link_parametro', 'id_menu_link', $_GET['id_menu_link']);
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|MenuLinkBaja';

        $contenido .= '|id_menu_link:' . $_GET['id_menu_link'];

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}

