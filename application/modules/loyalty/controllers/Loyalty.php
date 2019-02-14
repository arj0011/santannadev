<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Loyalty extends Common_Controller {

    public $data = array();
    public $file_data = "";
    public $_table = LOYALTY;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['url'] = base_url().'loyalty';
        $this->data['pageTitle'] = lang('loyalty');
        $this->data['parent'] = "Loyalty";
        $user_id = $this->uri->segment(3);
     if(!empty($user_id)){

        $option = array(
                'table' => LOYALTY.' as loyalty',
                'select' => 'loyalty.id,loyalty.title_en as title,loyalty.description_en as description,loyalty.qr_code,loyalty.no_of_scane,loyalty.start_date,loyalty.end_date,loyalty.qr_image,'.'user_loyalty.user_id,'.'user.name',
                 'join' => array(array(USER_LOYALTY.' as user_loyalty', 'user_loyalty.loyalty_id=loyalty.id','left'),
                       array(USERS.' as user','user.id=user_loyalty.user_id','left')),
                'where' => array('user_loyalty.user_id'=>$user_id),
                'order' => array('loyalty.id' => 'desc')
               
              );

        if (getDefaultLanguage() == "el") {
            $option['select'] = 'loyalty.id,loyalty.title_en as title,loyalty.description_en as description,loyalty.qr_code,loyalty.no_of_sc ane,loyalty.start_date,loyalty.end_date,loyalty.qr_image,'.'user_loyalty.user_id,'.'user.name';
        }
       
       
        $this->data['list'] = $this->Common_model->customGet($option);
      }else{
          $option = array('table' => $this->_table,
            'select' => 'id,title_en as title,description_en as description,qr_code,no_of_scane,start_date,end_date,qr_image',
           
            'order' => array('id' => 'desc')
           
        );

        if (getDefaultLanguage() == "el") {
            $option['select'] = 'id,title_el as title,description_el as description,qr_code,no_of_scane,start_date,end_date,qr_image';
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
        $this->data['title'] = lang("add_loyalty");

     $today_date = date('Y-m-d');

         $options = array(
                'table' => USER_LOYALTY.' as user_loyalty',
                'select' => 'user_loyalty.user_id',
                 'join' => array(LOYALTY.' as loyalty'=> 'loyalty.id=user_loyalty.loyalty_id'),
                       // array(USER_SCANE_HISTORY.' as user_scan','user_scan.user_loyalty_id=user_loyalty.id','left')),
               'where' => array('user_loyalty.status' => 1,
                                 'loyalty.end_date >= '=>$today_date,
                                 )
              );

         $users = $this->Common_model->customGet($options);
         $user_ids = array();
            if(!empty($users))
            {
                foreach ($users as $key => $user) {
                    array_push($user_ids, $user->user_id);
                }
            
         
         $id =implode(',',$user_ids);

         $this->data['results'] = $this->Common_model->customQuery("SELECT `id`,CONCAT(name,' ', '(', email,')') as name FROM `users`  WHERE `id` NOT IN ($id)");
        
      }else{
         $this->data['results'] = $this->Common_model->customQuery("SELECT `id`,CONCAT(name,' ', '(', email,')') as name FROM `users`");
      }
        $this->load->view('add', $this->data);
    }

    /**
     * @method loyalty_add
     * @description add dynamic rows
     * @return array
     */
    public function loyalty_add() {
        $this->form_validation->set_rules('title_en', lang('title_en'), 'required|trim');
        $this->form_validation->set_rules('title_el', lang('title_el'), 'required|trim');
        $this->form_validation->set_rules('start_date', lang('start_date'), 'required|trim');
        $this->form_validation->set_rules('end_date', lang('end_date'), 'required|trim');
        $this->form_validation->set_rules('no_of_scane', lang('no_of_scane'), 'required|trim');
        $this->form_validation->set_rules('qr_code', lang('qr_code'), 'required|trim');
        $this->form_validation->set_rules('description_en', lang('description_en'), 'required|trim');
        $this->form_validation->set_rules('description_el', lang('description_el'), 'required|trim');
        

        if ($this->form_validation->run() == true) {
            
               $qr_code = $this->input->post('qr_code');
               $qrName = $this->input->post('title_en');

               $result = $this->generate_qr($qr_code,$qrName);
              
               $start_date = str_replace('/', '-', $this->input->post('start_date'));
               $end_date = str_replace('/', '-', $this->input->post('end_date'));

                $options_data = array(
                   
                    'title_en' => $this->input->post('title_en'),
                    'title_el' => $this->input->post('title_el'),
                    'description_en' => $this->input->post('description_en'),
                    'description_el' => $this->input->post('description_el'),
                    'start_date' => date('Y-m-d',strtotime($start_date)),
                    'end_date' => date('Y-m-d',strtotime($end_date)),
                    'no_of_scane' => $this->input->post('no_of_scane'),
                    'qr_code' => $this->input->post('qr_code'),
                    'qr_image' => $result,
                    'create_date' => datetime(),
                    'is_active' => 1
                );
                 
                $option = array('table' => $this->_table, 
                                'data' => $options_data
                                );
                $last_id = $this->Common_model->customInsert($option);
                
                
                 $user_list=$this->input->post('user_id');
                 $scane=$this->input->post('no_of_scane');

                 if(!empty($user_list)){

                     /* Insert Notification Request */  
                     $notification_arr = array(
                                            'message' => 'Admin has added a new loyalty offer',
                                            'title' => 'Loyalty Offer',
                                            'type_id' => $last_id,
                                            'user_ids' => serialize($user_list),
                                            'notification_type' => 'LOYALTY',
                                            'added_date' => datetime()
                                        );
                     $lid = $this->Common_model->insertData(ADMIN_NOTIFICATION,$notification_arr);

                     /* Insert Loyalty Offers & Notifications */
                     $loyalty_offers     = array();
                     $user_notifications = array();
                     for($i=0;$i<count($user_list);$i++)
                     {
                        
                        $option = array(
                                'loyalty_id'=>$last_id,
                                'loyalty_scane'=>$scane,
                                'user_id'=>$user_list[$i],
                                'create_date'=>datetime(),
                                'user_scane' => 0,
                                'status' =>1
                            );
                        array_push($loyalty_offers, $option);

                        $insertArray = array(
                            'type_id' => $last_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $user_list[$i],
                            'notification_type' => 'LOYALTY',
                            'title' => 'Loyalty Offer',
                            'notification_parent_id' => $lid,
                            'message_en' => 'Admin has added a new loyalty offer',
                            'is_read' => 0,
                            'is_send' => 0,
                            'sent_time' => date('Y-m-d H:i:s'),
                        );
                        array_push($user_notifications, $insertArray);
                     }

                     if(!empty($loyalty_offers)){
                        $this->Common_model->insertBulkData(USER_LOYALTY,$loyalty_offers);
                     }
                     if(!empty($user_notifications)){
                        $this->Common_model->insertBulkData(USER_NOTIFICATION,$user_notifications);
                     }
                    
                     foreach($user_list as $user){
                            $data_add = array(
                                            'type_id' => $last_id,
                                            'user_id' => $user,
                                            'type' => 'LOYALTY',
                                            'is_sent' => 0
                                        );
                            $this->Common_model->insertData(EMAIL_CRON,$data_add);
                     }
                     
                 }
                if($last_id){
                    $response = array('status' => 1, 'message' => lang('loyalty_success'), 'url' => base_url('loyalty'));
                } else {
                    $response = array('status' => 0, 'message' => lang('loyalty_failed'));
                }
              
            
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method loyalty_edit
     * @description edit dynamic rows
     * @return array
     */
    public function loyalty_edit() {
        $this->data['title'] = lang("edit_loyalty");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {

            $option1 = array(
                'table' => $this->_table,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option1);
            
            $option = array(
                'table' => USER_LOYALTY,
                'where' => array('loyalty_id' => $id,'user_scane >' => 0),
                'single' => true
            );
            if($this->Common_model->customGet($option)){
                $this->data['is_edit'] = 1;
            }else{
               $this->data['is_edit'] = 0;
            }
             
            
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('loyalty');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('loyalty');
        }
    }

    /**
     * @method loyalty_update
     * @description update dynamic rows
     * @return array
     */
    public function loyalty_update() {

        $this->form_validation->set_rules('title_en', lang('title_en'), 'required|trim');
        $this->form_validation->set_rules('title_el', lang('title_el'), 'required|trim');
        $this->form_validation->set_rules('start_date', lang('start_date'), 'required|trim');
        $this->form_validation->set_rules('end_date', lang('end_date'), 'required|trim');
        $this->form_validation->set_rules('no_of_scane', lang('no_of_scane'), 'required|trim');
        $this->form_validation->set_rules('qr_code', lang('qr_code'), 'required|trim');
        $this->form_validation->set_rules('description_en', lang('description_en'), 'required|trim');
        $this->form_validation->set_rules('description_el', lang('description_el'), 'required|trim');

        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:

               //$qr_code = $this->input->post('qr_code');
               //$qrName = $this->input->post('title_en');

             //  $result = $this->generate_qr($qr_code,$qrName);
            
                 
               $start_date = str_replace('/', '-', $this->input->post('start_date'));
               $end_date = str_replace('/', '-', $this->input->post('end_date'));
        
               $options_data = array(
                   
                    'title_en' => $this->input->post('title_en'),
                    'title_el' => $this->input->post('title_el'),
                    'description_en' => $this->input->post('description_en'),
                    'description_el' => $this->input->post('description_el'),
                    'start_date' => date('Y-m-d',strtotime($start_date)),
                    'end_date' => date('Y-m-d',strtotime($end_date)),
                    'no_of_scane' => $this->input->post('no_of_scane'),
                    'qr_code' => $this->input->post('qr_code'),
                    //'qr_image' => $result,
                    
             );
            $option = array(
                'table' => $this->_table,
                'data' => $options_data,
                'where' => array('id' => $where_id)
            );
            $update = $this->Common_model->customUpdate($option);
            $response = array('status' => 1, 'message' => lang('loyalty_success_update'), 'url' => base_url('loyalty'));

         
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
    
    public function testNotification(){
        $data_array = array
            (
            'title' => 'Loyalty Offer',
            'body' => 'You have add new loyalty offer',
            'type' => 'loyalty',
            'type_id' => 1,
            'badge' => 1,
            'sound' => 1
        );
        $device = $this->getDevice(16);
        $device_token = "fNiD13ZTeGE:APA91bEFUvodcYEL4MPUiAsD_efpH-vPgS62ZOYbMkOm1q1X63xMl84cu5u7EQ808o_WzCkapEhbEoMsTH3lWdicLt3qvnf89iaRKJP4U7DmDt2IRDcie56cUPeT4B9tuNzg-3r0I9";
        $device_type  = $device->device_type;
        $insertArray = array(
            'type_id' => 1,
            'sender_id' => 1,
            'reciever_id' => 16,
            'notification_type' => 'loyalty',
            'title' => 'Loyalty Offer',
            'message_en' => 'You have add new loyalty offer',
            'is_read' => 0,
            'sent_time' => date('Y-m-d H:i:s'),
            'is_send' => 1
        );
        //$this->insertNotification($insertArray);

        if($device_type == "ANDROID"){
           echo $this->androidFcm($data_array,$device_token); 
        }
    }


    public function scan_history(){
       $this->data['parent'] = "Loyalty";
        $loyalty_id = $this->uri->segment(3);

         $options = array(
                'table' => USER_LOYALTY.' as user_loyalty',
                'select' => 'user_scan.id as user_scan_id,user_scan.scane_date,user_scan.scane_time,'
                . 'user_loyalty.user_scane,user_loyalty.loyalty_scane,user_loyalty.user_id,'.'user.name',
                 'join' => array(array(USER_SCANE_HISTORY.' as user_scan','user_scan.loyalty_id=user_loyalty.id','left'),
                    array(USERS.' as user'=>'user.id=user_loyalty.user_id')),
                'where' => array('user_loyalty.loyalty_id' => $loyalty_id
                                 ),
              
               
            );

        
          $this->data['list'] = $this->Common_model->customGet($options);
         

          $this->template->load_view('default', 'scan_history', $this->data, 'inner_script');
           
    }

}
