<?php

class ArmadoFormularioAutomatico extends PlantillaReemplazos {

    private $_id_tabla;
    private $_tabla_nombre;
    private $_componentes = array();
    private $_componentes_habilitados = array('001-CampoTexto', '002-CampoTextoLargo', '006-Desplegable');
    private $_componente_inhabilitado = array();
    private $_componentes_elementos = array();
    private $_idioma = 'cs';
    private $_armado_tr_form = '';
    private $_urlGracias = '';
    private $_captcha = true;
    private $_cargarEnBaseMostrarErrores = 'n';
    private $m_envio_mail = false;
    private $m_servidor_mail;
    private $m_servidor_nombre;
    private $m_mail_respuesta;
    private $m_asunto;
    private $m_mail_destinatario = array();
    private $m_mail_destinatario_cc = array();
    private $m_mail_destinatario_cco = array();
    private $m_ver_codigo = false;

    function __construct($id_tabla) {

        $this->_id_tabla = $id_tabla;
    }

    public function componenteInhabilitado($id_componente) {
        $this->_componente_inhabilitado[] = $id_componente;
    }

    public function urlGracias($url) {
        $this->_urlGracias = $url;
    }

    public function sinCaptcha() {
        $this->_captcha = false;
    }

    public function cargarEnBaseMostrarErrores() {
        $this->_cargarEnBaseMostrarErrores = 's';
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
        $this->m_asunto['asunto'] = $asunto;
        $this->m_asunto['asunto_matriz_reemplazo'] = $asunto_matriz_reemplazo;
    }

    public function verCodigo() {
        $this->m_ver_codigo = true;
    }

    public function obtenerFormulario() {

        $this->_armarConsulta();
        $this->_armarArray();
        $armado_tabla = '<link rel="stylesheet" type="text/css" href="/css/frm_estilos.css">';
        $armado_tabla .= '<form method="post" name="frm_' . $this->_tabla_nombre . '" id="frm_' . $this->_tabla_nombre . '" class="frm_formk" enctype="multipart/form-data" action="' . $_SERVER['REQUEST_URI'] . '" ><input name="kk_control_form" type="hidden" value="frm_' . $this->_tabla_nombre . '">';
        $armado_tabla .= '<table class="frm_table">';
        $armado_tabla .= $this->_armado_tr_form;
        if ($this->_captcha === true) {

            $captcha = new ArmadoCaptcha();
            $captcha->formulario('frm_' . $this->_tabla_nombre);
            $captcha_img = $captcha->mostrar();

            $armado_tabla .= '
                <tr class="frm_tr_captcha">
                <td class="frm_td_captcha" colspan="2">
                ' . $captcha_img . '
                <input id="captcha" class="frm_captcha_input" type="text" value="" name="captcha">
                <div id="VC_captcha" class="VC_error" style="display: none;"></div>
                </td>
                </tr>
            ';
        }
        $armado_tabla .= '<tr class="frm_tr_submit"><td class="frm_td_submit"></td><td class="frm_td_submit"><input name="frm_submit_' . $this->_tabla_nombre . '"  id="frm_submit_' . $this->_tabla_nombre . '" type="submit" class="frm_submit"/></td></tr>';
        $armado_tabla .= '</table>';
        $armado_tabla .= '</form>';
        return $armado_tabla;
    }

    private function _armarConsulta() {

        BDConexion::consulta();
        
        switch (strtolower(VariableGet::sistema('tipo_base'))) {
            case 'mysql':
                $consulta = $this->_consultaMySQL();
                $this->_componentes = BDMysqlConsulta::consulta($consulta, 'n');
                break;
            case '1':
                $this->_componentes = '';
                break;
            case '2':
                $this->_componentes = '';
                break;
        }
    }

    private function _consultaMySQL() {

        if (count($this->_componente_inhabilitado) != 0) {
            $consulta = "AND         kirke_componente.id_componente NOT IN (" . implode(',', $this->_componente_inhabilitado) . ")";
        } else {
            $consulta = '';
        }

        $consulta = "
            SELECT      kirke_tabla.tabla_nombre
            ,           kirke_tabla_prefijo.prefijo
            ,           kirke_componente.componente
            ,           kirke_componente.id_componente
            ,           kirke_componente.tabla_campo
            ,           kirke_componente_parametro.parametro
            ,           kirke_componente_parametro.valor
            FROM        kirke_tabla
            ,           kirke_tabla_prefijo
            ,           kirke_componente
            ,           kirke_componente_parametro
            WHERE       kirke_tabla.id_tabla_prefijo =  kirke_tabla_prefijo.id_tabla_prefijo
            AND         kirke_tabla.id_tabla = kirke_componente.id_tabla
            AND         kirke_componente.id_componente = kirke_componente_parametro.id_componente
            AND         kirke_tabla.id_tabla = '" . $this->_id_tabla . "'
            AND         kirke_componente.componente IN ('" . implode("','", $this->_componentes_habilitados) . "')
            " . $consulta . "
            ORDER BY    kirke_componente.orden
            ;
        ";

        return $consulta;
    }

