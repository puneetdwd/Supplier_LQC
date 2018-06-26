<style>
    .hiddenRow {
        padding:0px !important;
    }
    .hiddenRow .row {
        padding:8px !important;
    }
    .hiddenRow .form-group {
        margin-bottom:0px;
    }
</style>
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    
    <div class="breadcrumbs">
        <h1>
            Sampling Configuration
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Supplier Inspection Items</li>
        </ol>
        
    </div>
    <!-- END PAGE HEADER-->
    
    <div class="row" >
		<div class="col-md-12">
				<div class="portlet light bordered sampling-dashboard-search-portlet">				
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-reorder"></i> Filters
						</div>
					</div>

					<div class="portlet-body form">
						<form role="form" class="validate-form" method="post"  action='<?php echo base_url()."checkpoints/search_checkpoints_by_status" ?>'>
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
								<div class="row">
									<div class="col-md-3">
										<div class="form-group" id="sampling-dashboard-inspection-error">
											<label class="control-label">Select Checkpoint Status:
												
											</label>													
											<select name="checkpoint_status" class="form-control  select2me"
												data-placeholder="Select Checkpoint" data-error-container="#sampling-dashboard-inspection-error" style='width: 20%;'>
													<option <?php if($selected_status == 'Pending') { ?> selected="selected" <?php } ?> value="Pending">Pending</option>
													<option <?php if($selected_status == 'Approved') { ?> selected="selected" <?php } ?> value="Approved">Approved</option>
													<option <?php if($selected_status == 'Declined') { ?> selected="selected" <?php } ?> value="Declined">Declined</option>  
													<option <?php if($selected_status == 'All') { ?> selected="selected" <?php } ?> value="All">All</option>
											</select>
										
									</div>
								</div>
									<div class="col-md-3">
										<div class="form-actions" style='padding-top: 24px !important;border-top: 0px solid #e7ecf1;'>
											<button class="button" type="submit">Submit</button>
										</div>
									</div>
								</div>
							</div>
						</div>                           
                        
                    </form>
					
				</div>
				
		</div>
	</div>
 </div>
        
        <div class="col-md-12" style='margin-top: -250px;'>
            
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Supplier Inspection Items
                    </div>
                    
                    <div class="actions">
                        <!--<a class="button normals btn-circle" href="<?php echo base_url()."sampling/update_inspection_config"; ?>">
                            <i class="fa fa-plus"></i> Add Inspection Configuration
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/sort_inspections"; ?>">
                            <i class="fa fa-plus"></i> Sort Inspections
                        </a>-->
						<?php if(!empty($approval_items)) { ?>
						<a href='<?php echo base_url()."checkpoints/change_checkpoints_status_all/Approved" ?>' class="button" type="submit">Approve All</a>
						<a href='<?php echo base_url()."checkpoints/change_checkpoints_status_all/Declined" ?>' class="button" type="submit">Decline All</a>
			<?php } ?>
                    </div>
                </div>
                <div class="portlet-body form">
                    <!-- BEGIN FORM-->
                    <form class="form-horizontal" role="form">
                        <div class="form-body">
                            <?php if(!empty($approval_items)) { ?>
                                <table class="table table-hover table-light">
                                    <thead>
                                        <tr>
                                            <th>Supplier Name</th>
                                            <th>Product Name</th>
                                            <th>Part Name</th>
                                            <th>Part No.</th>
                                            <th>Insp Type</th>
                                            <th>Insp Item</th>
                                            <th>Insp Specification</th>
                                            <th>Created</th>
                                            <th>Modified</th>
                                            <th>Status</th>
                                            <th class="no_sort" style="width:100px;">Action</th>
                                        </tr>
                                    </thead>
                                
                                    <tbody>
                                        <?php foreach($approval_items as $key => $approval_item) { ?>
                                            <tr>
                                                <td><?php echo $approval_item['supplier_name']; ?></td>
                                                <td><?php echo $approval_item['product_name']; ?></td>
                                                <td><?php echo $approval_item['part_name']; ?></td>
                                                <td><?php echo $approval_item['part_number']; ?></td>
                                                <td><?php echo $approval_item['insp_item']; ?></td>
                                                <td><?php echo $approval_item['insp_item2']; ?></td>
                                                <td><?php echo $approval_item['spec']; ?></td>
                                                <td><?php echo date('jS M, Y', strtotime($approval_item['created'])); ?></td>
                                                <td><?php if(!empty($approval_item['modified']))
															echo date('jS M, Y', strtotime($approval_item['modified']));
														else 
															echo 'Not modified yet';
													?></td>
                                                <td><?php if($approval_item['status'] == NULL){ echo "Pending";}else{ echo $approval_item['status'];} ?></td>
                                                <td nowrap>
                                                    <button type="button" class="btn btn-default btn-xs accordion-toggle" data-toggle="collapse" data-target="#detail-<?php echo $key; ?>">
                                                        <span class="glyphicon glyphicon-eye-open"></span>
                                                    </button>
                                                    <?php if($approval_item['status'] == NULL){ ?>
                                                    <a class="button small gray" 
                                                        href="<?php echo base_url()."checkpoints/checkpoint_status/".$approval_item['id']."/Approved";?>">
                                                        <i class="fa fa-edit"></i> Approve
                                                    </a>
                                                    <a class="btn btn-xs btn-outline sbold red-thunderbird" data-confirm="Are you sure you want to decline this request ?"
                                                        href="<?php echo base_url()."checkpoints/checkpoint_status/".$approval_item['id']."/Declined";?>">
                                                        <i class="fa fa-trash-o"></i> Decline
                                                    </a>
                                                    <?php } ?>
                                                </td>
                                                
                                            </tr>
                                            <tr>
                                                <td colspan="7" class="hiddenRow">
                                                    <div class="accordian-body collapse row" id="detail-<?php echo $key; ?>">
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">LSL:</label>
                                                                <div class="col-md-7">
                                                                    <p class="form-control-static">
                                                                        <?php echo $approval_item['lsl']; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!--/span-->
                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">Target:</label>
                                                                <div class="col-md-7">
                                                                    <p class="form-control-static">
                                                                        <?php echo $approval_item['tgt']; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">USL:</label>
                                                                <div class="col-md-7">
                                                                    <p class="form-control-static">
                                                                        <?php echo $approval_item['usl']; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <div class="col-md-2">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">Unit:</label>
                                                                <div class="col-md-7">
                                                                    <p class="form-control-static">
                                                                        <?php echo $approval_item['unit']; ?>
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
														
														<div class="col-md-4">
                                                            <div class="form-group">
                                                                <label class="control-label col-md-5">Image:</label>
                                                                <div class="col-md-7">
																	<p>
																	<?php if(!empty($approval_item["images"])){ ?>
																		<a target='_blank' href='<?php echo base_url()."assets/inspection_guides/".$approval_item["product_name"].'/'.$approval_item["part_number"].'/'.$approval_item["images"].'.jpg'; ?>' >See Image</a>
																	<?php } else{ ?>
																		<a target='_blank' href='<?php echo base_url()."assets/inspection_guides/default_guide.jpg" ?>' >See Image</a>
																	<?php } ?>
																	</p>
																
																</div>
                                                            </div>
                                                        </div>
														
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                    
                                </table>
                            <?php } else { ?>
                                <p class="text-center">No Inspection Item Found.</p>
                            <?php } ?>
                        </div>
                    </form>
                    <!-- END FORM-->
                </div>
            </div>
            
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>