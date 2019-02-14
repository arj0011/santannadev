<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ContactFeedback extends Common_Controller {
    public $data = array();
    public $file_data = "";
    public $_table = CONTACTS;
    public function __construct() {
        parent::__construct();
    }
    
     /**
     * @method index
     * @description listing display
     * @return array
     */
    public function index() {
        $this->data['parent'] = "contactFeedback";
                $this->data['url'] = base_url().'contactFeedback';
        $this->data['pageTitle'] = lang('contactfeedback');
        $option = array('table' => $this->_table.' as contactfeedback',
                        'select' => 'contactfeedback.*',
                        //'join' => array(SERVICE_CATEGORY.' as catgory' => 'catgory.id=service.store_category_id')
            );
       
        if(getDefaultLanguage() == "el"){
            $option['select'] = 'contactfeedback.*';
        }
        $this->data['list'] = $this->Common_model->customGet($option);
        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }
}
