<style>
    .modal-footer .btn + .btn {
        margin-bottom: 5px !important;
        margin-left: 5px;
    }
</style>
<div id="paymentModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="form-horizontal" role="form" id="addFormAjax" method="post" action="<?php echo base_url('tablebooking/payment') ?>" enctype="multipart/form-data">
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
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Total Billing Amount</label>
                                    <div class="col-md-9">
                                        <span class="currencycls"><?php echo CURRENCY ?></span>
                                        <input type="text" class="form-control" placeholder="Total Amount" name="total_billing_amount" id="total_billing_amount" onkeyup="getPay(this.value)" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                <h4>Available Wallet Point = <span id="total_user_point"><?php echo $point;?></span></h4>
                                <span class="text-danger">You have worth amount = </span>
                                <span class="text-danger">
                                    <?php echo CURRENCY ?> <?php echo $money;?>
                                </span>
                                <input type="hidden" id="user_point" name="user_point" value="<?php echo $point;?>">
                                </div>
                            </div>
                            
                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">Redeem Point</label>
                                    <div class="col-md-6">
                                        <input type="text" class="form-control" placeholder="Redeem Point" name="redeem_point" id="redeem_point" value="" <?php if(!isset($point) || $point == 0){ echo 'disabled';}?> />
                                    </div>    
                                    <div class="col-md-3">    
                                        <button type="button" class="btn btn-primary" id="redeembtn" <?php echo ($point == 0) ? 'disabled' : '';?>>Redeem</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">OTP</label>
                                    <div class="col-md-9">
                                        <input type="text" class="form-control" placeholder="OTP" name="otp" value="">
                                    </div>
                                </div>
                            </div>

                             <div class="col-md-12" >
                                <div class="form-group">
                                    
                                    <div class="col-md-9">
                                        <button type="button" class="btn btn-primary">Scan QR Code</button>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12" >
                                <div class="form-group">
                                    <label class="col-md-3 control-label">New Payble amount</label>
                                    <div class="col-md-9">
                                        <span class="currencycls"><?php echo CURRENCY ?></span>
                                        <input type="text" class="form-control" placeholder="payment" name="payment" id="payment" value="" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="space-22"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="hidden" name="floor_id" value="<?php echo $floor_id;?>" />
                    <input type="hidden" name="user_id" value="<?php echo $user_id;?>" />
                    <input type="hidden" name="booking_id" value="<?php echo $id;?>" />
                    <button type="submit" id="paymentsubmit" disabled="" class="btn btn-primary"><?php echo lang('submit_btn'); ?></button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo lang('close_btn'); ?></button>
                </div>
            </form>
        </div> <!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<script type="text/javascript">
    function getPay(p){
        $("#payment").attr('value',p);
    }
</script>