    private function _armarArray() {
        if (count($this->_componentes) > 0) {
            $this->_tabla_nombre = $this->_componentes[0]['prefijo'] . '_' . $this->_componentes[0]['tabla_nombre'];
            $frm_automatico = new ArmadoFormulario('frm_' . $this->_tabla_nombre);          
            foreach ($this->_componentes as $id => $valores) {
                $cp_valores[$valores['parametro']] = $valores['valor'];
                if (!isset($this->_componentes[$id + 1]['id_componente']) || ($this->_componentes[$id + 1]['id_componente'] != $this->_componentes[$id]['id_componente'])) {
                    $campos_envio[] = $valores['tabla_campo'];                   
                    $cp_valores['kk_tabla_campo'] = $valores['tabla_campo'];
                    $cp_valores['kk_id_componente'] = $valores['id_componente'];
                    $funcion = '_' . str_replace('-', '_', $valores['componente']);
                    $this->$funcion($cp_valores);
                    $componente = '';
                    $frm_automatico->campo($valores['tabla_campo'], $cp_valores['idioma_' . $this->_idioma], $cp_valores['obligatorio'], $cp_valores['largo']);
                    if (isset($valores['caracteres_permitidos']) && ($valores['caracteres_permitidos'] != '')) {
                        $frm_automatico->campoValor('filtro', $valores['caracteres_permitidos']);
                    }
                    $carga_base_campos[] = $valores['tabla_campo'];
                }
            }
            
            $frm_automatico->envioMailCampos($campos_envio);
            
        } else {
            if ($desarrollo['mostrar_errores'] === true) {
                echo 'La tabla asignada no tiene componentes o no se pueden utilizar en la clase ArmadoFormularioAutomatico()';
            }
        }

        if ($this->_captcha === true) {
            $frm_automatico->campoCaptcha();
        }

        if ($this->_cargarEnBaseMostrarErrores == 's') {
            //$frm_automatico->cargarEnBaseMostrarErrores();
        }

        if ($this->m_envio_mail === true) {
            $frm_automatico->envio();
        }

        if ($this->m_servidor_mail != '') {
            $frm_automatico->servidorMail($this->m_servidor_mail);
        }

        if ($this->m_servidor_nombre != '') {
            $frm_automatico->servidorNombre($this->m_servidor_nombre);
        }

        if (count($this->m_mail_destinatario) > 0) {
            foreach($this->m_mail_destinatario as $valor){
                $frm_automatico->mailDestinatario($valor['mail'], $valor['nombre']);
            }
        }

        if (count($this->m_mail_destinatario_cc) > 0) {
            $frm_automatico->mailDestinatarioCc($this->m_mail_destinatario_cc);
        }

        if (count($this->m_mail_destinatario_cco) > 0) {
            $frm_automatico->mailDestinatarioCco($this->m_mail_destinatario_cco);
        }

        if ($this->m_mail_respuesta != '') {
            $frm_automatico->mailRespuesta($this->m_mail_respuesta);
        }

        if ($this->m_asunto['asunto'] != '') {
            $frm_automatico->asunto($this->m_asunto['asunto'], $this->m_asunto['asunto_matriz_reemplazo']);
        }

        if ($this->m_ver_codigo === true) {
            $frm_automatico->verCodigo();
        }

        $frm_automatico->cargarEnBaseTablaCampos($this->_tabla_nombre, $carga_base_campos);
        if ($frm_automatico->recepcionControl() !== false) {
            if ($this->_urlGracias != '') {
                header('Location: ' . $this->_urlGracias);
            } else {
                header('Location: /');
            }
            exit;
        }
    }

    private function _001_CampoTexto($cp) {
        $nombre = $cp['kk_tabla_campo'];
        if ($cp['obligatorio'] == 'no_nulo') {
            $error = '<div id="VC_' . $nombre . '" class="VC_error"></div>';
            $obligatorio = ' tipo="obligatorio" ';
        } else {
            $error = '';
            $obligatorio = '';
        }
        $maxlength = ' maxlength="' . $cp['largo'] . '" ';
        if ($cp['caracteres_permitidos'] != '') {
            $filtro = '<script type="text/javascript"> $(document).ready(function(){ solo_texto_permitido("' . $nombre . '","' . $cp['caracteres_permitidos'] . '") }); </script>';
        } else {
            $filtro = '';
        }
        $titulo = $cp['idioma_' . $this->_idioma];
        $elemento = '<input type="text" name="' . $nombre . '" id="' . $nombre . '" class="frm_input_text" ' . $maxlength . $obligatorio . ' etiqueta="' . $titulo . '" >' . $error . $filtro;

        $this->_armado_tr_form .= $this->fila($titulo, $elemento);
    }

