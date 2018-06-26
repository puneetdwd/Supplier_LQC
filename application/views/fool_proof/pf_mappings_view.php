<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            View Part-Foolproofs Mappings
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">View Part-Foolproof Mappings</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <?php if($this->session->flashdata('error')) {?>
            <div class="alert alert-danger">
               <i class="fa fa-times"></i>
               <?php echo $this->session->flashdata('error');?>
            </div>
        <?php } else if($this->session->flashdata('success')) { ?>
            <div class="alert alert-success">
                <i class="fa fa-check"></i>
               <?php echo $this->session->flashdata('success');?>
            </div>
        <?php } ?>
    
        <div class="col-md-3">
            <div class="portlet light bordered" id="ptc-mapping-portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-search"></i>Search
                    </div>
                </div>
                <div class="portlet-body form">
                    <form role="form" class="validate-form" method="post">
                        <div class="form-body" style="padding:0px;">
                            <div class="alert alert-danger display-hide">
                                <button class="close" data-close="alert"></button>
                                You have some form errors. Please check below.
                            </div>
                        
                        
                            <?php if(isset($error)) { ?>
                                <div class="alert alert-danger">
                                    <?php echo $error; ?>
                                </div>
                            <?php } ?>
                            
                            <!--<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="sp-mappings-product-search-error">
                                        <label class="control-label">Select Product:</label>
                                                
                                        <select name="product_id" class="form-control select2me" id="product-part-selector-new"
                                            data-placeholder="Select Product" data-error-container="#sp-mappings-product-search-error">
                                            <option></option>
                                            <?php foreach($products as $product) { ?>
                                                <option value="<?php echo $product['id']; ?>" <?php if($product['id'] == $this->input->post('product_id')) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $product['name']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>-->
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="sp-mappings-part-search-error">
                                        <label class="control-label">Select Part Name:</label>
                                                
                                        <select name="part_name" class="form-control select2me" id="part-selector"
                                            data-placeholder="Select Part Name" data-error-container="#sp-mappings-part-search-error">
                                            <option></option>
                                            <?php foreach($parts as $part) { ?>
                                                <option value="<?php echo $part['name']; ?>" <?php if($part['name'] == $this->input->post('part_name')) { ?> selected="selected" <?php } ?>>
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
                                                
                                        <select name="part_id" class="form-control select2me" id="part-number-selector"
                                            data-placeholder="Select Part Number" data-error-container="#sp-mappings-part-search-error">
                                            <option></option>
                                            <?php foreach($part_nums as $part_num) { ?>
                                                <option value="<?php echo $part_num['id']; ?>" <?php if($part_num['id'] == $this->input->post('part_id')) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $part_num['code']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="sp-mappings-chamber-search-error">
                                        <label class="control-label">Select Foolproof:</label>
                                                
                                        <select name="foolproofs" class="form-control select2me"
                                            data-placeholder="Select Foolproof" >
                                            <option></option>
                                            <?php foreach($foolproofs as $foolproof) { ?>
                                                <option value="<?php echo $foolproof['id']; ?>" <?php if($foolproof['id'] == $this->input->post('foolproofs')) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $foolproof['stage'].' - '.$foolproof['major_control_parameters']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>
							<?php if($this->user_type == 'Admin' || $this->user_type == 'LG Inspector'){ ?>
                                    
							<div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="sp-mappings-chamber-search-error">
                                        <label class="control-label">Select Supplier:</label>
                                                
                                        <select name="supplier_id" class="form-control select2me"
                                                data-placeholder="Select Supplier" data-error-container="#report-sel-supplier-error">
											<option value=""></option>
											
											<?php $sel_supplier = $this->input->post('supplier_id'); ?>
											<?php foreach($suppliers as $supplier) { ?>
												<option value="<?php echo $supplier['id']; ?>" 
												<?php if($sel_supplier == $supplier['id']) { ?> selected="selected" <?php } ?>>
													<?php echo $supplier['supplier_no'].' - '.$supplier['name']; ?>
												</option>
											<?php } ?>
											
										</select>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        
                        <div class="form-actions">
                            <button class="button" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php if(empty($sp_mappings)) { 
                $row_count = 0;
            }else{
                $row_count = sizeof($sp_mappings);
            }
        ?>
        <div class="col-md-9">

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Part-Foolproofs Mappings(<?php echo "Total Records - ".$row_count; ?>)
                    </div>
					<?php if($this->user_type == 'Supplier') { ?>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."fool_proof/pf_mappings"; ?>">
                            <i class="fa fa-plus"></i> Add New Part-Foolproof Mapping
                        </a>
                    </div>
					<?php } ?>
                </div>
                <div class="portlet-body">
                    <?php if(empty($sp_mappings)) { ?>
                        <p class="text-center">No Part-Foolproof Mapping.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>Foolproofs</th>
                                    <!--th class="no_sort" style="width:100px;"></th-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($sp_mappings as $sp_mapping) { ?>
                                    <tr>
                                        <td><?php echo $sp_mapping['product_name']; ?></td>
                                        <td><?php echo $sp_mapping['part_name']; ?></td>
                                        <td><?php echo $sp_mapping['part_num']; ?></td>
                                        <td><?php echo $sp_mapping['fc_stage']." - ".$sp_mapping['major_control_parameters']; ?></td>
                                        <!--td nowrap>
                                            <a class="button small gray" 
                                                href="<?php echo base_url()."suppliers/add_sp_mapping/".$sp_mapping['id'];?>">
                                                <i class="fa fa-edit"></i> Edit
                                            </a>
                                        </td-->
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>