<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php //echo (isset($phone_number) ? 'Edit': 'Add'); ?> QR Code Print
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."qr_code_generate"; ?>">
                    Manage QR Code Print
                </a>
            </li>
            <li class="active"><?php echo (isset($phone_number) ? 'Edit': 'Add'); ?> QR Code Print</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
        
            <div class="portlet light bordered checkpoint-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> QR Code Print Form
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" class="validate-form" method="post">
                        <div class="form-body">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>

                            <?php if(isset($error)) { ?>
                                <div class="alert alert-danger">
                                    <i class="fa fa-times"></i>
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>

                            <?php if(isset($phone_number['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $phone_number['id']; ?>" />
                            <?php } ?>

                                                       
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="register-inspection-part-error">
                                        <label class="control-label" for="product_id">Part:
                                        <span class="required">*</span></label>
                                        <select name="part_id" class="form-control required select2me"
                                        data-placeholder="Select Part" data-error-container="#register-inspection-part-error"	<?php if($part_id) echo "disabled"; ?>>
                                            <option value=""></option>
                                            <?php foreach($parts as $part) { ?>
                                                <option value="<?php echo $part['id']; ?>"
												<?php if($part_id == $part['id']) echo "selected"; ?>
												>
                                                    <?php echo $part['name'].' ('.$part['part_no'].')'; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                                
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="prod_lot_qty">QR Code Qty.:
                                        <span class="required">*</span></label>
                                        <input type="number" min="1" class="required form-control" name="qr_code_qty" value="<?php if($lot_size){  echo $lot_size; }	?>"		
										<?php if($lot_size){ ?>  max="<?php echo $lot_size;	?>" <?php }	?>	>
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'qr_code_generate'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
<?php if($lot_size){ ?>
	<script>
	 function check_less(lotsize){
		 alert(lotsize);
	 }
	</script>
<?php  }	?>