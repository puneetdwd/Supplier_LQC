<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            View Plan - <?php echo date('jS M, Y', strtotime($plan_date)); ?>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">View Plan</li>
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
        </div>
    </div>
    
    <div class="row" style="margin-bottom:10px;">
        <div class="col-md-5 col-md-offset-7 text-right">
            <form role="form" class="form-inline" method="get" action="<?php echo base_url().'lqc_plan/plans'; ?>">
                <div class="form-group">
                    <label class="control-label col-md-6" style="font-size: 15px; margin-top: 6px; text-align: right;">
                        Date <i class="fa fa-arrow-right"></i>
                    </label>
                    <div class="input-group date date-picker col-md-6" data-date-format="yyyy-mm-dd">
                        <input name="plan_date" type="text" class="required form-control" readonly
                        value="<?php echo $plan_date; ?>">
                        <span class="input-group-btn">
                            <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                        </span>
                    </div>
                </div>
                
                <button class="button" type="submit">Search</button>
            </form>
        </div>    
    </div>

    <div class="row">
        
        <div class="col-md-12">
            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> Plans for date - <?php echo date('jS M, Y', strtotime($plan_date));?>
                    </div>
					<?php if($this->session->userdata('user_type') == 'Supplier') { ?>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."lqc_plan/add_plan"; ?>">
                            <i class="fa fa-plus"></i> Add Plan
                        </a>
                    </div>
					<div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."lqc_plan/upload_production_plan"; ?>">
                            <i class="fa fa-plus"></i> Upload Plan
                        </a>
                    </div>
					<?php } ?>
                </div>
                <div class="portlet-body">
                    <?php if(empty($plans)) { ?>
                        <p class="text-center">No Plan added yet for this date.</p>
                    <?php } else { 
					// print_r($plans);exit;
					?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Part</th>
                                        <th>Lot Size</th>                                        
                                        <th class="no_sort" style="width:150px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($plans as $plan) { ?>
                                        <tr>
                                            <td><?php echo $plan['part_name'].' ('.$plan['part_no'].')'; ?></td>
                                            <td><?php echo $plan['lot_size']; ?></td>                                          
                                            <td nowrap class="text-center">
												<a class="button small gray"  target="_blank"
													href="<?php echo base_url()."qr_code_generate/number_qr_print/".$plan['part_id']."/".$plan['lot_size'];	?>" >
													<i class="fa fa-view"></i> Print QR
												</a> 
												<?php if($this->session->userdata('user_type') == 'Supplier') { ?>
                                                <a class="button small gray" href="<?php echo base_url()."lqc_plan/add_plan/".$plan['id']; ?>">
                                                    <i class="fa fa-edit"></i> Edit
                                                </a>
                                                <a class="btn btn-outline btn-xs sbold red-thunderbird" href="<?php echo base_url()."lqc_plan/delete_plan/".$plan['id']; ?>" data-confirm="Are you sure you want to delete this plan?">
                                                    <i class="fa fa-trash-o"></i> Delete
                                                </a>
												<?php } ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
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



<div class="modal fade bs-modal-lg modal-scroll" id="qr-modal" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <img src="../assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>

