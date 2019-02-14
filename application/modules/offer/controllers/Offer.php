<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Offer extends Common_Controller {

    public $data = array();
    public $file_data = "";
    public $_table = OFFER;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['url'] = base_url() . 'offer';
        $this->data['pageTitle'] = lang('offer');
        $this->data['parent'] = "Offer";
        $user_id = $this->uri->segment(3);

        if (!empty($user_id)) {

            $option = array(
                'table' => OFFER . ' as offer',
                'select' => 'offer.offer_type,offer.offer_points,offer.offer_name_en as offer_name,offer.description_en as description, offer.from_date,offer.to_date,offer.no_of_scane,offer.offer_code,offer.id,offer.image,offer.discounts_in_percent,offer.qr_image,offer.offer_limit,' . 'user.name',
                'join' => array(array(USER_OFFERS . ' as user_offer' => 'user_offer.offer_id=offer.id'),
                    array(USERS . ' as user' => 'user.id=user_offer.user_id')),
                'where' => array('user_offer.user_id' => $user_id),
                'order' => array('offer.id' => 'desc')
            );

            if (getDefaultLanguage() == "el") {
                $option['select'] = 'offer.offer_type,offer.offer_points,offer.offer_name_el as offer_name,offer.description_el as description, offer.from_date,offer.to_date,offer.no_of_scane,offer.offer_code,offer.id,offer.image,offer.discounts_in_percent,offer.qr_image,offer.offer_limit,' . 'user.name';
            }


            $this->data['list'] = $this->Common_model->customGet($option);
        } else {
            $option = array('table' => $this->_table . ' as offer',
                'select' => 'offer.offer_type,offer.offer_points,service.restaurant_id,service.restaurant_name_en as restaurant_name,offer.offer_name_en as offer_name,offer.description_en as description, offer.from_date,offer.to_date,offer.no_of_scane,offer.offer_code,offer.id,offer.image,offer.discounts_in_percent,offer.qr_image,offer.offer_limit',
                'join' => array(RESTAURANT . ' as service' => 'service.restaurant_id=offer.restaurant_id'),
                'order' => array('id' => 'desc')
            );

            if (getDefaultLanguage() == "el") {
                $option['select'] = 'service.restaurant_id,service.restaurant_name_el as restaurant_name,offer.offer_name_el as offer_name,offer.description_el as description, offer.from_date,offer.to_date,offer.no_of_scane,offer.offer_code,offer.id,offer.image,offer.discounts_in_percent,offer.offer_limit';
            }
            $this->data['list'] = $this->Common_model->customGet($option);
        }
        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

    /**
     * @method open_model
     * @description load model box
     * @return array
     */
    function open_model() {
        $this->data['title'] = lang("add_offer");

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
    public function offer_add() {

        $this->form_validation->set_rules('offer_name_en', lang('offer_name_en'), 'required|trim');
        //$this->form_validation->set_rules('offer_name_el', lang('offer_name_el'), 'required|trim');

        $this->form_validation->set_rules('from_date', lang('from_date'), 'required|trim');
        $this->form_validation->set_rules('to_date', lang('to_date'), 'required|trim');
        //$this->form_validation->set_rules('no_of_scane', lang('no_of_scane'), 'required|trim');
        $this->form_validation->set_rules('offer_code', lang('offer_code'), 'required|trim');
        $this->form_validation->set_rules('description_en', lang('description_en'), 'required|trim');
        //$this->form_validation->set_rules('description_el', lang('description_el'), 'required|trim');
        //$this->form_validation->set_rules('description_el', lang('description_el'), 'required|trim');
        //$this->form_validation->set_rules('discounts_in_percent', lang('discounts_in_percent'), 'required|trim');
        $notification_type = $this->input->post('notification');

        if ($notification_type == 3) {
            $this->form_validation->set_rules('group_name', lang('select_group'), 'required|trim');
        }

        if ($this->form_validation->run() == true) {
            $this->filedata['status'] = 1;
            $image = "";
            if (!empty($_FILES['service_image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'offer', 'service_image');
                if ($this->filedata['status'] == 1) {
                    $image = $this->filedata['upload_data']['file_name'];
                    $full_path = $this->filedata['upload_data']['full_path'];
                    $folder = "offer/thumb";
                    $this->resizeNewImage($full_path, $folder, 308, 828);
                }
            }

            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {

                $qr_code = $this->input->post('offer_code');
                $qrName = $this->input->post('offer_name_en');
                $result = $this->generate_qr($qr_code, $qrName);

                $notification_type = $this->input->post('notification');
                $all_user_list = $this->input->post('user');
                $user_list = $this->input->post('user_name');
                $offer_limit = $this->input->post('offer_limit');

                if ($notification_type == 1) {
                    $options = array('table' => USERS_DEVICE_HISTORY,
                        'select' => 'id'
                    );

                    $user_count = $this->Common_model->customCount($options);
                } else if ($notification_type == 2) {


                    $options = array('table' => USERS,
                        'select' => 'id'
                    );

                    $user_count = $this->Common_model->customCount($options);
                } else if ($notification_type == 3) {
                    $user_count = count($user_list);
                }
                if ($offer_limit <= $user_count) {

                    $offer = $this->input->post('offer_limit');


                    $from_date = str_replace('/', '-', $this->input->post('from_date'));
                    $to_date = str_replace('/', '-', $this->input->post('to_date'));

                    $options_data = array(
                        'company_id' => $this->_uid,
                        'offer_type' => $this->input->post('offer_type'),
                        'offer_points' => (!empty($this->input->post('offer_points'))) ? $this->input->post('offer_points') : 0,
                        'offer_name_en' => $this->input->post('offer_name_en'),
                        'offer_name_el' => $this->input->post('offer_name_el'),
                        'restaurant_id' => self::$_restaurant_id,
                        'description_en' => $this->input->post('description_en'),
                        'description_el' => "",
                        'from_date' => date('Y-m-d', strtotime($from_date)),
                        'to_date' => date('Y-m-d', strtotime($to_date)),
                        //'no_of_scane' => $this->input->post('no_of_scane'),
                        'offer_code' => $this->input->post('offer_code'),
                        'qr_image' => $result,
                        'discounts_in_percent' => (!empty($this->input->post('discounts_in_percent'))) ? $this->input->post('discounts_in_percent') : 0,
                        'offer_limit' => $offer,
                        'image' => $image,
                        'create_date' => datetime(),
                        'is_active' => 1,
                    );

                    $option = array('table' => $this->_table,
                        'data' => $options_data
                    );
                    $last_id = $this->Common_model->customInsert($option);



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
                                'message' => $this->input->post('offer_name_en'),
                                'title' => 'Offer',
                                'type_id' => $last_id,
                                'device_ids' => serialize($user_devices),
                                'notification_type' => 'OFFER_DEVICE',
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
                                    'notification_type' => 'OFFER',
                                    'title' => 'Offer',
                                    'notification_parent_id' => $lid,
                                    'message_en' => $this->input->post('offer_name_en'),
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
                            'message' => $this->input->post('offer_name_en'),
                            'title' => 'Offer',
                            'type_id' => $last_id,
                            'user_ids' => serialize($all_user_list),
                            'notification_type' => 'OFFER',
                            'added_date' => datetime()
                        );
                        $lid = $this->Common_model->insertData(ADMIN_NOTIFICATION, $notification_arr);

                        /* Insert Loyalty Offers & Notifications */
                        $offers = array();
                        $user_notifications = array();
                        for ($i = 0; $i < count($all_user_list); $i++) {

                            $option = array(
                                'offer_id' => $last_id,
                                'user_id' => $all_user_list[$i],
                                'added_date' => datetime()
                            );
                            array_push($offers, $option);

                            $insertArray = array(
                                'type_id' => $last_id,
                                'sender_id' => ADMIN_ID,
                                'reciever_id' => $all_user_list[$i],
                                'notification_type' => 'OFFER',
                                'title' => 'Offer',
                                'notification_parent_id' => $lid,
                                'message_en' => $this->input->post('offer_name_en'),
                                'is_read' => 0,
                                'is_send' => 0,
                                'sent_time' => date('Y-m-d H:i:s'),
                            );
                            array_push($user_notifications, $insertArray);
                        }

                        if (!empty($offers)) {
                            $this->Common_model->insertBulkData(USER_OFFERS, $offers);
                        }
                        if (!empty($user_notifications)) {
                            $this->Common_model->insertBulkData(USER_NOTIFICATION, $user_notifications);
                        }
                    } else if ($notification_type == 3) {
                        if (!empty($user_list)) {
                            /* Insert Notification Request */
                            $notification_arr = array(
                                'message' => $this->input->post('offer_name_en'),
                                'title' => 'Offer',
                                'type_id' => $last_id,
                                'user_ids' => serialize($user_list),
                                'notification_type' => 'OFFER',
                                'added_date' => datetime()
                            );
                            $lid = $this->Common_model->insertData(ADMIN_NOTIFICATION, $notification_arr);

                            /* Insert Loyalty Offers & Notifications */
                            $offers = array();
                            $user_notifications = array();
                            for ($i = 0; $i < count($user_list); $i++) {

                                $option = array(
                                    'offer_id' => $last_id,
                                    'user_id' => $user_list[$i],
                                    'added_date' => datetime()
                                );
                                array_push($offers, $option);

                                $insertArray = array(
                                    'type_id' => $last_id,
                                    'sender_id' => ADMIN_ID,
                                    'reciever_id' => $user_list[$i],
                                    'notification_type' => 'OFFER',
                                    'title' => 'Offer',
                                    'notification_parent_id' => $lid,
                                    'message_en' => $this->input->post('offer_name_en'),
                                    'is_read' => 0,
                                    'is_send' => 0,
                                    'sent_time' => date('Y-m-d H:i:s'),
                                );
                                array_push($user_notifications, $insertArray);
                            }

                            if (!empty($offers)) {
                                $this->Common_model->insertBulkData(USER_OFFERS, $offers);
                            }
                            if (!empty($user_notifications)) {
                                $this->Common_model->insertBulkData(USER_NOTIFICATION, $user_notifications);
                            }
                        }
                    }

                    if ($last_id) {

                        $response = array('status' => 1, 'message' => lang('offer_success'), 'url' => base_url('offer'));
                    } else {
                        $response = array('status' => 0, 'message' => lang('offer_failed'));
                    }
                } else {
                    $response = array('status' => 0, 'message' => lang('offer_exceed'));
                }
            }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method offer_edit
     * @description edit dynamic rows
     * @return array
     */
    public function offer_edit() {
        $this->data['title'] = lang("edit_offer");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {

            $option1 = array(
                'table' => $this->_table,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option1);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('offer');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('offer');
        }
    }

    /**
     * @method offer_update
     * @description update dynamic rows
     * @return array
     */
    public function offer_update() {

        $this->form_validation->set_rules('offer_name_en', lang('offer_name_en'), 'required|trim');
        //$this->form_validation->set_rules('offer_name_el', lang('offer_name_el'), 'required|trim');
        //$this->form_validation->set_rules('store_name', lang('store_name'), 'required|trim');
        $this->form_validation->set_rules('from_date', lang('from_date'), 'required|trim');
        $this->form_validation->set_rules('to_date', lang('to_date'), 'required|trim');
        //$this->form_validation->set_rules('no_of_scane', lang('no_of_scane'), 'required|trim');
        $this->form_validation->set_rules('offer_code', lang('offer_code'), 'required|trim');
        $this->form_validation->set_rules('description_en', lang('description_en'), 'required|trim');
        //$this->form_validation->set_rules('description_el', lang('description_el'), 'required|trim');
        //$this->form_validation->set_rules('description_el', lang('description_el'), 'required|trim');
        //$this->form_validation->set_rules('discounts_in_percent', lang('discounts_in_percent'), 'required|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:

            $this->filedata['status'] = 1;
            $image = $this->input->post('exists_image');
            $upload_url = "uploads/offers/";
            if (!empty($_FILES['service_image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'offer', 'service_image');
                // $this->image_unlink($image,$upload_url);

                if ($this->filedata['status'] == 1) {
                    $image = $this->filedata['upload_data']['file_name'];

                    $full_path = $this->filedata['upload_data']['full_path'];
                    $folder = "offer/thumb";
                    $this->resizeNewImage($full_path, $folder, 308, 828);
                    delete_file($this->input->post('exists_image'), FCPATH . "uploads/offer/");
                    delete_file($this->input->post('exists_image'), FCPATH . "uploads/offer/thumb/");
                }
            }


            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {

                $from_date = str_replace('/', '-', $this->input->post('from_date'));
                $to_date = str_replace('/', '-', $this->input->post('to_date'));

                $options_data = array(
                    'company_id' => $this->_uid,
                    'offer_type' => $this->input->post('offer_type'),
                    'offer_points' => (!empty($this->input->post('offer_points'))) ? $this->input->post('offer_points') : 0,
                    'offer_name_en' => $this->input->post('offer_name_en'),
                    'offer_name_el' => $this->input->post('offer_name_el'),
                    'restaurant_id' => self::$_restaurant_id,
                    'description_en' => $this->input->post('description_en'),
                    'description_el' => $this->input->post('description_el'),
                    'from_date' => date('Y-m-d', strtotime($from_date)),
                    'to_date' => date('Y-m-d', strtotime($to_date)),
                    //'no_of_scane' => $this->input->post('no_of_scane'),
                    'offer_code' => $this->input->post('offer_code'),
                    'discounts_in_percent' => (!empty($this->input->post('discounts_in_percent'))) ? $this->input->post('discounts_in_percent') : 0,
                    'image' => $image
                );
                $option = array(
                    'table' => $this->_table,
                    'data' => $options_data,
                    'where' => array('id' => $where_id)
                );
                $update = $this->Common_model->customUpdate($option);
                $response = array('status' => 1, 'message' => lang('offer_success_update'), 'url' => base_url('offer'));
            }
        endif;

        echo json_encode($response);
    }

    public function generate_qr($code, $qrName) {
        $this->load->library('ci_qr_code');
        $this->config->load('qr_code');
        $qr_code_config = array();
        $qr_code_config['cacheable'] = $this->config->item('cacheable');
        $qr_code_config['cachedir'] = $this->config->item('cachedir');
        $qr_code_config['imagedir'] = $this->config->item('imagedir');
        $qr_code_config['errorlog'] = $this->config->item('errorlog');
        $qr_code_config['ciqrcodelib'] = $this->config->item('ciqrcodelib');
        $qr_code_config['quality'] = $this->config->item('quality');
        $qr_code_config['size'] = $this->config->item('size');
        $qr_code_config['black'] = $this->config->item('black');
        $qr_code_config['white'] = $this->config->item('white');

        $this->ci_qr_code->initialize($qr_code_config);

        $image_name = str_replace(" ", "_", $code) . '_' . time() . '.png';
        $params['data'] = $code;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH . $qr_code_config['imagedir'] . $image_name;
        $qr_code_image_url = base_url() . $qr_code_config['imagedir'] . $image_name;
        $this->ci_qr_code->generate($params);
        return $image_name;
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
        // $opt='<option value="">Select Users</option>';
        foreach ($results as $user) {
            $opt .= "<option value='" . $user->id . "'>" . $user->name . "(" . $user->email . ")</option>";
        }
        echo $opt;
        exit;
    }

    function all_users() {
        //$gender = $this->input->post('gender');

        $option = array('table' => USERS,
            'select' => 'name,id,email',
        );

        $users_data = $this->Common_model->customGet($option);
        $opt = "";
        // $opt='<option value="">Select Users</option>';
        foreach ($users_data as $user) {
            $opt .= "<option value='" . $user->id . "'>" . $user->name . "</option>";
        }
        echo $opt;
        exit;
    }

}
