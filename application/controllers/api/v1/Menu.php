<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for menu
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Menu extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

    
    /**
     * Function Name: menu_list
     * Description:   To Get Menu List
     */
    function menu_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');   
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $language   = extract_value($data, 'language', '');
            $page_no    = extract_value($data,'page_no',1);  
            $offset     = get_offsets($page_no);
            $subcat_id  = extract_value($data, 'subcategory_id', '');
            $upload_url = base_url().'uploads/menu/thumb/';
            /* check if subcategory_id not empty */
          if(!empty($subcat_id)){
            $options = array('table' => MENUS . ' as menu',
                'select' => 'menu.id,menu.menu_subcategory_id,menu.menu_name_en as menu_name,menu.price,menu.image,menu.description_en as description,cat.category_name_en as category_name,subcat.subcategory_name_en as subcategory_name',
                  'join' =>array(array(MENU_CATEGORY.' as cat', '                        cat.id=menu.menu_category_id','left'),
                           array(MENU_SUBCATEGORY.' as subcat', 'subcat.id=menu.menu_subcategory_id','left')),
                  'where' => array('menu.menu_subcategory_id' =>$subcat_id),
                  'order' => array('menu.id' => 'desc'),
                  'limit' => array(10 => $offset)
                
            );
            if ($language == "el") {
                $options['select'] = 'menu.id,menu.menu_subcategory_id,menu.menu_name_el as menu_name,menu.price,menu.image,menu.description_el as description,cat.category_name_el as category_name,subcat.subcategory_name_el as subcategory_name';
            }
            $list = $this->Common_model->customGet($options);
          }else{
             $options = array('table' => MENUS . ' as menu',
                'select' => 'menu.id,menu.menu_name_en as menu_name,menu.price,menu.image,menu.description_en as description,cat.category_name_en as category_name,subcat.subcategory_name_en as subcategory_name',
                  'join' =>array(array(MENU_CATEGORY.' as cat', '                        cat.id=menu.menu_category_id','left'),
                           array(MENU_SUBCATEGORY.' as subcat', 'subcat.id=menu.menu_subcategory_id','left')),
                  'order' => array('menu.id' => 'desc'),
                  'limit' => array(10 => $offset)
                
            );
            if ($language == "el") {
                $options['select'] = 'menu.id,menu.menu_name_el as menu_name,menu.price,menu.image,menu.description_el as description,cat.category_name_el as category_name,subcat.subcategory_name_el as subcategory_name';
            }
          
              /* To get menu list from menus table */
            $list = $this->Common_model->customGet($options);
          }
            /* check for image empty or not */
            
            if (!empty($list)) {

                $eachArr = array();
                
                $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                $total_records = getAllCount(MENUS);
             
                if($total_records > $total_requested){                      
                  $has_next = TRUE;                    
                }else{                        
                  $has_next = FALSE;                    
                }

                foreach ($list as $rows):
                      if(!empty($rows->image))
                  {
                          $image = $upload_url.$rows->image;
                  } else{
                            /* set default image if empty */
                          $image = base_url().'assets/img/no_image.jpg';
                  }
                    $temp['id']               = null_checker($rows->id);
                    $temp['menu_name']        = null_checker($rows->menu_name);
                    $temp['description']      = null_checker($rows->description);
                    $temp['price']            = null_checker($rows->price);
                    $temp['category_name']    = null_checker($rows->category_name);
                    $temp['subcategory_name'] = null_checker($rows->subcategory_name);
                    $temp['image']       = $image;
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_menu_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_menu_error');
            }
        }
        $this->response($return);
    }


    /**
     * Function Name: menu_by_store
     * Description:   To Get Menu List store wise
     */
    function menu_by_store_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');   
        $this->form_validation->set_rules('store_id', 'Store', 'required|trim|numeric');
        $this->form_validation->set_rules('subcategory_id', 'Subcategory Id', 'trim|required');   
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $store_id   = extract_value($data, 'store_id', '');
            $language   = extract_value($data, 'language', '');
            $page_no    = extract_value($data,'page_no',1);  
            $offset     = get_offsets($page_no);
            $subcat_id  = extract_value($data, 'subcategory_id','');
            $upload_url = base_url().'uploads/menu/thumb/';
            /* check if subcategory_id not empty */
          if(!empty($subcat_id)){
            $options = array('table' => MENUS . ' as menu',
                'select' => 'menu.id,menu.menu_subcategory_id,menu.menu_name_en as menu_name,menu.price,menu.image,menu.description_en as description,cat.category_name_en as category_name,subcat.subcategory_name_en as subcategory_name',
                  'join' =>array(array(MENU_CATEGORY.' as cat', '                        cat.id=menu.menu_category_id','left'),
                           array(MENU_SUBCATEGORY.' as subcat', 'subcat.id=menu.menu_subcategory_id','left')),
                  'where' => array('menu.menu_subcategory_id' =>$subcat_id,'menu.store'=>$store_id),
                  'order' => array('menu.id' => 'desc'),
                  'limit' => array(10 => $offset)
                
            );
           
            if ($language == "el") {
                $options['select'] = 'menu.id,menu.menu_subcategory_id,menu.menu_name_el as menu_name,menu.price,menu.image,menu.description_el as description,cat.category_name_el as category_name,subcat.subcategory_name_el as subcategory_name';
            }
            
            $list = $this->Common_model->customGet($options);
            if (!empty($list)) {

                $eachArr = array();
                
                $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                $total_records = getAllCount(MENUS);
             
                if($total_records > $total_requested){                      
                  $has_next = TRUE;                    
                }else{                        
                  $has_next = FALSE;                    
                }

                foreach ($list as $rows):
                      if(!empty($rows->image))
                  {
                          $image = $upload_url.$rows->image;
                  } else{
                            /* set default image if empty */
                          $image = base_url().'assets/img/no_image.jpg';
                  }
                    $temp['id']               = null_checker($rows->id);
                    $temp['menu_name']        = null_checker($rows->menu_name);
                    $temp['description']      = null_checker($rows->description);
                    $temp['price']            = null_checker($rows->price);
                    $temp['category_name']    = null_checker($rows->category_name);
                    $temp['subcategory_name'] = null_checker($rows->subcategory_name);
                    $temp['image']       = $image;
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_menu_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_menu_error');
            }
          }/*else{
              $options = array('table' => MENUS . ' as menu',
                'select' => 'menu.id,menu.menu_name_en as menu_name,menu.price,menu.image,menu.description_en as description,cat.category_name_en as category_name,subcat.subcategory_name_en as subcategory_name',
                  'join' =>array(array(MENU_CATEGORY.' as cat', '                        cat.id=menu.menu_category_id','left'),
                           array(MENU_SUBCATEGORY.' as subcat', 'subcat.id=menu.menu_subcategory_id','left')),
                  'where'=>array('menu.store'=>$store_id),
                  'order' => array('menu.id' => 'desc'),
                  'limit' => array(10 => $offset)
                
            );
            if ($language == "el") {
                $options['select'] = 'menu.id,menu.menu_name_el as menu_name,menu.price,menu.image,menu.description_el as description,cat.category_name_el as category_name,subcat.subcategory_name_el as subcategory_name';
            }
          
              
            $list = $this->Common_model->customGet($options);
            
          }*/
            /* check for image empty or not */
            
            
        }
        $this->response($return);
    }


    /**
     * Function Name: menu_details
     * Description:   To Get Menu Details
     */

    function menu_details_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('id', 'Menu Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
          $menu_id    =  extract_value($data, 'id', '');
          $language   = extract_value($data, 'language', '');
          $upload_url = base_url().'uploads/menu/thumb/';

          $options = array('table' => MENUS . ' as menu',
            'select' => 'menu.id,menu.menu_name_en as menu_name,menu.price,menu.image,menu.description_en as description,cat.category_name_en as category_name,subcat.subcategory_name_en as subcategory_name',
            'join' =>array(array(MENU_CATEGORY.' as cat', '                        cat.id=menu.menu_category_id','left'),
                      array(MENU_SUBCATEGORY.' as subcat', 'subcat.id=menu.menu_subcategory_id','left')),
            'where' => array('menu.id' => $menu_id),
            'single' => true
         );
           if ($language == "el") {
                  $options['select'] = 'menu.id,menu.menu_name_el as    menu_name,menu.price,menu.image,menu.description_el as description,cat.category_name_el as category_name,subcat.subcategory_name_el as subcategory_name';
          }
           
           /* To get menu details from menus table according id */
          $list = $this->Common_model->customGet($options);
         
            /* check for image empty or not */
           if(!empty($list->image))
              {
                      $image = $upload_url.$list->image;
              } else{
                       /* set default image if empty */
                      $image = base_url().'assets/img/no_image.jpg';
              }
           if (!empty($list)) {

              $eachArr = array();

                
                    $eachArr['id']             = null_checker($list->id);
                    $eachArr['menu_name']      = null_checker($list->menu_name);
                    $eachArr['price']          = null_checker($list->price);
                    $eachArr['description']    = null_checker($list->description);
                    $eachArr['category_name']  = null_checker($list->category_name);
                    $eachArr['subcategory_name'] = null_checker($list->subcategory_name);
                    $eachArr['image']          = $image;
                    
                    
                
             /* return success response*/
            $return['status'] = 1;
            $return['response'] = $eachArr;
            $return['message'] = $this->lang->line('api_menu_details_success');
        } else {
            $return['status'] = 0;
            $return['message'] = $this->lang->line('api_menu_details_failed');
        }
    }
        $this->response($return);
    }

    /**
     * Function Name: menu_filter
     * Description:   To filter Menu Item
     */

    function menu_filter_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
       
        $language   = extract_value($data, 'language', '');
        
           $options = array('table' => MENU_CATEGORY . ' as cat',
                'select' => 'cat.id,cat.category_name_en as category_name',
               
             );
            if ($language == "el") {
                $options['select'] = 'cat.id,cat.category_name_el as category_name';
            }
             /* To Get category from menu_category table */
            $list = $this->Common_model->customGet($options);
       
          if (!empty($list)) {

                $eachArr = array();
                

           foreach ($list as $rows):

                  $cat_id= $rows->id;

               $options = array('table' => MENU_SUBCATEGORY . ' as subcat',
                  'select' => 'subcat.id as subcategory_id,subcat.subcategory_name_en as subcategory_name',
                  'where'=> array('subcat.menu_category_id'=>$cat_id)
                 
               );
                if ($language == "el") {
                    $options['select'] = 'subcat.id as subcategory_id,subcat.subcategory_name_el as subcategory_name';
                }
                 /* To Get subcategory from menu_subcategory table */
               $list_data = $this->Common_model->customGet($options);

                    $temp['id']                = null_checker($rows->id);
                    $temp['category']          = null_checker($rows->category_name);
                    $temp['subcategory']       = $list_data ;
                    
                    
                    $eachArr[] = $temp;
                

                endforeach;
            
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['message'] = 'Sucess';
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_menu_error');
           }
        
        $this->response($return);
    }


    public function category_list_post(){
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');   
        $this->form_validation->set_rules('store_id', 'Store', 'required|trim|numeric');   
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $store_id   = extract_value($data, 'store_id', '');
            $language   = extract_value($data, 'language', '');
            $page_no    = extract_value($data,'page_no',1);  
            $offset     = get_offsets($page_no);
      
            $upload_url = base_url().'uploads/menu/';
            $options = array(
              'table'=> MENU_CATEGORY.' AS cat',
              'join' =>array(array(MENUS.' as menu','menu.menu_category_id=cat.id','left')),
              'where' => array('menu.store'=>$store_id),
              'group_by'=>'cat.id',
              'order' => array('cat.id' => 'desc'),
              'limit' => array(10 => $offset)
            );  
            if ($language == "el") {
              $options['select'] = 'cat.id,cat.category_name_el,cat.image';
            }else{
              $options['select'] = 'cat.id,cat.category_name_en,cat.image';
            }
            $list = $this->Common_model->customGet($options);       
          
          
            /* check for image empty or not */
            
            if (!empty($list)) {

                $eachArr = array();
                
                $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                $total_records = getAllCount(MENU_CATEGORY);
             
                if($total_records > $total_requested){                      
                  $has_next = TRUE;                    
                }else{                        
                  $has_next = FALSE;                    
                }

                foreach ($list as $rows):
                  if(!empty($rows->image))
                  {
                      $image = $upload_url.$rows->image;
                  } else{
                            /* set default image if empty */
                      $image = base_url().'assets/img/no_image.jpg';
                  }
                    $temp['id']                 = null_checker($rows->id);
                    if($language == "el"):
                      $temp['category_name']    = null_checker($rows->category_name_el);
                    else:
                      $temp['category_name']    = null_checker($rows->category_name_en);
                    endif;
                    $temp['image']              = $image;
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_category_list_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_category_list_failed');
            }
        }
        $this->response($return);
    }

    public function subcategory_list_post(){
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');   
        $this->form_validation->set_rules('store_id', 'Store', 'required|trim|numeric');   
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $store_id    = extract_value($data, 'store_id', '');
            $category_id = extract_value($data, 'category_id', '');
            $language    = extract_value($data, 'language', '');
            $page_no     = extract_value($data,'page_no',1);  
            $offset      = get_offsets($page_no);
      
            $upload_url  = base_url().'uploads/menu/';
            $options = array(
              'table'=> MENU_SUBCATEGORY.' AS sbc',
              'join' =>array(array(MENUS.' as menu','menu.menu_subcategory_id=sbc.id','left')),
              'where' => array('menu.store'=>$store_id),
              'group_by'=>'sbc.id',
              'order' => array('sbc.id' => 'desc'),
              'limit' => array(10 => $offset)
            );  
            if ($language == "el") {
              $options['select'] = 'sbc.id,sbc.subcategory_name_el,sbc.image';
            }else{
              $options['select'] = 'sbc.id,sbc.subcategory_name_en,sbc.image';
            }


            if($category_id != "") {
              $options['where']['sbc.menu_category_id'] = $category_id;
            }

            $list = $this->Common_model->customGet($options);       
          
          
            /* check for image empty or not */
            
            if (!empty($list)) {

                $eachArr = array();
                
                $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                $total_records = getAllCount(MENU_SUBCATEGORY);
             
                if($total_records > $total_requested){                      
                  $has_next = TRUE;                    
                }else{                        
                  $has_next = FALSE;                    
                }

                foreach ($list as $rows):
                  if(!empty($rows->image))
                  {
                      $image = $upload_url.$rows->image;
                  } else{
                            /* set default image if empty */
                      $image = base_url().'assets/img/no_image.jpg';
                  }
                    $temp['id']                 = null_checker($rows->id);
                    if($language == "el"):
                      $temp['subcategory_name']    = null_checker($rows->subcategory_name_el);
                    else:
                      $temp['subcategory_name']    = null_checker($rows->subcategory_name_en);
                    endif;
                    $temp['image']              = $image;
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_subcategory_list_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_subcategory_list_failed');
            }
        }
        $this->response($return);
    }

}


/* End of file Menu.php */
/* Location: ./application/controllers/api/v1/Menu.php */
?>