    private function _002_CampoTextoLargo($cp) {
        $nombre = $cp['kk_tabla_campo'];
        if ($cp['obligatorio'] == 'no_nulo') {
            $error = '<div id="VC_' . $nombre . '" class="VC_error"></div>';
            $obligatorio = ' tipo="obligatorio" ';
        } else {
            $error = '';
            $obligatorio = '';
        }
        $maxlength = ' maxlength="' . $cp['largo'] . '" ';
        if ($cp['caracteres_permitidos'] != '') {
            $filtro = '<script type="text/javascript"> $(document).ready(function(){ solo_texto_permitido("' . $nombre . '","' . $cp['caracteres_permitidos'] . '") }); </script>';
        } else {
            $filtro = '';
        }
        $titulo = $cp['idioma_' . $this->_idioma];
        $elemento = '<textarea type="text" name="' . $nombre . '" id="' . $nombre . '" class="frm_textarea" ' . $maxlength . $obligatorio . ' etiqueta="' . $titulo . '"></textarea>' . $error . $filtro;

        $this->_armado_tr_form .= $this->fila($titulo, $elemento);
    }

    private function _006_Desplegable($cp) {

        //print_r($cp);

        $nombre = $cp['kk_tabla_campo'];
        if ($cp['obligatorio'] == 'no_nulo') {
            $error = '<div id="VC_' . $nombre . '" class="VC_error"></div>';
            $obligatorio = ' tipo="obligatorio" ';
        } else {
            $error = '';
            $obligatorio = '';
        }

        switch (strtolower(VariableGet::sistema('tipo_base'))) {
            case 'mysql':
                $consulta = $this->_consultaMySQLDesplegable($cp);
                $opciones = BDMysqlConsulta::consulta($consulta, 'n');
                break;
            case '1':
                $opciones = '';
                break;
            case '2':
                $opciones = '';
                break;
        }

        $titulo = $cp['idioma_' . $this->_idioma];

        $elemento = '<select name="' . $nombre . '" id="' . $nombre . '"  class="frm_select" ' . $obligatorio . ' style="width:' . $cp['ancho'] . 'px;">';
        if (is_array($opciones)) {
            foreach ($opciones as $valores) {
                if (isset($valores['etiqueta'])) {
                    $etiqueta = $valores['etiqueta'];
                } else {
                    $etiqueta = '';
                }
                if (isset($valores['valor'])) {
                    $valor = $valores['valor'];
                } else {
                    $valor = '';
                }

                $elemento .= '<option value="' . $valor . '" >' . $etiqueta . '</option>';
            }
        }
        $elemento .= '</select>' . $error;

        $this->_armado_tr_form .= $this->fila($titulo, $elemento);
    }

    private function _consultaMySQLDesplegable($cp) {

        $consulta = "
            SELECT      kirke_tabla.tabla_nombre
            ,           kirke_tabla_prefijo.prefijo
            ,           kirke_componente.componente
            ,           kirke_componente.tabla_campo
            FROM        kirke_tabla
            ,           kirke_tabla_prefijo
            ,           kirke_componente
            WHERE       kirke_tabla.id_tabla_prefijo =  kirke_tabla_prefijo.id_tabla_prefijo
            AND         kirke_tabla.id_tabla = kirke_componente.id_tabla
            AND         kirke_componente.id_componente = " . $cp['origen_cp_id'] . "
            ORDER BY    kirke_componente.orden
            ;
        ";

        $datos = BDMysqlConsulta::consulta($consulta, 'n');

        $consulta = "
            SELECT      " . $datos[0]['prefijo'] . "_" . $datos[0]['tabla_nombre'] . ".id_" . $datos[0]['prefijo'] . "_" . $datos[0]['tabla_nombre'] . " AS valor
            ,           " . $datos[0]['prefijo'] . "_" . $datos[0]['tabla_nombre'] . "." . $datos[0]['tabla_campo'] . " AS etiqueta
            FROM        " . $datos[0]['prefijo'] . "_" . $datos[0]['tabla_nombre'] . "
            ORDER BY    " . $datos[0]['prefijo'] . "_" . $datos[0]['tabla_nombre'] . "." . $datos[0]['tabla_campo'] . "
        ";

        return $consulta;
    }

    private function fila($titulo, $elemento) {
        return '<tr class="frm_tr"><td class="frm_td_1">' . $titulo . '</td><td class="frm_td_2">' . $elemento . '</td></tr>';
    }

}
