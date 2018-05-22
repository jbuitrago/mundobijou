<?php

class Archivos_DerDbdesigner4 {

    private $_matrizTablas;
    private $_matrizParametros;
    private $_matrizDatos;
    private $_tablas;

    public function set() {

        if (Inicio::usuario('tipo') == 'administrador general') {
            $this->_consulta();
        } else {
            return false;
        }
    }

    private function _consulta() {

        $this->_matrizTablas = Consultas_DerDbdesigner4::matrizTablas(__FILE__, __LINE__);

        $this->_armado();

        $this->_armadoTablas();

        $this->_impresion();
    }

    private function _armado() {

        $id_der_tabla = 10000000;
        $id_der_indice = 20000000;
        $id_der_indice_rel = 30000000;
        $id_der_orden = 40000000;
        $id_der_componente = 50000000;
        $id_der_relacion = 60000000;
        $relacion_num = 0;
        $id_der_otras_col = 70000000; // columnas de tablas que se crean para un componente especifico
        $otras_col_num = 0;
        $id_der_relacion_index = 80000000;
        $relacion_num_index = 0;
        $obj_num = 1;

        // posiciones de las tablas en el esquema
        // horizontal
        $pos_x = 5;
        $pos_x_separacion = 200;
        $pos_mimite_x = 4000;
        // vertical
        $pos_y = 5;
        $pos_y_separacion = 400;

        // contadores
        $pos_cont_x = 0;
        $pos_cont_y = 0;
        $id_tabla_ant = 0;

        foreach ($this->_matrizTablas as $key => $val) {

            if (($pos_x + ( $pos_x_separacion * $pos_cont_x )) > $pos_mimite_x) {
                $pos_cont_x = 0;
                $pos_cont_y++;
            }

            $tabla_id = $this->_matrizTablas[$key]['id_tabla'];
            $tabla_prefijo = $this->_matrizTablas[$key]['prefijo'];
            $tabla_nombre = $this->_matrizTablas[$key]['tabla_nombre'];
            $campo_nombre = $this->_matrizTablas[$key]['tabla_campo'];
            $componente_id = $this->_matrizTablas[$key]['id_componente'];

            if ($tabla_id != $id_tabla_ant) {

                // agrego datos de la tabla
                $this->_matrizDatos[$tabla_id]['tabla']['id'] = $id_der_tabla + $tabla_id;
                $this->_matrizDatos[$tabla_id]['tabla']['nombre'] = $tabla_prefijo . '_' . $tabla_nombre;
                $this->_matrizDatos[$tabla_id]['tabla']['pos_x'] = $pos_x + ( $pos_x_separacion * $pos_cont_x );
                $this->_matrizDatos[$tabla_id]['tabla']['pos_y'] = $pos_y + ( $pos_y_separacion * $pos_cont_y );
                $this->_matrizDatos[$tabla_id]['tabla']['obj_num'] = $obj_num;
                $obj_num++;
                $pos_cont_x++;

                // agrego datos de la columna id
                $this->_matrizDatos[$tabla_id]['id_tabla']['id'] = $id_der_indice + $tabla_id;
                $this->_matrizDatos[$tabla_id]['id_tabla']['nombre'] = 'id_' . $tabla_prefijo . '_' . $tabla_nombre;
                $this->_matrizDatos[$tabla_id]['id_tabla']['tipo_dato'] = 'indice';
                $this->_matrizDatos[$tabla_id]['id_tabla']['largo_campo'] = 6;
                $this->_matrizDatos[$tabla_id]['id_tabla']['indice'] = 1;
                $this->_matrizDatos[$tabla_id]['id_tabla']['nulo'] = 1;
                $this->_matrizDatos[$tabla_id]['id_tabla']['id_indice'] = $id_der_indice_rel + $tabla_id;
                $this->_matrizDatos[$tabla_id]['id_tabla']['clave_externa'] = 0;

                // agrego datos de la columna orden
                $this->_matrizDatos[$tabla_id]['orden']['id'] = $id_der_orden + $tabla_id;
                $this->_matrizDatos[$tabla_id]['orden']['nombre'] = 'orden';
                $this->_matrizDatos[$tabla_id]['orden']['tipo_dato'] = 'numero';
                $this->_matrizDatos[$tabla_id]['orden']['largo_campo'] = 6;
                $this->_matrizDatos[$tabla_id]['orden']['indice'] = 0;
                $this->_matrizDatos[$tabla_id]['orden']['nulo'] = 1;
                $this->_matrizDatos[$tabla_id]['orden']['clave_externa'] = 0;
            }
            $id_tabla_ant = $tabla_id;

            if ($campo_nombre) {

                $this->_matrizDatos[$tabla_id]['columnas'][$componente_id]['id'] = $id_der_componente + $componente_id;
                $this->_matrizDatos[$tabla_id]['columnas'][$componente_id]['nombre'] = $campo_nombre;
                $this->_matrizDatos[$tabla_id]['columnas'][$componente_id]['indice'] = 0;
                $this->_matrizDatos[$tabla_id]['columnas'][$componente_id]['nulo'] = 0;
                $this->_matrizDatos[$tabla_id]['columnas'][$componente_id]['clave_externa'] = 0;
            }

            $this->_matrizParametros = Consultas_DerDbdesigner4::matrizParametros(__FILE__, __LINE__, $componente_id);

            foreach ($this->_matrizParametros as $key_param => $val_param) {

                switch ($this->_matrizParametros[$key_param]['parametro']) {
                    case 'insercion_especial':
                        $crear_tabla = false;
                        break;
                    case 'largo':
                        $largo = $val_param['valor'];
                        break;
                    case 'decimales':
                        $decimales = $val_param['valor'];
                        break;
                    case 'tipo_dato':
                        $tipo_dato = $val_param['valor'];
                        break;
                    case 'origen_cp_id':
                        $origen_cp_id = $val_param['valor'];
                        break;
                    case 'intermedia_tb_id':
                        $intermedia_tb_id = $val_param['valor'];
                        break;
                }
            }

            if (isset($campo_nombre)) {

                if (isset($tipo_dato)) {
                    $this->_matrizDatos[$tabla_id]['columnas'][$componente_id]['tipo_dato'] = $tipo_dato;
                }
                if (isset($largo)) {
                    $this->_matrizDatos[$tabla_id]['columnas'][$componente_id]['largo_campo'] = $largo;
                }
                if (isset($decimales)) {
                    $this->_matrizDatos[$tabla_id]['columnas'][$componente_id]['largo_campo'] .= ',' . $decimales;
                }
            }

            if (isset($origen_cp_id) && !isset($intermedia_tb_id)) {

                // link en tabla de destino
                $this->_matrizDatos[$tabla_id]['relacion_destino'][$relacion_num]['id'] = $id_der_relacion + $relacion_num;
                $this->_matrizDatos[$tabla_id]['relacion_destino'][$relacion_num]['columna_id'] = $id_der_componente + $componente_id;
                $this->_matrizDatos[$tabla_id]['relacion_destino'][$relacion_num]['id_relacion'] = $id_der_relacion_index + $relacion_num_index;
                $this->_matrizDatos[$tabla_id]['relacion_destino'][$relacion_num]['nombre_relacion'] = $tabla_prefijo . '_' . $tabla_nombre;
                $relacion_num_index++;
                // convierto en indice el campo relacionado
                $this->_matrizDatos[$tabla_id]['columnas'][$componente_id]['tipo_dato'] = 'indice';
                $this->_matrizDatos[$tabla_id]['columnas'][$componente_id]['clave_externa'] = 1;

                // tabla origen
                $dcp = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($origen_cp_id);

                // link en tabla de origen
                $this->_matrizDatos[$dcp['tb_id']]['relacion_origen'][$relacion_num]['id'] = $id_der_relacion + $relacion_num;
                $relacion_num_index++;

                // establece la relacion en general
                $this->_matrizDatos['relaciones'][$relacion_num]['id'] = $id_der_relacion + $relacion_num;
                $this->_matrizDatos['relaciones'][$relacion_num]['tabla_destino'] = $id_der_tabla + $tabla_id;
                $this->_matrizDatos['relaciones'][$relacion_num]['tabla_origen'] = $id_der_tabla + $dcp['tb_id'];
                $this->_matrizDatos['relaciones'][$relacion_num]['nombre_num'] = $relacion_num;
                $this->_matrizDatos['relaciones'][$relacion_num]['id_relacion'] = $id_der_relacion_index + $relacion_num_index;
                $this->_matrizDatos['relaciones'][$relacion_num]['obj_num'] = $obj_num;
                $this->_matrizDatos['relaciones'][$relacion_num]['rel_direccion'] = 4;
                $this->_matrizDatos['relaciones'][$relacion_num]['campo_nombre'] = 'id_' . $dcp['tb_prefijo'] . '_' . $dcp['tb_nombre'];
                $obj_num++;
                $relacion_num++;
            } elseif (isset($origen_cp_id) && isset($intermedia_tb_id)) {

                // armado de esquema de tabla intermedia
                $dcp = Consultas_ObtenerTablaNombreTipo::armado($intermedia_tb_id, 'sin_idioma');

                // armado de esquema de tabla origen
                $dcp2 = Consultas_MatrizObtenerDeComponenteTablaYParametros::armado($origen_cp_id, 'sin_idioma');

                // agrego datos de la tabla
                $this->_matrizDatos[$dcp['id_tabla']]['tabla']['id'] = $id_der_tabla + $dcp['id_tabla'];
                $this->_matrizDatos[$dcp['id_tabla']]['tabla']['nombre'] = $dcp['prefijo'] . '_' . $dcp['nombre'];
                $this->_matrizDatos[$dcp['id_tabla']]['tabla']['pos_x'] = $pos_x + ( $pos_x_separacion * $pos_cont_x );
                $this->_matrizDatos[$dcp['id_tabla']]['tabla']['pos_y'] = $pos_y + ( $pos_y_separacion * $pos_cont_y );
                $this->_matrizDatos[$dcp['id_tabla']]['tabla']['obj_num'] = $obj_num;
                $obj_num++;
                $pos_cont_x++;

                // agrego datos de la columna id
                $this->_matrizDatos[$dcp['id_tabla']]['id_tabla']['id'] = $id_der_indice + $dcp['id_tabla'];
                $this->_matrizDatos[$dcp['id_tabla']]['id_tabla']['nombre'] = 'id_' . $dcp['prefijo'] . '_' . $dcp['nombre'];
                $this->_matrizDatos[$dcp['id_tabla']]['id_tabla']['tipo_dato'] = 'indice';
                $this->_matrizDatos[$dcp['id_tabla']]['id_tabla']['largo_campo'] = 6;
                $this->_matrizDatos[$dcp['id_tabla']]['id_tabla']['indice'] = 1;
                $this->_matrizDatos[$dcp['id_tabla']]['id_tabla']['nulo'] = 1;
                $this->_matrizDatos[$dcp['id_tabla']]['id_tabla']['id_indice'] = $id_der_indice_rel + $dcp['id_tabla'];
                $this->_matrizDatos[$dcp['id_tabla']]['id_tabla']['clave_externa'] = 0;

                // agrego datos de la columna orden
                $this->_matrizDatos[$dcp['id_tabla']]['orden']['id'] = $id_der_orden + $dcp['id_tabla'];
                $this->_matrizDatos[$dcp['id_tabla']]['orden']['nombre'] = 'orden';
                $this->_matrizDatos[$dcp['id_tabla']]['orden']['tipo_dato'] = 'numero';
                $this->_matrizDatos[$dcp['id_tabla']]['orden']['largo_campo'] = 6;
                $this->_matrizDatos[$dcp['id_tabla']]['orden']['indice'] = 0;
                $this->_matrizDatos[$dcp['id_tabla']]['orden']['nulo'] = 1;
                $this->_matrizDatos[$dcp['id_tabla']]['orden']['clave_externa'] = 0;

                // columna destino en tabla intermedia
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['id'] = $id_der_otras_col + $otras_col_num;
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['nombre'] = 'id_' . $tabla_prefijo . '_' . $tabla_nombre;
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['tipo_dato'] = 'indice';
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['largo_campo'] = 6;
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['indice'] = 0;
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['nulo'] = 0;
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['clave_externa'] = 1;
                $otras_col_num++;

                // columna origen en tabla intermedia
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['id'] = $id_der_otras_col + $otras_col_num;
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['nombre'] = 'id_' . $dcp2['tb_prefijo'] . '_' . $dcp2['tb_nombre'];
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['tipo_dato'] = 'indice';
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['largo_campo'] = 6;
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['indice'] = 0;
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['nulo'] = 0;
                $this->_matrizDatos[$dcp['id_tabla']]['columnas'][$otras_col_num]['clave_externa'] = 1;
                $otras_col_num++;

                // relacion origen a intermedia
                // link en tabla de intermedia
                $this->_matrizDatos[$dcp['id_tabla']]['relacion_destino'][$relacion_num]['id'] = $id_der_relacion + $relacion_num;
                $this->_matrizDatos[$dcp['id_tabla']]['relacion_destino'][$relacion_num]['columna_id'] = $id_der_componente + $componente_id;
                $this->_matrizDatos[$dcp['id_tabla']]['relacion_destino'][$relacion_num]['id_relacion'] = $id_der_relacion_index + $relacion_num_index;
                $this->_matrizDatos[$dcp['id_tabla']]['relacion_destino'][$relacion_num]['nombre_relacion'] = $dcp['prefijo'] . '_' . $dcp['nombre'];
                $relacion_num_index++;

                // link en tabla de origen
                $this->_matrizDatos[$dcp2['tb_id']]['relacion_origen'][$relacion_num]['id'] = $id_der_relacion + $relacion_num;
                $relacion_num_index++;

                // establece la relacion en general
                $this->_matrizDatos['relaciones'][$relacion_num]['id'] = $id_der_relacion + $relacion_num;
                $this->_matrizDatos['relaciones'][$relacion_num]['tabla_destino'] = $id_der_tabla + $dcp['id_tabla'];
                $this->_matrizDatos['relaciones'][$relacion_num]['tabla_origen'] = $id_der_tabla + $dcp2['tb_id'];
                $this->_matrizDatos['relaciones'][$relacion_num]['nombre_num'] = $relacion_num;
                $this->_matrizDatos['relaciones'][$relacion_num]['id_relacion'] = $id_der_relacion_index + $relacion_num_index;
                $this->_matrizDatos['relaciones'][$relacion_num]['obj_num'] = $obj_num;
                $this->_matrizDatos['relaciones'][$relacion_num]['rel_direccion'] = 2;
                $this->_matrizDatos['relaciones'][$relacion_num]['campo_nombre'] = 'id_' . $tabla_prefijo . '_' . $tabla_nombre;
                $obj_num++;
                $relacion_num++;

                // relacion intermedia a destino
                // link en tabla de destino
                $this->_matrizDatos[$tabla_id]['relacion_origen'][$relacion_num]['id'] = $id_der_relacion + $relacion_num;
                $relacion_num_index++;

                // link en tabla de intermedia
                $this->_matrizDatos[$dcp['id_tabla']]['relacion_destino'][$relacion_num]['id'] = $id_der_relacion + $relacion_num;
                if (isset($dcp['cp_id'])) {
                    $var_cp_id = $dcp['cp_id'];
                } else {
                    $var_cp_id = '';
                }
                $this->_matrizDatos[$dcp['id_tabla']]['relacion_destino'][$relacion_num]['columna_id'] = $id_der_componente + $var_cp_id;
                $this->_matrizDatos[$dcp['id_tabla']]['relacion_destino'][$relacion_num]['id_relacion'] = $id_der_relacion_index + $relacion_num_index;
                $this->_matrizDatos[$dcp['id_tabla']]['relacion_destino'][$relacion_num]['nombre_relacion'] = $dcp['prefijo'] . '_' . $dcp['nombre'];
                $relacion_num_index++;

                // establece la relacion en general
                $this->_matrizDatos['relaciones'][$relacion_num]['id'] = $id_der_relacion + $relacion_num;
                $this->_matrizDatos['relaciones'][$relacion_num]['tabla_destino'] = $id_der_tabla + $dcp['id_tabla'];
                $this->_matrizDatos['relaciones'][$relacion_num]['tabla_origen'] = $id_der_tabla + $tabla_id;
                $this->_matrizDatos['relaciones'][$relacion_num]['nombre_num'] = $relacion_num;
                $this->_matrizDatos['relaciones'][$relacion_num]['id_relacion'] = $id_der_relacion_index + $relacion_num_index;
                $this->_matrizDatos['relaciones'][$relacion_num]['obj_num'] = $obj_num;
                $this->_matrizDatos['relaciones'][$relacion_num]['rel_direccion'] = 4;
                $this->_matrizDatos['relaciones'][$relacion_num]['campo_nombre'] = 'id_' . $dcp2['tb_prefijo'] . '_' . $dcp2['tb_nombre'];
                $obj_num++;
                $relacion_num++;
            }

            unset($largo);
            unset($decimales);
            unset($tipo_dato);
            unset($origen_cp_id);
            unset($intermedia_tb_id);
        }
    }

