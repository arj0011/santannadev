<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Service extends Common_Controller {
    public $data = array();
    public $file_data = "";
    public $_table = SERVICE;
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
                $this->data['url'] = base_url().'service';
        $this->data['pageTitle'] = lang('service');
        $this->data['parent'] = "Service";
        $option = array('table' => $this->_table.' as service',
                        'select' => 'service.store_id,service.store_name_en as service_name,service.store_address_en  as service_address,service.store_file,'
                                   . 'service.store_lat,service.store_lang,service.contact_number,service.email,service.open_time,'
                                   . 'service.close_time,catgory.category_name_el as category_name',
                        'join' => array(SERVICE_CATEGORY.' as catgory' => 'catgory.id=service.store_category_id')
            );
       
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'service.store_id,service.store_name_el as service_name,service.store_address_el as service_address,service.store_file,'
                                . 'service.store_lat,service.store_lang,service.contact_number,service.email,service.open_time,'
                                . 'service.close_time,catgory.category_name_en as category_name';
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
        $this->data['title'] = lang("add_service");
        
        $option = array('table' => SERVICE_CATEGORY,'select' => 'category_name_en as category_name,id');
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'category_name_el as category_name,id';
        }
        $this->data['results'] = $this->Common_model->customGet($option);
        
        $this->load->view('add', $this->data);
    }
    
   /* function open_model_news() {
        $this->data['title'] = lang("news_main_image");
        
        $option = array('table' => NEWS_TITLE_IMAGE,'single' => true);
        $this->data['news_result'] = $this->Common_model->customGet($option);
        $this->load->view('main_img_add', $this->data);
    }*/
    /**
     * @method service_add
     * @description add dynamic rows
     * @return array
     */
    public function service_add() {
        
        $this->form_validation->set_rules('service_category',lang('service_category'), 'required|trim');
        $this->form_validation->set_rules('service_name_en', lang('service_name_en'), 'required|trim');
        $this->form_validation->set_rules('service_address_en', lang('service_address_en'), 'required|trim');
        $this->form_validation->set_rules('service_address_el', lang('service_address_el'), 'required|trim');
        $this->form_validation->set_rules('service_name_el', lang('service_name_el'), 'required|trim');
        $this->form_validation->set_rules('opening', lang('opening'), 'required|trim');
        $this->form_validation->set_rules('closing', lang('closing'), 'required|trim');
         
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
                   $address = $this->input->post('service_address_en');
            
                   $resultGeoCode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false&key=AIzaSyCPiAzkxaGu03_Qeku-PZfCH1dwOibeY-w');
              
                   $output= json_decode($resultGeoCode);
            
                if($output->status == 'OK'){
                   $latitude   = $output->results[0]->geometry->location->lat; //Returns Latitude
                
                   $longitude = $output->results[0]->geometry->location->lng; // Returns Longitude
                   $address = $output->results[0]->formatted_address;
               } 
                   $options_data = array(
                        'company_id'        => $this->_uid,
                        'store_category_id' => $this->input->post('service_category'),
                        'store_name_en'     => $this->input->post('service_name_en'),
                        'store_name_el'     => $this->input->post('service_name_el'),
                        'store_address_el'  => $this->input->post('service_address_en'),
                        'store_address_en'  => $this->input->post('service_address_el'),
                        'store_lat'         => $latitude,
                        'store_lang'        => $longitude,
                        'email'             => $this->input->post('email'),
                        'contact_number '   => $this->input->post('contact_number'),
                        'open_time'         => $this->input->post('opening'),
                        'close_time'        => $this->input->post('closing'),
                        'datetime'          => datetime(),
                        'store_file'        => $image
                    );
                    $option = array('table' => $this->_table, 'data' => $options_data);
                    if ($this->Common_model->customInsert($option)) {
                        $response = array('status' => 1, 'message' => lang('service_success'), 'url' => base_url('service'));
                    } else {
                        $response = array('status' => 0, 'message' => lang('service_failed'));
                    } 
                }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }
    
    /* public function news_main_image_add(){
        $id = $this->input->post('id');
        $image = $this->input->post('exists_image');
        $this->filedata = $this->commonUploadImage($_POST, 'news', 'news_image');
        if ($this->filedata['status'] == 1) {
         $image = $this->filedata['upload_data']['file_name'];
        }
        
        if ($this->filedata['status'] == 0) {
           $response = array('status' => 0, 'message' => $this->filedata['error']);  
        }else{
           if(empty($id)){
             
               $options_data = array(
                    'company_id' => 1,
                    'image' => $image
                );
                $option = array('table' => NEWS_TITLE_IMAGE, 'data' => $options_data);
                if ($this->Common_model->customInsert($option)) {
                    $response = array('status' => 1, 'message' => lang('news_success'), 'url' => base_url('news'));
                } else {
                    $response = array('status' => 0, 'message' => lang('news_failed'));
                } 
               
           }else{
               $options_data = array(
                'company_id' => 1,
                'image' => $image
                );
                $option = array('table' => NEWS_TITLE_IMAGE, 'data' => $options_data,'where' => array('title_id' =>$id));
                if ($this->Common_model->customUpdate($option)) {
                    $response = array('status' => 1, 'message' => lang('news_success'), 'url' => base_url('news'));
                } else {
                    $response = array('status' => 0, 'message' => lang('news_failed'));
                } 
           } 
        }
        echo json_encode($response);
    }*/
 
    /**
     * @method service_edit
     * @description edit dynamic rows
     * @return array
     */
    public function service_edit() {
        $this->data['title'] = lang("edit_service");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {
            
            
            $option = array('table' => SERVICE_CATEGORY,'select' => 'category_name_en as category_name,id');
            if(getDefaultLanguage() == "el"){
            $option['select'] = 'category_name_el as category_name,id';
            }
            $this->data['service_category'] = $this->Common_model->customGet($option);
        
            $option1 = array(
                'table' => SERVICE,
                'where' => array('store_id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option1);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('service');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('service');
        }
    }

    /**
     * @method news_update
     * @description update dynamic rows
     * @return array
     */
    public function service_update() {

        $this->form_validation->set_rules('service_category',lang('service_category'), 'required|trim');
        $this->form_validation->set_rules('service_name_en', lang('service_name_en'), 'required|trim');
        $this->form_validation->set_rules('service_address_en', lang('service_address_en'), 'required|trim');
        $this->form_validation->set_rules('service_address_el', lang('service_address_el'), 'required|trim');
        $this->form_validation->set_rules('service_name_el', lang('service_name_el'), 'required|trim');
        $this->form_validation->set_rules('opening', lang('opening'), 'required|trim');
        $this->form_validation->set_rules('closing', lang('closing'), 'required|trim');
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
                    
                    }
                    
                }
                        
                
                 if ($this->filedata['status'] == 0) {
                    $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                    $address = $this->input->post('service_address_en');
            
                   $resultGeoCode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false&key=AIzaSyCPiAzkxaGu03_Qeku-PZfCH1dwOibeY-w');
              
                   $output= json_decode($resultGeoCode);
            
                if($output->status == 'OK'){
                   $latitude   = $output->results[0]->geometry->location->lat; //Returns Latitude
                
                   $longitude = $output->results[0]->geometry->location->lng; // Returns Longitude
                   $address = $output->results[0]->formatted_address;
                } 
                    $options_data = array(
                        'company_id'        => $this->_uid,
                        'store_category_id' => $this->input->post('service_category'),
                        'store_name_en'     => $this->input->post('service_name_en'),
                        'store_name_el'     => $this->input->post('service_name_el'),
                        'store_address_el'  => $this->input->post('service_address_en'),
                        'store_address_en'  => $this->input->post('service_address_el'),
                        'store_lat'         => $latitude,
                        'store_lang'        => $longitude,
                        'email'             => $this->input->post('email'),
                        'contact_number '   => $this->input->post('contact_number'),
                        'open_time'         => $this->input->post('opening'),
                        'close_time'        => $this->input->post('closing'),
                        'store_file'        => $image
                    );
                    $option = array(
                        'table' => SERVICE,
                        'data' => $options_data,
                        'where' => array('store_id' => $where_id)
                    );
                    $update = $this->Common_model->customUpdate($option);
                    $response = array('status' => 1, 'message' => lang('service_success_update'), 'url' => base_url('service'));
                }
        endif;

        echo json_encode($response);
    }



}
