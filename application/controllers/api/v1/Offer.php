<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for offer
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Offer extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

    
    /**
     * Function Name: offer_list
     * Description:   To Get Offer List
     */
    function offer_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');   
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $language  = extract_value($data, 'language', '');
            $page_no  = extract_value($data,'page_no',1);  
            $user_id  = extract_value($data,'user_id','');
            $offset   = get_offsets($page_no);
            $is_used = 0;
            $upload_url = base_url().'uploads/offer/thumb/';

            
              /* To get offer list from offer table */
           
            if(!empty($user_id)){

             $options = array('table' => OFFER.' as offers',
                'select' => 'offers.id,offers.offer_name_en as offer_name,offers.description_en as description,offers.discounts_in_percent,offers.image,user_offers.is_used',
                'join' => array(array('user_offers','user_offers.offer_id=offers.id','left outer')),
                'order' => array('offers.create_date' => 'desc'),
                'where' => array('offers.is_active' =>1,'user_offers.user_id' => $user_id),
                'limit' => array(10 => $offset)
                
            );
            if ($language == "el") {
                $options['select'] = 'offers.id,offers.offer_name_el as offer_name,offers.description_el as description,offers.discounts_in_percent,offers.image,user_offers.is_used';
            }
            $list = $this->Common_model->customGet($options);

             $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
               
                $total_records = getAllCount(USER_OFFERS,array('user_id'=>$user_id));
                
                if($total_records > $total_requested){                      
                  $has_next = TRUE;                    
                }else{                        
                  $has_next = FALSE;                    
                }

            }else{
              $options = array('table' => OFFER.' as offers',
                'select' => 'offers.id,offers.offer_name_en as offer_name,offers.description_en as description,offers.discounts_in_percent,offers.image,user_offers.is_used',
                'join' => array(array('user_offers','user_offers.offer_id=offers.id','left outer')),
                'order' => array('offers.create_date' => 'desc'),
                'where' => array('offers.is_active' =>1,'user_offers.offer_id' => null),
                'limit' => array(10 => $offset)
                
            );
            if ($language == "el") {
                $options['select'] = 'offers.id,offers.offer_name_el as offer_name,offers.description_el as description,offers.discounts_in_percent,offers.image,user_offers.is_used';
            }
            $list = $this->Common_model->customGet($options);
             $total_requested = (int) $page_no * 10; 

                  /* Get total records */  

              $option1 = array('table' => OFFER.' as offers',
                'select' => 'offers.id,offers.offer_name_en as offer_name,offers.description_en as description,offers.discounts_in_percent,offers.image,user_offers.is_used',
                'join' => array(array('user_offers','user_offers.offer_id=offers.id','left outer')),
                'order' => array('offers.create_date' => 'desc'),
                'where' => array('offers.is_active' =>1,'user_offers.offer_id' => null)
                
                
            );
               $total_records = $this->Common_model->customCount($option1);
                
                if($total_records > $total_requested){                      
                  $has_next = TRUE;                    
                }else{                        
                  $has_next = FALSE;                    
                }

            }

             
            /* check for image empty or not */
           
            if (!empty($list)) {


                $eachArr = array();
                
               

                foreach ($list as $rows):
                
                  if(!empty($rows->is_used)){
                    $is_used = 1;
                  }else{
                    $is_used = 0;
                  }

                   if(!empty($rows->image))
                  {
                          $image = $upload_url.$rows->image;
                  } else{
                            /* set default image if empty */
                          $image = base_url().'assets/img/no_image.jpg';
                  }
                    $temp['id']                      = null_checker($rows->id);
                    $temp['offer_name']              = null_checker($rows->offer_name);
                    $temp['description']             = null_checker($rows->description);
                    $temp['discounts_in_percent']    = null_checker($rows->discounts_in_percent);
                    $temp['image']       = $image;
                    //$temp['is_used']     = $is_used;
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_offer_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_offer_error');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: offer_details
     * Description:   To Get Offer Details
     */

    function offer_details_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('id', 'Offer Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
          $offer_id    =  extract_value($data, 'id', '');
          $language    = extract_value($data, 'language', '');
          $upload_url  = base_url().'uploads/offer/thumb/';
          
          $options = array('table' => OFFER,
            'select' => 'id,offer_name_en as offer_name,description_en as description,offer_code,from_date,to_date,discounts_in_percent,image',
            'where' => array('id' => $offer_id,
                            'is_active' =>1),
            'single' =>true
         );
         if ($language == "el") {
                $options['select'] = 'id,offer_name_el as offer_name,description_el as description,offer_code,from_date,to_date,discounts_in_percent,image';
        }
           
           /* To get offer details from offer table according id */
          $list = $this->Common_model->customGet($options);
            /* check for image empty or not */
            if(!empty($list->is_used)){
              $is_used = 1;
            }else{
              $is_used = 0;
            }
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
                    $eachArr['offer_name']     = null_checker($list->offer_name);
                    $eachArr['description']    = null_checker($list->description);
                    $eachArr['offer_code']     = null_checker($list->offer_code);
                    $eachArr['discounts_in_percent']    = null_checker($list->discounts_in_percent);
                    $eachArr['from_date']      = convertDate(null_checker($list->from_date));
                    $eachArr['to_date']        = convertDate(null_checker($list->to_date));
                    $eachArr['image']          = $image;
                    
                   
                
             /* return success response*/
            $return['status'] = 1;
            $return['response'] = $eachArr;
            $return['message'] = $this->lang->line('api_offer_details_success');
        } else {
            $return['status'] = 0;
            $return['message'] = $this->lang->line('api_offer_details_failed');
        }
    }
        $this->response($return);
    }

   /**
     * Function Name: my_offer_list
     * Description:   To Get My Offer List
     */
    function my_offer_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');   
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {

            $user_id  = extract_value($data, 'user_id', '');
            $language  = extract_value($data, 'language', '');
            $page_no  = extract_value($data,'page_no',1);  
            $offset   = get_offsets($page_no);
            
            $upload_url = base_url().'uploads/offer/thumb/';


            $options = array('table' => USER_OFFERS . ' as user_offer',
                'select' => 'user_offer.offer_id,user_offer.is_used,' . OFFER . '.id,offer_name_en as offer_name,description_en as description,discounts_in_percent,image',
                'where' => array('user_offer.user_id' => $user_id,
                                   'is_active' =>1),
                'limit' => array(10 => $offset),
                'order' => array('create_date' => 'desc'),
                'join' => array(OFFER => OFFER . '.id=user_offer.offer_id')
            );
            if ($language == "el") {
                $options['select'] = 'user_offer.offer_id,user_offer.is_used,' . OFFER . '.id,offer_name_el as offer_name,description_el as description,discounts_in_percent,image';
            }

              /* To get offer list from offer table */
            $list = $this->Common_model->customGet($options);
            /* check for image empty or not */
           
            if (!empty($list)) {

                $eachArr = array();
                
                $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                $total_records = getAllCount(USER_OFFERS,array('user_id' =>$user_id));
                if($total_records > $total_requested){                      
                  $has_next = TRUE;                    
                }else{                        
                  $has_next = FALSE;                    
                }

                foreach ($list as $rows):
                   if(!empty($rows->is_used)){
                    $is_used = 1;
                  }else{
                    $is_used = 0;
                  }
              
                   if(!empty($rows->image))
                  {
                          $image = $upload_url.$rows->image;
                  } else{
                            /* set default image if empty */
                          $image = base_url().'assets/img/no_image.jpg';
                  }
                    $temp['id']                      = null_checker($rows->id);
                    $temp['offer_name']              = null_checker($rows->offer_name);
                    $temp['description']             = null_checker($rows->description);
                    $temp['discounts_in_percent']    = null_checker($rows->discounts_in_percent);
                    $temp['image']       = $image;
                    $temp['is_used']     = $is_used;
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_offer_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_offer_error');
            }
        }
        $this->response($return);
    }

     
     /**
     * Function Name: offer_scane
     * Description:   To scan offer
     */
     public function offer_scane_POST(){
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('offer_id', 'Offer Id', 'trim|required');
        $this->form_validation->set_rules('offer_code', 'Offer Code', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $offer_id = $this->input->post('offer_id');
            $user_id = $this->input->post('user_id');
            $offer_code = $this->input->post('offer_code');
            
            $option1 = array('table' => USER_OFFERS,
                'where' => array('offer_id'=>$offer_id),
                'single' => true,
            );
            $offer_exist = $this->Common_model->customGet($option1);

            if(empty($offer_exist)){
                $options = array('table' => OFFER,
                'select' => 'id,from_date,to_date,offer_name_en as offer_name,description_en as description,discounts_in_percent,image,offer_code,offer_limit',
                'where' => array('is_active' => 1,
                                'id'=>$offer_id,
                                'offer_code' => $offer_code),
                                'single' => true
            );
               
            }else{

              $options = array(
                'table' => USER_OFFERS.' as u_offer',
                'select' => 'u_offer.id as user_offer_id,'
                . 'offer.offer_code,offer.to_date,offer.id as offer_id,offer.from_date,offer.offer_limit',
                'join' => array(OFFER.' as offer' => 'offer.id=u_offer.offer_id'),
                'where' => array('u_offer.offer_id' => $offer_id,
                                 'u_offer.user_id' => $user_id,
                                 'offer.is_active' => 1,
                                 'offer.offer_code' => $offer_code),
                'single' => true
             );

            }

            
            $offer = $this->Common_model->customGet($options);
           
            if(!empty($offer)){

                $today_date = strtotime(date('Y-m-d'));
                $end_date = strtotime($offer->to_date);
                $start_date = strtotime($offer->from_date);
                if($start_date <= $today_date){
                      if($today_date <= $end_date){
                            
                           $options = array('table' => OFFER_HISTORY,
                                            'where' => array(
                                                 
                                                'offer_id' => $offer_id,
                                                
  
                                            ),
                                            'single' => true,
                                            
                                ); 
                            $current_scane = $this->Common_model->customCount($options);

                            $option1 = array('table' => OFFER_HISTORY,
                                            'where' => array(
                                                 'user_id'=> $user_id,
                                                'offer_id' => $offer_id,
                                                
  
                                            ),
                                            'single' => true,
                                           
                                ); 
                          $user_exist = $this->Common_model->customGet($option1);
                        
                           if(empty($user_exist)){


                            if($current_scane<$offer->offer_limit){

                                    $options = array(
                                       'table' => OFFER_HISTORY,
                                       'data' => array('user_id' => $user_id,
                                                       'offer_id' => $offer_id,
                                                       )
                                   );
                                   $this->Common_model->customInsert($options);
                                    $option = array(
                                       'table' => USER_OFFERS,
                                       'data' => array('is_used' => 1,
                                                       
                                                       ),
                                       'where' => array('user_id' => $user_id,
                                               'offer_id' => $offer_id
                                        )
                                   );
                                   $this->Common_model->customUpdate($option);
                                   
                                   $return['status'] = 1;
                                   $return['response'] = $offer; 
                                   $return['message'] = $this->lang->line('offer_scane_success'); 
                            

                               }else{
                              $return['status'] = 0;
                              $return['message'] = $this->lang->line('offer_limit_exceed'); 
                            }
                         }else{
                             $return['status'] = 0;
                             $return['message'] = $this->lang->line('offer_already_used'); 
                         }   
                        
                }else{
                        $return['status'] = 0;
                        $return['message'] = $this->lang->line('offer_scane_expired');   
                }
                }else{
                   $return['status'] = 0;
                   $return['message'] = $this->lang->line('offer_scane_start').' '.date('d F Y',strtotime($offer->from_date));    
                }

            }else{
               $return['status'] = 0;
               $return['message'] = $this->lang->line('offer_scane_qr_failed');  
            }
        }
        $this->response($return);
    }

}


/* End of file Offer.php */
/* Location: ./application/controllers/api/v1/Offer.php */
?>