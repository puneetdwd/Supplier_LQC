
<div class="page-content">

    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            LQC Part Inspection | Review Screen
        </h1>
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
    </div>
        
    <div class="row">    
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="">
                                <label class="control-label"><b>Supplier:</b></label><br />
                                <p class="form-control-static">
                                    <?php echo $audit['supplier_no'].' - '.$audit['supplier_name']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="">
                                <label class="control-label"><b>Product:</b></label><br />
                                <p class="form-control-static">
                                    <?php echo $audit['product_name']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="">
                                <label class="control-label"><b>Part:</b></label><br />
                                <p class="form-control-static">
                                    <?php echo $audit['part_no'].' - '.$audit['part_name']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="">
                                <label class="control-label"><b>Inspection Lot Qty.:</b></label><br />
                                <p class="form-control-static">
                                    <?php echo $audit['prod_lot_qty']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="form-group">
                                <label class="control-label"><b>Lot No:</b></label><br />
                                <p class="form-control-static">
                                    <?php echo $audit['lot_no']; ?>
                                </p>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
        
    <div class="row">
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        Inspection Items | Total <?php echo count($audit_lqc_defect); ?>
                        <small><?php echo ' (OK - '.$defect_OK.', NG - '.$defect_NG.')'; ?></small>
                    </div>
                    
                    <?php if(!isset($admin_edit_audit)) { ?>
                        <div class="actions">
							
                            <a id="mark_comp" href="<?php echo base_url().'auditer/mark_as_complete_lqc/';?>" data-confirm="Are you sure you want to mark this inspection result as complete. Once marked as complete the inspection result can't be changed." class="button normals btn-circle">    
                                Mark As Completed
                            </a>
                           
                            <a href="<?php echo base_url().'auditer/mark_as_abort_lqc';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red"
                            data-confirm="Are you sure you want to cancel this inspection?">
                                Abort
                            </a>
							
							<a href="<?php echo base_url().'auditer/mark_as_retest_lqc';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red"
                            data-confirm="Are you sure you want to send this inspection to Retest?">
                                Retest
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="portlet-body form">
                    <table class="table table-hover table-light">
                        <thead>
                            <tr>
                                <th class="text-center" style="vertical-align: middle;">#</th>
                                <th class="text-center" style="vertical-align: middle;">Serial No.</th>
                                <th class="text-center" style="vertical-align: middle;">Defects Occured</th>
                                <th class="text-center" style="vertical-align: middle;">Remark</th>
								<th class="text-center" style="vertical-align: middle;">Retest Remark</th>
                                <th class="text-center" style="vertical-align: middle;">Results</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
								$i = 1;
								
							foreach($audit_lqc_defect as $ald) { ?>
							
                                <?php 
                                    $class = '';
                                    if(empty($ald['result']) || $ald['result'] == 'NA') {
                                        $class = "warning";
                                    } else if($ald['result'] == 'NG') {
                                        $class = 'danger';
                                    }
                                    // echo $admin_edit_audit;
                                    $url = base_url().'auditer/retest_audit_lqc_defect/'.$ald['id'];
                                    if(isset($admin_edit_audit)) {
                                        $url .= "/".$admin_edit_audit;
                                    } 
                                ?>
                                <tr class="<?php echo $class; ?>" 
									<?php if($ald['result'] == 'NG'){ ?>
									href="<?php echo $url; ?>" 
									data-target="#change-audit_lqc_defect-modal" data-toggle="modal"
									<?php } ?>
									>
                                    <td class="text-center"><?php echo $i; ?></td>
                                    <td class="text-center"><?php echo $ald['serial_no']; ?></td>
                                    <td class="text-center"><?php echo $ald['defect_occured']; ?></td>
									<td class="text-center"><?php echo $ald['remark']; ?></td>
									<td class="text-center">
									<?php 
										if(isset($ald['retest_remark']))
											echo $ald['retest_remark'];
										else "";
									?>
									</td>
                                    <td class="text-center"><?php echo $ald['result']; ?></td>
                                </tr>
                            <?php 
							$i++;
							} ?>
                        </tbody>
                    </table>
                    
                    <?php if(!isset($admin_edit_audit)) { ?>
                        <div class="form-actions right">
                             <a id="mark_comp" href="<?php echo base_url().'auditer/mark_as_complete_lqc/'.$ald['id'];?>" data-confirm="Are you sure you want to mark this inspection result as complete. Once marked as complete the inspection result can't be changed." class="button normals btn-circle">    
                                Mark As Completed
                            </a>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

<div class="modal fade bs-modal-lg modal-scroll" id="change-audit_lqc_defect-modal" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <img src="../assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>