<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Wallethistory extends Common_Controller {

    public $data = array();
    public $file_data = "";

    public function __construct() {
        parent::__construct();
    }

    public function index($user_id) {
        $this->data['url'] = base_url().'wallethistory';
        $this->data['pageTitle'] = lang('wallet_history');
        $this->data['parent'] = "Wallethistory";
        $where = '';    
        if($this->session->userdata('role') == 'store'){ 
            $option = array(
                'table'=>'mw_rooms',
                'select'=>'id',
                'where'=>array('store'=>$this->session->userdata('id'))
            );
            $floorArr = $this->Common_model->customGet($option);
            if(!empty($floorArr)){
                $floorArr = json_decode(json_encode($floorArr), true);
                $floorArr = array_column($floorArr, 'id');
                $floorArr = array(1,2,3);
                $floorstr = implode(',',$floorArr);
                if($floorstr != ''){
                    $where .= " AND floor_id IN(".$floorstr.")";
                }
            }
        }
        $sql = "SELECT uwh.id,uwh.order_id,uwh.user_id,uwh.total_billing_amount,uwh.actual_payment,uwh.earn_point,uwh.use_point,uwh.created_date,uwh.floor_id,u.name
                FROM ".USERWALLETHISTORY." AS uwh
                JOIN ".USERS." AS u ON u.id = uwh.user_id
                WHERE user_id = ".$user_id."";
        if($where != ''){
            $sql .= $where;
        }        
        $sql .= " LIMIT 0,5";
        $this->data['list'] = $this->Common_model->customQuery($sql);
        $this->template->load_view('default', 'list', $this->data, 'inner_script');
    }

}
