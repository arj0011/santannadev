<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Group extends Common_Controller {

    public $data = array();
    public $file_data = "";
    public $_table = GROUPS;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['url'] = base_url().'group';
        $this->data['pageTitle'] = lang('group');
        $this->data['parent'] = "Group";
        $option = array('table' => $this->_table, 'select' => 'users,group_id,group_name');
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'users,group_id,group_name';
        }
        $this->data['list'] = $this->Common_model->customGet($option);

        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

    /**
     * @method group_add
     * @description add dynamic rows
     * @return array
     */
    public function group_add($group_id = 0) {
        $this->data['title'] = lang("add_group");
        $this->data['parent'] = "Group";
        $this->data['headline'] = "Group add";



        if (isset($_POST) && !empty($_POST)):



            $this->form_validation->set_rules('group_name', 'Group Name', 'required');
            //$this->form_validation->set_rules('age', 'Age', 'required');

            if ($this->form_validation->run() == FALSE):


                $this->template->load_view('default', 'add', $this->data, 'inner_script');
            else:


                $option_post = array(
                    'group_name' => $this->input->post('group_name'),
                    'users' => json_encode($this->input->post('user_name')),
                    'create_date' => date('y-m-d H:i:s')
                );
                $option = array(
                    'table' => $this->_table,
                    'data' => $option_post
                );
                $last_id = $this->Common_model->customInsert($option);

                $user_list = $this->input->post('user_name');
                for ($ui = 0; $ui < count($user_list); $ui++) {

                    $option = array(
                        'table' => "groups_user",
                        'data' => array('group_id' => $last_id, 'user_id' => $user_list[$ui])
                    );
                    $this->Common_model->customInsert($option);
                }

                if ($last_id) {
                    $this->session->set_flashdata('message', 'Successfully insert your record');
                    redirect('group');
                } else {
                    $this->session->set_flashdata('error', 'Failed insert your record');
                    redirect('group');
                }


            endif;
        else:

            $this->template->load_view('default', 'add', $this->data, 'inner_script');
        endif;
    }

    /**
     * @method group_edit
     * @description edit dynamic rows
     * @return array
     */
    public function group_edit($group_id = 0) {
        $this->data['title'] = lang("edit_group");
        $this->data['parent'] = "Group";
        $this->data['headline'] = "Group edit";

        if (isset($_POST) && !empty($_POST)) {
            $this->form_validation->set_rules('group_name', 'Group Name', 'required');
            if ($this->form_validation->run() == true) {
                $options_data = array(
                    'group_name' => $this->input->post('group_name'),
                    'users' => json_encode($this->input->post('user_name')),
                    'create_date' => date('Y-m-d H:i:s')
                );

                $option = array(
                    'table' => GROUPS,
                    'data' => $options_data,
                    'where' => array('group_id' => $group_id)
                );
                $this->Common_model->customUpdate($option);

                $option = array(
                    'table' => 'groups_user',
                    'where' => array('group_id' => $group_id)
                );
                $groupUserList = $this->Common_model->customGet($option);
                if (count($groupUserList) > 0) {
                    $option = array(
                        'table' => 'groups_user',
                        'where' => array('group_id' => $group_id)
                    );

                    $this->Common_model->customDelete($option);
                }






                $user_list = $this->input->post('user_name');
                for ($ui = 0; $ui < count($user_list); $ui++) {

                    $option = array(
                        'table' => "groups_user",
                        'data' => array('group_id' => $group_id, 'user_id' => $user_list[$ui])
                    );
                    $this->Common_model->customInsert($option);
                }
            }


            redirect('group');
        } else {

            if (!empty($group_id)) {

                $option = array(
                    'table' => USERS,
                    'select' => '*'
                );
                $this->data['users'] = $this->Common_model->customGet($option);

                $option1 = array(
                    'table' => $this->_table,
                    'where' => array('group_id' => $group_id),
                    'single' => true
                );
                $results_row = $this->Common_model->customGet($option1);
                if (!empty($results_row)) {


                    $this->data['results'] = $results_row;


                    $user_list = json_decode($results_row->users);

                    $this->data['users'] = $this->Common_model->getGroupUser('users', $user_list);


                    $this->template->load_view('default', 'edit', $this->data, 'inner_script');

                    // $this->load->view('edit', $this->data);
                } else {
                    $this->session->set_flashdata('error', lang('not_found'));
                    redirect('group');
                }
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('group');
            }
        }
    }

    /**
     * @method view_user
     * @description view user details
     * @return array
     */
    public function view_user() {
        $this->data['parent'] = "Group";
        //$this->data['title'] = lang("view_users");
        $id = $this->uri->segment(3);
        if (!empty($id)) {

            $option = array('table' => $this->_table . ' as group',
                'select' => 'group.type,group.user_name,group.group_id',
                'where' => array('group.group_id' => $id)
            );
        }

        $this->data['list'] = $this->Common_model->customGet($option);

        $this->template->load_view('default', 'user_view', $this->data, 'inner_script');
    }

    /**
     * @method group_filter
     * @description filter user according gender
     * @return array
     */
    public function group_filter() {
        $this->data['parent'] = "Group";

        $age = $this->input->post('age');
        $gender = $this->input->post('gender');

       
        
        $option = array('table' => USERS,
            'select' => 'name,id,email',
            'where' => array('gender' => $gender)
        );

         if ($age != "") {
            $diducted_birth = $this->diducted_birth($age);
            $option['where']['date_of_birth > '] = $diducted_birth;
        }
        
        $users_data = $this->Common_model->customGet($option);
           $opt ="";
        //$opt='<option value="">Select Users</option>';
        foreach ($users_data as $user) {
            $opt .= "<option value='" . $user->id . "'>" . $user->name . "(".$user->email.")</option>";
        }
        echo $opt;
        exit;
    }

    /**
     * @method user_filter
     * @description filter users according age and gender
     * @return array
     */
    public function user_filter() {

        $gender = "";//$this->input->post('gender');
        $age = $this->input->post('age');

        $where = array();

        if ($gender != "") {
            $where['gender'] = $gender;
        }

        if ($age != "") {
            $diducted_birth = $this->diducted_birth($age);
            
            $where['date_of_birth > '] = $diducted_birth;
        }


        $option = array('table' => USERS,
            'select' => 'name,id,email',
            'where' => $where
        );

        $users_data = $this->Common_model->customGet($option);
        $opt = "";
        foreach ($users_data as $user) {
            $opt .= "<option value='" . $user->id . "'>" . $user->name . "(".$user->email.")</option>";
        }
        echo $opt;
        exit;
    }

    /**
     * @method group_user_list
     * @description get user list
     * @return array
     */
    function group_user_list() {
        $groupId = $_POST['groupID'];

        $option1 = array(
            'table' => $this->_table,
            'where' => array('group_id' => $groupId),
            'single' => true
        );
        $results_row = $this->Common_model->customGet($option1);
        $res = "";
        if (!empty($results_row)) {

            $user_list = json_decode($results_row->users);
            $users_list = $this->Common_model->getGroupUser('users', $user_list);
            $res.="<div class='row'>";
            foreach ($users_list as $user) {

                $res.="<div class='col-md-4 text-center'><h4>" . ucfirst($user->name) . "(".$user->email.")</h4><img src='" . base_url(($user->user_image == '') ? "uploads/users/1491543652_man.png" : $user->user_image) . "' style='width:150px;height:150px; border:7px solid #dadada; border-radius:70px;'></div>";
            }
            $res.="</div>";
        } else {
            $res = "No user Found";
        }

        echo $res;
    }

    /**
     * @method diducted_birth
     * @description get age from date of birth
     * @return array
     */
    function diducted_birth($age) {

        $year = date('Y');
        $month = date('m');
        $date = date('d');

        $year = $year - $age;

        return $year . "-" . $month . "-" . $date;
    }

}
