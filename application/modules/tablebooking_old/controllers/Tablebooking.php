<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tablebooking extends Common_Controller {
    public $data = array();
    public $file_data = "";

    public function __construct() {
        parent::__construct();
    }
    public function index($id='') {
        $this->data['parent'] = "manage booking";
        
        $booking_data   = array();
        $bookdata       = array();
        $bdata          = array();
        $completeData   = array();
        
        $tableData      = array();
        $bookData       = array();
        $sql_agent      = '';
        $agentID        = '';
        
        /*Booking Data*/
        if(isset($_GET['floor'])){
            $flootID = $_GET['floor'];

            $sql_1 = "SELECT mw_tables.seats,mw_tables.id AS tableId,mw_tables.name AS tableName,mw_rooms.id AS roomId,mw_rooms.name AS roomName
                    FROM mw_tables
                    JOIN mw_rooms ON mw_rooms.id = mw_tables.roomId
                    WHERE mw_rooms.id=$flootID";
            $query_1    = $this->db->query($sql_1);
            $tableData  = $query_1->result_array();
            if(!empty($tableData)):
                foreach($tableData as $tab):
                    if(isset($_GET['date'])):
                        $date = date('Y-m-d',strtotime($_GET['date']));
                    else:
                        $date = date('Y-m-d');
                    endif;
                    if(isset($_GET['agent'])):
                        $agentID = $_GET['agent'];
                        $sql_agent = " AND b.agent_id = $agentID";
                    endif;

                    $tableID = $tab['tableId'];
                    $sql_2 = "SELECT b.id,b.name,b.time_from,b.time_to 
                              FROM mw_booking AS b
                              JOIN  mw_booking_tables AS bt ON bt.booking_id = b.id
                              WHERE booking_date = '".$date."' AND bt.table_id = '".$tableID."' $sql_agent";
                    $query_2 = $this->db->query($sql_2);
                    $bookData = $query_2->result_array();
                    if(!empty($bookData)){
                        foreach($bookData as $b):
                           $b['tableName'] =  $tab['tableName'];
                           $booking_data[] = $b;                       
                        endforeach;
                    }else{
                        $b['tableName'] =  $tab['tableName'];
                        $b['name'] =  '';
                        $b['time_from'] =  '00:00';
                        $b['time_to'] =  '00:00';
                        $booking_data[] = $b;
                    }
                endforeach;
            endif; 
            /*End*/

        }       
        
        $this->data['finaldata'] = $booking_data;
       
        /*Floor Plan*/
        if(isset($_GET['floorplan'])):

        $this->db->select('t.* , s.image , s.canrotate, r.name AS roomName, r.roomHeight,r.roomWidth,r.image as roomImage');
        $this->db->from('mw_tables as t');
        $this->db->join('mw_shapes as s' , 't.type=s.id');
        $this->db->join('mw_rooms as r' , 't.roomId=r.id');
        $this->db->where('r.id',$_GET['floorplan']);
//        if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'agent'):
//            $this->db->where('r.agent',$this->session->userdata('id'));
//        endif;
        $columnArr = array();
        $floordata = $this->db->get()->result();
        foreach($floordata as $fd){
            $columnArr[$fd->roomName][] = $fd;
        }
        $this->data['tables'] = $columnArr;
        endif;
        /*End*/


        /*Add Booking with Agent*/
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
        /*End*/

        /*Add Bookind*/
        $this->db->select('t.id AS table_id, t.type, t.seats, t.priority, t.top, t.left, t.rotation,t.roomId, t.name AS tableName, s.image AS shapeImage,s.canrotate, r.name AS roomName');     
        $this->db->from('mw_tables as t');
        $this->db->join('mw_shapes as s' , 't.type=s.id');
        $this->db->join('mw_rooms as r' , 't.roomId=r.id');

        $seatdata = $this->db->get()->result();
        $seatArray = array();
        foreach($seatdata as $sd){
            $seatArray[$sd->roomName][] = $sd;
        }
        $this->data['seatArr'] = $seatArray;
        if($id == ''){
            $this->data['requestData'] = array();  
        }else{
            $id = decoding($id);
            $this->data['requestData'] = $this->Common_model->getSingleRecordById('mw_booking',array('id'=>$id));
            $reqseats = $this->data['requestData']['no_of_persons'];

        }
        /*End*/
        
        if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'agent'):
            $this->db->where('agent',$this->session->userdata('id'));
        endif;
        $this->data['allfloors'] = $this->db->get('mw_rooms')->result_array();
        if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'agent'):
            $this->db->where('id',$this->session->userdata('id'));
        endif;
        $this->data['allagents'] = $this->db->get(AGENTS)->result_array();
        $this->template->load_view('default', 'chart', $this->data, 'inner_script');
    }

    function open_model() {
        $booking_data = array();
        $id = decoding($this->input->post('id'));
        $this->data['title'] = lang('add_booking');
        $this->db->select('b.*,GROUP_CONCAT(t.name) AS tableName,r.name as roomName,a.full_name AS agentName');
        $this->db->from('mw_booking as b');
        $this->db->join('mw_rooms as r' , 'b.floor_id=r.id');
        $this->db->join('agents as a' , 'b.agent_id=a.id');
        $this->db->join('mw_booking_tables as bt' , 'b.id=bt.booking_id');
        $this->db->join('mw_tables as t' , 'bt.table_id=t.id');
        $this->db->where('b.id',$id);
        $this->db->group_by('bt.booking_id');
        $this->data['booking_data'] = $this->db->get()->row_array();
        $this->load->view('add', $this->data);
    }


    public function booking_add(){
        $this->data['parent'] = lang('booking_details');
        $this->form_validation->set_rules('booking_date', 'Booking date', 'required|trim|xss_clean');
        $this->form_validation->set_rules('time_from', 'Start time', 'required|trim|xss_clean');
        $this->form_validation->set_rules('time_to', 'End time', 'required|trim|xss_clean');
        $this->form_validation->set_rules('name', 'Full name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim|xss_clean');
        $this->form_validation->set_rules('no_of_persons', 'No. of Person', 'required|trim|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'required|trim|xss_clean');
        $this->form_validation->set_rules('tables[]', 'Table', 'required|trim|xss_clean');
        if ($this->form_validation->run() == true) {
            $bookingInsert = array();
            $bookingArr     = array();
            $requestData    = array();
            $userData       = array();
            $users_device   = array();
            $temptable      = array();
            $tablebookingArr= array();
            $where_id       = '';
            $tablestr       = '';
            $booking_id     = '';
            $email          = $this->input->post('email');
            $full_name      = $this->input->post('name');
            $start_time     = $this->input->post('time_from');
            $end_time       = $this->input->post('time_to');
            $values         = $this->input->post('status');
            $booking_date   = date('Y-m-d',strtotime($this->input->post('booking_date')));
            

            foreach($this->input->post('tables') as $table):
                if($this->input->post('requestId') != ''):
                    $where_id = decoding($this->input->post('requestId'));
                    $requestData = $this->Common_model->getSingleRecordById('mw_booking',array('id'=>$where_id));
                    if(!empty($requestData)):
                        $bookingInsert['user_id'] = $requestData['user_id'];  
                    endif;                    
                endif;
                
                $count = $this->Common_model->tableAvailableCount($booking_date,$start_time,$end_time,$table,$where_id);
                if($count == 0):
                    $temptable[] = $table;
                endif; 
                $count = 0;   
            endforeach;
            if(!empty($temptable)):
                if($this->input->post('user_id')):
                    $bookingInsert['user_id'] = $this->input->post('user_id');
                endif;
                if($this->input->post('agent_id')):
                    $bookingInsert['agent_id'] = $this->input->post('agent_id');
                endif;
                $bookingInsert['floor_id'] = $this->input->post('floor');
                $bookingInsert['booking_date'] = $booking_date;
                $bookingInsert['time_from'] = $this->input->post('time_from');
                $bookingInsert['time_to'] = $this->input->post('time_to');
                $bookingInsert['no_of_persons'] = $this->input->post('no_of_persons');
                $bookingInsert['name'] = $this->input->post('name');
                $bookingInsert['mobile'] = $this->input->post('mobile');
                $bookingInsert['email'] = $this->input->post('email');
                $bookingInsert['status'] = $this->input->post('status');
                $bookingInsert['comment'] = $this->input->post('comment');
                $bookingInsert['created'] = date('Y-m-d H:i:s');
                if($this->input->post('requestId') != ''){
                    $option = array(
                    'table' => 'mw_booking',
                    'data' => $bookingInsert,
                    'where' => array('id'=>decoding($this->input->post('requestId')))
                );
                $update = $this->Common_model->customUpdate($option);
                   $response = array('status' => 1, 'message' => 'Booking Updated.', 'url' => base_url('tablebooking/view_booking')); 
                }else{
                    $booking_id = $this->Common_model->insertData('mw_booking',$bookingInsert);
                    if($booking_id):
                        $response = array('status' => 1, 'message' => 'Booking added.', 'url' => base_url('tablebooking/view_booking'));
                    endif;
                }
                if($booking_id != ''):
                    $this->insert_tables($temptable,$booking_id);    
                endif;
                if($this->input->post('requestId') != ''):
                    $opt = array(
                        'table' => 'mw_booking_tables',
                        'where' => array('booking_id'=>decoding($this->input->post('requestId')))
                    );
                    $this->Common_model->customDelete($opt);
                    $this->insert_tables($temptable,decoding($this->input->post('requestId')));
                endif;
            endif;
            
            $from = FROM_EMAIL;
            $subject = "Booking status";
            $title = "Booking status";
            $status_message = "";
        
            if($this->input->post('requestId') || $this->input->post('user_id')):
                if($this->input->post('requestId')):
                    $sql = "SELECT b.booking_date,b.email,b.name as full_name,u.badges,u.id,u.device_id,u.device_type
                        FROM mw_booking AS b
                        JOIN users AS u ON u.id = b.user_id
                        WHERE b.id='".decoding($this->input->post('requestId'))."'";
                        $query = $this->db->query($sql);        
                        $users_device = $query->row_array();        
                endif;
            
                if($this->input->post('user_id')):
                    $users_device = $this->Common_model->getSingleRecordById('users',array('id'=>$this->input->post('user_id'))); 
                endif;    
            endif;    
            
            if (!empty($users_device) && $this->input->post('requestId')) {
                $email =  $users_device['email'];
                $full_name =  (isset($users_device['full_name'])) ? $users_device['full_name'] : "";
                $booking_date = $users_device['booking_date'];
            }else if(!empty($users_device) && $this->input->post('user_id')){
                $email = $this->input->post('email');
                $full_name = $this->input->post('name');
            }

            $tableNameData = array();
            $tableName = '';
            if(!empty($temptable)):
                foreach($temptable as $t):
                   $tableNameData = $this->Common_model->getSingleRecordById('mw_tables',array('id'=>$t));
                    $tableName .= $tableNameData['name'].',';
                endforeach;
            endif;
            $tableName = rtrim($tableName,",");
            

            /*Send Email to User*/
            if ($values == 1) {
                
                $status_message = "Your booking has been Confirmed on $tableName Date ".date('d F Y', strtotime($booking_date))." Time ".date('h:i:s A', strtotime($booking_date)).".";
                $data['content'] = "Congratulation! Admin has been Confirmed your booking.";
                $data['user'] = ucwords($full_name);
                $message = $this->load->view('email_template',$data,true);

                /* send mail */
                send_mail($message, $subject, $email,$from,$title);


            } else if ($values == 2) {
                $status_message = "Your booking has been Cancelled for Date".date('d F Y', strtotime($booking_date))." Time ".date('h:i:s A', strtotime($booking_date)).".";

                $data['content'] = "Your booking is Pending.";
                $data['user'] = ucwords($full_name);

                $message = $this->load->view('email_template',$data,true);

                /* send mail */
                send_mail($message, $subject, $email,$from,$title);

            } else if ($values == 3) {
                $status_message = "Admin has been Cancelled your booking on " . date('d F Y h:i:s A', strtotime($booking_date));

                $data['content'] = "Admin has been Cancelled your booking.";
                $data['user'] = ucwords($full_name);

                $message = $this->load->view('email_template',$data,true);

                /* send mail */
                send_mail($message, $subject, $email,$from,$title);
            }
            /*end*/

            /*Send Push Notification to User*/
            if (!empty($users_device)):
                if($this->input->post('requestId') != ''):
                    $where_id = decoding($this->input->post('requestId'));
                endif;
                if($booking_id != ''):
                    $where_id = $booking_id;
                endif;

                if ($users_device['device_type'] == 'ANDROID') {

                    $user_badges = $users_device['badges'] + 1;
                    $data_array = array(
                        'title' => "Booking Status",
                        'body' => $status_message,
                        'type' => 'BOOKING',
                        'type_id' => $where_id,
                        'badges' => $user_badges,
                        );
                    $status = send_android_notification($data_array, $users_device['device_id'], $user_badges, $users_device['id']);
                    if ($status) {
                        $user_notifications = array(
                            'type_id' => $where_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $users_device['id'],
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

                if ($users_device['device_type'] == 'IOS') {
                    $user_badges = $users_device['badges'] + 1;
                    $params = array(
                        'title' => "Booking Status",
                        'type' => "Booking",
                        'type_id' => $where_id
                        );
                    $status = send_ios_notification($users_device['device_id'], $status_message, $params, $user_badges, $users_device['id']);
                    if ($status) {
                        $user_notifications = array(
                            'type_id' => $where_id,
                            'sender_id' => ADMIN_ID,
                            'reciever_id' => $users_device['id'],
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
                endif;
                /*end*/
        } else {
            $requireds = strip_tags($this->form_validation->error_string());
            $messages = explode("\n", trim($requireds, "\n"));
            $response = array('status' => 0, 'message' => $messages);
            
            //$messages = (validation_errors()) ? validation_errors() : '';
            //$response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);    
    }

    public function insert_tables($tbaleArr,$booking_id){
        foreach($tbaleArr as $tbl):
            $tablebookingArr[] = array('table_id'=>$tbl,'booking_id'=>$booking_id);
        endforeach;
        $this->Common_model->insertBulkData('mw_booking_tables',$tablebookingArr);
    }


    public function gettablebyfloor(){
        if(!empty($this->input->post('floor_id'))){
        $tabledata = array();
        $this->db->select('t.id AS table_id, t.type, t.seats, t.priority, t.top, t.left, t.rotation,t.roomId, t.name AS tableName, s.image AS shapeImage,s.canrotate, r.name AS roomName');     
        $this->db->from('mw_tables as t');
        $this->db->join('mw_shapes as s' , 't.type=s.id');
        $this->db->join('mw_rooms as r' , 't.roomId=r.id');
        $this->db->where(array('t.roomId'=>$this->input->post('floor_id')));
        $tabledata = $this->db->get()->result();
        ?>        
        <label class="tblcheck_box"></label>
        <?php
        echo '<h4>'.(!empty($tabledata) && isset($tabledata[0]->roomName)) ? $tabledata[0]->roomName : "".'</h4>';
        echo '<ul>';
        if(!empty($tabledata)){
        foreach($tabledata as $tabls):
            ?>
        <li>
        <?php echo (isset($tabls->tableName)) ? $tabls->tableName : "";?>
            <div class="custom_checkbox" style="display:inline-block;">
                <input id="tabel_check_id_<?php echo $tabls->table_id;?>" type="checkbox" data-pers="<?php echo $tabls->seats;?>" class="form-control chk_table_id" name="tables[]" value="<?php echo $tabls->table_id;?>" />   
                <label for="tabel_check_id_<?php echo $tabls->table_id;?>" class="chktablecls" title="<?php echo $tabls->seats;?>pers.">
                    <img src="<?php echo base_url()?>uploads/tables/<?php echo $tabls->shapeImage;?>" class="check_img">
                </label>
            </div>
        <?php echo $tabls->seats.' pers.';?>    
        </li>
        <?php endforeach;}?>
        </ul>
        <?php   
    }
    }


    /*View Booking*/
    public function view_booking() {
        $this->data['parent'] = "View Booking";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $user_id = $this->uri->segment(3);
        $where = '';
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
        $where = array();
        if($this->session->userdata('role') == 'agent'){
            /*$option = array('table' => 'mw_rooms',
               'select' => 'id',
               'where' => array('agent' => $this->session->userdata('id')),
               'single' => true);  
            $floor = $this->Common_model->customGet($option);*/

            /*$option = array('table' => 'mw_booking',
               'select' => 'id',
               'where' => array('agent_id' => $this->session->userdata('id')),
               'single' => true);  
            $floor = $this->Common_model->customGet($option);*/
            /*if(!empty($floor)):
                $where = array('floor_id' => $floor->id);
            endif;*/ 
            $where = array('agent_id' => $this->session->userdata('id'));
        }  
        //$floor = $this->Common_model->customGet($option);
        //$where['user_id'] = $user_id;
        /*if (empty($user_id)) {
            $this->data['list'] = $this->Common_model->booking($start_date, $end_date, 'mw_booking',$where);
        } else {
            $this->data['list'] = $this->Common_model->booking($start_date, $end_date, 'mw_booking', $where);
        }*/
        $this->data['list'] = $this->Common_model->booking('', '', 'mw_booking',$where);

        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }
    /*End*/

    public function deletebooking(){
        if($this->input->post('id') != ''):
            $id = decoding($this->input->post('id'));
            $option = array(
                        'table' => 'mw_booking',
                        'where' => array('id'=>$id)
                    );
            $this->Common_model->customDelete($option);

            $opt = array(
                        'table' => 'mw_booking_tables',
                        'where' => array('booking_id'=>$id)
                    );
            $this->Common_model->customDelete($opt);
        endif;
        echo 1;
    }
    public function freeTable(){
        if($this->input->post('id') != ''):
            $id = decoding($this->input->post('id'));
            $time = $this->input->post('time');
                $update['time_to'] = $time;
                $option = array(
                    'table' => 'mw_booking',
                    'data' => $update,
                    'where' => array('id'=>$id)
                );
            $update = $this->Common_model->customUpdate($option);
            $result = [];
            $result['success']= '1';
            echo json_encode($result);
        endif;
        
    }




}
?>
