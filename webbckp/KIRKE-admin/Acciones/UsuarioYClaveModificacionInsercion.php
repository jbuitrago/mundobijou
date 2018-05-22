<?php

class Acciones_UsuarioYClaveModificacionInsercion extends Armado_Plantilla {

    private $_usuarioActual;
    private $_claveActual;
    private $_usuarioNuevo;
    private $_claveNueva;
    private $_claveNuevaConfirmacion;

    public function armado() {

        // control de acceso a la pagina
        $validacion = new Seguridad_UsuarioValidacion();
        $validacion = $validacion->consultaUsuarioSesion();

        $this->_usuarioActual = Bases_InyeccionSql::consulta($_POST['usuario_actual']);
        $this->_claveActual = Bases_InyeccionSql::consulta($_POST['clave_actual']);
        $this->_claveNueva = Bases_InyeccionSql::consulta($_POST['clave_nueva']);
        $this->_claveNuevaConfirmacion = Bases_InyeccionSql::consulta($_POST['clave_nueva_confirmacion']);

        $validacion = new Seguridad_UsuarioValidacion();
        if ($validacion->consultaInicio($this->_usuarioActual, $this->_claveActual) === true) {
            if (($this->_claveNueva == $this->_claveNuevaConfirmacion) && (strlen($this->_claveNueva) < 6)) {
                return $this->_mensaje('clave_corta', false);
            } elseif ($this->_claveNueva == $this->_claveNuevaConfirmacion) {
                $this->_actualizarDatos();
                return $this->_mensaje('correcto', true);
            } else {
                return $this->_mensaje('diferentes', false);
            }
        } else {
            return $this->_mensaje('incorrecto', false);
        }
    }

    private function _mensaje($datos, $validado) {

        if ($datos == 'incorrecto') {
            $mensaje = '{TR|o_el_usuario_o_clave_ingresados_son_incorrectos}';
        } elseif ($datos == 'clave_corta') {
            $mensaje = '{TR|o_debe_contener_al_menos_6_caracteres}';
        } elseif ($datos == 'correcto') {
            $mensaje = '{TR|o_los_datos_han_sido_actualizados}';
        } elseif ($datos == 'diferentes') {
            $mensaje = '{TR|o_las_nuevas_claves_son_diferentes}';
        }

        if (Inicio::confVars('generar_log') == 's') {
            $this->_cargaLog($validado);
        }

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('general'));

        $armado_botonera = new Armado_Botonera();
        $parametros = array('kk_generar' => '0', 'accion' => '63');
        $armado_botonera->armar('volver', $parametros);

        $ver = '<div class="titulo_texto"  align="center">' . $mensaje . '</div>';

        return $ver;
    }

    private function _actualizarDatos() {

        $clave_encriptada = md5(preg_replace("/[^A-Za-z0-9]/", "", $this->_claveNueva));

        // crear usuario
        Consultas_Usuario::RegistroModificarUsuarioClave(__FILE__, __LINE__, Inicio::usuario('id'), $clave_encriptada);

        return true;
    }

    private function _cargaLog($validado) {

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $contenido = date('Y-m-d H:i:s') . '|SIS|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['valor'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['id'] . '|' . $_SESSION['KIRKE-admin'][Inicio::confVars('basedatos')]['usuario']['usuario'] . '|' . Inicio::usuario('nombre') . ',' . Inicio::usuario('apellido') . '|navegador:' . $_SERVER['HTTP_USER_AGENT'] . '|ip:' . $_SERVER['REMOTE_ADDR'];

        $contenido .= '|ConfiguracionPersonalModificacionInsercion|clave:' . md5(preg_replace("/[^A-Za-z0-9]/", "", $this->_claveNueva));

        if($validado){
            $contenido .= '|validado';
        }else{
            $contenido .= '|no validado';
        }

        file_put_contents($directorio . '/' . Inicio::confVars('basedatos') . '-SISMOD_' . date('Y-m') . '.log', $contenido . "\n", FILE_APPEND | LOCK_EX);
    }

}
