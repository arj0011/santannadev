<?php

/* Require Rest Controller Class */
require APPPATH . '/libraries/REST_Controller.php';

class Common_API_Controller extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $data = $this->input->post();
        $this->user_details = array();

        if (empty($data['language'])) {
            $return['code'] = 200;
            $return['response'] = new stdClass();
            $return['status'] = 0;
            $return['message'] = 'Please required language id';
            $this->response($return);
            exit;
        } else {
            $this->language_check($data['language']);
        }
        /* Validate login session key */
        if (isset($data['login_session_key']) && !empty($data['login_session_key'])) {
            $this->user_details = $this->Common_model->getsingle(USERS, array('login_session_key' => $data['login_session_key']));
            if (empty($this->user_details)) {
                $return['code'] = 200;
                $return['response'] = new stdClass();
                $return['status'] = 2;
                $return['message'] = 'Invalid login session key';
                $this->response($return);
                exit;
            } else {

                if ($this->user_details->is_blocked == 1) {
                    $return['code'] = 200;
                    $return['response'] = new stdClass();
                    $return['status'] = 2;
                    $return['message'] = BLOCK_USER;
                    $this->response($return);
                    exit;
                } else if ($this->user_details->is_deactivated == 1) {
                    $return['code'] = 200;
                    $return['response'] = new stdClass();
                    $return['status'] = 2;
                    $return['message'] = DEACTIVATE_USER;
                    $this->response($return);
                    exit;
                }
            }
        }
    }

    /**
     * Function Name: _check_value_exist
     * Description:   To check values exist or not into database
     */
    public function _check_value_exist($str, $field) {
        sscanf($field, '%[^.].%[^.].%[^.]', $table, $field, $msg);
        $rows = $this->db->limit(1)->get_where($table, array($field => $str))->num_rows();
        if ($rows != 0) {
            $this->form_validation->set_message('_check_value_exist', $msg);
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Function Name: _validate_login_session_key
     * Description:   To validate user login session key
     */
    public function _validate_login_session_key($LoginSessionKey) {
        $ci = &get_instance();
        $result = $ci->Common_model->getsingle(USERS, array('login_session_key' => $LoginSessionKey));
        if (!empty($result)) {
            return TRUE;
        } else {
            $ci->form_validation->set_message('_validate_login_session_key', 'Invalid Login Session Key');
            return FALSE;
        }
    }

    /**
     * Function Name: pswd_regex_check
     * Description:   For Password Regular Expression
     */
    public function pswd_regex_check($str) {
        $ci = &get_instance();
        if (1 !== preg_match("/^(?=.*\d)[0-9a-zA-Z!@#$%^&*]{6,}$/", $str)) {
            $ci->form_validation->set_message('pswd_regex_check', 'Password must contain at least 6 characters and numbers');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Function Name: _pageno_min_value
     * Description:   For Check Page No Minmum Value
     */
    public function _pageno_min_value($val) {
        $ci = &get_instance();
        $min = 1;
        if ($min > $val) {
            $ci->form_validation->set_message('_pageno_min_value', 'Page No minimum value should be ' . $min);
            return FALSE;
        } else {
            return TRUE;
        }
    }

    /**
     * Function Name: _validate_date_format
     * Description:   To validate date format
     */
    public function _validate_date_format($date) {
        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            $DateObject = strtotime($date);
            $CurrentDateObject = strtotime(date('Y-m-d'));

            // Date should be greater then or equal to current date
            if ($CurrentDateObject > $DateObject) {
                $this->form_validation->set_message('_validate_date_format', 'Date should be greater then or equal to current date');
                return FALSE;
            }
            return TRUE;
        } else {
            $this->form_validation->set_message('_validate_date_format', 'Invalid date format, should be (YYYY-MM-DD)');
            return FALSE;
        }
    }

    /**
     * Function Name: _validate_birthdate_format
     * Description:   To validate dateofbirth format
     */

     public function _validate_birthdate_format($date) {
        if (preg_match("/^[0-9]{4}-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][0-9]|3[0-1])$/", $date)) {
            
            return TRUE;
        } else {
            $this->form_validation->set_message('_validate_birthdate_format', 'Invalid date format, should be (YYYY-MM-DD)');
            return FALSE;
        }
    }

    /**
     * Function Name: _validate_date_time_format
     * Description:   To validate datetime format
     */
    public function _validate_date_time_format($datetime) {
        $ci = &get_instance();
        if (!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-9]{2}$/", $datetime)) {
            $ci->form_validation->set_message('_validate_date_time_format', 'Invalid datetime format, should be (YYYY-MM-DD HH:II:SS)');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    public function language_check($lang_id) {
        if (!empty($lang_id)) {
            if ($lang_id == 'el') {
                $this->lang->load('el', 'greek');
            } else {
                $this->lang->load('en', 'english');
            }
        } else {
            $this->lang->load('en', 'english');
        }
    }

    /**
     * @project Developer
     * @method uploadImage
     * @description common method upload value
     * @access public
     * @return string
     */
    public function uploadImage($data = '', $folder = '') {

        $config = array(
            'upload_path' => "./uploads/" . $folder,
            'allowed_types' => "gif|jpg|png|jpeg",
            'max_size' => "5048",
            'max_height' => "2048",
            'max_width' => "2048",
            'file_name' => time() . "_" . $_FILES['file_name']['name']
        );
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file_name')) {
            $this->filedata = array('upload_data' => $this->upload->data());
            $this->filedata['status'] = 1;
            return $this->filedata;
        } else {
            $this->filedata = array('error' => $this->upload->display_errors());
            $this->filedata['status'] = 0;
            return $this->filedata;
        }
    }

    /**
     * @project Developer
     * @method uploadImageThumb
     * @description image resize according to height and width global method
     * @access public
     * @param image_data, url, original_crop
     * @return string
     */
    public function uploadImageThumb($data = '', $folder = '', $height = '', $width = '') {

        $config = array(
            'upload_path' => "./uploads/" . $folder,
            'allowed_types' => "gif|jpg|png|jpeg",
            'max_size' => "5048",
            'max_height' => "2024",
            'max_width' => "2024",
            'min_height' => $height,
            'min_width' => $width,
            'file_name' => time() . "_" . $_FILES['file_name']['name']
        );
        $this->load->library('upload', $config);
        if ($this->upload->do_upload('file_name')) {
            $this->filedata = array('upload_data' => $this->upload->data());
            $this->filedata['status'] = 1;
            $this->resizeImage($this->upload->data());
            return $this->filedata;
        } else {
            $this->filedata = array('error' => $this->upload->display_errors());
            $this->filedata['status'] = 0;
            return $this->filedata;
        }
    }

    /**
     * @project Developer
     * @method send_email
     * @description sent email
     * @access public
     * @param 
     * @return array
     */
    function send_email($to, $from, $subject, $template, $title) {

        $this->load->library('email');

        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordwrap'] = TRUE;
        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from($from, $title);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($template);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @project Developer
     * @method send_email_smtp
     * @description sent email smtp
     * @access public
     * @param 
     * @return array
     */
    function send_email_smtp($to, $from, $subject, $template, $title) {

        $this->load->library('email');

        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_port'] = '465';
        $config['smtp_timeout'] = '7';
        $config['smtp_user'] = 'mobiwebtech@gmail.com';
        $config['smtp_pass'] = '*******';
        $config['charset'] = 'iso-8859-1';
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'text';
        $config['validation'] = TRUE;
        $config['wordwrap'] = TRUE;

        $this->email->initialize($config);
        $this->email->set_newline("\r\n");
        $this->email->from($from, $title);
        $this->email->to($to);
        $this->email->subject($subject);
        $this->email->message($template);
        if ($this->email->send()) {
            return true;
        } else {
            return false;
        }
    }
    public function commonUploadImage($data = '', $folder = '',$file_name = '') {

        $this->load->library('upload');
        $config = array(
            'upload_path' => "./uploads/" . $folder,
            'allowed_types' => "gif|jpg|png|jpeg",
            'max_size' => "10048",
            'max_height' => "4048",
            'max_width' => "4048",
            'file_name' => time() . "_" . $_FILES[$file_name]['name']
        );
        $this->upload->initialize($config);
        if ($this->upload->do_upload($file_name)) {
            $this_filedata = array('upload_data' => $this->upload->data());
            $this_filedata['status'] = 1;
            return $this_filedata;
        } else {
            $this_filedata = array('error' => $this->upload->display_errors());
            $this_filedata['status'] = 0;
            return $this_filedata;
        }
    }

}
