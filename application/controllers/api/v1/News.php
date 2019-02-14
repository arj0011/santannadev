<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * This Class used as REST API for news
 * @package   CodeIgniter
 * @category  Controller
 * @author    Developer
 */
class News extends Common_API_Controller {

    function __construct() {
        parent::__construct();
    }

    /**
     * Function Name: news_category
     * Description:   To Get News Category
     */
    function news_category_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();

       $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            
            $language = extract_value($data, 'language', '');
            $page_no  = extract_value($data,'page_no',1);  
            $offset   = get_offsets($page_no);
             $upload_url = base_url().'uploads/news/';

              $options = array('table' => NEWS_CATEGORY,
                'select' => 'id,category_name_en as category_name,category_image',
                'order' => array('id' => 'desc'), 
                'limit' => array(10 => $offset)
                
            );
             
            if ($language == "el") {
                $options['select'] = 'id,category_name_el as category_name,category_image';
            }
             /* Get news category from news*/
             $list = $this->Common_model->customGet($options);
            

                $options_data = array('table' => NEWS_TITLE_IMAGE,
                'select' => 'title_id,image',
                'single' =>true
               );
               $list_data = $this->Common_model->customGet($options_data);
               
               if(!empty($list_data->image))
                 {
                        $title_image = $upload_url.$list_data->image;
                 } else{
                        $title_image = base_url().'assets/img/no_image.jpg';
                 } 
                   
