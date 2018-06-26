<style>
    .form-inline .select2-container--bootstrap{
        width: 300px !important;
    }
    
</style>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Checkpoints
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Checkpoints</li>
        </ol>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-6 col-md-offset-6">
            <form role="form" class="validate-form form-inline" method="get">

                <?php if(isset($error)) { ?>
                    <div class="alert alert-danger">
                        <?php echo $error; ?>
                    </div>
                <?php } ?>

                <div class="form-group" id="report-sel-part-error">
                    <label class="control-label">Select Part:&nbsp;&nbsp;</label>
                            
                    <select name="part_no" class="form-control select2me" data-placeholder="Select Part" data-error-container="#report-sel-part-error" style="width:300px !important;">
                        <option></option>
                        <?php foreach($parts as $part) { ?>
                            <option value="<?php echo $part['part_no']; ?>" <?php if($part['part_no'] == $this->input->get('part_no')) { ?> selected="selected" <?php } ?>>
                                <?php echo $part['name'].' ('.$part['part_no'].')'; ?>
                            </option>
                        <?php } ?>        
                    </select>
                </div>
                &nbsp;&nbsp;
                <button class="button" type="submit">Search</button>
            </form>
        </div>
    </div>
    
    <div class="row" style="margin-top:15px;">
        
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

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Checkpoints - <?php echo $product['name'];?>
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."checkpoints/add_checkpoint"; ?>">
                            <i class="fa fa-plus"></i> Add New Checkpoint
                        </a>
                        <?php if($this->user_type == 'Admin') { ?>
                        <a class="button normals btn-circle" href="<?php echo base_url()."checkpoints/upload_checkpoints"; ?>">
                            <i class="fa fa-plus"></i> Upload Checkpoints
                        </a>
							<?php //if(!empty($checkpoints)) { ?>
							<a class="button normals btn-circle" href="<?php echo base_url()."checkpoints/checkpoint_export/".$this->input->get('part_no'); ?>">
								<i class="fa fa-download"></i> Download Checkpoints
							</a>
							<?php //} ?>
                        <?php } ?>
                        <?php if($this->user_type !== 'Supplier' && false) { ?>
                            <a class="button normals btn-circle" href="<?php echo base_url()."checkpoints/view_revision_history/"; ?>">
                                <i class="fa fa-eye"></i> View Revisions
                            </a>
                        <?php } ?>
                    </div>
                </div>
                <div class="portlet-body">
                    
                    <?php if(empty($checkpoints)) { ?>
                        <p class="text-center">No Checkpoints.</p>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-light">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-center">Product</th>
                                        <th class="text-center">Part</th>
                                        <th>Supplier</th>
                                        <th>Insp. Type</th>
                                        <th>Insp. Item</th>
                                        <th>Spec.</th>
                                        <th>LSL</th>
                                        <th>USL</th>
                                        <th>TGT</th>
                                        <th>Images</th>
										<th>Measuring Equipment</th>                                       
                                        <th>Approved By</th>
                                        <th>Created Date</th>
                                        <th>Status</th>
                                        <th>Is Active</th>
                                        <th class="no_sort" style="width:150px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if($this->user_type !== 'Supplier') { ?>
                                        <tr class="warning">
                                            <td colspan="17">LG Checkpoints</td>
                                        </tr>
                                    <?php } ?>
                                    <?php $first = true; ?>
                                    <?php $i=1; foreach($checkpoints as $checkpoint) { ?>
                                    
                                        <?php if($this->user_type !== 'Supplier' && $checkpoint['checkpoint_type'] == 'Supplier' && $first) { ?>
                                            <tr class="warning">
                                                <td colspan="17">Suppliers Checkpoints</td>
                                            </tr>
                                            <?php $first = false; ?>
                                        <?php } ?>
                                    
                                        <tr class="checkpoint-<?php echo $checkpoint['id']; ?>">
                                            <td><?php echo $i; ?></td>
                                            <td><?php echo $product['name']; ?></td>
                                            <td><?php echo $checkpoint['part_no']; ?></td>
                                            <td><?php echo $checkpoint['supplier_name'] ? $checkpoint['supplier_name'] : '--'; ?></td>
                                            <td><?php echo $checkpoint['insp_item']; ?></td>
                                            <td><?php echo $checkpoint['insp_item2']; ?></td>

                                            <td><?php echo $checkpoint['spec']; ?></td>
                                            <?php if($checkpoint['has_multiple_specs']) { ?>
                                                <td colspan="3" class="text-center">Model wise Specs</td>
                                            <?php } else { ?>
                                                <td nowrap>
                                                    <?php echo ($checkpoint['lsl']) ? $checkpoint['lsl'].' '.$checkpoint['unit'] : ''; ?>
                                                </td>
                                                <td nowrap>
                                                    <?php echo ($checkpoint['usl']) ? $checkpoint['usl'].' '.$checkpoint['unit'] : ''; ?>
                                                </td>
                                                <td nowrap>
                                                    <?php echo ($checkpoint['tgt']) ? $checkpoint['tgt'].' '.$checkpoint['unit'] : ''; ?>
                                                </td>
                                            <?php } ?>

											<td nowrap class="text-center">
                                                <?php if(($checkpoint['checkpoint_type'] == 'LG' || $this->user_type === 'Supplier') && empty($checkpoint['images'])) { ?>
                                                    <a target="_blank" href=<?php echo base_url()."assets/inspection_guides/default_guide.jpg"; ?>>
														See Image
													</a>
												<?php }else{ ?>
													<a target="_blank" href="<?php echo base_url()."assets/inspection_guides/".$product['name'].'/'.$checkpoint['part_no'].'/'.$checkpoint['images'].'.jpg'; ?>">
														See Image
													</a>
                                                    
                                                <?php } ?>
                                            </td>
											<td><?php echo $checkpoint['measure_equipment']; ?></td>
											
											<td nowrap class="text-center">
                                                <?php if(($checkpoint['checkpoint_type'] == 'LG' || $this->user_type === 'Supplier') && !empty($checkpoint['approved_by'])) { 
                                                    echo $checkpoint['approved_by'];
													
												 } ?>
                                            </td>
											
											<td nowrap class="text-center">
                                                <?php if(($checkpoint['checkpoint_type'] == 'LG' || $this->user_type === 'Supplier') && !empty($checkpoint['created'])) { 
                                                    $created_date = explode(" ",$checkpoint['created']);
													echo $created_date[0];
												 } ?>
                                            </td>

                                            <td nowrap>
                                                <?php if($checkpoint['status'] == NULL && $this->user_type === 'Supplier'){ echo "Pending";}else{ echo $checkpoint['status'];} ?>
                                            </td>
											
											<td>
												<?php 
													if($checkpoint['is_deleted'] == 0)
														echo '<i class="fa fa-check"></i>';
													else
														echo '<i class="fa fa-times"></i>'; 
												?>
											</td>
											 
                                            <td nowrap class="text-center">
                                                <?php if(($checkpoint['checkpoint_type'] == 'LG' && $this->user_type === 'Admin') || ($this->user_type === 'Supplier' && $checkpoint['supplier_id'] == $this->session->userdata('id'))) { ?>
                                                    <a class="button small gray" href="<?php echo base_url()."checkpoints/add_checkpoint/".$checkpoint['id']; ?>">
                                                        <i class="fa fa-edit"></i> Edit
                                                    </a>

                                                    <a class="btn btn-outline btn-xs sbold red-thunderbird" href="<?php echo base_url()."checkpoints/delete_checkpoint/".$checkpoint['id']; ?>" data-confirm="Are you sure you want to delete this checkpoint?">
                                                        <i class="fa fa-trash-o"></i> Delete
                                                    </a>
                                                <?php } ?>
                                            </td>
                                        </tr>
                                    <?php $i++; } ?>
                                </tbody>
                            </table>
                        </div>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
