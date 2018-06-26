<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            View Timecheck Report
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">View Timecheck Report</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

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
            
            <div class="row">
                
                <div class="col-md-12">
                    <div class="portlet light bordered">

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
                                    
                                    <input type="hidden" id="page-no" name="page_no" value="1"/>
                                    
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="control-label">Date Range</label>
                                                <div class="input-group date-picker input-daterange" data-date-format="yyyy-mm-dd">
                                                    <input type="text" class="form-control required" name="start_range" 
                                                    value="<?php echo $this->input->post('start_range'); ?>">
                                                    <span class="input-group-addon">
                                                    to </span>
                                                    <input type="text" class="form-control required" name="end_range"
                                                    value="<?php echo $this->input->post('end_range'); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    
                                        <div class="col-md-4">
                                            <div class="form-group" id="report-sel-part-error">
                                                <label class="control-label">Select Part:</label>
                                                        
                                                <select name="part_no" class="form-control select2me"
                                                    data-placeholder="Select Part" data-error-container="#report-sel-part-error">
                                                    <option></option>
                                                    <?php foreach($parts as $part) { ?>
                                                        <option value="<?php echo $part['part_no']; ?>" <?php if($part['part_no'] == $this->input->post('part_no')) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $part['part_name'].' ('.$part['part_no'].')'; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>
										
										<div class="col-md-4">
                                            <div class="form-group" id="report-sel-part-error">
                                                <label class="control-label">Select Part:</label>
                                                        
                                                <select name="chld_part_no" class="form-control select2me"
                                                    data-placeholder="Select Part" data-error-container="#report-sel-part-error">
                                                    <option></option>
                                                    <?php foreach($child_parts as $cpart) { ?>
                                                        <option value="<?php echo $cpart['part_no']; ?>" <?php if($cpart['part_no'] == $this->input->post('part_no')) { ?> selected="selected" <?php } ?>>
                                                            <?php echo $part['part_name'].' ('.$part['part_no'].')'; ?>
                                                        </option>
                                                    <?php } ?>        
                                                </select>
                                            </div>
                                        </div>

                                    <?php if($this->user_type == 'Admin' || $this->user_type == 'LG Inspector'){ ?>
                                        <div class="col-md-4">
                                            <div class="form-group" id="report-sel-supplier-error">
                                                <label class="control-label" for="supplier_id">Supplier:</label>
                                                
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
                                    <?php } ?>
                                    
                                </div>
                                
                                <div class="form-actions">
                                    <?php if($this->session->userdata('is_super_admin')){ ?>
                                        <label class="control-label" for="supplier_id">All Product:</label>
                                        <input type="checkbox" name="product_all" class="form-control" value="all" 
                                        <?php if($this->input->post('product_all') == 'all'){echo "checked";} ?> />
                                    <?php } ?>
                                    <button class="button" type="submit">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-reorder"></i>List of Timechecks (Total Records - <?php echo $total_records; ?>)
                            </div>
							<?php if(!empty($plans)){ ?>
							<div class="actions">
								<a class="button normals btn-circle" href="<?php echo base_url().'reports/timecheck_download/timecheck_download'; ?>">
									<i class="fa fa-download"></i> Excel Export
								</a>
							</div>
							<?php } ?>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($plans)) { ?>
                                <p class="text-center">No plans done yet.</p>
                            <?php } else { ?>
                                <div class="pagination-sec pull-right"></div>
                                <div style="clear:both;"></div>
                                
                                <!--<div class="table-scrollable">-->
                                    <table class="table table-hover table-light" id="make-data-table">
                                        <thead>
                                            <tr>
                                                <th>Product</th>
                                                <th>Part</th>
                                                <th>Date</th>
                                                <th>From Time</th>
                                                <th>To Time</th>
                                                <th>Result</th>
                                                <th>Progress</th>
                                                <th class="no_sort">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($plans as $plan) {
											/* if($plan['ng_lots'] > 0)
												$bg = 'background-color:red;'; */
											?>
                                                <tr>
                                                    <td><?php echo $plan['product_name']; ?></td>
                                                    <td><?php echo $plan['part_name'].' ('.$plan['part_no'].')'; ?></td>
                                                    <td><?php echo date('d M Y', strtotime($plan['plan_date'])); ?></td>
                                                    <td><?php echo $plan['from_time']; ?></td>
                                                    <td><?php echo $plan['to_time']; ?></td>
                                                    <td><?php echo $plan['ng_count'] > 0 ? 'NG' : 'OK'; ?></td>
                                                    <td>
                                                        <?php
                                                            $freq_results = explode(',', $plan['freq_results']);
                                                            $freq_indexs = explode(',', $plan['freq_indexs']);
                                                            
                                                            $fresults = array();
                                                            foreach($freq_indexs as $k => $idx) {
                                                                $fresults[$idx] = $freq_results[$k];
                                                            }
                                                        ?>

                                                        <div class="color-progress-box">
                                                            <?php for($i = 1;$i <= $plan['total_frequencies']; $i++) { ?>
                                                                <?php 
                                                                    $cls = '';
                                                                    if(!isset($fresults[$i])) {
                                                                        $cls = 'empty-item';
                                                                    } else if($fresults[$i] == 'NG') {
                                                                        $cls = 'ng-item';
                                                                    } else {
                                                                        $cls = 'ok-item';
                                                                    }
                                                                ?>
                                                                <span class="progress-item <?php echo $cls; ?>"></span>
                                                            <?php } ?>
                                                            
                                                        </div>
                                                    </td>
                                                    <td nowrap>
                                                        
                                                        
                                                        <a class="button small gray" target="_blank" 
                                                            href="<?php echo base_url()."timecheck/view/".$plan['id'];?>">
                                                            <i class="fa fa-edit"></i> View
                                                        </a>
                                                        <a class="button small gray" target="_blank" 
                                                            href="<?php echo base_url()."timecheck/view/".$plan['id']."?download=true";?>">
                                                            <i class="fa fa-edit"></i> Download
                                                        </a>
                                                        
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                <!--</div>-->
                                
                                <div class="pagination-sec pull-right"></div>
                                <div style="clear:both;"></div>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