    private function _armadoTablas() {

        $numero_relacion_index = 1;
        $tablas = '';
        $relaciones = '';
        $indice = '';

        foreach ($this->_matrizDatos as $sub_matrices => $val_sub_matrices) {

            if ($sub_matrices != 'relaciones') {

                $tabla = '';
                $columna = '';
                $indice = '';

                foreach ($val_sub_matrices as $key => $valor) {

                    switch ($key) {

                        case 'tabla':
                            $tabla = $this->_tabla($valor['id'], $valor['nombre'], $valor['pos_x'], $valor['pos_y'], $valor['obj_num']);
                            break;
                        case 'id_tabla':
                            $columna .= $this->_columna($valor['id'], $valor['nombre'], $valor['tipo_dato'], $valor['largo_campo'], '1', $valor['nulo'], '');
                            $indice .= $this->_indiceTabla($valor['id_indice'], $valor['id']);
                            break;
                        case 'orden':
                            $columna .= $this->_columna($valor['id'], $valor['nombre'], $valor['tipo_dato'], $valor['largo_campo'], '0', $valor['nulo'], '');
                            break;
                        case 'columnas':
                            foreach ($valor as $columna_key => $columna_valor) {
                                if (isset($columna_valor['tipo_dato']) && ($columna_valor['tipo_dato'] != '')) {
                                    if (isset($columna_valor['largo_campo'])) {
                                        $largo_campo = $columna_valor['largo_campo'];
                                    } else {
                                        $largo_campo = '';
                                    }
                                    if (isset($columna_valor['id'])) {
                                        $columna_valor_id = $columna_valor['id'];
                                    } else {
                                        $columna_valor_id = '';
                                    }
                                    if (isset($columna_valor['nombre'])) {
                                        $columna_valor_nombre = $columna_valor['nombre'];
                                    } else {
                                        $columna_valor_nombre = '';
                                    }
                                    if (isset($columna_valor['nulo'])) {
                                        $columna_valor_nulo = $columna_valor['nulo'];
                                    } else {
                                        $columna_valor_nulo = '';
                                    }
                                    if (isset($columna_valor['clave_externa'])) {
                                        $columna_valor_clave_externa = $columna_valor['clave_externa'];
                                    } else {
                                        $columna_valor_clave_externa = '';
                                    }
                                    $columna .= $this->_columna($columna_valor_id, $columna_valor_nombre, $columna_valor['tipo_dato'], $largo_campo, '0', $columna_valor_nulo, $columna_valor_clave_externa);
                                }
                            }
                            break;
                        case 'relacion_origen':
                            $relacion_inicio = '';
                            foreach ($valor as $columna_key => $columna_valor) {
                                $relacion_inicio .= $this->_relacionInicio($columna_valor['id']);
                                if (isset($columna_valor['id_relacion'])) {
                                    $indice .= $this->_indiceTablaRelacion($columna_valor['id_relacion'], $columna_valor['id'], $columna_valor['columna_id'], $columna_valor['nombre_relacion'], $numero_relacion_index);
                                }
                                $numero_relacion_index++;
                            }
                            break;
                        case 'relacion_destino':
                            $relacion_fin = '';
                            foreach ($valor as $columna_key => $columna_valor) {
                                $relacion_fin .= $this->_relacionFin($columna_valor['id']);
                                if ($columna_valor['id_relacion']) {
                                    $indice .= $this->_indiceTablaRelacion($columna_valor['id_relacion'], $columna_valor['id'], $columna_valor['columna_id'], $columna_valor['nombre_relacion'], $numero_relacion_index);
                                }
                                $numero_relacion_index++;
                            }
                            break;
                    }
                }

                $tablas .= $tabla;
                $tablas .= "\n" . '<COLUMNS>' . $columna . "\n" . '</COLUMNS>';

                if (isset($relacion_inicio)) {
                    $tablas .= "\n" . '<RELATIONS_START>' . "\n";
                    $tablas .= $relacion_inicio;
                    $tablas .= '</RELATIONS_START>';
                }
                if (isset($relacion_fin)) {
                    $tablas .= "\n" . '<RELATIONS_END>' . "\n";
                    $tablas .= $relacion_fin;
                    $tablas .= '</RELATIONS_END>';
                }
                if (isset($indice)) {
                    $tablas .= "\n" . '<INDICES>' . "\n";
                    $tablas .= $indice;
                    $tablas .= "\n" . '</INDICES>';
                }

                $tablas .= "\n" . '</TABLE>' . "\n";
            } else {

                foreach ($val_sub_matrices as $key => $valor) {

                    $relaciones .= $this->_relacionesGenerales($valor['id'], $valor['nombre_num'], $valor['id_relacion'], $valor['tabla_destino'], $valor['tabla_origen'], $valor['obj_num'], $valor['rel_direccion'], $valor['campo_nombre']);
                }
            }

            unset($relacion_inicio);
            unset($relacion_fin);
            unset($indice);
        }

        $this->_tablas = '<TABLES>' . "\n" . $tablas . '</TABLES>' . "\n" . '<RELATIONS>' . $relaciones . "\n" . '</RELATIONS>';
    }

