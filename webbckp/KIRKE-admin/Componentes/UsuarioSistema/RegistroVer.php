<?php

class Componentes_UsuarioSistema_RegistroVer extends Armado_Plantilla {

    private $_nombreComponente;
    private $_valor;
    private $_metodo;
    private $_dcp = array();
    private $_idComponente;
    private $_idRegistro;
    private $_campoAncho = array();
    private $_formatoFecha;

    function __construct() {
        $this->_nombreComponente = Generales_ObtenerNombreComponente::get(__FILE__);
    }

    public function set($datos) {
        $this->_valor = $datos[0];
        $this->_metodo = $datos[1];
        $this->_dcp = $datos[2];
        $this->_idComponente = $datos[3];
        $this->_idRegistro = $datos[4];
        $_pv = Componentes_Componente::componente($this->_nombreComponente, 'ParametrosValores');
        $this->_dcp = array_merge($_pv, $this->_dcp);
    }

    public function get() {
        $metodo = '_' . $this->_metodo;
        return $this->$metodo();
    }

    private function _registroValor() {
        return $this->_valor;
    }

    private function _registroListadoCabezal() {
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden';
        }else{
            $ocultar_celulares = '';
        }
        
        return Armado_RegistroListadoCabezal::armado($this->_dcp['cp_id'], $this->_dcp['tb_campo'], $this->_dcp['idioma_' . Generales_Idioma::obtener()], $ocultar_celulares);
    }

    private function _registroListadoCuerpo() {
        if ($this->_valor) {
            $usuario_datos = $this->_obtenerUsuario($this->_valor);
            $nombre_apellido = $usuario_datos['apellido'] . ', ' . $usuario_datos['nombre'];
            if (strlen($nombre_apellido) > 22) {
                $nombre_apellido_mostrar = substr($nombre_apellido, 0, 22) . '...';
            } else {
                $nombre_apellido_mostrar = $nombre_apellido;
            }
            $usuario = '<span title="' . $usuario_datos['id'] . ' - ' . $usuario_datos['apellido'] . ', ' . $usuario_datos['nombre'] . '">' . $nombre_apellido_mostrar . '</span>';
        } else {
            $usuario = '<span class="texto_claro">&lt; {TR|o_sin_usuario} &gt;</span>';
        }
        
        if (!isset($this->_dcp['ocultar_celulares'])) {
            $ocultar_celulares = ' kk_resp_hidden"';
        }else{
            $ocultar_celulares = '';
        }
        
        return '<td class="columna'.$ocultar_celulares.'">' . $usuario . '</td>';
    }

    private function _registroListadoPie() {
        if (!isset($this->_dcp['ocultar_celulares'])) {
            return '<td class="columna kk_resp_hidden">&nbsp;</td>';
        }
        return false;
    }

    private function _registroVer() {

        if (!isset($this->_dcp['ocultar_vista']) || ($this->_dcp['ocultar_vista'] == 'n')) {

            if ($this->_valor) {
                $usuario_datos = $this->_obtenerUsuario($this->_valor);
                $nombre_apellido = $usuario_datos['apellido'] . ', ' . $usuario_datos['nombre'];
                if (strlen($nombre_apellido) > 22) {
                    $nombre_apellido_mostrar = substr($nombre_apellido, 0, 22) . '...';
                } else {
                    $nombre_apellido_mostrar = $nombre_apellido;
                }
                $usuario = '<span title="' . $usuario_datos['id'] . ' - ' . $usuario_datos['apellido'] . ', ' . $usuario_datos['nombre'] . '">' . $nombre_apellido_mostrar . '</span>';
            } else {
                $usuario = '<span class="texto_claro">{TR|o_sin_usuario}</span>';
            }
            return $this->_tituloYComponente($usuario);
        } else {

            return '';
        }
    }

    private function _registroAlta() {
        return false;
    }

    private function _registroAltaPrevia() {
        return false;
    }

    private function _registroModificacion() {
        return false;
    }

    private function _registroModificacionPrevia() {
        return false;
    }

    private function _registroFiltroCampo() {

        if (isset($this->_dcp['filtrar']) && ($this->_dcp['filtrar'] == 's')) {

            if (isset($this->_valor['condicion'])) {
                $condicion = $this->_valor['condicion'];
            } else {
                $condicion = '';
            }

            $template = '
                <td><div class="filtros_texto">' . $this->_dcp['idioma_' . Generales_Idioma::obtener()] . '</div>
                <input name="parametro_' . $this->_dcp['cp_id'] . '" id="parametro_' . $this->_dcp['cp_id'] . '" type="hidden" />
                </td>
                <td>' . $this->_registroFiltroCampoOpciones($condicion) . '</td>
                <td></td>
                <td><div class="bt_tb_eliminar_filtro" filtro_eliminar_id="' . $this->_dcp['cp_id'] . '"></div></td>
            ';

            return $template;
        } else {

            return false;
        }
    }

    private function _registroFiltroCampoOpciones($condicion) {

        $descripciones[0] = '{TR|o_nulo}';
        $descripciones[1] = '{TR|o_no_nulo}';

        $valores[0] = 'nulo';
        $valores[1] = 'no_nulo';

        return Armado_SelectFiltros::armado($this->_dcp['cp_id'], $valores, $descripciones, $condicion);
    }

    // metodos especiales
    private function _tituloYComponente($mostrar) {

        $plantilla['mostrar'] = $mostrar;
        $plantilla['idioma_generales_idioma'] = $this->_dcp['idioma_' . Generales_Idioma::obtener()];
        
        if(Armado_DesplegableOcultos::mostrarOcultos()===true){
            $plantilla['ocultar'] = '<div id_ocultar_cp="' . $this->_dcp['cp_id'] . '" class="ocultos_ocultar">{TR|m_ocultar}</div>';
        }
        
        $plantilla['cp_id'] = $this->_dcp['cp_id'];

        return Armado_PlantillasInternas::componentes('registro', $this->_nombreComponente, $plantilla);
    }

    private function _obtenerUsuario($id_usuario) {

        $usuario_datos = Consultas_Usuario::RegistroConsulta(__FILE__, __LINE__, $id_usuario, 'id_usuario,nombre,apellido');

        $usuario['id'] = $usuario_datos[0]['id_usuario'];
        $usuario['nombre'] = $usuario_datos[0]['nombre'];
        $usuario['apellido'] = $usuario_datos[0]['apellido'];

        return $usuario;
    }

}
