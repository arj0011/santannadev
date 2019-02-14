<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menu extends Common_Controller {
    public $data = array();
    public $file_data = "";
    public $_table = MENUS;
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method menu_list
     * @description listing display
     * @return array
     */
    public function menu_list() {
        $this->data['parent'] = "Service";
        $store_id = $this->uri->segment(3);
         $option = array('table' => $this->_table.' as menu',
                        'select' => 'menu.id,menu.menu_name_en as menu_name,menu.price,menu.image',
                        'where' => array('store_id'=>$store_id)
            );
       
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'menu.id,menu.menu_name_el as menu_name,menu.price,menu.image';
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
        $this->data['title'] = lang("add_menu");
        $id = decoding($this->input->post('id'));
        //echo $id;die;
        if (!empty($id)) {
        
            $option = array(
                'table' => SERVICE,
                'where' => array('store_id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;



         //$id = $this->input->post('id');
      //echo $id;die;
      // $this->data['store_id']= $this->uri->segment(3);
       // echo $this->data['store_id'];die;
        
        //$option = array('table' => SERVICE,'select' => 'store_id','where' =>array('store_id'=>$id));
        
        //$this->data['results'] = $this->Common_model->customGet($option);
         //print_r($this->data['results']);die;
            }
        }
        $this->load->view('add', $this->data);
    }
    
   
    /**
     * @method menu_add
     * @description add dynamic rows
     * @return array
     */
    public function menu_add() {
        $this->form_validation->set_rules('menu_name_en',lang('menu_name_en'), 'required|trim');
        $this->form_validation->set_rules('menu_name_el', lang('menu_name_el'), 'required|trim');
        $this->form_validation->set_rules('price', lang('price'), 'required|trim');
         
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
                    
                   //$store_id = $this->uri->segment(3);
                   $options_data = array(

                        'store_id'          => $this->input->post('store_id'),
                        'menu_name_en'      => $this->input->post('menu_name_en'),
                        'menu_name_el'      => $this->input->post('menu_name_el'),
                        'price'             => $this->input->post('price'),
                        'image'             => $image,
                        'create_date'       => datetime()
                    );
                    $option = array('table' => $this->_table, 'data' => $options_data);
                    if ($this->Common_model->customInsert($option)) {
                        $response = array('status' => 1, 'message' => lang('menu_success'), 'url' => base_url('service'));
                    } else {
                        $response = array('status' => 0, 'message' => lang('menu_failed'));
                    } 
                }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }
    
    
 
    /**
     * @method menu_edit
     * @description edit dynamic rows
     * @return array
     */
    public function menu_edit() {
        $this->data['title'] = lang("edit_menu");
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
                redirect('menu');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('menu');
        }
    }

    /**
     * @method menu_update
     * @description update dynamic rows
     * @return array
     */
    public function menu_update() {

         $this->form_validation->set_rules('menu_name_en',lang('menu_name_en'), 'required|trim');
        $this->form_validation->set_rules('menu_name_el', lang('menu_name_el'), 'required|trim');
        $this->form_validation->set_rules('price', lang('price'), 'required|trim');

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
                    
                    }
                    
                }
                        
                if ($this->filedata['status'] == 0) {
                    $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                    
                    $options_data = array(

                        'menu_name_en'      => $this->input->post('menu_name_en'),
                        'menu_name_el'      => $this->input->post('menu_name_el'),
                        'price'             => $this->input->post('price'),
                        'image'             => $image
                    );
                    $option = array(
                        'table' => MENUS,
                        'data' => $options_data,
                        'where' => array('id' => $where_id)
                    );
                    $update = $this->Common_model->customUpdate($option);
                    $response = array('status' => 1, 'message' => lang('menu_success_update'), 'url' => base_url('service'));
                }
        endif;

        echo json_encode($response);
    }


}