    private function _impresion() {

        $contenido = $this->_inicio() . $this->_tablas . $this->_fin();

        $nombre = 'DER_' . Inicio::confVars('nombre_servidor') . '_DBDesigner4.xml';
        header("Content-type: application/force-download");
        header("Content-type: application/octet-stream");
        header("Content-type: application/download");
        header('Content-disposition: attachment; filename="' . $nombre . '"');

        echo $contenido;
    }

    private function _inicio() {

        $inicio = '<?xml version="1.0" standalone="yes" ?>';

        //<?php

        $inicio .= '
<DBMODEL Version="4.0">
<SETTINGS>
<GLOBALSETTINGS ModelName="der" IDModel="0" IDVersion="0" VersionStr="1.0.0.0" Comments="" UseVersionHistroy="1" AutoIncVersion="1" DatabaseType="MySQL" ZoomFac="100.00" XPos="0" YPos="0" DefaultDataType="5" DefaultTablePrefix="0" DefSaveDBConn="" DefSyncDBConn="" DefQueryDBConn="" Printer="" HPageCount="4.0" PageAspectRatio="1.440892512336408" PageOrientation="1" PageFormat="A4 (210x297 mm, 8.26x11.7 inches)" SelectedPages="" UsePositionGrid="0" PositionGridX="20" PositionGridY="20" TableNameInRefs="0" DefaultTableType="0" ActivateRefDefForNewRelations="1" FKPrefix="" FKPostfix="" CreateFKRefDefIndex="1" DBQuoteCharacter="`" CreateSQLforLinkedObjects="0" DefModelFont="Tahoma" CanvasWidth="4300" CanvasHeight="6000" />
<DATATYPEGROUPS>
<DATATYPEGROUP Name="Numeric Types" Icon="1" />
<DATATYPEGROUP Name="Date and Time Types" Icon="2" />
<DATATYPEGROUP Name="String Types" Icon="3" />
<DATATYPEGROUP Name="Blob and Text Types" Icon="4" />
<DATATYPEGROUP Name="User defined Types" Icon="5" />
<DATATYPEGROUP Name="Geographic Types" Icon="6" />
</DATATYPEGROUPS>
<DATATYPES>
<DATATYPE ID="1" IDGroup="0" TypeName="TINYINT" Description="A very small integer. The signed range is -128 to 127. The unsigned range is 0 to 255." ParamCount="1" OptionCount="2" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
</PARAMS>
<OPTIONS>
<OPTION Name="UNSIGNED" Default="1" />
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="2" IDGroup="0" TypeName="SMALLINT" Description="A small integer. The signed range is -32768 to 32767. The unsigned range is 0 to 65535." ParamCount="1" OptionCount="2" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
</PARAMS>
<OPTIONS>
<OPTION Name="UNSIGNED" Default="1" />
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="3" IDGroup="0" TypeName="MEDIUMINT" Description="A medium-size integer. The signed range is -8388608 to 8388607. The unsigned range is 0 to 16777215." ParamCount="1" OptionCount="2" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
</PARAMS>
<OPTIONS>
<OPTION Name="UNSIGNED" Default="1" />
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="4" IDGroup="0" TypeName="INT" Description="A normal-size integer. The signed range is -2147483648 to 2147483647. The unsigned range is 0 to 4294967295." ParamCount="1" OptionCount="2" ParamRequired="0" EditParamsAsString="0" SynonymGroup="1" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
</PARAMS>
<OPTIONS>
<OPTION Name="UNSIGNED" Default="0" />
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="5" IDGroup="0" TypeName="INTEGER" Description="A normal-size integer. The signed range is -2147483648 to 2147483647. The unsigned range is 0 to 4294967295." ParamCount="1" OptionCount="2" ParamRequired="0" EditParamsAsString="0" SynonymGroup="1" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
</PARAMS>
<OPTIONS>
<OPTION Name="UNSIGNED" Default="1" />
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="6" IDGroup="0" TypeName="BIGINT" Description="A large integer. The signed range is -9223372036854775808 to 9223372036854775807. The unsigned range is 0 to 18446744073709551615." ParamCount="1" OptionCount="2" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
</PARAMS>
<OPTIONS>
<OPTION Name="UNSIGNED" Default="0" />
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="7" IDGroup="0" TypeName="FLOAT" Description="A small (single-precision) floating-point number. Cannot be unsigned. Allowable values are -3.402823466E+38 to -1.175494351E-38, 0, and 1.175494351E-38 to 3.402823466E+38." ParamCount="1" OptionCount="1" ParamRequired="1" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="precision" />
</PARAMS>
<OPTIONS>
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="8" IDGroup="0" TypeName="FLOAT" Description="A small (single-precision) floating-point number. Cannot be unsigned. Allowable values are -3.402823466E+38 to -1.175494351E-38, 0, and 1.175494351E-38 to 3.402823466E+38." ParamCount="2" OptionCount="1" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
<PARAM Name="decimals" />
</PARAMS>
<OPTIONS>
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="9" IDGroup="0" TypeName="DOUBLE" Description="A normal-size (double-precision) floating-point number. Cannot be unsigned. Allowable values are -1.7976931348623157E+308 to -2.2250738585072014E-308, 0, and 2.2250738585072014E-308 to 1.7976931348623157E+308." ParamCount="2" OptionCount="1" ParamRequired="0" EditParamsAsString="0" SynonymGroup="2" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
<PARAM Name="decimals" />
</PARAMS>
<OPTIONS>
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="10" IDGroup="0" TypeName="DOUBLE PRECISION" Description="This is a synonym for DOUBLE." ParamCount="2" OptionCount="1" ParamRequired="0" EditParamsAsString="0" SynonymGroup="2" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
<PARAM Name="decimals" />
</PARAMS>
<OPTIONS>
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="11" IDGroup="0" TypeName="REAL" Description="This is a synonym for DOUBLE." ParamCount="2" OptionCount="1" ParamRequired="0" EditParamsAsString="0" SynonymGroup="2" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
<PARAM Name="decimals" />
</PARAMS>
<OPTIONS>
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="12" IDGroup="0" TypeName="DECIMAL" Description="An unpacked floating-point number. Cannot be unsigned. Behaves like a CHAR column." ParamCount="2" OptionCount="1" ParamRequired="0" EditParamsAsString="0" SynonymGroup="3" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
<PARAM Name="decimals" />
</PARAMS>
<OPTIONS>
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="13" IDGroup="0" TypeName="NUMERIC" Description="This is a synonym for DECIMAL." ParamCount="2" OptionCount="1" ParamRequired="1" EditParamsAsString="0" SynonymGroup="3" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
<PARAM Name="decimals" />
</PARAMS>
<OPTIONS>
<OPTION Name="ZEROFILL" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="14" IDGroup="1" TypeName="DATE" Description="A date. The supported range is \a1000-01-01\a to \a9999-12-31\a." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="15" IDGroup="1" TypeName="DATETIME" Description="A date and time combination. The supported range is \a1000-01-01 00:00:00\a to \a9999-12-31 23:59:59\a." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="16" IDGroup="1" TypeName="TIMESTAMP" Description="A timestamp. The range is \a1970-01-01 00:00:00\a to sometime in the year 2037. The length can be 14 (or missing), 12, 10, 8, 6, 4, or 2 representing YYYYMMDDHHMMSS, ... , YYYYMMDD, ... , YY formats." ParamCount="1" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
</PARAMS>
</DATATYPE>
<DATATYPE ID="17" IDGroup="1" TypeName="TIME" Description="A time. The range is \a-838:59:59\a to \a838:59:59\a." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="18" IDGroup="1" TypeName="YEAR" Description="A year in 2- or 4-digit format (default is 4-digit)." ParamCount="1" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
</PARAMS>
</DATATYPE>
<DATATYPE ID="19" IDGroup="2" TypeName="CHAR" Description="A fixed-length string (1 to 255 characters) that is always right-padded with spaces to the specified length when stored. values are sorted and compared in case-insensitive fashion according to the default character set unless the BINARY keyword is given." ParamCount="1" OptionCount="1" ParamRequired="1" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
</PARAMS>
<OPTIONS>
<OPTION Name="BINARY" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="20" IDGroup="2" TypeName="VARCHAR" Description="A variable-length string (1 to 255 characters). Values are sorted and compared in case-sensitive fashion unless the BINARY keyword is given." ParamCount="1" OptionCount="1" ParamRequired="1" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="length" />
</PARAMS>
<OPTIONS>
<OPTION Name="BINARY" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="21" IDGroup="2" TypeName="BIT" Description="This is a synonym for CHAR(1)." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="22" IDGroup="2" TypeName="BOOL" Description="This is a synonym for CHAR(1)." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="23" IDGroup="3" TypeName="TINYBLOB" Description="A column maximum length of 255 (2^8 - 1) characters. Values are sorted and compared in case-sensitive fashion." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="24" IDGroup="3" TypeName="BLOB" Description="A column maximum length of 65535 (2^16 - 1) characters. Values are sorted and compared in case-sensitive fashion." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="25" IDGroup="3" TypeName="MEDIUMBLOB" Description="A column maximum length of 16777215 (2^24 - 1) characters. Values are sorted and compared in case-sensitive fashion." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="26" IDGroup="3" TypeName="LONGBLOB" Description="A column maximum length of 4294967295 (2^32 - 1) characters. Values are sorted and compared in case-sensitive fashion." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="27" IDGroup="3" TypeName="TINYTEXT" Description="A column maximum length of 255 (2^8 - 1) characters." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="28" IDGroup="3" TypeName="TEXT" Description="A column maximum length of 65535 (2^16 - 1) characters." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="29" IDGroup="3" TypeName="MEDIUMTEXT" Description="A column maximum length of 16777215 (2^24 - 1) characters." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="30" IDGroup="3" TypeName="LONGTEXT" Description="A column maximum length of 4294967295 (2^32 - 1) characters." ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="31" IDGroup="3" TypeName="ENUM" Description="An enumeration. A string object that can have only one value, chosen from the list of values." ParamCount="1" OptionCount="0" ParamRequired="1" EditParamsAsString="1" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="values" />
</PARAMS>
</DATATYPE>
<DATATYPE ID="32" IDGroup="3" TypeName="SET" Description="A set. A string object that can have zero or more values, each of which must be chosen from the list of values." ParamCount="1" OptionCount="0" ParamRequired="1" EditParamsAsString="1" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<PARAMS>
<PARAM Name="values" />
</PARAMS>
</DATATYPE>
<DATATYPE ID="33" IDGroup="4" TypeName="Varchar(20)" Description="" ParamCount="0" OptionCount="1" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<OPTIONS>
<OPTION Name="BINARY" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="34" IDGroup="4" TypeName="Varchar(45)" Description="" ParamCount="0" OptionCount="1" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<OPTIONS>
<OPTION Name="BINARY" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="35" IDGroup="4" TypeName="Varchar(255)" Description="" ParamCount="0" OptionCount="1" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
<OPTIONS>
<OPTION Name="BINARY" Default="0" />
</OPTIONS>
</DATATYPE>
<DATATYPE ID="36" IDGroup="5" TypeName="GEOMETRY" Description="Geographic Datatype" ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="38" IDGroup="5" TypeName="LINESTRING" Description="Geographic Datatype" ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="39" IDGroup="5" TypeName="POLYGON" Description="Geographic Datatype" ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="40" IDGroup="5" TypeName="MULTIPOINT" Description="Geographic Datatype" ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="41" IDGroup="5" TypeName="MULTILINESTRING" Description="Geographic Datatype" ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="42" IDGroup="5" TypeName="MULTIPOLYGON" Description="Geographic Datatype" ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
<DATATYPE ID="43" IDGroup="5" TypeName="GEOMETRYCOLLECTION" Description="Geographic Datatype" ParamCount="0" OptionCount="0" ParamRequired="0" EditParamsAsString="0" SynonymGroup="0" PhysicalMapping="0" PhysicalTypeName="" >
</DATATYPE>
</DATATYPES>
<COMMON_DATATYPES>
<COMMON_DATATYPE ID="5" />
<COMMON_DATATYPE ID="8" />
<COMMON_DATATYPE ID="20" />
<COMMON_DATATYPE ID="15" />
<COMMON_DATATYPE ID="22" />
<COMMON_DATATYPE ID="28" />
<COMMON_DATATYPE ID="26" />
<COMMON_DATATYPE ID="33" />
<COMMON_DATATYPE ID="34" />
<COMMON_DATATYPE ID="35" />
</COMMON_DATATYPES>
<TABLEPREFIXES>
<TABLEPREFIX Name="Default (no prefix)" />
</TABLEPREFIXES>
<REGIONCOLORS>
<REGIONCOLOR Color="Red=#FFEEEC" />
<REGIONCOLOR Color="Yellow=#FEFDED" />
<REGIONCOLOR Color="Green=#EAFFE5" />
<REGIONCOLOR Color="Cyan=#ECFDFF" />
<REGIONCOLOR Color="Blue=#F0F1FE" />
<REGIONCOLOR Color="Magenta=#FFEBFA" />
</REGIONCOLORS>
<POSITIONMARKERS>
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
<POSITIONMARKER ZoomFac="-1.0" X="0" Y="0" />
</POSITIONMARKERS>
</SETTINGS>
<METADATA>
<REGIONS>
</REGIONS>
';

        return $inicio;
    }

