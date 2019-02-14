<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Floors extends Common_Controller {

    public $data = array();
    public $file_data = "";

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->data['url'] = base_url().'floors';
        $this->data['pageTitle'] = lang('locations');
        $this->data['parent'] = "Floor";
        $option = array('table' => 'mw_rooms', 'select' => '*');
        if ($this->session->userdata('id') != '' && $this->session->userdata('role') == 'agent'){
            $option['where'] = array('agent' => $this->session->userdata('id'));
        }
        else if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'store'){
            $option['where'] = array('store' => $this->session->userdata('id'));
        }
        /* if (getDefaultLanguage() == "el") {
          $option['select'] = 'full_name,image,id,email,date_of_birth,phone_number,create_date,is_active';
          } */
        $this->data['list'] = $this->Common_model->customGet($option);
        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

    function open_model() {
        $this->data['title'] = lang("add_location");
        $option = array('table' => AGENTS, 'select' => 'id,full_name');
        $this->data['agentslist'] = $this->Common_model->customGet($option);
        $options = array('table' => STORE, 'select' => 'id,store_name');
        $this->data['storeslist'] = $this->Common_model->customGet($options);
        $this->load->view('add', $this->data);
    }

    public function floors_add() {
        $this->form_validation->set_rules('name', 'Floor name', 'required|trim|xss_clean');
        /* $this->form_validation->set_rules('roomWidth', 'Floor width', 'required|trim|xss_clean');
          $this->form_validation->set_rules('roomHeight', 'Floor height', 'required|trim|xss_clean'); */
        $this->form_validation->set_rules('agent', 'Agent', 'required|trim|xss_clean');
        $this->form_validation->set_rules('store', 'Store', 'required|trim|xss_clean');
        if ($this->form_validation->run() == true) {
            $image = "";
            if (!empty($_FILES['image']['name'])) {
                $uploaddata = $this->commonUploadImage($_POST, 'floors', 'image');
                if ($uploaddata['status'] == 1) {
                    $image = $uploaddata['upload_data']['file_name'];
                }
            }
            /* if ($uploaddata['status'] == 0) {
              $response = array('status' => 0, 'message' => 'Room image not Uploaded');
              }else{ */
            if ($image == ""):
                $image = IMAGENAME;
            endif;
            $roomWidth = ($this->input->post('roomWidth') != "") ? $this->input->post('roomWidth') : LOCATIONWIDTH;
            $roomHeight = ($this->input->post('roomHeight') != "") ? $this->input->post('roomHeight') : LOCATIONHEIGHT;
            $options_data = array(
                'name' => $this->input->post('name'),
                'roomWidth' => $roomWidth,
                'roomHeight' => $roomHeight,
                'agent' => $this->input->post('agent'),
                'store' => $this->input->post('store'),
                'image' => $image
            );
            $option = array('table' => 'mw_rooms', 'data' => $options_data);
            if ($this->Common_model->customInsert($option)) {
                $response = array('status' => 1, 'message' => 'Floor added.', 'url' => base_url('floors'));
            } else {
                $response = array('status' => 0, 'message' => 'Floor added failed');
            }
            //}  
        } else {
            /* $messages = (validation_errors()) ? validation_errors() : '';
              $response = array('status' => 0, 'message' => 'All fields are Required'); */

            $requireds = strip_tags($this->form_validation->error_string());
            $messages = explode("\n", trim($requireds, "\n"));
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    public function table($roomId) {
        $this->data['parent'] = "Table";

        $option = array('table' => 'mw_rooms', 'select' => '*');
        if ($this->session->userdata('role') == 'agent') {
            $option['where'] = array('agent' => $this->session->userdata('id'));
        }
        $this->data['rooms'] = $this->Common_model->customGet($option);
        $this->data['roomDetail'] = $this->Common_model->getSingleRecordById('mw_rooms', array('id' => $roomId));
        $this->data['shapes'] = $this->Common_model->customGet(array('table' => 'mw_shapes', 'select' => '*'));
        $this->data['roomId'] = $roomId;

        $this->db->select('t.* , s.image , s.canrotate');
        $this->db->from('mw_tables as t');
        $this->db->join('mw_shapes as s', 't.type=s.id');
        $this->db->where('roomId', $roomId);
        $this->data['tables'] = $this->db->get()->result();
        $this->template->load_view('default', 'table', $this->data, 'inner_script');
    }

    public function saveTablePlan() {
        $data = json_decode($_POST['myData']);
        $updateRoom['roomWidth'] = $data->roomWidth;
        $updateRoom['roomHeight'] = $data->roomHeight;
        $this->Common_model->updateFields('mw_rooms', $updateRoom, array('id' => $data->roomId));

        foreach ($data->deleted as $deleteTable) {
            $this->Common_model->deleteData('mw_tables', $deleteTable);
        }

        $return['NewTables'] = array();
        foreach ($data->tables as $table) {
            $tableInsert['type'] = $table->type;
            $tableInsert['seats'] = $table->seats;
            $tableInsert['priority'] = $table->priority;
            $tableInsert['top'] = $table->top;
            $tableInsert['left'] = $table->left;
            $tableInsert['name'] = $table->name;
            $tableInsert['rotation'] = isset($table->rotation) ? $table->rotation : "0";
            $tableInsert['roomId'] = $data->roomId;
            if ($table->tableId != '') {
                $this->Common_model->updateFields('mw_tables', $tableInsert, array('id' => $table->tableId));
            } else {
                $return['NewTables'][] = $this->Common_model->insertData('mw_tables', $tableInsert);
            }
        }
        $return['Status'] = 1;
        echo json_encode(array($return));
    }

    public function floors_edit() {
        $this->data['title'] = lang("edit_location");
        ;
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {
            $option = array(
                'table' => 'mw_rooms',
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            if (!empty($results_row)) {
                $opt = array('table' => AGENTS, 'select' => 'id,full_name');
                $this->data['agentslist'] = $this->Common_model->customGet($opt);

                $op = array('table' => STORE, 'select' => 'id,store_name');
                $this->data['storeslist'] = $this->Common_model->customGet($op);

                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('floors');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('floors');
        }
    }

    public function floors_update() {

        $this->form_validation->set_rules('name', 'Name', 'required|trim|xss_clean');
        /* $this->form_validation->set_rules('roomWidth', 'Floor Width', 'required|trim|xss_clean');
          $this->form_validation->set_rules('roomHeight', 'Floor Height', 'required|trim|xss_clean'); */
        $this->form_validation->set_rules('agent', 'Agent', 'required|trim|xss_clean');
        $this->form_validation->set_rules('store', 'Store', 'required|trim|xss_clean');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE) {
            /* $messages = (validation_errors()) ? validation_errors() : '';
              $response = array('status' => 0, 'message' => $messages); */
            $requireds = strip_tags($this->form_validation->error_string());
            $messages = explode("\n", trim($requireds, "\n"));
            $response = array('status' => 0, 'message' => $messages);
        } else {
            $uploaddata = array();
            $uploaddata['status'] = 1;
            $image = $this->input->post('exists_image');
            if (!empty($_FILES['file']['name'])) {
                $uploaddata = $this->commonUploadImage($_POST, 'floors', 'file');
                if ($uploaddata['status'] == 1) {
                    $image = $uploaddata['upload_data']['file_name'];
                    if ($this->input->post('exists_image') != 'Location.jpg'):
                        delete_file($this->input->post('exists_image'), FCPATH . "uploads/floors/");
                    endif;
                }
            }
            /* if ($uploaddata['status'] == 0) {
              $response = array('status' => 0, 'message' => $uploaddata['upload_data']['error']);
              } else { */
            $roomWidth = ($this->input->post('roomWidth') != "") ? $this->input->post('roomWidth') : LOCATIONWIDTH;
            $roomHeight = ($this->input->post('roomHeight') != "") ? $this->input->post('roomHeight') : LOCATIONHEIGHT;
            $options_data = array(
                'name' => $this->input->post('name'),
                'roomWidth' => $roomWidth,
                'roomHeight' => $roomHeight,
                'agent' => $this->input->post('agent'),
                'store' => $this->input->post('store'),
                'image' => $image
            );

            $option = array(
                'table' => 'mw_rooms',
                'data' => $options_data,
                'where' => array('id' => $where_id)
            );
            $update = $this->Common_model->customUpdate($option);
            $response = array('status' => 1, 'message' => 'Floor updated', 'url' => base_url('floors'));
            //}
        }
        echo json_encode($response);
    }

    public function getTablePlan() {
        $floor_id = $this->input->post('floor_id');
        if (!empty($floor_id)) {
            $option = array(
                'table' => 'mw_tables',
                'where' => array('roomId' => $floor_id),
            );
            $data['list'] = $this->Common_model->customGet($option);
            $data['floor_id'] = $floor_id;
            if (!empty($data['list'])) {
                $this->load->view('floor_table_list', $data);
            } else {
                echo 1;
            }
        } else {
            echo 1;
        }
    }

    public function deleteTables() {
        $table_id = $this->input->post('table_id');
        $floor_id = $this->input->post('floor_id');
        if (!empty($table_id)) {

            $option = array(
                'table' => 'mw_booking_tables',
                'select' => 'mw_booking_tables.id',
                'join' => array('mw_booking' => 'mw_booking.id=mw_booking_tables.booking_id'),
                'where' => array('mw_booking_tables.table_id' => $table_id, 'mw_booking.booking_date' => date('Y-m-d'))
            );
            $is_booking = $this->Common_model->customGet($option);
            if (empty($is_booking)) {
                $option = array(
                    'table' => 'mw_tables',
                    'where' => array('id' => $table_id),
                );
                $delete = $this->Common_model->customDelete($option);
                if ($delete) {
                    $option = array(
                        'table' => 'mw_tables',
                        'where' => array('roomId' => $floor_id),
                    );
                    $data['list'] = $this->Common_model->customGet($option);
                    $data['floor_id'] = $floor_id;
                    $this->load->view('tables_list', $data);
                } else {
                    echo 1;
                }
            } else {
                echo 2;
            }
        } else {
            echo 1;
        }
    }

}
