<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Retest Part
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Retest Part</li>
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
                                                <label class="control-label">Date Range </label>
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
                                                            <?php echo $part['part_no']; ?>
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
                                <i class="fa fa-reorder"></i>List of Inspections (Total Records - <?php echo $total_records; ?>)
                            </div>
							<?php if(!empty($audits)){ ?>
							<div class="actions">
								<a class="button normals btn-circle" href="<?php echo base_url().'reports/report_download/report_download'; ?>">
									<i class="fa fa-download"></i> Excel Export
								</a>
							</div>
							<?php } ?>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($audits)) { ?>
                                <p class="text-center">No inspection done yet.</p>
                            <?php } else { ?>
                                <!--div class="pagination-sec pull-right"></div>
                                <div style="clear:both;"></div>-->
                                
                                <!--<div class="table-scrollable">-->
                                    <table class="table table-hover " id="make-data-table">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <?php if($this->user_type == 'Admin' || $this->user_type == 'LG Inspector'){ ?>
                                                    <th>Supplier</th>
                                                <?php } ?>
                                                <th>Lot No</th>
                                                <th>Part Name</th>
                                                <th>Part No</th>
                                                <th>Insp Qty.</th>
                                                <th>OK Qty.</th>
                                                <th>NG Qty.</th>
                                                <th>Remark</th>
                                                <th>Inspector Name</th>
                                                
                                                <th class="no_sort">Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
											//print_r($audits);exit;
											foreach($audits as $audit) { ?>
                                                <tr>
                                                    <td nowrap><?php echo date('d M y', strtotime($audit['audit_date'])); ?></td>
                                                    <?php if($this->user_type == 'Admin' || $this->user_type == 'LG Inspector') { ?>
                                                        <td><?php echo $audit['supplier_name']; ?></td>
                                                    <?php } ?>
                                                    
                                                    <td><?php echo $audit['lot_no']; ?></td>
                                                    <td><?php echo $audit['part_name']; ?></td>
                                                    <td><?php echo $audit['part_no']; ?></td>
                                                    <td><?php echo $audit['prod_lot_qty']; ?></td>
                                                    <td>
														<?php //echo $audit['ok_count'];
														ECHO $defect_OK = $this->Audit_model->get_count_audit_lqc_defect_by_result($audit['id'], 'OK');
														?>
													</td>
                                                    <td>
														<?php //echo $audit['ng_count']; 
															ECHO $defect_NG = $this->Audit_model->get_count_audit_lqc_defect_by_result($audit['id'], 'NG');
														?>
													</td>
                                                    <td><?php echo $audit['remark']; ?></td>
                                                    <td><?php echo $audit['inspector_name']; ?></td>
                                                  
                                                    <td nowrap>
                                                       
                                                        <?php if($audit['prod_lot_qty'] > $audit['ok_count']){	?>
															<a class="button small gray" target="_blank" 
																href="<?php echo base_url()."auditer/finish_screen_lqc_retest/".$audit['id'];?>">
																<i class="fa fa-edit"></i> Retest
															</a>
                                                        <?php }	?>
                                                        
                                                    </td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                <!--</div>-->
                                
                                <!--<div class="pagination-sec pull-right"></div>-->
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