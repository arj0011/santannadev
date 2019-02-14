<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Booking extends Common_Controller {

    public $data = array();
    public $file_data = "";
    public $_table = BOOKING;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['parent'] = "Booking";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->uri->segment(3);
        $this->data['booking'] = array(
            'start_date' => $start_date,
            'end_date' => $end_date
        );
        if (!empty($start_date) && !empty($end_date)) {
            $start_date = date('Y-m-d H:i:s', strtotime($start_date));

            $end_date = date('Y-m-d H:i:s', strtotime($end_date));
        } else {
            $start_date = "";
            $end_date = "";
        }
        if (empty($user_id)) {
            $this->data['list'] = $this->Common_model->booking($start_date, $end_date, BOOKING);
        } else {
            $this->data['list'] = $this->Common_model->booking($start_date, $end_date, BOOKING, array('user_id' => $user_id));
        }


        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

    /**
     * @method open_model
     * @description load model box
     * @return array
     */
    function open_model() {
        $this->data['title'] = lang("add_booking");

        $option = array('table' => USERS, 'select' => 'CONCAT(name," (",email,")") as name,id');
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'CONCAT(name," (",email,")") as name,id';
        }
        $this->data['results'] = $this->Common_model->customGet($option);


        $option_data = array('table' => AGENTS, 'select' => 'full_name,id', 'where' => array('is_active' => 1));
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'full_name,id';
        }
        $this->data['agents'] = $this->Common_model->customGet($option_data);

        $this->load->view('add', $this->data);
    }

    /**
     * @method booking_add
     * @description add dynamic rows
     * @return array
     */
    public function booking_add() {

        $this->form_validation->set_rules('user_id', lang('user_id'), 'required|trim');
        $this->form_validation->set_rules('full_name', lang('full_name'), 'required|trim');
        $this->form_validation->set_rules('email', lang('email'), 'required|trim');
        $this->form_validation->set_rules('phone_number', lang('phone_number'), 'required|trim');
        $this->form_validation->set_rules('place', lang('place'), 'required|trim');
        $this->form_validation->set_rules('booking_details', lang('booking_details'), 'required|trim');
        $this->form_validation->set_rules('no_of_persons', lang('no_of_persons'), 'required|trim');
        $this->form_validation->set_rules('reservation_date', lang('reservation_date'), 'required|trim');

        if ($this->form_validation->run() == true) {



            $options_data = array(
                'user_id' => $this->input->post('user_id'),
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'phone_number' => $this->input->post('phone_number'),
                'place' => $this->input->post('place'),
                'booking_details' => $this->input->post('booking_details'),
                'no_of_persons' => $this->input->post('no_of_persons'),
                'reservation_date ' => date('Y-m-d H:i:s', strtotime($this->input->post('reservation_date'))),
                'agent_id' => $this->input->post('agent_id'),
                'status' => 2
            );

            $option = array('table' => $this->_table, 'data' => $options_data);
            if ($this->Common_model->customInsert($option)) {
                $response = array('status' => 1, 'message' => lang('booking_success'), 'url' => base_url('booking'));
            } else {
                $response = array('status' => 0, 'message' => lang('booking_failed'));
            }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method restaurant_edit
     * @description edit dynamic rows
     * @return array
     */
    public function booking_edit() {
        $this->data['title'] = lang("edit_booking");
        $id = decoding($this->input->post('id'));

        if (!empty($id)) {

            $option_data = array(
                'table' => AGENTS,
                'select' => '*'
            );
            $this->data['agents'] = $this->Common_model->customGet($option_data);

            $option = array(
                'table' => USERS,
                'select' => '*'
            );
            $this->data['users'] = $this->Common_model->customGet($option);

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
                redirect('booking');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('booking');
        }
    }

    /**
     * @method restaurant_update
     * @description update dynamic rows
     * @return array
     */
    public function booking_update() {

        $this->form_validation->set_rules('user_id', lang('user_id'), 'required|trim');
        $this->form_validation->set_rules('full_name', lang('full_name'), 'required|trim');
        $this->form_validation->set_rules('email', lang('email'), 'required|trim');
        $this->form_validation->set_rules('phone_number', lang('phone_number'), 'required|trim');
        $this->form_validation->set_rules('place', lang('place'), 'required|trim');
        $this->form_validation->set_rules('booking_details', lang('booking_details'), 'required|trim');
        $this->form_validation->set_rules('no_of_persons', lang('no_of_persons'), 'required|trim');
        $this->form_validation->set_rules('reservation_date', lang('reservation_date'), 'required|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:


            $options_data = array(
                'user_id' => $this->input->post('user_id'),
                'full_name' => $this->input->post('full_name'),
                'email' => $this->input->post('email'),
                'phone_number' => $this->input->post('phone_number'),
                'place' => $this->input->post('place'),
                'booking_details' => $this->input->post('booking_details'),
                'no_of_persons' => $this->input->post('no_of_persons'),
                'reservation_date ' => date('Y-m-d H:i:s', strtotime($this->input->post('reservation_date'))),
                'agent_id' => $this->input->post('agent_id'),
            );
            $option = array(
                'table' => $this->_table,
                'data' => $options_data,
                'where' => array('id' => $where_id)
            );
            $update = $this->Common_model->customUpdate($option);
            $response = array('status' => 1, 'message' => lang('booking_success_update'), 'url' => base_url('booking'));

        endif;

        echo json_encode($response);
    }

    /**
     * @method changestatus
     * @description update dynamic rows
     * @return array
     */
   public function changestatus() {
        $where_id = $this->input->post('id');
        $values = $this->input->post('value');

        $options_data = array(
            'status' => $values
        );
        $option = array(
            'table' => $this->_table,
            'data' => $options_data,
            'where' => array('id' => $where_id)
        );
        if ($this->Common_model->customUpdate($option)) {



            $options = array(
                'table' => $this->_table . ' as booking',
                'select' => 'users.id,users.device_id,users.device_type,booking.reservation_date,users.badges,booking.email,booking.full_name',
                'join' => array(USERS . ' as users' => 'users.id=booking.user_id'),
                'where' => array('booking.id' => $where_id),
                'single' => true
            );
            $users_device = $this->Common_model->customGet($options);

            $from = FROM_EMAIL;
            
            $subject = "Booking status";

            $title = "Booking status";
                
                
            if (!empty($users_device)) {
                $status_message = "";
                $email =  $users_device->email;
                $full_name =  $users_device->full_name;
                if ($values == 1) {
                    $status_message = "Admin has been Confirmed your booking on " . date('d F Y h:i:s A', strtotime($users_device->reservation_date));
                    
                     
                     $data['content'] = "Congratulation! Admin has been Confirmed your booking.";
                     $data['user'] = ucwords($full_name);

                     $message = $this->load->view('email_template',$data,true);
                 
                    /* send mail */
                     send_mail($message, $subject, $email,$from,$title);
            

                } else if ($values == 2) {
                    $status_message = "Your booking is Pending on " . date('d F Y h:i:s A', strtotime($users_device->reservation_date));

                     $data['content'] = "Your booking is Pending.";
                     $data['user'] = ucwords($full_name);

                     $message = $this->load->view('email_template',$data,true);

                   /* send mail */
                   send_mail($message, $subject, $email,$from,$title);

                } else if ($values == 3) {
                    $status_message = "Admin has been Cancelled your booking on " . date('d F Y h:i:s A', strtotime($users_device->reservation_date));

                     $data['content'] = "Admin has been Cancelled your booking.";
                     $data['user'] = ucwords($full_name);

                     $message = $this->load->view('email_template',$data,true);

                   /* send mail */
                   send_mail($message, $subject, $email,$from,$title);
                }

                if ($users_device->device_type == 'ANDROID') {

                    $user_badges = $users_device->badges + 1;
                    $data_array = array(
                        'title' => "Booking Status",
                        'body' => $status_message,
                        'type' => 'BOOKING',
                        'type_id' => $where_id,
                        'badges' => $user_badges,
                    );
                    $status = send_android_notification($data_array, $users_device->device_id, $user_badges, $users_device->id);
                    if ($status) {
                        $user_notifications = array(
                            'type_id' => $where_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $users_device->id,
                            'notification_type' => 'BOOKING',
                            'title' => 'Booking',
                            'notification_parent_id' => 0,
                            'message_en' => $status_message,
                            'is_read' => 0,
                            'is_send' => 1,
                            'sent_time' => date('Y-m-d H:i:s'),
                        );
                        $this->Common_model->insertData(USER_NOTIFICATION, $user_notifications);
                    }
                }

                if ($users_device->device_type == 'IOS') {
                    $user_badges = $users_device->badges + 1;
                    $params = array(
                        'title' => "Booking Status",
                        'type' => "Booking",
                        'type_id' => $where_id
                    );
                    $status = send_ios_notification($users_device->device_id, $status_message, $params, $user_badges, $users_device->id);
                    if ($status) {
                        $user_notifications = array(
                            'type_id' => $where_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $users_device->id,
                            'notification_type' => 'BOOKING',
                            'title' => 'Booking',
                            'notification_parent_id' => 0,
                            'message_en' => $status_message,
                            'is_read' => 0,
                            'is_send' => 1,
                            'sent_time' => date('Y-m-d H:i:s'),
                        );
                        $this->Common_model->insertData(USER_NOTIFICATION, $user_notifications);
                    }

                }
            }
               

            echo 1;
        } else {
            echo 0;
        }
    }

    public function booking_view() {
        $this->data['parent'] = "Booking";
        $id = decoding($this->input->post('id'));

        if (!empty($id)) {
            $option = array('table' => $this->_table . ' as booking',
                'select' => 'booking.full_name,booking.id,booking.email,booking.phone_number,booking.place,booking.no_of_persons,booking.booking_details,booking.reservation_date,booking.status,user.name',
                'join' => array(USERS . ' as user' => 'user.id=booking.user_id'),
                'where' => array('booking.id' => $id),
                'single' => true
            );

            if (getDefaultLanguage() == "el") {
                $option['select'] = 'booking.full_name,booking.id,booking.email,booking.phone_number,booking.place,booking.no_of_persons,booking.booking_details,booking.reservation_date,booking.status,user.name';
            }

            $results_row = $this->Common_model->customGet($option);
            // print_r($result);die;
        } $this->data['result'] = $results_row;
        $this->load->view('view', $this->data);
    }

    public function user_search() {
        $search = $this->input->post('search');
        $user = $this->Common_model->user_auto_search($search['term']);
        echo json_encode($user);
    }

    public function user_email() {
        $user_id = $this->input->post('user_id');
        $option = array(
            'table' => USERS,
            'select' => '*',
            'where' => array('id' => $user_id)
        );

        $user_email = $this->Common_model->customGet($option);


        $HTML = '';
        if (!empty($user_email)) {

            $HTML = $user_email[0]->email;
        }

        echo $HTML;
    }

    public function user_name() {
        $user_id = $this->input->post('user_id');
        $option = array(
            'table' => USERS,
            'select' => '*',
            'where' => array('id' => $user_id)
        );

        $user_name = $this->Common_model->customGet($option);

        $HTML = '';
        if (!empty($user_name)) {

            // $HTML = '<select class="form-control required" id="full_name" name="full_name" >';
            // $HTML .= '<option value="">Select email</option>';
            // foreach($user_name as $name)
            //{
            $HTML = $user_name[0]->name;


            //$HTML .= '<option value="' . $user_name[0]->name . '" selected >' . $user_name[0]->name . '</option>';
            //}
            // $HTML .= '</select>';
        }

        echo $HTML;
    }

    public function user_phone_number() {
        $user_id = $this->input->post('user_id');
        $option = array(
            'table' => USERS,
            'select' => '*',
            'where' => array('id' => $user_id)
        );

        $user_phone = $this->Common_model->customGet($option);


        $HTML = '';
        if (!empty($user_phone)) {

            $HTML = $user_phone[0]->phone_number;
        }

        echo $HTML;
    }

}
