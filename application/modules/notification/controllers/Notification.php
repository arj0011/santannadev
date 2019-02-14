<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Notification extends Common_Controller {

    public $data = array();
    public $file_data = "";
    public $_table = USER_NOTIFICATION;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['url'] = base_url().'notification';
        $this->data['pageTitle'] = lang('notification');
        $dates = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));
        $this->data['parent'] = "notification";
        $id = $this->session->userdata('id');
        $this->data['list'] = array();
        if ($this->session->userdata('role') == 'admin') {
            $option = array('table' => $this->_table . ' as notify',
                'select' => 'notify.booking_id,notify.id,notify.notification_type,notify.title,notify.message_en as message,notify.sent_time,notify.is_read,user.name,user.user_image',
                'join' => array('users as user' => 'user.id=notify.sender_id',),
                'order' => array('notify.id' => 'desc'),
                'where' => array(
                    'notify.notification_type' => 'ADMIN',
                    'DATE(notify.sent_time) >=' => $dates
                )
            );
            if (getDefaultLanguage() == "el") {
                $option['select'] = 'id,notification_type,title,message_el as message,sent_time,is_read';
            }
            $this->data['list'] = $this->Common_model->customGet($option);
        } else {

            $sql = "SELECT notify.booking_id,notify.id,notify.notification_type,notify.title,notify.message_en as message,"
                    . " notify.sent_time,notify.is_read,user.name,user.user_image FROM users_notifications as notify"
                    . " INNER JOIN users as user ON user.id=notify.sender_id "
                    . " INNER JOIN mw_booking as book ON book.id=notify.booking_id"
                    . " WHERE notify.notification_type = 'ADMIN'"
                    . " AND DATE(notify.sent_time) >= $dates AND book.agent_id = $id";
            $sql .= " UNION ";
            $sql .= "SELECT notify.booking_id,notify.id,notify.notification_type,notify.title,notify.message_en as message,"
                    . " notify.sent_time,notify.is_read,user.full_name as name,CONCAT('uploads/agents/',user.image) as user_image FROM users_notifications as notify"
                    . " INNER JOIN agents as user ON user.id=notify.agent_id "
                    . " WHERE notify.notification_type = 'ADMIN'"
                    . " AND DATE(notify.sent_time) >= $dates AND notify.agent_id = $id order by id DESC";

            $this->data['list'] = $this->Common_model->customQuery($sql);
        }

        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

    public function notification_ajaxs() {
        $search = $this->input->post('searchstr');
        $where['notify.notification_type'] = "ADMIN";
        echo $this->Common_model->notification_ajax($search, $where);
    }

    public function read_notification() {
        $id = $this->uri->segment(3);
        $where = array('id' => $id);

        $option = array(
            'table' => 'notifications',
            'where' => $where,
            'single' => true
        );
        $notification = $this->Common_model->customGet($option);
        $where = array('title' => $notification->title, 'company_id' => $notification->company_id, 'message' => $notification->message);
        $mark_read = array('read_status' => 1);
        $update = $this->Common_model->updateFields('notifications', $mark_read, $where);
        redirect('notification/push_message');
    }

    public function read_notification_admin() {

        $delId = decoding($_GET['q']);
        if (!empty($delId)) {
            foreach ($delId as $rows) {
                $options = array(
                    'table' => 'users_notifications',
                    'data' => array('is_read' => 1),
                    'where' => array(
                        'notification_type' => 'ADMIN',
                        'is_read' => 0,
                        'id' => $rows
                    )
                );
                $this->Common_model->customUpdate($options);
            }
        }

        redirect('notification/push_message');
    }

    /**
     * @method open_model
     * @description load model box
     * @return array
     */
    function open_model() {
        $this->data['title'] = lang("send_notification");

        $option1 = array('table' => USERS, 'select' => 'CONCAT(name," (",email,")") as name,id');
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'CONCAT(name," (",email,")") as name,id';
        }
        $this->data['users'] = $this->Common_model->customGet($option1);

        $option = array('table' => GROUPS, 'select' => 'group_name,group_id');
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'group_name,group_id';
        }
        $this->data['results'] = $this->Common_model->customGet($option);

        $this->load->view('add', $this->data);
    }

    /**
     * @method offer_add
     * @description add dynamic rows
     * @return array
     */
    public function notification_add() {
        $this->form_validation->set_rules('title', lang('title'), 'required|trim');
        $this->form_validation->set_rules('message', lang('message'), 'required|trim');

        $notification_type = $this->input->post('notification');

        if ($notification_type == 3) {
            $this->form_validation->set_rules('group_name', lang('select_group'), 'required|trim');
        } elseif ($notification_type == 4) {
            $this->form_validation->set_rules('user_id', lang('select_name'), 'required|trim');
        }

        if ($this->form_validation->run() == true) {


            $notification_type = $this->input->post('notification');
            $all_user_list = $this->input->post('user');
            $user_list = $this->input->post('user_name');
            $user = array($this->input->post('user_id'));


            if ($notification_type == 1) {

                $options = array('table' => USERS_DEVICE_HISTORY,
                    'select' => 'id'
                );

                $devices = $this->Common_model->customGet($options);
                $user_devices = array();
                foreach ($devices as $rows) {
                    $user_devices[] = $rows->id;
                }
                if (!empty($devices)) {
                    $notification_arr = array(
                        'message' => $this->input->post('message'),
                        'title' => $this->input->post('title'),
                        //'type_id' => $last_id,
                        'device_ids' => serialize($user_devices),
                        'notification_type' => 'ADMIN_DEVICE',
                        'added_date' => datetime()
                    );
                    $lid = $this->Common_model->insertData(ADMIN_NOTIFICATION, $notification_arr);

                    $user_notifications = array();
                    foreach ($devices as $device) {

                        $insertArray = array(
                            //'type_id' => $last_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => 0,
                            'device_reciever_id' => $device->id,
                            'notification_type' => 'ADMIN',
                            'title' => $this->input->post('title'),
                            'notification_parent_id' => $lid,
                            'message_en' => $this->input->post('message'),
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
                    'message' => $this->input->post('message'),
                    'title' => $this->input->post('title'),
                    //'type_id' => $last_id,
                    'user_ids' => serialize($all_user_list),
                    'notification_type' => 'ADMIN',
                    'added_date' => datetime()
                );
                $lid = $this->Common_model->insertData(ADMIN_NOTIFICATION, $notification_arr);

                /* Insert Notifications */

                $user_notifications = array();
                for ($i = 0; $i < count($all_user_list); $i++) {


                    $insertArray = array(
                        //'type_id' => $last_id,
                        'sender_id' => ADMIN_ID,
                        'reciever_id' => $all_user_list[$i],
                        'notification_type' => 'ADMIN',
                        'title' => $this->input->post('title'),
                        'notification_parent_id' => $lid,
                        'message_en' => $this->input->post('message'),
                        'is_read' => 0,
                        'is_send' => 0,
                        'sent_time' => date('Y-m-d H:i:s'),
                    );
                    array_push($user_notifications, $insertArray);
                }


                if (!empty($user_notifications)) {
                    $this->Common_model->insertBulkData(USER_NOTIFICATION, $user_notifications);
                }
            } else if ($notification_type == 3) {
                if (!empty($user_list)) {

                    /* Insert Notification Request */
                    $notification_arr = array(
                        'message' => $this->input->post('message'),
                        'title' => $this->input->post('title'),
                        //'type_id' => $last_id,
                        'user_ids' => serialize($user_list),
                        'notification_type' => 'ADMIN',
                        'added_date' => datetime()
                    );
                    $lid = $this->Common_model->insertData(ADMIN_NOTIFICATION, $notification_arr);

                    /* Insert Notifications */

                    $user_notifications = array();
                    for ($i = 0; $i < count($user_list); $i++) {

                        $insertArray = array(
                            //'type_id' => $last_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $user_list[$i],
                            'notification_type' => 'ADMIN',
                            'title' => $this->input->post('title'),
                            'notification_parent_id' => $lid,
                            'message_en' => $this->input->post('message'),
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
            } else if ($notification_type == 4) {
                if (!empty($user)) {

                    /* Insert Notification Request */
                    $notification_arr = array(
                        'message' => $this->input->post('message'),
                        'title' => $this->input->post('title'),
                        //'type_id' => $last_id,
                        'user_ids' => serialize($user),
                        'notification_type' => 'ADMIN',
                        'added_date' => datetime()
                    );
                    $lid = $this->Common_model->insertData(ADMIN_NOTIFICATION, $notification_arr);

                    /* Insert Notifications */
                    for ($i = 0; $i < count($user); $i++) {


                        $insertArray = array(
                            //'type_id' => $last_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $user[$i],
                            'notification_type' => 'ADMIN',
                            'title' => $this->input->post('title'),
                            'notification_parent_id' => $lid,
                            'message_en' => $this->input->post('message'),
                            'is_read' => 0,
                            'is_send' => 0,
                            'sent_time' => date('Y-m-d H:i:s'),
                        );


                        $this->Common_model->insertData(USER_NOTIFICATION, $insertArray);
                    }
                }
            }

            if ($lid) {

                $response = array('status' => 1, 'message' => lang('notification_success'), 'url' => base_url('notification'));
            } else {
                $response = array('status' => 0, 'message' => lang('notification_failed'));
            }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
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
        $opt = "";
        foreach ($results as $user) {
            $opt .= "<option value='" . $user->id . "'>" . $user->name . "(" . $user->email . ")</option>";
        }
        echo $opt;
        exit;
    }

    function all_users() {
        $option = array('table' => USERS,
            'select' => 'name,id,email',
        );
        $users_data = $this->Common_model->customGet($option);
        $opt = "";
        foreach ($users_data as $user) {
            $opt .= "<option value='" . $user->id . "'>" . $user->name . "</option>";
        }
        echo $opt;
        exit;
    }

    public function push_message() {
        $this->data['parent'] = "floor_notification";
        $this->data['title'] = "Floor Notification";
        $option = array('table' => 'mw_rooms',
        );
        $this->data['floors'] = $this->Common_model->customGet($option);
        $dates = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));
        $id = $this->session->userdata('id');
        $this->data['list'] = array();
        if ($this->session->userdata('role') == 'admin') {
            $option = array('table' => $this->_table . ' as notify',
                'select' => 'notify.booking_id,notify.id,notify.notification_type,notify.title,notify.message_en as message,notify.sent_time,notify.is_read,user.name,user.user_image',
                'join' => array('users as user' => 'user.id=notify.sender_id',),
                'order' => array('notify.id' => 'desc'),
                'where' => array(
                    'notify.notification_type' => 'ADMIN',
                    'DATE(notify.sent_time) >=' => $dates
                )
            );
            if (getDefaultLanguage() == "el") {
                $option['select'] = 'id,notification_type,title,message_el as message,sent_time,is_read';
            }
            $this->data['list'] = $this->Common_model->customGet($option);
        } else {

            $sql = "SELECT notify.booking_id,notify.id,notify.notification_type,notify.title,notify.message_en as message,"
                    . " notify.sent_time,notify.is_read,user.name,user.user_image FROM users_notifications as notify"
                    . " INNER JOIN users as user ON user.id=notify.sender_id "
                    . " INNER JOIN mw_booking as book ON book.id=notify.booking_id"
                    . " WHERE notify.notification_type = 'ADMIN'"
                    . " AND DATE(notify.sent_time) >= $dates AND book.agent_id = $id";
            $sql .= " UNION ";
            $sql .= "SELECT notify.booking_id,notify.id,notify.notification_type,notify.title,notify.message_en as message,"
                    . " notify.sent_time,notify.is_read,user.full_name as name,CONCAT('uploads/agents/',user.image) as user_image FROM users_notifications as notify"
                    . " INNER JOIN agents as user ON user.id=notify.agent_id "
                    . " WHERE notify.notification_type = 'ADMIN'"
                    . " AND DATE(notify.sent_time) >= $dates AND notify.agent_id = $id order by id DESC";

            $this->data['list'] = $this->Common_model->customQuery($sql);
        }

        $this->template->load_view('default', 'floor_notification', $this->data, 'inner_script');
    }

    public function sendPush() {

        $all = $this->input->post('allsection');
        if (empty($all)) {
            $this->form_validation->set_rules('floor_id', 'Section', 'required|trim');
        }
        $this->form_validation->set_rules('message', 'Message', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        } else {
            $option = array('table' => 'mw_rooms',
            );
            if (empty($all)) {
                $option['where'] = array('id' => $this->input->post('floor_id'));
            }
            $floors = $this->Common_model->customGet($option);
            if (!empty($floors)) {
                foreach ($floors as $rows) {
                    $options_notification = array(
                        'table' => 'users_notifications',
                        'data' => array(
                            'sender_id' => $this->session->userdata('id'),
                            'reciever_id' => 0,
                            'notification_type' => 'ADMIN',
                            'title' => 'New Notification',
                            'message_en' => $this->input->post('message'),
                            'is_read' => 0,
                            'sent_time' => date('Y-m-d H:i:s'),
                            'is_send' => 1,
                            'booking_id' => 0,
                            'agent_id' => $rows->agent
                        )
                    );
                    $this->Common_model->customInsert($options_notification);
                }
            }
            $response = array('status' => 1, 'message' => lang('success_push_message'));
        }
        echo json_encode($response);
    }

    function sendPushNotification() {
        
        $all = $this->input->post('allsection');
        if (empty($all)) {
            $this->form_validation->set_rules('floor_id', 'Section', 'required|trim');
        }
        $this->form_validation->set_rules('message', 'Message', 'required');
        if ($this->form_validation->run() == FALSE) {
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        } else {
            $option = array('table' => 'mw_rooms',
            );
            if (empty($all)) {
                $option['where'] = array('id' => $this->input->post('floor_id'));
            }
            $floors = $this->Common_model->customGet($option);
            if (!empty($floors)) {
                foreach ($floors as $rows) {
                    $options_notification = array(
                        'table' => 'users_notifications',
                        'data' => array(
                            'sender_id' => $this->session->userdata('id'),
                            'reciever_id' => 0,
                            'notification_type' => 'ADMIN',
                            'title' => 'New Notification',
                            'message_en' => $this->input->post('message'),
                            'is_read' => 0,
                            'sent_time' => date('Y-m-d H:i:s'),
                            'is_send' => 1,
                            'booking_id' => 0,
                            'agent_id' => $rows->agent
                        )
                    );
                    $this->Common_model->customInsert($options_notification);
                }
            }
            $response = array('status' => 1, 'message' => lang('success_push_message'));
        }
        echo json_encode($response);
    }

}
