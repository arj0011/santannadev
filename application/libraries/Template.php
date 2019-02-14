<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Template {

    var $ci;

    function __construct() {
        $this->ci = & get_instance();
    }

    function load($tpl_view, $body_view = null, $data = null) {


        if (!is_null($body_view)) {

            if (file_exists(APPPATH . 'views/admin/' . $body_view . '.php')) {
                $body_view_path = $body_view . '.php';
            }


            $body = $this->ci->load->view('admin/' . $body_view_path, $data, TRUE);

            if (is_null($data)) {
                $data = array('body' => $body);
            } else if (is_array($data)) {
                $data['body'] = $body;
                $data['param'] = $data;
            } else if (is_object($data)) {
                $data->body = $body;
            }
        }

        $this->ci->load->view('admin/includes/templates/' . $tpl_view, $data);
    }
    
    function load_view($tpl_view, $body_view = null, $data = null,$script=null) {


        if (!is_null($body_view)) {

            //if (file_exists(APPPATH . '/' . $body_view . '.php')) {
                $body_view_path = $body_view . '.php';
            //}


            $body = $this->ci->load->view($body_view_path, $data, TRUE);

            if (is_null($data)) {
                $data = array('body' => $body);
            } else if (is_array($data)) {
                $data['body'] = $body;
                $data['param'] = $data;
            } else if (is_object($data)) {
                $data->body = $body;
            }
        }

        $this->ci->load->view('admin/includes/templates/' . $tpl_view, $data);
        if($script != null){
           $this->ci->load->view($script); 
        }
    }

}
