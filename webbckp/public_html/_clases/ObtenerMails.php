<?php

/*
  Se debe instalar php5-imap
 * En debian: apt-get install php5-imap
 * 
 * Todas las fechas deben ser enviadas en formato UNIX
 */

class ObtenerMails {

    private $_servidor;
    private $_servidor_puerto;
    private $_servidor_directorio;
    private $_usuario;
    private $_clave;
    private $_obtener_leido = false;
    private $_obtener_cuerpo = false;
    private $_obtener_cuerpo_html = false;
    private $_obtener_cuerpo_texto = false;
    private $_obtener_cuerpo_multipart = false;
    private $_obtener_asunto = false;
    private $_obtener_remitente = false;
    private $_obtener_destinatario = false;
    private $_obtener_fecha = false;
    private $_obtener_id = false;
    private $_obtener_id_referencia = false;
    private $_obtener_respuesta_id = false;
    private $_obtener_tamano = false;
    private $_obtener_uid = false;
    private $_obtener_msgno = false;
    private $_obtener_reciente = false;
    private $_obtener_marcado = false;
    private $_obtener_respondido = false;
    private $_obtener_eliminado = false;
    private $_obtener_borrador = false;
    private $_buscar_todos = false;
    private $_buscar_fecha = false; // fecha UNIX
    private $_buscar_fecha_desde = false; // fecha UNIX
    private $_buscar_fecha_hasta = false; // fecha UNIX
    private $_buscar_nuevos = false;
    private $_buscar_viejos = false;
    private $_buscar_texto = false; // cadena
    private $_buscar_en_asunto = false; // cadena
    private $_buscar_en_cuerpo = false; // cadena
    private $_buscar_en_from = false; // cadena
    private $_buscar_en_to = false; // cadena
    private $_buscar_en_cc = false; // cadena
    private $_buscar_en_bcc = false; // cadena
    private $_buscar_no_leidos = false;
    private $_buscar_no_respondidos = false;
    private $_buscar_no_eliminados = false;
    private $_buscar_eliminados = false;
    private $_buscar_bandera_respondidos = false;
    private $_buscar_bandera_de_marca = false;
    private $_buscar_bandera_reciente = false;
    private $_buscar_bandera_leidos = false;
    private $_buscar_sin_bandera = false;
    private $_buscar_clave_coincide = false; // cadena
    private $_buscar_clave_no_coincide = false; // cadena
    private $_control = false;

    /* Opciones de conexion:
      Local1 {localhost:993/imap/ssl/novalidate-cert}INBOX
      Local2 {127.0.0.1:110/pop3/notls}INBOX
      Gmail {imap.gmail.com:993/imap/ssl}INBOX
      Yahoo {imap.mail.yahoo.com:993/imap/ssl}INBOX
      AOL {imap.aol.com:993/imap/ssl}INBOX
      Dreamhost {mail.kirke.ws:143/notls}INBOX
     */

    public function servidor($nombre) {
        $this->_servidor = $nombre;
    }

    public function servidorPuerto($servidor_puerto) {
        $this->_servidor_puerto = $servidor_puerto;
    }

    public function servidorDirectorio($servidor_directorio) {
        $this->_servidor_directorio = $servidor_directorio;
    }

    public function clave($clave) {
        $this->_clave = $clave;
    }

    public function usuario($usuario) {
        $this->_usuario = $usuario;
    }

    public function obtenerLeido() {
        $this->_obtener_leido = true;
    }

    public function obtenerCuerpo($numero = '1') {
        /*
          0 - Message header
          1 - MULTIPART/ALTERNATIVE
          1.1 - TEXT/PLAIN
          1.2 - TEXT/HTML
          2 - file.ext
         */
        $this->_obtener_cuerpo = $numero;
    }

    public function obtenerAsunto() {
        $this->_obtener_asunto = true;
    }

    public function obtenerRemitente() {
        $this->_obtener_remitente = true;
    }

    public function obtenerFecha() {
        $this->_obtener_fecha = true;
    }

    public function obtenerDestinatario() {
        $this->_obtener_destinatario = true;
    }

    public function obtenerID() {
        $this->_obtener_i = true;
    }

    public function obtenerIdReferencia() {
        $this->_obtener_id_referencia = true;
    }

    public function obtenerRespuestaID() {
        $this->_obtener_respuesta_id = true;
    }

    public function obtenerTamano() {
        $this->_obtener_tamano = true;
    }

    public function obtenerUID() {
        $this->_obtener_uid = true;
    }

    public function obtenerMSGNO() {
        $this->_obtener_msgno = true;
    }

    public function obtenerReciente() {
        $this->_obtener_reciente = true;
    }

    public function obtenerMarcado() {
        $this->_obtener_marcado = true;
    }

    public function obtenerRespondido() {
        $this->_obtener_respondido = true;
    }

