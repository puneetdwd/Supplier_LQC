
<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Supplier Timecheck Counts 
        </h1>
           <!--span class='caption' style='float:right'>Date:<?php echo $yesterday; ?></span-->  
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
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
                                    <?php $date = $this->input->post('date') ? $this->input->post('date') : date('Y-m-d'); ?>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Select Date</label>
                                                <div class="required input-group date date-picker col-md-6" data-date-format="yyyy-mm-dd" data-date-end-date="<?php echo date('Y-m-d'); ?>">
                                                    <input name="date" type="text" class="required form-control" readonly
                                                    value="<?php echo $date; ?>">
                                                    <span class="input-group-btn">
                                                        <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>

                                    <!--<?php if($this->user_type == 'Admin' || $this->user_type == 'LG Inspector'){ ?>
                                        <div class="col-md-6">
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
                                    <?php } ?>-->
                                    
                                </div>
                                
                                <div class="form-actions">
                                    <button class="button" type="submit">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
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
                        <i class="fa fa-reorder"></i>List of Supplier Timecheck Count 
                    </div>
						
                    <div class="actions">
                        <?php if(!empty($plans)) { ?>
						<a class="button normals btn-circle" href="<?php echo base_url()."reports/timecheck_count_by_supplier_download/".$date; ?>">
                            <i class="fa fa-download"></i> Excel Export
                        </a>
						<?php } ?>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($plans)) { ?>
                        <p class="text-center">No Supplier has done timecheck on this date.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Supplier Id</th>
                                    <th>Supplier Name</th>
                                    <th>Counts</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
								foreach($plans as $plan) { ?>
                                    <tr>
										<td><?php echo $plan['supplier_no']; ?></td>
										<td><?php echo $plan['name']; ?></td>
										<td><?php echo $plan['cnt']; ?></td>
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