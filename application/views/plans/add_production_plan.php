<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($production_plan) ? 'Edit': 'Add'); ?> Production Plan Item
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."sampling/view_production_plan/".$plan_date; ?>">
                    Manage Production Plan
                </a>
            </li>
            <li class="active"><?php echo (isset($production_plan) ? 'Edit': 'Add'); ?> Production Plan Item</li>
        </ol>
        
    </div>

    <div class="row">
        <div class="col-md-12">
        
            <div class="portlet light bordered production_plan-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Production Plan Item Form
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

                            <?php if(isset($production_plan['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $production_plan['id']; ?>" />
                            <?php } ?>
							
							 <div class="row" style="margin-bottom:10px;">
        <div class="col-md-5 col-md-offset-7 text-right">
            <form role="form" class="form-inline" method="post" action="<?php echo base_url().'sampling/view_plan_date'; ?>">
                <div class="form-group">
                    <label class="control-label col-md-6" style="font-size: 15px; margin-top: 6px; text-align: right;">
                        Date <i class="fa fa-arrow-right"></i>
                    </label>
                    <div class="input-group date date-picker col-md-6" data-date-format="yyyy-mm-dd">
                        <input name="view_plan_date" type="text" class="required form-control" readonly
                        value="<?php echo $plan_date; ?>">
                        <span class="input-group-btn">
                            <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                <button class="button" type="submit">Search</button>
                
            </form>
        </div>    
    </div>
    

                            <div class="row">
                             
                                
                               <div class="col-md-6">
                                    <div class="form-group" id="register-inspection-part-error">
                                        <label class="control-label" for="product_id">Part:
                                        <span class="required">*</span></label>
                                        <select name="part_id" class="form-control required select2me"
                                        data-placeholder="Select Part" data-error-container="#register-inspection-part-error">
                                            <option value=""></option>
                                            <?php foreach($parts as $part) { ?>
                                                <option value="<?php echo $part['id']; ?>">
                                                    <?php echo $part['name'].' ('.$part['part_no'].')'; ?>
                                                </option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                
								 
								
                          
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="control-label" for="lot_size">Lot Size:</label>
                                        <input type="text" class=" form-control" name="lot_size" value="">
                                    </div>
                                </div>
                                
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'sampling/view_production_plan/'.$plan_date; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>