    private function _fin() {

        $fin = '
<NOTES>
</NOTES>
<IMAGES>
</IMAGES>
</METADATA>
<PLUGINDATA>
<PLUGINDATARECORDS>
</PLUGINDATARECORDS>
</PLUGINDATA>
<QUERYDATA>
<QUERYRECORDS>
</QUERYRECORDS>
</QUERYDATA>
<LINKEDMODELS>
</LINKEDMODELS>
</DBMODEL>
';

        return $fin;
    }

    private function _tabla($id, $nombre, $pos_x, $pos_y, $obj_num) {

        $tabla = '<TABLE ID="' . $id . '" Tablename="' . $nombre . '" PrevTableName="" XPos="' . $pos_x . '" YPos="' . $pos_y . '" TableType="0" TablePrefix="0" nmTable="0" Temporary="0" UseStandardInserts="0" StandardInserts="\n" TableOptions="DelayKeyTblUpdates=0\nPackKeys=0\nRowChecksum=0\nRowFormat=0\nUseRaid=0\nRaidType=0\nChunks=2\nChunkSize=64\n" Comments="" Collapsed="0" IsLinkedObject="0" IDLinkedModel="-1" Obj_id_Linked="-1" OrderPos="' . $obj_num . '" >';

        return $tabla;
    }