    public function obtenerEliminado() {
        $this->_obtener_eliminado = true;
    }

    public function obtenerBorrador() {
        $this->_obtener_borrador = true;
    }

    public function buscarTodos() {
        $this->_buscar_todos = true;
    }

    public function buscarFecha($fecha_unix) {
        $this->_buscar_fecha = $fecha_unix;
    }

    public function buscarFechaDesde($fecha_unix) {
        $this->_buscar_fecha_desde = $fecha_unix;
    }

    public function buscarFechaHasta($fecha_unix) {
        $this->_buscar_fecha_hasta = $fecha_unix;
    }

    public function buscarNuevos() {
        $this->_buscar_nuevos = true;
    }

    public function buscarViejos() {
        $this->_buscar_viejos = true;
    }

    public function buscarTexto($texto) {
        $this->_buscar_texto = $texto;
    }

    public function buscarAsunto($texto) {
        $this->_buscar_en_asunto = $texto;
    }

    public function buscarEnCuerpo($texto) {
        $this->_buscar_en_cuerpo = $texto;
    }

    public function buscarEnFrom($texto) {
        $this->_buscar_en_from = $texto;
    }

    public function buscarEnTo($texto) {
        $this->_buscar_en_to = $texto;
    }

    public function buscarEnCC($texto) {
        $this->_buscar_en_cc = $texto;
    }

    public function buscarEnBCC($texto) {
        $this->_buscar_en_bcc = $texto;
    }

    public function buscarNoLeidos() {
        $this->_buscar_no_leidos = true;
    }

    public function buscarNoRespondidos() {
        $this->_buscar_no_respondidos = true;
    }

    public function buscarNoEliminados() {
        $this->_buscar_no_eliminados = true;
    }

    public function buscarEliminados() {
        $this->_buscar_eliminados = true;
    }

    public function buscarBanderaRespondidos() {
        $this->_buscar_bandera_respondidos = true;
    }

    public function buscarBanderaDeMarca() {
        $this->_buscar_bandera_de_marca = true;
    }

    public function buscarBanderaRecientes() {
        $this->_buscar_bandera_reciente = true;
    }

    public function buscarBanderaLeidos() {
        $this->_buscar_bandera_leidos = true;
    }

    public function buscarSinFecha() {
        $this->_buscar_sin_bandera = true;
    }

    public function buscarClaveCoincide($texto) {
        $this->_buscar_clave_coincide = $texto;
    }

    public function buscarClaveNoCoincide($texto) {
        $this->_buscar_clave_no_coincide = $texto;
    }

    public function control() {
        $this->_control = true;
    }

