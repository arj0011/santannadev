<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Menus extends Common_Controller {
    public $data = array();
    public $file_data = "";
    public $_table = MENUS;
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method menu_list
     * @description listing display
     * @return array
     */
    public function index() {
                $this->data['url'] = base_url().'menus';
        $this->data['pageTitle'] = lang('menu');
        $this->data['parent'] = "Menu";
        $option = array('table' => $this->_table.' as menu',
                        'select' => 'menu.id,menu.menu_name_en as menu_name,menu.price,menu.image,menu.description_en as description',
                        'join' => array(MENU_CATEGORY.' as cat' => 'cat.id=menu.menu_category_id'),
                        'join' => array(MENU_SUBCATEGORY.' as subcat' => 'subcat.id=menu.menu_subcategory_id'),
                        'order' => array('id' => 'desc')
                        
            );
        if($this->session->userdata('role') == 'store'){
            $option['where'] = array('store'=>$this->session->userdata('id'));
        }
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'menu.id,menu.menu_name_el as menu_name,menu.price,menu.image,menu.description_el as description';
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
        $this->data['title'] = lang("add_menu");
       
       
        $option = array('table' => MENU_CATEGORY,
            'select' => 'category_name_en as category_name,id'
               );
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'category_name_el as category_name,id';
        }
        $this->data['results'] = $this->Common_model->customGet($option);
        $options = array(
            'table'   => STORE,
            'select'  => 'id,store_name'
                        
            );
        $this->data['storeslist'] = $this->Common_model->customGet($options);
        
        $this->load->view('add', $this->data);
     
    }

     function getSubcat($menu_category_id){
       
         $option = array('table' => MENU_SUBCATEGORY,
                        'select' => 'subcategory_name_en as subcategory_name,id',
                        'where' => array('menu_category_id' => $menu_category_id)
            );
         if(getDefaultLanguage() == "el"){
            $option['select'] = 'subcategory_name_el as subcategory_name,id';
        }

         $results = $this->Common_model->customGet($option);
        
         $opt='<option value="">Select Sub category</option>';
         foreach($results as $subcat){
            $opt .= "<option value='".$subcat->id."'>".$subcat->subcategory_name."</option>";
         }
        echo $opt;
        exit;
    
    }
   
    /**
     * @method menu_add
     * @description add dynamic rows
     * @return array
     */
    public function menu_add() {
        $this->form_validation->set_rules('menu_name_en',lang('menu_name_en'), 'required|trim');
        $this->form_validation->set_rules('menu_name_el', lang('menu_name_el'), 'required|trim');
        $this->form_validation->set_rules('price', lang('price'), 'required|trim');
        $this->form_validation->set_rules('store', 'Store', 'required|trim');
         
        if ($this->form_validation->run() == true) {
            
                $this->filedata['status'] = 1;
                $image = "";
                if (!empty($_FILES['image']['name'])) {
                    $this->filedata = $this->commonUploadImage($_POST, 'menu', 'image');
                    if ($this->filedata['status'] == 1) {
                     $image = $this->filedata['upload_data']['file_name'];
                     $full_path = $this->filedata['upload_data']['full_path'];
                     $folder = "menu/thumb";
                     $this->resizeNewImage($full_path,$folder,166,166);
                    }
                   
                }
                      
                if ($this->filedata['status'] == 0) {
                   $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                    $menu_name_en = $this->input->post('menu_name_en');
                    $option = array('table' => $this->_table,
                        'select' => 'menu_name_en as menu_name',
                        'where' => array('menu_name_en' => $menu_name_en)
                   );
                     if(getDefaultLanguage() == "el"){
                     $option['select'] = 'menu_name_el as menu_name';
                    }
                   $menu = $this->Common_model->customGet($option);
                   if(empty($menu)){
                     $options_data = array(

                     'restaurant_id' => self::$_restaurant_id,
                     'menu_category_id'  => $this->input->post('menu_category'),
                     'menu_subcategory_id' => $this->input->post('menu_subcategory_id'),
                     'menu_name_en'      => $this->input->post('menu_name_en'),
                     'menu_name_el'      => $this->input->post('menu_name_el'),
                     'description_en'    => $this->input->post('description_en'),
                     'description_el'    => $this->input->post('description_el'),
                     'price'             => $this->input->post('price'),
                     'stock'             => $this->input->post('stock'),
                     'store'             => $this->input->post('store'),
                     'image'             => $image,
                     'create_date'       => datetime()
                    );
                    $option = array('table' => $this->_table, 'data' => $options_data);
                    if ($this->Common_model->customInsert($option)) {
                        $response = array('status' => 1, 'message' => lang('menu_success'), 'url' => base_url('menus'));
                    } else {
                        $response = array('status' => 0, 'message' => lang('menu_failed'));
                    } 
                }else{
                    $response = array('status' => 0, 'message' => lang('menu_exists'));
                }
                }
        } else {
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        }
        echo json_encode($response);
    }
    
    
 
    /**
     * @method menu_edit
     * @description edit dynamic rows
     * @return array
     */
    public function menu_edit() {
        $this->data['title'] = lang("edit_menu");
        $id = decoding($this->input->post('id'));
        if (!empty($id)) {

          $option = array('table' => MENU_CATEGORY,
            'select' => 'category_name_en as category_name,id'
               );
         if(getDefaultLanguage() == "el"){
            $option['select'] = 'category_name_el as category_name,id';
         }
         $this->data['menu_category'] = $this->Common_model->customGet($option);

        //    $option = array('table' => SERVICE,
        //     'select' => 'store_name_en as store_name,store_id'
        //        );
        // if(getDefaultLanguage() == "el"){
        //     $option['select'] = 'store_name_el as store_name,store_id';
        // }
        //  $this->data['store_name'] = $this->Common_model->customGet($option);

        $opt = array(
            'table'   => STORE,
            'select'  => 'id,store_name'          
            );
        if($this->session->userdata('role') == 'store'){
            $opt['where']['id'] = $this->session->userdata('id');
        }
        $this->data['storeslist'] = $this->Common_model->customGet($opt); 


         $option = array('table' => MENU_SUBCATEGORY,
            'select' => 'subcategory_name_en as subcategory_name,id'
               );
         if(getDefaultLanguage() == "el"){
            $option['select'] = 'subcategory_name_el as subcategory_name,id';
         }
         $this->data['menu_subcategory'] = $this->Common_model->customGet($option);

           $option1 = array(
                'table' => $this->_table,
                'where' => array('id' => $id),
                'single' => true
            );
            $results_row = $this->Common_model->customGet($option1);
            if (!empty($results_row)) {
                $this->data['results'] = $results_row;
                $this->load->view('edit', $this->data);
            } else {
                $this->session->set_flashdata('error', lang('not_found'));
                redirect('menu');
            }
        } else {
            $this->session->set_flashdata('error', lang('not_found'));
            redirect('menu');
        }
    }

    /**
     * @method menu_update
     * @description update dynamic rows
     * @return array
     */
    public function menu_update() {

        $this->form_validation->set_rules('menu_name_en',lang('menu_name_en'), 'required|trim');
        $this->form_validation->set_rules('menu_name_el', lang('menu_name_el'), 'required|trim');
        $this->form_validation->set_rules('price', lang('price'), 'required|trim');
        $this->form_validation->set_rules('store', 'Store', 'required|trim');
        $where_id = $this->input->post('id');
        if ($this->form_validation->run() == FALSE):
            $messages = (validation_errors()) ? validation_errors() : '';
            $response = array('status' => 0, 'message' => $messages);
        else:
                $this->filedata['status'] = 1;
                $image = $this->input->post('exists_image');

                if (!empty($_FILES['image']['name'])) {
                    $this->filedata = $this->commonUploadImage($_POST, 'menu', 'image');
                    
                    if ($this->filedata['status'] == 1) {
                     $image = $this->filedata['upload_data']['file_name'];
                     $full_path = $this->filedata['upload_data']['full_path'];
                     $folder = "menu/thumb";
                     $this->resizeNewImage($full_path,$folder,166,166);
                     delete_file($this->input->post('exists_image'), FCPATH."uploads/menu/");
                     delete_file($this->input->post('exists_image'), FCPATH."uploads/menu/thumb/");
                    
                    }
                    
                }
                        
                if ($this->filedata['status'] == 0) {
                    $response = array('status' => 0, 'message' => $this->filedata['error']);  
                }else{
                    $menu_name_en = $this->input->post('menu_name_en');
                    $option = array('table' => $this->_table,
                        'select' => 'menu_name_en as menu_name',
                        'where' => array('id !='=>$where_id ,'menu_name_en' => $menu_name_en)
                   );
                     if(getDefaultLanguage() == "el"){
                     $option['select'] = 'menu_name_el as menu_name';
                    }
                   $menu = $this->Common_model->customGet($option);
                   if(empty($menu)){

                    $options_data = array(

                     'restaurant_id' => self::$_restaurant_id,
                     'menu_category_id'  => $this->input->post('menu_category'),
                     'menu_subcategory_id' => $this->input->post('menu_subcategory_id'),
                     'menu_name_en'      => $this->input->post('menu_name_en'),
                     'menu_name_el'      => $this->input->post('menu_name_el'),
                     'description_en'    => $this->input->post('description_en'),
                     'description_el'    => $this->input->post('description_el'),
                     'price'             => $this->input->post('price'),
                     'stock'             => $this->input->post('stock'),
                     'store'             => $this->input->post('store'),
                     'image'             => $image
                     
                    );
                    $option = array(
                        'table' => MENUS,
                        'data' => $options_data,
                        'where' => array('id' => $where_id)
                    );
                    $update = $this->Common_model->customUpdate($option);
                    $response = array('status' => 1, 'message' => lang('menu_success_update'), 'url' => base_url('menus'));
                }else{
                    $response = array('status' => 0, 'message' => lang('menu_exists'));
                }
                }
        endif;

        echo json_encode($response);
    }


}
