<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends Common_Controller { 
    public $data = array();
    public $file_data = "";
    public $_table = GROUPS ;
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['parent'] = "Group";
         $option = array('table' => $this->_table,'select' => 'type,user_name,group_id');
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'type,user_name,group_id';
        }
        $this->data['list'] = $this->Common_model->customGet($option);

        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

    /**
     * @method open_model
     * @description load model box
     * @return array
     */

    function open_group() {
        $this->data['title'] = lang("add_group");
        
        $option = array('table' => USERS,'select' => 'name,id');
        
        $this->data['users'] = $this->Common_model->customGet($option);
       // $search = $this->input->post('search');
       //  $users = $this->Common_model->user_auto_search($search['term']);
       //  echo json_encode($users);
       // print_r($user);die;
        
       // $results= json_encode($this->data['res']);
        
        $this->load->view('add', $this->data);

     }
   

    /**
     * @method cms_add
     * @description add dynamic rows
     * @return array
     */
    public function group_add() {
        $this->data['title'] = lang("add_group");
        //$this->form_validation->set_rules('type', lang('type'), 'required|trim');
        //$this->form_validation->set_rules('user_name', lang('user_name'), 'required|trim');
        
        
        if ($this->form_validation->run() == true) {
            
           
                 $options_data = array(
                        //'company_id' => $this->_uid,
                        'type' => $this->input->post('type'),
                        'user_name' => json_encode($this->input->post('user_name')),
                        'create_date' => datetime(),
                        
                    );
                  
                    $option = array('table' => $this->_table, 'data' => $options_data);

                    if ($this->Common_model->customInsert($option)) {
                     

                        $response = array('status' => 1, 'message' => lang('group_success'), 'url' => base_url('group'));
                    
                }else {
                        $response = array('status' => 0, 'message' => lang('group_failed'));
                    } 
                
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method cms_edit
     * @description edit dynamic rows
     * @return array
     */
    public function group_edit() {
        $this->data['title'] = lang("edit_group");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {

             $option = array(
                'table' => USERS,
                'select' => '*'
            );
            $this->data['users'] = $this->Common_model->customGet($option);
         
            $option1 = array(
                'table' => $this->_table,
                'where' => array('group_id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option1);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('group');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('group');
        }
    }

    /**
     * @method cms_update
     * @description update dynamic rows
     * @return array
     */
    public function group_update() {

        $this->form_validation->set_rules('type', lang('type'), 'required|trim');
        //$this->form_validation->set_rules('user_name', lang('user_name'), 'required|trim');

        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:

               
                  $options_data = array(
                       //'company_id' => $this->_uid,
                        'type' => $this->input->post('type'),
                        'user_name' => json_encode($this->input->post('user_name'))
                        
                        
                    );
                    $option = array(
                        'table' => $this->_table,
                        'data' => $options_data,
                        'where' => array('group_id' => $where_id)
                    );
                    $update = $this->Common_model->customUpdate($option);
                    $response = array('status' => 1, 'message' => lang('group_success_update'), 'url' => base_url('group'));
                   

        endif;

        echo json_encode($response);
    }

    public function view_user() {
        $this->data['parent'] = "Group";
        //$this->data['title'] = lang("view_users");
        $id = $this->uri->segment(3);
        if (!empty($id)) {
           
            $option = array('table' => $this->_table.' as group',
                            'select' => 'group.type,group.user_name,group.group_id',
                            'where' => array('group.group_id'=>$id)

                            );
               
          
        }
         
        $this->data['list'] = $this->Common_model->customGet($option);
      
        $this->template->load_view('default', 'user_view', $this->data, 'inner_script');
    }
     public function user_search() {
        $search = $this->input->post('search');
        $user = $this->Common_model->user_auto_search($search['term']);
        //print_r($user);die;
        echo json_encode($user);
    }
}
