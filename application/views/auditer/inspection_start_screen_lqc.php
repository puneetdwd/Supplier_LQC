<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            LQC Inspection | Start Screen
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
        
        <div class="col-md-3">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        LQC Inspection Details
                    </div>
                </div>
                <div class="portlet-body form inspection-detail-sidebar">
                    <!-- BEGIN FORM-->
                    <form role="form">
                        <div class="form-body">
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Supplier:</b></label><br />
                                        <p class="form-control-static">
                                            <?php echo $audit['supplier_no'].' - '.$audit['supplier_name']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Product:</b></label><br />
                                        <p class="form-control-static">
                                            <?php echo $audit['product_name']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Part Name:</b></label><br />
                                        <p class="form-control-static">
                                            <?php echo $audit['part_name']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Part No:</b></label><br />
                                        <p class="form-control-static">
                                            <?php echo $audit['part_no']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Inspection Lot Qty.:</b></label><br />
                                        <p class="form-control-static">
                                            <?php echo $audit['prod_lot_qty']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label class="control-label"><b>Lot No:</b></label><br />
                                        <p class="form-control-static">
                                            <?php echo $audit['lot_no']; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-9">
            
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Inspection | <small>Total No. of Inspection Items <?php echo count($defect_code); ?> <!--Applicable defect_code <?php echo count($defect_code); ?>--></small>
                    </div>
                    <div class="actions">
                        <a href="<?php echo base_url().'auditer/start_inspection_lqc'; ?>" class="button normals btn-circle start-lqc-inspection-button">    
                            Start LQC Inspection
                        </a>
                        <a href="<?php echo base_url().'auditer/mark_as_abort_lqc';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red" data-confirm="Are you sure you want to cancel this inspection?">
                            Abort
                        </a>
                        
                    </div>
                </div>
                <div class="portlet-body form">
                    <?php 
					if(empty($defect_code)) { 
					
					?>
                        <p class="text-center">No Defect Codes.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light">
                            <thead>
                                <tr>
                                    <th class="text-center" style="vertical-align: middle;">Product Name</th>
                                    <th class="text-center" style="vertical-align: middle;">Part Name</th>
                                    <th class="text-center" style="vertical-align: middle;">Part Number</th>
                                    <th class="text-center" style="vertical-align: middle;">Supplier Name</th>
                                    <th class="text-center" style="vertical-align: middle;">Defect Code</th>
                                    <th class="text-center" style="vertical-align: middle;">Defect Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($defect_code as $dc) { ?>
                                    
                                    <tr>
                                        <td class="text-center" ><?php echo $dc['product_name']; ?></td>
                                        <td class="text-center" ><?php echo $dc['part_name']; ?></td>
                                        <td class="text-center" ><?php echo $dc['part_no']; ?></td>
                                        <td class="text-center" ><?php echo $dc['supplier_name']; ?></td>
										<td class="text-center" ><?php echo $dc['defect_description']; ?></td>
										<td class="text-center" ><?php echo $dc['defect_description_detail']; ?></td>
                                       
                                        
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    
                    <div class="form-actions right">
                        <a href="<?php echo base_url().'auditer/start_inspection_lqc'; ?>" class="button normals btn-circle start-lqc-inspection-button">    
                            Start LQC Inspection
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>


<?php if(!empty($defect_code)) { ?>
    <script>
        $(window).load(function() {
            $('.fetch-sample-qty-button:first').trigger('click');
        });
    </script>
<?php } ?>