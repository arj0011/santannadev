<style>
    #sp-board::after {
    background: rgba(0, 0, 0, 0.5) none repeat scroll 0 0;
    content: "";
    height: 100%;
    left: 0;
    position: absolute;
    top: 0;
    width: 100%;
}
#sp-board .sp-table,
.tableplan .sp-table {
    display: block;
    position: absolute;
    z-index: 999;
}
</style>
<div class="">
    <div class="row">
        <div class="col-md-12">
            <h2><?php echo lang('table_plan');?></h2>
        </div>
        <div class="col-sm-3">
            <div class="panel panel-primary">
                <div class="panel-heading"><?php echo lang('locations');?></div>
                <div class="panel-body">
                    <?php
                    $options = array();

                    foreach ($rooms as $room) {
                        $options[$room->id] = $room->name;
                    }
                    echo form_dropdown('room', $options, set_value('room', $roomId)
                            , 'class="form-control" id="room"');
                    ?>

                </div>
                <div class="panel-footer">
                    <!--<p><a href="#" class="btn btn-primary btn-block">Manage Floors</a></p>-->
                </div>
            </div>
        <?php if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'):?>
            <div id="sp-tables-add" class="panel panel-primary">
                <div class="panel-heading"><strong><?php echo lang('add_table');?></strong><?php echo lang('drag');?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="optionbox">
                            <?php foreach ($shapes as $shape): ?>
                                <div class="col-md-3">
                                    <div class="sp-table new" type="<?php echo $shape->id ?>" canrotate="<?php echo $shape->canrotate ?>" seats="<?php echo $shape->seats ?>">
                                        <img src="<?php echo base_url() . 'uploads/tables/' . $shape->image ?>" class="svg" style="width: 40.91px; height: 52.73px;" />
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endif;?>
                   <?php if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'):?>
            <div class="panel panel-primary" id="properties" >
                <div class="panel-heading"><strong><?php echo lang('table_setting');?></strong></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-12">

                            <div class="content">
                                <div class="option form-group">
                                    <label><?php echo lang('name');?>:</label>
                                    <input type="text" id="tablename" maxlength="50" class="form-control" />
                                </div>
                                <div class="option form-group">
                                    <label><?php echo lang('seats');?>:</label>
                                    <?php
                                    for ($i = 1; $i < 21; $i++) {
                                        $options[$i] = $i . ' Pers';
                                    }
                                    echo form_dropdown('room', $options, set_value('room', $roomId)
                                            , 'size="1" class="form-control" id="seats"');
                                    ?>

                                </div>

                                <div class="option misc form-group">
                                    <label><?php echo lang('shapes');?>:</label>
                                    <div class="row" id="shapes">
                                        <?php foreach ($shapes as $shape): ?>
                                            <div class="col-md-3">
                                                <div class="shape type<?php echo $shape->id ?>" type="<?php echo $shape->id ?>" svgwidth="40.91" svgheight="52.73" canrotate="<?php echo $shape->canrotate ?>">
                                                    <img src="<?php echo base_url() . 'uploads/tables/' . $shape->image ?>" class="svg" style="width: 40.91px; height: 52.73px;" />
                                                </div>
                                            </div>
                                        <?php endforeach; ?>

                                    </div>
                                </div>
                                <div class="option misc">
                                    <label></label>
                                    <div class="row" >
                                        <div class="col-md-12" >
                                            <input type="button" id="done-button" class="btn btn-primary btn-block" value="<?php echo lang('done');?>" /><br />
                                            <span id="delete-table" class="link btn-link pull-right"><?php echo lang('delete_tables');?></span>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      
  <?php endif;?>
        </div>
     
        <!-- <div class="col-sm-3"> -->
            
        <!-- </div> -->




        <div class="col-sm-9" style="">
        <div class="bordercls"> 
            <div id="sp-board" style="width: <?php echo ($roomDetail['roomWidth'] != '')? $roomDetail['roomWidth'].'px':'100%' ?>; height: <?php echo ($roomDetail['roomHeight'] != '')? $roomDetail['roomHeight'].'px':'400px' ?>;" room="<?php echo $roomId ?>" pers="pers." delete-warning="Are you sure you want to delete this table? The table will be removed from all associated bookings." saving="Saving..." name-missing="You must enter a name!" changes-saved="Changes saved..." changes-not-saved="The changes were not saved! Please try again.">
            <img src="<?php echo base_url();?>uploads/floors/<?php echo $roomDetail['image'];?>" class="floorimagecls" style="width: <?php echo ($roomDetail['roomWidth'] != '')? $roomDetail['roomWidth'].'px':'100%' ?>; height: <?php echo ($roomDetail['roomHeight'] != '')? $roomDetail['roomHeight'].'px':'400px' ?>;" />
                <?php foreach($tables as $table): ?>
                <div class="sp-table <?php echo ($table->canrotate) ? 'canrotate' : '' ?> <?php echo ($table->rotation != '')?'rotate'.$table->rotation:'' ?>" style="left: <?php echo $table->left ?>; top: <?php echo $table->top ?>; visibility: hidden;" type="<?php echo $table->type ?>" tableid="<?php echo $table->id ?>" priority="<?php echo $table->priority ?>" rotation="<?php echo $table->rotation ?>" seats="<?php echo $table->seats ?>">
                    <div class="rel">
                        <div class="display">
                            <div class="name"><?php echo $table->name ?></div>
                            <div class="seats"><?php echo $table->seats ?> pers.</div>
                                
                        </div>
                        <img src="<?php echo base_url() . 'uploads/tables/' . $table->image ?>" class="svg" style="width: 43.64px; height: 43.64px;" />
                    </div>
                </div>
                <?php endforeach ?>
            </div>
            <?php if($this->session->userdata('id') != '' && $this->session->userdata('role') == 'admin'):?>
            <div>
                <input class="btn btn-block btn-primary" type="button" id="save-button" value="<?php echo lang('save_table_plan');?>" />
            </div>
            <?php endif;?>
        </div>
        </div>


    </div>
</div>

<script>
window.onbeforeunload = function (e) {
    if (changed) {
        return 'You have unsaved Changes.';
    }
};

$(document).ready(function(){
    //var widthPercent = ($('#sp-board').width() / $(window).width())*100;  
    var widthPercent = $('#sp-board').width();  
    $('#save-button').css({'width': widthPercent + 'px'});
});
</script>

