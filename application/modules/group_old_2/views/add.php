<div class="row wrapper border-bottom white-bg page-heading">
    <div class="col-lg-10">
        <h2><?php echo (isset($headline)) ? ucwords($headline) : ""?></h2>
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

                        <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('group/group_add') ?>" enctype="multipart/form-data">
                
                <div class="loaders">
                        <img src="<?php echo base_url().'assets/images/Preloader_3.gif';?>" class="loaders-img" class="img-responsive">
                    </div>
                    
                   
                          

                              <div class="col-md-12" >
                               <div class="form-group">
                                 <label class="col-md-3 control-label"><?php echo lang('page_id');?></label>
                                    <div class="col-md-9">
                                         <select class="form-control" name="type" id="type">
                                            <option value="">Select Type</option>
                                            <?php $groups = allGroups();foreach($groups as $key=>$val){?>
                                                <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                            <?php }?>
                                        </select>
                                    </div>
                                   
                                </div>
                            </div>
                            
                             <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label"><?php echo lang('user_name');?></label>
                                    <div class="col-md-9">

                                       <select  class="js-example-basic-multiple" multiple=""  id="user_name" name="user_name[]" style="width:100%;">
                                            <?php foreach($users as $user) { ?>
                                                      <option value="<?php echo $user->id; ?>"><?php echo $user->name; ?></option>
                                               <?php } ?>  

                                         </select>
                                    </div>
                                    
                                </div>
                            </div> 

                           

                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('reset_btn');?></button>
                    <button type="submit" id="submit" class="btn btn-primary" ><?php echo lang('submit_btn');?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
</div>
<script type="text/javascript">
$(".js-example-basic-multiple").select2({
  placeholder: 'Select User',
  allowClear: true
  
});
</script>
<!-- <script type="text/javascript">

$(document).ready(function(){
  
$("#user_name").select2({


  dropdownParent: $("#commonModal"),
 //placeholder: 'Select User',
  //allowClear: true,
  minimumInputLength: 2,
                    tags: [],
                    ajax: {
                        url: '<?php echo base_url().'group/user_search'?>',
                        dataType: 'json',
                        type: "POST",
                        quietMillis: 50,
                        data: function (term) {
                            return {
                                search: term
                            };
                        },
                         processResults: function (data) {
                            return {
                                results: $.map(data, function (item) {
                                    return {
                                        text: item.name,
                                        id: item.id
                                    }
                                })
                            };
                        }
                    }
 
})
  
});
</script> -->