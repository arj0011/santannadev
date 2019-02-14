<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for company
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class Company extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

    /**
     * Function Name: app
     * Description:   Get company session detail which application installed
     */
    function app_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('device_type', 'Device Type', 'trim|required|in_list[ANDROID,IOS]');
        $this->form_validation->set_rules('device_id', 'Device ID', 'trim|required');
        //$this->form_validation->set_rules('device_token', 'Device Token', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $UpdateData = array();
            $UpdateData['device_type'] = extract_value($data, 'device_type', NULL);
            $UpdateData['device_id'] = extract_value($data, 'device_id', NULL);
            $UpdateData['device_key'] = extract_value($data, 'device_token', NULL);
            $UpdateData['added_date'] = date('Y-m-d H:i:s');

            $where['device_type'] = extract_value($data, 'device_type', NULL);
            $where['device_id'] = extract_value($data, 'device_id', NULL);
            $device = $this->Common_model->getsingle(USERS_DEVICE_HISTORY, $where);
            if (!empty($device)) {
                $this->Common_model->updateFields(USERS_DEVICE_HISTORY, $UpdateData, $where);
            } else {
                 $this->Common_model->insertData(USERS_DEVICE_HISTORY, $UpdateData);
            }


            $upload_url = base_url() . 'uploads/app/';
            $offer_url = base_url() . 'uploads/offer/thumb/';
            $language    = extract_value($data, 'language', '');
            
            $options = array('table' => ADMIN,
                'select' => 'id as company_id,company_name,CONCAT("' . $upload_url . '",company_logo) as company_logo,company_image',
                'single' => true
            );
            /* Get company details from company table */
            $list = $this->Common_model->customGet($options);

            $today = date('Y-m-d');

            $options_data = array('table' => OFFER.' as offers',
                'select' => 'offers.id,offers.offer_name_en as offer_name,offers.description_en as description,offers.discounts_in_percent,offers.image,user_offers.is_used',
                'join' => array(array('user_offers','user_offers.offer_id=offers.id','left outer')),
                'order' => array('offers.id' => 'desc'),
                'where' => array('offers.is_active' =>1,'user_offers.offer_id' => null),
                'limit' => 2
                
            );
            if ($language == "el") {
                $options_data['select'] = 'offers.id,offers.offer_name_el as offer_name,offers.description_el as description,offers.discounts_in_percent,offers.image,user_offers.is_used';
            }

            // $options_data = array('table' => OFFER,
            //     'select' => 'id,from_date,to_date,offer_name_en as offer_name,description_en as description,discounts_in_percent,image',
            //     'where' => array('is_active' => 1),
            //     'limit' => 2,
            //     'order' => array('id' => 'desc')
            // );

            // if ($language == "el") {
            //     $options_data['select'] = 'id,from_date,to_date,offer_name_el as offer_name,description_el as description,discounts_in_percent,image';
            // }
            $list_data = $this->Common_model->customGet($options_data);



            if (!empty($list)) {

                $eachArr = array();
                $temp2 = array();

                foreach ($list_data as $rows):

                    if (!empty($rows->image)) {
                        $offer_image = $offer_url . $rows->image;
                    } else {
                        /* set default image if empty */
                        $offer_image = base_url() . 'assets/img/no_image.jpg';
                    }

                    $res['id'] = null_checker($rows->id);
                    $res['offer_name'] = null_checker($rows->offer_name);
                    $res['description'] = null_checker($rows->description);
                    $res['discounts_in_percent'] = null_checker($rows->discounts_in_percent);
                    $res['image'] = $offer_image;
                    $temp2[] = $res;


                endforeach;

                $eachArr['company_id'] = null_checker($list->company_id);
                $eachArr['company_name'] = null_checker($list->company_name);
                $eachArr['company_logo'] = null_checker($list->company_logo);
                $company_image = json_decode(null_checker($list->company_image));
                $temp = array();
                if (!empty($company_image)):
                    foreach ($company_image as $img):
                        $images['image'] = base_url() . 'uploads/app/thumb/' . $img;
                        $temp[] = $images;

                    endforeach;
                endif;


                /* Return success response */

                $eachArr['company_image'] = $temp;
                $eachArr['offers'] = $temp2;
                $return['status'] = 1;
                $return['response'] = $eachArr;

                $return['message'] = $this->lang->line('api_company_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_company_failed');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: info
     * Description:   Company info
     */
    function info_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('company_id', 'Company Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $company_id = extract_value($data, 'company_id', '');
            $language = extract_value($data, 'language', '');

            $options = array('table' => COMPANY_INFO . ' as info',
                'select' => 'info.id,info.legal_text_en as legal_text,info.version,info.copyright,info.contact_images,info.info_files,info.contact_title_en as contact_title,' . ADMIN . '.company_name',
                'where' => array('info.company_id' => $company_id),
                'join' => array(ADMIN => ADMIN . '.id=info.company_id')
            );
            if ($language == "el") {
                $options['select'] = 'info.id,info.legal_text_el as legal_text,info.version,info.copyright,info.contact_images,info.info_files,info.contact_title_el as contact_title,' . ADMIN . '.company_name';
            }
            /* Get company info from company_info table */
            $list = $this->Common_model->customGet($options);
            if (!empty($list)) {
                $eachArr = array();

                foreach ($list as $rows):

                    $temp['legal_text'] = null_checker($rows->legal_text);
                    $temp['version'] = null_checker($rows->version);
                    $temp['copyright'] = null_checker($rows->copyright);
                    $temp['contact_title'] = null_checker($rows->contact_title);
                    $contact_images = json_decode(null_checker($rows->contact_images));
                    $temp2 = array();
                    if (!empty($contact_images)):
                        foreach ($contact_images as $img):
                            $images['image'] = base_url() . 'uploads/app/' . $img;
                            $temp2[] = $images;
                        endforeach;
                    endif;
                    $temp['contact_images'] = $temp2;
                    $info_files = unserialize(null_checker($rows->info_files));
                    $temp3 = array();
                    if (!empty($info_files)):
                        foreach ($info_files as $file):
                            $files['title'] = $file['title'];
                            $files['filename'] = base_url() . 'uploads/app/' . $file['filename'];
                            $temp3[] = $files;
                        endforeach;
                    endif;
                    $temp['info_files'] = $temp3;
                    $eachArr[] = $temp;
                endforeach;
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['message'] = $this->lang->line('api_company_info_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_company_info_failed');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: add_feedback
     * Description:   To Add Feedback
     */
    function add_feedback_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        //$this->form_validation->set_rules('company_id', 'Company Id', 'trim|required');
        //$this->form_validation->set_rules('restaurant_id', 'Restaurant Id', 'trim|required');
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('contact_no', 'Contact No', 'trim|required');
        $this->form_validation->set_rules('feedback', 'Feedback', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $insertArr = array();
            $language = extract_value($data, 'language', '');

            $insertArr['company_id'] = 1;
            $insertArr['restaurant_id'] = 1;
            $insertArr['full_name'] = extract_value($data, 'full_name', '');
            $insertArr['email'] = extract_value($data, 'email', '');
            $insertArr['contact'] = extract_value($data, 'contact_no', '');
            $insertArr['feedback'] = extract_value($data, 'feedback', '');
            $insertArr['feedback_time'] = date('Y-m-d H:i:s');

            $options = array('table' => FEEDBACK_OF_RESTAURANT,
                'data' => $insertArr,
            );

            /* insert data into feedback_of_restaurant table */
            $insert = $this->Common_model->customInsert($options);
            if ($insert) {
                /* return success response */
                $return['status'] = 1;
                $return['message'] = $this->lang->line('api_feedback_success');
            } else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_feedback_error');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: city_list
     * Description:   To Get City List
     */
    function city_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('id', 'City Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $city_id = extract_value($data, 'id', '');
            $language = extract_value($data, 'language', '');

            $options = array('table' => COMPANY_CITY,
                'select' => 'id,title_en as title,description_en as description,site_name_en as site_name,location_en as location,company_images',
                'where' => array('id' => $city_id)
            );
            if ($language == "el") {
                $options['select'] = 'id,title_el as title,description_el as description,site_name_el as site_name,location_el as location,company_images';
            }
            /* Get data from comany_city table */
            $list = $this->Common_model->customGet($options);
            if (!empty($list)) {
                $eachArr = array();

                foreach ($list as $rows):

                    $temp['title'] = null_checker($rows->title);
                    $temp['description'] = null_checker($rows->description);
                    $temp['site_name'] = null_checker($rows->site_name);
                    $temp['location'] = null_checker($rows->location);
                    $company_images = json_decode(null_checker($rows->company_images));
                    $temp2 = array();
                    if (!empty($company_images)):
                        foreach ($company_images as $img):
                            $images['image'] = base_url() . 'uploads/city/' . $img;
                            $temp2[] = $images;
                        endforeach;
                    endif;
                    $temp['company_images'] = $temp2;
                    $eachArr[] = $temp;
                endforeach;
                /* return success response */
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['message'] = $this->lang->line('api_city_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_city_error');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: language
     * Description:   To Get Language
     */
    function language_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('language_code', 'Language Code', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $language_code = extract_value($data, 'language_code', '');
            $options = array('table' => LANGUAGE,
                'select' => 'id as language_id,language_code,language_name',
                'where' => array('language_code' => $language_code),
                'single' => true
            );
            /* get language from language table */
            $list = $this->Common_model->customGet($options);
            $eachArr = array();
            if (!empty($list)) {
                $eachArr['language_id'] = null_checker($list->language_id);
                $eachArr['language_code'] = null_checker($list->language_code);
                $eachArr['language_name'] = null_checker($list->language_name);

                /* return success response  */
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['message'] = $this->lang->line('api_language_success');
            } else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_language_error');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: contact_info
     * Description:   To Get Contact Info
     */
    function contact_info_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('company_id', 'Company Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $company_id = extract_value($data, 'company_id', '');
            $language = extract_value($data, 'language', '');
            $upload_url = base_url() . 'uploads/app/';

            $options = array('table' => CONTACTS . ' as contact',
                'select' => 'contact.contact_id,contact.contact_name,contact.phone_no,contact.contact_image,contact.message,contact.message_image,' . ADMIN . '.company_name',
                'where' => array('contact.company_id' => $company_id),
                'join' => array(ADMIN => ADMIN . '.id=contact.company_id')
            );
            /* Get contact info from contacts table */
            $list = $this->Common_model->customGet($options);
            /* check for image empty or not */
            if (!empty($list[0]->contact_image)) {
                $contact_image = $upload_url . $list[0]->contact_image;
            } else {
                /* set default image if empty */
                $contact_image = base_url() . 'assets/img/no_image.jpg';
            }
            if (!empty($list[0]->message_image)) {
                $message_image = $upload_url . $list[0]->message_image;
            } else {
                /* set default image if empty */
                $message_image = base_url() . 'assets/img/no_image.jpg';
            }
            if (!empty($list)) {
                $eachArr = array();

                foreach ($list as $rows):
                    $temp['company_name'] = null_checker($rows->company_name);
                    $temp['contact_name'] = null_checker($rows->contact_name);
                    $temp['phone_no'] = null_checker($rows->phone_no);
                    $temp['message'] = null_checker($rows->message);
                    $temp['contact_image'] = null_checker($contact_image);
                    $temp['message_image'] = null_checker($message_image);

                    $eachArr[] = $temp;
                endforeach;
                /* return success response */
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['message'] = $this->lang->line('api_contact_info_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_contact_info_failed');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: send_email_contact
     * Description:   To Send Contact Email
     */
    function send_email_contact_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('company_id', 'Company Id', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('subject', 'Subject', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');


        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $insertArr = array();
            $language = extract_value($data, 'language', '');
            $message = extract_value($data, 'message', '');
            $subject = extract_value($data, 'subject', '');
            $email = extract_value($data, 'email', '');
            $sent = false;
            $emails = explode(',', $email);

            foreach ($emails as $to) {

                $insertArr['company_id'] = extract_value($data, 'company_id', '');
                $insertArr['email'] = $to;
                $insertArr['subject'] = $subject;
                $insertArr['message'] = $message;
                $insertArr['create_datetime'] = date('Y-m-d H:i:s');

                $options = array('table' => CONTACTS_EMAIL,
                    'data' => $insertArr,
                );
                /* save data into contact_email table */
                $insert = $this->Common_model->customInsert($options);

                $from = 'infofeedback@gmail.com';


                $messages = "<html><body>"
                        . "<h4>Hi,</h4>"
                        . "<h2>" . $message . "</h2></n></n></n>"
                        . "<div>Best Regards,</div>"
                        . "</body</html>";
                $title = "Feedback";
                /* send mail */
                send_mail($message, $subject, $email, $to, $from, $title);
            }
            if ($sent) {
                /* return success response */
                $return['status'] = 1;
                $return['message'] = $this->lang->line('api_contact_email_success');
            } else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_contact_email_failed');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: add_contact
     * Description:   To Add Contacts
     */
    function add_contact_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        //$this->form_validation->set_rules('company_id', 'Company Id', 'trim|required');
        $this->form_validation->set_rules('full_name', 'Full Name', 'trim|required');
        $this->form_validation->set_rules('email', 'Email Id', 'trim|required|valid_email|is_unique[' . CONTACTS . '.email_id]');
        $this->form_validation->set_rules('phone_no', 'Contact No', 'trim|required');
        $this->form_validation->set_rules('message', 'Message', 'trim|required');

        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $insertArr = array();
            $language = extract_value($data, 'language', '');

            $insertArr['company_id'] = 1;
            $insertArr['contact_name'] = extract_value($data, 'full_name', '');
            $insertArr['email_id'] = extract_value($data, 'email', '');
            $insertArr['phone_no'] = extract_value($data, 'phone_no', '');
            $insertArr['message'] = extract_value($data, 'message', '');
            $insertArr['datetime'] = date('Y-m-d H:i:s');

            $options = array('table' => CONTACTS,
                'data' => $insertArr,
            );

            /* insert data into contacts table */
            $insert = $this->Common_model->customInsert($options);
            if ($insert) {
                /* return success response */
                $return['status'] = 1;
                $return['message'] = $this->lang->line('api_contact_success');
            } else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_contact_failed');
            }
        }
        $this->response($return);
    }

}

/* End of file Company.php */
/* Location: ./application/controllers/api/v1/Company.php */
?>