<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . "third_party/MX/Controller.php";

class Common_Controller extends MX_Controller {

    public $filedata = "";
    public static $_restaurant_id = 0;
    public static $_server_key="";
    public $_uid = 0; 

    function __construct() {
        parent::__construct();
        $this->load->helper('language');
        if(!empty($this->session->userdata("id"))){
           $this->_uid = $this->session->userdata("id"); 
       }else{
           $this->_uid = 1;
       }
        $this->_uid = 1;
        $this->language_load();
        $this->session_model->checkAdminSession();
        $option = array('table' => RESTAURANT,
            'single' => true
        );
        $restaurants = $this->Common_model->customGet($option);
        if (!empty($restaurants)) {
            self::$_restaurant_id = $restaurants->restaurant_id;
        }
        $this->is_login();
    }
    
    function is_login(){
        if(!$this->session->userdata('id')){
           redirect('siteadmin'); 
        }
    }

    /**
     * @project Developer
     * @method language_load
     * @description common method load language after change
     * @access public
     * @return boolean
     */
    public function language_load() {
        $_language = $this->session->userdata("language");
        $option = array('table' => LANGUAGE,
            'where' => array('is_default' => 1),
            'single' => true
        );
        $default_language = $this->Common_model->customGet($option);
        if (!empty($default_language)) {
            if ($default_language->language_code) {
                $this->lang->load($default_language->language_code, strtolower($default_language->language_name));
            } else {
                $this->lang->load('en', 'english');
            }
        } else {
            $this->lang->load('en', 'english');
        }
    }

    /**
     * @project Developer
     * @method delete
     * @description common method delete value
     * @access public
     * @return boolean
     */
    public function delete() {
        $response = "";
        $id = decoding($this->input->post('id')); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        if (!empty($table) && !empty($id) && !empty($id_name)) {
            $option = array(
                'table' => $table,
                'where' => array($id_name => $id)
            );
            $delete = $this->Common_model->customDelete($option);
            $response = 200;
        } else {
            $response = 400;
        }
        echo $response;
    }

    /**
     * @project Developer
     * @method status
     * @description common method active or inactive value
     * @access public
     * @return boolean
     */
    public function status() {
        $response = "";
        $id = $this->input->post('id'); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        $status = $this->input->post('status');
        if (!empty($table) && !empty($id) && !empty($id_name)) {
            $option = array(
                'table' => $table,
                'data' => array('is_deactivated' => ($status == 1) ? 0 : 1),
                'where' => array($id_name => $id)
            );
            $update = $this->Common_model->customUpdate($option);
            if ($update) {
                $response = 200;
            } else
                $response = 400;
        }else {
            $response = 400;
        }
        echo $response;
    }

    /**
     * @project Developer
     * @method new_status
     * @description common method active or inactive value
     * @access public
     * @return boolean
     */
    public function new_status() {
        $response = "";
        $id = $this->input->post('id'); // delete id
        $table = $this->input->post('table'); //table name
        $id_name = $this->input->post('id_name'); // table field name
        $status = $this->input->post('status');
        if (!empty($table) && !empty($id) && !empty($id_name)) {
            $option = array(
                'table' => $table,
                'data' => array('is_active' => ($status == 1) ? 0 : 1),
                'where' => array($id_name => $id)
            );
            $update = $this->Common_model->customUpdate($option);
            if ($update) {
                $response = 200;
            } else
                $response = 400;
        }else {
            $response = 400;
        }
        echo $response;
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
     * @method resizeImage
     * @description image resize according to height and width global method
     * @access public
     * @param image_data, url, original_crop
     * @return string
     */
    public function resizeImage($image_data = '') {

        $config['image_library'] = 'gd2';
        $config['source_image'] = $image_data['full_path'];
        $config['new_image'] = './uploads/app/thumb/' . $image_data['file_name'];
        $config['width'] = 140;
        $config['height'] = 140;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
        return true;
    }

    public function resizeNewImage($full_path = '', $folder = '', $height = '', $width = '') {

        $config['image_library'] = 'gd2';
        $config['source_image'] = $full_path;
        $config['new_image'] = './uploads/' . $folder;
        $config['width'] = $width;
        $config['height'] = $height;
        $this->load->library('image_lib', $config);
        $this->image_lib->resize();
        return true;
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
        $config['mailtype'] = 'html';
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

    /**
     * @project Developer
     * @method change_language
     * @description change language
     * @access public
     * @param 
     * @return array
     */
    public function change_language() {
        $language = $this->input->post('language');
        if (!empty($language)) {
            $this->session->set_userdata('language', $language);
            $option = array('table' => LANGUAGE,
                'data' => array('is_default' => 0)
            );
            $this->Common_model->customUpdate($option);
            $option['where'] = array('language_code' => $language);
            $option['data'] = array('is_default' => 1);
            $this->Common_model->customUpdate($option);
            echo 1;
        } else {
            echo 0;
        }
    }

    /**
     * @project Developer
     * @method commonUploadImage
     * @description common upload image
     * @access public
     * @param 
     * @return array
     */
    public function commonUploadImage($data = '', $folder = '', $file_name = '') {

        $this->load->library('upload');
        $config = array(
            'upload_path' => "./uploads/" . $folder,
            'allowed_types' => "gif|jpg|png|jpeg",
            'max_size' => "10000000",
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

    public function image_unlink($filename, $filepath) {

        $file_path_name = $filepath . $filename;
        // print_r($file_path_name);die;
        if (file_exists($file_path_name)) {

            if (unlink($file_path_name)) {
                return true;
            } else {
                return false;
            }
        }
    }

    public function getDevice($id) {

        $option = array('table' => USERS,
            'select' => 'id,device_key,device_type',
            'where' => array('id' => $id),
            'single' => true
        );
        return $this->Common_model->customGet($option);
    }

    public function insertNotification($data) {

        $option = array('table' => USER_NOTIFICATION,
            'data' => $data,
        );
        return $this->Common_model->customInsert($option);
    }

    public function androidFcm($data_array = array(), $target_token) {

        $server_key = "AAAAcRNhqcs:APA91bE_R4dPjGjuy4KZ81xhRZTB6BAYFkdEQNy8L2Wzq0elr5muzjmGT3RnIfb8aYqris8KpR0z489A2irjJitwRE6dthxAj7ynbydmxg3SchJviiRrfZmu_5qKtAJf1t2C6J9_VPOA";
        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
                'to'   => $target_token,
                'notification' => $data_array,
        );
        $headers = array( 
                'Authorization:key=' . $server_key,
                'Content-Type:application/json'
        );
        // Open connection
        $ch = curl_init();
        // Set the url, number of POST vars, POST data
        curl_setopt( $ch, CURLOPT_URL, $url );

        curl_setopt( $ch, CURLOPT_POST, true );
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );

        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Avoids problem with https certificate
        curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false);

        // Execute post
        $result = curl_exec($ch);

        // Close connection
        curl_close($ch);

        return $result; 
    }


    public function p($data){
        echo '<pre>';
        print_r($data);die;
    }

    public function generate_qr_user($code) {
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

}