                if (!empty($list)) {
                  $eachArr = array();
                  $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                  $total_records = getAllCount(NEWS_CATEGORY);
               
                  if($total_records > $total_requested){   

                    $has_next = TRUE;                    
                  }else{                        
                    $has_next = FALSE;                    
                  }

                  

                  foreach ($list as $rows):
                     if(!empty($rows->category_image))
                  {
                          $image = $upload_url.$rows->category_image;
                  } else{
                            /* set default image if empty */
                          $image = base_url().'assets/img/no_image.jpg';
                  }
                    $temp['category_id']    = null_checker($rows->id);
                    $temp['category_name']  = null_checker($rows->category_name);
                    $temp['category_image'] = $image;
                    $eachArr[] = $temp; 
                   endforeach;

                     $temp2['title_image']     = $title_image;
                    //$eachArr[] = $res;
                    /* return success response */
                    
                    $return['title_image'] = $temp2;
                    $return['response'] = $eachArr;
                    $return['status'] = 1;
                    $return['has_next'] =  $has_next; 
                    $return['message'] = $this->lang->line('api_news_cat_success');
            } else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_news_cat_failed');
            }
        }
            $this->response($return);
    }

    /**
     * Function Name: news_list
     * Description:   To Get News List
     */
    function news_list_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('category_id', 'Category Id', 'trim|required');
        $this->form_validation->set_rules('page_no', 'Page No', 'trim|numeric|callback__pageno_min_value');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
            $cat_id     = extract_value($data, 'category_id', '');
            $language   = extract_value($data, 'language', '');
            $page_no  = extract_value($data,'page_no',1);  
            $offset   = get_offsets($page_no);
            $upload_url = base_url().'uploads/news/';

            $options = array('table' => NEWS . ' as news',
                'select' => 'news.id,news.news_category,news.news_heading_en as news_heading,news.news_content_en as news_content,news.date,news.news_location, news_image,' . NEWS_CATEGORY . '.category_name_en as category_name',
                'where' => array('news.news_category' => $cat_id),
                'limit' => array(10 => $offset),
                'order' => array('id' => 'desc'),
                'join' => array(NEWS_CATEGORY => NEWS_CATEGORY . '.id=news.news_category')
            );
            if ($language == "el") {
                $options['select'] = 'news.id,news.news_category,news.news_heading_el as news_heading,news.news_content_el as news_content,news.date,news.news_location, news_image,' . NEWS_CATEGORY . '.category_name_en as category_name';
            }
              /* To get news list from news table */
            $list = $this->Common_model->customGet($options);
          
          
            
            if (!empty($list)) {
                 $total_requested = (int) $page_no * 10; 

                  /* Get total records */  
                  $total_records = getAllCount(NEWS,array('news_category'=>$cat_id));
               
                  if($total_records > $total_requested){   

                    $has_next = TRUE;                    
                  }else{ 

                    $has_next = FALSE;                    
                  }

                $eachArr = array();

                foreach ($list as $rows):
                    /* check for image empty or not */
                    if(!empty($rows->news_image))
                  {
                          $image = $upload_url.$rows->news_image;
                  } else{
                            /* set default image if empty */
                          $image = base_url().'assets/img/no_image.jpg';
                  }
                    $temp['news_id']       = null_checker($rows->id);
                    $temp['category_id']   = null_checker($rows->news_category);
                    $temp['news_heading']  = null_checker($rows->news_heading);
                    $temp['news_content']  = null_checker($rows->news_content);
                    $temp['news_location'] = null_checker($rows->news_location);
                    $temp['category_name'] = null_checker($rows->category_name);
                    $temp['create_date']   = null_checker($rows->date);
                    $temp['news_image']    = $image;
                    
                    $eachArr[] = $temp;
                endforeach;
                 /* return success response*/
                $return['status'] = 1;
                $return['response'] = $eachArr;
                $return['has_next'] =  $has_next; 
                $return['message'] = $this->lang->line('api_news_success');
            }else {
                $return['status'] = 0;
                $return['message'] = $this->lang->line('api_news_failed');
            }
        }
        $this->response($return);
    }

    /**
     * Function Name: news_details
     * Description:   To Get News Details
     */

    function news_details_post() {
        $data = $this->input->post();
        $return['code'] = 200;
        $return['response'] = new stdClass();
        $this->form_validation->set_rules('id', 'News Id', 'trim|required');
        if ($this->form_validation->run() == FALSE) {
            $error = $this->form_validation->rest_first_error_string();
            $return['status'] = 0;
            $return['message'] = $error;
        } else {
          $news_id    =  extract_value($data, 'id', '');
          $language   = extract_value($data, 'language', '');
          $upload_url = base_url().'uploads/news/';

          $options = array('table' => NEWS,
            'select' => 'id,news_heading_en as news_heading,news_content_en as news_content,news_location,latitude,longitude,news_url,date,news_image',
            'where' => array('id' => $news_id),
            'single' =>true

        );
         if ($language == "el") {
                $options['select'] = 'id,news_heading_el as news_heading,news_content_el as news_content,news_location,latitude,longitude,news_url,date, news_image';
        }
           
           /* To get news details from news table accorind id */
          $list = $this->Common_model->customGet($options);
         
            /* check for image empty or not */
           if(!empty($list->news_image))
              {
                      $image = $upload_url.$list->news_image;
              } else{
                       /* set default image if empty */
                      $image = base_url().'assets/img/no_image.jpg';
              }
           if (!empty($list)) {

              $eachArr = array();

                
                    $eachArr['id']             = null_checker($list->id);
                    $eachArr['news_heading']   = null_checker($list->news_heading);
                    $eachArr['news_content']   = null_checker($list->news_content);
                    $eachArr['news_location']  = null_checker($list->news_location);
                    $eachArr['latitude']       = null_checker($list->latitude);
                    $eachArr['longitude']      = null_checker($list->longitude);
                    $eachArr['news_url']       = null_checker($list->news_url);
                    $eachArr['create_date']    = null_checker($list->date);
                    $eachArr['news_image']     = $image;
                    
                    
                
             /* return success response*/
            $return['status'] = 1;
            $return['response'] = $eachArr;
            $return['message'] = $this->lang->line('api_news_details_success');
        } else {
            $return['status'] = 0;
            $return['message'] = $this->lang->line('api_news_details_failed');
        }
    }
        $this->response($return);
    }

}

/* End of file News.php */
/* Location: ./application/controllers/api/v1/News.php */
?>