    public function obtenerDatos() {

        $conexcion = imap_open('{' . $this->_servidor . ':' . $this->_servidor_puerto . '/' . $this->_servidor_directorio . '}INBOX', $this->_usuario, $this->_clave);

        $buscar_mails = '';

        if ($this->_buscar_todos == true) {
            $buscar_mails .= ' ALL';
        }

        // Busqueda por fecha

        if ($this->_buscar_fecha !== false) {
            $buscar_mails .= ' ON "' . date("j F Y", $this->_buscar_fecha) . '"';
        }
        if ($this->_buscar_fecha_desde !== false) {
            $buscar_mails .= ' SINCE "' . date("j F Y", $this->_buscar_fecha_desde) . '"';
        }
        if ($this->_buscar_fecha_hasta !== false) {
            $buscar_mails .= ' BEFORE "' . date("j F Y", $this->_buscar_fecha_hasta) . '"';
        }

        // Busqueda nuevos o viejos

        if ($this->_buscar_nuevos == true) {
            $buscar_mails .= ' NEW';
        }
        if ($this->_buscar_viejos == true) {
            $buscar_mails .= ' OLD';
        }

        // Busqueda por contenido

        if ($this->_buscar_texto !== false) {
            $buscar_mails .= ' TEXT "' . $this->_buscar_texto . '"';
        }
        if ($this->_buscar_en_asunto !== false) {
            $buscar_mails .= ' SUBJECT "' . $this->_buscar_en_asunto . '"';
        }
        if ($this->_buscar_en_cuerpo !== false) {
            $buscar_mails .= ' BODY "' . $this->_buscar_en_cuerpo . '"';
        }
        if ($this->_buscar_en_from !== false) {
            $buscar_mails .= ' FROM "' . $this->_buscar_en_from . '"';
        }
        if ($this->_buscar_en_to !== false) {
            $buscar_mails .= ' TO "' . $this->_buscar_en_to . '"';
        }
        if ($this->_buscar_en_cc !== false) {
            $buscar_mails .= ' CC "' . $this->_buscar_en_cc . '"';
        }
        if ($this->_buscar_en_bcc !== false) {
            $buscar_mails .= ' BCC "' . $this->_buscar_en_bcc . '"';
        }

        // Busqueda por estado

        if ($this->_buscar_no_leidos == true) {
            $buscar_mails .= ' UNSEEN';
        }
        if ($this->_buscar_no_respondidos == true) {
            $buscar_mails .= ' UNANSWERED';
        }
        if ($this->_buscar_no_eliminados == true) {
            $buscar_mails .= ' UNDELETED';
        }
        if ($this->_buscar_eliminados == true) {
            $buscar_mails .= ' DELETED';
        }

        // Busqueda con banderas

        if ($this->_buscar_bandera_respondidos == true) {
            $buscar_mails .= ' ANSWERED';
        }
        if ($this->_buscar_bandera_de_marca == true) {
            $buscar_mails .= ' FLAGGED';
        }
        if ($this->_buscar_bandera_reciente == true) {
            $buscar_mails .= ' RECENT';
        }
        if ($this->_buscar_bandera_leidos == true) {
            $buscar_mails .= ' SEEN';
        }
        if ($this->_buscar_sin_bandera == true) {
            $buscar_mails .= ' UNFLAGGED';
        }

        // Busqueda con palabras claves

        if ($this->_buscar_clave_coincide !== false) {
            $buscar_mails .= ' KEYWORD "' . $this->_buscar_clave_coincide . '"';
        }
        if ($this->_buscar_clave_no_coincide !== false) {
            $buscar_mails .= ' UNKEYWORD "' . $this->_buscar_clave_no_coincide . '"';
        }

        $mails = imap_search($conexcion, $buscar_mails);

        if ($mails) {

            $mails_array = array();
            $i = 0;

            foreach ($mails as $mail_datos) {

                if ($this->_obtener_cuerpo !== false) {

                    if (
                            ($this->_obtener_cuerpo == '0') ||
                            ($this->_obtener_cuerpo == '1') ||
                            ($this->_obtener_cuerpo == '1.1') ||
                            ($this->_obtener_cuerpo == '1.2') ||
                            ($this->_obtener_cuerpo == '2')
                    ) {

                        $mails_array[$i]['cuerpo'] = imap_fetchbody($conexcion, $mail_datos, $this->_obtener_cuerpo);
                    }
                }

                $datos_mail = imap_fetch_overview($conexcion, $mail_datos, 0);

                if ($this->_obtener_leido) {
                    $mails_array[$i]['leido'] = ($datos_mail[0]->seen ? 's' : 'n');
                }
                if ($this->_obtener_asunto) {
                    $mails_array[$i]['asunto'] = imap_utf8($datos_mail[0]->subject);
                }
                if ($this->_obtener_remitente) {
                    $mails_array[$i]['remitente'] = imap_utf8($datos_mail[0]->from);
                }
                if ($this->_obtener_destinatario) {
                    $mails_array[$i]['destinatario'] = imap_utf8($datos_mail[0]->to);
                }
                if ($this->_obtener_fecha) {
                    $mails_array[$i]['fecha'] = $datos_mail[0]->date;
                }
                if ($this->_obtener_id) {
                    $mails_array[$i]['id'] = $datos_mail[0]->message_id;
                }
                if ($this->_obtener_id_referencia) {
                    $mails_array[$i]['id_referencia'] = $datos_mail[0]->references;
                }
                if ($this->_obtener_respuesta_id) {
                    $mails_array[$i]['respuesta_id'] = $datos_mail[0]->in_reply_to;
                }
                if ($this->_obtener_tamano) {
                    $mails_array[$i]['tamano'] = $datos_mail[0]->size;
                }
                if ($this->_obtener_uid) {
                    $mails_array[$i]['uid'] = $datos_mail[0]->uid;
                }
                if ($this->_obtener_msgno) {
                    $mails_array[$i]['msgno'] = $datos_mail[0]->msgno;
                }
                if ($this->_obtener_reciente) {
                    $mails_array[$i]['reciente'] = $datos_mail[0]->recent;
                }
                if ($this->_obtener_marcado) {
                    $mails_array[$i]['marcado'] = $datos_mail[0]->flagged;
                }
                if ($this->_obtener_respondido) {
                    $mails_array[$i]['respondido'] = $datos_mail[0]->answered;
                }
                if ($this->_obtener_eliminado) {
                    $mails_array[$i]['eliminado'] = ($datos_mail[0]->deleted ? 's' : 'n');
                }
                if ($this->_obtener_leido) {
                    $mails_array[$i]['leido'] = $datos_mail[0]->seen;
                }
                if ($this->_obtener_borrador) {
                    $mails_array[$i]['borrador'] = $datos_mail[0]->draft;
                }

                $i++;
            }
        } else {

            $mails_array = false;
        }

        if ($this->_control === true) {
            echo imap_errors();
            echo imap_alerts();
        } else {
            imap_errors();
            imap_alerts();
        }

        imap_close($conexcion);

        return $mails_array;
    }

}
