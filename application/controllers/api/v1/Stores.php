<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for user
 * @package   CodeIgniter
 * @category  Controller
 * @author    Arjun
 */
class Stores extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

    function store_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        // $this->form_validation->set_rules('user_id', 'User Id', 'trim|required|numeric');
        /*if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {*/
            // $user_id  = extract_value($data, 'user_id', '');
            $language = extract_value($data, 'language', '');
            $page_no  = extract_value($data,'page_no',1);  
            $offset   = get_offsets($page_no);

            $options = array(
                'table' => STORE,
                'select' => '*',
                'limit' => array(10 => $offset),
                'order' => array('id' => 'desc')
            );
            
            /* To get customer wallet history from user_wallet_history */
            $list = $this->Common_model->customGet($options);
    
            if (!empty($list)) {
                 $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                  $total_records = getAllCount(STORE);
               
                  if($total_records > $total_requested){   
                    $has_next = TRUE;                    
                  }else{ 
                    $has_next = FALSE;                    
                  }
                $eachArr = array();
                foreach ($list as $rows):
                    /* check for image empty or not */

                    $temp['id']                 = null_checker($rows->id);
                    $temp['store_name']            = null_checker($rows->store_name);
                    $temp['email']           = null_checker($rows->email);
                    $temp['store_place']            = null_checker($rows->store_place);
                    $temp['is_active']              = null_checker($rows->is_active);
                    $temp['create_date']  = null_checker($rows->create_date);
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_store_success_wallethistory');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_store_failed_wallethistory');
            }
        //}
        $this->response($return);
    }

}

/* End of file Stores.php */
/* Location: ./application/controllers/api/v1/Stores.php */
?>