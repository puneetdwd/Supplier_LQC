<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Part-Foolproof Mappings
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Part-Foolproof Mappings</li>
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
                            </div>
                            <div class="row">
                                <div class="col-md-12">
							<div class="form-group" id="pf-mappings-part-search-error">
								<label class="control-label">Select Foolproof:<span class='required'>*</span></label>
										
								<select name="foolproof" class="required form-control select2me" id="foolproof_selecter" onchange='get_pf()' 
									data-placeholder="Select Foolproof" data-error-container="#pf-mappings-part-search-error">
									<option></option>
									<?php foreach($foolproofs as $foolproof) { ?>
										<option value="<?php echo $foolproof['id']; ?>" <?php if($foolproof['id'] == $this->input->post('foolproof')) { ?> selected="selected" <?php } ?>>
											<?php echo $foolproof['stage'].' - '.$foolproof['major_control_parameters']; ?>
										</option>
									<?php } ?>        
								</select>
							</div>
						</div>
                            </div>-->
                           <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group" id="sp-mappings-part-search-error">
                                        <label class="control-label">Select Part Name:<span class='required'>*</span></label>
                                                
                                        <select name="part_name" class="required form-control select2me" id="part-selector"
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
                                            <?php foreach($parts as $part) { ?>
                                                <option value="<?php echo $part['id']; ?>" <?php if($part['id'] == $this->input->post('part_id')) { ?> selected="selected" <?php } ?>>
                                                    <?php echo $part['code']; ?>
                                                </option>
                                            <?php } ?>        
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                           
                            
                        </div>
                        
                        <div class="form-actions">
                            <button class="button" type="submit">Search</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php 
		$CI = $CI =& get_instance();
		$CI->load->model('foolproof_model');


		if(empty($part_nums)) { 
                $row_count = 0;
            }else{
                $row_count = sizeof($part_nums);
            }
        ?>
        <div class="col-md-9">

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Part-Foolproof Mappings(<?php echo "Total Records - ".$row_count; ?>)
                    </div>
                    <div class="actions" style='width: 40%;'>
                       <div class="col-md-12" >							
							<div class="form-group" id="pf-mappings-part-search-error">
								<label class="control-label">Select Foolproof:<span class='required'>*</span></label>		
								<select name="foolproof" class="required form-control select2me" id="foolproof_selecter" onchange='get_pf()' 
									data-placeholder="Select Foolproof" data-error-container="#pf-mappings-part-search-error">
									<option></option>
									<?php $cnt=1; foreach($foolproofs as $foolproof) { ?>
										<option value="<?php echo $foolproof['id']; ?>" <?php if($foolproof['id'] == $this->input->post('foolproof')) { ?> selected="selected" <?php $cnt++; } ?>>
											<?php echo $foolproof['stage'].' - '.$foolproof['major_control_parameters']; ?>
										</option>
									<?php } ?>        
								</select>
							</div>
						</div>
					</div>
					<input type="hidden" name="fp_id" id='fp_id' />
					<input type="hidden" name="p_id" id='p_id' />
  
                </div>
                <div class="portlet-body">
                    <?php if(empty($part_nums)) { ?>
                        <p class="text-center">No Parts.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id='mapping'>
                            <thead>
                                <tr>
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>
									Mapping(Part-Foolproof)
									</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
								foreach($part_nums as $part_num) { 
									
								?>
                                    <tr>
                                       <td><?php echo $part_num['name']; ?></td>
                                        <td><?php echo $part_num['code']; ?></td>
                                        <td>
										<?php
										//$check_map = $CI->foolproof_model->pf_map_status($part_num['id']);
										
										?>
											<input data-index="<?php echo $part_num['id']; ?>" type="checkbox" name="map_pf_<?php echo $i;	?>" id="map_pf_<?php echo $i;	?>" onClick='map_part_foolproof(<?php echo $part_num['id'].",".$i; ?>);' />
										</td>
                                       
                                    </tr>
                                <?php 
								$i++;
								} ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
