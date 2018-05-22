<?php

class ArmadoFormulario extends PlantillaReemplazos {

    private $_nombreCampo;
    private $_captcha = true;
    private $_asunto;
    private $_mail_origen;
    private $_nombre_origen;
    private $_destinatario;
    private $_nombre_destinatario;
    private $m_envio_mail = false;
    private $m_asunto;
    private $m_mail_destinatario = array();
    private $m_mail_destinatario_cc = array();
    private $m_mail_destinatario_cco = array();
    private $m_mail_asunto = array();
    private $m_servidor_mail;
    private $m_servidor_nombre;
    private $m_mail_respuesta;
    private $m_ver_codigo = false;
    private $m_destinatario;            // no se usa mas, compativialidad antes del 07/2013
    private $m_nombre_destinatario;     // no se usa mas, compativialidad antes del 07/2013
    private $m_mail_origen;             // no se usa mas, compativialidad antes del 07/2013
    private $m_nombre_origen;           // no se usa mas, compativialidad antes del 07/2013
    private $m_sis_ant = false;         // no se usa mas, compativialidad antes del 07/2013
    private $_nombreArchivo;
    private $_cargarEnBaseTabla;
    private $_cargarEnBaseTablaCampos = array();
    private $_cargarEnBaseMostrarErrores = 'n';
    private $_cargarEnBase = false;
    private $_kk_control_form;
    private $_formulario = array();
    private $_envioMailCampos;
    private static $_formulario_tpl = array();

    function __construct($kk_control_form) {

        $this->_kk_control_form = $kk_control_form;

        if (!isset($_SESSION['kk_sistema']['Formulario']['cont'])) {
            $_SESSION['kk_sistema']['Formulario']['cont'] = 0;
        }
    }

    public function nombreArchivo($nombre_archivo) {
        $this->_nombreArchivo = $nombre_archivo;
    }

    // obtencion de los valores de los campos definidos en el php
    public static function getCampo($plantilla_nombre, $nombre, $elemento) {

        if (isset(self::$_formulario_tpl[$plantilla_nombre][$nombre][$elemento])) {
            return self::$_formulario_tpl[$plantilla_nombre][$nombre][$elemento];
        } else {
            return false;
        }
    }

    // para controlar desde otras clases si existe algun formulario
    public static function existeFormulario() {

        if (count(self::$_formulario_tpl) > 0) {
            return true;
        } else {
            return false;
        }
    }

    // campos
    public function campo($nombre, $etiqueta = null, $obligatorio = null, $tamano = null) {

        if ($etiqueta == '') {
            $etiqueta = $nombre;
        }

        $this->_formulario[$this->_kk_control_form][$nombre]['etiqueta'] = $etiqueta;
        $this->_formulario[$this->_kk_control_form][$nombre]['obligatorio'] = $obligatorio;
        $this->_formulario[$this->_kk_control_form][$nombre]['tamano'] = $tamano;

        // para modificar los campos en los templates segun lo parametros
        // - nombre real del archivo, este se toma directamente del script que llamo al metodo

        if ($this->_nombreArchivo != '') {
            $nombreArchivo = $this->_nombreArchivo;
        } else {
            $archivo = debug_backtrace(false);
            $archivo = basename($archivo[0]['file']);
            $nombreArchivo = preg_replace("/([a-zA-Z0-9._.-.]+)\.php/", "\${1}", $archivo);
        }

        self::$_formulario_tpl[$nombreArchivo][$nombre]['etiqueta'] = $etiqueta;
        self::$_formulario_tpl[$nombreArchivo][$nombre]['obligatorio'] = $obligatorio;
        self::$_formulario_tpl[$nombreArchivo][$nombre]['tamano'] = $tamano;

        $this->_nombreCampo = $nombre;
    }

    // validacion de contenido
    public function campoValor($tipo, $parametro1 = null, $parametro2 = null) {
        switch ($tipo) {
            case 'mail':

                // para modificar los campos en los templates segun lo parametros
                // - nombre real del archivo, este se toma directamente del script que llamo al metodo
                if ($this->_nombreArchivo != '') {
                    $nombreArchivo = $this->_nombreArchivo;
                } else {
                    $archivo = debug_backtrace(false);
                    $archivo = basename($archivo[0]['file']);
                    $nombreArchivo = preg_replace("/([a-zA-Z0-9._.-.]+)\.php/", "\${1}", $archivo);
                }
                self::$_formulario_tpl[$nombreArchivo][$this->_nombreCampo]['valor'] = $tipo;

                break;
            case 'filtro':
                echo "<script type=\"text/javascript\"> $(document).ready(function(){ solo_texto_permitido('" . $this->_nombreCampo . "','" . $parametro1 . "') }); </script>";
                break;
        }
    }

