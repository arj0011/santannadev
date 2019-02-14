<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tablebooking extends Common_Controller {

    public $data = array();
    public $file_data = "";

    public function __construct() {
        parent::__construct();
    }

    public function index($id = '') {

        $this->data['url'] = base_url() . 'tablebooking';
        $this->data['pageTitle'] = "Booking";
        $this->data['parent'] = "manage booking";

        $booking_data = array();
        $bookdata = array();
        $bdata = array();
        $completeData = array();

        $tableData = array();
        $bookData = array();
        $sql_agent = '';
        $agentID = '';

        $options = array('table' => COUNTRY, 'select' => 'CONCAT(countries_name," (",countries_isd_code,")") as name,countries_isd_code');
        $this->data['countries'] = $this->Common_model->customGet($options);

        $options = array('table' => SPECIAL_REQUEST, 'select' => '*');
        $this->data['special_request'] = $this->Common_model->customGet($options);
        
        $option = array('table' => 'mw_rooms','single' => true);
        //arjun
        if($this->session->userdata('role') == 'store'){
            $option['where']['store'] = $this->session->userdata('id');
        }
        //end

        $floorsResult = $this->Common_model->customGet($option);

        $floorPlanId = (!empty($floorsResult)) ? $floorsResult->id : 0;
        /* Booking Data */
        $floorPlanId = (!empty($floorsResult)) ? $floorsResult->id : 0;
        if ((isset($_GET['floor']) && !empty($_GET['floor']))) {
            $floorPlanId = $_GET['floor'];
        }
        if ((isset($_GET['floorplan']) && !empty($_GET['floorplan']))) {
            $floorPlanId = $_GET['floorplan'];
        }
        if ($floorPlanId) {
            $bookingPlanDate = date('d/m/Y');
            if (isset($_GET['date']) && !empty($_GET['date'])) {
                $bookingPlanDate = $_GET['date'];
            }

            $sql_1 = "SELECT mw_tables.seats,mw_tables.id AS tableId,mw_tables.name AS tableName,mw_rooms.id AS roomId,mw_rooms.name AS roomName
                    FROM mw_tables
                    JOIN mw_rooms ON mw_rooms.id = mw_tables.roomId
                    WHERE mw_rooms.id=$floorPlanId";
            $query_1 = $this->db->query($sql_1);
            $tableData = $query_1->result_array();
            if (!empty($tableData)):
                $utc_time = strtotime(date('H:i:s'));
                $ist_time = date('H:i:s', strtotime('+330 minutes', $utc_time));
                foreach ($tableData as $tab):
                    $bookingdatessearch = str_replace('/', '-', $bookingPlanDate);
                    $date = date('Y-m-d', strtotime($bookingdatessearch));
                    if (isset($_GET['agent'])):
                        $agentID = $_GET['agent'];
                        $sql_agent = " AND b.agent_id = $agentID";
                    endif;
                    $sel_time = "";
                    if (isset($_GET['from']) && isset($_GET['to'])):
                        $from = $_GET['from'];
                        $to = $_GET['to'];
                        $sel_time = " AND b.time_from >= '" . $from . "' AND b.time_to <= '" . $to . "'";
                    endif;

                    $tableID = $tab['tableId'];
                    /* $sql_2 = "SELECT b.id,b.name,b.time_from,b.time_to 
                      FROM mw_booking AS b
                      JOIN  mw_booking_tables AS bt ON bt.booking_id = b.id
                      WHERE b.time_to > '".$ist_time."' AND booking_date = '".$date."' "
                      . " AND bt.table_id = '".$tableID."' $sql_agent $sel_time"; */
                    $sql_2 = "SELECT b.id,b.name,b.time_from,b.time_to 
                              FROM mw_booking AS b
                              JOIN  mw_booking_tables AS bt ON bt.booking_id = b.id
                              WHERE booking_date = '" . $date . "' "
                            . " AND bt.table_id = '" . $tableID . "' $sql_agent $sel_time";
                    $query_2 = $this->db->query($sql_2);
                    $bookData = $query_2->result_array();
                    if (!empty($bookData)) {
                        foreach ($bookData as $b):
                            $b['tableName'] = $tab['tableName'];
                            $booking_data[] = $b;
                        endforeach;
                    }else {
                        $b['tableName'] = $tab['tableName'];
                        $b['name'] = '';
                        $b['time_from'] = '00:00';
                        $b['time_to'] = '00:00';
                        $booking_data[] = $b;
                    }
                endforeach;
            endif;
            /* End */
        }
        $this->data['finaldata'] = $booking_data;

        /* Floor Plan */




        $this->data['floorPlanId'] = $floorPlanId;
        if ($floorPlanId):
            $this->db->select('t.* , s.image , s.canrotate, r.id AS roomId,r.name AS roomName, r.roomHeight,r.roomWidth,r.image as roomImage');
            $this->db->from('mw_tables as t');
            $this->db->join('mw_shapes as s', 't.type=s.id');
            $this->db->join('mw_rooms as r', 't.roomId=r.id');
            $this->db->where('r.id', $floorPlanId);

            $columnArr = array();
            $validateData = array();
            $floordata = $this->db->get()->result();
            foreach ($floordata as $fd) {
                $utctime = strtotime(date('H:i:s'));
                $isttime = date('H:i:s', strtotime('+330 minutes', $utctime));
                $isttime = strtotime($isttime);
                $prev_time = date("H:i:s", strtotime("+30 minutes", $isttime));
                $curr_time = date("H:i:s", $isttime);
                $sql_3 = "SELECT b.time_from AS StartTime,b.time_to AS EndTime,bt.id,b.name AS personName,b.id AS bookingID
        			  FROM mw_booking AS b
        			  INNER JOIN mw_booking_tables AS bt ON bt.booking_id = b.id
        			  WHERE bt.table_id = $fd->id AND b.booking_date = '" . date('Y-m-d') . "' AND b.floor_id = $fd->roomId AND b.time_from <= '" . $prev_time . "' AND b.time_to >= '" . $curr_time . "'";
                $query_3 = $this->db->query($sql_3);
                $validateData = $query_3->row_array();
                if (!empty($validateData)):
                    $border = '2px solid red !important;}';
                    $StartTime = $validateData['StartTime'];
                    $EndTime = $validateData['EndTime'];
                    $personName = $validateData['personName'];
                    $bookingID = $validateData['bookingID'];
                else:
                    $border = '2px solid #19db45 !important;';
                    $StartTime = '';
                    $EndTime = '';
                    $personName = '';
                    $bookingID = '';
                endif;
                $fd->border = $border;
                $fd->StartTime = $StartTime;
                $fd->EndTime = $EndTime;
                $fd->personName = $personName;
                $fd->bookingID = $bookingID;
                $columnArr[$fd->roomName][] = $fd;
            }
            $this->data['tables'] = $columnArr;
        endif;
        /* End */

        /* Add Booking with Agent */
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
        /* End */

        /* Add Bookind */
        $this->db->select('t.id AS table_id, t.type, t.seats, t.priority, t.top, t.left, t.rotation,t.roomId, t.name AS tableName, s.image AS shapeImage,s.canrotate, r.name AS roomName');
        $this->db->from('mw_tables as t');
        $this->db->join('mw_shapes as s', 't.type=s.id');
        $this->db->join('mw_rooms as r', 't.roomId=r.id');

        $seatdata = $this->db->get()->result();
        $seatArray = array();
        foreach ($seatdata as $sd) {
            $seatArray[$sd->roomName][] = $sd;
        }
        $this->data['seatArr'] = $seatArray;
        if ($id == '') {
            $this->data['requestData'] = array();
        } else {
            $id = decoding($id);
            $this->data['requestData'] = $this->Common_model->getSingleRecordById('mw_booking', array('id' => $id));
            $reqseats = $this->data['requestData']['no_of_persons'];
            $userID = $this->data['requestData']['user_id'];
            $point = $this->Common_model->get_user_wallet_point($userID);
            $this->data['user_wallet_point'] = $point;

        }
        /* End */

        if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'agent'):
            $this->db->where('agent', $this->session->userdata('id'));
        endif;
        
        // $this->data['allfloors'] = $this->db->get('mw_rooms')->result_array();
        $this->data['allfloors'] = $this->Common_model->floorByRole();

        if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'agent'):
            $this->db->where('id', $this->session->userdata('id'));
        endif;
        $this->data['allagents'] = $this->db->get(AGENTS)->result_array();
        $this->template->load_view('default', 'chart', $this->data, 'inner_script');
    }

    function open_model() {
        $booking_data = array();
        $id = decoding($this->input->post('id'));

        $this->data['title'] = lang('view_booking');
        $this->db->select('b.*,GROUP_CONCAT(t.name) AS tableName,r.name as roomName,a.full_name AS agentName');
        $this->db->from('mw_booking as b');
        $this->db->join('mw_rooms as r', 'b.floor_id=r.id', 'left');
        $this->db->join('agents as a', 'b.agent_id=a.id', 'left');
        $this->db->join('mw_booking_tables as bt', 'b.id=bt.booking_id', 'left');
        $this->db->join('mw_tables as t', 'bt.table_id=t.id', 'left');
        $this->db->where('b.id', $id);
        $this->db->group_by('bt.booking_id');
        $this->data['booking_data'] = $this->db->get()->row_array();
        $this->load->view('add', $this->data);
    }

    function open_payment_model() {
        $this->data['title'] = lang('payment');
        $booking_data = array();
        $point = 0;
        $money = 0;
        $user_id = '';
        $booking_id = $this->input->post('id');
        $bookingID = decoding($booking_id);
        $userdata = $this->db->get_where('mw_booking',array('id'=>$bookingID))->row_array();
        if(!empty($userdata)){
            $user_id = $userdata['user_id'];
            $floor_id = encoding($userdata['floor_id']);
            $point = $this->Common_model->get_user_wallet_point($user_id);
            $user_id = encoding($user_id);
            
            if($point > 0){
                $money = $this->Common_model->PointToMoney($point);
            }    
        }
                
        $this->data['money'] = $money;
        $this->data['point'] = $point;
        $this->data['id'] = $booking_id;
        $this->data['user_id'] = $user_id;
        $this->data['floor_id'] = $floor_id;
        $this->load->view('payment', $this->data);
    }


    public function payment(){
        header('Content-type: application/json');
        $this->form_validation->set_rules('total_billing_amount', 'Total Billing Amount', 'required|numeric|trim|xss_clean');
        // $this->form_validation->set_rules('redeem_point', 'Redeem Point', 'required|numeric|trim|xss_clean');
        $this->form_validation->set_rules('payment', 'New Payble Amount', 'required|numeric|trim|xss_clean');            
        if ($this->form_validation->run() == true){
            $booking_id = decoding($this->input->post('booking_id'));
            $user_id = decoding($this->input->post('user_id'));
            $floor_id = decoding($this->input->post('floor_id'));
            $store_id = '';
            if($floor_id != ''){
                $floorData = $this->db->get_where('mw_rooms',array('id'=>$floor_id))->row_array();
                if(!empty($floorData)):
                    $store_id = $floorData['store'];
                    endif;
            }
            if((isset($_POST['total_billing_amount']) && $_POST['total_billing_amount'] != 0 && $_POST['total_billing_amount'] != '') && (isset($_POST['redeem_point']) && $_POST['redeem_point'] != 0 && $_POST['redeem_point'] != '')){
                    $total_billing_amount = $this->input->post('total_billing_amount');
                    $oldpoints = $this->input->post('user_point');        
                    if($oldpoints > 0){
                        if($oldpoints >= $_POST['redeem_point']){
                            $this->Common_model->withdrawalpoint($user_id,$booking_id,$oldpoints,$total_billing_amount,$_POST['redeem_point'],$floor_id);            
                        }
                    }
                                
                }
                //arjun
                 if($this->input->post('payment') != 0 && $this->input->post('payment') != 0.00 && $this->input->post('payment') != ''){
                        $payment = $this->input->post('payment');
                        $total_billing_amount = $this->input->post('total_billing_amount');
                        $updtArr = array('payment'=>$payment);
                        $op = array(
                            'table'=>'mw_booking',
                            'data'=>$updtArr,
                            'where'=>array('id'=>$booking_id)
                        );
                        $update = $this->Common_model->customUpdate($op);
                        if($update){
                            $redeemPoint = 0;
                            if(isset($_POST['redeem_point'])){   
                                $redeemPoint = $this->input->post('redeem_point');
                            }
                            $this->Common_model->creditpoint($user_id,$booking_id,$total_billing_amount,$payment,$floor_id,$redeemPoint,$store_id);    
                        }

                }
                //end
                $response = array('status' => 1, 'message' => 'Booking Updated.', 'url' => base_url('tablebooking/view_booking'));
                log_message('error', "BOOKING UPDATE id :" . $booking_id . " : Date: " . date('Y-m-d'));
        }else{
            $errors = array();
            // Loop through $_POST and get the keys
            foreach ($this->input->post() as $key => $value){
                // Add the error message for this field
                $errors[$key] = form_error($key);
            }
            $response['status'] = 0;
            $response['data']['errors'] = array_filter($errors);
        }
        echo json_encode($response);
    }


    function open_paymentview_model() {
        $this->data['title'] = lang('payment_view');
        $booking_data = array();
        $total_billing_amount = 0;
        $redeem_point = 0;
        $points_worth = 0;
        $actual_payment=0;
        $booking_id = $this->input->post('id');
        $bookingID = decoding($booking_id);
        // $option = array(
        //     'table' => 'mw_booking',
        //     'select'=> 'payment',
        //     'where' => array('id'=> $bookingID,'payment != '=> NULL),
        //     'single'=> true
        // );
        // $bookingArr = $this->Common_model->customGet($option);
        // if(!empty($bookingArr)){
            // $actual_payment = $bookingArr->payment;
            $options = array(
                'table' => USERWALLETHISTORY,
                'select'=> 'total_billing_amount,actual_payment,earn_point,use_point',
                'where' => array('order_id' => $bookingID),
                'single'=>true 
            );
            $historyArr = $this->Common_model->customGet($options);
            if(!empty($historyArr)){
                $total_billing_amount = $historyArr->total_billing_amount;
                $actual_payment = $historyArr->actual_payment;
                $redeem_point = $historyArr->use_point;
                if($redeem_point != 0 && $redeem_point != NULL){
                    $points_worth = $this->Common_model->PointToMoney($redeem_point);
                }
            }
        // }
              
        $this->data['total_billing_amount'] = $total_billing_amount;
        $this->data['actual_payment'] = $actual_payment;
        $this->data['redeem_point'] = $redeem_point;
        $this->data['points_worth'] = $points_worth;
        $this->load->view('paymentview', $this->data);
    }

    public function booking_add() {

        $this->data['parent'] = lang('booking_details');
        $this->form_validation->set_rules('booking_date', 'Booking date', 'required|trim|xss_clean');
        $this->form_validation->set_rules('time_from', 'Start time', 'required|trim|xss_clean');
        $this->form_validation->set_rules('time_to', 'End time', 'required|trim|xss_clean');
        $this->form_validation->set_rules('name', 'Full name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('floor', 'Floor', 'required|trim|xss_clean');
        $this->form_validation->set_rules('no_of_persons', 'No. of Person', 'required|trim|xss_clean');
        $this->form_validation->set_rules('status', 'Status', 'required|trim|xss_clean');
        $this->form_validation->set_rules('tables[]', 'Table', 'required|trim|xss_clean');


        if ($this->form_validation->run() == true) {
            $bookingInsert = array();
            $bookingArr = array();
            $requestData = array();
            $userData = array();
            $users_device = array();
            $temptable = array();
            $tablebookingArr = array();
            $where_id = '';
            $tablestr = '';
            $booking_id = '';
            $email = $this->input->post('email');
            $full_name = $this->input->post('name');
            $start_time = $this->input->post('time_from');
            $end_time = $this->input->post('time_to');
            $values = $this->input->post('status');
            $bookingdates = str_replace('/', '-', $this->input->post('booking_date'));
            $booking_date = date('Y-m-d', strtotime($bookingdates));

            foreach ($this->input->post('tables') as $table):
                if ($this->input->post('requestId') != ''):
                    $where_id = decoding($this->input->post('requestId'));
                    $requestData = $this->Common_model->getSingleRecordById('mw_booking', array('id' => $where_id));
                    if (!empty($requestData)):
                        $bookingInsert['user_id'] = $requestData['user_id'];
                    endif;
                endif;

                $count = $this->Common_model->tableAvailableCount($booking_date, $start_time, $end_time, $table, $where_id);
                if ($count == 0):
                    $temptable[] = $table;
                endif;
                $count = 0;
            endforeach;
            if (!empty($temptable)):
                if ($this->input->post('user_id')):
                    $bookingInsert['user_id'] = $this->input->post('user_id');
                endif;
                if ($this->input->post('agent_id')):
                    $bookingInsert['agent_id'] = $this->input->post('agent_id');
                endif;
                $userId = 0;
                $option = array('table' => USERS,
                    'select' => 'id',
                    'where' => array('email' => $this->input->post('email')),
                    'single' => true
                );
                $u = $this->Common_model->customGet($option);
                if (!empty($u)) {
                    $bookingInsert['user_id'] = $u->id;
                } else {
                    $qr_image = '';
                    $qr_code = $this->input->post('email');
                    $qr_image = $this->generate_qr_user($qr_code);

                    $option = array('table' => USERS,
                        'data' => array(
                            'name' => ucwords($this->input->post('name')),
                            'email' => $this->input->post('email'),
                            'password' => md5(123456),
                            'phone_number' => $this->input->post('mobile'),
                            'created_date' => date('Y-m-d H:i:s'),
                            'is_verified' => 1,
                            'is_social_signup' => 0,
                            'date_of_birth' => '0000-00-00',
                            'qr_image' => $qr_image
                        ),
                    );
                    $bookingInsert['user_id'] = $this->Common_model->customInsert($option);
                }
                $store_id = '';
                if($this->input->post('floor') != ''){
                    $floorData = $this->db->get_where('mw_rooms',array('id'=>$this->input->post('floor')))->row_array();
                    if(!empty($floorData)):
                        $store_id = $floorData['store'];
                        endif;
                }
                $bookingInsert['floor_id'] = $this->input->post('floor');
                $bookingInsert['store_id'] = $store_id;
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
                $bookingInsert['countries_isd_code'] = $this->input->post('countries_isd_code');
                $bookingInsert['referrer'] = $this->input->post('referrer');
                $bookingInsert['special_request_id'] = json_encode($this->input->post('special_request_id'));

                //arjun
                if($this->input->post('payment') != 0 && $this->input->post('payment') != 0.00 && $this->input->post('payment') != ''){
                    $bookingInsert['payment'] = $this->input->post('payment');
                }
                //end

                if ($this->input->post('requestId') != '') {
                    $option = array(
                        'table' => 'mw_booking',
                        'data' => $bookingInsert,
                        'where' => array('id' => decoding($this->input->post('requestId')))
                    );
                    $update = $this->Common_model->customUpdate($option);
                    if((isset($_POST['total_billing_amount']) && $_POST['total_billing_amount'] != 0 && $_POST['total_billing_amount'] != '') && (isset($_POST['redeem_point']) && $_POST['redeem_point'] != 0 && $_POST['redeem_point'] != '') ){
    
                                $oldpoints = $this->input->post('total_point');
                                $total_billing_amount = $this->input->post('total_billing_amount');
                                if($oldpoints > 0){
                                    if($oldpoints >= $_POST['redeem_point']){
                                        $this->Common_model->withdrawalpoint($bookingInsert['user_id'],$booking_id,$oldpoints,$total_billing_amount,$_POST['redeem_point'],$bookingInsert['floor_id']);            
                                    }
                                }
                                
                            }
                    //arjun
                     if($this->input->post('payment') != 0 && $this->input->post('payment') != 0.00 && $this->input->post('payment') != ''){
                            $payment = $this->input->post('payment');
                            $total_billing_amount = $this->input->post('total_billing_amount');
                            $booking_id = decoding($this->input->post('requestId'));
                            $redeemPoint = 0;
                            if(isset($_POST['redeem_point'])){   
                                $redeemPoint = $this->input->post('redeem_point');
                            }
                            $this->Common_model->creditpoint($bookingInsert['user_id'],$booking_id,$total_billing_amount,$payment,$bookingInsert['floor_id'],$redeemPoint,$store_id);

                    }
                    //end
                    $response = array('status' => 1, 'message' => 'Booking Updated.', 'url' => base_url('tablebooking/view_booking'));
                    log_message('error', "BOOKING UPDATE id :" . decoding($this->input->post('requestId')) . " : Date: " . date('Y-m-d'));
                } else {
                    $booking_id = $this->Common_model->insertData('mw_booking', $bookingInsert);
                    if ($booking_id):

                        //arjun
                        if($this->input->post('payment') != 0 && $this->input->post('payment') != 0.00 && $this->input->post('payment') != ''){
                            
                            $payment = $this->input->post('payment');
                            $total_billing_amount = $this->input->post('total_billing_amount');
                            $redeemPoint = 0;
                            if(isset($_POST['redeem_point'])){   
                                $redeemPoint = $this->input->post('redeem_point');
                            }
                            
                            if((isset($total_billing_amount) && $total_billing_amount != 0 && $total_billing_amount != '') && (isset($_POST['redeem_point']) && $_POST['redeem_point'] != 0 && $_POST['redeem_point'] != '') ){
                                
                                $oldpoints = $this->input->post('total_point');
                                
                                if($oldpoints > 0){
                                    if($oldpoints >= $_POST['redeem_point']){
                                        $this->Common_model->withdrawalpoint($bookingInsert['user_id'],$booking_id,$oldpoints,$total_billing_amount,$_POST['redeem_point'],$bookingInsert['floor_id']);            
                                    }
                                }
                                
                            }
                            

                            //Add point of new money
                            $this->Common_model->creditpoint($bookingInsert['user_id'],$booking_id,$total_billing_amount,$payment,$bookingInsert['floor_id'],$redeemPoint,$store_id);

                        }
                        //end

                        $options_notification = array(
                            'table' => 'users_notifications',
                            'data' => array(
                                'sender_id' => $bookingInsert['user_id'],
                                'reciever_id' => $this->session->userdata('id'),
                                'notification_type' => 'ADMIN',
                                'title' => 'New Booking Generate',
                                'message_en' => 'New booking has been on Date ' . date('d/m/Y', strtotime($booking_date)) . " From " . $bookingInsert['time_from'] . " To " . $bookingInsert['time_to'],
                                'is_read' => 0,
                                'sent_time' => date('Y-m-d H:i:s'),
                                'is_send' => 1,
                                'booking_id' => $booking_id,
                                'agent_id' => 0
                            )
                        );
                        $this->Common_model->customInsert($options_notification);
                        $response = array('status' => 1, 'message' => 'Booking added.', 'url' => base_url('tablebooking/view_booking'));
                        log_message('error', "BOOKING CREATE id :" . $booking_id . " : create date:" . date('Y-m-d'));
                    endif;
                }

                if ($booking_id != ''):
                    $this->insert_tables($temptable, $booking_id);
                endif;
                if ($this->input->post('requestId') != ''):
                    $opt = array(
                        'table' => 'mw_booking_tables',
                        'where' => array('booking_id' => decoding($this->input->post('requestId')))
                    );
                    $this->Common_model->customDelete($opt);
                    $this->insert_tables($temptable, decoding($this->input->post('requestId')));
                endif;
            endif;

            $from = FROM_EMAIL;
            $subject = "Booking status";
            $title = "Booking status";
            $status_message = "";

            if ($this->input->post('requestId') || $this->input->post('user_id')):
                if ($this->input->post('requestId')):
                    $sql = "SELECT b.booking_date,b.email,b.name as full_name,u.badges,u.id,u.device_id,u.device_type
                        FROM mw_booking AS b
                        JOIN users AS u ON u.id = b.user_id
                        WHERE b.id='" . decoding($this->input->post('requestId')) . "'";
                    $query = $this->db->query($sql);
                    $users_device = $query->row_array();
                endif;

                if ($this->input->post('user_id')):
                    $users_device = $this->Common_model->getSingleRecordById('users', array('id' => $this->input->post('user_id')));
                endif;
            endif;

            if (!empty($users_device) && $this->input->post('requestId')) {
                $email = $users_device['email'];
                $full_name = (isset($users_device['full_name'])) ? $users_device['full_name'] : "";
                $booking_date = $users_device['booking_date'];
            } else if (!empty($users_device) && $this->input->post('user_id')) {
                $email = $this->input->post('email');
                $full_name = $this->input->post('name');
            }

            $tableNameData = array();
            $tableName = '';
            if (!empty($temptable)):
                foreach ($temptable as $t):
                    $tableNameData = $this->Common_model->getSingleRecordById('mw_tables', array('id' => $t));
                    $tableName .= $tableNameData['name'] . ',';
                endforeach;
            endif;
            $tableName = rtrim($tableName, ",");


            /* Send Email to User */
            if ($values == 1) {

                $status_message = "Your booking has been Confirmed on $tableName Date " . date('d/m/y', strtotime($booking_date)) . " Time " . date('h:i:s A', strtotime($booking_date)) . ".";
                $data['content'] = "Congratulation! Admin has been Confirmed your booking.";
                $data['user'] = ucwords($full_name);
                $message = $this->load->view('email_template', $data, true);

                /* send mail */
                if (!empty($email) && !empty($from)) {
                    // send_mail($message, $subject, $email, $from, $title);
                }
            } else if ($values == 2) {
                $status_message = "Your booking has been Cancelled for Date" . date('d/m/y', strtotime($booking_date)) . " Time " . date('h:i:s A', strtotime($booking_date)) . ".";

                $data['content'] = "Your booking is Pending.";
                $data['user'] = ucwords($full_name);

                $message = $this->load->view('email_template', $data, true);

                /* send mail */
                if (!empty($email) && !empty($from)) {
                    //send_mail($message, $subject, $email, $from, $title);
                }
            } else if ($values == 3) {
                $status_message = "Admin has been Cancelled your booking on " . date('d/m/y h:i:s A', strtotime($booking_date));

                $data['content'] = "Admin has been Cancelled your booking.";
                $data['user'] = ucwords($full_name);

                $message = $this->load->view('email_template', $data, true);

                /* send mail */
                if (!empty($email) && !empty($from)) {
                    //send_mail($message, $subject, $email, $from, $title);
                }
            }
            /* end */

            /* Send Push Notification to User */
            if (!empty($users_device)):
                if ($this->input->post('requestId') != ''):
                    $where_id = decoding($this->input->post('requestId'));
                endif;
                if ($booking_id != ''):
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
            /* end */
        } else {
            //$requireds = strip_tags($this->form_validation->error_string());
            //$messages = explode("\n", trim($requireds, "\n"));
            //$response = array('status' => 0, 'message' => $messages);

            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }

        echo json_encode($response);
    }

    public function insert_tables($tbaleArr, $booking_id) {
        foreach ($tbaleArr as $tbl):
            $tablebookingArr[] = array('table_id' => $tbl, 'booking_id' => $booking_id);
        endforeach;
        $this->Common_model->insertBulkData('mw_booking_tables', $tablebookingArr);
    }

    public function gettablebyfloor() {
        if (!empty($this->input->post('floor_id'))) {
            $floorId = $this->input->post('floor_id');
            $requestBookingId = $this->input->post('requestBookingId');
            $booking = array();
            if (!empty($requestBookingId)) {
                $bookingId = decoding($requestBookingId);
                $option = array('table' => 'mw_booking',
                    'select' => 'id,booking_date',
                    'where' => array('id' => $bookingId),
                    'single' => true
                );
                $booking = $this->Common_model->customGet($option);
            }
            if (!empty($this->input->post('booking_date'))) {
                $bookingDate = $this->input->post('booking_date');
                $bookingDate = str_replace('/', '-', $bookingDate);
                $bookingDate = date('Y-m-d', strtotime($bookingDate));
            } else {
                $bookingDate = date('Y-m-d');
            }
            $tabledata = array();
            $this->db->select('t.id AS table_id, t.type, t.seats, t.priority, t.top, t.left, t.rotation,t.roomId, t.name AS tableName, s.image AS shapeImage,s.canrotate, r.name AS roomName');
            $this->db->from('mw_tables as t');
            $this->db->join('mw_shapes as s', 't.type=s.id');
            $this->db->join('mw_rooms as r', 't.roomId=r.id');
            $this->db->where(array('t.roomId' => $floorId));
            $tabledata = $this->db->get()->result();
            $columnArr = array();
            $validateData = array();
            foreach ($tabledata as $key => $fd) {
                $utctime = strtotime(date('H:i:s'));
                $isttime = date('H:i:s', strtotime('+330 minutes', $utctime));
                $isttime = strtotime($isttime);
                $prev_time = date("H:i:s", strtotime("+30 minutes", $isttime));
                $curr_time = date("H:i:s", $isttime);
                $sql_3 = "SELECT bt.table_id,b.booking_date,b.no_of_persons,b.time_from AS StartTime,b.time_to AS EndTime,bt.id,b.name AS personName,b.id AS bookingID
        			  FROM mw_booking AS b
        			  INNER JOIN mw_booking_tables AS bt ON bt.booking_id = b.id
        			  WHERE bt.table_id = $fd->table_id AND b.floor_id = $fd->roomId AND b.booking_date = '" . $bookingDate . "'";

                $query_3 = $this->db->query($sql_3);
                $validateData = $query_3->row_array();
                if (!empty($validateData)):
                    if (!empty($requestBookingId)):
                        if (!empty($booking)):
                            if ($validateData['bookingID'] == $booking->id):
                                if ($bookingDate != $booking->booking_date):
                                    if (strtotime($validateData['EndTime']) > $utctime):
                                        unset($tabledata[$key]);
                                    endif;
                                endif;
                            else:
                                if (strtotime($validateData['EndTime']) > $utctime):
                                    unset($tabledata[$key]);
                                endif;
                            endif;
                        endif;
                    else:
                        if (strtotime($validateData['EndTime']) > $utctime):
                            unset($tabledata[$key]);
                        endif;
                    endif;
                endif;
            }
            ?>        
            <label class="tblcheck_box"></label>
            <?php
            if (!empty($tabledata)) {
                echo '<h5>' . (!empty($tabledata)) ? (isset($tabledata[0]->roomName)) ? $tabledata[0]->roomName : "" : "" . '</h5>';
                echo "<h5 class='text-success'>We will show only selected section free tables for booking date " . date('d/m/Y', strtotime($bookingDate)) . "</h5>";
                echo '<ul>';
            } else {
                echo "<h5 class='text-danger'>Tables not available For this section</h5>";
            }
            if (!empty($tabledata)) {
                foreach ($tabledata as $tabls):
                    ?>
                    <li>
                        <?php echo (isset($tabls->tableName)) ? $tabls->tableName : ""; ?>
                        <div class="custom_checkbox" style="display:inline-block;">
                            <input id="tabel_check_id_<?php echo $tabls->table_id; ?>" type="checkbox" data-pers="<?php echo $tabls->seats; ?>" class="form-control chk_table_id" name="tables[]" value="<?php echo $tabls->table_id; ?>" />   
                            <label for="tabel_check_id_<?php echo $tabls->table_id; ?>" class="chktablecls" title="<?php echo $tabls->seats; ?>pers.">
                                <img src="<?php echo base_url() ?>uploads/tables/<?php echo $tabls->shapeImage; ?>" class="check_img">
                            </label>
                        </div>
                        <?php echo $tabls->seats . ' pers.'; ?>    
                    </li>
                    <?php
                endforeach;
            }
            ?>
            </ul>
            <?php
        }
    }

    public function gettablebyfloor_OLD() {
        if (!empty($this->input->post('floor_id'))) {
            $floorId = $this->input->post('floor_id');
            $floorId = 3;
            $tabledata = array();
            $this->db->select('t.id AS table_id, t.type, t.seats, t.priority, t.top, t.left, t.rotation,t.roomId, t.name AS tableName, s.image AS shapeImage,s.canrotate, r.name AS roomName');
            $this->db->from('mw_tables as t');
            $this->db->join('mw_shapes as s', 't.type=s.id');
            $this->db->join('mw_rooms as r', 't.roomId=r.id');
            $this->db->where(array('t.roomId' => $floorId));
            $tabledata = $this->db->get()->result();
            ?>        
            <label class="tblcheck_box"></label>
            <?php
            echo '<h4>' . (!empty($tabledata) && isset($tabledata[0]->roomName)) ? $tabledata[0]->roomName : "" . '</h4>';
            echo '<ul>';
            if (!empty($tabledata)) {
                foreach ($tabledata as $tabls):
                    ?>
                    <li>
                        <?php echo (isset($tabls->tableName)) ? $tabls->tableName : ""; ?>
                        <div class="custom_checkbox" style="display:inline-block;">
                            <input id="tabel_check_id_<?php echo $tabls->table_id; ?>" type="checkbox" data-pers="<?php echo $tabls->seats; ?>" class="form-control chk_table_id" name="tables[]" value="<?php echo $tabls->table_id; ?>" />   
                            <label for="tabel_check_id_<?php echo $tabls->table_id; ?>" class="chktablecls" title="<?php echo $tabls->seats; ?>pers.">
                                <img src="<?php echo base_url() ?>uploads/tables/<?php echo $tabls->shapeImage; ?>" class="check_img">
                            </label>
                        </div>
                        <?php echo $tabls->seats . ' pers.'; ?>    
                    </li>
                    <?php
                endforeach;
            }
            ?>
            </ul>
            <?php
        }
    }

    /* View Booking */

    public function view_booking() {
        $this->data['url'] = base_url() . 'tablebooking/view_booking';
        $this->data['pageTitle'] = "Booking";
        $this->data['parent'] = "View Booking";
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $floorID = $this->input->post('floor_id_search');
        $user_id = $this->uri->segment(3);
        $options = array('table' => 'mw_rooms', 'select' => 'id,name');
        if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'):
            $options['where'] = array('store'=>$this->session->userdata('id'));
        endif;
        $this->data['floors'] = $this->Common_model->customGet($options);
        $where = '';
        $this->data['booking'] = array(
            'start_date' => $start_date,
            'end_date' => $end_date
        );
        if (!empty($start_date) && !empty($end_date)) {

            $start_date = str_replace('/', '-', $start_date);
            $start_date = date('Y-m-d', strtotime($start_date));
            $end_date = str_replace('/', '-', $end_date);
            $end_date = date('Y-m-d', strtotime($end_date));
        } else {
            $start_date = "";
            $end_date = "";
        }
        $where = array();
        if ($this->session->userdata('role') == 'agent') {
            /* $option = array('table' => 'mw_rooms',
              'select' => 'id',
              'where' => array('agent' => $this->session->userdata('id')),
              'single' => true);
              $floor = $this->Common_model->customGet($option); */

            /* $option = array('table' => 'mw_booking',
              'select' => 'id',
              'where' => array('agent_id' => $this->session->userdata('id')),
              'single' => true);
              $floor = $this->Common_model->customGet($option); */
            /* if(!empty($floor)):
              $where = array('floor_id' => $floor->id);
              endif; */
            $where = array('agent_id' => $this->session->userdata('id'));
        }
        if (!empty($floorID)) {
            $where = array('floor_id' => $floorID);
        }
        if (!empty($user_id)) {
            $where = array('user_id' => $user_id);
        }

        //$floor = $this->Common_model->customGet($option);
        //$where['user_id'] = $user_id;
        /* if (empty($user_id)) {
          $this->data['list'] = $this->Common_model->booking($start_date, $end_date, 'mw_booking',$where);
          } else {
          $this->data['list'] = $this->Common_model->booking($start_date, $end_date, 'mw_booking', $where);
          } */
        if (empty($start_date) && empty($end_date)) {
            if ($this->session->userdata('role_id') == 2):
                $start_date = date('Y-m-d');
                $end_date = date('Y-m-d', strtotime('+1 days'));
            endif;
        }
        $this->data['list'] = $this->Common_model->booking($start_date, $end_date, 'mw_booking', $where);
        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

    /* End */

    function open_model_feedback() {

        $this->data['id'] = decoding($this->input->post('id'));
        $this->data['title'] = "Booking Delete Reason";
        $this->load->view('feedback_booking', $this->data);
    }

    public function deletebooking() {

        $this->form_validation->set_rules('comment', 'comment', 'required|trim');
        $this->form_validation->set_rules('id', 'booking id', 'required|trim');
        if ($this->form_validation->run() == true) {
            $id = $this->input->post('id');
            $option = array(
                'table' => 'mw_booking',
                'where' => array('id' => $id),
                'single' => true
            );
            $booking = $this->Common_model->customGet($option);
            $booking->delete_comment = $this->input->post('comment');
            $option = array(
                'table' => 'booking_history',
                'data' => $booking
            );
            $this->Common_model->customInsert($option);
            $option = array(
                'table' => 'mw_booking',
                'where' => array('id' => $id)
            );
            $this->Common_model->customDelete($option);

            $opt = array(
                'table' => 'mw_booking_tables',
                'where' => array('booking_id' => $id)
            );
            $this->Common_model->customDelete($opt);
            $response = array('status' => 1, 'message' => "Successfully removed booking", 'url' => base_url('tablebooking/view_booking'));
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => strip_tags($messages));
        }

        echo json_encode($response);
    }

    public function freeTable() {
        if ($this->input->post('id') != ''):
            $id = decoding($this->input->post('id'));
            $time = $this->input->post('time');
            $update['time_to'] = $time;
            $option = array(
                'table' => 'mw_booking',
                'data' => array('time_to' => date('H:i:s'), 'booking_date' => date('Y-m-d')),
                'where' => array('id' => $id)
            );
            $count = $this->Common_model->customUpdate($option);
            if ($count == 1):
                echo 1;
            else:
                echo 0;
            endif;
        endif;
    }

    public function getReffer() {
        $str = $this->input->get('term');
        $option = array(
            'table' => 'mw_booking',
            'select' => 'referrer',
            'like' => array('referrer' => $str),
            'group_by' => 'referrer'
        );
        $referrs = $this->Common_model->customGet($option);
        $temp = array();
        foreach ($referrs as $rows) {
            $temp[] = $rows->referrer;
        }
        echo json_encode($temp);
    }

    public function bookingStatus() {
        $flag = FALSE;
        $bookingId = decoding($this->input->post('bookingId'));
        $status = $this->input->post('status');
        $response = array();
        if (empty($bookingId) && empty($status)) {
            $response = array('status' => 0, 'message' => 'Booking Not Found', 'redirect' => base_url() . 'tablebooking/view_booking');
            echo json_encode($response);
            exit;
        }
        $from = FROM_EMAIL;
        $subject = "Booking status";
        $title = "Booking status";
        $status_message = "";
        $option = array(
            'table' => 'mw_booking',
            'data' => array('status' => $status),
            'where' => array('id' => $bookingId)
        );
        $update = $this->Common_model->customUpdate($option);
        $option = array(
            'table' => 'mw_booking',
            'where' => array('id' => $bookingId),
            'single' => true
        );
        $bookingData = $this->Common_model->customGet($option);
        /* Send Email to User */
        if ($update && !empty($bookingData)) {

            $option = array(
                'table' => 'mw_booking_tables',
                'select' => "mw_tables.name",
                'join' => array('mw_tables' => 'mw_tables.id = mw_booking_tables.table_id'),
                'where' => array('mw_booking_tables.booking_id' => $bookingId),
            );
            $temptable = $this->Common_model->customGet($option);

            $tableName = '';
            if (!empty($temptable)):
                foreach ($temptable as $t):
                    $tableName .= $t->name . ',';
                endforeach;
            endif;
            $tableName = rtrim($tableName, ",");
            $booking_date = $bookingData->booking_date;
            $full_name = $bookingData->name;
            $email = $bookingData->email;

            if ($status == 1) {

                $status_message = "Your booking has been Confirmed on $tableName Date " . date('d/m/y', strtotime($booking_date)) . " Time " . date('h:i:s A', strtotime($booking_date)) . ".";
                $data['content'] = "Congratulation! Admin has been Confirmed your booking.";
                $data['user'] = ucwords($full_name);
                $message = $this->load->view('email_template', $data, true);

                /* send mail */
                if (!empty($email) && !empty($from)) {
                    // $flag = send_mail($message, $subject, $email, $from, $title);
                    $flag = TRUE;
                } else {
                    $flag = TRUE;
                }
            } else if ($status == 2) {
                $status_message = "Your booking has been Cancelled for Date" . date('d/m/y', strtotime($booking_date)) . " Time " . date('h:i:s A', strtotime($booking_date)) . ".";

                $data['content'] = "Your booking is Pending.";
                $data['user'] = ucwords($full_name);

                $message = $this->load->view('email_template', $data, true);

                /* send mail */
                if (!empty($email) && !empty($from)) {
                    // $flag = send_mail($message, $subject, $email, $from, $title);
                    $flag = TRUE;
                } else {
                    $flag = TRUE;
                }
            } else if ($status == 3) {
                $status_message = "Admin has been Cancelled your booking on " . date('d/m/y h:i:s A', strtotime($booking_date));

                $data['content'] = "Admin has been Cancelled your booking.";
                $data['user'] = ucwords($full_name);

                $message = $this->load->view('email_template', $data, true);

                /* send mail */
                if (!empty($email) && !empty($from)) {
                    //$flag = send_mail($message, $subject, $email, $from, $title);
                    $flag = TRUE;
                } else {
                    $flag = TRUE;
                }
            } else if ($status = 4 || $status = 5) {
                $flag = TRUE;
            }
            if ($flag) {
                $response = array('status' => 1, 'message' => 'Booking Successfully update', 'redirect' => base_url() . 'tablebooking/view_booking');
                echo json_encode($response);
            } else {
                $response = array('status' => 0, 'message' => 'Booking Can not update', 'redirect' => base_url() . 'tablebooking/view_booking');
                echo json_encode($response);
            }
        } else {
            $response = array('status' => 0, 'message' => 'Booking Not Found', 'redirect' => base_url() . 'tablebooking/view_booking');
            echo json_encode($response);
        }
    }

    public function deleteHistory() {
        $this->data['url'] = base_url() . 'tablebooking/deleteHistory';
        $this->data['pageTitle'] = "Delete Booking History";
        $this->data['parent'] = "delete booking";
        $this->data['list'] = $this->Common_model->booking_delete_history();
        $this->template->load_view('default', 'delete_history', $this->data, 'inner_script');
    }

    public function booking_ajax_table() {
        $search = $this->input->post('searchstr');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $floor = $this->input->post('floor');
        $where = array();
        if (!empty($floor)) {
            $where = array('floor_id' => $floor);
        }
        if ($this->session->userdata('role') == 'agent') {
            $where['agent_id'] = $this->session->userdata('id');
        }
        echo $this->Common_model->booking_ajax_table($search, $start_date, $end_date, $where);
    }

    public function exportExcelBookingByDate() {
        $this->load->library('PHPExcel');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');
        $floor_id_search = $this->input->post('floor_id_search');
        if (!empty($start_date) && !empty($end_date)) {
            $date = str_replace('/', '-', $start_date);
            $start_date = date('Y-m-d', strtotime($date));

            $date = str_replace('/', '-', $end_date);
            $end_date = date('Y-m-d', strtotime($date));
        }
        $options = array(
            'table' => 'mw_booking',
            'order' => array('mw_booking.id' => 'DESC'),
        );
        if (!empty($start_date) && !empty($end_date)) {
            $options['where']['mw_booking.booking_date >='] = $start_date;
            $options['where']['mw_booking.booking_date <='] = $end_date;
        }
        if (!empty($floor_id_search)) {
            $options['where']['mw_booking.floor_id'] = $floor_id_search;
        }else{
            //arjun
            if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'){
                $options['join'] = array('mw_rooms'=>'mw_rooms.id = mw_booking.floor_id');
                $options['where'] = array('mw_rooms.store'=>$this->session->userdata('id'));
            }
        }
        if ($this->session->userdata('role') == 'agent') {
            $options['where']['mw_booking.agent_id'] = $this->session->userdata('id');
        }

        $bookings = $this->Common_model->customGet($options);
        $objPHPExcel = new PHPExcel();
// Set document properties
        $objPHPExcel->getProperties()->setCreator("Santanna")
                ->setLastModifiedBy("Maarten Balliauw")
                ->setTitle("Office 2007 XLSX Test Document")
                ->setSubject("Office 2007 XLSX Test Document")
                ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Test result file");
// Add some data


        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(30);

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A1", 'Name')
                ->setCellValue("B1", 'STATUS')
                ->setCellValue("C1", 'Section')
                ->setCellValue("D1", 'No of persons')
                ->setCellValue("E1", 'Booking date')
                ->setCellValue("F1", 'Time From')
                ->setCellValue("G1", 'Time To')
                ->setCellValue("H1", 'Email')
                ->setCellValue("I1", 'Mobile')
                ->setCellValue("J1", 'Referrer')
                ->setCellValue("K1", 'Comment');

        $x = 2;
        foreach ($bookings as $sub) {
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A$x", $sub->name)
                    ->setCellValue("B$x", getStatusStr($sub->status))
                    ->setCellValue("C$x", getFloorDetail($sub->floor_id))
                    ->setCellValue("D$x", $sub->no_of_persons)
                    ->setCellValue("E$x", dateFormateManage($sub->booking_date))
                    ->setCellValue("F$x", timeFormateManage($sub->time_from))
                    ->setCellValue("G$x", timeFormateManage($sub->time_to))
                    ->setCellValue("H$x", $sub->email)
                    ->setCellValue("I$x", $sub->mobile)
                    ->setCellValue("J$x", $sub->referrer)
                    ->setCellValue("K$x", $sub->comment);
            $x++;
        }


// Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Reservation');
// Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
// Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Santanna-Reservation.xls"');
        header('Cache-Control: max-age=0');
// If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
// If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }


    public function get_money_by_point(){
        if($this->input->post('redeem_point') != ''){
            $money = $this->Common_model->PointToMoney($this->input->post('redeem_point'));
            echo $money;
        }
    }

}
?>
