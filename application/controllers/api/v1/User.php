<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for user
 * @package   CodeIgniter
 * @category  Controller
 * @author    Sorav Garg
 */
class User extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

    /**
     * Function Name: signup
     * Description:   To User Registration
     */
    function signup_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email|is_unique[' . USERS . '.email]');
        $this->form_validation->set_rules('phone_number', 'Phone No.', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[6]|max_length[14]|callback_pswd_regex_check');
        $this->form_validation->set_rules('confm_pswd', 'Confirm Password', 'trim|required|min_length[6]|max_length[14]|callback_pswd_regex_check|matches[password]');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required|callback__validate_birthdate_format');
        //$this->form_validation->set_rules('address', 'Address', 'trim|required');
        //$this->form_validation->set_rules('country', 'Country', 'trim|required');

        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|in_list[MALE,FEMALE]');
        $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
        $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');
        $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $gender = extract_value($data, 'gender', '');
            $dob = extract_value($data, 'date_of_birth', '');
            $email = extract_value($data, 'email', '');
            $name = extract_value($data, 'name', '');
            $result = $this->generate_qr($email, $name);

            $dataArr = array();
            $dataArr['name'] = $name;
            $dataArr['email'] = $email;
            $dataArr['phone_number'] = extract_value($data, 'phone_number', '');
            $dataArr['password'] = md5(extract_value($data, 'password', ''));
            $dataArr['date_of_birth'] = date('Y-m-d', strtotime($dob));
            $dataArr['is_subscribe'] = extract_value($data, 'subscribe_newsletter', '');
            //$dataArr['country'] = extract_value($data, 'country', '');
            $dataArr['gender'] = $gender;
            $dataArr['qr_image'] = $result;
            $dataArr['login_session_key'] = get_guid();
            $dataArr['device_id'] = extract_value($data, 'device_token', '');
            $dataArr['device_type'] = extract_value($data, 'device_type', '');
            $dataArr['device_key'] = extract_value($data, 'device_id', '');
            $dataArr['is_verified'] = 1;
            $dataArr['is_deactivated'] = 0;
            $dataArr['created_date'] = datetime();

            /* if (isset($_FILES['user_image']['name']) && !empty($_FILES['user_image']['name'])) {
              /* Upload user image */
            $image = fileUpload('user_image', 'users', 'png|jpg|jpeg|gif');
            if (isset($image['error'])) {
                $return['status'] = 0;
                $return['message'] = strip_tags($image['error']);
                $this->response($return);
                exit;
            } else {

                $dataArr['user_image'] = 'uploads/users/' . $image['upload_data']['file_name'];
            }

            /* Create user image thumb */
            $dataArr['user_image_thumb'] = 'uploads/users/' . get_image_thumb($dataArr['user_image'], 'users', 250, 250);
            // }*/

            /* Insert User Data Into Users Table */
            $lid = $this->Common_model->insertData(USERS, $dataArr);
            if ($lid) {
                /* Save user device history */
                //save_user_device_history($lid, $dataArr['device_id'], $dataArr['device_type'], $dataArr['device_key']);

                $email = $dataArr['email'];
                $name = $dataArr['name'];





                /* $token = encoding($email . "-" . $lid . "-" . time());


                  $tokenArr = array('user_token' => $token);
                  $this->Common_model->updateFields(USERS, $tokenArr, array('id' => $lid));

                  $link = base_url() . 'user/verifyuser?email=' . $email . '&token=' . $token;
                  $message = "";
                  $message .= "<img style='width:200px' src='" . base_url() . "assets/img/logo.png' class='img-responsive'></br></br>";
                  $message .= "<br><br> Hello, <br/><br/>";
                  $message .= "Your " . SITE_NAME . " profile has been created. Please click on below link to verify your account. <br/><br/>";
                  $message .= "Click here : <a href='" . $link . "'>Verify Your Email</a>";
                  send_mail($message, '[' . SITE_NAME . '] Thank you for registering with us', $email, FROM_EMAIL);

                 */
                $from = FROM_EMAIL;
                $subject = "New User has been Registered";
                $data['content'] = "Congratulation! You were successfully registered.";
                $data['user'] = ucwords($name);

                $message = $this->load->view('email_template', $data, true);

                $title = "New User";

                /* send mail */
                send_mail($message, $subject, $email, $from, $title);

                $options = array(
                    'table' => 'users_notifications',
                    'data' => array(
                        'sender_id' => $lid,
                        'reciever_id' => 1,
                        'notification_type' => 'ADMIN',
                        'title' => 'New User Register',
                        'message_en' => 'New User has been Registered',
                        'is_read' => 0,
                        'sent_time' => date('Y-m-d H:i:s'),
                        'is_send' => 1
                    )
                );
                $this->Common_model->customInsert($options);




                /* Return success response */
                $return['status'] = 1;
                $return['message'] = $this->lang->line('api_user_registered');
            } else {
                $is_error = db_err_msg();
                if ($is_error == FALSE) {
                    $return['status'] = 1;
                    $return['message'] = $this->lang->line('api_user_registered');
                } else {
                    $return['status'] = 0;
                    $return['message'] = $is_error;
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: login
     * Description:   To User Login
     */
    function login_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_type', 'Login Type', 'trim|required');
        $login_type = strtoupper(extract_value($data, 'login_type', ''));
        if ($login_type == 'APP') {

            $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
            $this->form_validation->set_rules('password', 'Password', 'trim|required');
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
            $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');
            $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
        } else {


            $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
            $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
            $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');
            $this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');


            $login_session_id = get_guid();
            $email = extract_value($data, 'email', '');
            $name = extract_value($data, 'name', NULL);

            $result = $this->generate_qr($email, $name);
            $option = array(
                'table' => USERS,
                'select' => 'qr_image',
                'where' => array('email' => $email),
                'single' => true
            );
            $qr_image = $this->Common_model->customGet($option);

            //print_r($qr_image->qr_image);die;

            $option = array(
                'table' => USERS,
                'where' => array('email' => $email),
                'single' => true
            );
            $email_exist = $this->Common_model->customGet($option);

            $UpdateField = array();
            if ($email_exist) {
                if (empty($qr_image->qr_image)) {

                    $UpdateField['qr_image'] = $result;
                } else {
                    $UpdateField['qr_image'] = $qr_image->qr_image;
                }
            } else {
                $UpdateField['qr_image'] = $result;
            }
            $UpdateField['email'] = extract_value($data, 'email', NULL);
            $UpdateField['gender'] = extract_value($data, 'gender', NULL);
            $UpdateField['social_id'] = extract_value($data, 'social_id', NULL);
            $UpdateField['name'] = $name;
            $UpdateField['device_type'] = extract_value($data, 'device_type', NULL);
            $UpdateField['device_key'] = extract_value($data, 'device_token', NULL);
            $UpdateField['device_id'] = extract_value($data, 'device_id', NULL);
            $UpdateField['is_social_signup'] = 1;
            $UpdateField['social_type'] = 'FACEBOOK';
            $UpdateField['is_verified'] = 1;
            $UpdateField['is_deactivated'] = 0;
            $UpdateField['created_date'] = datetime();




            $url = extract_value($data, 'user_image', NULL);
            if (!empty($url)) {
                $image = "uploads/users/" . time() . "_" . "image.jpg";

                $in = fopen($url, "rb");
                $out = fopen($image, "wb");

                while ($chunk = fread($in, 8192)) {
                    fwrite($out, $chunk, 8192);
                }
                fclose($in);
                fclose($out);
            }

            // error_reporting(E_ALL);
            //  ini_set('display_errors', 1);
            // echo $img = save_file_from_server($url, 'users');
            // exit;
            $UpdateField['user_image'] = $image;

            /* Create user image thumb */
            $UpdateField['user_image_thumb'] = get_image_thumb($UpdateField['user_image'], 'users', 250, 250);

            if ($email_exist) {

                $this->Common_model->updateFields(USERS, $UpdateField, array('email' => $email));
            } else {
                $this->Common_model->insertData(USERS, $UpdateField);
            }
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $login_session_id = get_guid();
            $upload_url = base_url();
            if ($login_type == 'APP') {

                $dataArr['email'] = extract_value($data, 'email', '');
                $dataArr['password'] = md5(extract_value($data, 'password', ''));
            } else {
                $dataArr['email'] = extract_value($data, 'email', '');
            }

            /* Get User Data From Users Table */
            $Status = $this->Common_model->getsingle(USERS, $dataArr);
            

            if (empty($Status)) {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_user_invalid_email');
            } else if (!empty($Status) && $Status->is_verified == 0) {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_user_not_verified');
            } else if (!empty($Status) && $Status->is_blocked == 1) {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_user_block');
            } else if (!empty($Status) && $Status->is_deactivated == 1) {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_user_deactive');
            } else if ($Status->is_verified == 1 && $Status->is_blocked == 0 && $Status->is_deactivated == 0) {

                /* Save user device history */
                //save_user_device_history($Status->id, $data['device_id'], $data['device_type'], $data['device_key']);

                if (!empty($Status->user_image)) {
                    $image = $upload_url . $Status->user_image;
                } else {
                    /* set default image if empty */
                    $image = base_url() . 'assets/img/no_image.jpg';
                }



                /* Update User Data */
                $UpdateData = array();
                if ($login_type == 'APP') {
                    $UpdateData['device_type'] = extract_value($data, 'device_type', NULL);
                    $UpdateData['device_id'] = extract_value($data, 'device_token', NULL);
                    $UpdateData['device_key'] = extract_value($data, 'device_id', NULL);
                    $UpdateData['last_login'] = datetime();
                    $UpdateData['login_session_key'] = $login_session_id;
                    $UpdateData['is_login'] = 1;
                    $UpdateData['is_social_signup'] = 0;
                    $UpdateData['social_type'] = 'APP';
                    $UpdateData['social_id'] = '';
                } else {
                    $UpdateData['device_type'] = extract_value($data, 'device_type', NULL);
                    $UpdateData['device_id'] = extract_value($data, 'device_token', NULL);
                    $UpdateData['device_key'] = extract_value($data, 'device_id', NULL);
                    $UpdateData['last_login'] = datetime();
                    $UpdateData['login_session_key'] = $login_session_id;
                    $UpdateData['is_login'] = 1;
                }
                $this->Common_model->updateFields(USERS, $UpdateData, array('id' => $Status->id));
                /* Return Response */
                $response = array();
                $response['user_id'] = null_checker($Status->id);
                $response['name'] = null_checker($Status->name);
                $response['email'] = null_checker($Status->email);
                $response['phone_number'] = null_checker($Status->phone_number);
                $response['gender'] = null_checker($Status->gender);
                $response['date_of_birth'] = null_checker($Status->date_of_birth);
                $response['user_original_image'] = $image;
                $response['qr_image'] = base_url() . 'tmp/qr_codes/' . $Status->qr_image;
                //$response['user_thumb_image'] = $thumb_image;
                $response['login_session_key'] = $login_session_id;
                $response['total_user_wallet_point'] = $this->Common_model->get_user_wallet_point($user_id);
                $return['status'] = 1;
                $return['response'] = $response;
                $return['message'] = $this->lang->line('api_user_login');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: countries
     * Description:   To Get All Countries
     */
    function countries_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = array();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $countries = $this->Common_model->getAll(COUNTRY, 'name', 'ASC');
            if ($countries['result']) {
                /* Return Response */
                $response = array();

                foreach ($countries['result'] as $r) {
                    $country_name = null_checker($r->name);
                    array_push($response, $country_name);
                }
                $return['status'] = 1;
                $return['response'] = $response;
                $return['message'] = 'success';
            } else {
                $return['status'] = 0;
                $return['message'] = 'Countries not found';
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: resend_verification_link
     * Description:   To Re-send User Verification Link
     */
    function resend_verification_link_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dataArr['email'] = extract_value($data, 'email', '');

            /* Get User Data From Users Table */
            $result = $this->Common_model->getsingle(USERS, $dataArr);
            if (empty($result)) {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_user_email_exist');
            } else {
                if ($result->is_verified == 0) {
                    if ($result->is_social_signup == 0) {
                        $user_id = $result->id;
                        $user_email = $result->email;
                        /* Update user token */
                        $token = encoding($user_email . "-" . $user_id . "-" . time());
                        $tokenArr = array('user_token' => $token);
                        $update_status = $this->Common_model->updateFields(USERS, $tokenArr, array('id' => $user_id));
                        $link = base_url() . 'user/verifyuser?email=' . $user_email . '&token=' . $token;
                        $message = "";
                        $message .= "<img style='width:200px' src='" . base_url() . "assets/img/logo.png' class='img-responsive'></br></br>";
                        $message .= "<br><br> Hello, <br/><br/>";
                        $message .= "Your " . SITE_NAME . " profile has been created. Please click on below link to verify your account. <br/><br/>";
                        $message .= "Click here : <a href='" . $link . "'>Verify Your Email</a>";
                        $status = send_mail($message, '[' . SITE_NAME . '] Thank you for registering with us', $user_email, FROM_EMAIL);
                        if ($status) {
                            $return['status'] = 1;
                            $return['message'] = $this->lang->line('api_user_email_success');
                        } else {
                            $return['status'] = 0;
                            $return['message'] = $this->lang->line('api_user_email_failed');
                        }
                    } else {
                        $return['status'] = 0;
                        $return['message'] = $this->lang->line('api_user_request_failed');
                    }
                } else {
                    $return['status'] = 0;
                    $return['message'] = $this->lang->line('api_user_verified');
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: forgot_password
     * Description:   To User Forgot Password
     */
    function forgot_password_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dataArr = array();
            $dataArr['email'] = extract_value($data, 'email', '');

            /* Get User Data From Users Table */
            $result = $this->Common_model->getsingle(USERS, $dataArr);
            if (empty($result)) {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_user_email_exist');
            } else {
                if ($result->is_verified == 1) {
                    /* Update user token */
                    $email = $result->email;
                    $user_id = $result->id;
                    $name = $result->name;

                    /*  $token = encoding($email . "-" . $user_id . "-" . time());
                      $updateArr = array('user_token' => $token);
                      $update_status = $this->Common_model->updateFields(USERS, $updateArr, array('id' => $user_id));
                      $link = base_url() . 'users/resetpassword?email=' . $email . '&token=' . $token;
                      $message = "";
                      $message .= "<img style='width:200px' src='" . base_url() . "assets/img/logo.png' class='img-responsive'></br></br>";
                      $message .= "<br><br> Hello, <br/><br/>";
                      $message .= "Somebody (hopefully you) requested a new password for the " . SITE_NAME . " account for " . $result->name . ". No changes have been made to your account yet.<br/><br/>";
                      $message .= "You can reset your password by clicking this <a href='" . $link . "'>link</a>  <br/><br/>";
                      $message .= "We'll be here to help you every step of the way. <br/><br/>";
                      $message .= "If you did not request a new password, let us know immediately by forwarding this email to " . SUPPORT_EMAIL . ". <br/><br/>";
                      $message .= "Thanks, <br/>";
                      $message .= "The " . SITE_NAME . " Team";
                      $status = send_mail($message, 'Reset your ' . SITE_NAME . ' password', $email, FROM_EMAIL);

                     */

                    $subject = 'Reset password for Loyalty App';
                    $from = FROM_EMAIL;
                    $title = "Reset Password";
                    $data = array('email' => $email, 'name' => $name, 'user_id' => base64_encode(base64_encode(base64_encode(base64_encode($user_id)))));

                    $message = $this->load->view('reset_password_mail', $data, TRUE);

                    $sent_mail = send_mail($message, $subject, $email, $from, $title);

                    if ($sent_mail) {
                        $return['status'] = 1;
                        $return['message'] = $this->lang->line('api_user_email_success');
                    } else {
                        $return['status'] = 0;
                        $return['message'] = $this->lang->line('api_user_email_failed');
                    }
                } else {
                    $return['status'] = 0;
                    $return['message'] = $this->lang->line('api_user_not_verified');
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: update_profile
     * Description:   To Update User Profile
     */
    function update_profile_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('phone_number', 'Phone No.', 'trim|required');
        $this->form_validation->set_rules('date_of_birth', 'Date of Birth', 'trim|required|callback__validate_birthdate_format');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|in_list[MALE,FEMALE]');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $dob = extract_value($data, 'date_of_birth', '');
            $dataArr = array();
            $dataArr['name'] = extract_value($data, 'name', '');
            $dataArr['phone_number'] = extract_value($data, 'phone_number', '');
            $dataArr['date_of_birth'] = date('Y-m-d', strtotime($dob));
            $dataArr['gender'] = extract_value($data, 'gender', '');


            /* Update User Data Into Users Table */
            $status = $this->Common_model->updateFields(USERS, $dataArr, array('id' => $this->user_details->id));
            if ($status) {
                /* Return success response */
                $return['status'] = 1;
                $return['message'] = $this->lang->line('api_user_success_update');
            } else {
                $is_error = db_err_msg();
                $return['status'] = 0;
                if ($is_error == FALSE) {
                    $return['message'] = $this->lang->line('api_user_no_update');
                } else {
                    $return['message'] = $is_error;
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: deactivate_account
     * Description:   To Deactivate User Account
     */
    function deactivate_account_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            if ($this->user_details->is_deactivated == 1) {
                $return['status'] = 0;
                $return['message'] = 'Account already deactivated';
            } else if ($this->user_details->is_deactivated == 0) {
                /* Update User Details */
                $status = $this->Common_model->updateFields(USERS, array('is_deactivated' => 1), array('id' => $this->user_details->id));
                if ($status) {
                    $return['status'] = 1;
                    $return['message'] = 'Account deactivated successfully';
                } else {
                    $is_error = db_err_msg();
                    $return['status'] = 0;
                    if ($is_error == FALSE) {
                        $return['message'] = NO_CHANGES;
                    } else {
                        $return['message'] = $is_error;
                    }
                }
            }
        }
        $this->response($return);
    }

    /*
     * Function Name: profile_details
     * Description:   To Get User Profile Details
     */

    function profile_details_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $upload_url = base_url();
            if (!empty($this->user_details->user_image)) {
                $image = $upload_url . $this->user_details->user_image;
            } else {
                /* set default image if empty */
                $image = base_url() . 'assets/img/no_image.jpg';
            }
            if (!empty($this->user_details->user_image_thumb)) {
                $thumb_image = $upload_url . $this->user_details->user_image_thumb;
            } else {
                /* set default image if empty */
                $thumb_image = base_url() . 'assets/img/no_image.jpg';
            }
            /* Return Response */
            $response = array();
            $response['login_session_key'] = null_checker($this->user_details->login_session_key);
            $response['name'] = null_checker($this->user_details->name);
            $response['email'] = null_checker($this->user_details->email);
            $response['phone_number'] = (int) null_checker($this->user_details->phone_number);
            $response['date_of_birth'] = null_checker($this->user_details->date_of_birth);
            $response['gender'] = null_checker($this->user_details->gender);
            $response['user_image'] = $image;
            $response['user_image_thumb'] = $thumb_image;
            $response['qr_image'] = base_url() . 'tmp/qr_codes/' . $this->user_details->qr_image;
            $return['status'] = 1;
            $return['response'] = $response;
            $return['message'] = $this->lang->line('api_user_success_details');
        }
        $this->response($return);
    }

    /**
     * Function Name: change_profile_image
     * Description:   To Change User Profile Image
     */
    function change_profile_image_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required');
        if (empty($_FILES['user_image']['name'])) {
            $this->form_validation->set_rules('user_image', 'User Image', 'required');
        }
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $upload_url = base_url();
            $dataArr = array();

            /* Upload user image */

            $image = fileUpload('user_image', 'users', 'png|jpg|jpeg|gif');
            if (isset($image['error'])) {
                $return['status'] = 0;
                $return['message'] = $image['error'];
            } else {
                $dataArr['user_image'] = 'uploads/users/' . $image['upload_data']['file_name'];

                /* Create user image thumb */
                $dataArr['user_image_thumb'] = 'uploads/users/' . get_image_thumb($dataArr['user_image'], 'users', 250, 250);

                /* Update User Details */
                $status = $this->Common_model->updateFields(USERS, $dataArr, array('id' => $this->user_details->id));
                if ($status) {
                    /* Return Response */
                    $response = array();
                    $response['user_original_image'] = $upload_url . $dataArr['user_image'];
                    $response['user_thumb_image'] = $upload_url . $dataArr['user_image_thumb'];
                    $return['response'] = $response;
                    $return['status'] = 1;
                    $return['message'] = $this->lang->line('api_user_image_success_update');
                } else {
                    $is_error = db_err_msg();
                    $return['status'] = 0;
                    if ($is_error == FALSE) {
                        $return['message'] = $this->lang->line('api_user_no_update');
                    } else {
                        $return['message'] = $is_error;
                    }
                }
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: change_password
     * Description:   To Change User Password
     */
    function change_password_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login Session Key', 'trim|required');
        $this->form_validation->set_rules('current_password', 'Current Password', 'trim|required');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|min_length[6]|max_length[14]|callback_pswd_regex_check');
        $this->form_validation->set_rules('confirm_password', 'Confirm Password', 'trim|required|min_length[6]|max_length[14]|matches[new_password]|callback_pswd_regex_check');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $current_password = extract_value($data, 'current_password', "");
            $new_password = extract_value($data, 'new_password', "");
            $confirm_password = extract_value($data, 'confirm_password', "");

            /* To check user current password */
            $where = array('id' => $this->user_details->id, 'password' => md5($current_password));
            $result = $this->Common_model->getsingle(USERS, $where);
            if (!empty($result)) {

                /* To update new password */
                $updateArr = array(
                    'password' => md5($new_password),
                );
                $update_status = $this->Common_model->updateFields(USERS, $updateArr, array('id' => $this->user_details->id));
                if ($update_status) {
                    $return['status'] = 1;
                    $return['message'] = $this->lang->line('api_user_success_pass');
                } else {
                    $is_error = db_err_msg();
                    $return['status'] = 0;
                    if ($is_error == FALSE) {
                        $return['message'] = $this->lang->line('api_user_failed_pass');
                    } else {
                        $return['message'] = $is_error;
                    }
                }
            } else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_user_invalid_pass');
            }
        }
        $this->response($return);
    }

    /*
     * Function Name: logout
     * Description:   To User Logout
     */

    function logout_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('login_session_key', 'Login Session Key', 'trim|required');
        $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
        $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');
        $this->form_validation->set_rules('device_key', 'Device Key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            /* User Data Array */
            $user_data = array();
            $user_data['device_type'] = extract_value($data, 'device_type', NULL);
            $user_data['device_id'] = extract_value($data, 'device_id', NULL);
            $user_data['device_key'] = extract_value($data, 'device_key', NULL);
            $user_data['user_id'] = $this->user_details->id;

            $UpdateData['is_login'] = 0;
            /* Delete User Device History */
            // $this->Common_model->deleteData(USERS_DEVICE_HISTORY, $user_data);
            $this->Common_model->updateFields(USERS, $UpdateData, array('id' => $this->user_details->id));

            $return['status'] = 1;
            $return['message'] = $this->lang->line('api_user_success_logout');
        }
        $this->response($return);
    }

    /**
     * Function Name: clear_badges
     * Description:   To Clear Notification Badges
     */
    function clear_badges_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login Session Key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $this->Common_model->updateFields(USERS, array('badges' => 0), array('id' => $this->user_details->id));
            $return['status'] = 1;
            $return['message'] = 'Badges cleared successfully';
        }
        $this->response($return);
    }

    /**
     * Function Name: get_badges
     * Description:   To Get Notification Badges
     */
    function get_badges_post() {
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $data = $this->input->post();
        $this->form_validation->set_rules('login_session_key', 'Login Session Key', 'trim|required');
        $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
        $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');
        $this->form_validation->set_rules('device_key', 'Device Key', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $badges = $this->Common_model->getsingle(USERS_DEVICE_HISTORY, array('user_id' => $this->user_details->id));
            if (!empty($badges)) {
                $return['response'] = array('badges' => null_checker($badges->device_badges));
            } else {
                $return['response'] = array('badges' => 0);
            }
            $return['status'] = 1;
            $return['message'] = 'Success';
        }
        $this->response($return);
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

    function importCSV_post() {
        if (isset($_POST)) {
            $filename = $_FILES["file"]["tmp_name"];
            if ($_FILES["file"]["size"] > 0) {
                $file = fopen($filename, "r");
                fgetcsv($file);
                $password = md5(123456);
                while (($getData = fgetcsv($file, 10000, ",")) !== FALSE) {
                    $email = $getData[11];
                    if (!empty($email)) {
                        $name = ucwords($getData[1] . " " . $getData[2]);
                        if (empty($getData[1])) {
                            $explode = explode('@', $email);
                            $name = $explode[0];
                        }
                        $dob = (!empty($getData[6])) ? date('Y-m-d', strtotime($getData[6])) : "0000-00-00";
                        $phone = (!empty($getData[18])) ? $getData[18] : 0;
                        $email_exists = $this->Common_model->customGet(array('table' => 'users', 'where' => array('email' => $email)));
                        if (empty($email_exists)) {

                            $data = array(
                                'name' => $name,
                                'email' => $email,
                                'phone_number' => $phone,
                                'date_of_birth' => $dob,
                                'is_social_signup' => 0,
                                'social_type' => 'APP',
                                'is_verified' => 1,
                                'password' => $password,
                                'country' => $getData[16],
                                'city' => $getData[14],
                                'address' => $getData[13],
                                'title' => $getData[0],
                                'add_member_first_name' => $getData[3],
                                'add_member_last_name' => $getData[4],
                                'company_name' => $getData[5],
                                'nationality' => $getData[10],
                                'other_email' => $getData[12],
                                'zip_code' => $getData[15],
                                'country_code' => $getData[17],
                                'country_code_secondary' => $getData[19],
                                'phone_number_secondary' => $getData[20],
                                'mobile_number_country_code' => $getData[21],
                                'mobile_number' => $getData[22],
                                'fax_country_code' => $getData[23],
                                'fax' => $getData[24],
                                'group_name' => $getData[25],
                                'reference' => $getData[26],
                                'suppliers' => $getData[27],
                                'hoteliers' => $getData[28],
                                'concierge' => $getData[29],
                                'points' => $getData[30],
                                'notes' => $getData[31],
                                'name_day' => $getData[9],
                                'created_date' => date('Y-m-d H:i:s')
                            );
                            $options = array(
                                'table' => 'users',
                                'data' => $data
                            );
                            $this->Common_model->customInsert($options);
                        }
                    }
                }

                fclose($file);
            }
        }
    }


    function wallet_history_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $return['total_points'] = 0;
        $total_points = 0;
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id  = extract_value($data, 'user_id', '');
            $language = extract_value($data, 'language', '');
            $page_no  = extract_value($data,'page_no',1);  
            $offset   = get_offsets($page_no);

            $options = array(
                'table' => USERWALLETHISTORY,
                'select' => '*',
                'where' => array('user_id' => $user_id),
                'limit' => array(10 => $offset),
                'order' => array('id' => 'desc')
            );
            
            /* To get customer wallet history from user_wallet_history */
            $list = $this->Common_model->customGet($options);
            
            $opt = array(
                'table'=>USERPOINTSWALLET,
                'select'=>'total_point',
                'where'=>array('user_id'=>$user_id),
                'single'=>true
            );

            $pointsArr = $this->Common_model->customGet($opt);
            if(!empty($pointsArr)){
                $total_points = $pointsArr->total_point;
            }

            if (!empty($list)) {
                 $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                  $total_records = getAllCount(USERWALLETHISTORY,array('user_id' => $user_id));
               
                  if($total_records > $total_requested){   
                    $has_next = TRUE;                    
                  }else{ 
                    $has_next = FALSE;                    
                  }
                $eachArr = array();
                foreach ($list as $rows):
                    /* check for image empty or not */

                    $temp['id']                   = null_checker($rows->id);
                    $temp['user_id']              = null_checker($rows->user_id);
                    $temp['order_id']             = null_checker($rows->order_id);
                    $temp['total_billing_amount'] = null_checker($rows->total_billing_amount);
                    $temp['actual_payment']       = null_checker($rows->actual_payment);
                    $temp['earn_point']           = null_checker($rows->earn_point);
                    $temp['use_point']            = null_checker($rows->use_point);
                    $temp['floor_id']             = null_checker($rows->floor_id);
                    $temp['created_date']         = null_checker($rows->created_date);
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['total_points'] =  (int)$total_points; 
                $return['message'] = $this->lang->line('api_user_success_wallethistory');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_user_failed_wallethistory');
            }
        }
        $this->response($return);
    }  

}

/* End of file User.php */
/* Location: ./application/controllers/api/v1/User.php */
?>