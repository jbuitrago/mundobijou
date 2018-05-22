<?php

class Armado_Mail {

    private $_servidorMail;
    private $_servidorNombre;
    private $_cabeceras;
    private $_asunto;
    private $_datos = Array();
    private $_mailDestinatario;
    private $_mailDestinatarioNombre;
    private $_mailDestinatarioCc;
    private $_mailDestinatarioCco;
    private $_mailRespuesta;

    public function servidorMail($mail) {
        $this->_servidorMail = $mail;
    }

    public function servidorNombre($nombre) {
        $this->_servidorNombre = $nombre;
    }

    public function mailDestinatario($mail, $nombre = null) {
        $this->_mailDestinatario .= ', ' . $mail;

        if ($nombre) {
            $this->_mailDestinatarioNombre .= ', ' . $nombre . ' <' . $mail . '>';
        } else {
            $this->_mailDestinatarioNombre .= ', ' . $mail;
        }
    }

    public function mailDestinatarioCc($mail) {
        $this->_mailDestinatarioCc .= ', ' . $mail;
    }

    public function mailDestinatarioCco($mail) {
        $this->_mailDestinatarioCco .= ', ' . $mail;
    }

    public function mailRespuesta($mail) {
        $this->_mailRespuesta = $mail;
    }

    public function asunto($asunto) {
        $this->_asunto = $asunto;
    }

    public function datos($nombre, $valor, $tipo) {

        if ($tipo == 'texto') {

            $this->_datos[] = '      <div class="contenido_margen"></div>
        <div class="contenido_titulo">' . $nombre . '</div>
        <div class="contenido_7">' . $valor . '</div>
        <div class="contenido_separador_color"></div>';
        } elseif ($tipo == 'texto_largo') {

            $this->_datos[] = '      <div class="contenido_margen"></div>
        <div class="contenido_titulo">' . $nombre . '</div>
        <div class="contenido_alto_libre">' . $valor . '</div>
        <div class="contenido_separador"></div>
        <div class="contenido_separador_color"></div>';
        } elseif ($tipo == 'titulo') {

            $this->_datos[] = '      <div class="contenido_separador"></div>
    	  <div class="titulo">' . $valor . '</div>
    	  <div class="contenido_separador"></div>
    	  <div class="contenido_separador_color"></div>';
        }
    }

    public function mensaje() {

        $mensaje = '     <head>
      <style type="text/css">
      <!--
      ' . $this->_obtenerCss() . '
      --></style>
      </head>';

        foreach ($this->_datos as $v) {
            $mensaje .= $v;
        }

        return $mensaje;
    }

    private function _cabeceras() {

        // Para enviar correo HTML, la cabecera Content-type debe definirse
        $cabeceras = 'MIME-Version: 1.0' . "\r\n";
        $cabeceras .= 'Content-type: text/html; charset=utf-8' . "\r\n";

        // Cabeceras adicionales
        $cabeceras .= 'To: ' . substr($this->_mailDestinatarioNombre, 1) . "\r\n";
        $cabeceras .= 'From: ' . $this->_servidorNombre . ' <' . $this->_servidorMail . '>' . "\r\n";
        if ($this->_mailDestinatarioCc != '') {
            $cabeceras .= 'Cc: ' . substr($this->_mailDestinatarioCc, 1) . "\r\n";
        }
        if ($this->_mailDestinatarioCco != '') {
            $cabeceras .= 'Bcc: ' . substr($this->_mailDestinatarioCco, 1) . "\r\n";
        }
        $cabeceras .= 'Reply-To: ' . $this->mailRespuestaObtener() . "\r\n";

        return $cabeceras;
    }

    public function envio() {

        if (mail(substr($this->_mailDestinatario, 1), $this->_asunto, $this->mensaje(), $this->_cabeceras())) {
            return true;
        } else {
            return false;
        }
    }

    public function ver() {
        return $this->_cabeceras() . $this->mensaje();
    }

    public function mailRespuestaObtener() {
        if ($this->_mailRespuesta != '') {
            return $this->_mailRespuesta;
        } else {
            return $_SERVER['SERVER_NAME'];
        }
    }

    private function _obtenerCss() {

        $archivo = Inicio::pathPublico() . '/Plantillas/' . Inicio::confVars('plantilla') . '/css/mails.css';

        if (file_exists($archivo)) {
            return file_get_contents($archivo);
        } else {
            echo 'problema en Mail';
        }
    }

}

