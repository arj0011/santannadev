<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Appinfo extends Common_Controller {

    public $data = array();
    public $file_data = "";
    public $_table = ADMIN;

    public function __construct() {
        parent::__construct();
    }

    /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['parent'] = "App Content";
        $this->data['url'] = base_url().'appinfo';
        $this->data['pageTitle'] = lang('app_content');
        $option = array('table' => $this->_table,
            'select' => 'id,company_name,company_image,ceo_image,news_event_image,ceo_message_en as ceo_message,phone_number,email,company_logo',
            'where' => array('id' => $this->_uid)
        );
        if (getDefaultLanguage() == "el") {
            $option['select'] = 'id,company_name,company_image,ceo_image,news_event_image,ceo_message_el as ceo_message,phone_number,email,company_logo';
        }
        $this->data['list'] = $this->Common_model->customGet($option);
        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

    /**
     * @method appinfo_edit
     * @description edit dynamic rows
     * @return array
     */
    public function appinfo_edit() {
        $this->data['title'] = lang("edit_app_content");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {
            $option = array(
                'table' => $this->_table,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('appinfo');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('appinfo');
        }
    }

    /**
     * @method service_category_update
     * @description update dynamic rows
     * @return array
     */
    public function appinfo_update() {


        $this->form_validation->set_rules('ceo_message_en', lang('app_mayor_greeting_en'), 'required|trim');
        $this->form_validation->set_rules('ceo_message_el', lang('app_mayor_greeting_el'), 'required|trim');
        $this->form_validation->set_rules('phone_number', lang('app_phone'), 'required|trim');
        $this->form_validation->set_rules('email', lang('app_email'), 'required|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
            $this->filedata['status'] = 1;
            $ceo_image = "";
            if (!empty($_FILES['ceo_image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'app', 'ceo_image');

                if ($this->filedata['status'] == 1) {
                    $ceo_image = $this->filedata['upload_data']['file_name'];
                }
            }
            $app_news_main_image = "";
            if (!empty($_FILES['app_news_main_image']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'app', 'app_news_main_image');
                if ($this->filedata['status'] == 1) {
                    $app_news_main_image = $this->filedata['upload_data']['file_name'];
                }
            }
            $app_logo = "";
            if (!empty($_FILES['app_logo']['name'])) {
                $this->filedata = $this->commonUploadImage($_POST, 'app', 'app_logo');
                if ($this->filedata['status'] == 1) {
                    $app_logo = $this->filedata['upload_data']['file_name'];
                }
            }

            $old_img = $this->input->post('exisisCompanyImage');
            $company_images = array();
            if (!empty($old_img)) {
                $company_images = $old_img;
            }

            if ($_FILES['files']) {
              
                for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
                    if ($_FILES['files']['name'][$i]) {
                        $up = $_FILES['files']['tmp_name'][$i];

                    
                        if (is_uploaded_file($up)) {
                            
                            
                            $company_image = $_FILES['files']['name'][$i];

                           
                            $f_extension = explode('.', $company_image); //To breaks the string into array
                            $f_extension = strtolower(end($f_extension)); //end() is used to retrun a last element to the array
                            $f_newfile = "";
                            $company_image = uniqid() . '.' . $f_extension;
                           
                            move_uploaded_file($up, "uploads/app/" . $company_image);


                            $full_path =  FCPATH."uploads/app/".$company_image;
                           
                            $folder = "app/thumb";
                            $this->resizeNewImage($full_path,$folder,440,828);
                           

                            array_push($company_images, $company_image);
                             
                      
                        }
                    }
                }
            }

            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {

                $options_data = array(
                    'ceo_message_en' => $this->input->post('ceo_message_en'),
                    'ceo_message_el' => $this->input->post('ceo_message_el'),
                    'phone_number' => $this->input->post('phone_number'),
                    'email' => $this->input->post('email'),
                );
                if (!empty($company_images)) {
                    $options_data['company_image'] = json_encode($company_images);
                }
                if (!empty($ceo_image)) {
                    $options_data['ceo_image'] = $ceo_image;
                }
                if (!empty($app_news_main_image)) {
                    $options_data['news_event_image'] = $app_news_main_image;
                }
                if (!empty($app_logo)) {
                    $options_data['company_logo'] = $app_logo;
                }
                $option = array(
                    'table' => $this->_table,
                    'data' => $options_data,
                    'where' => array('id' => $this->_uid)
                );
                $update = $this->Common_model->customUpdate($option);
                $response = array('status' => 1, 'message' => lang('appinfo_update_success'), 'url' => base_url('appinfo'));
            }
        endif;

        echo json_encode($response);
    }

    /**
     * @method info
     * @description listing display
     * @return array
     */
    public function info() {
        $this->data['parent'] = "app_info";
        $option = array('table' => COMPANY_INFO . ' as info',
            'select' => 'info.id,info.legal_text_en as legal_text,info.version,info.copyright,info.contact_images,info.info_files,info.contact_title_en as contact_title,' . $this->_table . '.company_name',
            'where' => array('info.company_id' => $this->_uid),
            'join' => array($this->_table => $this->_table . '.id=info.company_id')
        );

        if (getDefaultLanguage() == "el") {
            $option['select'] = 'info.id,info.legal_text_el as legal_text,info.version,info.copyright,info.contact_images,info.info_files,info.contact_title_el as contact_title,' . $this->_table . '.company_name';
        }
        $this->data['list'] = $this->Common_model->customGet($option);
        $this->template->load_view('default', 'list-info', $this->data, 'inner_script');
    }

    /**
     * @method open_model_appinfo
     * @description open add app info model
     * @return array
     */
    public function open_model_appinfo() {
        $this->data['title'] = lang('add_app_info');
        $this->load->view('add-info', $this->data);
    }

    /**
     * @method add_app_info
     * @description add dynamic rows
     * @return array
     */
    public function add_app_info() {
        $this->form_validation->set_rules('legal_text_en', lang('legal_text_en'), 'required|trim');
        $this->form_validation->set_rules('legal_text_el', lang('legal_text_el'), 'required|trim');
        $this->form_validation->set_rules('version', lang('version'), 'required|trim');
        $this->form_validation->set_rules('copyright', lang('copyright'), 'required|trim');
        $this->form_validation->set_rules('contact_title_en', lang('contact_title_en'), 'required|trim');
        $this->form_validation->set_rules('contact_title_el', lang('contact_title_el'), 'required|trim');

        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
            $this->filedata['status'] = 1;
            
            $company_images = array();
            if (!empty($_FILES['files']['name'])) {
                for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
                    if ($_FILES['files']['name'][$i]) {
                        $up = $_FILES['files']['tmp_name'][$i];
                        if (is_uploaded_file($up)) {
                            $company_image = $_FILES['files']['name'][$i];
                            $f_extension = explode('.', $company_image); //To breaks the string into array
                            $f_extension = strtolower(end($f_extension)); //end() is used to retrun a last element to the array
                            $f_newfile = "";
                            $company_image = uniqid() . '.' . $f_extension;
                            move_uploaded_file($up, "uploads/app/" . $company_image);
                            array_push($company_images, $company_image);
                        }
                    }
                }
            }
            $info_files = array();
            $info_file_titles = array();
            if (isset($_FILES['info_file']['name'])) {
                    for ($i = 0; $i < count($_FILES['info_file']['name']); $i++) {
                        if (!empty($_FILES['info_file']['name'][$i])) {
                            $up = $_FILES['info_file']['tmp_name'][$i];
                            if (is_uploaded_file($up)) {
                                $info = $_FILES['info_file']['name'][$i];
                                $f_extension = explode('.', $info); //To breaks the string into array
                                $f_extension = strtolower(end($f_extension)); //end() is used to retrun a last element to the array
                                $f_newfile = "";
                                //Give unique name to file
                                $info = uniqid() . '.' . $f_extension;
                                $ext = pathinfo($info, PATHINFO_EXTENSION);

                                move_uploaded_file($up, "uploads/app/" . $info);
                                array_push($info_files, $info);
                                array_push($info_file_titles, $_POST['info_file_title'][$i]);
                            }
                        }
                    }
            }
            $information_files = array();
            for ($i = 0; $i < count($info_files); $i++) {
                array_push($information_files, array('title' => $info_file_titles[$i], 'filename' => $info_files[$i]));
            }

            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {

                $options_data = array(
                    'legal_text_en' => $this->input->post('legal_text_en'),
                    'legal_text_el' => $this->input->post('legal_text_el'),
                    'version' => $this->input->post('version'),
                    'copyright' => $this->input->post('copyright'),
                    'contact_title_en' => $this->input->post('contact_title_en'),
                    'contact_title_el' => $this->input->post('contact_title_el'),
                    'company_id' => $this->_uid,
                );
                if (!empty($company_images)) {
                    $options_data['contact_images'] = json_encode($company_images);
                }
                if(!empty($information_files)){
                   $options_data['info_files'] = serialize($information_files);
                }

                $option = array(
                    'table' => COMPANY_INFO,
                    'data' => $options_data
                );
                $update = $this->Common_model->customInsert($option);
                $response = array('status' => 1, 'message' => lang('info_add_success'), 'url' => base_url('appinfo/info'));
            }
        endif;

        echo json_encode($response);
    }
    
   /**
     * @method info_edit
     * @description edit dynamic rows
     * @return array
     */
    public function info_edit() {
        $this->data['title'] = lang("edit_app_info");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {
            $option = array(
                'table' => COMPANY_INFO,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit-info', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('appinfo');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('appinfo');
        }
    }
    
   /**
     * @method edit_app_info
     * @description edit dynamic rows
     * @return array
     */
    public function update_app_info() {
        $this->form_validation->set_rules('legal_text_en', lang('legal_text_en'), 'required|trim');
        $this->form_validation->set_rules('legal_text_el', lang('legal_text_el'), 'required|trim');
        $this->form_validation->set_rules('version', lang('version'), 'required|trim');
        $this->form_validation->set_rules('copyright', lang('copyright'), 'required|trim');
        $this->form_validation->set_rules('contact_title_en', lang('contact_title_en'), 'required|trim');
        $this->form_validation->set_rules('contact_title_el', lang('contact_title_el'), 'required|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
            $this->filedata['status'] = 1;
            
            $old_img = $this->input->post('exisisCompanyImage');
            $company_images = array();
            if (!empty($old_img)) {
                $company_images = $old_img;
            }
            if (!empty($_FILES['files']['name'])) {
                for ($i = 0; $i < count($_FILES['files']['name']); $i++) {
                    if ($_FILES['files']['name'][$i]) {
                        $up = $_FILES['files']['tmp_name'][$i];
                        if (is_uploaded_file($up)) {
                            $company_image = $_FILES['files']['name'][$i];
                            $f_extension = explode('.', $company_image); //To breaks the string into array
                            $f_extension = strtolower(end($f_extension)); //end() is used to retrun a last element to the array
                            $f_newfile = "";
                            $company_image = uniqid() . '.' . $f_extension;
                            move_uploaded_file($up, "uploads/app/" . $company_image);
                            array_push($company_images, $company_image);
                        }
                    }
                }
            }
            
            $option = array(
                'table' => COMPANY_INFO,
                'where' => array('id' => $where_id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option);
            $files = unserialize($results_row->info_files);
            $privious_files = array();
            if (count($files) > 0) {
                foreach ($files as $file) {
                    array_push($privious_files, $file['filename']);
                }
            }

            $info_files = array();
            $info_file_titles = array();
            
            if (isset($_POST['old_info_file'])) {
                 for ($i = 0; $i < count($_POST['old_info_file']); $i++) {
                     if (!empty($_POST['old_info_file'][$i])) {
                         array_push($info_files, $_POST['old_info_file'][$i]);
                         array_push($info_file_titles, $_POST['old_info_file_title'][$i]);
                     }
                 }
             }
            
            
            if (isset($_FILES['info_file']['name'])) {
                    for ($i = 0; $i < count($_FILES['info_file']['name']); $i++) {
                        if (!empty($_FILES['info_file']['name'][$i])) {
                            $up = $_FILES['info_file']['tmp_name'][$i];
                            if (is_uploaded_file($up)) {
                                $info = $_FILES['info_file']['name'][$i];
                                $f_extension = explode('.', $info); //To breaks the string into array
                                $f_extension = strtolower(end($f_extension)); //end() is used to retrun a last element to the array
                                $f_newfile = "";
                                //Give unique name to file
                                $info = uniqid() . '.' . $f_extension;
                                $ext = pathinfo($info, PATHINFO_EXTENSION);

                                move_uploaded_file($up, "uploads/app/" . $info);
                                array_push($info_files, $info);
                                array_push($info_file_titles, $_POST['info_file_title'][$i]);
                            }
                        }
                    }
            }
            
             if (count($privious_files) > 0) {
                    for ($i = 0; $i < count($privious_files); $i++) {
                        if (!in_array($privious_files[$i], $info_files)) {
                            unlink("uploads/app/" . $privious_files[$i]);
                        }
                    }
            }
            
            $information_files = array();
            for ($i = 0; $i < count($info_files); $i++) {
                array_push($information_files, array('title' => $info_file_titles[$i], 'filename' => $info_files[$i]));
            }

            if ($this->filedata['status'] == 0) {
                $response = array('status' => 0, 'message' => $this->filedata['error']);
            } else {

                $options_data = array(
                    'legal_text_en' => $this->input->post('legal_text_en'),
                    'legal_text_el' => $this->input->post('legal_text_el'),
                    'version' => $this->input->post('version'),
                    'copyright' => $this->input->post('copyright'),
                    'contact_title_en' => $this->input->post('contact_title_en'),
                    'contact_title_el' => $this->input->post('contact_title_el'),
                    'company_id' => $this->_uid,
                );
                if (!empty($company_images)) {
                    $options_data['contact_images'] = json_encode($company_images);
                }
                if(!empty($information_files)){
                   $options_data['info_files'] = serialize($information_files);
                }

                $option = array(
                    'table' => COMPANY_INFO,
                    'data' => $options_data,
                    'where' => array('id' => $where_id)
                );
                $update = $this->Common_model->customUpdate($option);
                $response = array('status' => 1, 'message' => lang('info_update_success'), 'url' => base_url('appinfo/info'));
            }
        endif;

        echo json_encode($response);
    }

}
