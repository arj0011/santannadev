<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class City extends Common_Controller { 
    public $data = array();
    public $file_data = "";
    public $_table = COMPANY_CITY;
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['parent'] = "City";
        $option = array('table' => $this->_table,'select' => 'title_en as title,description_en as description,site_name_en as site_name,location_en as location, company_images,id,company_id');
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'title_el as title,description_el as description,site_name_el as site_name,location_el as location,company_images,id,company_id';
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
        $this->data['title'] = lang("add_city");
        $this->load->view('add', $this->data);
    }

    /**
     * @method city_add
     * @description add dynamic rows
     * @return array
     */
    public function city_add() {
        
        $this->form_validation->set_rules('title_en', lang('title_en'), 'required|trim');
        $this->form_validation->set_rules('title_el', lang('title_el'), 'required|trim');
        $this->form_validation->set_rules('description_en', lang('description_en'), 'required|trim');
        $this->form_validation->set_rules('description_el', lang('description_el'), 'required|trim');
        
        $this->form_validation->set_rules('site_name_en', lang('site_name_en'), 'required|trim');
        $this->form_validation->set_rules('site_name_el', lang('site_name_el'), 'required|trim');
        $this->form_validation->set_rules('location_en', lang('location_en'), 'required|trim');
        $this->form_validation->set_rules('location_el', lang('location_el'), 'required|trim');
        
        if ($this->form_validation->run() == true) {
            
                $this->filedata['status'] = 1;

                 $company_images = array();
                
                if (!empty($_FILES['files']['name'])) {
                //print_r($_FILES);die;
                for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
                    if ($_FILES['files']['name'][$i]) {
                        $up = $_FILES['files']['tmp_name'][$i];
                        if (is_uploaded_file($up)) {
                            $company_image = $_FILES['files']['name'][$i];
                            $f_extension = explode('.', $company_image); //To breaks the string into array
                            $f_extension = strtolower(end($f_extension)); //end() is used to retrun a last element to the array
                            $f_newfile = "";
                            $company_image = uniqid() . '.' . $f_extension;
                            move_uploaded_file($up, "uploads/city/" . $company_image);

                            array_push($company_images, $company_image);
                        }
                    }
                }
            }

             if ($this->filedata['status'] == 0) {
                   $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                   
                    $location = $this->input->post('location_en');
            
                 $resultGeoCode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$location.'&sensor=false&key=AIzaSyCPiAzkxaGu03_Qeku-PZfCH1dwOibeY-w');
              
                $output= json_decode($resultGeoCode);
            
            if($output->status == 'OK'){
                $latitude   = $output->results[0]->geometry->location->lat; //Returns Latitude
                
               $longitude = $output->results[0]->geometry->location->lng; // Returns Longitude
               $location = $output->results[0]->formatted_address;
           }
                $title_en = $this->input->post('title_en');
                $title_el = $this->input->post('title_el');

               $option = array(
                'table' => $this->_table,
                'where' => array('title_en' => $title_en),
                'single' => true
               );
                $title_exist1   = $this->Common_model->customGet($option);

             if(empty($title_exist1)){
                $option = array(
                'table' => $this->_table,
                'where' => array('title_el' => $title_el),
                'single' => true
                );
                $title_exist2   = $this->Common_model->customGet($option);
             if(empty($title_exist2)){
                   $options_data = array(
                        'company_id' => $this->_uid,
                        'title_en' => $this->input->post('title_en'),
                        'title_el' => $this->input->post('title_el'),
                        'description_en' => $this->input->post('description_en'),
                        'description_el' => $this->input->post('description_el'),
                        'site_name_en' => $this->input->post('site_name_en'),
                        'site_name_el' => $this->input->post('site_name_el'),
                        'location_en' => $this->input->post('location_en'),
                        'location_el' => $this->input->post('location_el'),
                        'latitude' => $latitude,
                        'longitude' => $longitude
                        
                    );
                 if (!empty($company_images)) {
                    $options_data['company_images'] = json_encode($company_images);
                  }
                    $option = array('table' => $this->_table, 'data' => $options_data);
                    if ($this->Common_model->customInsert($option)) {
                        $response = array('status' => 1, 'message' => lang('city_success'), 'url' => base_url('city'));
                    } else {
                        $response = array('status' => 0, 'message' => lang('city_failed'));
                    } 
                    }else{
                    $response = array('status' => 0, 'message' => lang('title_el_exist'));
                }
                }else{
                    $response = array('status' => 0, 'message' => lang('title_en_exist'));
                }
                }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method city_edit
     * @description edit dynamic rows
     * @return array
     */
    public function city_edit() {
        $this->data['title'] = lang("edit_city");
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
                redirect('city');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('city');
        }
    }

    /**
     * @method city_update
     * @description update dynamic rows
     * @return array
     */
    public function city_update() {

        $this->form_validation->set_rules('title_en', lang('title_en'), 'required|trim');
        $this->form_validation->set_rules('title_el', lang('title_el'), 'required|trim');
        $this->form_validation->set_rules('description_en', lang('description_en'), 'required|trim');
        $this->form_validation->set_rules('description_el', lang('description_el'), 'required|trim');
        $this->form_validation->set_rules('site_name_en', lang('site_name_en'), 'required|trim');
        $this->form_validation->set_rules('site_name_el', lang('site_name_el'), 'required|trim');
        $this->form_validation->set_rules('location_en', lang('location_en'), 'required|trim');
        $this->form_validation->set_rules('location_el', lang('location_el'), 'required|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
                $this->filedata['status'] = 1;

            $old_img = $this->input->post('exisisCompanyImage');
            $company_images = array();
            if (!empty($old_img)) {
                $company_images = $old_img;
            }
            if (!empty($_FILES['files']['name'])) {
                for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
                    if ($_FILES['files']['name'][$i]) {
                        $up = $_FILES['files']['tmp_name'][$i];
                        if (is_uploaded_file($up)) {
                            $company_image = $_FILES['files']['name'][$i];
                            $f_extension = explode('.', $company_image); //To breaks the string into array
                            $f_extension = strtolower(end($f_extension)); //end() is used to retrun a last element to the array
                            $f_newfile = "";
                            $company_image = uniqid() . '.' . $f_extension;
                            move_uploaded_file($up, "uploads/city/" . $company_image);
                            array_push($company_images, $company_image);
                        }
                    }
                }
            }
               
                 if ($this->filedata['status'] == 0) {
                    $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{

                $location = $this->input->post('location_en');
            
                 $resultGeoCode=file_get_contents('https://maps.google.com/maps/api/geocode/json?address='.$location.'&sensor=false&key=AIzaSyCPiAzkxaGu03_Qeku-PZfCH1dwOibeY-w');
              
                $output= json_decode($resultGeoCode);
            
              if($output->status == 'OK'){
                $latitude   = $output->results[0]->geometry->location->lat; //Returns Latitude
                
               $longitude = $output->results[0]->geometry->location->lng; // Returns Longitude
               $location = $output->results[0]->formatted_address;
            }
                $title_en = $this->input->post('title_en');
                $title_el = $this->input->post('title_el');

               $option = array(
                'table' => $this->_table,
                'where' => array('id !=' => $where_id,'title_en' => $title_en),
                'single' => true
               );
                $title_exist1   = $this->Common_model->customGet($option);

             if(empty($title_exist1)){
                $option = array(
                'table' => $this->_table,
                'where' => array('id !=' => $where_id,'title_el' => $title_el),
                'single' => true
                );
                $title_exist2   = $this->Common_model->customGet($option);
             if(empty($title_exist2)){
                    
                    $options_data = array(
                        'company_id' => $this->_uid,
                        'title_en' => $this->input->post('title_en'),
                        'title_el' => $this->input->post('title_el'),
                        'description_en' => $this->input->post('description_en'),
                        'description_el' => $this->input->post('description_el'),
                        'site_name_en' => $this->input->post('site_name_en'),
                        'site_name_el' => $this->input->post('site_name_el'),
                        'location_en' => $this->input->post('location_en'),
                        'location_el' => $this->input->post('location_el'),
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'create_date' => datetime()
                    );
                 if (!empty($company_images)) {
                    $options_data['company_images'] = json_encode($company_images);
                  }
                    $option = array(
                        'table' => $this->_table,
                        'data' => $options_data,
                        'where' => array('id' => $where_id)
                    );
                    $update = $this->Common_model->customUpdate($option);
                    $response = array('status' => 1, 'message' => lang('city_success_update'), 'url' => base_url('city'));
                   }else{
                    $response = array('status' => 0, 'message' => lang('title_el_exist'));
                }
                }else{
                    $response = array('status' => 0, 'message' => lang('title_en_exist'));
                }
                }


        endif;

        echo json_encode($response);
    }

}
