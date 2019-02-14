<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Session_model Extends CI_Model {

    public function checkAdminSession() {
        if ($this->session->userdata('id') == TRUE) {
            $activity = $this->session->userdata('user_activity');
            $max_time = 6000; // 10 minutes
            $current_time = time() - $activity;
            if ($current_time > $max_time) {
                $this->session->unset_userdata('id');
                $this->session->set_flashdata('session_expire', 'Session expired, please login again.');
                //redirect('siteadmin');
            } else {
                $this->session->set_userdata('user_activity', time());
            }
        } else {
            //redirect('siteadmin');
        }
    }

    public function checkUserSession() {
        if ($this->session->userdata('user_id') == TRUE) {
            $this->session->set_userdata('user_activity', time());
        } else {
            redirect('/');
        }
    }

}

?>