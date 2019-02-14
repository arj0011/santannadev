<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for loyalty
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Loyalty extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

    /**
     * Function Name: Loyalty_list
     * Description:   To Get Loyalty List
     */
    function Loyalty_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $user_id = extract_value($data, 'user_id', '');
            $language = extract_value($data, 'language', '');
            $options = array('table' => USER_LOYALTY . ' as user_loyalty',
                'select' => 'user_loyalty.status,user_loyalty.loyalty_id,user_loyalty.user_scane,user_loyalty.loyalty_scane,' . LOYALTY . '.id,title_en as title,description_en as description,no_of_scane',
                'where' => array('user_loyalty.user_id' => $user_id,
                                 LOYALTY.'.is_active' => 1),
                'order' => array('user_loyalty.create_date' => 'desc'),
                'join' => array(LOYALTY => LOYALTY . '.id=user_loyalty.loyalty_id')
            );
            if ($language == "el") {
                $options['select'] = 'user_loyalty.loyalty_id,' . LOYALTY . '.id,title_el as title,description_el as description,no_of_scane';
            }
            /* To get loyalty list from loyalty table */
            $list = $this->Common_model->customGet($options);
            if (!empty($list)) {
                $eachArr = array();
                $temp = array();
                foreach ($list as $rows):
                    $temp['id'] = null_checker($rows->id);
                    $temp['title'] = null_checker($rows->title);
                    $temp['description'] = null_checker($rows->description);
                    $temp['no_of_scane'] = null_checker($rows->no_of_scane);
                    $temp['user_scane'] = null_checker($rows->user_scane);
                    $no_of_scane = null_checker($rows->no_of_scane);
                    $user_scane = null_checker($rows->user_scane);
                    $remaining = $no_of_scane - $user_scane;
                    if($no_of_scane == $user_scane){
                       $remaining = 0;
                    }
                    $temp['remaining_scane'] = (string)$remaining;
                    $temp['scane_status'] = $rows->status;

                    //$eachArr[] = $temp;
                endforeach;
                /* return success response */
                $return['status'] = 1;
                $return['response'] = $temp;

                $return['message'] = $this->lang->line('api_loyalty_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_loyalty_failed');
            }
        }
        $this->response($return);
    }
    
    public function loyalty_scane_POST(){
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('loyalty_id', 'Loyalty Id', 'trim|required');
        $this->form_validation->set_rules('qr_code', 'QR Code', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $loyalty_id = $this->input->post('loyalty_id');
            $user_id = $this->input->post('user_id');
            $qr_code = $this->input->post('qr_code');
            $options = array(
                'table' => USER_LOYALTY.' as u_loyalty',
                'select' => 'u_loyalty.id as user_loyalty_id,u_loyalty.loyalty_scane,u_loyalty.user_scane,u_loyalty.status,'
                . 'loyalty.qr_code,loyalty.end_date,loyalty.id as loyalty_id,loyalty.start_date',
                'join' => array(LOYALTY.' as loyalty' => 'loyalty.id=u_loyalty.loyalty_id'),
                'where' => array('u_loyalty.loyalty_id' => $loyalty_id,
                                 'u_loyalty.user_id' => $user_id,
                                 'loyalty.is_active' => 1,
                                 'loyalty.qr_code' => $qr_code),
                'single' => true
            );
            $loyalty = $this->Common_model->customGet($options);
            if(!empty($loyalty)){
                $today_date = strtotime(date('Y-m-d'));
                $end_date = strtotime($loyalty->end_date);
                $start_date = strtotime($loyalty->start_date);
                if($start_date <= $today_date){
                      if($today_date <= $end_date){
                        if($loyalty->status == 1){
                            
                           $options = array('table' => USER_SCANE_HISTORY,
                                            'where' => array(
                                                'user_loyalty_id' => $loyalty->user_loyalty_id,
                                                'loyalty_id' => $loyalty->loyalty_id,
                                                'scane_date' => strtotime(date('Y-m-d')),
  
                                            ),
                                            'single' => true,
                                            'order' => array('id' => 'desc')
                                ); 
                            $current_scane = $this->Common_model->customGet($options);
                            $flag = true;
                            $open = "";
                            if(!empty($current_scane)){
                                //$scane_time = $current_scane->scane_time + 60*60;
                                 $scane_time = $current_scane->scane_time + 100;
                                $current_time = strtotime(date('H:i:s'));
                                if($current_time > $scane_time){
                                    $flag = true;
                                }else{
                                   $flag = false;
                                   $open = date('d F Y',$current_scane->scane_date).' '.date('h:i:s A',$current_scane->scane_time + 60*60);
                                }
                            }

                            if($flag){
                                    $options = array(
                                       'table' => USER_SCANE_HISTORY,
                                       'data' => array('user_loyalty_id' => $loyalty->user_loyalty_id,
                                                       'loyalty_id' => $loyalty->loyalty_id,
                                                       'scane_date' => strtotime(date('Y-m-d')),
                                                       'scane_time' => strtotime(date('H:i:s'))
                                                       )
                                   );
                                   $this->Common_model->customInsert($options);
                                   $status = 1;
                                   $loyalty_scane = $loyalty->loyalty_scane;
                                   $user_scane = $loyalty->user_scane + 1;
                                   if($loyalty_scane == $user_scane){
                                      $status = 2; 
                                   }
                                   $options = array(
                                       'table' => USER_LOYALTY,
                                       'data' => array('user_scane' => $user_scane,
                                                       'status' => $status
                                                       ),
                                       'where' => array('id' => $loyalty->user_loyalty_id)
                                   );
                                   $this->Common_model->customUpdate($options);

                                   $return['status'] = 1;
                                   $return['response'] = $loyalty; 
                                   $return['message'] = $this->lang->line('loyalty_scane_success'); 
                            }else{
                                  $return['status'] = 0;
                                  $return['message'] = $this->lang->line('loyalty_scane_time_restriction').' '.$open; 
                            }
                            
                        }else{
                           $return['status'] = 0;
                           $return['message'] = $this->lang->line('loyalty_scane_complete');    
                        }
                }else{
                        $return['status'] = 0;
                        $return['message'] = $this->lang->line('loyalty_scane_expired_loyalty');   
                }
                }else{
                   $return['status'] = 0;
                   $return['message'] = $this->lang->line('loyalty_scane_start_loyalty').' '.date('d F Y',strtotime($loyalty->start_date));    
                }

            }else{
               $return['status'] = 0;
               $return['message'] = $this->lang->line('loyalty_scane_qr_failed');  
            }
        }
        $this->response($return);
    }

}

/* End of file Offer.php */
/* Location: ./application/controllers/api/v1/Offer.php */
?>