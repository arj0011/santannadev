<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
            </li>
            <li>
                <a href="<?php echo site_url('pointconfig');?>"><?php echo lang('point_config');?></a>
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
                    <div class="btn-group " href="#">
                        <h4><?php echo lang('point_config') ?></h4>
                    </div>
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
                    <div class="col-lg-12">
                    <form class="" id="addFormAjax" role="form" method="post" action="<?php echo base_url('pointconfig/edit_point') ?>" enctype="multipart/form-data">
                        
                    
                       <div class="col-md-12 custom_dollor">
                            <?php if(!empty($storelist)): ?>
                            <div class="col-md-1">
                                <label><span>S.No.</span></label>
                            </div>
                            <div class="col-md-4">
                                <label><span>Store Name</span></label>
                            </div>
                            <div class="col-md-3">
                                <label><span>Money</span></label>
                            </div>
                            <div class="col-md-1">
                               <label><span></span></label>
                            </div>
                            <div class="col-md-3"><label>Point</label></div>

                            <?php
                                $i=1; 
                                foreach($storelist as $row): 
                            ?>
                            <div class="col-md-1">
                                <label><span class="sNoCls"><?php echo $i;?></span></label>
                            </div>
                            <div class="col-md-4">
                                <label><span class="input_dollor"><b><?php echo $row->store_name;?></b></span></label>
                            </div>
                            <div class="col-md-3">
                                <label><span class="input_dollor"><?php echo CURRENCY;?></span></label>
                                <div class="form-group">
                                    <input type="text" name="mtop_money[]" placeholder="<?php echo lang('money');?>" class="form-control" value="<?php echo (!empty($row->subset)) ? $row->subset : 0;?>" />
                                </div>
                            </div>
                            <div class="col-md-1">
                                <label></label>
                                <div class="form-group equil_div">=</div>
                            </div>
                            <div class="col-md-3">
                                <label></label>
                                <?php 
                                $point = 0;
                                if(isset($row->keyto) && $row->keyto != ''):
                                    if($row->keyto == 'money_to_point'): 
                                        $mpArr = explode('.',$row->value);
                                        $point = $mpArr[0];

                                    endif;
                                endif;
                                ?>
                                <div class="form-group">
                                    <input type="text" name="mtop_point[]" placeholder="<?php echo lang('point');?>" class="form-control" value="<?php echo $point;?>"/>
                                </div>
                                <input type="hidden" name="store_id[]" value="<?php if(isset($row->storeID) && $row->storeID != null && $row->storeID != '' && $row->storeID != 0){ echo $row->storeID;}?>">
                            </div>
                        <?php $i++;endforeach; ?>
                        <?php endif; ?>
                        </div>
                        
                        <div class="col-md-12 hrsepcls"></div>

                        <div class="col-md-12">
                            <div class="col-md-5">
                                <label>Point</label>
                                <?php 
                                $pmpoint = '';
                                if(isset($list->keyto) && $list->keyto != ''):
                                    if($list->keyto == 'point_to_money'): 
                                        $pmArr = explode(':',$list->subset);
                                        $pmpoint = $pmArr[0];
                                    endif;
                                endif;
                                ?>
                                <div class="form-group">
                                    <input type="text" name="ptom_point" placeholder="<?php echo lang('point');?>" class="form-control" value="<?php echo $pmpoint;?>" />
                                </div>
                            </div>
                            <div class="col-md-2">
                            <label></label>
                                <div class="form-group equil_div">=</div>
                            </div>  
                            <div class="col-md-5">
                            <label><span class="input_dollor"><?php echo CURRENCY;?></span> Money</label>    
                                <div class="form-group">    
                                    <input type="text" name="ptom_money" placeholder="<?php echo lang('money');?>" class="form-control" value="<?php echo (!empty($list->value)) ? $list->value : '';?>" />
                                </div>    
                            </div>
                        </div>



                        <div class="col-md-6">
                            <button type="submit" id="submit" class="btn btn-primary">
                                <?php echo lang('submit_btn');?>
                            </button>
                                <button type="button" class="reset btn btn-danger" >
                                    <?php echo lang('reset_btn');?>
                            </button>
                        
                        </div>
                    </form>
                  </div>
                </div>
            </div>
                <div id="form-modal-box"></div>
        </div>
    </div>
</div>
