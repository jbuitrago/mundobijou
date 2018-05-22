<?php

class Acciones_RegistroTabuladoresBaja {

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaElemento('pagina', $_GET['id_tabla'], 'datos');

        // se obtiene el nombre de la pagina
        $datos_tabla = Consultas_ObtenerTablaNombreTipo::armado();
        
        $tabla_nombre = $datos_tabla['prefijo'] . '_' . $datos_tabla['nombre'];

        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $tabla_nombre, 'id_' . $tabla_nombre, $_GET['id_tabulador']);
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $tabla_nombre . '_rel', 'id_' . $tabla_nombre, $_GET['id_tabulador']);
        Consultas_RegistroEliminar::armado(__FILE__, __LINE__, $tabla_nombre . '_trd', 'id_' . $tabla_nombre, $_GET['id_tabulador']);
        
        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog();
        }

        // la redireccion va al final
        $armado_botonera = new Armado_Botonera();

        $parametros = array('kk_generar' => '0', 'accion' => '41', 'id_tabla' => $_GET['id_tabla'], 'valor_sistema' => 0, 'id_registro' => $_GET['extra']);
        $armado_botonera->armar('redirigir', $parametros);
    }
    
    private function _cargaLog() {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SISADMIN|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|RolBaja';

        $contenido .= '|id_tabulador:' . $_GET['id_tabulador'];

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISADMIN_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}
