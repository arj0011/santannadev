<style>
    .modal-footer .btn + .btn {
        margin-bottom: 5px !important;
        margin-left: 5px;
    }
</style>
<div id="paymentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="" enctype="multipart/form-data">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title"><?php echo (isset($title)) ? ucwords($title) : "" ?></h4>
                </div>
                <div class="modal-body">
                    <div class="loaders">
                        <img src="<?php echo base_url() . 'assets/images/Preloader_3.gif'; ?>" class="loaders-img" class="img-responsive">
                    </div>
                    <div class="alert alert-danger" id="error-box" style="display: none"></div>
                    <div class="form-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Total Billing Amount</label>
                                    <div class="col-md-9">
                                        <span class="currencycls"><?php echo CURRENCY ?></span>
                                        <input type="text" class="form-control" placeholder="Total Amount" name="total_billing_amount" value="<?php echo $total_billing_amount; ?>" disabled />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Redeem Point</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="Redeem Point" name="redeem_point" value="<?php echo $redeem_point;?>" disabled />
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Points Worth</label>
                                    <div class="col-md-9">
                                        <span class="currencycls"><?php echo CURRENCY ?></span>
                                        <input type="text" class="form-control" placeholder="Points Worth" name="points_worth" value="<?php echo $points_worth;?>" disabled />
                                    </div>  
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Payment</label>
                                    <div class="col-md-9">
                                        <span class="currencycls"><?php echo CURRENCY ?></span>
                                        <input type="text" class="form-control" placeholder="payment" name="payment" value="<?php echo $actual_payment;?>" disabled />
                                    </div>
                                </div>
                            </div>

                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('close_btn'); ?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>