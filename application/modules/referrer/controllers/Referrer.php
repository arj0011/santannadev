<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Referrer extends Common_Controller {

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
                         $this->data['url'] = base_url() . 'referrer';
        $this->data['pageTitle'] = lang('referrer');
        $this->data['parent'] = "Referrer";
        error_reporting(E_ALL);
ini_set('display_errors', 1);
        $option = array('table' => 'mw_booking', 
            'select' => 'referrer,count(*) as total_booking',
            'where' => array('referrer !=' => ""),
            'group_by' => 'referrer'
            );
        if(!empty($_POST['start_date']) && isset($_POST)){
         $option['where']['MONTH(booking_date)'] = $_POST['start_date'];
        // $sql = "SELECT `referrer`, count(*) as total_booking FROM `mw_booking` WHERE `referrer` != '' AND MONTH(`booking_date`) = 7 GROUP BY `referrer` ";
         //$this->data['list']=$this->Common_model->customQuery($sql);
       // pr($this->data['list']);exit;
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
        $this->data['title'] = lang("add_agent");

        $option = array('table' => ROLE,'select' => 'name,id');
          if(getDefaultLanguage() == "el"){
          $option['select'] = 'name,id';
          }
          $this->data['results'] = $this->Common_model->customGet($option); 

        $this->load->view('add', $this->data);
    }

    /**
     * @method users_add
     * @description add dynamic rows
     * @return array
     */
    public function agents_add() {
        $this->form_validation->set_rules('full_name', lang('full_name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', lang('email'), 'required|trim|xss_clean|is_unique[agents.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
       
        if ($this->form_validation->run() == true) {

            $this->filedata['status'] = 1;
            $image = "";
            if (!empty($_FILES['image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'agents', 'image');
                if ($this->filedata['status'] == 1) {
                    $image = $this->filedata['upload_data']['file_name'];
                }
            }

            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {
                $options_data = array(
                    'role_id' => $this->input->post('role_name'),
                    'full_name' => $this->input->post('full_name'),
                    'email' => $this->input->post('email'),
                    'phone_number' => $this->input->post('phone_number'),
                    'date_of_birth' => date('Y-m-d',strtotime($this->input->post('date_of_birth'))),
                    'gender' => $this->input->post('gender'),
                    'password' => md5($this->input->post('password')),
                    'image' => $image,
                    'is_active' => 1,
                    'create_date' => datetime()
                );
                $option = array('table' => AGENTS, 'data' => $options_data);
                if ($this->Common_model->customInsert($option)) {
                    $response = array('status' => 1, 'message' => lang('agent_success'), 'url' => base_url('agents'));
                } else {
                    $response = array('status' => 0, 'message' => lang('agent_failed'));
                }
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
    public function agents_edit() {
        $this->data['title'] = lang("edit_agents");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {

            $option_data = array(
                'table' => ROLE,
                'select' => '*'
            );
            $this->data['roles'] = $this->Common_model->customGet($option_data);

            $option = array(
                'table' => AGENTS,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('agents');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('agents');
        }
    }

    /**
     * @method user_update
     * @description update dynamic rows
     * @return array
     */
    public function agents_update() {

       $this->form_validation->set_rules('full_name', lang('full_name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', lang('email'), 'required|trim|xss_clean');
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
            $this->filedata['status'] = 1;
            $image = $this->input->post('exists_image');

            if (!empty($_FILES['image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'agents', 'image');

                if ($this->filedata['status'] == 1) {
                    $image = $this->filedata['upload_data']['file_name'];
                  
                    delete_file($this->input->post('exists_image'), FCPATH."uploads/agents/");
                    
                }
            }
            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {
               
               $email = $this->input->post('email');
               $option = array(
                    'table' => AGENTS,
                    'select'=> 'email',
                    'where' => array('id !='=> $where_id,'email' =>$email)
                    );
                $email_exist = $this->Common_model->customGet($option);
                if(empty($email_exist)){
                 $options_data = array(
                    'role_id' => $this->input->post('role_name'),
                    'full_name' => $this->input->post('full_name'),
                    'email' => $this->input->post('email'),
                    'phone_number' => $this->input->post('phone_number'),
                    'date_of_birth' => date('Y-m-d',strtotime($this->input->post('date_of_birth'))),
                    'gender' => $this->input->post('gender'),
                    'image' => $image,
                   // 'create_date' => datetime()
                );
                 if ($newpass != "") {
                   $options_data['password'] = md5($newpass); 
                 }
                 
                $option = array(
                    'table' => AGENTS,
                    'data' => $options_data,
                    'where' => array('id' => $where_id)
                );
                $update = $this->Common_model->customUpdate($option);
                $response = array('status' => 1, 'message' => lang('agent_success_update'), 'url' => base_url('agents'));
            }else{
               $response = array('status' => 0, 'message' => lang('email_exist'));
           }
            
        
    }
        endif;

        echo json_encode($response);
    }

     public function booking_history() {
        $this->data['parent'] = "Agent";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $agent_id = $this->uri->segment(3);
         $this->data['booking'] = array(
           
            'start_date' => $start_date,
            'end_date' => $end_date
        );
        if (!empty($start_date) && !empty($end_date)) {
            $start_date = date('Y-m-d', strtotime($start_date));
         
            $end_date = date('Y-m-d', strtotime($end_date));
        } else {
            $start_date = "";
            $end_date = "";
        }
     

        $this->data['list'] = $this->Common_model->booking($start_date, $end_date,'mw_booking',array('agent_id' =>$agent_id));
        
       
        $this->template->load_view('default', 'booking_history', $this->data, 'inner_script');
    }

   
}
