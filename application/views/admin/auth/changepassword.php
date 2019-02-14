<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo lang('profile');?></h2>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin'); ?>"><?php echo lang('home');?></a>
            </li>
            <li class="active">
                <strong><?php echo lang('change_password');?></strong>
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
                </div>
                <div class="ibox-content">
                    <div class="row">
                        <?php $message = $this->session->flashdata('success');
                            if(!empty($message)):?><div class="alert alert-success">
                                <?php echo $message;?></div><?php endif; ?>
                        <?php $flash_error = $this->session->flashdata('error');
                            if(!empty($flash_error)):?><div class="alert alert-danger">
                                <?php echo $flash_error;?></div><?php endif; ?>
                        
                
                        <form method="post" id="password_form" class="profile_form" novalidate="novalidate" action="<?php echo base_url().'admin/change_password'?>">
                            <div class="form-group col-sm-12">
                                <div class="col-sm-3">
                                    <label><?php echo lang('current_password');?></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="password" placeholder="<?php echo lang('current_password');?>" class="form-control" id="old_pswd" name="old" required>
                                    <div class="text-danger"><?php echo form_error('old'); ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-12">
                                <div class="col-sm-3">
                                    <label><?php echo lang('new_password');?></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="password" placeholder="<?php echo lang('new_password');?>" class="form-control" id="new_pswd" name="new" required>
                                    <div class="text-danger"><?php echo form_error('new'); ?></div>
                                </div>
                            </div>

                            <div class="form-group col-sm-12">
                                <div class="col-sm-3">
                                    <label><?php echo lang('confirm_password');?></label>
                                </div>
                                <div class="col-sm-5">
                                    <input type="password" placeholder="<?php echo lang('confirm_password');?>" class="form-control" id="confirm_pswd" name="new_confirm" required>
                                    <div class="text-danger"><?php echo form_error('new_confirm'); ?></div>
                                </div>
                            </div>
                            <div class="form-group col-sm-12 text-center">
                                <button class="btn btn-primary create_btn" ><?php echo lang('update_btn');?></button>
                                <button type="reset" class="btn btn-danger cancel-btn"><?php echo lang('reset_btn');?></button>
                            </div>
                            <form>
                    </div>
                </div>
            </div>
        </div>
    </div>