<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for store
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Store extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

 /**
   * Function Name: store_list
   * Description:   To get Store List
 */

    function store_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
         
            $upload_url = base_url().'uploads/service/';
            $language = extract_value($data, 'language', '');
            $limit =   extract_value($data, 'limit', '');
            $offset =  extract_value($data, 'offset', '');
            /* set limit and offset */
            if (empty($limit)) {
                $limit = 10;
            }
            if (empty($offset)) {
                    $offset = 0;
            }

            $options = array('table' => SERVICE . ' as service',
                'select' => 'service.store_id,service.store_name_en as store_name,service.store_address_en as store_address,service.contact_number,service.store_file,service.store_lat,service.store_lang,service.open_time,service.close_time,' . SERVICE_CATEGORY . '.category_name_en as category_name',
                'limit' => array($limit => $offset),
                //'where' => array('info.company_id' => $company_id),
                'join' => array(SERVICE_CATEGORY => SERVICE_CATEGORY . '.id=service.store_category_id')
            );
            if ($language == "el") {
                $options['select'] = 'service.store_id,service.store_name_el as store_name,service.store_address_el as store_address,service.contact_number,service.store_file,service.store_lat,service.store_lang,service.open_time,service.close_time,' . SERVICE_CATEGORY . '.category_name_el as category_name';
            }
             /* To get store list from service table */
            $list = $this->Common_model->customGet($options);
            
            if (!empty($list)) {
                $eachArr = array();

                foreach ($list as $rows):
                    /* check for image empty or not */
                   if(!empty($rows->store_file))
                {
                      $image = $upload_url.$rows->store_file;
                 } else{
                     /* set default image if empty */
                      $image = base_url().'assets/img/no_image.jpg';
                }
                    $temp['store_id']       = null_checker($rows->store_id);
                    $temp['store_name']     = null_checker($rows->store_name);
                    $temp['store_address']  = null_checker($rows->store_address);
                    $temp['contact_number'] = null_checker($rows->contact_number);
                    $temp['store_lat']      = null_checker($rows->store_lat);
                    $temp['store_lang']     = null_checker($rows->store_lang);
                    $temp['open_time']      = null_checker($rows->open_time);
                    $temp['close_time']     = null_checker($rows->close_time);
                    $temp['category_name']  = null_checker($rows->category_name);
                    $temp['store_file']     = null_checker($image);
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['message'] = $this->lang->line('api_store_list_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_store_list_error');
            }
        
        $this->response($return);
    }

    /**
     * Function Name: store_details
     * Description:   To Get Store Details
     */
    function store_details_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('store_id', 'Store Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $store_id = extract_value($data, 'store_id', '');
            $language = extract_value($data, 'language', '');
            $upload_url = base_url().'uploads/service/';

             $options = array('table' => SERVICE . ' as service',
                'select' => 'service.store_id,service.store_name_en as store_name,service.store_address_en as store_address,service.contact_number,service.email,service.store_file,service.store_lat,service.store_lang,service.open_time,service.close_time,' . SERVICE_CATEGORY . '.category_name_en as category_name',
                'where' => array('service.store_id' => $store_id),
                'join' => array(SERVICE_CATEGORY => SERVICE_CATEGORY . '.id=service.store_category_id')
            );
            if ($language == "el") {
                $options['select'] = 'service.store_id,service.store_name_el as store_name,service.store_address_el as store_address,service.contact_number,service.email,service.store_file,service.store_lat,service.store_lang,service.open_time,service.close_time,' . SERVICE_CATEGORY . '.category_name_el as category_name';
            }
            $list = $this->Common_model->customGet($options);
            if(!empty($list[0]->store_file))
              {
                      $image = $upload_url.$list[0]->store_file;
              } else{
                      $image = base_url().'assets/img/no_image.jpg';
              }
            if (!empty($list)) {
                $eachArr = array();

                foreach ($list as $rows):

                    $temp['store_id']      = null_checker($rows->store_id);
                    $temp['store_name']    = null_checker($rows->store_name);
                    $temp['category_name'] = null_checker($rows->category_name);
                    $temp['store_address'] = null_checker($rows->store_address);
                    $temp['email']         = null_checker($rows->email);
                    $temp['contact_number']= null_checker($rows->contact_number);
                    $temp['open_time']     = null_checker($rows->open_time);
                    $temp['close_time']    = null_checker($rows->close_time);
                    $temp['store_file']    = null_checker($image);
                   
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['message'] = $this->lang->line('api_store_details_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_store_details_error');
            }
        }
        $this->response($return);
    }

     /**
     * Function Name: send_email_store
     * Description:   To Send Store Message
    */

    function send_email_store_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('store_id', 'Store Id', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('surname', 'Surname', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');
       
        
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $insertArr = array();
            $language  = extract_value($data, 'language', '');
            $store_id  = extract_value($data, 'store_id', '');
            $name      = extract_value($data, 'name', '');
            $surname   = extract_value($data, 'surname', '');
            $message   = extract_value($data, 'message', '');
            $phone     = extract_value($data, 'phone', '');
            $email     = extract_value($data, 'email', '');
            
           
           $options = array('table' => SERVICE,
            'select' => 'store_id,store_name_en as store_name,email',
            'where' =>  array('store_id' => $store_id),
            'single' => true
            );
           if ($language == "el") {
                $options['select'] = 'store_id,store_name_el as store_name,email';
            }
           $list = $this->Common_model->customGet($options);
           if(!empty($list)){

            $this->filedata['status'] = 1;
            $image = "";
            if (!empty($_FILES['image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'service', 'image');
                if ($this->filedata['status'] == 1) {
                 $image = $this->filedata['upload_data']['file_name'];
                }
               
            }
                  
            if ($this->filedata['status'] == 0) {
               $response = array('status' => 0, 'message' => $this->filedata['error']);  
            }else{

                $insertArr['store_id']   = $store_id;
                $insertArr['name']       = $name;
                $insertArr['surname']    = $surname;
                $insertArr['email']      = $email;
                $insertArr['phone']      = $phone;
                $insertArr['message']    = $message;
                $insertArr['image']      = $image;
                $insertArr['create_datetime'] = date('Y-m-d H:i:s');
                
                $options = array('table' => STORE_SENT_MESSAGE,
                    'data' => $insertArr,
                );

                $insert_id = $this->Common_model->customInsert($options);
                $emails = explode(',', $list->email);
                $sent = false;
                foreach($emails as $to){
                if(!empty($to)){
                      $from = 'infofeedback@gmail.com';
                      $url_img = base_url().'uploads/service/'.$image;
                      $message = "<html><body>"
                              . "<h4>Hi,</h4>"
                              . "<div>Name: ".$name." ".$surname."</div></n>"
                              . "<div>Phone: ".$phone."</div></n>"
                              . "<div>Email: ".$email."</div></n>"
                              . "<div>Message: ".$message."</div></n>"
                              . "<div><img src='$url_img' width='150'/></div></n>"
                              . "<div>Best Regards,</div>"
                              . "</body</html>";
                      $subject = "Feedback message";
                      $title  = "Feedback";
                      send_mail($message, $subject, $email,$to,$from,$title);
                      
            }
         }
        
            if ($sent) {
                 /* return success response*/
                $return['status'] = 1;
                $return['message'] = $this->lang->line('api_store_email_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_store_email_failed');
            }
        }
    }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_store_not_found');
        }
    
  }
        $this->response($return);
    }
    
    
}

/* End of file Store.php */
/* Location: ./application/controllers/api/v1/Store.php */
?>