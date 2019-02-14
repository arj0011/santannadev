<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class News extends Common_Controller {

    public $data = array();
    public $file_data = "";
    public $_table = NEWS;

    public function __construct() {
        parent::__construct();
        //echo FCPATH;exit;
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['url'] = base_url().'news';
        $this->data['pageTitle'] = lang('news');
        $this->data['parent'] = "news_category";
        $option = array('table' => $this->_table . ' as news',
            'select' => 'news.id,news.company_id,news.news_heading_en as news_heading,'
            . 'news.news_content_en as news_content,news.news_location,news.news_image,news.date,catgory.category_name_en as category_name',
            'join' => array(NEWS_CATEGORY . ' as catgory' => 'catgory.id=news.news_category'),
            'order' => array('news.id'=>'DESC')
        );

        if (getDefaultLanguage() == "el") {
            $option['select'] = 'news.id,news.company_id,news.news_heading_el as news_heading,'
                    . 'news.news_content_el as news_content,news.news_location,news.news_image,news.date,catgory.category_name_el as category_name,';
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
        $this->data['title'] = lang("add_news");

        $option = array('table' => NEWS_CATEGORY, 'select' => 'category_name_en as category_name,id');
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'category_name_el as category_name,id';
        }
        $this->data['news'] = $this->Common_model->customGet($option);

        $this->load->view('add', $this->data);
    }

    function open_model_news() {
        $this->data['title'] = lang("news_main_image");

        $option = array('table' => NEWS_TITLE_IMAGE, 'single' => true);
        $this->data['news_result'] = $this->Common_model->customGet($option);
        $this->load->view('main_img_add', $this->data);
    }

    /**
     * @method news_add
     * @description add dynamic rows
     * @return array
     */
    public function news_add() {

        $this->form_validation->set_rules('news_title_en', lang('news_title_en'), 'required|trim');
        $this->form_validation->set_rules('news_title_el', lang('news_title_el'), 'required|trim');
        $notification_type = $this->input->post('notification');

      if($notification_type == 3){
        $this->form_validation->set_rules('group_name', lang('select_group'), 'required|trim');
      }
        if ($this->form_validation->run() == true) {

            $this->filedata['status'] = 1;
            $image = "";
            if (!empty($_FILES['news_image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'news', 'news_image');
                if ($this->filedata['status'] == 1) {
                    $image = $this->filedata['upload_data']['file_name'];
                }
            }

            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {
                $options_data = array(
                    'company_id' => $this->_uid,
                    'news_category' => $this->input->post('news_category'),
                    'news_heading_en' => $this->input->post('news_title_en'),
                    'news_heading_el' => $this->input->post('news_title_el'),
                    'news_content_en' => $this->input->post('news_content_en'),
                    'news_content_el' => $this->input->post('news_content_el'),
                    'news_location' => $this->input->post('news_location'),
                    'news_image' => $image
                );
                $option = array('table' => $this->_table, 'data' => $options_data);
                $last_id = $this->Common_model->customInsert($option);


                $notification_type = $this->input->post('notification');
                $user_list = $this->input->post('user_name');

                $all_user_list = $this->input->post('user');

                if ($notification_type == 1) {

                    $options = array('table' => USERS_DEVICE_HISTORY,
                        'select' => 'id'
                    );

                    $devices = $this->Common_model->customGet($options);
                    $user_devices = array();
                    foreach($devices as $rows){
                       $user_devices[] = $rows->id;
                    }
                    if (!empty($devices)) {
                        $notification_arr = array(
                            'message' => $this->input->post('news_title_en'), //'Admin has added a news',
                            'title' => 'News',
                            'type_id' => $last_id,
                            'device_ids' => serialize($user_devices),
                            'notification_type' => 'NEWS_DEVICE',
                            'added_date' => datetime()
                        );
                        $lid = $this->Common_model->insertData(ADMIN_NOTIFICATION, $notification_arr);

                        $user_notifications = array();
                        foreach ($devices as $device) {

                            $insertArray = array(
                                'type_id' => $last_id,
                                'sender_id' => ADMIN_ID,
                                'reciever_id' => 0,
                                'device_reciever_id' => $device->id,
                                'notification_type' => 'NEWS',
                                'title' => 'News',
                                'notification_parent_id' => $lid,
                                'message_en' => $this->input->post('news_title_en'),
                                'is_read' => 0,
                                'is_send' => 0,
                                'sent_time' => date('Y-m-d H:i:s'),
                            );
                            array_push($user_notifications, $insertArray);
                        }

                        if (!empty($user_notifications)) {
                            $this->Common_model->insertBulkData(USER_NOTIFICATION, $user_notifications);
                        }
                    }
                } else if ($notification_type == 2) {

                    /* Insert Notification Request */
                    $notification_arr = array(
                        'message' => $this->input->post('news_title_en'),
                        'title' => 'News',
                        'type_id' => $last_id,
                        'user_ids' => serialize($all_user_list),
                        'notification_type' => 'NEWS',
                        'added_date' => datetime()
                    );
                    $lid = $this->Common_model->insertData(ADMIN_NOTIFICATION, $notification_arr);

                    /* Insert Loyalty Offers & Notifications */
                    $news = array();
                    $user_notifications = array();
                    for ($i = 0; $i < count($all_user_list); $i++) {

                        $option = array(
                            'news_id' => $last_id,
                            'user_id' => $all_user_list[$i],
                            'added_date' => datetime()
                        );
                        array_push($news, $option);

                        $insertArray = array(
                            'type_id' => $last_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $all_user_list[$i],
                            'notification_type' => 'NEWS',
                            'title' => 'News',
                            'notification_parent_id' => $lid,
                            'message_en' => $this->input->post('news_title_en'),
                            'is_read' => 0,
                            'is_send' => 0,
                            'sent_time' => date('Y-m-d H:i:s'),
                        );
                        array_push($user_notifications, $insertArray);
                    }

                    if (!empty($news)) {
                        $this->Common_model->insertBulkData(USER_NEWS, $news);
                    }
                    if (!empty($user_notifications)) {
                        $this->Common_model->insertBulkData(USER_NOTIFICATION, $user_notifications);
                    }
                } else if ($notification_type == 3) {
                    if (!empty($user_list)) {
                        /* Insert Notification Request */
                        $notification_arr = array(
                            'message' => $this->input->post('news_title_en'),
                            'title' => 'News',
                            'type_id' => $last_id,
                            'user_ids' => serialize($user_list),
                            'notification_type' => 'NEWS',
                            'added_date' => datetime()
                        );
                        $lid = $this->Common_model->insertData(ADMIN_NOTIFICATION, $notification_arr);

                        /* Insert Loyalty Offers & Notifications */
                        $news = array();
                        $user_notifications = array();
                        for ($i = 0; $i < count($user_list); $i++) {

                            $option = array(
                                'news_id' => $last_id,
                                'user_id' => $user_list[$i],
                                'added_date' => datetime()
                            );
                            array_push($news, $option);

                            $insertArray = array(
                                'type_id' => $last_id,
                                'sender_id' => ADMIN_ID,
                                'reciever_id' => $user_list[$i],
                                'notification_type' => 'NEWS',
                                'title' => 'News',
                                'notification_parent_id' => $lid,
                                'message_en' => $this->input->post('news_title_en'),
                                'is_read' => 0,
                                'is_send' => 0,
                                'sent_time' => date('Y-m-d H:i:s'),
                            );
                            array_push($user_notifications, $insertArray);
                        }

                        if (!empty($news)) {
                            $this->Common_model->insertBulkData(USER_NEWS, $news);
                        }
                        if (!empty($user_notifications)) {
                            $this->Common_model->insertBulkData(USER_NOTIFICATION, $user_notifications);
                        }
                    }
                }
                if ($last_id) {
                    $response = array('status' => 1, 'message' => lang('news_success'), 'url' => base_url('news'));
                } else {
                    $response = array('status' => 0, 'message' => lang('news_failed'));
                }
            }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    public function news_main_image_add() {
        $id = $this->input->post('id');
        $image = $this->input->post('exists_image');
        $this->filedata = $this->commonUploadImage($_POST, 'news', 'news_image');
        if ($this->filedata['status'] == 1) {
            $image = $this->filedata['upload_data']['file_name'];
            /* $full_path = $this->filedata['upload_data']['full_path'];
              $folder = "news/thumb";
              $this->resizeNewImage($full_path,$folder,150,85); */
        }

        if ($this->filedata['status'] == 0) {
            $response = array('status' => 0, 'message' => $this->filedata['error']);
        } else {
            if (empty($id)) {

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
            } else {
                $options_data = array(
                    'company_id' => 1,
                    'image' => $image
                );
                $option = array('table' => NEWS_TITLE_IMAGE, 'data' => $options_data, 'where' => array('title_id' => $id));
                if ($this->Common_model->customUpdate($option)) {
                    $response = array('status' => 1, 'message' => lang('news_success'), 'url' => base_url('news'));
                } else {
                    $response = array('status' => 0, 'message' => lang('news_failed'));
                }
            }
        }
        echo json_encode($response);
    }

    /**
     * @method news_edit
     * @description edit dynamic rows
     * @return array
     */
    public function news_edit() {
        $this->data['title'] = lang("edit_news");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {


            $option = array('table' => NEWS_CATEGORY, 'select' => 'category_name_en as category_name,id');
            if (getDefaultLanguage() == "el") {
                $option['select'] = 'category_name_el as category_name,id';
            }
            $this->data['news_category'] = $this->Common_model->customGet($option);

            $option1 = array(
                'table' => NEWS,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option1);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('news');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('news');
        }
    }

    /**
     * @method news_update
     * @description update dynamic rows
     * @return array
     */
    public function news_update() {

        $this->form_validation->set_rules('news_title_en', lang('news_title_en'), 'required|trim');
        $this->form_validation->set_rules('news_title_el', lang('news_title_el'), 'required|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
            $this->filedata['status'] = 1;
            $image = $this->input->post('exists_image');
            if (!empty($_FILES['news_image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'news', 'news_image');
                if ($this->filedata['status'] == 1) {
                    $image = $this->filedata['upload_data']['file_name'];
                    delete_file($this->input->post('exists_image'), FCPATH."uploads/news/");
                   
                }
            }
            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {

                $options_data = array(
                    'news_category' => $this->input->post('news_category'),
                    'news_heading_en' => $this->input->post('news_title_en'),
                    'news_heading_el' => $this->input->post('news_title_el'),
                    'news_content_en' => $this->input->post('news_content_en'),
                    'news_content_el' => $this->input->post('news_content_el'),
                    'news_location' => $this->input->post('news_location'),
                    'news_image' => $image
                );
                $option = array(
                    'table' => NEWS,
                    'data' => $options_data,
                    'where' => array('id' => $where_id)
                );
                $update = $this->Common_model->customUpdate($option);
                $response = array('status' => 1, 'message' => lang('news_success_update'), 'url' => base_url('news'));
            }
        endif;

        echo json_encode($response);
    }

    function getUser($group_id) {

        $option = array('table' => GROUPS_USER . ' as group',
            'select' => 'group.group_id,group.user_id,user.name,user.id,user.email',
            'join' => array(USERS . ' as user' => 'user.id=group.user_id'),
            'where' => array('group.group_id' => $group_id)
        );
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'group.group_id,group.user_id,user.name,user.id,user.email';
        }

        $results = $this->Common_model->customGet($option);
        $opt="";
       // $opt = '<option value="">Select Users</option>';
        foreach ($results as $user) {
            $opt .= "<option value='" . $user->id . "'>" . $user->name . "(".$user->email.")</option>";
        }
        echo $opt;
        exit;
    }

    function all_users() {
        //$gender = $this->input->post('gender');

        $option = array('table' => USERS,
            'select' => 'name,id',
        );

        $users_data = $this->Common_model->customGet($option);
        $opt = "";
        //$opt = '<option value="">Select Users</option>';
        foreach ($users_data as $user) {
            $opt .= "<option value='" . $user->id . "'>" . $user->name . "</option>";
        }
        echo $opt;
        exit;
    }

}
