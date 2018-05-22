<?php

class ArmadoMail extends PlantillaFunciones {

    private $_servidorMail;
    private $_servidorNombre;
    private $_cabeceras;
    private $_asunto;
    private $_mimeBoundary;
    private $_mimeBoundaryAdjunto;
    private $_mimeBoundaryImgIncrustada;
    private $_datos = Array();
    private $_datosTxt = Array();
    private $_html;
    private $_imagenIncrustada = Array();
    private $_imagenIncrustadaId = Array();
    private $_imagenIncrustadaTipo = Array();
    private $_imagenIncrustadaNombre = Array();
    private $_imagenIncrustadaCod = Array();
    static private $_imagenIncrustadaContador = 0;
    private $_mailDestinatario;
    private $_mailDestinatarioCc;
    private $_mailDestinatarioCco;
    private $_mailRespuesta;
    private $_adjunto = Array();
    private $_adjuntoTipo = Array();
    private $_adjuntoNombre = Array();
    private $_aaCont = 0;
    private $_servidor;
    private $_usuario;
    private $_clave;
    private $_puerto;
    private $_autentificar = false;
    private $_controlAutentificar = 'n';
    private $_quotedPrintable = true;
    private $_nombreArchivo;
    private $_contenido;
    private $_var_tpl = Array();
    private $_var_tpl_glob;
    private $_asunto_matriz_reemplazo;

    public function nombreArchivo($nombre_archivo) {
        $this->_nombreArchivo = $nombre_archivo;
    }

    public function asignar($variable, $valor = NULL) {
        $this->_var_tpl[$variable] = $valor;
    }

    public function servidorMail($mail) {
        $this->_servidorMail = $mail;
    }

    public function servidorNombre($nombre) {
        $this->_servidorNombre = $nombre;
    }

