<!--link href="https://gitcdn.github.io/bootstrap-toggle/2.2.2/css/bootstrap-toggle.min.css" rel="stylesheet">
<script src="https://gitcdn.github.io/bootstrap-toggle/2.2.2/js/bootstrap-toggle.min.js"></script-->

<style>
.form-group{
    margin-bottom: 0px;
}
.table > thead > tr > th,.table > tbody > tr > td{
    padding : 4px 8px;
}
.btn-block{
    display: inline;
}
.mt-element-ribbon .ribbon{
    top: 6px;
}
.portlet.light > .portlet-title{
    min-height: 30px;
}
.portlet > .portlet-title{
    margin-bottom: 2px;
}
.portlet.light > .portlet-title > .actions {
    padding: 0 0 8px;
}
textarea.form-control {
    overflow-y: unset;
}
.mt-element-ribbon .ribbon {
    padding: 0.2em 1em;
}
.form-control-static{
    min-height: 25px;
    padding-top: 0;
}

.portlet.light.bordered > .portlet-title {
    border-bottom: 1px solid #ddd;
}
.portlet.light.bordered {
    border: 1px solid #ddd !important;
}
.guideline-image-section {
    /*height:350px;*/
    overflow-y:scroll;
    margin-bottom:20px;
}
.help-block {
    margin:0px;
}
.carousel-inner > .item > img,
.carousel-inner > .item > a > img {
    width: 100%;
    margin: auto;
}
.carousel-control.right{
    background-image: none;
}
.carousel-control.left{
    background-image: none;
}
.carousel-caption, .carousel-control{
    color: #C80541 !important;
}
.carousel-indicators li{
    border: 1px solid #C80541;
}
.carousel-indicators .active{
    background-color: #C80541;
}

@media print{
body {display:none;}
}

.unpunched{
	background-color:#006600 !important;
	border-color: #006600 !important;
}
.punched{
	background-color:#ff0000 !important;
	border-color: #ff0000 !important;
}
</style>

<div class="page-content" style="padding:20px 10px;">

    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs" style="margin-bottom:5px;">
        <h1>
            Retest LQC Inspection 
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
            <div class="portlet light bordered" style="padding-top: 5px; padding-bottom: 0px; margin-bottom: 2px;">

                <div class="portlet-body form">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="">
                                <label class="control-label"><b>Supplier:</b></label>
                                <p class="form-control-static">
                                    <?php echo $audit['supplier_no'].' - '.$audit['supplier_name']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="">
                                <label class="control-label"><b>Product:</b></label>
                                <p class="form-control-static">
                                    <?php echo $audit['product_name']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="">
                                <label class="control-label"><b>Part:</b></label>
                                <p class="form-control-static">
                                    <?php echo $audit['part_no'].' - '.$audit['part_name']; ?>
                                </p>
                            </div>
                        </div>
                        
                        <div class="col-md-2">
                            <div class="">
                                <label class="control-label"><b>Lot Qty:</b></label>
                                <p class="form-control-static">
                                    <?php echo $audit['prod_lot_qty']; ?>
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
            <div class="portlet light bordered mt-element-ribbon" style="padding-top: 8px;">
               
                <h3 style="margin:50px;float:left">Possible Defect Codes:</h3>
                <p style="float:right"><b>Note:</b></br> Green Button=>Unpunched Defect</br>Red Button=>Punched</p>
                <div class="portlet-body form">
                    <div class="form-body" style="padding-top: 0px; padding-bottom: 0px;">
                       	<div class="row" >
							<div class="col-md-8 text-center" style="margin:0 16% 0 16%">
							<?php  //	print_r($lqc_defect_code); ?>
							
								<div class="form-group">
										<!--label style="float:left" for="qr_num" class="control-label">QR Code: </label--></br>
                                        <input readonly disabled style="width: 495px;height: 35px;" type="text" class="required input-sm " id="qr_num"  name="qr_num" placeholder="QR Code" value="<?php echo $lqc_defect_code['serial_no']; ?>" />
                                </div>
								
								<div class="form-group">
										<label style="margin: 20px 0 0 -360px !important;" class="control-label">Possible Defect Code: </label></br>
                                </div> 
								
								<div class="form-group">
									<?php 
									$i = 0;
									foreach($defect_code as $dc){ 
									
													$df = explode(',',$lqc_defect_code['defect_occured']);
													array_unshift($df,"LGEEXTRA");
													
													$button_txt =  $dc['defect_description']."-". $dc['defect_description_detail']; 
													if(strlen($button_txt) > 40)
														$out = strlen($button_txt) > 17 ? substr($button_txt,0,17)."</br>".substr($button_txt,17,40).'...' : $button_txt;
													else
														$out = strlen($button_txt) > 17 ? substr($button_txt,0,17)."</br>".substr($button_txt,17,40) : $button_txt;
													$val_b =  "<span style='font-size: 12px;'>".$out. "</span>";
										?>
										
										<div class="col-sm-4" >
											<button name="defect_code<?php echo $i; ?>" id="defect_code<?php echo $i; ?>" value="<?php echo $button_txt; ?>" style="border-radius: 12px !important;" type="button" class="btn btn-primary btn-lg  btn_width
											<?php if(array_search($button_txt,$df)){
												echo 'punched';
											}else{
												echo 'unpunched';
											} ?>" data-toggle="button"  defect_code_id ="<?php echo $dc['id']; ?>" autocomplete="off" onclick="return change_bg(this);" 
											aria-pressed="<?php if(array_search($button_txt,$df)){
												echo 'true';
											}else{
												echo 'false';
											} ?>" >
													<?php echo $val_b; ?>
													  
											</button>
										</div>
											
										<?php 
										$i++;
									} ?>
								</div>
								<div class="form-group">
										</br>
                                        <textarea disabled readonly id="register-inspection-remark"  onblur="return check_space(this);" name="remark" placeholder="Remarks" rows="2" cols="70"><?php echo $lqc_defect_code['remark'];	?>	</textarea>
										<span class="help-block"></span> 
                                </div> 
								<div class="form-group">
										</br>
                                        <textarea  id="register-inspection-retest-remark"  onblur="return check_space(this);" name="retest_remark" placeholder="Repair Content Remark" rows="2" cols="70">
											<?php 
												if(isset($lqc_defect_code['retest_remark']))
													echo $lqc_defect_code['retest_remark'];
												else "";
											?>
										</textarea>
										<span class="help-block"></span> 
                                </div> 
								<div class="form-group">
									<button style="margin-top:20px;" type="submit" id="register-lqc-submit" class="btn btn-circle green-meadow" onclick="return submit_inspection(<?php echo $i.','.$audit['id'].','.$lqc_defect_code['id']; ?>);">Submit</button>
									<a style="margin-top:20px;" class="btn btn-circle default" href="<?php echo base_url();	?>auditer/finish_screen_lqc_retest/<?php echo $audit['id'];	?>" >Cancel</a>
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- END PAGE CONTENT-->
	</div>

