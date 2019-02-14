<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class MenuSubcategory extends Common_Controller {
    public $data = array();
    public $file_data = "";
    public $_table = MENU_SUBCATEGORY;
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['url'] = base_url().'menuSubcategory';
        $this->data['pageTitle'] = lang('menu_subcategory');
        $this->data['parent'] = "menu_subcategory";
         $option = array('table' => $this->_table.' as subcat',
                        'select' => 'subcat.id,subcat.subcategory_name_en as subcategory_name,subcat.image,cat.category_name_en as category_name',
                          'join' => array(MENU_CATEGORY.' as cat' => 'cat.id=subcat.menu_category_id')
            );
        
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'subcat.id,subcat.subcategory_name_en as subcategory_name,subcat.image,cat.category_name_en as category_name';
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
        $this->data['title'] = lang("add_menu_subcategory");
        $option = array('table' => MENU_CATEGORY,'select' => 'category_name_en as category_name,id');
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'category_name_el as category_name,id';
        }
        $this->data['results'] = $this->Common_model->customGet($option);

        $this->load->view('add', $this->data);
    }

    

    /**
     * @method menu_subcategory_add
     * @description add dynamic rows
     * @return array
     */
    public function menu_subcategory_add() {

        $this->form_validation->set_rules('menu_category',lang('menu_category'), 'required|trim');
        $this->form_validation->set_rules('menu_subcategory_name_en', lang('menu_subcategory_name_en'), 'required|trim');
        $this->form_validation->set_rules('menu_subcategory_name_el', lang('menu_subcategory_name_el'), 'required|trim');
        if ($this->form_validation->run() == true) {
            
                $this->filedata['status'] = 1;
                $image = "";
                if (!empty($_FILES['image']['name'])) {
                    $this->filedata = $this->commonUploadImage($_POST, 'menu', 'image');
                    if ($this->filedata['status'] == 1) {
                     $image = $this->filedata['upload_data']['file_name'];
                    }
                }

                if ($this->filedata['status'] == 0) {
                   $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                   
                 
                   $options_data = array(
                       // 'company_id' => $this->_uid,
                        'menu_category_id' => $this->input->post('menu_category'),
                        'subcategory_name_en' => $this->input->post('menu_subcategory_name_en'),
                        'subcategory_name_el' => $this->input->post('menu_subcategory_name_el'),
                        'image' => $image,
                        'is_active' => 1,
                        'create_date' => datetime()
                        
                    );
                    $option = array('table' => $this->_table, 'data' => $options_data);
                    if ($this->Common_model->customInsert($option)) {
                        $response = array('status' => 1, 'message' => lang('menu_subcategory_success'), 'url' => base_url('menuSubcategory'));
                    } else {
                        $response = array('status' => 0, 'message' => lang('menu_subcategory_failed'));
                    } 
                   
                }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method menu_subcategory_edit
     * @description edit dynamic rows
     * @return array
     */
    public function menu_subcategory_edit() {
        $this->data['title'] = lang("edit_menu_subcategory");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {
            $option = array('table' => MENU_CATEGORY,'select' => 'category_name_en as category_name,id');
            if(getDefaultLanguage() == "el"){
            $option['select'] = 'category_name_el as category_name,id';
            }
            $this->data['menu_category'] = $this->Common_model->customGet($option);
            $option1 = array(
                'table' => $this->_table,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option1);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('menuSubcategory');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('menuSubcategory');
        }
    }

    /**
     * @method menu_subcategory_update
     * @description update dynamic rows
     * @return array
     */
    public function menu_subcategory_update() {

       $this->form_validation->set_rules('menu_category',lang('menu_category'), 'required|trim');
        $this->form_validation->set_rules('menu_subcategory_name_en', lang('menu_subcategory_name_en'), 'required|trim');
        $this->form_validation->set_rules('menu_subcategory_name_el', lang('menu_subcategory_name_el'), 'required|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
                $this->filedata['status'] = 1;
                $image = $this->input->post('exists_image');
                 
                 if (!empty($_FILES['image']['name'])) {
                    $this->filedata = $this->commonUploadImage($_POST, 'menu', 'image');
                    if ($this->filedata['status'] == 1) {
                     $image = $this->filedata['upload_data']['file_name'];
                     delete_file($this->input->post('exists_image'), FCPATH."uploads/menu/");
                     
                    }


                }
                 if ($this->filedata['status'] == 0) {
                    $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                   
                    $options_data = array(
                       // 'company_id' => $this->_uid,
                        'menu_category_id' => $this->input->post('menu_category'),
                        'subcategory_name_en' => $this->input->post('menu_subcategory_name_en'),
                        'subcategory_name_el' => $this->input->post('menu_subcategory_name_el'),
                        'image' => $image
                        
                        
                    );
                    $option = array(
                        'table' => $this->_table,
                        'data' => $options_data,
                        'where' => array('id' => $where_id)
                    );
                    $update = $this->Common_model->customUpdate($option);
                    $response = array('status' => 1, 'message' => lang('menu_subcategory_success_update'), 'url' => base_url('menuSubcategory'));
                
            }
        endif;

        echo json_encode($response);
    }

}
