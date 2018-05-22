<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require_once APPPATH . '/controllers/my_controller.php';

class propiedades extends MY_Controller {

    var $model = 'propiedades_model';
    var $folder_view = 'propiedades';
    var $controller = 'propiedades';
    var $data_view = array();

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load_view();
    }

    public function form($id=0){
        
        $msg['controller'] = $this->controller;
        
        $msg['tipo_propiedades'] = $this->generate_data_dropdown('tipo_propiedades', 'nombre', FALSE);

        $msg['output'] = $this->load->view($this->folder_view.'/add', $msg, TRUE);

        $this->load->view('dashboard', $msg);
        
    }
    
    public function ajax_add() {

        $this->form_validation->set_rules("tipo_propiedad", "Tipo_propiedad", "required|xss_clean|trim");
        $this->form_validation->set_rules("tipo_operacion", "Tipo_operacion", "required|xss_clean|trim");
        $this->form_validation->set_rules("monto_operacion", "Monto_operacion", "required|xss_clean|trim");
        $this->form_validation->set_rules("localidad_id", "Localidad_id", "required|xss_clean|trim");
        $this->form_validation->set_rules("dormitorios", "Dormitorios", "required|xss_clean|trim");
        $this->form_validation->set_rules("patio", "Patio", "required|xss_clean|trim");
        $this->form_validation->set_rules("banios", "Banios", "required|xss_clean|trim");
        $this->form_validation->set_rules("antiguedad", "Antiguedad", "required|xss_clean|trim");
        $this->form_validation->set_rules("garage", "Garage", "required|xss_clean|trim");
        $this->form_validation->set_rules("apto_banco", "Apto_banco", "required|xss_clean|trim");
        $this->form_validation->set_rules("estado", "Estado", "required|xss_clean|trim");
        $this->form_validation->set_rules("superficie_lote", "Superficie_lote", "required|xss_clean|trim");
        $this->form_validation->set_rules("detalles", "Detalles", "required|xss_clean|trim");
        $this->form_validation->set_rules("tamanio_lote", "Tamanio_lote", "required|xss_clean|trim");
        $this->form_validation->set_rules("nro_pisos", "Nro_pisos", "required|xss_clean|trim");
        $this->form_validation->set_rules("orientación", "Orientación", "required|xss_clean|trim");
        $this->form_validation->set_rules("deptos_por_piso", "Deptos_por_piso", "required|xss_clean|trim");
        $this->form_validation->set_rules("superficie_construida", "Superficie_construida", "required|xss_clean|trim");
        $this->form_validation->set_rules("ascensores_priv", "Ascensores_priv", "required|xss_clean|trim");
        $this->form_validation->set_rules("terraza", "Terraza", "required|xss_clean|trim");
        $this->form_validation->set_rules("ascensores_serv", "Ascensores_serv", "required|xss_clean|trim");
        $this->form_validation->set_rules("seguridad", "Seguridad", "required|xss_clean|trim");
        $this->form_validation->set_rules("servicios", "Servicios", "required|xss_clean|trim");
        $this->form_validation->set_rules("calefacción", "Calefacción", "required|xss_clean|trim");
        $this->form_validation->set_rules("gas", "Gas", "required|xss_clean|trim");
        $this->form_validation->set_rules("luminosidad", "Luminosidad", "required|xss_clean|trim");
        $this->form_validation->set_rules("cloacas", "Cloacas", "required|xss_clean|trim");
        $this->form_validation->set_rules("apto_profesional", "Apto_profesional", "required|xss_clean|trim");
        $this->form_validation->set_rules("agua", "Agua", "required|xss_clean|trim");
        $this->form_validation->set_rules("ubicación_en_planta", "Ubicación_en_planta", "required|xss_clean|trim");
        $this->form_validation->set_rules("asfalto", "Asfalto", "required|xss_clean|trim");
        $this->form_validation->set_rules("piso", "Piso", "required|xss_clean|trim");
        $this->form_validation->set_rules("energia", "Energia", "required|xss_clean|trim");
        $this->form_validation->set_rules("dependencia_de_servicio", "Dependencia_de_servicio", "required|xss_clean|trim");
        $this->form_validation->set_rules("telefono", "Telefono", "required|xss_clean|trim");
        $this->form_validation->set_rules("ambientes", "Ambientes", "required|xss_clean|trim");
        $this->form_validation->set_rules("cable", "Cable", "required|xss_clean|trim");
        $this->form_validation->set_rules("patio", "Patio", "required|xss_clean|trim");
        $this->form_validation->set_rules("direccion", "Direccion", "required|xss_clean|trim");
        $this->form_validation->set_rules("zona_barrio_id", "Zona_barrio_id", "required|xss_clean|trim");
        $this->form_validation->set_rules("descripcion_zona", "Descripcion_zona", "required|xss_clean|trim");
        $this->form_validation->set_rules("descripcion", "Descripcion", "required|xss_clean|trim");
        $this->form_validation->set_rules("codigo", "Codigo", "required|xss_clean|trim");
        $this->form_validation->set_rules("moneda_operacion", "Moneda_operacion", "required|xss_clean|trim");
        $this->form_validation->set_rules("expensas", "Expensas", "required|xss_clean|trim");
        $this->form_validation->set_rules("monto_expensas", "Monto_expensas", "required|xss_clean|trim");


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {
            $data = array(
                "tipo_propiedad" => $this->input->post("tipo_propiedad"),
                "tipo_operacion" => $this->input->post("tipo_operacion"),
                "monto_operacion" => $this->input->post("monto_operacion"),
                "localidad_id" => $this->input->post("localidad_id"),
                "dormitorios" => $this->input->post("dormitorios"),
                "patio" => $this->input->post("patio"),
                "banios" => $this->input->post("banios"),
                "antiguedad" => $this->input->post("antiguedad"),
                "garage" => $this->input->post("garage"),
                "apto_banco" => $this->input->post("apto_banco"),
                "estado" => $this->input->post("estado"),
                "superficie_lote" => $this->input->post("superficie_lote"),
                "detalles" => $this->input->post("detalles"),
                "tamanio_lote" => $this->input->post("tamanio_lote"),
                "nro_pisos" => $this->input->post("nro_pisos"),
                "orientación" => $this->input->post("orientación"),
                "deptos_por_piso" => $this->input->post("deptos_por_piso"),
                "superficie_construida" => $this->input->post("superficie_construida"),
                "ascensores_priv" => $this->input->post("ascensores_priv"),
                "terraza" => $this->input->post("terraza"),
                "ascensores_serv" => $this->input->post("ascensores_serv"),
                "seguridad" => $this->input->post("seguridad"),
                "servicios" => $this->input->post("servicios"),
                "calefacción" => $this->input->post("calefacción"),
                "gas" => $this->input->post("gas"),
                "luminosidad" => $this->input->post("luminosidad"),
                "cloacas" => $this->input->post("cloacas"),
                "apto_profesional" => $this->input->post("apto_profesional"),
                "agua" => $this->input->post("agua"),
                "ubicación_en_planta" => $this->input->post("ubicación_en_planta"),
                "asfalto" => $this->input->post("asfalto"),
                "piso" => $this->input->post("piso"),
                "energia" => $this->input->post("energia"),
                "dependencia_de_servicio" => $this->input->post("dependencia_de_servicio"),
                "telefono" => $this->input->post("telefono"),
                "ambientes" => $this->input->post("ambientes"),
                "cable" => $this->input->post("cable"),
                "patio" => $this->input->post("patio"),
                "direccion" => $this->input->post("direccion"),
                "zona_barrio_id" => $this->input->post("zona_barrio_id"),
                "descripcion_zona" => $this->input->post("descripcion_zona"),
                "descripcion" => $this->input->post("descripcion"),
                "codigo" => $this->input->post("codigo"),
                "moneda_operacion" => $this->input->post("moneda_operacion"),
                "expensas" => $this->input->post("expensas"),
                "monto_expensas" => $this->input->post("monto_expensas"),
            );
            $insert = $this->obj_model->save($data);
            echo json_encode(array("status" => $insert));
        }
    }

    public function ajax_update() {

        $this->form_validation->set_rules("tipo_propiedad", "Tipo_propiedad", "required|xss_clean|trim");
        $this->form_validation->set_rules("tipo_operacion", "Tipo_operacion", "required|xss_clean|trim");
        $this->form_validation->set_rules("monto_operacion", "Monto_operacion", "required|xss_clean|trim");
        $this->form_validation->set_rules("localidad_id", "Localidad_id", "required|xss_clean|trim");
        $this->form_validation->set_rules("dormitorios", "Dormitorios", "required|xss_clean|trim");
        $this->form_validation->set_rules("patio", "Patio", "required|xss_clean|trim");
        $this->form_validation->set_rules("banios", "Banios", "required|xss_clean|trim");
        $this->form_validation->set_rules("antiguedad", "Antiguedad", "required|xss_clean|trim");
        $this->form_validation->set_rules("garage", "Garage", "required|xss_clean|trim");
        $this->form_validation->set_rules("apto_banco", "Apto_banco", "required|xss_clean|trim");
        $this->form_validation->set_rules("estado", "Estado", "required|xss_clean|trim");
        $this->form_validation->set_rules("superficie_lote", "Superficie_lote", "required|xss_clean|trim");
        $this->form_validation->set_rules("detalles", "Detalles", "required|xss_clean|trim");
        $this->form_validation->set_rules("tamanio_lote", "Tamanio_lote", "required|xss_clean|trim");
        $this->form_validation->set_rules("nro_pisos", "Nro_pisos", "required|xss_clean|trim");
        $this->form_validation->set_rules("orientación", "Orientación", "required|xss_clean|trim");
        $this->form_validation->set_rules("deptos_por_piso", "Deptos_por_piso", "required|xss_clean|trim");
        $this->form_validation->set_rules("superficie_construida", "Superficie_construida", "required|xss_clean|trim");
        $this->form_validation->set_rules("ascensores_priv", "Ascensores_priv", "required|xss_clean|trim");
        $this->form_validation->set_rules("terraza", "Terraza", "required|xss_clean|trim");
        $this->form_validation->set_rules("ascensores_serv", "Ascensores_serv", "required|xss_clean|trim");
        $this->form_validation->set_rules("seguridad", "Seguridad", "required|xss_clean|trim");
        $this->form_validation->set_rules("servicios", "Servicios", "required|xss_clean|trim");
        $this->form_validation->set_rules("calefacción", "Calefacción", "required|xss_clean|trim");
        $this->form_validation->set_rules("gas", "Gas", "required|xss_clean|trim");
        $this->form_validation->set_rules("luminosidad", "Luminosidad", "required|xss_clean|trim");
        $this->form_validation->set_rules("cloacas", "Cloacas", "required|xss_clean|trim");
        $this->form_validation->set_rules("apto_profesional", "Apto_profesional", "required|xss_clean|trim");
        $this->form_validation->set_rules("agua", "Agua", "required|xss_clean|trim");
        $this->form_validation->set_rules("ubicación_en_planta", "Ubicación_en_planta", "required|xss_clean|trim");
        $this->form_validation->set_rules("asfalto", "Asfalto", "required|xss_clean|trim");
        $this->form_validation->set_rules("piso", "Piso", "required|xss_clean|trim");
        $this->form_validation->set_rules("energia", "Energia", "required|xss_clean|trim");
        $this->form_validation->set_rules("dependencia_de_servicio", "Dependencia_de_servicio", "required|xss_clean|trim");
        $this->form_validation->set_rules("telefono", "Telefono", "required|xss_clean|trim");
        $this->form_validation->set_rules("ambientes", "Ambientes", "required|xss_clean|trim");
        $this->form_validation->set_rules("cable", "Cable", "required|xss_clean|trim");
        $this->form_validation->set_rules("patio", "Patio", "required|xss_clean|trim");
        $this->form_validation->set_rules("direccion", "Direccion", "required|xss_clean|trim");
        $this->form_validation->set_rules("zona_barrio_id", "Zona_barrio_id", "required|xss_clean|trim");
        $this->form_validation->set_rules("descripcion_zona", "Descripcion_zona", "required|xss_clean|trim");
        $this->form_validation->set_rules("descripcion", "Descripcion", "required|xss_clean|trim");
        $this->form_validation->set_rules("codigo", "Codigo", "required|xss_clean|trim");
        $this->form_validation->set_rules("moneda_operacion", "Moneda_operacion", "required|xss_clean|trim");
        $this->form_validation->set_rules("expensas", "Expensas", "required|xss_clean|trim");
        $this->form_validation->set_rules("monto_expensas", "Monto_expensas", "required|xss_clean|trim");


        if ($this->form_validation->run() == FALSE) {
            echo json_encode(array("status" => FALSE, "errores" => validation_errors()));
        } else {

            $data = array(
                "tipo_propiedad" => $this->input->post("tipo_propiedad"),
                "tipo_operacion" => $this->input->post("tipo_operacion"),
                "monto_operacion" => $this->input->post("monto_operacion"),
                "localidad_id" => $this->input->post("localidad_id"),
                "dormitorios" => $this->input->post("dormitorios"),
                "patio" => $this->input->post("patio"),
                "banios" => $this->input->post("banios"),
                "antiguedad" => $this->input->post("antiguedad"),
                "garage" => $this->input->post("garage"),
                "apto_banco" => $this->input->post("apto_banco"),
                "estado" => $this->input->post("estado"),
                "superficie_lote" => $this->input->post("superficie_lote"),
                "detalles" => $this->input->post("detalles"),
                "tamanio_lote" => $this->input->post("tamanio_lote"),
                "nro_pisos" => $this->input->post("nro_pisos"),
                "orientación" => $this->input->post("orientación"),
                "deptos_por_piso" => $this->input->post("deptos_por_piso"),
                "superficie_construida" => $this->input->post("superficie_construida"),
                "ascensores_priv" => $this->input->post("ascensores_priv"),
                "terraza" => $this->input->post("terraza"),
                "ascensores_serv" => $this->input->post("ascensores_serv"),
                "seguridad" => $this->input->post("seguridad"),
                "servicios" => $this->input->post("servicios"),
                "calefacción" => $this->input->post("calefacción"),
                "gas" => $this->input->post("gas"),
                "luminosidad" => $this->input->post("luminosidad"),
                "cloacas" => $this->input->post("cloacas"),
                "apto_profesional" => $this->input->post("apto_profesional"),
                "agua" => $this->input->post("agua"),
                "ubicación_en_planta" => $this->input->post("ubicación_en_planta"),
                "asfalto" => $this->input->post("asfalto"),
                "piso" => $this->input->post("piso"),
                "energia" => $this->input->post("energia"),
                "dependencia_de_servicio" => $this->input->post("dependencia_de_servicio"),
                "telefono" => $this->input->post("telefono"),
                "ambientes" => $this->input->post("ambientes"),
                "cable" => $this->input->post("cable"),
                "patio" => $this->input->post("patio"),
                "direccion" => $this->input->post("direccion"),
                "zona_barrio_id" => $this->input->post("zona_barrio_id"),
                "descripcion_zona" => $this->input->post("descripcion_zona"),
                "descripcion" => $this->input->post("descripcion"),
                "codigo" => $this->input->post("codigo"),
                "moneda_operacion" => $this->input->post("moneda_operacion"),
                "expensas" => $this->input->post("expensas"),
                "monto_expensas" => $this->input->post("monto_expensas"),
            );
            $res = $this->obj_model->update(array('id' => $this->input->post('id')), $data);
            echo json_encode(array("status" => $res));
        }
    }

}