<script>
$(document).ready(function(){
    $("html, body").animate({ scrollTop: 100 }, 1000);
    $("header").hide();
});


function submit_inspection(i,audit_id,al_id)
{
	
	var max_punch_code = i - 1;
	var defect_occured = new Array();
	var defect_occured_ids = new Array();

		// alert(max_punch_code);
	for(i = 0 ; i <= max_punch_code ; i++){
		var cidd = 'defect_code'+i;
		var cid = '#defect_code'+i;
		var vall = $(cid).val();
		var status = document.getElementById(cidd).getAttribute("aria-pressed");
		 if(status == 'true'){			 
			 defect_code_id = document.getElementById(cidd).getAttribute("defect_code_id");
			 defect_occured.push(vall);
			 defect_occured_ids.push(defect_code_id);
		// alert(defect_code_id);
		 }
	}
	
	var remark = $('#register-inspection-remark').val();
						var retest_remark = $('#register-inspection-retest-remark').val();
	
	if(defect_occured.length > 0){
		var result = 'NG';
		
		remark = remark.trim();
		vl= remark.length;
		//alert(val.length);
		if(remark.length > 0)
		{
			
		}
		else{
			bootbox.alert({ 
			  title: "Alert Box",
			  message: "Remark is Required.", 
			  callback: function(){ /* your callback code */ }
			});
			              
		}
	}
	else{ 
						retest_remark = retest_remark.trim();
						vl= retest_remark.length;
						//alert(val.length);
						if(retest_remark.length > 0)
						{
							
						}
						else{
							bootbox.alert({ 
							  title: "Alert Box",
							  message: "Retest Remark is Required.", 
							  callback: function(){ /* your callback code */ }
							});
										  
						}
		var result = 'OK';
	}
	// alert(result);
		$.ajax({
			type: 'POST',
			url: '<?php echo base_url();	?>auditer/retest_update_submit_defect',
			data: { result : result , defect_occured : defect_occured,defect_occured_ids:defect_occured_ids,remark:remark,retest_remark:retest_remark,al_id:al_id },
			dataType: 'json',
			success: function(resp) {
				// alert(resp['audit_def_id']);
				if(resp['audit_def_id'] > 0){
					console.log("Data Recored.");
					bootbox.alert({ 
					  title: "Confirm Box",
					  message: "Data Recorded.", 
					  callback: function(){
						  <?php 
							 $this->session->set_flashdata('success', 'Inspection successfully Edited.');
						  ?>
						window.location.href = "<?php echo base_url();	?>auditer/finish_screen_lqc_retest/"+audit_id;					  
					  }
					});
					
				}
			}
				
		});
} 

function change_bg(btn){
	var cidd = btn.getAttribute('name');
	var status = document.getElementById(cidd).getAttribute("aria-pressed");
	/* alert(cidd);
	alert(status); */
	if(status == 'false'){			 
		$(btn).removeClass('unpunched');
		$(btn).addClass('punched');
		$(btn).attr("aria-pressed","true");
		$('#register-inspection-remark').addClass('required');
	 }
	 else if(status == 'true'){			 
		$(btn).removeClass('punched');
		$(btn).addClass('unpunched');
		$(btn).attr("aria-pressed","false");
		$('#register-inspection-remark').removeClass('required');
	 }
}

function check_space(id) {
		var colClass = id.className;
		//alert(colClass);exit;
		if(colClass == 'required'){
			// alert($(this).html());
			var val = document.getElementById('register-inspection-remark');
			val = val.value;
			val = val.trim();
			vl= val.length;
			//alert(val.length);
			if(val.length > 0)
			{
				
			}
			else{
				bootbox.alert({ 
				  title: "Alert Box",
				  message: "Please enter valid Remark! Only Space has been entered which is considered as invalid input.", 
				  callback: function(){ /* your callback code */ }
				});
				$('#register-inspection-remark').html('');                
			}
		}
				//exit;		
	}
	
</script>