    // validacion del captcha
    public function campoCaptcha() {

        $this->campo('captcha', '', '', 6);
        $this->campoValor('captcha', 'El codigo ingresado de la imagen es incorrecto');

        $captcha = new ArmadoCaptcha();
        $captcha->formulario($this->_kk_control_form);
        if ($captcha->verificar('captcha')) {

            $this->_captcha = true;
            unset($_SESSION['kk_sistema']['captcha']);
        } else {

            $this->_captcha = false;
        }
    }

    // campos a se enviado por mail
    public function envioMailCampos($datos) {
        $this->_envioMailCampos = $datos;
    }

    // no se usa mas, compatibilidad antes del 07/2013
    public function envioMail($m_asunto = null, $m_destinatario = null, $m_nombre_destinatario = null, $m_mail_origen = null, $m_nombre_origen = null) {

        $this->m_envio_mail = true;
        $this->m_asunto = $m_asunto;
        $this->m_destinatario = $m_destinatario;
        $this->m_nombre_destinatario = $m_nombre_destinatario;
        $this->m_mail_origen = $m_mail_origen;
        $this->m_nombre_origen = $m_nombre_origen;
        $this->m_sis_ant = true;
    }

    public function envio() {
        $this->m_envio_mail = true;
    }

    public function servidorMail($mail) {
        $this->m_servidor_mail = $mail;
    }

    public function servidorNombre($nombre) {
        $this->m_servidor_nombre = $nombre;
    }

    public function mailDestinatario($mail, $nombre = NULL) {
        $num = count($this->m_mail_destinatario);
        $this->m_mail_destinatario[$num]['mail'] = $mail;
        $this->m_mail_destinatario[$num]['nombre'] = $nombre;
    }

    public function mailDestinatarioCc($mail) {
        $this->m_mail_destinatario_cc[] = $mail;
    }

    public function mailDestinatarioCco($mail) {
        $this->m_mail_destinatario_cco[] = $mail;
    }

    public function mailRespuesta($mail) {
        $this->m_mail_respuesta = $mail;
    }

    public function asunto($asunto, $asunto_matriz_reemplazo = null) {
        $num = count($this->m_asunto);
        $this->m_asunto[$num]['asunto'] = $asunto;
        $this->m_asunto[$num]['asunto_matriz_reemplazo'] = $asunto_matriz_reemplazo;
    }

    // definicion de tabla y campos a cargar en la misma
    public function cargarEnBaseTablaCampos($tabla, $campos) {
        $this->_cargarEnBase = true;
        $this->_cargarEnBaseTabla = $tabla;
        $this->_cargarEnBaseTablaCampos = $campos;
    }

    // definicion de tabla y campos a cargar en la misma
    public function cargarEnBaseMostrarErrores() {
        $this->_cargarEnBaseMostrarErrores = 's';
    }

    // controla si se recepcinan bien los datos y la cantidad de veces,
    // sin javascript, sirve para casos como el captcha
    public function controlVariables() {

        $validacion = false;

        if (
                (count($_POST) > 0) && isset($_POST['kk_control_form']) && ($_POST['kk_control_form'] == $this->_kk_control_form)
        ) {
            $validacion = true;

            if (count($this->_formulario) > 0) {
                foreach ($this->_formulario[$this->_kk_control_form] as $key => $valor) {
                    if ($valor['obligatorio'] == 'no_nulo') {
                        if (isset($_POST[$key]) && is_array($_POST[$key])) {
                            $valor_nulo = false;
                            foreach ($_POST[$key] as $valor_post) {
                                if (isset($valor_post) && $valor_post != '') {
                                    $valor_nulo = true;
                                }
                            }
                            $validacion = $valor_nulo;
                        } elseif ((!isset($_POST[$key]) || $_POST[$key] == '')) {
                            $validacion = false;
                        }
                    }
                }

                if ($this->_captcha === false) {
                    $validacion = false;
                }
            }
        }

        return $validacion;
    }

