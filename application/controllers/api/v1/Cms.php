<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for cms
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Cms extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

    
    /**
     * Function Name: about_us
     * Description:   To Get About Us Details
     */
    function about_us_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
       //$this->form_validation->set_rules('cms_id', 'Cms Id', 'trim|required');
        
            $language  = extract_value($data, 'language', '');
            $upload_url = base_url().'uploads/cms/thumb/';
            //$cms_id    = extract_value($data, 'cms_id', '');
            

            $options = array('table' => CMS,
                'select' => 'description_en as description,image,page_id',
                'where' => array('page_id'=>'about','is_active' =>1),
                'single' =>true,
              
            );
            if ($language == "el") {
                $options['select'] = 'description_el as description,image,page_id';
            }
              /* To get page details from cms table */
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

                

                    $eachArr['description']   = null_checker($list->description);
                    $eachArr['page_id']       = null_checker(allModules($list->page_id));
                    $eachArr['image']         = $image;
                    //$eachArr[] = $temp;
                /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['message'] = $this->lang->line('api_about_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_about_failed');
            }
        
        $this->response($return);
    }

    /**
     * Function Name: terms_condition
     * Description:   To Get Terms and condition Details
     */
    function terms_condition_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
       //$this->form_validation->set_rules('cms_id', 'Cms Id', 'trim|required');
        
            $language  = extract_value($data, 'language', '');
            $upload_url = base_url().'uploads/cms/thumb/';
            //$cms_id    = extract_value($data, 'cms_id', '');
            

            $options = array('table' => CMS,
                'select' => 'description_en as description,image,page_id',
                'where' => array('page_id'=>'terms_condition','is_active' =>1),
                'single' =>true,
              
            );
            if ($language == "el") {
                $options['select'] = 'description_el as description,image,page_id';
            }
              /* To get page details from cms table */
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

                

                    $eachArr['description']   = null_checker($list->description);
                    //$eachArr['page_id']       = null_checker(allModules($list->page_id));
                    //$eachArr['image']         = $image;
                    //$eachArr[] = $temp;
                /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['message'] = $this->lang->line('api_terms_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_terms_failed');
            }
        
        $this->response($return);
    }



}


/* End of file Cms.php */
/* Location: ./application/controllers/api/v1/Cms.php */
?>