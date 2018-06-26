<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Daily Production Plan - <?php echo date('jS M, Y', strtotime($plan_date)); ?>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Daily Production Plan</li>
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
            <form role="form" class="form-inline" method="post" action="<?php echo base_url().'sampling/view_plan_date'; ?>">
                <div class="form-group">
                    <label class="control-label col-md-6" style="font-size: 15px; margin-top: 6px; text-align: right;">
                        Date <i class="fa fa-arrow-right"></i>
                    </label>
                    <div class="input-group date date-picker col-md-6" data-date-format="yyyy-mm-dd">
                        <input name="view_plan_date" type="text" class="required form-control" readonly
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
                        <i class="fa fa-reorder"></i>Production Plan
                    </div>
                    
                    <div class="actions">
                        <!--a class="button normals btn-circle" href="<?php echo base_url()."sampling/fetch_production_plan"; ?>">
                            <i class="fa fa-refresh"></i> Fetch Today's Plan	fetch_plan_confirm()
                        </a-->
						<a id="fetch_plan_confirm" onClick="return confirm('If you fetch todays plan all the anitment of todays plan will lost. Are you sure want to continue ?');" class="button normals btn-circle" href="<?php echo base_url()."sampling/fetch_production_plan"; ?>">
                            <i class="fa fa-refresh"></i> Fetch Today's Plan
                        </a>
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/upload_production_plan"; ?>">
                            <i class="fa fa-plus"></i> Upload New Production Plan
                        </a>
                        <?php if(!empty($production_plan)) { ?>
                            <a class="button normals btn-circle" href="<?php echo base_url()."sampling/view_sampling_plan/".$plan_date; ?>">
                                <i class="fa fa-eye"></i> View Sampling Plan
                            </a>
                            <!--a class="button normals btn-circle" href="<?php echo base_url()."sampling/create_sampling_plan/".$plan_date; ?>">
                                <i class="fa fa-refresh"></i> Create Sampling Plan
                            </a-->
							<a class="button normals btn-circle" href="<?php echo base_url()."sampling/create_sampling_plan_new/".$plan_date; ?>" title="This will create sampling for overall product of this date.">
                                <i class="fa fa-refresh"></i> Create Product Sampling Plan
                            </a>
                        <?php } ?>
                        <a class="button normals btn-circle" href="<?php echo base_url()."sampling/add_production_plan/".$plan_date; ?>">
                            <i class="fa fa-plus"></i> Add Production Plan
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($production_plan)) { ?>
                        <p class="text-center">No Production Plan.</p>
                    <?php } else { ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-bordered">
                                <thead>
                                    <tr>
                                        <td>Line</td>
                                        <td>Tool</td>
                                        <td>Model.Suffix</td>
                                        <td>Lot Size</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($production_plan as $plan) { 
									// print_r($plan);
									?>
                                        <tr class="producton-plan-<?php echo $plan['id']; ?>" href="<?php echo base_url()."sampling/production_plan/".$plan['id']; ?>" 
                                            data-target="#adjust-production-modal" data-toggle="modal">
                                            <td><?php echo $plan['line']; ?></td>
                                            <td><?php echo $plan['tool']; ?></td>
                                            <td><?php echo $plan['model_suffix']; ?></td>
                                            <td>
                                                <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="ajay-image-loading loading" style="display:none;">
                                                
                                                <span class="return-content-section">
                                                    <span style="font-size:15px;"><?php echo $plan['lot_size']; ?></span>
                                                    <?php if(empty($plan['original_id'])) { ?>
														<?php if($plan['lot_size'] != $plan['original_lot_size']) { ?>
															<small style="text-decoration:line-through;"> <?php echo $plan['original_lot_size']; ?></small>
														<?php } ?>
                                                    <?php } ?>
                                                </span>
                                            </td>
                                            <td>
                                                <?php if(!empty($plan['is_user_defined'])) { ?>
                                                    <a class="btn btn-outline btn-xs sbold red" href="<?php echo base_url()."sampling/delete_production_plan/".$plan['id'];?>" data-confirm="Are you sure you want to delete this?">
                                                    <i class="fa fa-trash-o"></i> Delete
                                                    </a>
													<a class="btn  btn-xs sbold red" href="<?php echo base_url()."sampling/create_sampling_plan_individual/".$plan['id'];?>" data-confirm="Are you sure you want to Create Sampling for this plan?">
                                                    <i class="fa fa-add-o"></i> Create Sampling Plan
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

<div class="modal fade" id="adjust-production-modal" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="<?php echo base_url(); ?>assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>


<script>
function fetch_plan_confirm(){	
		var base_url = $('#base_url').val();
		var m = base_url+'sampling/fetch_production_plan';
		bootbox.dialog({
            message: "If you fetch today's plan all the anitment of today\'s plan will lost. Are you sure want to continue ?",
            title: "Confirmation box",
            buttons: {
                confirm: {
                    label: "Yes",
                    className: "red",
                    callback: function() {                        
                        // $('#fetch_plan_confirm').trigger('click'); 
						//$('#fetch_plan_confirm').prop("href", m);
						// alert(123);
                    }
                },
                cancel: {
                    label: "No",
                    className: "blue"
                }
            }
        });	

		
		
}
</script>