    private function _columna($id, $nombre, $tipo_dato, $largo_campo, $indice, $nulo, $clave_externa) {

        // $id              ( numero )
        // $nombre          ( texto )
        // $tipo_dato       ( 5 = indice de tabla / 20 = texto / 30 = texto largo / 4 = numero )
        // $largo_campo     ( numero )
        // $indice          ( 1 = si / 0 = no )
        // $nulo            ( 1 = si / 0 = no )
        // $opcionselect1   ( incice de tabla =  Value="1" / texto = Value="0" / texto_largo = '' / numero = Value="0" );
        // $opcionselect2   ( incice de tabla =  Value="0" / texto = '' / texto_largo = '' / numero = Value="0" );

        switch ($tipo_dato) {
            case 'indice':
                $tipo_dato = 4;
                $opcionselect1 = 'Value="1" ';
                $opcionselect2 = 'Value="0" ';
                $largo_campo = '12';
                break;
            case 'texto':
                $tipo_dato = 20;
                $opcionselect1 = 'Value="0" ';
                break;
            case 'texto_largo':
                $tipo_dato = 30;
                $largo_campo = '';
                break;
            case 'numero':
                $tipo_dato = 4;
                $opcionselect1 = 'Value="0" ';
                $opcionselect2 = 'Value="0" ';
                break;
            case 'decimal':
                $tipo_dato = 12;
                $opcionselect1 = 'Value="0" ';
                break;
        }


        if ($largo_campo != '') {
            $largo_campo = '(' . $largo_campo . ')';
        }

        if (!isset($clave_externa) || ($clave_externa == '')) {
            $clave_externa = 0;
        }

        $columna = "\n" . '<COLUMN ID="' . $id . '" ColName="' . $nombre . '" PrevColName="" Pos="0" idDatatype="' . $tipo_dato . '" DatatypeParams="' . $largo_campo . '" Width="-1" Prec="-1" PrimaryKey="' . $indice . '" NotNull="' . $nulo . '" AutoInc="0" IsForeignKey="' . $clave_externa . '" DefaultValue="" Comments="">' . "\n";
        $columna .= "<OPTIONSELECTED>\n";
        if (isset($opcionselect1)) {
            $columna .= '<OPTIONSELECT ' . $opcionselect1 . '/>' . "\n";
        }
        if (isset($opcionselect2)) {
            $columna .= '<OPTIONSELECT ' . $opcionselect2 . '/>' . "\n";
        }
        $columna .= '</OPTIONSELECTED>' . "\n";
        $columna .= '</COLUMN>';

        return $columna;
    }

