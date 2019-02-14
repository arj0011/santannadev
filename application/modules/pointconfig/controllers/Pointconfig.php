<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pointconfig extends Common_Controller {

    public $data = array();
    public $file_data = "";

    public function __construct() {
        parent::__construct();
    }  

    /**
     * @method index
     * @description listing display
     * @return array
     */
     public function index() { 
        $this->data['parent'] = "point_config";
        $this->data['title'] = lang("point_config");
        $storelist = array();
        $list = array();
        /*$options = array(
          'table' => STORE, 
          'select' => 'id,store_name',
          'where'=>array('is_active'=> 1)
        ); */         
        // $storelist = $this->Common_model->customGet($options);
        $sql = "SELECT s.store_name,s.id AS storeID,pc.subset,pc.keyto,pc.value 
                FROM store AS s 
                LEFT JOIN point_config AS pc ON pc.store_id = s.id";
        $storelist = $this->Common_model->customQuery($sql);
      
        $opt = array(
          'table' => POINTCONFIG, 
          'select' => 'id,subset,keyto,value',
          'where'=>array('keyto'=>'point_to_money'),
          'single'=>true
        );          
        $list = $this->Common_model->customGet($opt);
    
        $this->data['storelist'] = $storelist;
        $this->data['list'] = $list;
        $this->template->load_view('default', 'add', $this->data, 'inner_script');
    }
 
    /**
     * @method open_model
     * @description load model box
     * @return array
     */
 
    public function edit_point(){
      
      $this->form_validation->set_rules('ptom_point', lang('point_req'), 'required|trim|numeric|xss_clean');
      $this->form_validation->set_rules('ptom_money', lang('money_req'), 'required|trim|numeric|xss_clean');
      if($this->form_validation->run() == true) {
      
        $flag = 0;
        
        /*MONEY TO POINT ADD AND EDIT*/

        $i = 0;
        foreach ($_POST['store_id'] as $store) {
          $MtoPArr = array();
          $opt = array(
            'select'=>'id',
            'table' =>POINTCONFIG,
            'where' =>array('store_id'=>$store,'keyto'=>'money_to_point')
          );
          $MtoPArr = $this->Common_model->customGet($opt);
          if(!empty($MtoPArr)){
            if(($_POST['mtop_money'][$i] != '' && $_POST['mtop_money'][$i] != 0) && ($_POST['mtop_point'][$i] != '' && $_POST['mtop_point'][$i] != 0)){
                $updatemtopArr = array(
                  'subset'=>$_POST['mtop_money'][$i],
                  'value' => $_POST['mtop_point'][$i]
                );
                $opt1 = array(
                  'table' => POINTCONFIG,
                  'data'  => $updatemtopArr,
                  'where' => array('store_id'=>$store,'keyto'=>'money_to_point')
                );
                $affectrow = $this->Common_model->customUpdate($opt1);
                $flag = 2;  
            }
          }else{
            if(($_POST['mtop_money'][$i] != '' && $_POST['mtop_money'][$i] != 0) && ($_POST['mtop_point'][$i] != '' && $_POST['mtop_point'][$i] != 0)){
                $insertmtopArr = array(
                  'store_id'=> $store,
                  'keyto'   => 'money_to_point',
                  'subset'  => $_POST['mtop_money'][$i],
                  'value'   => $_POST['mtop_point'][$i]
                );
                $opt1 = array(
                  'table' => POINTCONFIG,
                  'data'  => $insertmtopArr
                );
                $affectrow = $this->Common_model->customInsert($opt1);
                $flag = 1;
            }
          }  
          $i++;
        }
        
        /*POINT TO MONEY ADD AND EDIT*/
        $option = array(
          'select'=>'id',
          'table' =>POINTCONFIG,
          'where' =>array('keyto'=>'point_to_money'),
          'single'=>true
        );
        
        $PtoMArr = $this->Common_model->customGet($option);
        if(empty($PtoMArr)){
            $insertptomArr = array(
              'subset'=>$this->input->post('ptom_point'),
              'keyto'=>'point_to_money',
              'value'=>$this->input->post('ptom_money')
              );
            $options = array(
            'table'=>POINTCONFIG,
            'data'=>$insertptomArr
            );
            $affectrow = $this->Common_model->customInsert($options);
            $flag = 1;
        }else{
          $id = $PtoMArr->id;
          $updateptomArr = array(
              'subset'=>$this->input->post('ptom_point'),
              'keyto'=>'point_to_money',
              'value'=>$this->input->post('ptom_money')
              );
            $options = array(
            'table'=>POINTCONFIG,
            'data'=>$updateptomArr,
            'where'=>array('id'=>$id)
            );
            $affectrow = $this->Common_model->customUpdate($options);
            $flag = 2;
        }
        /*END*/
        
        if($flag == 1 || $flag == 2){
          if($flag == 1){
            $response = array('status' => 1, 'message' => lang('mtop_ptom_success_insert'), 'url' => base_url('pointconfig'));
          }
          if($flag == 2){
            $response = array('status' => 1, 'message' => lang('mtop_ptom_success_update'), 'url' => base_url('pointconfig'));
          }
      
        }else{
          $response = array('status' => 0, 'message' => lang('mtop_ptom_failed'), 'url' => base_url('pointconfig'));
        }
      }else{
        $messages = (validation_errors()) ? validation_errors() : '';
        $response = array('status' => 0, 'message' => strip_tags($messages));
      }
      echo json_encode($response);  
    }
 
}
