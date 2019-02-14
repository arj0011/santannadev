<style>
    .modal-footer .btn + .btn {
        margin-bottom: 5px !important;
        margin-left: 5px;
    }
</style>
<div id="commonModalFloorTable" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo lang('close_btn'); ?></span></button>
                <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
            </div>
            <div class="modal-body addlocbodycls">
                <div class="loaders">
                    <img src="<?php echo base_url() . 'assets/images/Preloader_3.gif'; ?>" class="loaders-img" class="img-responsive">
                </div>
                <div class="alert alert-danger" id="error-box" style="display: none"></div>
                <div class="form-body">
                    <div class="row">
                        <div class="col-md-12" id="onTableList">
                            <table class="table table-bordered table-responsive" id="common_datatable_floors">
                                <thead>
                                    <tr>
                                        <th><?php echo lang('serial_no'); ?></th>
                                        <th>Table Name</th>
                                        <th>Seats</th>
                                        <th style="width: 12%"><?php echo lang('action'); ?></th>
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
                                                <td><?php echo $rows->name ?></td>
                                                <td><?php echo $rows->seats ?></td>

                                                <td class="actions">
                                                        <a href="javascript:void(0)" onclick="deleteFnTable('<?php echo $rows->id; ?>','<?php echo $floor_id;?>')" class="on-default edit-row text-danger"><img width="20" src="<?php echo base_url() . DELETE_ICON; ?>" /></a>
                                                </td>
                                            </tr>
                                        <?php endforeach;
                                    endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('close_btn'); ?></button>
            </div>

        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>