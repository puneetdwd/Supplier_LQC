<?php
$CI =& get_instance(); 
$CI->load->model('Checkpoint_model');
$tc_fp_status = $CI->Checkpoint_model->get_tc_fp_status();		
?>
 

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
	
    <div class="breadcrumbs">
        <!--<h1>
            Complete the following Checklists
        </h1>-->
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Checklist</li>
        </ol>
        
    </div>
   
    <div class="row">
	 
           <div class="alert alert-danger">
               <i class="fa fa-times"></i>
               
			<?php 
			   if($this->session->flashdata('error')) {
				   echo $this->session->flashdata('error');
			   }
			   else if($this->session->flashdata('success')) { 
					echo $this->session->flashdata('success');
			} ?>
			   
			   
			   Please complete the 			   
			   <?php if($tc_fp_status['timecheck_chk'] == 1){ ?>
			   <a style="color:#c80541;font-weight:700" href="<?=base_url().'timecheck'; ?>">Timechecks</a> 
			   <?php } ?>
			   
			   <?php if($tc_fp_status['timecheck_chk'] == 1 && $tc_fp_status['foolproof_chk'] == 1){ 
				echo '&';
			   }
			   ?>
			   
			   <?php if($tc_fp_status['foolproof_chk'] == 1){ ?>			   
			   <a style="color:#c80541;font-weight:700"  href="<?=base_url().'fool_proof/start'; ?>">Foolproofs</a> first.
			   <?php } ?>
            </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>