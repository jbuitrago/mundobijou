<?php

class ArmadoCaptcha {

    private $_captcha;
    private $_formulario;

    function __construct() {
        include_once "_clases/captcha/securimage.php";
        $this->_captcha = new Securimage();
    }

    public function generar($path_imagen_fondo = null) {
        if ($path_imagen_fondo == null) {
            $this->_captcha->show();
        } else {
            $this->_captcha->show($path_imagen_fondo);
        }
    }

    public function mostrar() {
        // muestra la imagen
        return "<a href=\"#\" onclick=\"document.getElementById('captcha_" . $this->_formulario . "').src = '/index.php?kk_captcha=captcha&formulario=" . $this->_formulario . "&sid=' + Math.random(); return false\"><img src=\"/index.php?kk_captcha=captcha&formulario=" . $this->_formulario . "&sid=" . md5(uniqid(time())) . "\" id=\"captcha_" . $this->_formulario . "\" border=\"0\"/></a>";
    }

    public function verificar($nombre_campo) {
        if (isset($_POST[$nombre_campo]) && ($_POST[$nombre_campo] != '')) {
            return $this->_captcha->check($_POST[$nombre_campo]);
        } else {
            return false;
        }
    }

    public function verificar_ajax($valor = null) {
        if (isset($valor) && ($valor != '')) {
            return $this->_captcha->check($valor);
        } else {
            return false;
        }
    }

    public function formulario($nombre) {
        $this->_formulario = $nombre;
        $this->_captcha->namespace = $nombre;
    }

}
