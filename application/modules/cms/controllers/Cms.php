<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cms extends Common_Controller {

    public $data = array();
    public $file_data = "";
    public $_table = CMS;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['url'] = base_url() . 'cms';
        $this->data['pageTitle'] = lang('cms');
        $this->data['parent'] = "Cms";
        $option = array('table' => $this->_table, 'select' => 'page_id,description_en as description,image,cms_id');
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'page_id,description_el as description,image,cms_id';
        }
        $this->data['list'] = $this->Common_model->customGet($option);
        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

    /**
     * @method open_model
     * @description load model box
     * @return array
     */
    function open_model() {
        $this->data['title'] = lang("add_cms");

        // $option = array('table' => SERVICE,'select' => 'store_name_en as store_name,store_id');
        // if(getDefaultLanguage() == "el"){
        //     $option['select'] = 'store_name_el as store_name,store_id';
        // }
        // $this->data['results'] = $this->Common_model->customGet($option);

        $this->load->view('add', $this->data);
    }

    /**
     * @method cms_add
     * @description add dynamic rows
     * @return array
     */
    public function cms_add() {

        $this->form_validation->set_rules('page_id', lang('page_id'), 'required|trim');
        $this->form_validation->set_rules('description_en', lang('description_en'), 'required|trim');
        $this->form_validation->set_rules('description_el', lang('description_el'), 'required|trim');



        if ($this->form_validation->run() == true) {


            $this->filedata['status'] = 1;
            $image = "";
            if (!empty($_FILES['image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'cms', 'image');
                if ($this->filedata['status'] == 1) {
                    $image = $this->filedata['upload_data']['file_name'];
                    $full_path = $this->filedata['upload_data']['full_path'];
                    $folder = "cms/thumb";
                    $this->resizeNewImage($full_path, $folder, 480, 828);
                }
            }

            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {
                $page_id = $this->input->post('page_id');
                $option = array(
                    'table' => $this->_table,
                    'where' => array('page_id' => $page_id)
                );
                $page = $this->Common_model->customGet($option);
                if (empty($page)) {
                    $options_data = array(
                        //'company_id' => $this->_uid,
                        'page_id' => $this->input->post('page_id'),
                        'description_en' => $this->input->post('description_en'),
                        'description_el' => $this->input->post('description_el'),
                        'image' => $image,
                        'create_date' => datetime(),
                        'is_active' => 1,
                    );

                    $option = array('table' => $this->_table, 'data' => $options_data);
                    if ($this->Common_model->customInsert($option)) {


                        $response = array('status' => 1, 'message' => lang('cms_success'), 'url' => base_url('cms'));
                    } else {
                        $response = array('status' => 0, 'message' => lang('cms_failed'));
                    }
                } else {
                    $response = array('status' => 0, 'message' => lang('page_exist'));
                }
            }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method cms_edit
     * @description edit dynamic rows
     * @return array
     */
    public function cms_edit() {
        $this->data['title'] = lang("edit_cms");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {

            $option = array(
                'table' => $this->_table,
                'where' => array('cms_id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('cms');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('cms');
        }
    }

    /**
     * @method cms_update
     * @description update dynamic rows
     * @return array
     */
    public function cms_update() {

        //$this->form_validation->set_rules('page_id', lang('page_id'), 'required|trim');
        $this->form_validation->set_rules('description_en', lang('description_en'), 'required|trim');
        $this->form_validation->set_rules('description_el', lang('description_el'), 'required|trim');

        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
            $this->filedata['status'] = 1;
            $image = $this->input->post('exists_image');

            if (!empty($_FILES['image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'cms', 'image');

                if ($this->filedata['status'] == 1) {
                    $image = $this->filedata['upload_data']['file_name'];
                    $full_path = $this->filedata['upload_data']['full_path'];
                    $folder = "cms/thumb";
                    $this->resizeNewImage($full_path, $folder, 480, 828);
                    delete_file($this->input->post('exists_image'), FCPATH . "uploads/cms/");
                    delete_file($this->input->post('exists_image'), FCPATH . "uploads/cms/thumb/");
                }
            }

            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {

                $options_data = array(
                    //'company_id' => $this->_uid,
                    //'page_id' => $this->input->post('page_id'),
                    'description_en' => $this->input->post('description_en'),
                    'description_el' => $this->input->post('description_el'),
                    'image' => $image
                );
                $option = array(
                    'table' => $this->_table,
                    'data' => $options_data,
                    'where' => array('cms_id' => $where_id)
                );
                $update = $this->Common_model->customUpdate($option);
                $response = array('status' => 1, 'message' => lang('cms_success_update'), 'url' => base_url('cms'));
            }

        endif;

        echo json_encode($response);
    }

}
