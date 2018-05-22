<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class producto extends MY_Controller {

    var $model = 'producto_model';
    var $folder_view = 'producto';
    var $controller = 'producto';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index($page_view='') {
        
        $this->data_view['page_view'] = $page_view;
        
        $this->load_view();
    }

    public function check_formato($objWorksheet) {
        if (($objWorksheet->getCellByColumnAndRow(0, 1)->getValue() != "TITULO")
                or ( $objWorksheet->getCellByColumnAndRow(1, 1)->getValue() != "ARTICULO")
                or ( $objWorksheet->getCellByColumnAndRow(2, 1)->getValue() != "DESCRIPCION CORTA")
                or ( $objWorksheet->getCellByColumnAndRow(3, 1)->getValue() != "CATEGORIA")
                or ( $objWorksheet->getCellByColumnAndRow(4, 1)->getValue() != "SUBCATEGORIA")
                or ( $objWorksheet->getCellByColumnAndRow(5, 1)->getValue() != "OFERTA")
                or ( $objWorksheet->getCellByColumnAndRow(6, 1)->getValue() != "NUEVO")
                or ( $objWorksheet->getCellByColumnAndRow(7, 1)->getValue() != "VIDEO")
                or ( $objWorksheet->getCellByColumnAndRow(8, 1)->getValue() != "MEDIDAS")
                or ( $objWorksheet->getCellByColumnAndRow(9, 1)->getValue() != "COLORES")
                or ( $objWorksheet->getCellByColumnAndRow(10, 1)->getValue() != "TALLES")
                or ( $objWorksheet->getCellByColumnAndRow(11, 1)->getValue() != "PRECIO MAYORISTA")
                or ( $objWorksheet->getCellByColumnAndRow(12, 1)->getValue() != "PRECIO OFERTA MAYORISTA")
                or ( $objWorksheet->getCellByColumnAndRow(13, 1)->getValue() != "PRECIO REVENDEDOR")
                or ( $objWorksheet->getCellByColumnAndRow(14, 1)->getValue() != "PRECIO OFERTA REVENDEDOR")
                or ( $objWorksheet->getCellByColumnAndRow(15, 1)->getValue() != "PRECIO X 10 MAYORISTA")
                or ( $objWorksheet->getCellByColumnAndRow(16, 1)->getValue() != "PRECIO X 10 REVENDEDOR")
                or ( $objWorksheet->getCellByColumnAndRow(17, 1)->getValue() != "PRECIO X 10 OFERTA MAYORISTA")
                or ( $objWorksheet->getCellByColumnAndRow(18, 1)->getValue() != "PRECIO X 10 OFERTA REVENDEDOR")
                or ( $objWorksheet->getCellByColumnAndRow(19, 1)->getValue() != "STOCK")
                or ( $objWorksheet->getCellByColumnAndRow(20, 1)->getValue() != "DESCRIPCION")
                or ( $objWorksheet->getCellByColumnAndRow(21, 1)->getValue() != "IMAGEN 1")
                or ( $objWorksheet->getCellByColumnAndRow(22, 1)->getValue() != "IMAGEN 2")
                or ( $objWorksheet->getCellByColumnAndRow(23, 1)->getValue() != "IMAGEN 3")
        ) {
            return false;
        } else {
            return true;
        }
    }

    public function check_campos($objWorksheet, $totalrows) {
        for ($i = 2; $i <= $totalrows; $i++) {
            if ($objWorksheet->getCellByColumnAndRow(0, $i)->getValue() == '') {
                echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                . 'El valor de la columna TITULO de la celda ' . $i . ' esta vacio!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                die;
            } elseif ($objWorksheet->getCellByColumnAndRow(1, $i)->getValue() == '') {
                echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                . 'El valor de la columna ARTICULO de la celda ' . $i . ' esta vacio!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                die;
            } elseif ($objWorksheet->getCellByColumnAndRow(3, $i)->getValue() == '') {
                echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                . 'El valor de la columna CATEGORIA de la celda ' . $i . ' esta vacio!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                die;
            } elseif ($objWorksheet->getCellByColumnAndRow(21, $i)->getValue() == '') {
                echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                . 'El valor de la columna IMAGEN 1 de la celda ' . $i . ' esta vacio!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                die;
            } elseif ($objWorksheet->getCellByColumnAndRow(11, $i)->getValue() == '') {
                echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                . 'El valor de la columna PRECIO MAYORISTA de la celda ' . $i . ' esta vacio!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                die;
            } elseif ($objWorksheet->getCellByColumnAndRow(11, $i)->getValue() != '') {
                $var = floatval($objWorksheet->getCellByColumnAndRow(11, $i)->getValue());
                if ($var == 0) {
                    echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                    . 'El valor de la columna PRECIO MAYORISTA de la celda ' . $i . ' es incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                    die;
                }
            } elseif ($objWorksheet->getCellByColumnAndRow(12, $i)->getValue() == '') {
                echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                . 'El valor de la columna PRECIO OFERTA MAYORISTA de la celda ' . $i . ' esta vacio!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                die;
            } elseif ($objWorksheet->getCellByColumnAndRow(12, $i)->getValue() != '') {
                $var = floatval($objWorksheet->getCellByColumnAndRow(12, $i)->getValue());
                if ($var == 0) {
                    echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                    . 'El valor de la columna PRECIO OFERTA MAYORISTA de la celda ' . $i . ' es incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                    die;
                }
            } elseif ($objWorksheet->getCellByColumnAndRow(13, $i)->getValue() == '') {
                echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                . 'El valor de la columna PRECIO REVENDEDOR de la celda ' . $i . ' esta vacio!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                die;
            } elseif ($objWorksheet->getCellByColumnAndRow(13, $i)->getValue() != '') {
                $var = floatval($objWorksheet->getCellByColumnAndRow(13, $i)->getValue());
                if ($var == 0) {
                    echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                    . 'El valor de la columna PRECIO REVENDEDOR de la celda ' . $i . ' es incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                    die;
                }
            } elseif ($objWorksheet->getCellByColumnAndRow(14, $i)->getValue() == '') {
                echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                . 'El valor de la columna PRECIO OFERTA REVENDEDOR de la celda ' . $i . ' esta vacio!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                die;
            } elseif ($objWorksheet->getCellByColumnAndRow(14, $i)->getValue() != '') {
                $var = floatval($objWorksheet->getCellByColumnAndRow(14, $i)->getValue());
                if ($var == 0) {
                    echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                    . 'El valor de la columna PRECIO OFERTA REVENDEDOR de la celda ' . $i . ' es incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                    die;
                }
            } elseif ($objWorksheet->getCellByColumnAndRow(15, $i)->getValue() != '') {
                $var = floatval($objWorksheet->getCellByColumnAndRow(15, $i)->getValue());
                if ($var == 0) {
                    echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                    . 'El valor de la columna PRECIO X 10 MAYORISTA de la celda ' . $i . ' es incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                    die;
                }
            } elseif ($objWorksheet->getCellByColumnAndRow(16, $i)->getValue() != '') {
                $var = floatval($objWorksheet->getCellByColumnAndRow(16, $i)->getValue());
                if ($var == 0) {
                    echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                    . 'El valor de la columna PRECIO X 10 REVENDEDOR de la celda ' . $i . ' es incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                    die;
                }
            } elseif ($objWorksheet->getCellByColumnAndRow(17, $i)->getValue() != '') {
                $var = floatval($objWorksheet->getCellByColumnAndRow(17, $i)->getValue());
                if ($var == 0) {
                    echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                    . 'El valor de la columna PRECIO X 10 OFERTA MAYORISTA de la celda ' . $i . ' es incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                    die;
                }
            } elseif ($objWorksheet->getCellByColumnAndRow(18, $i)->getValue() != '') {
                $var = floatval($objWorksheet->getCellByColumnAndRow(18, $i)->getValue());
                if ($var == 0) {
                    echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                    . 'El valor de la columna PRECIO X 10 OFERTA REVENDEDOR de la celda ' . $i . ' es incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                    die;
                }
            } elseif ($objWorksheet->getCellByColumnAndRow(10, $i)->getValue() != '') {
                $array = explode(",", ($objWorksheet->getCellByColumnAndRow(10, $i)->getValue() . ","));
                foreach ($array as $value) {
                    if (strlen($value) != 0) {
                        $id = $this->db->query("SELECT * FROM talles WHERE UPPER(nombre) = UPPER('" . trim($value) . "') AND borrado='no'");
                        if ($id->num_rows() == 0) {
                            echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                            . 'El valor ' . $value . ' de la columna TALLES de la celda ' . $i . ' no existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                            die;
                        }
                    }
                }
            } elseif ($objWorksheet->getCellByColumnAndRow(9, $i)->getValue() != '') {
                $array = explode(",", ($objWorksheet->getCellByColumnAndRow(9, $i)->getValue() . ","));
                foreach ($array as $value) {
                    if (strlen($value) != 0) {
                        $id = $this->db->query("SELECT * FROM colores WHERE UPPER(nombre) = UPPER('" . trim($value) . "') AND borrado='no'");
                        if ($id->num_rows() == 0) {
                            $res[1] = $value;
                            $res[2] = $i;
                            echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                            . 'El valor ' . $value . ' de la columna COLORES de la celda ' . $i . ' no existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                            die;
                        }
                    }
                }
            } elseif ($objWorksheet->getCellByColumnAndRow(8, $i)->getValue() != '') {
                $array = explode(",", ($objWorksheet->getCellByColumnAndRow(8, $i)->getValue() . ","));
                foreach ($array as $value) {
                    if (strlen($value) != 0) {
                        $id = $this->db->query("SELECT * FROM medidas WHERE UPPER(nombre) = UPPER('" . trim($value) . "') AND borrado='no'");
                        if ($id->num_rows() == 0) {
                            $res[1] = $value;
                            $res[2] = $i;
                            echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">'
                            . 'El valor ' . $value . ' de la columna MEDIDAS de la celda ' . $i . ' no existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                            die;
                        }
                    }
                }
            }
        }
        return TRUE;
    }

    public function ExcelDataAdd() {

        $this->load->library('excel'); //load PHPExcel library 
        $configUpload['upload_path'] = FCPATH . 'uploads/excel/';
        $configUpload['allowed_types'] = 'xls|xlsx|csv';
        $configUpload['max_size'] = '5000';
        $this->load->library('upload', $configUpload);
        $this->upload->do_upload('excelfile');
        $upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.
        $file_name = $upload_data['file_name']; //uploded file name
        $extension = $upload_data['file_ext'];    // uploded file extension
        //$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
        $objReader = PHPExcel_IOFactory::createReader('Excel2007'); // For excel 2007 	  
        $objReader->setReadDataOnly(true);
        $objPHPExcel = $objReader->load(FCPATH . 'uploads/excel/' . $file_name);
        $totalrows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Numbe of rows avalable in excel      	 
        $objWorksheet = $objPHPExcel->setActiveSheetIndex(0);
        $check_formato = $this->check_formato($objWorksheet);
        if ($check_formato) {
            $check_campos = $this->check_campos($objWorksheet, $totalrows);
        } else {
            echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;">
                 <label class="control-label col-md-3" style="width:100%;">Formato de importación incorrecto!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
            die;
        }

        if ($check_campos) {
            $this->db->trans_begin();
            for ($i = 2; $i <= $totalrows; $i++) {
                $titulo = $objWorksheet->getCellByColumnAndRow(0, $i)->getValue();
                if ($titulo != '') {
                    $articulo = $objWorksheet->getCellByColumnAndRow(1, $i)->getValue();
                    $descripcion_corta = $objWorksheet->getCellByColumnAndRow(2, $i)->getValue();
                    $categoria = $objWorksheet->getCellByColumnAndRow(3, $i)->getValue();
                    $id_categoria = $this->db->query("SELECT * FROM categorias WHERE UPPER(nombre) = UPPER('" . trim($categoria) . "') AND borrado='no'");
                    $cant_cat = $id_categoria->num_rows();
                    if ($cant_cat != 0) {
                        $id_cat = $id_categoria->row();
                        $subcategoria = $objWorksheet->getCellByColumnAndRow(4, $i)->getValue();
                        if ($subcategoria == '') {
                            $ok = true;
                        } else {
                            $id_subcategoria = $this->db->query("SELECT * FROM subcategorias WHERE UPPER(nombre) = UPPER('" . trim($subcategoria) . "') AND borrado='no' AND categoria_padre = $id_cat->id ");
                            $cant_sub = $id_subcategoria->num_rows();
                            if ($cant_sub != 0) {
                                $id_subcat = $id_subcategoria->row();
                                $subcategoria = $id_subcat->id;
                                $ok = true;
                            } else {
                                $ok = false;
                            }
                        }
                        if ($ok) {
                            $oferta = $objWorksheet->getCellByColumnAndRow(5, $i)->getValue();
                            if (strlen($oferta) == 0) {
                                $oferta = 'NO';
                            } else {
                                $oferta = strtoupper($oferta);
                            }
                            $nuevo = $objWorksheet->getCellByColumnAndRow(6, $i)->getValue();
                            if (strlen($nuevo) == 0) {
                                $nuevo = 'NO';
                            } else {
                                $nuevo = strtoupper($nuevo);
                            }
                            $video = $objWorksheet->getCellByColumnAndRow(7, $i)->getValue();
                            $medidas = $objWorksheet->getCellByColumnAndRow(8, $i)->getValue();
                            $colores = $objWorksheet->getCellByColumnAndRow(9, $i)->getValue();
                            $talles = $objWorksheet->getCellByColumnAndRow(10, $i)->getValue();
                            $precio_mayorista = $objWorksheet->getCellByColumnAndRow(11, $i)->getValue();
                            $precio_oferta_mayorista = $objWorksheet->getCellByColumnAndRow(12, $i)->getValue();
                            $precio_revendedor = $objWorksheet->getCellByColumnAndRow(13, $i)->getValue();
                            $precio_oferta_revendedor = $objWorksheet->getCellByColumnAndRow(14, $i)->getValue();
                            $precio_10_mayorista = $objWorksheet->getCellByColumnAndRow(15, $i)->getValue();
                            $precio_10_revendedor = $objWorksheet->getCellByColumnAndRow(16, $i)->getValue();
                            $precio_10_oferta_mayorista = $objWorksheet->getCellByColumnAndRow(17, $i)->getValue();
                            $precio_10_oferta_revendedor = $objWorksheet->getCellByColumnAndRow(18, $i)->getValue();
                            $stock = $objWorksheet->getCellByColumnAndRow(19, $i)->getValue();
                            $descripcion = $objWorksheet->getCellByColumnAndRow(20, $i)->getValue();
                            $imagen_1 = $objWorksheet->getCellByColumnAndRow(21, $i)->getValue();
                            $imagen_2 = $objWorksheet->getCellByColumnAndRow(22, $i)->getValue();
                            $imagen_3 = $objWorksheet->getCellByColumnAndRow(23, $i)->getValue();
                            $data = array("titulo" => $titulo, "articulo" => $articulo, "descripcion_corta" => $descripcion_corta, "oferta" => $oferta, "nuevo" => $nuevo, "video" => $video, "precio_mayorista" => $this->tofloat($precio_mayorista), "precio_oferta_mayorista" => $this->tofloat($precio_oferta_mayorista), "precio_revendedor" => $this->tofloat($precio_revendedor), "precio_oferta_revendedor" => $this->tofloat($precio_oferta_revendedor), "precio_por_diez_mayorista" => $this->tofloat($precio_10_mayorista), "precio_por_diez_revendedor" => $this->tofloat($precio_10_revendedor), "precio_por_diez_oferta_mayorista" => $this->tofloat($precio_10_oferta_mayorista), "precio_por_diez_oferta_revendedor" => $this->tofloat($precio_10_oferta_revendedor), "descripcion" => $descripcion, "stock" => $stock, "file1" => $imagen_1, "file2" => $imagen_2, "file3" => $imagen_3, "categoria_id" => $id_cat->id, "sub_categoria_id" => $subcategoria, "fecha_insert" => date("Y-m-d H:i:s"));
                            $config = array('field' => 'slug', 'title' => 'title', 'table' => 'producto', 'id' => 'id',);
                            $this->load->library('slug', $config);
                            $data['slug'] = $this->slug->create_uri(array('title' => $titulo));
                            $insert = $this->obj_model->save($data);

                            if (($insert)and ( strlen($talles) != 0)) {
                                $array_talles = explode(",", ($talles . ","));
                                $data_talles = array();
                                foreach ($array_talles as $value1) {
                                    if (strlen($value1) != 0) {
                                        $id_talles = $this->db->query("SELECT * FROM talles WHERE UPPER(nombre) = UPPER('" . trim($value1) . "') AND borrado='no'");
                                        if ($id_talles->num_rows() != 0) {
                                            $id_t = $id_talles->row();
                                            $data_talles[] = array('producto_id' => $insert, 'talle_id' => $id_t->id);
                                        } else {
                                            $this->db->trans_rollback();
                                            echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"><label class="control-label col-md-3" style="width:100%;">El talle ' . $value1 . ' de la celda ' . $i . ' no existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                                            die;
                                        }
                                    }
                                }
                                $this->obj_model->insert_multiple('producto_talles', $data_talles);
                            }

                            if (($insert)and ( strlen($colores) != 0)) {
                                $array_colores = explode(",", ($colores . ","));
                                $data_colores = array();
                                foreach ($array_colores as $value2) {
                                    if (strlen($value2) != 0) {
                                        $id_color = $this->db->query("SELECT * FROM colores WHERE UPPER(nombre) = UPPER('" . trim($value2) . "') AND borrado='no'");
                                        if ($id_color->num_rows() != 0) {
                                            $id_c = $id_color->row();
                                            $data_colores[] = array('producto_id' => $insert, 'color_id' => $id_c->id);
                                        } else {
                                            $this->db->trans_rollback();
                                            echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">El color ' . $value2 . ' de la celda ' . $i . ' no existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                                            die;
                                        }
                                    }
                                }
                                $this->obj_model->insert_multiple('producto_colores', $data_colores);
                            }

                            if (($insert)and ( strlen($medidas) != 0)) {
                                $array_medidas = explode(",", ($medidas . ","));
                                $data_medidas = array();
                                foreach ($array_medidas as $value3) {
                                    if (strlen($value3) != 0) {
                                        $id_medida = $this->db->query("SELECT * FROM medidas WHERE UPPER(nombre) = UPPER('" . trim($value3) . "') AND borrado='no'");
                                        if ($id_medida->num_rows() != 0) {
                                            $id_m = $id_medida->row();
                                            $data_medidas[] = array('producto_id' => $insert, 'medida_id' => $id_m->id);
                                        } else {
                                            $this->db->trans_rollback();
                                            echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;"> <label class="control-label col-md-3" style="width:100%;">La medida ' . $value3 . ' de la celda ' . $i . ' no existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                                            die;
                                        }
                                    }
                                }
                                $this->obj_model->insert_multiple('producto_medidas', $data_medidas);
                            }
                        } else {
                            // error subcategoria  
                            $this->db->trans_rollback();
                            echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;">
                                <label class="control-label col-md-3" style="width:100%;">La subcategoria ' . $subcategoria . ' de la celda ' . $i . ' no existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                            die;
                        }
                    } else {
                        // error categoria  
                        $this->db->trans_rollback();
                        echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;">
                       <label class="control-label col-md-3" style="width:100%;">La categoria ' . $categoria . ' de la celda ' . $i . ' no existe!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
                        die;
                    }
                }
            }
        } else {

            die;
        }
        if ($this->db->trans_status() === FALSE) {
            //error al guardar
            $this->db->trans_rollback();
            echo '<link href="' . base_url() . 'assets/web/css/bootstrap.min.css" rel="stylesheet"><body><div class="modal-backdrop fade in" style="z-index:-1;"></div><div class="modalx fade"  role="dialog" style="display:block;opacity:1;"><div class="modal-dialog"><div class="modal-content"><div class="modal-header"><h3 class="modal-title"> Error al importar desde Excel</h3></div><div class="modal-body form" style="margin-bottom:15px;">
                   <label class="control-label col-md-3" style="width:100%;">Error al guardar!</label></div><div class="modal-footer"><button type="button" onClick="history.back()" class="btn btn-danger" data-dismiss="modal">Atras</button></div></div></div></div></div></body>';
            die;
        } else {
            $this->db->trans_commit();
        }
        unlink(FCPATH . '/uploads/excel/' . $file_name); //File Deleted After uploading in database .			 

        redirect(base_url() . "producto");
    }

    public function ajax_add() {

        $this->form_validation->set_rules("titulo", "Titulo", "required|xss_clean|trim");
        $this->form_validation->set_rules("articulo", "Articulo", "required|xss_clean|trim");
        $this->form_validation->set_rules("descripcion_corta", "Descripcion corta", "required|xss_clean|trim");
        $this->form_validation->set_rules("oferta", "Oferta", "xss_clean|trim");
        $this->form_validation->set_rules("nuevo", "Nuevo", "xss_clean|trim");
        $this->form_validation->set_rules("video", "Video", "xss_clean|trim");
        $this->form_validation->set_rules("precio_mayorista", "Precio mayorista", "required|xss_clean|trim|numeric");
        $this->form_validation->set_rules("precio_oferta_mayorista", "Precio oferta mayorista", "xss_clean|trim|numeric");
        $this->form_validation->set_rules("precio_revendedor", "Precio revendedor", "required|xss_clean|trim|numeric");
        $this->form_validation->set_rules("precio_oferta_revendedor", "Precio oferta revendedor", "xss_clean|trim|numeric");
        //$this->form_validation->set_rules("precio_por_diez_mayorista", "Precio por diez mayorista", "xss_clean|trim|numeric");
        //$this->form_validation->set_rules("precio_por_diez_revendedor", "Precio por diez revendedor", "xss_clean|trim|numeric");
        $this->form_validation->set_rules("precio_por_diez_oferta_mayorista", "Precio por diez oferta mayorista", "xss_clean|trim|numeric");
        $this->form_validation->set_rules("precio_por_diez_oferta_revendedor", "Precio por diez oferta revendedor", "xss_clean|trim|numeric");
        $this->form_validation->set_rules("descripcion", "Descripcion", "xss_clean|trim");
        $this->form_validation->set_rules("stock", "Stock", "required|xss_clean|trim|integer");
        $this->form_validation->set_rules("file1", "File1", "required|xss_clean|trim");
        $this->form_validation->set_rules("file2", "File2", "xss_clean|trim");
        $this->form_validation->set_rules("file3", "File3", "xss_clean|trim");

        $this->form_validation->set_rules("categoria_id", "Categoria", "xss_clean|trim|callback_check_default_combox_categoria");
        $this->form_validation->set_message('check_default_combox_categoria', 'Debe seleccionar una categoria');

        //$this->form_validation->set_rules("sub_categoria_id", "Sub-Categoria", "xss_clean|trim|callback_check_default_combox_subcategoria");
        //$this->form_validation->set_message('check_default_combox_subcategoria', 'Debe seleccionar una sub-categoria');
        //$this->form_validation->set_rules('colores[]', 'Colores', 'required');
        //$this->form_validation->set_rules('medidas[]', 'Medidas', 'required');
        // $this->form_validation->set_rules('talles[]', 'Talles', 'required');

        if ($this->form_validation->run() == FALSE) {

            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data_talles = array();

            $data = array(
                "titulo" => $this->input->post("titulo"),
                "articulo" => $this->input->post("articulo"),
                "descripcion_corta" => $this->input->post("descripcion_corta"),
                "oferta" => (!empty($this->input->post("oferta"))) ? 'SI' : 'NO',
                "nuevo" => (!empty($this->input->post("nuevo"))) ? 'SI' : 'NO',
                "video" => $this->input->post("video"),
                "precio_mayorista" => $this->input->post("precio_mayorista"),
                "precio_oferta_mayorista" => $this->input->post("precio_oferta_mayorista"),
                "precio_revendedor" => $this->input->post("precio_revendedor"),
                "precio_oferta_revendedor" => $this->input->post("precio_oferta_revendedor"),
                "precio_por_diez_mayorista" => $this->input->post("precio_por_diez_mayorista"),
                "precio_por_diez_revendedor" => $this->input->post("precio_por_diez_revendedor"),
                "precio_por_diez_oferta_mayorista" => $this->input->post("precio_por_diez_oferta_mayorista"),
                "precio_por_diez_oferta_revendedor" => $this->input->post("precio_por_diez_oferta_revendedor"),
                "descripcion" => $this->input->post("descripcion"),
                "stock" => $this->input->post("stock"),
                "file1" => $this->input->post("file1"),
                "file2" => $this->input->post("file2"),
                "file3" => $this->input->post("file3"),
                "hay" => $this->input->post("hay"),
                "categoria_id" => $this->input->post("categoria_id"),
                "sub_categoria_id" => $this->input->post("sub_categoria_id"),
                "fecha_insert" => date("Y-m-d H:i:s")
            );

            $config = array(
                'field' => 'slug',
                'title' => 'title',
                'table' => 'producto',
                'id' => 'id',
            );
            $this->load->library('slug', $config);

            $data['slug'] = $this->slug->create_uri(array('title' => $this->input->post("titulo")));

            $this->db->trans_start();

            $insert = $this->obj_model->save($data);

            if ($insert) {

                $data_talles = array();

                if (!empty($this->input->post("talles[]"))) {

                    foreach ($this->input->post("talles[]") as $value1) {

                        $data_talles[] = array('producto_id' => $insert, 'talle_id' => $value1);
                    }

                    $this->obj_model->insert_multiple('producto_talles', $data_talles);
                }
            }

            if ($insert) {

                if (!empty($this->input->post("colores[]"))) {

                    $data_colores = array();

                    foreach ($this->input->post("colores[]") as $value2) {

                        $data_colores[] = array('producto_id' => $insert, 'color_id' => $value2);
                    }

                    $this->obj_model->insert_multiple('producto_colores', $data_colores);
                }
            }

            if ($insert) {

                if (!empty($this->input->post("medidas[]"))) {

                    $data_medidas = array();

                    foreach ($this->input->post("medidas[]") as $value3) {

                        $data_medidas[] = array('producto_id' => $insert, 'medida_id' => $value3);
                    }

                    $this->obj_model->insert_multiple('producto_medidas', $data_medidas);
                }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();

                echo json_encode(array("status" => FALSE));
            } else {

                $this->db->trans_commit();

                if ($this->input->post('hay') == 'no') {

                    $this->enviar_alerta(array('titulo' => $this->input->post('titulo').'-'.$this->input->post('articulo'), 'descripcion' => $this->input->post('descripcion_corta'), 'imagen'=>$this->input->post("file1")));
                }

                echo json_encode(array("status" => TRUE));
            }
        }
    }

    public function ajax_update() {

        $this->form_validation->set_rules("titulo", "Titulo", "required|xss_clean|trim");
        $this->form_validation->set_rules("articulo", "Articulo", "required|xss_clean|trim");
        $this->form_validation->set_rules("descripcion_corta", "Descripcion corta", "required|xss_clean|trim");
        $this->form_validation->set_rules("oferta", "Oferta", "xss_clean|trim");
        $this->form_validation->set_rules("nuevo", "Nuevo", "xss_clean|trim");
        $this->form_validation->set_rules("video", "Video", "xss_clean|trim");
        $this->form_validation->set_rules("precio_mayorista", "Precio mayorista", "required|xss_clean|trim|numeric");
        $this->form_validation->set_rules("precio_oferta_mayorista", "Precio oferta mayorista", "xss_clean|trim|numeric");
        $this->form_validation->set_rules("precio_revendedor", "Precio revendedor", "required|xss_clean|trim|numeric");
        $this->form_validation->set_rules("precio_oferta_revendedor", "Precio oferta revendedor", "xss_clean|trim|numeric");
        //$this->form_validation->set_rules("precio_por_diez_mayorista", "Precio por diez mayorista", "xss_clean|trim|numeric");
        //$this->form_validation->set_rules("precio_por_diez_revendedor", "Precio por diez revendedor", "xss_clean|trim|numeric");
        $this->form_validation->set_rules("precio_por_diez_oferta_mayorista", "Precio por diez oferta mayorista", "xss_clean|trim|numeric");
        $this->form_validation->set_rules("precio_por_diez_oferta_revendedor", "Precio por diez oferta revendedor", "xss_clean|trim|numeric");
        $this->form_validation->set_rules("descripcion", "Descripcion", "xss_clean|trim");
        $this->form_validation->set_rules("stock", "Stock", "required|xss_clean|trim|integer");
        $this->form_validation->set_rules("file1", "File1", "required|xss_clean|trim");
        $this->form_validation->set_rules("file2", "File2", "xss_clean|trim");
        $this->form_validation->set_rules("file3", "File3", "xss_clean|trim");

        $this->form_validation->set_rules("categoria_id", "Categoria", "xss_clean|trim|callback_check_default_combox_categoria");

        $this->form_validation->set_message('check_default_combox_categoria', 'Debe seleccionar una categoria');

        //$this->form_validation->set_rules("sub_categoria_id", "Sub-Categoria", "xss_clean|trim|callback_check_default_combox_subcategoria");
        //$this->form_validation->set_message('check_default_combox_subcategoria', 'Debe seleccionar una sub-categoria');
        //$this->form_validation->set_rules('colores[]', 'Colores', 'required');
        //$this->form_validation->set_rules('medidas[]', 'Medidas', 'required');
        //$this->form_validation->set_rules('talles[]', 'Talles', 'required');

        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "titulo" => $this->input->post("titulo"),
                "articulo" => $this->input->post("articulo"),
                "descripcion_corta" => $this->input->post("descripcion_corta"),
                "oferta" => (!empty($this->input->post("oferta"))) ? 'SI' : 'NO',
                "nuevo" => (!empty($this->input->post("nuevo"))) ? 'SI' : 'NO',
                "video" => $this->input->post("video"),
                "precio_mayorista" => $this->input->post("precio_mayorista"),
                "precio_oferta_mayorista" => $this->input->post("precio_oferta_mayorista"),
                "precio_revendedor" => $this->input->post("precio_revendedor"),
                "precio_oferta_revendedor" => $this->input->post("precio_oferta_revendedor"),
                "precio_por_diez_mayorista" => $this->input->post("precio_por_diez_mayorista"),
                "precio_por_diez_revendedor" => $this->input->post("precio_por_diez_revendedor"),
                "precio_por_diez_oferta_mayorista" => $this->input->post("precio_por_diez_oferta_mayorista"),
                "precio_por_diez_oferta_revendedor" => $this->input->post("precio_por_diez_oferta_revendedor"),
                "descripcion" => $this->input->post("descripcion"),
                "stock" => $this->input->post("stock"),
                "file1" => $this->input->post("file1"),
                "file2" => $this->input->post("file2"),
                "file3" => $this->input->post("file3"),
                "hay" => $this->input->post("hay"),
                "categoria_id" => $this->input->post("categoria_id"),
                "sub_categoria_id" => $this->input->post("sub_categoria_id"),
                "fecha_update" => date("Y-m-d H:i:s")
            );

            $config = array(
                'field' => 'slug',
                'title' => 'title',
                'table' => 'producto',
                'id' => 'id',
            );
            $this->load->library('slug', $config);

            $data['slug'] = $this->slug->create_uri(array('title' => $this->input->post("titulo")), $this->input->post('id'));

            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);

            if ($this->input->post('hay') == 'no') {

                $this->enviar_alerta(array('titulo' => $this->input->post('titulo').'-'.$this->input->post('articulo'), 'descripcion' => $this->input->post('descripcion_corta'), 'imagen'=>$this->input->post("file1")));
            }

            if ($res) {

                $this->obj_model->delete_all_from('producto_talles', $this->input->post('id'));

                $data_talles = array();

                if (!empty($this->input->post("talles[]"))) {

                    foreach ($this->input->post("talles[]") as $value1) {

                        $data_talles[] = array('producto_id' => $this->input->post('id'), 'talle_id' => $value1);
                    }

                    $this->obj_model->insert_multiple('producto_talles', $data_talles);
                }
            }

            if ($res) {

                $this->obj_model->delete_all_from('producto_colores', $this->input->post('id'));

                $data_colores = array();

                if (!empty($this->input->post("colores[]"))) {

                    $data_colores = array();

                    foreach ($this->input->post("colores[]") as $value2) {

                        $data_colores[] = array('producto_id' => $this->input->post('id'), 'color_id' => $value2);
                    }

                    $this->obj_model->insert_multiple('producto_colores', $data_colores);
                }
            }

            if ($res) {

                $this->obj_model->delete_all_from('producto_medidas', $this->input->post('id'));

                $data_medidas = array();

                if (!empty($this->input->post("medidas[]"))) {

                    $data_medidas = array();

                    foreach ($this->input->post("medidas[]") as $value3) {

                        $data_medidas[] = array('producto_id' => $this->input->post('id'), 'medida_id' => $value3);
                    }

                    $this->obj_model->insert_multiple('producto_medidas', $data_medidas);
                }
            }


            echo json_encode(array("status" => $res));
        }
    }

    public function import_excel() {

        $msg['controller'] = $this->controller;

        $msg['output'] = $this->load->view($this->folder_view . '/import_excel', $msg, TRUE);

        $this->load->view('dashboard', $msg);
    }

    public function add($id = 0, $page=1) {

        $msg['controller'] = $this->controller;

        $msg['categorias'] = $this->generate_data_dropdown('categorias', 'nombre', FALSE);

        $msg['subcategorias'] = $this->generate_data_dropdown('subcategorias', 'nombre', FALSE);

        $msg['medidas'] = $this->generate_data_dropdown('medidas', 'nombre', FALSE);

        $msg['talles'] = $this->generate_data_dropdown('talles', 'nombre', FALSE);

        $msg['colores'] = $this->generate_data_dropdown('colores', 'nombre', FALSE);

        $msg['colores_selected'] = array();

        $msg['talles_selected'] = array();

        $msg['medidas_selected'] = array();

        unset($msg['medidas'][0]);

        unset($msg['talles'][0]);

        unset($msg['colores'][0]);

        $msg['url_images'] = $this->files_urlpath_s;

        $msg['accion'] = 'add';

        if ($id != 0) {
            $msg['id'] = $id;

            $msg['colores_selected'] = $this->obj_model->get_colores($id);

            $msg['talles_selected'] = $this->obj_model->get_talles($id);

            $msg['medidas_selected'] = $this->obj_model->get_medidas($id);
        }
        if ($id != 0) {
            $msg['accion'] = 'update';
        }
        
        $msg['page_view'] = $page;

        $msg['output'] = $this->load->view($this->folder_view . '/add', $msg, TRUE);

        $this->load->view('dashboard', $msg);
    }

    public function check_default_combox_categoria($str) {
        return parent::check_default_combox($str);
    }

    public function check_default_combox_subcategoria($str) {
        return parent::check_default_combox($str);
    }

    public function multiple_select_colores() {
        $arr_course = $this->input->post('colores[]');
        if (empty($arr_course)):
            $this->form_validation->set_rules('colores', 'Debe seleccionar al menos un color');
            return false;
        endif;
    }

    public function multiple_select_talles() {
        $arr_course = $this->input->post('talles[]');
        if (empty($arr_course)):
            $this->form_validation->set_rules('talles', 'Debe seleccionar al menos un talle');
            return false;
        endif;
    }

    public function multiple_select_medidas() {
        $arr_course = $this->input->post('medidas[]');
        if (empty($arr_course)):
            $this->form_validation->set_rules('medidas', 'Debe seleccionar al menos una medida');
            return false;
        endif;
    }

    public function update_stock() {

        $data = array(
            "stock" => $this->input->post("stock"),
            "fecha_update" => date("Y-m-d H:i:s")
        );
        $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);

        echo json_encode(array("status" => $res));
    }

    private function enviar_alerta($data = array()) {

        $this->load->model('Config_emails_model', 'config_email');

        $email_data = $this->config_email->get_last();

        $this->email_from = $email_data->smtp_email_from;

        $this->email_name = $email_data->smtp_email_name;

        $this->email_pass = $email_data->smtp_email_pass;

        $this->email_pedido = $email_data->email_envio_pedido;

        $this->email_quiebre = $email_data->email_envio_quiebre;

        $this->email_host = $email_data->smtp_host;

        $this->email_contacto = $email_data->email_contacto;

        require_once APPPATH . '/external_libs/phpmailer/PHPMailerAutoload.php';

        require_once APPPATH . '/external_libs/mailin-api/Mailin.php';

        $mail = new PHPMailer();

        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true));

        $mail->Host = $this->email_host; // SMTP a utilizar. Por ej. smtp.elserver.com
        $mail->Username = $this->email_from; // Correo completo a utilizar
        $mail->Password = $this->email_pass; // Contraseña
        $mail->Port = 587; // Puerto a utilizar

        $mail->From = $this->email_from; // Desde donde enviamos (Para mostrar)
        $mail->FromName = $this->email_name;


        $mail->AddAddress($this->email_quiebre); // Esta es la dirección a donde enviamos
        $mail->IsHTML(true); // El correo se envía como HTML
        $mail->Subject = 'Producto marcado como "NO HAY"'; // Este es el titulo del email.

        $mail->Body = $this->load->view('email_templates/alerta_no_hay', $data, true); // Mensaje a enviar

        $exito = $mail->Send(); // Envía el correo.

        if ($exito) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

}
