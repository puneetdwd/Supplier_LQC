<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Edit Monthly Production Plan Item
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."sampling/production_plan_monthly/"; ?>">
                    Manage Production Plan Monthly
                </a>
            </li>
            <li class="active">Edit Monthly Production Plan Item</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered production_plan-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Edit Monthly Production Plan Item Form
                    </div>
                </div>

                <div class="portlet-body form">
                    <form role="form" class="validate-form" method="post" enctype="multipart/form-data">
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

                            <?php if(isset($plan['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $plan['id']; ?>" />
								<input type="hidden" class=" form-control" name="original_lot_size" value="<?php echo $plan['lot_size'];	?>">
                            <?php } ?>

                            <div class="row">
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label">
                                            Select Month
                                        </label>
										<input type="text" id="plan_month" readonly class="required form-control" name="plan_month" value="<?php echo $plan['plan_month']; ?>">
									</div>
                                </div>
                                
                            </div>
                            
                            <div class="row">
                               <div class="col-md-6">
                                    <div class="form-group" id="sampling-dashboard-model-error">
                                        <label class="control-label">Select Model.Suffix:
                                        <span class="required"> * </span></label>
                                        	<input type="text" id="model_suffix"  readonly class="required form-control" name="model_suffix" value="<?php echo $plan['model_suffix'];	?>">
                                       </div>
                                </div>
								
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="lot_size">Lot Size:</label>
                                        <input type="text" class=" form-control" name="lot_size" value="<?php echo $plan['lot_size'];	?>">
                                    </div>
                                </div>
                                
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'sampling/edit_production_plan_monthly/'.$plan['id']; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>