    // controla si se recepcinan bien los datos y la cantidad de veces,
    // sin javascript, sirve para casos como el captcha
    public function recepcionControl() {

        if ($this->ControlVariables()) {

            if ($this->m_envio_mail) {
                $this->_enviarMail();
            }
            if ($this->_cargarEnBase) {
                $insercion = $this->_cargarEnBase();
            }
            if (isset($insercion)) {
                return $insercion;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    public function verCodigo() {

        $this->m_ver_codigo = true;
    }

    // Metodos privados

    private function _enviarMail() {

        // preparacion envio de mail
        $this->_mail = new ArmadoMail;

        if ($this->m_sis_ant === true) {
            $this->_mail->asunto($this->m_asunto);
            $this->_mail->servidorMail($this->m_mail_origen);
            $this->_mail->servidorNombre($this->m_nombre_origen);
            $this->_mail->mailDestinatario($this->m_destinatario, $this->m_destinatario);
        } else {
            $this->_mail->servidorMail($this->m_servidor_mail);
            $this->_mail->servidorNombre($this->m_servidor_nombre);
            foreach ($this->m_mail_destinatario as $valor) {
                $this->_mail->mailDestinatario($valor['mail'], $valor['nombre']);
            }
            foreach ($this->m_mail_destinatario_cc as $valor) {
                $this->_mail->mailDestinatarioCc($valor);
            }
            foreach ($this->m_mail_destinatario_cco as $valor) {
                $this->_mail->mailDestinatarioCco($valor);
            }
            $this->_mail->mailRespuesta($this->m_mail_respuesta);
            foreach ($this->m_mail_asunto as $valor) {
                $this->_mail->asunto($valor['asunto'], $valor['asunto_matriz_reemplazo']);
            }
        }

        $this->_mail->html($this->_armadoMail());

        if ($this->m_ver_codigo === true) {
            $this->_mail->verCodigo();
        }

        $this->_mail->envio();
    }

    public function _cargarEnBase() {

        if (count($this->_cargarEnBaseTablaCampos) > 0) {

            $guardar_contacto = new BDInsercion();
            $guardar_contacto->baseTabla($this->_cargarEnBaseTabla);

            foreach ($this->_cargarEnBaseTablaCampos as $valor) {
                if (!is_array($valor)) {
                    $guardar_contacto->campoValor($valor, $_POST[$valor]);
                } else {
                    $guardar_contacto->campoValor($valor[0], $valor[1]);
                }
            }

            return $guardar_contacto->insertar($this->_cargarEnBaseMostrarErrores);
        }
        return false;
    }

    //#######################################################################################################

    private function _armadoMail() {
        if (count($this->_formulario[$this->_kk_control_form]) > 0) {

            $armado_mail = '<table width="300" border="0" cellpadding="1" cellspacing="0" bgcolor="#CCCCCC">' . "\n" . '<tr>' . "\n" . '<td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#0162A9">' . "\n" . '<tr>' . "\n" . '<td height="40" align="center" style="font-family:Arial,Helvetica,sans-serif;font-size:14px;color:#FFFFFF;">Mensaje enviado desde el sitio web</td>' . "\n" . '</tr>' . "\n" . '</table></td>' . "\n" . '</tr>' . "\n";

            foreach ($this->_formulario[$this->_kk_control_form] as $key => $valor) {

                if (isset($_POST[$key]) && (array_search($key, $this->_envioMailCampos) !== false)) {

                    if (is_array($_POST[$key]) !== false) {
                        $valor_post = implode('<br />', $_POST[$key]);
                    } else {
                        $valor_post = $_POST[$key];
                    }

                    $armado_mail .= '<tr><td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#0099CC">' . "\n" . '<tr>' . "\n" . '<td width="100" height="20" style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#FFFFFF;">&nbsp;<strong>' . $valor['etiqueta'] . '</strong></td>' . "\n" . '<td><span style="font-family:Arial,Helvetica,sans-serif;font-size:12px;color:#FFFFFF;">' . $valor_post . '</span></td>' . "\n" . '</tr>' . "\n" . '</table></td>' . "\n" . '</tr>' . "\n";
                }
            }

            $armado_mail .= '<tr>' . "\n" . '<td><table width="100%" border="0" cellpadding="0" cellspacing="0" bgcolor="#0162A9">' . "\n" . '<tr>' . "\n" . '<td height="25" align="right" valign="middle"><a href="http://kirke.ws/" style="font-family:Arial,Helvetica,sans-serif;font-size:9px;color:#FFFFFF;font-weight:bold;text-decoration:none;">KIRKE</a>&nbsp;&nbsp;</td>' . "\n" . '</tr>' . "\n" . '</table></td>' . "\n" . '</tr>' . "\n" . '</table>' . "\n";

            return $armado_mail;
        } else {
            return false;
        }
    }

}
