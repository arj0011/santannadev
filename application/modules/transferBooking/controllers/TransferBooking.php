<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class TransferBooking extends Common_Controller {

    public $data = array();
    public $file_data = array();

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        
        $this->data['url'] = base_url().'transferBooking';
        $this->data['pageTitle'] = "Transfer Booking";
        $this->data['parent'] = "Transfer Booking";
        /*$option = array('table' => 'mw_rooms',
        );*/
        // $this->data['floors'] = $floors = $this->Common_model->customGet($option);
        $this->data['floors'] = $floors = $this->Common_model->floorByRole('result');
        $bookingId = $this->uri->segment(3);
        $booking_where = "";
        if (empty($bookingId)) {
            $floorPlanId = $floors[0]->id;
        } else {
            $bookingId = decoding($bookingId);
            $option = array('table' => 'mw_booking', 'where' => array('id' => $bookingId), 'single' => true);
            $booking = $this->Common_model->customGet($option);
            if (!empty($booking)) {
                $floorPlanId = $booking->floor_id;
                $booking_where = "AND b.id = " . $bookingId;
            }
        }
        $this->data['floor_id_us'] = $floorPlanId;
        if (!empty($floorPlanId)):
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
                $sql_3 = "SELECT b.booking_date,b.no_of_persons,b.time_from AS StartTime,b.time_to AS EndTime,bt.id,b.name AS personName,b.id AS bookingID
        			  FROM mw_booking AS b
        			  INNER JOIN mw_booking_tables AS bt ON bt.booking_id = b.id
        			  WHERE bt.table_id = $fd->id AND b.floor_id = $fd->roomId AND b.booking_date = '" . date('Y-m-d') . "'";

                $query_3 = $this->db->query($sql_3);
                $validateData = $query_3->row_array();
                if (!empty($validateData)):
                    
                    if (strtotime($validateData['booking_date']) == strtotime(date('Y-m-d'))):
                        if (strtotime($validateData['EndTime']) > $utctime):
                            $border = '2px solid #C91F37 !important;}';
                            $StartTime = $validateData['StartTime'];
                            $EndTime = $validateData['EndTime'];
                            $personName = $validateData['personName'];
                            $bookingID = $validateData['bookingID'];
                            $numofpersons = $validateData['no_of_persons'];
                            $bookingDate = $validateData['booking_date'];
                            
                        else:
                            $border = '2px solid #03A678 !important;';
                            $StartTime = '';
                            $EndTime = '';
                            $personName = '';
                            $bookingID = '';
                            $numofpersons = "";
                            $bookingDate = "";
                        endif;
                    else:
                        $border = '2px solid #FFA400 !important;}';
                        $StartTime = $validateData['StartTime'];
                        $EndTime = $validateData['EndTime'];
                        $personName = $validateData['personName'];
                        $bookingID = $validateData['bookingID'];
                        $numofpersons = $validateData['no_of_persons'];
                        $bookingDate = $validateData['booking_date'];

                    endif;
                else:
                    $border = '2px solid #03A678 !important;';
                    $StartTime = '';
                    $EndTime = '';
                    $personName = '';
                    $bookingID = '';
                    $numofpersons = "";
                    $bookingDate = "";
                endif;

                $fd->border = $border;
                $fd->StartTime = $StartTime;
                $fd->EndTime = $EndTime;
                $fd->personName = $personName;
                $fd->bookingID = $bookingID;
                $fd->no_of_persons = $numofpersons;
                $fd->booking_date = $bookingDate;
                $columnArr[$fd->roomName][] = $fd;
            }

            $this->data['tables'] = $columnArr;
        endif;
        $this->template->load_view('default', 'transfer', $this->data, 'inner_script');
    }

    /**
     * @method booking_list
     * @description show booking table list
     * @return array
     */
    function booking_list() {
        $this->data['parent'] = "Booking List";
        $this->load->view('tables_view', $this->data);
        //$this->template->load_view('default', 'tables_view', $this->data, 'inner_script');
    }

    function floorPlanView() {
        $this->data['parent'] = "Floor Plan View";
        $floorPlanId = $this->input->post('floorId');
        if (!empty($floorPlanId)):
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
                $sql_3 = "SELECT b.booking_date,b.no_of_persons,b.time_from AS StartTime,b.time_to AS EndTime,bt.id,b.name AS personName,b.id AS bookingID
        			  FROM mw_booking AS b
        			  INNER JOIN mw_booking_tables AS bt ON bt.booking_id = b.id
        			  WHERE bt.table_id = $fd->id AND b.floor_id = $fd->roomId AND b.booking_date = '" . date('Y-m-d') . "'";

                $query_3 = $this->db->query($sql_3);
                $validateData = $query_3->row_array();
                               if (!empty($validateData)):
                    
                    if (strtotime($validateData['booking_date']) == strtotime(date('Y-m-d'))):
                        if (strtotime($validateData['EndTime']) > $utctime):
                            $border = '2px solid #C91F37 !important;}';
                            $StartTime = $validateData['StartTime'];
                            $EndTime = $validateData['EndTime'];
                            $personName = $validateData['personName'];
                            $bookingID = $validateData['bookingID'];
                            $numofpersons = $validateData['no_of_persons'];
                            $bookingDate = $validateData['booking_date'];
                            
                        else:
                            $border = '2px solid #03A678 !important;';
                            $StartTime = '';
                            $EndTime = '';
                            $personName = '';
                            $bookingID = '';
                            $numofpersons = "";
                            $bookingDate = "";
                        endif;
                    else:
                        $border = '2px solid #FFA400 !important;}';
                        $StartTime = $validateData['StartTime'];
                        $EndTime = $validateData['EndTime'];
                        $personName = $validateData['personName'];
                        $bookingID = $validateData['bookingID'];
                        $numofpersons = $validateData['no_of_persons'];
                        $bookingDate = $validateData['booking_date'];

                    endif;
                else:
                    $border = '2px solid #03A678 !important;';
                    $StartTime = '';
                    $EndTime = '';
                    $personName = '';
                    $bookingID = '';
                    $numofpersons = "";
                    $bookingDate = "";
                endif;

                $fd->border = $border;
                $fd->StartTime = $StartTime;
                $fd->EndTime = $EndTime;
                $fd->personName = $personName;
                $fd->bookingID = $bookingID;
                $fd->no_of_persons = $numofpersons;
                $fd->booking_date = $bookingDate;
                $columnArr[$fd->roomName][] = $fd;
            }

            $this->data['tables'] = $columnArr;
        endif;
        $this->load->view('floor_plan_view', $this->data);
    }

    function moveTo() {
        $bookingId = $this->input->post('bookingId');
        $tableId = decoding($this->input->post('tableId'));
        $roomId = $this->input->post('roomId');
        $bookingDetails = decoding($this->input->post('bookingDetails'));
        $option = array('table' => 'mw_tables as t',
            'select' => 't.* ,r.id AS roomId,r.name AS roomName, r.roomHeight,r.roomWidth,r.image as roomImage',
            'join' => array('mw_rooms as r' => 'r.id=t.roomId'),
            'where' => array('r.id' => $roomId),
            'where_not_in' => array('t.id' => $tableId)
        );
        $this->data['tables'] = $this->Common_model->customGet($option);
        $this->data['bookingId'] = $bookingId;
        $this->data['roomId'] = $roomId;
        $this->data['bookingDetails'] = $bookingDetails;
        $this->load->view('tables_view', $this->data);
    }

    function moveNow() {
        $this->form_validation->set_rules('tables[]', 'Tables', 'required|trim');
        if ($this->form_validation->run() == true) {
            $bookingId = $this->input->post('bookingId');
            $roomId = $this->input->post('roomId');
            $tables = $this->input->post('tables');
            $tableId = $this->input->post('tableId');
            $insert = 0;
            foreach ($tables as $rows) {
                $option = array('table' => 'mw_booking_tables',
                    'data' => array('booking_id' => $bookingId,
                        'table_id' => $rows)
                );
                $insert = $this->Common_model->customInsert($option);
            }
            if ($insert) {
                $option = array('table' => 'mw_booking_tables',
                    'where' => array('booking_id' => $bookingId,
                        'table_id' => $tableId),
                );
                $this->Common_model->customDelete($option);
            }
            $response = array('status' => 1, 'message' => "Successfully Moved", 'url' => base_url('transferBooking'));
        } else {
            $response = array('status' => 0, 'message' => "Please select table in which you want to move");
        }
        echo json_encode($response);
    }

    public function moveTable() {
        $fromBookingId = $this->input->post('fromBookingId');
        $tableId = $this->input->post('tableId');
        $roomId = $this->input->post('roomId');
        $bookedTableId = $this->input->post('bookedTableId');
        if (!empty($fromBookingId)) {
            if (!empty($tableId)) {

                $option = array('table' => 'mw_booking_tables',
                    'data' => array('booking_id' => $fromBookingId,
                        'table_id' => $tableId)
                );
                $insert = $this->Common_model->customInsert($option);
                if ($insert) {
                    $option = array('table' => 'mw_booking_tables',
                        'where' => array('booking_id' => $fromBookingId,
                            'table_id' => $bookedTableId),
                    );
                    $this->Common_model->customDelete($option);
                }
                $response = array('status' => 1, 'message' => "Successfully Moved, Please wait content loading...");
            } else {
                $response = array('status' => 0, 'message' => "Please Select Table In Which You Want To Move");
            }
        } else {
            $response = array('status' => 0, 'message' => "Please Select Booked Table You Want To Move");
        }
        echo json_encode($response);
    }

    public function freeTable() {
        $bookingId = $this->input->post('bookingId');
        if (!empty($bookingId)) {
            $option = array(
                'table' => 'mw_booking',
                'data' => array('time_to' => date('H:i:s'), 'booking_date' => date('Y-m-d')),
                'where' => array('id' => $bookingId)
            );
            $update = $this->Common_model->customUpdate($option);
            if ($update) {
                $response = array('status' => 1, 'message' => "Successfully Table Updated, Please wait content loading...");
            } else {
                $response = array('status' => 0, 'message' => "Table Not Update Please Try Again");
            }
        } else {
            $response = array('status' => 0, 'message' => "Please Select Booked Table You Want To Free");
        }
        echo json_encode($response);
    }

}
