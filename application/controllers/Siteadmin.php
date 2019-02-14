<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as admin management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Siteadmin extends CI_Controller {

    function __construct() {
        parent::__construct();
        
    }

    /**
     * Function Name: index
     * Description:   To admin login view
     */
    public function index() {
        $user = $this->session->userdata("id");
        $cookie_data = $this->input->cookie('siteadmin', true);
        /*if (isset($cookie_data) && !empty($cookie_data)) {
            $cookie_val = explode("_", decoding($cookie_data));
            $result = $this->Common_model->getsingle('company', array('email' => $cookie_val[0], 'id' => $cookie_val[1]));
            if (!empty($result)) {
                $this->session->set_userdata("id", $result->id);
                $this->session->set_userdata("email", $result->email);
                redirect("/admin/dashboard");
            } else {
                redirect("siteadmin");
            }
        }*/
        if (!empty($user)) {
            redirect("/admin/dashboard");
        }
        $this->load->view("admin/auth/login");
    }

    /**
     * Function Name: login
     * Description:   To admin login
     */
    /*public function login() {
        if ($this->input->is_ajax_request()) {
            $data = $this->input->post();
            $u_data = array();
            $u_data["email"] = $data["email"];
            $u_data["password"] = md5($data["password"]);
            $user_data = $this->Common_model->getsingle(ADMIN, $u_data);
            if (!empty($user_data)) {
                $user_id = (int) $user_data->id; 
                $last_login = date('Y-m-d H:i:s');
                $this->session->set_userdata("id", $user_id);
                $this->session->set_userdata("email", $user_data->email);
                $this->session->set_userdata("company_name", $user_data->company_name);
                $this->session->set_userdata("last_login", $user_data->last_login);
                $this->session->set_userdata("last_ip", $user_data->last_ip);
                $this->session->set_userdata('user_activity', time());
                $this->Common_model->updateFields(ADMIN, array('last_login' => $last_login, 'last_ip' => getRealIpAddr()), array('id' => $user_data->id));
                if (isset($data['remember_me'])) {
                    $cookie = array(
                        'name' => 'siteadmin',
                        'value' => encoding($user_data->email . "_" . $user_id),
                        'expire' => '864000000', // 10 days
                    );
                    $this->input->set_cookie($cookie);
                }
                echo "success";
                exit;
            } else {
                echo "error";
                exit;
            }
        }
    }*/

    public function login() {
        if ($this->input->is_ajax_request()) {
            $user_data = array();
            $role = 'admin';
            $data = $this->input->post();
            $u_data = array();
            $u_data["email"] = $data["email"];
            $u_data["password"] = md5($data["password"]);
            $user_data = $this->Common_model->getsingle(ADMIN, array('email'=>$u_data["email"]));
            if(!empty($user_data)):
                $user_data = $this->Common_model->getsingle(ADMIN, $u_data);
                $role = 'admin';
            else:
                $user_data = $this->Common_model->getsingle(AGENTS, $u_data);
                if(!empty($user_data)):
                    $role = 'agent';
                else:
                    $user_data = $this->Common_model->getsingle(STORE, $u_data);
                    $role = 'store';
                endif;          
            endif;    

            if (!empty($user_data)) {
                $user_id = (int) $user_data->id; 
                $last_login = date('Y-m-d H:i:s');
                $this->session->set_userdata("id", $user_id);
                $this->session->set_userdata("email", $user_data->email);
                $this->session->set_userdata('user_activity', time());
                $this->session->set_userdata("role_name", "Admin");
                if($role == 'admin'):
                $this->session->set_userdata("company_name", $user_data->company_name);
                $this->session->set_userdata("last_login", $user_data->last_login);
                $this->session->set_userdata("last_ip", $user_data->last_ip);
                $this->session->set_userdata("role", "admin");
                $this->Common_model->updateFields(ADMIN, array('last_login' => $last_login, 'last_ip' => getRealIpAddr()), array('id' => $user_data->id));
                endif;
                if($role == 'agent'):
                    $this->session->set_userdata("full_name", $user_data->full_name);
                    $this->session->set_userdata("role_id", $user_data->role_id);
                    $this->session->set_userdata("role", "agent");
                    $roleData = $this->Common_model->getsingle(ROLE, array('id' =>$user_data->role_id));
                    if(!empty($roleData)){
                       $this->session->set_userdata("role_name", $roleData->name);
                    }
                endif;

                if($role == 'store'):
                    $this->session->set_userdata("store_name", $user_data->store_name);
                    $this->session->set_userdata("role", "store");
                    $this->session->set_userdata("role_name", $user_data->store_name);
                endif;


                if (isset($data['remember_me'])) {
                    $cookie = array(
                        'name' => 'siteadmin',
                        'value' => encoding($user_data->email . "_" . $user_id . "_" . $role),
                        'expire' => '864000000', // 10 days
                    );
                    $this->input->set_cookie($cookie);
                }
                echo "success";
                exit;
            } else {
                echo "error";
                exit;
            }
        }
    }



}

/* End of file Siteadmin.php */
/* Location: ./application/controllers/Siteadmin.php */
?>