<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class NewsCategory extends Common_Controller {
    public $data = array();
    public $file_data = "";
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
                $this->data['url'] = base_url().'newsCategory';
        $this->data['pageTitle'] = lang('news_category');
        $this->data['parent'] = "News Category";
        $option = array('table' => NEWS_CATEGORY,'select' => 'category_name_en as category_name,category_image,id,company_id,language_id',
             'order'=> array('id'=>'DESC')
            );
       
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'category_name_el as category_name,category_image,id,company_id,language_id';
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
        $this->data['title'] = lang("add_news_category");
        $this->load->view('add', $this->data);
    }

    /**
     * @method news_category_add
     * @description add dynamic rows
     * @return array
     */
    public function news_category_add() {
        
        $this->form_validation->set_rules('category_name_en', lang('category_name_en'), 'required|trim');
        $this->form_validation->set_rules('category_name_el', lang('category_name_el'), 'required|trim');
        if ($this->form_validation->run() == true) {
            
                $this->filedata['status'] = 1;
                $image = "";
                if (!empty($_FILES['news_image']['name'])) {
                    $this->filedata = $this->commonUploadImage($_POST, 'news', 'news_image');
                    if ($this->filedata['status'] == 1) {
                     $image = $this->filedata['upload_data']['file_name'];
                    }
                }

                if ($this->filedata['status'] == 0) {
                   $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                   $options_data = array(
                        'company_id' => $this->_uid,
                        'category_name_en' => $this->input->post('category_name_en'),
                        'category_name_el' => $this->input->post('category_name_el'),
                        'category_image' => $image
                    );
                    $option = array('table' => NEWS_CATEGORY, 'data' => $options_data);
                    if ($this->Common_model->customInsert($option)) {
                        $response = array('status' => 1, 'message' => lang('news_category_success'), 'url' => base_url('newsCategory'));
                    } else {
                        $response = array('status' => 0, 'message' => lang('news_category_failed'));
                    } 
                }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }

    /**
     * @method news_category_edit
     * @description edit dynamic rows
     * @return array
     */
    public function news_category_edit() {
        $this->data['title'] = lang("edit_news_category");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {
            $option = array(
                'table' => NEWS_CATEGORY,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('newsCategory');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('newsCategory');
        }
    }

    /**
     * @method news_category_update
     * @description update dynamic rows
     * @return array
     */
    public function news_category_update() {

        $this->form_validation->set_rules('category_name_en', lang('category_name_en'), 'required|trim');
        $this->form_validation->set_rules('category_name_el', lang('category_name_el'), 'required|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
                $this->filedata['status'] = 1;
                $image = $this->input->post('exists_image');
                if (!empty($_FILES['news_image']['name'])) {
                    $this->filedata = $this->commonUploadImage($_POST, 'news', 'news_image');
                    if ($this->filedata['status'] == 1) {
                     $image = $this->filedata['upload_data']['file_name'];
                     delete_file($this->input->post('exists_image'), FCPATH."uploads/news/");
                    }
                }
                 if ($this->filedata['status'] == 0) {
                    $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                    
                    $options_data = array(
                            'category_name_en' => $this->input->post('category_name_en'),
                            'category_name_el' => $this->input->post('category_name_el'),
                            'category_image' => $image
                    );
                    $option = array(
                        'table' => NEWS_CATEGORY,
                        'data' => $options_data,
                        'where' => array('id' => $where_id)
                    );
                    $update = $this->Common_model->customUpdate($option);
                    $response = array('status' => 1, 'message' => lang('news_category_success_update'), 'url' => base_url('newsCategory'));
                }
        endif;

        echo json_encode($response);
    }

}
