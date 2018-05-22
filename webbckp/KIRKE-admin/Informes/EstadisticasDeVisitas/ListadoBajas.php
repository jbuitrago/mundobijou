<?php

class Informes_EstadisticasDeVisitas_ListadoBajas extends Armado_Plantilla {

    public function armado() {

        // encabezados necesarios para el funcionamiento de los elementos de la pagina
        $this->_armadoPlantillaSet('cabeceras', Armado_Cabeceras::armado('tabla'));

        $armado_botonera = new Armado_Botonera();
        $parametros = array('kk_generar' => '4', 'informe' => 'EstadisticasDeVisitas', 'pagina' => 'Inicio');
        $armado_botonera->armar('volver', $parametros);

        $url_actual = getcwd();
        chdir(Inicio::path());
        chdir('Logs');
        $directorio = getcwd();
        chdir($url_actual);

        $matriz = glob($directorio . '/' . Inicio::confVars('basedatos') . '-B_*.log');

        $log = array();

        if (is_array($matriz)) {
            foreach ($matriz as $dir_nombre_archivo) {
                $cont = 0;
                $filas = file($dir_nombre_archivo);
                foreach ($filas as $contenido) {
                    $ce = explode('|', $contenido);
                    $log[$cont]['fecha'] = $ce[0];
                    $log[$cont]['hora'] = $ce[1];
                    $log[$cont]['id_usuario'] = $ce[3];
                    $log[$cont]['usuario'] = $ce[4];
                    $log[$cont]['navegador'] = Generales_CargaLog::navegador($ce[5]);
                    $log[$cont]['ip'] = $ce[6];
                    $log[$cont]['tabla'] = $ce[7];
                    $log[$cont]['id_registro'] = $ce[8];
                    $cont++;
                }
            }
        }

        // datos necesarios para armar la tabla:
        // columna 0
        $tabla_columnas[0]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[0]['tb_titulo_idioma'] = '{TR|o_fecha}';
        $tabla_columnas[0]['tb_columna_ancho'] = '5';
        $tabla_columnas[0]['tb_campo'] = 'fecha';
        // columna 1
        $tabla_columnas[1]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[1]['tb_titulo_idioma'] = '{TR|o_hora}';
        $tabla_columnas[1]['tb_columna_ancho'] = '';
        $tabla_columnas[1]['tb_campo'] = 'hora';
        // columna 2
        $tabla_columnas[2]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[2]['tb_titulo_idioma'] = '{TR|o_id_usuario}';
        $tabla_columnas[2]['tb_columna_ancho'] = '10';
        $tabla_columnas[2]['tb_campo'] = 'id_usuario';
        // columna 3
        $tabla_columnas[3]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[3]['tb_titulo_idioma'] = '{TR|o_usuario}';
        $tabla_columnas[3]['tb_columna_ancho'] = '10';
        $tabla_columnas[3]['tb_campo'] = 'usuario';
        // columna 4
        $tabla_columnas[4]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[4]['tb_titulo_idioma'] = '{TR|o_navegador}';
        $tabla_columnas[4]['tb_columna_ancho'] = '10';
        $tabla_columnas[4]['tb_campo'] = 'navegador';
        // columna 5
        $tabla_columnas[5]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[5]['tb_titulo_idioma'] = '{TR|t_ip}';
        $tabla_columnas[5]['tb_columna_ancho'] = '10';
        $tabla_columnas[5]['tb_campo'] = 'ip';
        // columna 6
        $tabla_columnas[6]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[6]['tb_titulo_idioma'] = '{TR|o_tabla}';
        $tabla_columnas[6]['tb_columna_ancho'] = '10';
        $tabla_columnas[6]['tb_campo'] = 'tabla';
        // columna 7
        $tabla_columnas[7]['tb_columna_tipo'] = 'texto';
        $tabla_columnas[7]['tb_titulo_idioma'] = '{TR|o_id_registro}';
        $tabla_columnas[7]['tb_columna_ancho'] = '10';
        $tabla_columnas[7]['tb_campo'] = 'id_registro';
        // armado de la tabla
        $armar_tabla = new Armado_Tabla();
        $armar_tabla->sinDatosPie();
        return $armar_tabla->armar($tabla_columnas, $log);
    }

}
