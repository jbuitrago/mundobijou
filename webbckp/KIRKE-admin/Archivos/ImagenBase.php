<?php

class Archivos_ImagenBase {

    private $_dcp;

    public function set($parametros) {

        $this->_dcp = $parametros;
        $this->_armado();
    }

    private function _armado() {

        if ($_GET['tabla_tipo'] == 'temporaria') {

            $foto = Consultas_Tmp::RegistroConsulta(__FILE__, __LINE__, $_GET['id_registro'], $this->_dcp['cp_id']);

            $contenido = $foto['0']['contenido'];
        } elseif ($_GET['tabla_tipo'] == 'especifica') {

            // si existe $_GET['id_registro'] quiere decir que es una tabla de registros
            if ($_GET['id_registro'] != '') {

                $tabla_consulta = $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'];

                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas($tabla_consulta);
                $consulta->campos($tabla_consulta, $this->_dcp['tb_campo']);
                $consulta->condiciones('', $tabla_consulta, 'id_' . $tabla_consulta, 'iguales', '', '', $_GET['id_registro']);
                $foto = $consulta->realizarConsulta();

                $contenido = $foto['0'][$this->_dcp['tb_campo']];
            } else {

                $tabla = $this->_dcp['tb_prefijo'] . '_' . $this->_dcp['tb_nombre'];

                $consulta = new Bases_RegistroConsulta(__FILE__, __LINE__);
                $consulta->tablas($tabla);
                $consulta->campos($tabla, 'valores');
                $consulta->condiciones('', $tabla, 'variables', 'iguales', '', '', $_GET['variables']);
                $foto = $consulta->realizarConsulta();

                $contenido = $foto['0']['valores'];
            }
        }

        $contenido_registro = explode(";", $contenido);

        switch ($contenido_registro[0]) {
            case 1:
                Header("Content-Type: image/gif");
                break;
            case 2:
                Header("Content-Type: image/jpg");
                break;
            case 3:
                Header("Content-Type: image/png");
                break;
        }

        if ($_GET['tipo_imagen'] == 'original') {
            echo base64_decode($contenido_registro[2]);
        } elseif ($_GET['tipo_imagen'] == 'muestra') {
            echo base64_decode($contenido_registro[3]);
        }
    }

}