    private function _indiceTabla($id, $id_columna) {

        $id_indice = "\n" . '<INDEX ID="' . $id . '" IndexName="PRIMARY" IndexKind="0" FKRefDef_Obj_id="-1">
<INDEXCOLUMNS>
<INDEXCOLUMN idColumn="' . $id_columna . '" LengthParam="0" />
</INDEXCOLUMNS>
</INDEX>
';

        return $id_indice;
    }

    private function _indiceTablaRelacion($id_relacion, $id, $id_columna, $nombre_columna, $numero_relacion_index) {

        $id_indice = "\n" . '<INDEX ID="' . $id_relacion . '" IndexName="' . $nombre_columna . '_FKIndex' . $numero_relacion_index . '" IndexKind="1" FKRefDef_Obj_id="' . $id . '">
<INDEXCOLUMNS>
<INDEXCOLUMN idColumn="' . $id_columna . '" LengthParam="0" />
</INDEXCOLUMNS>
</INDEX>
';

        return $id_indice;
    }

    private function _relacionInicio($id) {

        $id_indice = '<RELATION_START ID="' . $id . '" />' . "\n";

        return $id_indice;
    }

    private function _relacionFin($id) {

        $id_indice = '<RELATION_END ID="' . $id . '" />' . "\n";

        return $id_indice;
    }

    private function _relacionesGenerales($id, $nombre_num, $id_index, $tabla_destino, $tabla_origen, $obj_num, $rel_direccion, $campo_nombre) {

        $id_indice = "\n" . '<RELATION ID="' . $id . '" RelationName="Rel_' . $nombre_num . '" Kind="2" SrcTable="' . $tabla_origen . '" DestTable="' . $tabla_destino . '" FKFields="' . $campo_nombre . '=' . $campo_nombre . '\n" FKFieldsComments="\n" relDirection="' . $rel_direccion . '" MidOffset="0" OptionalStart="0" OptionalEnd="0" CaptionOffsetX="0" CaptionOffsetY="0" StartIntervalOffsetX="0" StartIntervalOffsetY="0" EndIntervalOffsetX="0" EndIntervalOffsetY="0" CreateRefDef="1" Invisible="0" RefDef="Matching=0\nOnDelete=3\nOnUpdate=3\n" Comments="" FKRefDefIndex_Obj_id="' . $id_index . '" Splitted="0" IsLinkedObject="0" IDLinkedModel="-1" Obj_id_Linked="-1" OrderPos="' . $obj_num . '" />';

        return $id_indice;
    }

}
