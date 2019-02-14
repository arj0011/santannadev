<style>
.danger {
    color:red;
}
</style>
<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($headline)) ? ucwords($headline) : "" ?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard');?>"><?php echo lang('home');?></a>
            </li>
            <li>
                 <a href="<?php echo site_url('group');?>"><?php echo lang('group');?></a>
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

                <div class="row">
                    <div class="col-lg-8">
                        <div class="ibox float-e-margins">
                            
                            <?php $message = $this->session->flashdata('error');
                            if(!empty($message)):?><div class="alert alert-danger">
                                <?php echo $message;?></div><?php endif; ?>

                            <form class="form-horizontal" action="<?php echo site_url('group/group_add');?>" method="post" enctype="multipart/form-data">

                            <div class="form-group">
                                <!-- <label class="col-lg-2 control-label"><?php echo lang('group_name');?></label> -->

                                    <div class="col-lg-10">
                                     <input type="text" class="form-control" name="group_name" id="group_name" placeholder="<?php echo lang('group_name');?>"/>
                                            <span class="help-block m-b-none"><?php echo form_error('group_name'); ?></span>
                                    </div>
                                </div>
                                

                                <div class="form-group">
                                
                                    <div class="col-lg-2">
                                    <div class="checkbox_input">
                                        <input type="checkbox" id="chkAge" />
                                        <label><?php echo lang('age');?></label>
                                    </div>
                                    </div>
                                    <div class="col-lg-8">
                                    <div id="dvAge" style="display: none">
                                    <select class="form-control messages-show" onChange="dropdownTip()"  name="age" id="group_age">
                                       <option value=""><?php echo lang('select_age');?></option>
                                    <?php 
                                    for($i=1; $i<=80; $i++)
                                    {
                                        ?>
                                         <option value="<?php echo $i;?>"><?php echo $i;?></option>
                                         
                                   <?php }
                                    ?> 
                                          
                                    </select> 
                                    <div id="result"></div>
                                    </div>
                                    
                                            <span class="help-block m-b-none"><?php echo form_error('age'); ?></span>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                     <div class="col-lg-2">
                                         <div class="checkbox_input">
                                            <input type="checkbox" id="chkGender" />
                                             <label>
                                           <?php echo lang('user_gender');?></label>
                                       </div>
                                     </div>
                                    <div class="col-lg-8">
                                     <div id="dvGender" class="custom_chk chk_box" style="display:none">
                                        <div class="checkbox">
                                         <input type="radio" name="user_gender" id="gender_both">
                                         <label class="checkbox-inline"> <?php echo lang('both');?></label>
                                        </div>
                                        <div class="checkbox">
                                             <input calss="gender_male" type="radio" name="user_gender" id="user_gender" value="MALE">
                                             <label class="checkbox-inline"><?php echo lang('male');?></label>
                                        </div>
                                            <div class="checkbox">
                                            <input calss="gender_female" type="radio" name="user_gender" id="user_gender" value="FEMALE">
                                            <label class="checkbox-inline"> <?php echo lang('female');?></label>
                                        </div>
                                     </div>
                                     <span class="help-block m-b-none"><?php echo (isset($error)) ? $error : ""; ?></span>
                                     <span class="help-block m-b-none"><?php echo form_error('gender'); ?></span>
                                    </div>
                                </div>
                                

                                 <div class="form-group">
                                <!-- <label class="col-lg-2 control-label"><?php echo lang('user_name');?></label> -->

                                    <div class="col-lg-10">
                                    <select  class="formcontrol"  multiple="ture" id="user_name" name="user_name[]" placeholder="<?php echo lang('select_user');?>" style="width:100%;">
                                    
                                            <!-- <?php 
                                            
                                           foreach($users_data as $user) { ?>
                                                      <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                                               <?php } ?> -->  

                                         </select>
                                            <span class="help-block m-b-none"><?php echo form_error('user_name'); ?></span>
                                    </div>
                                </div>
                                <div class="col-lg-8">

                                 <div class="form-group ">
                                 <div class="checkbox_input">
                                   <input type="checkbox" id="all_user" /> 
                                   <label> <?php echo lang('pick_all_user');?></label>
                                   </div> 
                                </div>
                                </div>





                                <div class="form-group">
                                    <div class="col-lg-10">
                                        <button type="submit" id="submit" class="btn btn-primary" type="submit"><?php echo lang('apply');?></button>
                                    </div>
                                </div>

                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
<script type="text/javascript">
    function dropdownTip(){
        var value = $('#group_age').val(); 
       
        $('#result').html("<div class='danger'>Note: The results are shown upto "+value+" years of age</div>");

    }
</script>
