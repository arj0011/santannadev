<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class SpecialRequest extends Common_Controller {
    public $data = array();
    public $file_data = "";
    public $_table = SPECIAL_REQUEST;
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        
        $this->data['url'] = base_url().'specialRequest';
        $this->data['pageTitle'] = lang('specialRequest');
        $this->data['parent'] = "specialRequest";
        $option = array('table' => $this->_table,
                     'select' => 'name,id'
               );
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'name,id';
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
        $this->data['title'] = lang("add_special_request");
        $this->load->view('add', $this->data);
    }

    /**
     * @method special_request_add
     * @description add dynamic rows
     * @return array
     */
    public function special_request_add() {
        
        $this->form_validation->set_rules('name', lang('name'), 'required|trim');
        
        if ($this->form_validation->run() == true) {
            
                 
                   $options_data = array(
                       
                        'name' => $this->input->post('name'),
                        'create_date' => datetime()
                        
                    );
                    $option = array('table' => $this->_table, 'data' => $options_data);
                    if ($this->Common_model->customInsert($option)) {
                        $response = array('status' => 1, 'message' => lang('special_request_success'), 'url' => base_url('specialRequest'));
                    } else {
                        $response = array('status' => 0, 'message' => lang('special_request_failed'));
                    } 
                   
                }
         else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method special_request_edit
     * @description edit dynamic rows
     * @return array
     */
    public function special_request_edit() {
        $this->data['title'] = lang("edit_special_request");
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
                redirect('specialRequest');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('specialRequest');
        }
    }

    /**
     * @method special_request_update
     * @description update dynamic rows
     * @return array
     */
    public function special_request_update() {

        $this->form_validation->set_rules('name', lang('name'), 'required|trim');
        
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
                
                   
                    $options_data = array(
                        'name' => $this->input->post('name'),
                        
                    
                     );
                    $option = array(
                        'table' => $this->_table,
                        'data' => $options_data,
                        'where' => array('id' => $where_id)
                    );
                    $update = $this->Common_model->customUpdate($option);
                    $response = array('status' => 1, 'message' => lang('special_request_success_update'), 'url' => base_url('specialRequest'));
                
            
        endif;

        echo json_encode($response);
    }

}
