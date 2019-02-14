<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Store extends Common_Controller {

    public $data = array();
    public $file_data = "";

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
     public function index() {
        $this->data['parent'] = "Store";
        $this->data['url'] = base_url().'store';
        $this->data['pageTitle'] = "Store";
        $option = array('table' => STORE.' as store', 
            'select' => 'store.id,store.store_name,store.email,store.store_place,store.is_active,store.create_date',
            'order' => array('store.id'=>'DESC')
            );
        if($this->session->userdata('role') == "store"){
            $option['where']['id'] = $this->session->userdata('id');
        }
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'store.id,store.store_name,store.email,store.store_place,store.is_active,role.name';
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
        $this->data['title'] = lang("add_store");
        $this->load->view('add', $this->data);
    }

    /**
     * @method users_add
     * @description add dynamic rows
     * @return array
     */
    public function store_add() {
        $this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('store_place', lang('store_place'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('store_email', lang('store_email'), 'required|trim|xss_clean|is_unique[store.email]');
        $this->form_validation->set_rules('store_password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[store_password]');
       
        if ($this->form_validation->run() == true) {

                $options_data = array(
                    'store_name' => $this->input->post('store_name'),
                    'email' => $this->input->post('store_email'),
                    'store_place' => $this->input->post('store_place'),
                    'password' => md5($this->input->post('store_password')),
                    'is_active' => 1,
                    'create_date' => datetime()
                );
                $option = array('table' => STORE, 'data' => $options_data);
                if ($this->Common_model->customInsert($option)) {
                    $response = array('status' => 1, 'message' => lang('store_success'), 'url' => base_url('store'));
                } else {
                    $response = array('status' => 0, 'message' => lang('store_failed'));
                }
            
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }
    /**
     * @method user_edit
     * @description edit dynamic rows
     * @return array
     */
    public function store_edit() {
        $this->data['title'] = lang("edit_store");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {

            $option = array(
                'table' => STORE,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('store');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('store');
        }
    }

    /**
     * @method user_update
     * @description update dynamic rows
     * @return array
     */
    public function store_update() {

        $this->form_validation->set_rules('store_name', lang('store_Name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('store_place', lang('store_place'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('store_email', lang('store_email'), 'required|trim|xss_clean');
        $newpass = $this->input->post('new_password');
        
        if ($newpass != "") {
            $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean');
            $this->form_validation->set_rules('confirm_password1', 'Confirm Password', 'trim|required|xss_clean|matches[new_password]');
            
        }
       

        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
               // $email = $this->input->post('store_name');
               $email = $this->input->post('store_email');
               $option = array(
                    'table' => STORE,
                    'select'=> 'store_name',
                    'where' => array('id !='=> $where_id,'email' =>$email)
                    );
                $email_exist = $this->Common_model->customGet($option);
                if(empty($email_exist)){
                 $options_data = array(
                    'store_place' => $this->input->post('store_place'),
                    'store_name' => $this->input->post('store_name'),
                    'email' => $this->input->post('store_email')
                );
                 if ($newpass != "") {
                   $options_data['password'] = md5($newpass); 
                 }
                 
                $option = array(
                    'table' => STORE,
                    'data' => $options_data,
                    'where' => array('id' => $where_id)
                );
                $update = $this->Common_model->customUpdate($option);
                $response = array('status' => 1, 'message' => lang('store_success_update'), 'url' => base_url('store'));
            }else{
               $response = array('status' => 0, 'message' => lang('email_exist'));
           }
        endif;

        echo json_encode($response);
    }

 
}
