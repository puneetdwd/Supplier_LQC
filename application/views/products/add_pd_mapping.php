<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            <?php echo (isset($part) ? 'Edit': 'Add'); ?> Part Defect Code
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url()."products/pd_master"; ?>">
                    Manage Defect Code 
                </a>
            </li>
           
            <li class="active"><?php echo (isset($defect_code) ? 'Edit': 'Add'); ?> Defect Code</li>
        </ol>
        
    </div>
	<?php 
	
	 
	/*	print_r($defect_code);	
			echo "<pre>";	print_r($parts[0]);
			//print_r($part_nums[0]); */
	?>
    <div class="row">
        <div class="col-md-6 col-md-offset-3">
        
            <div class="portlet light bordered checkpoint-add-form-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Part Defect Code Form - <?php echo $product['name']; ?>
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."products/upload_pd_mappings"; ?>">
                            <i class="fa fa-plus"></i> Upload Defect Code
                        </a>
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

                            <?php if(isset($defect_code['id'])) { ?>
                                <input type="hidden" name="id" value="<?php echo $defect_code['id']; ?>" />
                            <?php } ?>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="sp-mappings-part-search-error">
                                        <label class="control-label">Select Part Name:</label>
                                        <span class="required">*</span></label>
                                        <select name="part_name" class="form-control select2me" id="part-selector"
                                            data-placeholder="Select Part Name" data-error-container="#sp-mappings-part-search-error">
                                            <option></option>
                                            <?php foreach($parts as $part) { ?>
                                                <option value="<?php echo $part['name']; ?>" 
												<?php 
												if(isset($defect_code['id'])) { 
											if($part['name'] == $defect_code['part_name']) { ?> selected="selected" <?php } }  ?>>
                                                    <?php echo $part['name']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="sp-mappings-part-search-error">
                                        <label class="control-label">Select Part Number:</label>
                                         <span class="required">*</span></label>
                                               
                                        <select name="part_id" class="form-control select2me" id="part-number-selector"
                                            data-placeholder="Select Part Number" data-error-container="#sp-mappings-part-search-error">
                                            <option></option>
                                            <?php foreach($part_nums as $part_num) { ?>
                                                <option value="<?php echo $part_num['id']; ?>" 
												<?php 
													if(isset($defect_code['id'])) { 
														if($part_num['id'] == $defect_code['part_id']) { 
													?>

														selected="selected" <?php } } ?>
													
													>
                                                    <?php echo $part_num['code']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!--div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="sp-mappings-chamber-search-error">
                                        <label class="control-label">Select Supplier:</label>
                                         <span class="required">*</span></label>
                                               
                                        <select name="supplier_id" class="form-control select2me"
                                            data-placeholder="Select Supplier" data-error-container="#sp-mappings-chamber-search-error">
                                            <option></option>
                                            <?php foreach($suppliers as $supplier) { ?>
                                                <option value="<?php echo $supplier['id']; ?>" 
												
												<?php 
												if(isset($defect_code['id'])) { 
												if($supplier['id'] == $defect_code['supplier_id']) { ?> selected="selected" <?php } } ?>>
                                                    <?php echo strtoupper($supplier['name']); ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div-->
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="name">Defect Description:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="defect_description"
                                        value="<?php echo isset($defect_code['defect_description']) ? $defect_code['defect_description'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
							
							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label" for="code">Defect Description Detail:
                                        <span class="required">*</span></label>
                                        <input type="text" class="required form-control" name="defect_description_detail"
                                        value="<?php echo isset($defect_code['defect_description_detail']) ? $defect_code['defect_description_detail'] : ''; ?>">
                                        <span class="help-block">
                                        </span>
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                            
                            
                        <div class="form-actions">
                            <button class="button" type="submit">Submit</button>
                            <a href="<?php echo base_url().'products/pd_master'; ?>" class="button white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>