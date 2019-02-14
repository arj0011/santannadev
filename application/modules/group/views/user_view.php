<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
            </li>
            
            <li>
                <a href="<?php echo site_url('group/view_user').'/'.$this->uri->segment(3);?>"><?php echo lang('view_users');?></a>
            </li>
        </ol>
    </div>
    <div class="col-lg-2">
 
    </div>
</div>
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                   <!--  <div class="btn-group " href="#">
                        <a href="javascript:void(0)"  onclick="open_modal('group')" class="btn btn-primary">
                            <?php echo lang('add_group');?>
                        <i class="fa fa-plus"></i>
                        </a>
                    </div> -->
                </div>
                <div class="ibox-content">
                 <div class="row">
                      <?php $message = $this->session->flashdata('success');
                            if(!empty($message)):?><div class="alert alert-success">
                                <?php echo $message;?></div><?php endif; ?>
                       <?php $error = $this->session->flashdata('error');
                            if(!empty($error)):?><div class="alert alert-danger">
                                <?php echo $error;?></div><?php endif; ?>
                     <div id="message"></div>
                    <div class="col-lg-12" style="overflow-x: auto">
                    <table class="table table-bordered table-responsive" id="common_datatable">
                        <thead>
                            <tr>
                                
                                <th><?php echo lang('type');?></th>
                                <th><?php echo lang('user_name');?></th>
                                <th><?php echo lang('action');?></th>
                            </tr>
                        </thead>
                        <tbody>
                          <?php 
                          
                            if (isset($list) && !empty($list)):
                                $rowCount = 0;
                                foreach ($list as $rows):
                                    $rowCount++;
                                    ?>
                            <tr>
                          
                            <td><?php echo allGroups($rows->type);?></td>

                           <td><?php $user_name = json_decode($rows->user_name);
                                if(!empty($user_name)){
                                    foreach($user_name as $mod){
                                 $option = array(
                                    'table' => 'users',
                                    'where' => array('id' => $mod),
                                    'single' => true
                                );
                                $users = $this->Common_model->customGet($option);
                                
                                if(!empty($users)){

                                echo " <div class='text-danger'> <i class='fa fa-check'></i> ".strtoupper($users->name).'</div>';

                                }}}?>
                                
                                </td>
                        
                            <td class="actions">
                             <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('group','group_edit','<?php echo encoding($rows->group_id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                            <a href="javascript:void(0)" onclick="deleteFn('group','group_id','<?php echo encoding($rows->group_id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>
                            </td>
                            </tr>
                            <?php endforeach; endif;?>
                        </tbody>
                    </table>
                  </div>
                </div>
            </div>
                <div id="form-modal-box"></div>
        </div>
    </div>
</div>
<div id="message_div">
    <span id="close_button"><img src="<?php echo base_url();?>assets/img/close.png" onclick="close_message();"></span>
    <div id="message_container"></div>
</div>