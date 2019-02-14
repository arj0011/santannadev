<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for booking
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Booking extends Common_API_Controller {

    function __construct() {
        parent::__construct();
        require APPPATH . "third_party/braintree/Braintree.php";
        Braintree_Configuration::environment(BRAINTREEENVIRONMENTS);
        Braintree_Configuration::merchantId(BRAINTREEMERCHANTID);
        Braintree_Configuration::publicKey(BRAINTREEPUBLICKEY);
        Braintree_Configuration::privateKey(BRAINTREEPRIVATEKEY);
    }

    /**
     * Function Name: booking_form
     * Description:   To Insert Booking Details
    */

    function booking_form_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        //$this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
         $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email');
        $this->form_validation->set_rules('phone_number', 'Phone No', 'trim|required');
        $this->form_validation->set_rules('place', 'Place', 'trim|required');
        $this->form_validation->set_rules('no_of_persons', 'No Of Persons', 'trim|required');
        $this->form_validation->set_rules('booking_details', 'Booking Details', 'trim|required');
        $this->form_validation->set_rules('reservation_date', 'Reservation Date', 'trim|required');
        $this->form_validation->set_rules('store_id', 'Store Id', 'trim|required');
        
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $insertArr = array();
            $language = extract_value($data, 'language', '');
            $reservation_date = extract_value($data, 'reservation_date', '');

            $email=  extract_value($data, 'email', '');
            $full_name =  extract_value($data, 'full_name', '');

            $insertArr['user_id']       = extract_value($data, 'user_id', '');
            $insertArr['name']     = $full_name;
            $insertArr['email']         = $email;
            $insertArr['mobile']  = extract_value($data, 'phone_number', '');
            $insertArr['place']         = extract_value($data, 'place', '');
            $insertArr['no_of_persons'] = extract_value($data, 'no_of_persons', '');
            $insertArr['comment'] = extract_value($data, 'booking_details', '');
            $insertArr['booking_date'] = date('Y-m-d', strtotime($reservation_date));
            $insertArr['status'] = 2;
            $insertArr['store_id'] = extract_value($data, 'store_id', '');

             $storeId = extract_value($data, 'store_id', '');
            $option = array('table' => 'mw_rooms',
                'where'=>array('store' => $storeId),
                'single' => true);
            $floors = $this->Common_model->customGet($option);
          $insertArr['floor_id'] = (!empty($floors)) ? $floors->id : 3;
            $options = array('table' =>'mw_booking',
                'data' => $insertArr,
            );
             /* insert data into booking table */
            $insert = $this->Common_model->customInsert($options);


             $from = FROM_EMAIL;
             $subject = "New booking has been added";
             $data['content'] = "Congratulation! Your booking has been successfully Done.";
             $data['user'] = ucwords($full_name);

             $message = $this->load->view('email_template',$data,true);

             $title = "New Booking";
                 
                /* send mail */
             $sent_mail=    send_mail($message, $subject, $email,$from,$title);
            


            if ($insert) {
                
                $options = array(
                    'table' => 'users_notifications',
                    'data' => array(
                        'sender_id' => $insertArr['user_id'],
                        'reciever_id' => 1,
                        'notification_type' => 'ADMIN',
                        'title' => 'New Booking Request',
                        'message_en' => 'New booking request has been on Date '.date('d/m/Y H:i:s', strtotime($reservation_date)),
                        'is_read' => 0,
                        'sent_time' => date('Y-m-d H:i:s'),
                        'is_send' => 1
                    )
                );
                $this->Common_model->customInsert($options);
                /* return success response */
                $return['status'] = 1;
                $return['message'] = $this->lang->line('api_booking_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_booking_failed');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: booking_confirm
     * Description:   To Get Booking Confirmed List
    */

    function booking_confirm_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');  
        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');   
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $language  = extract_value($data, 'language', '');
            $page_no  = extract_value($data,'page_no',1);  
            $offset   = get_offsets($page_no);
            $user_id  = extract_value($data, 'user_id', '');
            
            $upload_url = base_url().'uploads/offer/thumb/';

            $options = array('table' => 'mw_booking',
                'select' => 'user_id,id,name,email,mobile,no_of_persons,comment,booking_date',
                'order' => array('id' => 'desc'),
                'where' => array('user_id'=>$user_id,'status' =>1), 
                'limit' => array(10 => $offset)
                
            );
            if ($language == "el") {
                $options['select'] = 'user_id,id,name,email,mobile,no_of_persons,comment,booking_date';
            }
              /* To get booking list from booking table */
            $list = $this->Common_model->customGet($options);
            /* check for image empty or not */
           
            if (!empty($list)) {

                $eachArr = array();
                
                $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                $total_records = getAllCount('mw_booking',array('user_id'=>$user_id,'status' =>1));
               
                
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

                    $temp['booking_id']       = null_checker($rows->id);
                    $temp['user_id']          = null_checker($rows->user_id);
                    $temp['full_name']        = null_checker($rows->name);
                    $temp['no_of_persons']    = null_checker($rows->no_of_persons);
                    $temp['reservation_date'] = null_checker(convertDate($rows->booking_date));
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_booking_confirm_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_booking_confirm_failed');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: booking_pending
     * Description:   To Get Booking Pending List
    */

    function booking_pending_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        
        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required'); 
        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');   
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $language  = extract_value($data, 'language', '');
            $page_no  = extract_value($data,'page_no',1);  
            $offset   = get_offsets($page_no);
            $user_id  = extract_value($data, 'user_id', '');
           // $upload_url = base_url().'uploads/offer/thumb/';

            $options = array('table' => 'mw_booking',
                'select' => 'user_id,id,name,email,mobile,no_of_persons,comment,booking_date',
                'order' => array('id' => 'desc'),
                'where' => array('user_id'=>$user_id,'status' =>2), 
                'limit' => array(10 => $offset)
                
            );
            if ($language == "el") {
                $options['select'] = 'user_id,id,name,email,mobile,no_of_persons,comment,booking_date';
            }
              /* To get booking list from booking table */
            $list = $this->Common_model->customGet($options);
            /* check for image empty or not */
           
            if (!empty($list)) {

                $eachArr = array();
                
                $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                $total_records = getAllCount('mw_booking',array('user_id'=>$user_id,'status' =>2));
                
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
                    $temp['booking_id']       = null_checker($rows->id);
                    $temp['user_id']          = null_checker($rows->user_id);
                    $temp['full_name']        = null_checker($rows->name);
                    $temp['no_of_persons']    = null_checker($rows->no_of_persons);
                    $temp['reservation_date'] = null_checker(convertDate($rows->booking_date));
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_booking_pending_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_booking_pending_failed');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: booking_cancel
     * Description:   To Get Booking Cancel List
    */

    function booking_cancel_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

        $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required'); 
        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');   
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $language  = extract_value($data, 'language', '');
            $page_no  = extract_value($data,'page_no',1);  
            $offset   = get_offsets($page_no);
            $user_id  = extract_value($data, 'user_id', '');
            $upload_url = base_url().'uploads/offer/thumb/';

            $options = array('table' => 'mw_booking',
                'select' => 'user_id,id,name,email,mobile,no_of_persons,comment,booking_date',
                'order' => array('id' => 'desc'),
                'where' => array('user_id'=>$user_id,'status' =>3), 
                'limit' => array(10 => $offset)
                
            );
            if ($language == "el") {
                $options['select'] = 'user_id,id,name,email,mobile,no_of_persons,comment,booking_date';
            }
              /* To get booking list from booking table */
            $list = $this->Common_model->customGet($options);
            /* check for image empty or not */
           
            if (!empty($list)) {

                $eachArr = array();
                
                $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                $total_records = getAllCount('mw_booking',array('user_id'=>$user_id,'status' =>3));
                
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
                    $temp['booking_id']       = null_checker($rows->id);
                    $temp['user_id']          = null_checker($rows->user_id);
                    $temp['full_name']        = null_checker($rows->name);
                    $temp['no_of_persons']    = null_checker($rows->no_of_persons);
                    $temp['reservation_date'] = null_checker(convertDateTime($rows->booking_date));
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_booking_cancel_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_booking_cancel_failed');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: bookingcart
     * Description:   To Insert Cart Details
    */

    function bookingcart_post() {
        $data = $this->input->post();
        $return['code']     = 200;
        $return['response'] = new stdClass();

        // $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        $this->form_validation->set_rules('store_id', 'Store Id', 'trim|required');
        $this->form_validation->set_rules('menu_jsondata', 'Menu List', 'trim|required');        
        $this->form_validation->set_rules('amount', 'Cart Total Amount', 'trim|required');        
        $this->form_validation->set_rules('booking_date', 'Booking Date', 'trim|required');        
        if ($this->form_validation->run() == FALSE) {
            $error              = $this->form_validation->rest_first_error_string();
            $return['status']   = 0;
            $return['message']  = $error;
        } else {
            $language                   = extract_value($data, 'language', '');
            $user_id                    = extract_value($data, 'user_id', '');
            $booking_date               = extract_value($data, 'booking_date', '');
            
            $insertArr                  = array();
            $insertArr['user_id']       = $user_id;
            $insertArr['store_id']      = extract_value($data, 'store_id', '');
            $insertArr['amount']        = extract_value($data, 'amount', '');
            $insertArr['menu_jsondata'] = $data['menu_jsondata'];
            $insertArr['schedule']      = date('Y-m-d H:i:s',strtotime($booking_date));
            $insertArr['status']        = 1;
            $insertArr['created_date']  = date('Y-m-d H:i:s');

            $storeId = extract_value($data, 'store_id', '');
            $option = array(
                'table' => 'mw_rooms',
                'where' =>array('store' => $storeId),
                'single'=> true
            );
            
            $floors = $this->Common_model->customGet($option);
            $insertArr['floor_id'] = (!empty($floors)) ? $floors->id : 3;
            
            $opt = array(
                    'table' => BOOKINGCART,
                    'select'=> 'id',
                    'where' => array('user_id'=>$user_id),
                    'single'=> true 
                );
            $cartdata = array();
            $cartdata = $this->Common_model->customGet($opt);
            if(!empty($cartdata)){
                $delopt = array(
                    'table' => BOOKINGCART,
                    'where' => array('user_id'=>$user_id)
                );
                $this->Common_model->customDelete($delopt);
            }
            $options = array(
                'table' => BOOKINGCART,
                'data'  => $insertArr
            );    
            
            /* insert data into booking table */
            $insert = $this->Common_model->customInsert($options);
        
            if ($insert) {

                /* return success response */
                $insertArr['total_wallet_point']  = $this->Common_model->get_user_wallet_point($user_id);
                $insertArr['conversion_rate'] = $this->Common_model->get_conversion_rate();
                $insertArr['cart_id'] = $insert;
                $insertArr['menu_jsondata'] = json_decode($data['menu_jsondata']);
                $return['response'] = $insertArr;
                $return['status'] = 1;
                $return['message'] = 'success';
            }else {
                $return['status'] = 0;
                $return['message'] = 'failed';
            }
        }
        $this->response($return);
    }


    /**
     * Function Name: braintree_token
     * Description:   To Generate Braintree nonce
    */
    public function braintree_nonce_post(){
        $data = $this->input->post();
        $return['code']     = 200;
        $return['response'] = new stdClass();
        // $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');        
        if ($this->form_validation->run() == FALSE) {
            $error              = $this->form_validation->rest_first_error_string();
            $return['status']   = 0;
            $return['message']  = $error;
        }else{
            $user_id  = extract_value($data, 'user_id', '');
            $option = array(
                'table' => USERS,
                'select'=> 'braintree_customer_id,name,email,phone_number',
                'where' => array('id'=>$user_id),
                'single'=> true 
            ); 
            $userdata = $this->Common_model->customGet($option);
            if(!empty($userdata->braintree_customer_id)){
                $CustomerId =  (string)$userdata->braintree_customer_id;
            }else{
                $result = Braintree_Customer::create([
                    'firstName' => $user_details->name,
                    'lastName' => $user_details->name,
                    'email' => $user_details->email,
                    'phone' => $user_details->phone_number,
                    ]);
                $CustomerId = (string)$result->customer->id;
    
                $options = array(
                    'table' => USERS,
                    'data'  => array('braintree_customer_id'=>$CustomerId),
                    'where' => array("id" => $user_id) 
                );
                $this->Common_model->customUpdate($options);
            }
            $clientToken = Braintree_ClientToken::generate([
                "customerId" => $CustomerId
                ]);
            if($clientToken != ''){
                $return['status']   = 1;
                $return['response'] = $clientToken; 
                $return['message']  = $this->lang->line('api_braintree_token_success');
            }else{
                $return['status']   = 0; 
                $return['message']  = $this->lang->line('api_braintree_token_failed');
            }

        }
        $this->response($return);     
    }  

    /**
     * Function Name: braintree_token
     * Description:   Payment by Braintree nonce
    */

    public function braintree_payment_post(){
        $data = $this->input->post();
        $return['code']     = 200;
        $return['response'] = new stdClass();
        // $this->form_validation->set_rules('login_session_key', 'Login session key', 'trim|required|callback__validate_login_session_key');
        $this->form_validation->set_rules('user_id', 'User Id', 'trim|required');
        // $this->form_validation->set_rules('nonce', 'Nonce', 'trim|required');                 
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');        
        $this->form_validation->set_rules('cart_id', 'Cart Id', 'trim|required');        
        $this->form_validation->set_rules('store_id', 'Store Id', 'trim|required');        
        $this->form_validation->set_rules('floor_id', 'Floor Id', 'trim|required');        
                
        if ($this->form_validation->run() == FALSE) {
            $error              = $this->form_validation->rest_first_error_string();
            $return['status']   = 0;
            $return['message']  = $error;
        }else{
            $user_id      = extract_value($data, 'user_id', '');
            $cart_id      = extract_value($data, 'cart_id', '');
            $nonce        = extract_value($data, 'nonce', '');
            $store_id     = extract_value($data, 'store_id', '');
            $floor_id     = extract_value($data, 'floor_id', '');
            $amount       = extract_value($data, 'amount', '');//actual amount
            $total_billing_amount  = $data['total_billing_amount'];
            $redeemPoint  = $data['redeem_point'];
            $total_point  = $data['total_point'];
            $payment_type  = $data['payment_type'];
            
            $cartData = $this->db->get_where(BOOKINGCART,array('id'=>$cart_id))->row_array();
            
            //Check cart is empty or not

            if(!empty($cartData)){

                //Check amount and nonce are not empty

                if($nonce != '' && $payment_type == 2){
                    
                    //payment_type 
                    // 1 = COD
                    // 2 = Braintree 

                    //Make Payment using BRAINTREE
                    $result = Braintree_Transaction::sale([
                        'amount'             => (int)$amount,
                        'paymentMethodNonce' => $nonce,
                        'options' => [
                            'submitForSettlement' => True
                        ]
                    ]);
                    
                    //Check payment successfull ?
                    if(($result->success == true) && (CARTCURRENCY == $result->transaction->currencyIsoCode) && ($amount == $result->transaction->amount)){
                        
                        /*
                         * Insert data into Booking table
                         * Insert data into User wallet history
                         * Update points into user wallet
                         * Delete data from Booking CART  
                        */

                        $is_payment = 2;
                        $bool = $this->payment_process($user_id,$store_id,$floor_id,$amount,$total_billing_amount,$redeemPoint,$total_point,$cartData['schedule'],$cartData['menu_jsondata'],$payment_type,$is_payment);
                        
                        if($bool){
                            $return['status']   = 1; 
                            $return['message']  = $this->lang->line('api_braintree_payment_success'); 
                        }else{
                            $return['status']   = 0; 
                            $return['message']  = $this->lang->line('api_braintree_payment_failed');    
                        }
                        
                    }else{
                        
                        //Payment response failed
                        $return['status']   = 0; 
                        $return['message']  = $this->lang->line('api_braintree_payment_failed');
                    } 
                }else{

                    /*CASE 1: User Redeem point for complete payment in that case amount 
                              will become 0 so we don't make payment.  
                    */
                    
                    /*
                        * Insert data into Booking table
                        * Insert data into User wallet history
                        * Update points into user wallet
                        * Delete data from Booking CART  
                    */
                    $is_payment = 1;
                    $bool = $this->payment_process($user_id,$store_id,$floor_id,$amount,$total_billing_amount,$redeemPoint,$total_point,$cartData['schedule'],$cartData['menu_jsondata'],$payment_type,$is_payment);
                    
                    if($bool){
                        $return['status']   = 1; 
                        $return['message']  = $this->lang->line('api_braintree_payment_success'); 
                    }else{
                        $return['status']   = 0; 
                        $return['message']  = $this->lang->line('api_braintree_payment_failed');    
                    }
               } 
            }else{
                $return['status']   = 0; 
                $return['message']  = "Cart Empty";
            }
        }
        $this->response($return);        
    }

    public function payment_process($user_id,$store_id,$floor_id,$amount,$total_billing_amount,$redeemPoint,$total_point,$schedule,$menu_jsondata,$payment_type,$is_payment){
        $datetime = $schedule;
        $date = date('Y-m-d',strtotime($datetime));
        $time = date('H:i:s',strtotime($datetime));
        $menu_info = array();
        $menu_info['menu_data'] = json_decode($menu_jsondata);
        $menu_info['payment'] = $amount;
        $menu_info = json_encode($menu_info);

        $userdata = array();
        $userdata = $this->db->get_where(USERS,array('id'=>$user_id))->row_array();
        // pr($userdata);
        $bookingArr = array(
            'user_id'       => $user_id,
            'store_id'      => $store_id,
            'floor_id'      => $floor_id,
            'booking_date'  => $date,
            'time_from'     => $time,
            'time_to'       => '00:00:00',
            'payment'       => $amount,
            'payment_method'=> $payment_type,
            'is_payment'    => $is_payment,
            'name'          => $userdata['name'],
            'email'         => $userdata['email'],
            'mobile'        => $userdata['phone_number'],
            'status'        => 1,
            'menu_info'     => $menu_info,
            'created'       => date('Y-m-d H:i:s')
            /*'table_id'      => 0,
            'no_of_persons' => 0,
            'place'         => '',
            'countries_isd_code'=>'',
            'comment'           =>'',
            'referrer'          =>'',    
            'special_request_id'=>''*/

        );
        
        $option = array(
            'table' => 'mw_booking',
            'data'  => $bookingArr 
        ); 
        $booking_id = $this->Common_model->customInsert($option);
        if($booking_id){

            if((isset($total_billing_amount) && $total_billing_amount != 0 && $total_billing_amount != '') && (isset($redeemPoint) && $redeemPoint != 0 && $redeemPoint != '') ){                   
                if($total_point > 0){
                    if($total_point >= $redeemPoint){
                        $this->Common_model->withdrawalpoint($user_id,$booking_id,$total_point,$total_billing_amount,$redeemPoint,$floor_id);            
                    }
                }
            }
            if($payment_type == 2){
            $this->Common_model->creditpoint($user_id,$booking_id,$total_billing_amount,$amount,$floor_id,$redeemPoint,$store_id);        
            }

            $opt = array('table'=>BOOKINGCART,'where'=>array('user_id'=>$user_id));
            $this->Common_model->customDelete($opt);
            $return = 1;    
        }else{
            $return = 0; 
        }
        return $return;
    }

}


/* End of file Booking.php */
/* Location: ./application/controllers/api/v1/Booking.php */
?>