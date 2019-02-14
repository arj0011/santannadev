<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ServiceCategory extends Common_Controller {
    public $data = array();
    public $file_data = "";
    public $_table = SERVICE_CATEGORY;
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['url'] = base_url().'serviceCategory';
        $this->data['pageTitle'] = lang('service_category');
        $this->data['parent'] = "service_category";
        $option = array('table' => $this->_table,'select' => 'category_name_en as category_name,category_image,id,company_id,language_id');
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'category_name_el as category_name,category_image,id,company_id,language_id';
        }
        $this->data['list'] = $this->Common_model->customGet($option);
        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

    /**
     * @method open_model
     * @description load model box
     * @return array
     */
    function open_model() {
        $this->data['title'] = lang("add_service_category");
        $this->load->view('add', $this->data);
    }

    /**
     * @method service_category_add
     * @description add dynamic rows
     * @return array
     */
    public function service_category_add() {
        
        $this->form_validation->set_rules('service_category_name_en', lang('service_category_name_en'), 'required|trim');
        $this->form_validation->set_rules('service_category_name_el', lang('service_category_name_el'), 'required|trim');
        if ($this->form_validation->run() == true) {
            
                $this->filedata['status'] = 1;
                $image = "";
                if (!empty($_FILES['service_image']['name'])) {
                    $this->filedata = $this->commonUploadImage($_POST, 'service', 'service_image');
                    if ($this->filedata['status'] == 1) {
                     $image = $this->filedata['upload_data']['file_name'];
                    }
                }

                if ($this->filedata['status'] == 0) {
                   $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                    $category_name_en = $this->input->post('service_category_name_en');
                     $option = array(
                         'table' => $this->_table,
                         'where' => array('category_name_en' => $category_name_en),
                         'single' => true
                 );
                  $cat_exist  = $this->Common_model->customGet($option);
                 if(empty($cat_exist)){
                   $options_data = array(
                        'company_id' => $this->_uid,
                        'category_name_en' => $this->input->post('service_category_name_en'),
                        'category_name_el' => $this->input->post('service_category_name_el'),
                        'category_image' => $image
                    );
                    $option = array('table' => $this->_table, 'data' => $options_data);
                    if ($this->Common_model->customInsert($option)) {
                        $response = array('status' => 1, 'message' => lang('service_category_success'), 'url' => base_url('serviceCategory'));
                    } else {
                        $response = array('status' => 0, 'message' => lang('service_category_failed'));
                    } 
                    }else{
                    $response = array('status' => 0, 'message' => lang('cat_en_exist'));
                }
                }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method service_category_edit
     * @description edit dynamic rows
     * @return array
     */
    public function service_category_edit() {
        $this->data['title'] = lang("edit_service_category");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {
            $option = array(
                'table' => $this->_table,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('serviceCategory');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('serviceCategory');
        }
    }

    /**
     * @method service_category_update
     * @description update dynamic rows
     * @return array
     */
    public function service_category_update() {

        $this->form_validation->set_rules('service_category_name_en', lang('service_category_name_en'), 'required|trim');
        $this->form_validation->set_rules('service_category_name_el', lang('service_category_name_el'), 'required|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
                $this->filedata['status'] = 1;
                $image = $this->input->post('exists_image');
                 
                 if (!empty($_FILES['service_image']['name'])) {
                    $this->filedata = $this->commonUploadImage($_POST, 'service', 'service_image');
                    if ($this->filedata['status'] == 1) {
                     $image = $this->filedata['upload_data']['file_name'];
                     //image_unlink( FCPATH . "uploads/service".$image);
                    }


                }
                 if ($this->filedata['status'] == 0) {
                    $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                    $category_name_en = $this->input->post('service_category_name_en');
                     $option = array(
                         'table' => $this->_table,
                         'where' => array('id !=' => $where_id,'category_name_en' => $category_name_en),
                         'single' => true
                 );
                   $cat_exist  = $this->Common_model->customGet($option);
                  if(empty($cat_exist)){
                    $options_data = array(
                            'category_name_en' => $this->input->post('service_category_name_en'),
                            'category_name_el' => $this->input->post('service_category_name_el'),
                            'category_image' => $image
                    );
                    $option = array(
                        'table' => $this->_table,
                        'data' => $options_data,
                        'where' => array('id' => $where_id)
                    );
                    $update = $this->Common_model->customUpdate($option);
                    $response = array('status' => 1, 'message' => lang('service_category_success_update'), 'url' => base_url('serviceCategory'));
                }else{
                    $response = array('status' => 0, 'message' => lang('cat_en_exist'));
                }
            }
        endif;

        echo json_encode($response);
    }

}