    public function mailDestinatario($mail, $nombre = NULL) {
        $this->_mailDestinatario .= ', ' . $mail;
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

    public function asunto($asunto, $asunto_matriz_reemplazo = null) {
        $this->_asunto = $asunto;
        if (isset($asunto_matriz_reemplazo)) {

            if (count($asunto_matriz_reemplazo) > 0) {
                $this->_asunto_matriz_reemplazo = $asunto_matriz_reemplazo;
                $patron = '/{\\$(.*?)\}/';
                $this->_asunto = preg_replace_callback($patron, array($this, '_reemplazar'), $this->_asunto);
            }
        }
    }

    private function _reemplazar($coincidencias) {

        return $this->_asunto_matriz_reemplazo[$coincidencias[1]];
    }

    public function datos($nombre, $valor, $tipo) {

        if ($tipo == 'texto') {

            $this->_datos[] = "<div style=\"float:left;width:2%;height:40px;background-color:#FFFFFF;\"></div><div style=\"float:left;width:28%;height:40px;overflow:hidden;background-color:#FFFFFF;\">" . $nombre . ":</div><div style=\"float:left;width:70%;height:40px;overflow:hidden;\">" . $valor . "</div><div style=\"float:left;width:100%;height:10px;border-top-width:1px;border-top-style:solid;border-top-color:#CCCCCC;background-color:#FFFFFF;\"></div>";

            $this->_datosTxt[] = $nombre . ": " . $valor . "\r\n";
        } elseif ($tipo == 'texto_largo') {

            $this->_datos[] = "<div style=\"float:left;width:2%;height:40px;background-color:#FFFFFF;\"></div><div style=\"float:left;width:28%;height:40px;overflow:hidden;background-color:#FFFFFF;\">" . $nombre . ":</div><div style=\"float:left;width:70%;overflow:hidden;background-color:#FFFFFF;\">" . $valor . "</div><div style=\"float:left;width:100%;height:10px;background-color:#FFFFFF;\"></div><div style=\"float:left;width:100%;height:10px;border-top-width:1px;border-top-style:solid;border-top-color:#CCCCCC;background-color:#FFFFFF;\"></div>";

            $this->_datosTxt[] = "\r\n" . $nombre . ":\r\n " . $valor . "\r\n\r\n";
        } elseif ($tipo == 'titulo') {

            $this->_datos[] = "<div style=\"float:left;width:100%;height:10px;background-color:#FFFFFF;\"></div><div style=\"float:left;width:100%;height:30px;font-family:Arial, Helvetica, sans-serif;font-size:12px;font-weight:bold;background-color:#FFFFFF;text-align:center;vertical-align:middle;color:#666666;\">" . $valor . "</div><div style=\"float:left;width:100%;height:10px;background-color:#FFFFFF;\"></div><div style=\"float:left;width:100%;height:10px;border-top-width:1px;border-top-style:solid;border-top-color:#CCCCCC;background-color:#FFFFFF;\"></div>";

            $this->_datosTxt[] = "\r\n- " . $valor . "\r\n\r\n";
        }
    }

    public function html($html, $html_matriz_reemplazo = null) {

        $this->_html = $html;
    }

    public function adjuntoDesdeCampo($nombre_campo) {

        for ($i = 0; $i < count($_FILES[$nombre_campo]['name']); $i++) {
            if (
                    isset($_FILES[$nombre_campo]['tmp_name'][$i]) &&
                    is_uploaded_file($_FILES[$nombre_campo]['tmp_name'][$i]) &&
                    !empty($_FILES[$nombre_campo]['size'][$i]) &&
                    !empty($_FILES[$nombre_campo]['name'][$i])) {

                $abrir_archivo = fopen($_FILES[$nombre_campo]['tmp_name'][$i], 'rb');
                $this->_adjunto[$i] = chunk_split(base64_encode(fread($abrir_archivo, $_FILES[$nombre_campo]['size'][$i])));
                fclose($abrir_archivo);

                $this->_adjuntoTipo[$i] = $_FILES[$nombre_campo]['type'][$i];
                $this->_adjuntoNombre[$i] = $_FILES[$nombre_campo]['name'][$i];
            }
        }
    }

    public function adjuntoDesdeArchivo($nombre, $directorio = null) {

        if ($directorio != null) {
            $nombre_dir = $directorio . '/' . $nombre;
        } else {
            $nombre_dir = $nombre;
        }

        if (file_exists($nombre_dir)) {

            $abrir_archivo = fopen($nombre_dir, 'rb');
            $this->_adjunto[$this->_aaCont] = chunk_split(base64_encode(fread($abrir_archivo, filesize($nombre_dir))));
            fclose($abrir_archivo);

            $this->_adjuntoTipo[$this->_aaCont] = ObtenerMime::obtener($nombre_dir);
            $this->_adjuntoNombre[$this->_aaCont] = $nombre;

            $this->_aaCont++;
        }
    }

    private function _imagenIncrustada($nombre_imagen) {

        if (file_exists($nombre_imagen)) {

            $cont = self::$_imagenIncrustadaContador;

            $abrir_imagen = fopen($nombre_imagen, 'rb');

            $this->_imagenIncrustadaCod[$cont] = chunk_split(base64_encode(fread($abrir_imagen, filesize($nombre_imagen))));
            fclose($abrir_imagen);

            $id = md5(date('r', time() + 300));

            if ($this->_servidorMail != '') {
                preg_match("/^(.*?)@(.*?)$/", $this->_servidorMail, $mail_en_partes);
                $identificador_mail = '@' . $mail_en_partes[2];
            } else {
                $identificador_mail = '@kirke.com.ar';
            }

            $this->_imagenIncrustadaId[$cont] = "part" . $cont . "." . $id . $identificador_mail;
            $this->_imagenIncrustadaTipo[$cont] = ObtenerMime::obtener($nombre_imagen);
            $this->_imagenIncrustadaNombre[$cont] = $nombre_imagen;

            self::$_imagenIncrustadaContador++;

            return '<img src="cid:part' . (self::$_imagenIncrustadaContador - 1) . "." . $id . $identificador_mail . '" border="0">';
        }
    }

    public function autentificar($servidor, $usuario, $clave, $puerto = null, $control = 'n') {

        if ($control == 's') {
            $this->_controlAutentificar = 's';
        }

        if ((isset($servidor)) && (isset($usuario)) && (isset($clave))) {
            $this->_servidor = $servidor;
            $this->_usuario = $usuario;
            $this->_clave = $clave;
            if ($puerto == '') {
                $this->_puerto = 25;
            } else {
                $this->_puerto = $puerto;
            }
            $this->_autentificar = true;
        }
    }

    public function quotedPrintable() {
        $this->_quotedPrintable = true;
    }

//========================================================================================================================

    public function envio() {

        // si el servidor esta en modo testing y tiene la IP que le corresponde 
        // por KIRKE entonces envia usando mail de prueba de KIRKE
        if (isset($_SERVER['KIRKE_DEV']) && ($_SERVER['KIRKE_DEV'] != '')) {
            $this->autentificar($_SERVER['KIRKE_MAIL_SERV'], $_SERVER['KIRKE_MAIL_USER'], $_SERVER['KIRKE_MAIL_PASS'], $_SERVER['KIRKE_MAIL_PORT']);
        }

        if (!isset($this->_servidorMail)) {
            $this->_servidorMail = VariableGet::sistema('mail_servidor');
        }
        if (!isset($this->_servidorNombre)) {
            $this->_servidorNombre = VariableGet::sistema('nombre_servidor');
        }
        if (!isset($this->_mailDestinatario)) {
            $this->_mailDestinatario = VariableGet::sistema('nombre_responsable') . ' <' . VariableGet::sistema('mail_responsable') . '> ';
        }

        $mailDestinatario = substr(mb_convert_encoding($this->_mailDestinatario, "ISO-8859-1", "UTF-8"), 1);
        $asunto = mb_convert_encoding($this->_asunto, "ISO-8859-1", "UTF-8");
        $mensaje = mb_convert_encoding($this->_mensaje(), "ISO-8859-1", "UTF-8");
        $cabeceras = mb_convert_encoding($this->_cabeceras(), "ISO-8859-1", "UTF-8");

        if ($this->_autentificar === true) {
            $this->_autentificacion($mailDestinatario, $asunto, $mensaje, $cabeceras);
            $enviado = true;
        } else {
            if (mail($mailDestinatario, $asunto, $mensaje, $cabeceras)) {
                $enviado = true;
            } else {
                $enviado = false;
            }
        }

        return $enviado;
    }

    public function verCodigo() {
        $ver = 'Destinatario: ' . substr($this->_mailDestinatario, 1);
        $ver .= "\r\n";
        $ver .= 'Asunto: ' . $this->_asunto;
        $ver .= "\r\n\r\n";
        $ver .= 'Cabeceras mail ====================================================';
        $ver .= "\r\n\r\n";
        $ver .= $this->_cabeceras();
        $ver .= "\r\n\r\n";
        $ver .= 'Cuerpo mail =======================================================';
        $ver .= "\r\n\r\n";
        $ver .= $this->_mensaje();
        echo $ver;
    }

//========================================================================================================================

    private function _mensaje() {

        $mensaje = '';
        $mensaje_html = '';
        $mensaje_txt = '';
        $mensaje_html_dir = '';
        $mensaje_txt_dir = '';

        if (count($this->_datos) > 0) {

            foreach ($this->_datos as $v) {
                $mensaje_html .= $v;
            }

            foreach ($this->_datosTxt as $v) {
                $mensaje_txt .= $v;
            }
        }

        $this->armarTemplate();
        $_html = $this->_PlantillaReemplazos();

        if ($_html !== false) {

            $this->_html = $_html;
        }

        if (isset($this->_html)) {

            $html_sin_salto_lineas = preg_replace("[\\n|\\r|\\n\\r|\\t]", ' ', $this->_html);

            preg_match("/<body(.*?)<\/body>/", $html_sin_salto_lineas, $extraido);

            if (isset($extraido[0])) {
                $nuevo_html = $extraido[0];
                $contine_body = true;
            } else {
                $nuevo_html = $html_sin_salto_lineas;
                $contine_body = false;
            }

            $nuevo_html = preg_replace_callback("/{mail_img[\s]name=\"{1}(.*?)\"[\s]dir=\"{1}(.*?)\"\}{1}/", array($this, '_reemplazoImagenIncrustadas'), $nuevo_html);

            $mensaje_html_dir = mb_convert_encoding($nuevo_html, "UTF-8", "auto");

            $mensaje_html_dir = preg_replace("[\\n|\\r|\\n\\r|\\t]", ' ', $mensaje_html_dir);

            $mensaje_txt_dir = preg_replace('/\s+/', ' ', $mensaje_html_dir);
            $mensaje_txt_dir = strip_tags($mensaje_txt_dir);
            if ($this->_quotedPrintable === true) {
                $mensaje_txt_dir = str_replace("=", "=3D", $mensaje_txt_dir);
                $mensaje_txt_dir = trim(wordwrap($mensaje_txt_dir, 73, " =\r\n", true));
            }
        }

        if (count($this->_adjuntoNombre) > 0) {
            $mensaje .= "--" . $this->_mimeBoundaryAdjunto() . "\r\n";
            $mensaje .= "Content-Type: multipart/alternative;\r\n";
            $mensaje .= " boundary=\"" . $this->_mimeBoundary() . "\"\r\n\r\n";
        }

        // mensaje texto
        $mensaje .= "--" . $this->_mimeBoundary() . "\r\n";
        $mensaje .= "Content-Type: text/plain; charset=ISO-8859-1; format=flowed\r\n";
        $mensaje .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        $mensaje .= $mensaje_txt . "\r\n";
        $mensaje .= $mensaje_txt_dir . "\r\n\r\n";

        // mensaje HTML
        $mensaje .= "--" . $this->_mimeBoundary() . "\r\n";
        if (count($this->_imagenIncrustadaNombre) > 0) {
            $mensaje .= "Content-Type: multipart/related;\r\n";
            $mensaje .= " boundary=\"" . $this->_mimeBoundaryImgIncrustada() . "\"\r\n\r\n";
            $mensaje .= "\r\n\r\n";
            $mensaje .= "--" . $this->_mimeBoundaryImgIncrustada() . "\r\n";
        }
        $mensaje .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

        if ($this->_quotedPrintable === false) {
            $mensaje .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
        } else {
            $mensaje .= "Content-Transfer-Encoding: quoted-printable\r\n\r\n";
        }

        $mensaje_html = '<html>';
        $mensaje_html .= '<head>';
        $mensaje_html .= '<meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1" />';
        $mensaje_html .= '</head>';
        if ($contine_body === false) {
            $mensaje_html .= '<body>';
        }
        $mensaje_html .= $mensaje_html_dir;

        if (isset($_SERVER['KIRKE_DEV']) && ($_SERVER['KIRKE_DEV'] != '')) {
            $mensaje_kirke = '<br /><br />';
            $mensaje_kirke .= '---------------------------------------------------------------------------------------------------------------------------------------------<br />';
            $mensaje_kirke .= 'Este es un mail de prueba, ante cualquier problema, por favor informe a info@kirke.ws<br />';
            $mensaje_kirke .= 'Desarrollo: ' . $_SERVER['HTTP_HOST'] . '.<br />';
            $mensaje_kirke .= '---------------------------------------------------------------------------------------------------------------------------------------------<br />';
            if ($contine_body === false) {
                $mensaje_html .= $mensaje_kirke;
            } else {
                $mensaje_html = str_replace('</body>', $mensaje_kirke . '</body>', $mensaje_html);
            }
        }

        if ($contine_body === false) {
            $mensaje_html .= '</body>';
        }
        $mensaje_html .= '</html>';

        $mensaje_html = preg_replace('/\s+/', ' ', $mensaje_html);

        if ($this->_quotedPrintable === false) {

            $saltos_de_linea = array(
                '</td>' => "</td>\r\n",
                '</tr>' => "</tr>\r\n",
                '</table>' => "</table>\r\n",
                '</div>' => "</div>\r\n",
            );
            $mensaje .= strtr($mensaje_html, $saltos_de_linea);
        } else {

            $mensaje_html = trim(chunk_split($mensaje_html, 60, "\n"));
            $mensaje_html = explode("\n", $mensaje_html);

            // el siguiente codigo quita el punto inicial de las lineas y los agrega al final de la linea anterior
            // ya que sino genera problemas por otro lado se reemplazan los = por =3D, y se inseta un igual como
            // salto de linea
            $quitar_punto = false;
            foreach ($mensaje_html as $key => $value) {
                if ($quitar_punto === true) {
                    $value = substr($value, 1);
                    $quitar_punto = false;
                }
                if (isset($mensaje_html[$key + 1]) && substr($mensaje_html[$key + 1], 0, 1) == '.') {
                    $value = $value . '.';
                    $quitar_punto = true;
                }
                $mensaje_html_linea[] = str_replace("=", "=3D", $value);
            }

            $mensaje_html = implode("=\r\n", $mensaje_html_linea);
            $mensaje .= $mensaje_html . "\r\n";
        }

        // mensaje imagen incrustada / imagenes codificadas
        $mensaje .= $this->_imagenesIncrustadasArmar();

        if (count($this->_imagenIncrustadaNombre) > 0) {
            $mensaje .= "\r\n\r\n--" . $this->_mimeBoundaryImgIncrustada() . "--";
        }

        // mensaje HTML fin
        $mensaje .= "\r\n\r\n--" . $this->_mimeBoundary() . "--\r\n\r\n";

        // mensaje adjunto
        $mensaje .= $this->_adjuntosArmar();

        return $mensaje;
    }

    private function _mimeBoundary() {

        if (!isset($this->_mimeBoundary)) {
            $this->_mimeBoundary = md5(date('r', time()));
        }
        return $this->_mimeBoundary;
    }

    private function _mimeBoundaryAdjunto() {

        if (!isset($this->_mimeBoundaryAdjunto)) {
            $this->_mimeBoundaryAdjunto = md5(date('r', time() + 100));
        }
        return $this->_mimeBoundaryAdjunto;
    }

    private function _mimeBoundaryImgIncrustada() {

        if (!isset($this->_mimeBoundaryImgIncrustada)) {
            $this->_mimeBoundaryImgIncrustada = md5(date('r', time() + 200));
        }
        return $this->_mimeBoundaryImgIncrustada;
    }

    private function _cabeceras() {

        $cabeceras = "From: " . $this->_servidorNombre . " <" . $this->_servidorMail . ">\r\n";
        $cabeceras .= "Subject: " . $this->_asunto . "\r\n";
        $cabeceras .= "MIME-Version: 1.0\r\n";
        $mailDestinatario = explode(', ', $this->_mailDestinatario);
        $cabeceras .= "To: " . trim($mailDestinatario[1]) . "\r\n";
        $cabeceras .= "Reply-To: " . $this->_mailRespuestaObtener() . "\r\n";

        if (
                isset($this->_mailDestinatarioCc) && $this->_mailDestinatarioCc != ''
        ) {
            $cabeceras .= "Cc: " . substr($this->_mailDestinatarioCc, 1) . "\r\n";
        }
        if (
                isset($this->_mailDestinatarioCco) && $this->_mailDestinatarioCco != ''
        ) {
            $cabeceras .= "Bcc: " . substr($this->_mailDestinatarioCco, 1) . "\r\n";
        }

        if (count($this->_adjuntoNombre) > 0) {
            $cabeceras .= "Content-Type: multipart/mixed;\r\n";
            $cabeceras .= " boundary=\"" . $this->_mimeBoundaryAdjunto() . "\"\r\n\r\n";
        } else {
            $cabeceras .= "Content-Type: multipart/alternative;\r\n";
            $cabeceras .= " boundary=\"" . $this->_mimeBoundary() . "\"\r\n\r\n";
        }

        return $cabeceras;
    }

    private function _mailRespuestaObtener() {
        if (
                isset($this->_mailRespuesta) && $this->_mailRespuesta != ''
        ) {
            return $this->_mailRespuesta;
        } else {
            return $this->_servidorMail;
        }
    }

    private function _adjuntosArmar() {

        if (count($this->_adjuntoNombre) > 0) {
            $adjunto_mail = '';
            foreach ($this->_adjuntoNombre as $v => $valor) {

                $adjunto_mail .= "--" . $this->_mimeBoundaryAdjunto() . "\r\n";
                $adjunto_mail .= "Content-Type: " . $this->_adjuntoTipo[$v] . "; name=\"" . $this->_adjuntoNombre[$v] . "\"\r\n";
                $adjunto_mail .= "Content-Transfer-Encoding: base64\r\n";
                $adjunto_mail .= "Content-Disposition: attachment; filename=\"" . $this->_adjuntoNombre[$v] . "\"\r\n\r\n";
                $adjunto_mail .= $this->_adjunto[$v];
            }
            $adjunto_mail .= "--" . $this->_mimeBoundaryAdjunto() . "--";

            return $adjunto_mail;
        } else {
            return false;
        }
    }

    private function _imagenesIncrustadasArmar() {

        if (count($this->_imagenIncrustadaNombre) > 0) {

            foreach ($this->_imagenIncrustadaNombre as $v => $valor) {

                $img_inc = "\r\n--" . $this->_mimeBoundaryImgIncrustada() . "\r\n";
                $img_inc .= "Content-Type: " . $this->_imagenIncrustadaTipo[$v] . "; name=\"" . $this->_imagenIncrustadaNombre[$v] . "\"\r\n";
                $img_inc .= "Content-Transfer-Encoding: base64\r\n";
                $img_inc .= "Content-ID: <" . $this->_imagenIncrustadaId[$v] . ">\r\n";
                $img_inc .= "Content-Disposition: inline; filename=\"" . $this->_imagenIncrustadaNombre[$v] . "\"\r\n\r\n";
                $img_inc .= $this->_imagenIncrustadaCod[$v];
            }

            return $img_inc;
        } else {
            return false;
        }
    }

//========================================================================================================================

    private function _autentificacion($mailDestinatario, $asunto, $mensaje, $cabeceras) {

        set_time_limit(0);

        $mailDestinatarioPrimero = explode(" ,", $mailDestinatario);
        $para = $mailDestinatarioPrimero[0];

        $SMTPIN = fsockopen($this->_servidor, $this->_puerto, $errno, $errstr, 30);

        if ($SMTPIN) {

            $usuario = base64_encode($this->_usuario);
            $clave = base64_encode($this->_clave);
            $rn = "\r\n";

            // verifica si es trabajamos desde un navegador o linea de comando (cron)
            $host = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : 'CLI';
            fwrite($SMTPIN, "EHLO " . $host . $rn);
            $talk["hello"] = fgets($SMTPIN);

            fwrite($SMTPIN, "AUTH LOGIN" . $rn);
            $talk["res"] = fgets($SMTPIN);

            fwrite($SMTPIN, $usuario . $rn);
            $talk["user"] = fgets($SMTPIN);

            fwrite($SMTPIN, $clave . $rn);
            $talk["pass"] = fgets($SMTPIN);

            fwrite($SMTPIN, "MAIL FROM: <" . $this->_servidorMail . ">" . $rn);
            $talk["From"] = fgets($SMTPIN);

            fwrite($SMTPIN, "RCPT TO: <" . $para . ">" . $rn);
            $talk["To"] = fgets($SMTPIN);

            fwrite($SMTPIN, "DATA" . $rn);
            $talk["data"] = fgets($SMTPIN);

            // armado del mensaje
            fwrite($SMTPIN, $cabeceras . $mensaje . $rn . '.' . $rn);
            $talk["send"] = fgets($SMTPIN);

            if ($this->_controlAutentificar == 's') {
                echo $rn;
                echo 'Datos de conexion:' . $rn;
                echo 'Servidor: ' . $this->_servidor . $rn;
                echo 'Puerto: ' . $this->_puerto . $rn;
                echo 'Usuario: ' . $this->_usuario . $rn;
                echo 'Clave: ' . $this->_clave . $rn;
                echo $rn;
                echo 'Estado de conexion:' . $rn;
                print_r($talk);
                echo $rn;
                echo 'Control de conexion:' . $rn;
            }
            // cierre de conexion

            fwrite($SMTPIN, "QUIT" . $rn);

            $intento = 0;
            while (fgets($SMTPIN) !== false) {

                if ($this->_controlAutentificar == 's') {
                    echo 'Control: ' . $intento . $rn;
                    $intento++;
                }
            }

            fclose($SMTPIN);
        } else {

            if ($this->_controlAutentificar == 's') {
                echo $rn . $errstr . ' (' . $errno . ')' . $rn;
            }
        }

        return $talk;
    }

    private function armarTemplate() {

        $plantilla_archivo = $this->_obtenerNombreTpl($this->_nombreArchivo);

        if (file_exists($plantilla_archivo)) {

            $plantilla = file_get_contents($plantilla_archivo, FILE_USE_INCLUDE_PATH);

            $this->_contenido = $plantilla;
        }
    }

    private function _PlantillaReemplazos() {

        $this->_var_tpl_glob = VariableControl::getArrayGlobales();

        if ((!isset($this->_nombreArchivo)) && (!isset($this->_html))) {

            return false;
        }

        $tpl_mail = new PlantillaReemplazos();

        $tpl_mail->contenidoArchivo($this->_contenido . $this->_html);

        $plantilla = $tpl_mail->obtenerPlantilla(false);

        $plantilla = "\$pn = '" . str_replace(array('<?=', ')?>', '<?php } } ?>', '<?php', '{ ?>', '} ?>'), array("'; \$pn .= ", ").'", "';}} ; \$pn .= '", "'; ", "{ \$pn .= '", "} \$pn .= '"), $plantilla) . "';";

        eval($plantilla);

        return $pn;
    }

    private function _reemplazoImagenIncrustadas($coincidencias) {

        $patron = "/([\$]{0,2})([#]{0,1})([a-zA-Z0-9._.]+)(.*)|(.*?)/";

        preg_match_all($patron, $coincidencias[2], $var_2, PREG_SET_ORDER);
        preg_match_all($patron, $coincidencias[1], $var_1, PREG_SET_ORDER);

        $separador = '';
        if ($coincidencias[2] != '') {
            $separador = '/';
        }

        $img = $this->_reemplazoVariable($var_2) . $separador . $this->_reemplazoVariable($var_1);

        return $this->_imagenIncrustada($img);
    }

    private function _reemplazoVariable($variable) {

        if ($variable[0][1] == '') {
            return $variable[0][0];
        } elseif ($variable[0][1] == '$') {
            eval('$var = @$this->_modificadores(@$this->_var_tpl["' . $variable[0][3] . '"]' . $variable[0][4] . ', "");');
            return @$this->_modificadores($var, '');
        } elseif ($variable[0][1] == '$#') {
            eval('$var = @$this->_modificadores(@$this->_var_tpl_glob["' . $variable[0][3] . '"]' . $variable[0][4] . ', "");');
            return @$this->_modificadores($var, '');
        } elseif ($variable[0][1] == '$$') {
            $variable_nva = substr($variable[0][0], 1);
            eval('$var = @$this->_modificadores(' . $variable_nva . ', "");');
            return @$this->_modificadores($var, '');
        }
    }

}
