<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ServiceFeedback extends Common_Controller {
    public $data = array();
    public $file_data = "";
    public $_table = FEEDBACK_OF_RESTAURANT;
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
                $this->data['url'] = base_url().'serviceFeedback';
        $this->data['pageTitle'] = lang('serviceFeedback');
        $this->data['parent'] = "servicefeedback";
        $option = array('table' => $this->_table.' as feedback',
                        'select' => 'feedback.*,app.company_name,store.restaurant_name_en as store_name',
                        'join' => array(ADMIN.' as app' => 'app.id=feedback.company_id',
                                        RESTAURANT.' as store' => 'store.restaurant_id=feedback.restaurant_id')
            );
       
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'feedback.*,app.company_name,store.restaurant_name_en as store_name';
        }
        $this->data['list'] = $this->Common_model->customGet($option);
 
        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }
}
