<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Common extends CI_Controller {

    public function getRecords() {
        $table = $this->input->post('table');
        $where = array($this->input->post('column') => $this->input->post('value'));
        $result = $this->Common_model->getAllwhere($table, $where);
        echo json_encode($result);
    }

    public function deleteRecords() {
        $table = $this->input->post('table');
        $where = array($this->input->post('column') => $this->input->post('value'));
        if ($this->Common_model->deleteData($table, $where)) {
            echo json_encode(array('type' => 'success', 'msg' => 'Record deleted successfully'));
            exit;
        } else {
            echo json_encode(array('type' => 'failed', 'msg' => 'Failed please try again !'));
            exit;
        }
    }

}
