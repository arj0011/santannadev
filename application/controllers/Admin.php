<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as admin management
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->load->helper('language');

        $this->uid = 1;
        //$this->uid = $this->session->userdata("id");
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
        $this->session_model->checkAdminSession();
        //$this->isLogin();
    }

    public function index() {
        if ($this->session->userdata('user_id') == TRUE) {
            redirect('admin/dashboard');
        } else {
            redirect('siteadmin');
        }
    }

    function isLogin() {
        if (!$this->session->userdata('id')) {
            redirect('siteadmin');
        }
    }

    function NotificationAdmin() {
        $dates = date('Y-m-d', mktime(0, 0, 0, date("m"), date("d") - 7, date("Y")));
        if ($this->session->userdata('id')) {
            if ($this->session->userdata('role') == 'admin'):
                $options = array(
                    'table' => 'users_notifications as notify',
                    'select' => 'notify.*,user.name,user.user_image',
                    'join' => array('users as user' => 'user.id=notify.sender_id'),
                    'where' => array(
                        'notify.notification_type' => 'ADMIN',
                        'notify.is_read' => 0,
                        'DATE(notify.sent_time) >=' => $dates
                    ),
                    'order' => array('notify.id' => 'DESC'),
                );
                $data['notification'] = $this->Common_model->customGet($options);
            else:
                $options = array(
                    'table' => 'users_notifications as notify',
                    'select' => 'notify.*,user.name,user.user_image',
                    'join' => array('users as user' => 'user.id=notify.sender_id',
                        'mw_booking as book' => 'book.id=notify.booking_id'),
                    'where' => array(
                        'notify.notification_type' => 'ADMIN',
                        'notify.is_read' => 0,
                        'book.agent_id' => $this->session->userdata('id')
                    ),
                    'order' => array('notify.id' => 'DESC'),
                );
                $id = $this->session->userdata('id');
                $sql = "SELECT notify.*,user.name,user.user_image"
                        . "  FROM users_notifications as notify"
                        . " INNER JOIN users as user ON user.id=notify.sender_id "
                        . " INNER JOIN mw_booking as book ON book.id=notify.booking_id"
                        . " WHERE notify.notification_type = 'ADMIN'"
                        . " AND notify.is_read = 0 AND book.agent_id = $id AND DATE(notify.sent_time) >= $dates";
                $sql .= " UNION ";
                $sql .= "SELECT notify.*,user.full_name as name,CONCAT('uploads/agents/',user.image) as user_image "
                        . " FROM users_notifications as notify"
                        . " INNER JOIN agents as user ON user.id=notify.agent_id "
                        . " WHERE notify.notification_type = 'ADMIN'"
                        . " AND notify.is_read = 0 AND notify.agent_id = $id AND DATE(notify.sent_time) >= $dates order by id DESC";

                $data['notification'] = $this->Common_model->customQuery($sql);
            endif;
            $this->load->view('admin/notification_list', $data);
        }else {
            echo 2;
        }
    }

    /**
     * Function Name: dashboard
     * Description:   To admin dashboard
     */
    public function dashboard() {
        $this->load->library('datatables');
        $data['parent'] = "dashboard";
        $where = '';
        if ($this->session->userdata('role') == 'agent') {
            $where = array('agent_id' => $this->session->userdata('id'));
        }
        $start_date = "";
        $end_date = "";
        if ($this->session->userdata('role_id') == 2):
            $start_date = date('Y-m-d');
            $end_date = date('Y-m-d', strtotime('+1 days'));
        endif;
        $data['list'] = $this->Common_model->booking($start_date, $end_date, 'mw_booking', $where);
        $option = array('table' => 'mw_rooms',
        );
        $data['floors'] = $this->Common_model->customGet($option);

        if (isset($_GET['floorplan'])):

            $this->db->select('t.* , s.image , s.canrotate, r.id AS roomId,r.name AS roomName, r.roomHeight,r.roomWidth,r.image as roomImage');
            $this->db->from('mw_tables as t');
            $this->db->join('mw_shapes as s', 't.type=s.id');
            $this->db->join('mw_rooms as r', 't.roomId=r.id');
            $this->db->where('r.id', $_GET['floorplan']);

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
            $data['tables'] = $columnArr;
        endif;

        if (!empty($_POST) && isset($_POST)) {
            $all = $this->input->post('allsection');
            if (empty($all)) {
                $this->form_validation->set_rules('floor_id', 'Section', 'required|trim');
            }
            $this->form_validation->set_rules('message', 'Message', 'required');
            if ($this->form_validation->run() == FALSE) {
                $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
                $this->template->load('default', 'auth/dashboard', $data);
            } else {
                $option = array('table' => 'mw_rooms',
                );
                if (empty($all)) {
                    $option['where'] = array('id' => $this->input->post('floor_id'));
                }
                $floors = $this->Common_model->customGet($option);
                if (!empty($floors)) {
                    foreach ($floors as $rows) {
                        $options_notification = array(
                            'table' => 'users_notifications',
                            'data' => array(
                                'sender_id' => $this->session->userdata('id'),
                                'reciever_id' => 0,
                                'notification_type' => 'ADMIN',
                                'title' => 'New Notification',
                                'message_en' => $this->input->post('message'),
                                'is_read' => 0,
                                'sent_time' => date('Y-m-d H:i:s'),
                                'is_send' => 1,
                                'booking_id' => 0,
                                'agent_id' => $rows->agent
                            )
                        );
                        $this->Common_model->customInsert($options_notification);
                    }
                }
                $this->session->set_flashdata('success', 'Successfully sent notification');
                redirect('admin/dashboard');
            }
        } else {
            $this->template->load('default', 'auth/dashboard', $data);
        }
    }

    public function booking_ajax() {
        $search = $this->input->post('searchstr');
        $limitOffset = $this->input->post('limitOffset');
        $booking_date = date('Y-m-d');
        $booking_type = $this->input->post('booking_type');
        if (!empty($booking_type) && $booking_type == 'current') {
            $where = array('booking.booking_date' => $booking_date);
        } else {
            $where = array();
        }

        if ($this->session->userdata('role') == 'agent') {
            $where['booking.agent_id'] = $this->session->userdata('id');
        }

        echo $this->Common_model->booking_ajax($search, null, null, $where);
    }

    public function exportCsvBooking($type = '') {

        $options = array(
            'table' => 'mw_booking',
            'order' => array('id' => 'DESC'),
        );
        if ($type == 'today') {
            $options['where'] = array('booking_date' => date('Y-m-d'));
        }
        if ($this->session->userdata('role') != 'admin') {
            $options['where']['agent_id'] = $this->session->userdata('id');
        }
        $bookings = $this->Common_model->customGet($options);
        $response = array();
        if (!empty($bookings)) {
            foreach ($bookings as $rows) {
                $temp['name'] = $rows->name;
                $temp['status'] = getStatusStr($rows->status);
                $temp['section'] = getFloorDetail($rows->floor_id);
                $temp['no_of_persons'] = $rows->no_of_persons;
                $temp['booking_date'] = dateFormateManage($rows->booking_date);
                $temp['time_from'] = timeFormateManage($rows->time_from);
                $temp['time_to'] = timeFormateManage($rows->time_to);
                $temp['email'] = $rows->email;
                $temp['mobile'] = $rows->mobile;
                $temp['referrer'] = $rows->referrer;
                $temp['comment'] = $rows->comment;

                $response[] = $temp;
            }
        }
        $array = array();


        $title = array(
            'Name',
            'STATUS',
            'Section',
            'No of persons',
            'Booking date',
            'Time From',
            'Time To',
            'Email',
            'Mobile',
            'Referrer',
            'Comment'
        );
        $array[] = $title;
        foreach ($response as $client) {
            $array[] = $client;
        }
        array_to_csv($array, 'All-Reservation-' . date('Y-m-d h:i A') . '.csv');
    }

    public function exportExcelBooking1($type = '') {

        $options = array(
            'table' => 'mw_booking',
            'order' => array('id' => 'DESC'),
        );
        if ($type == 'today') {
            $options['where'] = array('booking_date' => date('Y-m-d'));
        }
        $bookings = $this->Common_model->customGet($options);
        $response = array();
        if (!empty($bookings)) {
            foreach ($bookings as $rows) {
                $temp['name'] = $rows->name;
                $temp['email'] = $rows->email;
                $temp['mobile'] = $rows->mobile;
                $temp['section'] = getFloorDetail($rows->floor_id);
                $temp['no_of_persons'] = $rows->no_of_persons;
                $temp['booking_date'] = dateFormateManage($rows->booking_date);
                $temp['time_from'] = timeFormateManage($rows->time_from);
                $temp['time_to'] = timeFormateManage($rows->time_to);
                $temp['referrer'] = $rows->referrer;
                $temp['comment'] = $rows->comment;
                $temp['status'] = getStatusStr($rows->status);
                $response[] = $temp;
            }
        }
        $array = array();
        $title = array(
            'Name',
            'Email',
            'Mobile',
            'Section',
            'No of persons',
            'Booking date',
            'Time From',
            'Time To',
            'Referrer',
            'Comment',
            'STATUS'
        );
        $array[] = $title;
        foreach ($response as $client) {
            $array[] = $client;
        }
        array_to_excel($array, 'All-Reservation-' . date('Y-m-d h:i A') . '.xls');
    }

    public function exportExcelBooking($type = '') {
        $this->load->library('PHPExcel');
        $options = array(
            'table' => 'mw_booking',
            'order' => array('id' => 'DESC'),
        );
        if ($type == 'today') {
            $options['where'] = array('booking_date' => date('Y-m-d'));
        }
        if ($this->session->userdata('role') != 'admin') {
            $options['where']['agent_id'] = $this->session->userdata('id');
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

    /**
     * Function Name: changepassword
     * Description:   To change admin password view
     */
    public function changepassword() {
        $data['parent'] = "password";
        $data['error'] = "";
        $data['message'] = "";
        $this->template->load('default', 'auth/changepassword', $data);
    }

    /**
     * Function Name: logout
     * Description:   To admin logout
     */
    public function logout() {
        $this->session->sess_destroy();
        delete_cookie("siteadmin");
        redirect("siteadmin");
    }

    /**
     * Function Name: dochangepassword
     * Description:   To change admin password
     */
    public function change_password() {
        $data['parent'] = "Password";
        $data['title'] = "Password";
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[6]|max_length[12]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required|matches[new]');

        if ($this->form_validation->run() == false) {
            $this->template->load('default', 'auth/changepassword', $data);
        } else {
            if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin') {
                $results = $this->Common_model->getsingle(ADMIN, array('password' => md5($this->input->post('old'))));
            } else if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'agent'){
                $results = $this->Common_model->getsingle(AGENTS, array('password' => md5($this->input->post('old'))));
            }else if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'){
                $results = $this->Common_model->getsingle(STORE, array('password' => md5($this->input->post('old'))));
            }
            if (empty($results)) {
                $this->session->set_flashdata('error', lang('password_change_invalid_old'));
                redirect('admin/changepassword');
            }
            $pswdArr = array('password' => md5($this->input->post('new')));
            $where = array('id' => $this->session->userdata("id"));
            if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin') {
                if ($this->Common_model->updateFields(ADMIN, $pswdArr, $where)) {
                    $this->session->set_flashdata('success', lang('password_change_successful'));
                    redirect('admin/changepassword');
                } else {
                    $this->session->set_flashdata('error', lang('password_change_unsuccessful'));
                    redirect('admin/changepassword');
                }
            } else if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'agent') {
                if ($this->Common_model->updateFields(AGENTS, $pswdArr, array('id' => $this->session->userdata('id')))) {
                    $this->session->set_flashdata('success', lang('password_change_successful'));
                    redirect('admin/changepassword');
                } else {
                    $this->session->set_flashdata('error', lang('password_change_unsuccessful'));
                    redirect('admin/changepassword');
                }
            }else if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'){
                if ($this->Common_model->updateFields(AGENTS, $pswdArr, array('id' => $this->session->userdata('id')))) {
                    $this->session->set_flashdata('success', lang('password_change_successful'));
                    redirect('admin/changepassword');
                } else {
                    $this->session->set_flashdata('error', lang('password_change_unsuccessful'));
                    redirect('admin/changepassword');
                }
            }
        }
    }

    /**
     * Function Name: users
     * Description:   To Get All Users
     */
    public function users() {
        $data['parent'] = "users";
        $data['users'] = $this->Common_model->getAll(USERS);
        $this->template->load('default', 'user/users', $data);
    }

    /**
     * Function Name: export_users
     * Description:   To Export All Users
     */
    public function export_users() {
        $users = $this->Common_model->getAll(USERS, 'name', 'ASC');
        if ($users['total_count'] > 0) {
            $print_array = array();
            foreach ($users['result'] as $value) {
                $print_array[] = array('name' => $value->name, 'email' => $value->email, 'gender' => $value->gender, 'registration_date' => convertDateTime($value->created_date));
            }

            $filename = "users-" . date('d-F-Y-h-i-A') . ".csv";
            $fp = fopen('php://output', 'w');
            header('Content-type: application/csv');
            header('Content-Disposition: attachment; filename=' . $filename);
            fputcsv($fp, array('User Name', 'Email', 'Gender', 'Registration Date'));
            foreach ($print_array as $row) {
                fputcsv($fp, $row);
            }
        }
    }

    /**
     * Function Name: block_unblock
     * Description:   To Block/Unlock Users
     */
    public function block_unblock() {
        $user_id = decoding($this->input->get('id'));
        $flag = $this->input->get('type');
        if ($user_id) {
            $status = $this->Common_model->updateFields(USERS, array('is_blocked' => $flag), array('id' => $user_id));
            if ($status) {
                $success_msg = ($flag == 1) ? 'User blocked successfully' : 'User unblocked successfully';
                $this->session->set_flashdata('success', $success_msg);
            } else {
                $this->session->set_flashdata('error', NO_CHANGES);
            }
        } else {
            $this->session->set_flashdata('error', GENERAL_ERROR);
        }
        redirect('admin/users');
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

        $image_name = str_replace(" ", "_", $qrName) . '_' . time() . '.png';
        $params['data'] = $code;
        $params['level'] = 'H';
        $params['size'] = 10;
        $params['savename'] = FCPATH . $qr_code_config['imagedir'] . $image_name;
        $qr_code_image_url = base_url() . $qr_code_config['imagedir'] . $image_name;
        $this->ci_qr_code->generate($params);
        return $qr_code_image_url;
    }

    public function databse_backup() {
        ini_set('memory_limit', '1020M');
        $this->load->dbutil();
        $prefs = array(
            'format' => 'sql',
            'filename' => 'feedback_clone.sql'
        );
        $backup = $this->dbutil->backup($prefs);
        $db_name = 'SANTANNA-DB-BACKUP-' . date("Y-m-d") . '-' . time() . '.sql';
        $this->load->helper('download');
        $this->load->helper('file');
        write_file('database-cron/' . $db_name, $backup);
        exit();
    }

    public function current_booking_old() {
        $this->data['parent'] = "dashboard";

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

        $this->data['allfloors'] = $this->db->get('mw_rooms')->result_array();


        $this->form_validation->set_rules('name', 'Full name', 'required|trim|xss_clean');
        $this->form_validation->set_rules('no_of_persons', 'No. of Person', 'required|trim|xss_clean');
        $this->form_validation->set_rules('floor', 'Location', 'required|trim|xss_clean');


        if ($this->form_validation->run() == FALSE) {
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->template->load('default', 'auth/current_booking', $this->data);
        } else {

            $bookingInsert = array();
            $where_id = '';
            $booking_id = '';
            $email = $this->input->post('email');
            $full_name = $this->input->post('name');
            $start_time = date('h:i:s');
            $booking_date = date('Y-m-d');
            $timestamp = strtotime($start_time) + 60 * 60;
            $end_time = date('h:i:s', $timestamp);

            $table_id = $this->input->post('tables');


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
                $option = array('table' => USERS,
                    'data' => array(
                        'name' => ucwords($this->input->post('name')),
                        'email' => $this->input->post('email'),
                        'password' => md5(123456),
                        'phone_number' => $this->input->post('mobile'),
                        'created_date' => date('Y-m-d H:i:s'),
                        'is_verified' => 1,
                        'is_social_signup' => 0,
                        'date_of_birth' => '0000-00-00'
                    ),
                );
                $bookingInsert['user_id'] = $this->Common_model->customInsert($option);
            }
            $bookingInsert['floor_id'] = $this->input->post('floor');
            $bookingInsert['booking_date'] = $booking_date;
            $bookingInsert['time_from'] = $start_time;
            $bookingInsert['time_to'] = $end_time;
            $bookingInsert['no_of_persons'] = $this->input->post('no_of_persons');
            $bookingInsert['name'] = $this->input->post('name');
            $bookingInsert['mobile'] = $this->input->post('mobile');
            $bookingInsert['email'] = $this->input->post('email');
            $bookingInsert['status'] = 1;
            $bookingInsert['created'] = date('Y-m-d H:i:s');

            $booking_id = $this->Common_model->insertData('mw_booking', $bookingInsert);
            if ($booking_id) {

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

                for ($i = 0; $i < count($table_id); $i++) {

                    $option1 = array(
                        'booking_id' => $booking_id,
                        'table_id' => $table_id[$i],
                    );
                    $option = array('table' => 'mw_booking_tables', 'data' => $option1);
                    $this->Common_model->customInsert($option);
                }

                $this->session->set_flashdata('success', 'Booking added');
                redirect('admin/current_booking');
                log_message('error', "BOOKING CREATE id :" . $booking_id . " : create date:" . date('Y-m-d'));
            }
        }
    }

    public function current_booking() {
        $this->data['parent'] = "dashboard";
        $this->data['url'] = base_url() . 'admin/current_booking';
        $this->data['pageTitle'] = 'Walk In Booking';
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

        // $this->data['allfloors'] = $this->db->get('mw_rooms')->result_array();
        $this->data['allfloors'] = $this->Common_model->floorByRole();



        $this->form_validation->set_rules('no_of_persons', 'No. of Person', 'required|trim|xss_clean');
        $this->form_validation->set_rules('floor', 'Location', 'required|trim|xss_clean');
        $this->form_validation->set_rules('email', 'Email', 'required|trim|xss_clean');


        if ($this->form_validation->run() == FALSE) {
            $this->form_validation->set_error_delimiters('<div class="error">', '</div>');
            $this->template->load('default', 'auth/current_booking', $this->data);
        } else {

            $bookingInsert = array();
            $where_id = '';
            $booking_id = '';
            $email = $this->input->post('email');
            $full_name = $this->input->post('name');
            $start_time = date('h:i:s');
            $booking_date = date('Y-m-d');
            $timestamp = strtotime($start_time) + 60 * 60;
            $end_time = date('h:i:s', $timestamp);
            $table_id = $this->input->post('tables');

            $user_name = substr($email, 0, strrpos($email, '@'));


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
                $option = array('table' => USERS,
                    'data' => array(
                        'name' => ucwords($user_name),
                        'email' => $this->input->post('email'),
                        'password' => md5(123456),
                        'phone_number' => $this->input->post('mobile'),
                        'created_date' => date('Y-m-d H:i:s'),
                        'is_verified' => 1,
                        'is_social_signup' => 0,
                        'date_of_birth' => '0000-00-00'
                    ),
                );
                $bookingInsert['user_id'] = $this->Common_model->customInsert($option);
            }
            $bookingInsert['floor_id'] = $this->input->post('floor');
            $bookingInsert['booking_date'] = $booking_date;
            $bookingInsert['time_from'] = $start_time;
            $bookingInsert['time_to'] = $end_time;
            $bookingInsert['no_of_persons'] = $this->input->post('no_of_persons');
            $bookingInsert['name'] = $user_name;
            $bookingInsert['mobile'] = $this->input->post('mobile');
            $bookingInsert['email'] = $this->input->post('email');
            $bookingInsert['status'] = 1;
            $bookingInsert['created'] = date('Y-m-d H:i:s');

            $booking_id = $this->Common_model->insertData('mw_booking', $bookingInsert);
            if ($booking_id) {

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

                for ($i = 0; $i < count($table_id); $i++) {

                    $option1 = array(
                        'booking_id' => $booking_id,
                        'table_id' => $table_id[$i],
                    );
                    $option = array('table' => 'mw_booking_tables', 'data' => $option1);
                    $this->Common_model->customInsert($option);
                }

                $this->session->set_flashdata('success', 'Booking added');
                redirect('admin/current_booking');
                log_message('error', "BOOKING CREATE id :" . $booking_id . " : create date:" . date('Y-m-d'));
            }
        }
    }

}

/* End of file Admin.php */
/* Location: ./application/controllers/Admin.php */
