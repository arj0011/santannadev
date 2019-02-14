<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for Notification
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Notification extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

    
    /**
     * Function Name: notification_list
     * Description:   To Get Notification List
     */
    function notification_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');   
        //$this->form_validation->set_rules('user_id', 'User Id', 'trim|numeric'); 
        
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $language   = extract_value($data, 'language', '');
            $page_no    = extract_value($data,'page_no',1);  
            $offset     = get_offsets_new($page_no);
            $user_id    = extract_value($data, 'user_id', '');
            $where = array('is_send' => 1);
            $where_not_in = array('notification_type' => array('BOOKING','LOYALTY','ADMIN'));
            
            
            $device_id = extract_value($data, 'device_id', '');
            $devices = array();
            if(!empty($device_id)){
                $opt = array(
                    'table' => USERS_DEVICE_HISTORY,
                    'where' => array('device_id' => $device_id),
                    'single' => true
                );
                $devices = $this->Common_model->customGet($opt);
                if(!empty($devices)){
                  $this->Common_model->updateFields(USERS_DEVICE_HISTORY, array('device_badges' => 0), array('device_id' => $device_id));  
                }
            }
            $or_where = false;
            if(!empty($user_id)){
              // $or_where['reciever_id'] = $user_id;
              if(!empty($devices)){
                $or_where['device_reciever_id'] = $devices->id;  
              }
              $where['reciever_id ='] = $user_id;  
            }else{
               $where['reciever_id ='] = 0;  
                if(!empty($devices)){
                 $where['device_reciever_id'] = $devices->id;  
                }
            }
            $options = array('table' => USER_NOTIFICATION,
                  'select' => 'reciever_id,id,type_id,notification_type,title,if(message_en IS NULL,"",message_en) as message,device_reciever_id,'
                . 'is_read,sent_time,',
                  'where' => $where,
                  'or_where' => $or_where,
                  'order' => array('id' => 'desc'),
                  'limit' => array(30 => $offset)
            );
            if(empty($user_id)){
               $options['where_not_in'] = $where_not_in; 
            }
            
            $list = $this->Common_model->customGet($options);


            if(!empty($user_id)){
               //$options['or_where'] = array('reciever_id' =>$user_id);
               $this->Common_model->updateFields(USERS, array('badges' => 0), array('id' => $user_id));
            }
            /* check for image empty or not */
            if (!empty($list)) {
                $eachArr = array();
                $total_requested = (int) $page_no * 30; 
               

                  /* Get total records */ 
                  $option1 = array('table' => USER_NOTIFICATION,
                  'select' => 'reciever_id,id,type_id,notification_type,title,if(message_en IS NULL,"",message_en) as message,device_reciever_id,'
                . 'is_read,sent_time,',
                  'where' => $where,
                  'or_where' => $or_where,
                  
                );
                if(empty($user_id)){
                   $options['where_not_in'] = $where_not_in; 
                } 
                 $total_records = $this->Common_model->customCount($option1);
                //$total_records = getAllCount(USER_NOTIFICATION,array('reciever_id' =>$user_id));
               
            
                if($total_records > $total_requested){                      
                  $has_next = TRUE;                    
                }else{                        
                  $has_next = FALSE;                    
                }

                foreach ($list as $rows):
                    
                    $temp['user_id']           = null_checker($rows->reciever_id); 
                    $temp['device_reciever_id'] = null_checker($rows->device_reciever_id); 
                    $temp['id']                = null_checker($rows->id);
                    $temp['type_id']           = null_checker($rows->type_id);
                    $temp['notification_type'] = null_checker($rows->notification_type);
                    $temp['title']             = null_checker($rows->title);
                    $temp['message']           = null_checker($rows->message);
                    $temp['is_read']           = null_checker($rows->is_read);
                    $temp['sent_time']         = null_checker(convertDateTime($rows->sent_time));
                    $eachArr[] = $temp; 
                   endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_notification_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_notification_error');
            }
        }
        $this->response($return);
    }

}


/* End of file Menu.php */
/* Location: ./application/controllers/api/v1/Menu.php */
?>

