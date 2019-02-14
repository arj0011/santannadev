<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_model extends CI_Model {

    //Function for update
    public function customUpdate($options) {
        $table = false;
        $where = false;
        $orwhere = false;
        $data = false;

        extract($options);

        if (!empty($where)) {
            $this->db->where($where);
        }

        // using or condition in where  
        if (!empty($orwhere)) {
            $this->db->or_where($orwhere);
        }
        $this->db->update($table, $data);
        return $this->db->affected_rows();
    }

    //Function for delete
    public function customDelete($options) {
        $table = false;
        $where = false;

        extract($options);

        if (!empty($where))
            $this->db->where($where);

        $this->db->delete($table);

        return $this->db->affected_rows();
    }

    //Function for insert
    public function customInsert($options) {
        $table = false;
        $data = false;

        extract($options);


        $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    //Function for get
    public function customGet($options) {

        $select = false;
        $table = false;
        $join = false;
        $order = false;
        $limit = false;
        $offset = false;
        $where = false;
        $or_where = false;
        $single = false;
        $group_by = false;
        $where_not_in = false;
        $like = false;

        extract($options);

        if ($select != false)
            $this->db->select($select);

        if ($table != false)
            $this->db->from($table);

        if ($where != false)
            $this->db->where($where);

        if ($where_not_in != false) {
            if (is_array($where_not_in)) {
                foreach ($where_not_in as $key => $val) {
                    $this->db->where_not_in($key, $val);
                }
            }
        }

        if ($or_where != false)
            $this->db->or_where($or_where);

        if ($limit != false) {

            if (!is_array($limit)) {
                $this->db->limit($limit);
            } else {
                foreach ($limit as $limitval => $offset) {
                    $this->db->limit($limitval, $offset);
                }
            }
        }

        if ($like != false) {
            foreach ($like as $col => $keyword) {
                $this->db->like($col, $keyword);
            }
        }

        if ($group_by != false) {

            $this->db->group_by($group_by);
        }


        if ($order != false) {

            foreach ($order as $key => $value) {

                if (is_array($value)) {
                    foreach ($order as $orderby => $orderval) {
                        $this->db->order_by($orderby, $orderval);
                    }
                } else {
                    $this->db->order_by($key, $value);
                }
            }
        }




        if ($join != false) {

            foreach ($join as $key => $value) {

                if (is_array($value)) {
                    if (count($value) == 3) {
                        $this->db->join($value[0], $value[1], $value[2]);
                    } else {
                        foreach ($value as $key1 => $value1) {
                            $this->db->join($key1, $value1);
                        }
                    }
                } else {
                    $this->db->join($key, $value);
                }
            }
        }


        $query = $this->db->get();
        
        if ($single) {
            return $query->row();
        }


        return $query->result();
    }

    public function customQuery($query, $single = false, $updDelete = false, $noReturn = false) {
        $query = $this->db->query($query);

        if ($single) {
            return $query->row();
        } elseif ($updDelete) {
            return $this->db->affected_rows();
        } elseif (!$noReturn) {
            return $query->result();
        } else {
            return true;
        }
    }

    public function getGroupUser($table, $user_list = array()) {

        $this->db->select('id,name,user_image,email');
        $this->db->from($table);
        $this->db->where_in('id', $user_list);
        $query = $this->db->get();
        return $query->result();
    }

    public function customQueryCount($query) {
        return $this->db->query($query)->num_rows();
    }

    function customCount($options) {
        $table = false;
        $join = false;
        $order = false;
        $limit = false;
        $offset = false;
        $where = false;
        $or_where = false;
        $single = false;

        extract($options);

        if ($table != false)
            $this->db->from($table);

        if ($where != false)
            $this->db->where($where);

        if ($or_where != false)
            $this->db->or_where($or_where);

        if ($limit != false) {

            if (!is_array($limit)) {
                $this->db->limit($limit);
            } else {
                foreach ($limit as $limitval => $offset) {
                    $this->db->limit($limitval, $offset);
                }
            }
        }


        if ($order != false) {

            foreach ($order as $key => $value) {

                if (is_array($value)) {
                    foreach ($order as $orderby => $orderval) {
                        $this->db->order_by($orderby, $orderval);
                    }
                } else {
                    $this->db->order_by($key, $value);
                }
            }
        }


        if ($join != false) {

            foreach ($join as $key => $value) {

                if (is_array($value)) {
                    if (count($value) == 3) {
                        $this->db->join($value[0], $value[1], $value[2]);
                    } else {
                        foreach ($value as $key1 => $value1) {
                            $this->db->join($key1, $value1);
                        }
                    }
                } else {
                    $this->db->join($key, $value);
                }
            }
        }

        return $this->db->count_all_results();
    }

    //Send Mail 
    function customMail($data = false) {
        $config['protocol'] = 'sendmail';
        $config['mailpath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1'; // or utf-8 for html mail
        $config['newline'] = "\r\n";
        $config['mailtype'] = 'html'; // or html
        $config['wordwrap'] = TRUE;
        $config['validation'] = TRUE; // bool whether to validate email or not  
        $config['charset'] = "utf-8";

        $this->load->library('email', $config);

        if (!$data)
            return FALSE;

        $cc = '';

        if (isset($data['cc']) && (!empty($data['cc']))) {
            $cc = $data['cc'];
        }

        $this->email->from($this->configCustomData['verif_email'], $this->configCustomData['verif_name']);
        $this->email->to($data['toEmail']);
        $this->email->cc($cc);
        $this->email->subject($data['subject']);
        //$this->email->message('Testing the email class. <br /> TEST Again <br /> <h1> H1 Heading </h1>');
        $this->email->message($data['message'] . $data['body']);
        $status = (bool) $this->email->send();
        return $status;
    }

    /* <!--INSERT RECORD FROM SINGLE TABLE--> */

    function insertData($table, $dataInsert) {
        $this->db->insert($table, $dataInsert);
        return $this->db->insert_id();
    }

    function insertBulkData($table, $dataInsert) {
        $this->db->insert_batch($table, $dataInsert);
    }

    /* <!--UPDATE RECORD FROM SINGLE TABLE--> */

    function updateFields($table, $data, $where) {
        $this->db->update($table, $data, $where);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    function deleteData($table, $where) {
        $this->db->where($where);
        $this->db->delete($table);
        if ($this->db->affected_rows() > 0) {
            return true;
        } else {
            return false;
        }
    }

    /* ---GET SINGLE RECORD--- */

    function getsingle($table, $where = '', $fld = NULL, $order_by = '', $order = '') {

        if ($fld != NULL) {
            $this->db->select($fld);
        }
        $this->db->limit(1);

        if ($order_by != '') {
            $this->db->order_by($order_by, $order);
        }
        if ($where != '') {
            $this->db->where($where);
        }

        $q = $this->db->get($table);
        $num = $q->num_rows();
        if ($num > 0) {
            return $q->row();
        }
    }

    /* <!--Join tables get single record with using where condition--> */

    function GetJoinRecord($table, $field_first, $tablejointo, $field_second, $field_val = '', $where = "", $group_by = '', $order_fld = '', $order_type = '', $limit = '', $offset = '') {
        $data = array();
        if (!empty($field_val)) {
            $this->db->select("$field_val");
        } else {
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first", "inner");
        if (!empty($where)) {
            $this->db->where($where);
        }
        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }

        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if (!empty($order_fld) && !empty($order_type)) {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();

        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    /* ---GET MULTIPLE RECORD--- */

    function getAllwhere($table, $where = '', $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '', $group_by = '') {
        $data = array();
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        $this->db->from($table);
        if ($where != '') {
            $this->db->where($where);
        }
        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }

        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    /* ---GET MULTIPLE RECORD--- */

    function getAll($table, $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '', $group_by = '') {
        $data = array();
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        if ($group_by != '') {
            $this->db->group_by($group_by);
        }
        $this->db->from($table);

        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    /* <!--GET ALL COUNT FROM SINGLE TABLE--> */

    function getcount($table, $where = "") {
        if (!empty($where)) {
            $this->db->where($where);
        }
        $q = $this->db->count_all_results($table);
        return $q;
    }

    function getTotalsum($table, $where, $data) {
        $this->db->where($where);
        $this->db->select_sum($data);
        $q = $this->db->get($table);
        return $q->row();
    }

    function GetJoinRecordNew($table, $field_first, $tablejointo, $field_second, $field, $value, $field_val, $group_by = '', $order_fld = '', $order_type = '', $limit = '', $offset = '') {
        $data = array();
        $this->db->select("$field_val");
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first");
        $this->db->where("$table.$field", "$value");
        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }

        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }
        if (!empty($order_fld) && !empty($order_type)) {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    function GetJoinRecordThree($table, $field_first, $tablejointo, $field_second, $tablejointhree, $field_three, $table_four, $field_four, $field_val = '', $where = "", $group_by = "", $order_fld = '', $order_type = '', $limit = '', $offset = '') {
        $data = array();
        if (!empty($field_val)) {
            $this->db->select("$field_val");
        } else {
            $this->db->select("*");
        }
        $this->db->from("$table");
        $this->db->join("$tablejointo", "$tablejointo.$field_second = $table.$field_first", 'inner');
        $this->db->join("$tablejointhree", "$tablejointhree.$field_three = $table_four.$field_four", 'inner');
        if (!empty($where)) {
            $this->db->where($where);
        }

        if (!empty($group_by)) {
            $this->db->group_by($group_by);
        }
        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }

        if (!empty($order_fld) && !empty($order_type)) {
            $this->db->order_by($order_fld, $order_type);
        }
        $q = $this->db->get();
        if ($q->num_rows() > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    function getAllwhereIn($table, $where = '', $column = '', $wherein = '', $order_fld = '', $order_type = '', $select = 'all', $limit = '', $offset = '', $group_by = '') {
        $data = array();
        if ($order_fld != '' && $order_type != '') {
            $this->db->order_by($order_fld, $order_type);
        }
        if ($select == 'all') {
            $this->db->select('*');
        } else {
            $this->db->select($select);
        }
        $this->db->from($table);
        if ($where != '') {
            $this->db->where($where);
        }
        if ($wherein != '') {
            $this->db->where_in($column, $wherein);
        }
        if ($group_by != '') {
            $this->db->group_by($group_by);
        }

        $clone_db = clone $this->db;
        $total_count = $clone_db->count_all_results();

        if ($limit != '' && $offset != '') {
            $this->db->limit($limit, $offset);
        } else if ($limit != '') {
            $this->db->limit($limit);
        }

        $q = $this->db->get();
        $num_rows = $q->num_rows();
        if ($num_rows > 0) {
            foreach ($q->result() as $rows) {
                $data[] = $rows;
            }
            $q->free_result();
        }
        return array('total_count' => $total_count, 'result' => $data);
    }

    function user_auto_search($search) {
        $query = "SELECT users.id,users.name,users.email FROM users"
                . " where users.name like '" . $search . "%' OR users.email like '" . $search . "%' LIMIT 100";
        return $result = $this->db->query($query)->result_array();
    }

    public function booking($start_date, $end_date, $table, $where = '') {

        $this->db->select('booking.referrer,booking.special_request_id,booking.floor_id,booking.id,booking.email,booking.mobile,booking.place,booking.no_of_persons,booking.comment,booking.booking_date,booking.status,booking.name,booking.time_from,booking.time_to');

        $this->db->from('mw_booking AS booking');

        //arjun
        if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'){
            $this->db->join('mw_rooms', 'mw_rooms.id = booking.floor_id', 'inner');
            $this->db->where('mw_rooms.store', $this->session->userdata('id'));
        }
        //end

        if (!empty($where)) {
            $this->db->where($where);
        }    
        


        $this->db->order_by('booking.id', 'desc');
        if (!empty($start_date) && !empty($end_date)) {
            $end_date = date('Y-m-d', strtotime($end_date));
            $this->db->where('booking.booking_date >=', $start_date);
            $this->db->where('booking.booking_date <=', $end_date);
        }
        $query = $this->db->get();
        return $query->result_array();
    }

    public function booking_ajax($search = '', $start_date = '', $end_date = '', $where = '') {
        $this->load->library('datatables');
        $this->datatables->select('booking.id,booking.payment,booking.referrer,booking.special_request_id,booking.floor_id,booking.id,booking.email,booking.mobile,booking.place,booking.no_of_persons,booking.comment,booking.status as st,booking.booking_date,booking.status,booking.name,booking.time_from,booking.time_to');
        $this->datatables->from('mw_booking AS booking');
        $this->datatables->join('mw_rooms as rooms','rooms.id=booking.floor_id');
        $this->datatables->order_by('booking.id', 'desc');
        
         //arjun
        if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'){
            $this->datatables->where('rooms.store',$this->session->userdata('id'));
        }
        //end

        if (!empty($where)) {
            $this->datatables->where($where);
        }
        if (!empty($start_date) && !empty($end_date)) {
            $end_date = date('Y-m-d', strtotime($end_date));
            $this->datatables->where('booking.booking_date >=', $start_date);
            $this->datatables->where('booking.booking_date <=', $end_date);
        }

        if (!empty($search)) {
            $this->datatables->where('booking.name LIKE', "%$search%");
            $this->datatables->or_where('booking.mobile Like', "%$search%");
            $this->datatables->or_where('rooms.name Like', "%$search%");
            $dt = date('Y-m-d', strtotime($search));

            $date = str_replace('/', '-', $search);
            $dt = date('Y-m-d', strtotime($date));
            $this->datatables->or_where('booking.booking_date Like', "%$dt%");
        }

        $this->datatables->add_column('email', '$1', 'email');
        $this->datatables->add_column('name', '$1', 'paymentStatus(name,payment,id)');
        $this->datatables->add_column('mobile', '$1', 'mobile');
        $this->datatables->add_column('no_of_persons', '$1', 'no_of_persons');
        $this->datatables->add_column('booking_date', '$1', 'dateFormateManage(booking_date)');
        $this->datatables->add_column('time_from', '$1', 'timeFormateManage(time_from)');
        $this->datatables->add_column('time_to', '$1', 'timeFormateManage(time_to)');
        $this->datatables->add_column('referrer', '$1', 'referrer');
        $this->datatables->add_column('floor', "$1", 'getFloorDetail(floor_id)');
        $this->datatables->add_column('comment', "$1", 'comment');
        $this->datatables->add_column('status', "$1", 'getStatusStrBookingDashboard(status,id)');
        $this->datatables->add_column('confirmation', "$1", 'getConfirmStr(st,id)');
        $this->datatables->add_column('action', "$1", 'encodingStrAction(id)');

         return  $this->datatables->generate();
         echo $this->datatables->last_query();exit;
    }
    
    public function booking_ajax_walkIn($search = '', $start_date = '', $end_date = '', $where = '') {
        $this->load->library('datatables');
        $this->datatables->select('booking.referrer,booking.special_request_id,booking.floor_id,booking.id,booking.email,booking.mobile,booking.place,booking.no_of_persons,booking.comment,booking.status as st,booking.booking_date,booking.status,booking.name,booking.time_from,booking.time_to');
        $this->datatables->from('mw_booking AS booking');
        $this->datatables->join('mw_rooms as rooms','rooms.id=booking.floor_id');
        $this->datatables->order_by('booking.id', 'desc');
        
         //arjun
        if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'){
            $this->datatables->where('rooms.store',$this->session->userdata('id'));
        }
        //end

        if (!empty($where)) {
            $this->datatables->where($where);
        }
        if (!empty($start_date) && !empty($end_date)) {
            $end_date = date('Y-m-d', strtotime($end_date));
            $this->datatables->where('booking.booking_date >=', $start_date);
            $this->datatables->where('booking.booking_date <=', $end_date);
        }

        if (!empty($search)) {
            $this->datatables->where('booking.name LIKE', "%$search%");
           // $this->datatables->or_where('booking.mobile Like', "%$search%");
            $this->datatables->or_where('rooms.name Like', "%$search%");
        }

        $this->datatables->add_column('email', '$1', 'email');
        $this->datatables->add_column('name', '$1', 'name');
        $this->datatables->add_column('mobile', '$1', 'mobile');
        $this->datatables->add_column('no_of_persons', '$1', 'no_of_persons');
        $this->datatables->add_column('booking_date', '$1', 'dateFormateManage(booking_date)');
        $this->datatables->add_column('time_from', '$1', 'timeFormateManage(time_from)');
        $this->datatables->add_column('time_to', '$1', 'timeFormateManage(time_to)');
        $this->datatables->add_column('referrer', '$1', 'referrer');
        $this->datatables->add_column('floor', "$1", 'getFloorDetail(floor_id)');
        $this->datatables->add_column('comment', "$1", 'comment');
        $this->datatables->add_column('status', "$1", 'getStatusStrBookingDashboard(status,id)');
        $this->datatables->add_column('confirmation', "$1", 'getConfirmStr(st,id)');
        $this->datatables->add_column('action', "$1", 'encodingStrAction(id)');

           $this->datatables->generate();
         echo $this->datatables->last_query();exit;
    }

    public function booking_ajax_table($search = '', $start_date = '', $end_date = '', $where = '') {
        $this->load->library('datatables');
        $this->datatables->select('booking.payment,booking.referrer,booking.special_request_id,booking.floor_id,booking.id,booking.email,booking.mobile,booking.place,booking.no_of_persons,booking.comment,booking.booking_date,booking.status,booking.status as st,booking.name,booking.time_from,booking.time_to');
        $this->datatables->from('mw_booking AS booking');
        $this->datatables->join('mw_rooms as rooms','rooms.id=booking.floor_id');
        $this->datatables->order_by('booking.id', 'desc');
        
        //arjun
        if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'){
            $this->datatables->where('rooms.store',$this->session->userdata('id'));
        }
        //end

        if (!empty($where)) {
            $this->datatables->where($where);
        }
        if (!empty($start_date) && !empty($end_date)) {
            
            $date = str_replace('/', '-', $start_date);
            $start_date = date('Y-m-d', strtotime($date));
            
            $date = str_replace('/', '-', $end_date);
            $end_date = date('Y-m-d', strtotime($date));
            
            $this->datatables->where('booking.booking_date >=', $start_date);
            $this->datatables->where('booking.booking_date <=', $end_date);
        }

        if (!empty($search)) {
            $this->datatables->where('booking.name LIKE', "%$search%");
            $this->datatables->or_where('booking.mobile Like', "%$search%");
            $this->datatables->or_where('rooms.name Like', "%$search%");
            $dt = date('Y-m-d', strtotime($search));

            $date = str_replace('/', '-', $search);
            $dt = date('Y-m-d', strtotime($date));
            $this->datatables->or_where('booking.booking_date Like', "%$dt%");
        }
        
        $this->datatables->add_column('email', '$1', 'email');
        $this->datatables->add_column('name', '$1', 'paymentStatus(name,payment,id)');
        $this->datatables->add_column('mobile', '$1', 'mobile');
        $this->datatables->add_column('no_of_persons', '$1', 'no_of_persons');
        $this->datatables->add_column('booking_date', '$1', 'dateFormateManage(booking_date)');
        $this->datatables->add_column('time_from', '$1', 'timeFormateManage(time_from)');
        $this->datatables->add_column('time_to', '$1', 'timeFormateManage(time_to)');
        $this->datatables->add_column('referrer', '$1', 'referrer');
        $this->datatables->add_column('floor', "$1", 'getFloorDetail(floor_id)');
        $this->datatables->add_column('comment', "$1", 'comment');
        $this->datatables->add_column('status', "$1", 'getStatusStrBooking(status,id)');
        $this->datatables->add_column('confirmation', "$1", 'getConfirmStr(st,id)');
        $this->datatables->add_column('action', "$1", 'encodingStrAction(id)');
        
        return $this->datatables->generate();
    }
    
    
     public function notification_ajax($search = '',$where = '') {
        $this->load->library('datatables');
        $this->datatables->select('notify.booking_id,notify.id,notify.notification_type,notify.title,notify.message_en,notify.sent_time,user.name');
        $this->datatables->from('users_notifications as notify');
        $this->datatables->join('users as user','user.id=notify.sender_id');
        $this->datatables->order_by('notify.id','DESC');
        if (!empty($where)) {
            $this->datatables->where($where);
        }
        if (!empty($search)) {
            $this->datatables->where('notify.title LIKE', "%$search%");
            $this->datatables->or_where('user.name Like', "%$search%");
        }
        $this->datatables->add_column('user', '$1', 'name');
        $this->datatables->add_column('title', '$1', 'title');
        $this->datatables->add_column('message', '$1', 'message_en');
        $this->datatables->add_column('notification_type', '$1', 'notification_type');
        $this->datatables->add_column('dates', '$1', 'sent_time');
        $this->datatables->add_column('action', '$1', 'id');

        return  $this->datatables->generate();
    }
    
    

    public function tableAvailableCount($date, $start_time, $end_time, $table, $where = '') {
        $subpart = '';
        if ($where != '') {
            $subpart = "AND id <> $where";
        }
        $sql = "SELECT * 
                FROM mw_booking 
                WHERE table_id=$table 
                AND booking_date='" . $date . "' 
                $subpart 
                AND ( 
                (time_from > '" . $start_time . "' AND time_from < '" . $end_time . "') 
                OR 
                (time_to > '" . $start_time . "' AND time_to < '" . $end_time . "' ) 
                OR 
                (time_from <='" . $start_time . "' AND time_to >= '" . $end_time . "' ) 
                )";
        $query = $this->db->query($sql);
        return $query->num_rows();
    }

    function getSingleRecordById($table, $conditions) {
        $query = $this->db->get_where($table, $conditions);
        return $query->row_array();
    }

    public function booking_delete_history($where = '') {

        $this->db->select('booking.referrer,booking.special_request_id,booking.floor_id,booking.id,booking.email,booking.mobile,booking.place,booking.no_of_persons,booking.comment,booking.booking_date,booking.status,booking.name,booking.time_from,booking.time_to,booking.delete_comment');
        $this->db->from('booking_history AS booking');
        
        //arjun
        if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'){
            $this->db->join('mw_rooms','mw_rooms.id = booking.floor_id');
            $this->db->where('mw_rooms.store',$this->session->userdata('id'));
        }
        //end

        if (!empty($where)) {
            $this->db->where($where);
        }
        $this->db->order_by('booking.id', 'desc');
        $query = $this->db->get();
        return $query->result_array();
    }


    public function floorByRole($where=''){
        if($this->session->userdata('id') != ''){
            $this->db->select('*');
            $this->db->from('mw_rooms'); 
            if($this->session->userdata('role') == 'store'){
                $this->db->where('mw_rooms.store', $this->session->userdata('id'));
            }
            $query = $this->db->get();
            if(isset($where) && $where != ''):
                return $query->result();    
                endif;
            return $query->result_array();
        }
    }

    public function get_user_wallet_point($user_id){
        $point = 0;
        $userdata = array();
        $this->db->select('total_point');
        $this->db->from(USERPOINTSWALLET);
        $this->db->where('user_id',$user_id);
        $userdata = $this->db->get()->row_array();
        if(!empty($userdata)){
            $point = ($userdata['total_point'] != '') ? $userdata['total_point'] : 0;    
        }
        return $point;
    }

    public function creditpoint($user_id,$order_id,$total_billing_amount,$payment,$floor_id,$redeem_point=0,$store_id){
        $earnpoint = $this->MoneyToPoint($payment,$store_id);
        $walletHistoryArr = $this->db->get_where(USERWALLETHISTORY,array('order_id'=>$order_id))->row_array();
        if(!empty($walletHistoryArr)){

            //for update booking

            // $oldpayment = $walletHistoryArr['payment'];
            // $oldpoint = $this->MoneyToPoint($payment);
            
            $walletArr = $this->db->get_where(USERPOINTSWALLET,array('user_id'=>$user_id))->row_array();
            if(!empty($walletArr)){
                /*$actualpoint = $walletArr['total_point'] - $point;
                if($actualpoint < 0){
                    $actualpoint = 0;
                }*/
                // $actualpoint = $actualpoint + $point;
                $actualpoint = $walletArr['total_point'] + $earnpoint;

                $wallet_id = $walletArr['id'];
                $walletupdateArr = array('total_point'=>$actualpoint);
                $this->db->where('id',$wallet_id);
                $this->db->update(USERPOINTSWALLET,$walletupdateArr);    


                $historyupdateArr = array(
                    // 'user_id'=>$user_id,
                    // 'order_id'=>$order_id,
                    'total_billing_amount' => $total_billing_amount,
                    'actual_payment'=>$payment,
                    'earn_point'=>$earnpoint,
                    'use_point'=>$redeem_point,
                    'floor_id'=>$floor_id,
                    // 'withdrawal_credit'=>'credit',
                    'created_date'      => date('Y-m-d H:i:s')
                    );
                $this->db->where('order_id',$order_id);
                // $this->db->where('withdrawal_credit','credit');
                $this->db->update(USERWALLETHISTORY,$historyupdateArr);
                // $this->db->insert(USERWALLETHISTORY,$historyupdateArr);
            }
        }else{

            //for fresh booking

            $userWallet = $this->db->get_where(USERPOINTSWALLET,array('user_id'=>$user_id))->row_array();
            if(!empty($userWallet)){
                $wallet_id = $userWallet['id'];
                $walletpoint = $userWallet['total_point'];
                $walletpoint = $walletpoint + $earnpoint;
                $updateArr = array('total_point'=>$walletpoint);
                $this->db->where('id',$wallet_id);
                $this->db->update(USERPOINTSWALLET,$updateArr); 
            }else{

                //run only once when customer comes first time ever

                $insertArr = array('user_id'=>$user_id,'total_point'=>$earnpoint);
                $this->db->insert(USERPOINTSWALLET,$insertArr);
            }

            $historyArr = array(
                'user_id'              => $user_id,
                'order_id'             => $order_id,
                'total_billing_amount' => $total_billing_amount,
                'actual_payment'       => $payment,
                'earn_point'           => $earnpoint,
                'use_point'            => $redeem_point,
                'floor_id'             => $floor_id,
                // 'withdrawal_credit'    => 'credit',
                'created_date'         => date('Y-m-d H:i:s')
                );
            $this->db->insert(USERWALLETHISTORY,$historyArr);
        }

        
    }


    public function withdrawalpoint($user_id,$order_id,$total_point,$total_billing_amount,$redeem_point,$floor_id){
        
        //subtract point from userwallet 
        $subtract_point = $total_point - $redeem_point;
        $updtwalletArr = array('total_point'=>$subtract_point);
            
        $option = array(
            'table' => USERPOINTSWALLET,
            'data' => $updtwalletArr,
            'where' => array('user_id' => $user_id)
        );
        $update = $this->Common_model->customUpdate($option);    
        if($update){
            
            $subtract_payment = $this->PointToMoney($redeem_point);
            $actual_payment = $total_billing_amount - $subtract_payment;
            $withdrawalArr = array(
                // 'user_id'           => $user_id,
                // 'order_id'          => $order_id,
                'total_billing_amount' => $total_billing_amount,
                'actual_payment'       => $actual_payment,
                'use_point'            => $redeem_point,
                'floor_id'             => $floor_id,
                'created_date'         => date('Y-m-d H:i:s')   
                );
            $this->db->where('order_id',$order_id);
            $this->db->update(USERWALLETHISTORY,$withdrawalArr);
            // $this->db->insert(USERWALLETHISTORY,$withdrawalArr);
        }
    }


    public function MoneyToPoint($payment,$store_id){
        $point = 0;
        
        $pointconfig = $this->db->get_where(POINTCONFIG,array('store_id'=>$store_id,'keyto'=>'money_to_point'))->row_array();
        
        if(!empty($pointconfig)){
            $value = $pointconfig['value'];
            $point = $value * $payment;
            $point = floor($point); 
        }
        return $point;
    }

    public function PointToMoney($point){
        $payment = 0;
        $pointconfig = $this->db->get_where(POINTCONFIG,array('keyto'=>'point_to_money'))->row_array();
        if(!empty($pointconfig)){
            $value = $pointconfig['value'];
            $point = $value * $point;
            $payment = floor($point); 
        }
        return $payment;
    }

    public function get_conversion_rate(){
        $value = '';
        $pointconfig = $this->db->get_where(POINTCONFIG,array('keyto'=>'point_to_money'))->row_array();
        
        if(!empty($pointconfig)){
            $value = $pointconfig['value'];
        }
        return $value;
    }

}
