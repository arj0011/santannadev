<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends Common_Controller {

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
        $this->data['parent'] = "User";
        $option = array('table' => USERS, 'select' => 'name as user_name,user_image,id,email,date_of_birth,address,created_date,is_deactivated','order' => array('id'=>'DESC'));
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'name as user_name,user_image,id,email,date_of_birth,address,created_date,is_deactivated';
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
        $this->data['title'] = lang("add_user");
        $this->load->view('add', $this->data);
    }

    /**
     * @method users_add
     * @description add dynamic rows
     * @return array
     */
    public function users_add() {
        $this->form_validation->set_rules('user_name', lang('user_name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('user_email', lang('user_email'), 'required|trim|xss_clean|is_unique[users.email]');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|xss_clean|matches[password]');
        if ($this->form_validation->run() == true) {

            $this->filedata['status'] = 1;
            $image = "";
            if (!empty($_FILES['user_image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'users', 'user_image');
                if ($this->filedata['status'] == 1) {
                    $image = 'uploads/users/' .$this->filedata['upload_data']['file_name'];
                }
            }

            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {
                $options_data = array(
                    //'company_id' => 1,
                    'name' => $this->input->post('user_name'),
                    'email' => $this->input->post('user_email'),
                    'phone_number' => $this->input->post('phone_number'),
                    'date_of_birth' => date('Y-m-d',strtotime($this->input->post('date_of_birth'))),
                    'gender' => $this->input->post('user_gender'),
                    'password' => md5($this->input->post('password')),
                    'user_image' => $image,
                    'is_deactivated' => 0,
                    'created_date' => datetime()
                );
                $option = array('table' => USERS, 'data' => $options_data);
                if ($this->Common_model->customInsert($option)) {
                    $response = array('status' => 1, 'message' => lang('user_success'), 'url' => base_url('users'));
                } else {
                    $response = array('status' => 0, 'message' => lang('user_failed'));
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
    public function user_edit() {
        $this->data['title'] = lang("edit_user");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {
            $option = array(
                'table' => USERS,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('users');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('users');
        }
    }

    /**
     * @method user_update
     * @description update dynamic rows
     * @return array
     */
    public function user_update() {

        $this->form_validation->set_rules('user_name', lang('user_name'), 'required|trim|xss_clean');
        $this->form_validation->set_rules('user_email', lang('user_email'), 'required|trim|xss_clean');
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

            if (!empty($_FILES['user_image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'users', 'user_image');

                if ($this->filedata['status'] == 1) {
                    $image = 'uploads/users/' .$this->filedata['upload_data']['file_name'];
                   
                    delete_file($this->input->post('exists_image'), FCPATH);
                    
                }
            }
            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {

               
                $options_data = array(
                'name' => $this->input->post('user_name'),
                'email' => $this->input->post('user_email'),
                'phone_number' => $this->input->post('phone_number'),
                'date_of_birth' => date('Y-m-d',strtotime($this->input->post('date_of_birth'))),
                'address' => $this->input->post('user_address'),
                'gender' => $this->input->post('user_gender'),
                'user_image' => $image,
                'country' => $this->input->post('country'),
                'city' => $this->input->post('city'),
                'address' =>$this->input->post('address'),
                'title' => $this->input->post('title'),
                'add_member_first_name' => $this->input->post('add_member_first_name'),
                'add_member_last_name' => $this->input->post('add_member_last_name'),
                'company_name' => $this->input->post('company_name'),
                'nationality' => $this->input->post('nationality'),
                'other_email' => $this->input->post('other_email'),
                'zip_code' => $this->input->post('zip_code'),
                'country_code' => $this->input->post('country_code'),
                'country_code_secondary' => $this->input->post('country_code_secondary'),
                'phone_number_secondary' => $this->input->post('phone_number_secondary'),
                'mobile_number_country_code' => $this->input->post('mobile_number_country_code'),
                'mobile_number' => $this->input->post('mobile_number'),
                'fax_country_code' => $this->input->post('fax_country_code'),
                'fax' => $this->input->post('fax'),
                'group_name' => $this->input->post('group_name'),
                'reference' => $this->input->post('reference'),
                'suppliers' => $this->input->post('suppliers'),
                'hoteliers' => $this->input->post('hoteliers'),
                'concierge' => $this->input->post('concierge'),
                'points' => $this->input->post('points'),
                'notes' => $this->input->post('notes'),
                'name_day' => $this->input->post('name_day')    
                    
                );
                 if ($newpass != "") {
                   $options_data['password'] = md5($newpass); 
                 }
                $option = array(
                    'table' => USERS,
                    'data' => $options_data,
                    'where' => array('id' => $where_id)
                );
                $update = $this->Common_model->customUpdate($option);
                $response = array('status' => 1, 'message' => lang('user_success_update'), 'url' => base_url('users'));
            }
        endif;

        echo json_encode($response);
    }

    public function export_user() {

           $option = array(
                'table' => USERS,
                'select' =>'*'
            );
            $users = $this->Common_model->customGet($option);

       // $userslist = $this->Common_model->getAll(USERS,'name','ASC');
        $print_array = array();
        $i = 1;
        foreach ($users as $value) {


            $print_array[] = array('s_no' => $i, 'name' => $value->name, 'email' => $value->email);
            $i++;
        }

        $filename = "user_email_csv.csv";
        $fp = fopen('php://output', 'w');

        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);
        fputcsv($fp, array('S.no', 'User Name', 'Email'));

        foreach ($print_array as $row) {
            fputcsv($fp, $row);
        }
    }


     public function reset_password() {
        $user_id_encode = $this->uri->segment(3);

        $data['id_user_encode'] = $user_id_encode;

        if (!empty($_POST) && isset($_POST)) {

            $user_id_encode = $_POST['user_id'];

            if (!empty($user_id_encode)) {

                $user_id = base64_decode(base64_decode(base64_decode(base64_decode($user_id_encode))));


                $this->form_validation->set_rules('new_password', 'Password', 'required');
                $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'required|matches[new_password]');

                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('reset_password', $data);
                } else {

                   
                    $user_pass = $_POST['new_password'];

                    $data1 = array('password' => md5($user_pass));
                    $where = array('id' => $user_id);

                    $out = $this->Common_model->updateFields(USERS, $data1, $where);

                    

                    if ($out) {
                       
                        $this->session->set_flashdata('passupdate', 'Password Successfully Changed.');
                        $data['success'] = 1;
                        $this->load->view('reset_password', $data);
                       
                    } else {
                        
                        $this->session->set_flashdata('error_passupdate', 'Password Already Changed.');
                       $this->load->view('reset_password', $data);
                         
                    }
                }
            } else {
                
                $this->session->set_flashdata('error_passupdate', 'Unable to Change Password, Authentication Failed.');
                $this->load->view('reset_password');
            }
        } else {
            $this->load->view('reset_password', $data);
        }
    }

    public function user_view() {
        $this->data['parent'] = "User";
        $this->data['title'] = "View User";
        $id = decoding($this->input->post('id'));

        if (!empty($id)) {
             $option = array('table' => USERS, 
                'select' => '*',
                'where' => array('id' => $id),
                'single' => true
               
                );

            if (getDefaultLanguage() == "el") {
                $option['select'] = '*';
            }

            $results_row = $this->Common_model->customGet($option);
            // print_r($result);die;
        } $this->data['result'] = $results_row;
        $this->load->view('view', $this->data);
    }
}
