<style>
    .form-inline .select2-container--bootstrap{
        width: 300px !important;
    }
    
</style>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage Timecheck & Foolproof Check
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage Timecheck & Foolproof Check</li>
        </ol>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
   
    <div class="row" style="margin-top:15px;">
        
        <div class="col-md-12">

            <?php if($this->session->flashdata('error')) { ?>
                <div class="alert alert-danger">
                   <i class="fa fa-times"></i>
                   <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php } else if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                    <i class="fa fa-check"></i>
                   <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php } ?>

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i>Manage Timecheck-Foolproof Checks
                    </div>
                   
                </div>
                <div class="portlet-body">                    
					<form role="form" class="validate-form form-inline" method="post" action='tc_pf_update' style='width: 50%;margin: 10px auto;'>
						<div class="form-group" id="" >
							<input <?php if($tc_fp_status['foolproof_chk'] == 1) { ?> checked = 'checked' <?php } ?> 
							 data-index="foolproof" type="checkbox" name="foolproof" id="foolproof"  ><span>Do you want to enable Foolproof?</span>
							</br>
							</br>
							<input <?php if($tc_fp_status['timecheck_chk'] == 1) { ?> checked = 'checked' <?php } ?>  data-index="timecheck" type="checkbox" name="timecheck" id="timecheck"  ><span>Do you want to enable Timecheck?</span>
						</div>
						<hr>
						<button class="button" type="submit">UPDATE</button>
					</form>
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
