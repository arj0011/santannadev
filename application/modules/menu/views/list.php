
<div class="row wrapper border-bottom white-bg page-heading custom_pgheading">
    <div class="col-lg-12">
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo site_url('admin/dashboard'); ?>"><?php echo lang('home'); ?></a>
            </li>
            <li>
                <a href="<?php echo site_url('menu/menu_list'); ?>"><?php echo lang('menu'); ?></a>
            </li>
        </ol>
        <ul class="nav navbar-top-links navbar-right custom_message">

            <li class="dropdown" id="notification-list-view">

            </li>
            <li><a style="display:none;" id="exitFullScreen" href="javascript:void(0)" onclick="ExitfullScreen()"><img  width="30" alt="Exit Full Screen" class="" src="<?php echo base_url(); ?>assets/img/exit-full-screen-arrows.png"  title="Exit Full Screen"/></a></li>
            <li><a href="javascript:void(0)" onclick="fullScreen()"><img  width="30" alt="Full Screen" class="" src="<?php echo base_url(); ?>assets/img/switch-to-full-screen-button.png"  title="Full Screen"/></a></li>

        </ul>
    </div>

</div>
<div class="wrapper wrapper-content animated fadeIn">
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <div class="btn-group">
                
                        <a href="javascript:void(0)"  onclick="open_modal_menu('menu','open_model','<?php if(!empty($list)){echo encoding($list[0]->id);} ?>')" class="btn btn-primary">
                            <?php echo lang('add_menu');?>
                        <i class="fa fa-plus"></i>
                        </a>
                       
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
                    <div class="col-lg-12" style="overflow-x: auto">
                    <table class="table table-bordered table-responsive" id="common_datatable">
                        <thead>
                            <tr>
                                <th><?php echo lang('serial_no');?></th>
                                <th><?php echo lang('menu_name');?></th>
                                <th><?php echo lang('price');?></th>
                                <th><?php echo lang('image');?></th>
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
                            <td><?php echo $rowCount; ?></td>            
                            <td><?php echo $rows->menu_name;?></td>
                            <td><?php echo $rows->price?></td>
                            <td><img width="100" src="<?php if(!empty($rows->image)){echo base_Url()?>uploads/menu/<?php echo $rows->image;}else{echo base_url().DEFAULT_NO_IMG_PATH;}?>" /></td>
                            <td class="actions">
                              <a href="javascript:void(0)" class="on-default edit-row" onclick="editFn('menu','menu_edit','<?php echo encoding($rows->id)?>');"><img width="20" src="<?php echo base_url().EDIT_ICON;?>" /></a>
                            <a href="javascript:void(0)" onclick="deleteFn('menus','id','<?php echo encoding($rows->id); ?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url().DELETE_ICON;?>" /></a>

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
