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
            LQC Inspection | Punch Occured Defect
        </h1>        
        <a href="<?php echo base_url().'auditer/mark_as_abort_lqc';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red"
		data-confirm="Are you sure you want to cancel this inspection?">
			Abort
		</a>
		<a href="<?php echo base_url().'auditer/mark_as_remove_remaining';?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red"
		data-confirm="Are you sure you want to cancel further inspection of this lot?">
			Remove All
		</a>
        <a href="<?php echo base_url();?>" class="btn btn-circle btn-outline pull-right btn-sm sbold red" target="_blank">
            Go To Home
        </a>
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
					<div class="row">
                        <div class="col-md-4">
                            <div class="">
                                <label class="control-label"><b>Lot No.:</b></label>
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
            <div class="portlet light bordered mt-element-ribbon" style="padding-top: 8px;">
                <div class="ribbon ribbon-clip ribbon-color-danger uppercase">
                    <div class="ribbon-sub ribbon-clip"></div> <b>Remaining Lot: <?php echo isset($audit['remaining_prod_lot_qty']) ? $audit['remaining_prod_lot_qty'] : $audit['prod_lot_qty'];	?></b> 
                </div>
                <h3 style="margin:50px;float:left">Possible Defect Codes:</h3>
                <p style="float:right"><b>Note:</b></br> Green Button=>Unpunched Defect</br>Red Button=>Punched</p>
                <div class="portlet-body form">
                    <div class="form-body" style="padding-top: 0px; padding-bottom: 0px;">
                       	<div class="row" >
							<div class="col-md-8 text-center mar" >
								<div class="form-group">
										<!--label style="float:left" for="qr_num" class="control-label">QR Code: </label--></br>
                                        <input required style="height: 35px;" type="text" class="required input-sm qr_width" id="qr_num"  name="qr_num" value="" placeholder="QR Code" onblur="return check_qr_duplicate(<?php echo $audit['id']; ?>,<?php echo $audit['part_id']; ?>);"/>
                                
								
										<label class="control-label df_label" style="display: table;">Possible Defect Code: </label>
											<div class="row">
										
											
											<?php 
											$i = 0;
											foreach($defect_code as $dc){ 
													$button_txt =  $dc['defect_description']."-". $dc['defect_description_detail']; 
													if(strlen($button_txt) > 40)
														$out = strlen($button_txt) > 17 ? substr($button_txt,0,17)."</br>".substr($button_txt,17,40).'...' : $button_txt;
													else
														$out = strlen($button_txt) > 17 ? substr($button_txt,0,17)."</br>".substr($button_txt,17,40) : $button_txt;
													$val_b =  "<span style='font-size: 12px;'>".$out. "</span>"; 
												?>
												
												<div class="col-sm-4" >
													<button name="defect_code<?php echo $i; ?>" id="defect_code<?php echo $i; ?>" value="<?php echo $button_txt; ?>" style="border-radius: 12px !important;" type="button" class="btn btn-primary btn-lg unpunched btn_width" data-toggle="button" aria-pressed="false" defect_code_id ="<?php echo $dc['id']; ?>" autocomplete="off" onclick="return change_bg(this);">
															  <?php echo $val_b; ?>
															  
													</button>
												</div>
												<?php 
												$i++;
											} 
											?>
											
										</div>
								
										</br>
                                        <input id="register-inspection-remark" class="input-sm" onblur="return check_space(this);" name="remark" placeholder="Remarks" style="height: 35px;"></input>
										<span class="help-block"></span> 
                               
									<button style="margin-top:20px;" type="submit" id="register-lqc-submit" class="btn btn-circle green-meadow" onclick="return submit_inspection(<?php echo $i.','.$audit['id']; ?>);">Submit</button>
								</div> 
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		
	</div>

<script>
	$(document).ready(function(){
		$("html, body").animate({ scrollTop: 100 }, 1000);
		$("header").hide();
	});
	function submit_inspection(i,audit_id)
	{
		var serial_no = $('#qr_num').val();
		serial_no = serial_no.trim();
			vl= serial_no.length;
			//alert(val.length);
			if(serial_no.length > 0)
			{
				
			}
			else{
				bootbox.alert({ 
			  title: "Alert Box",
			  message: "QR Code is Required.", 
			  callback: function(){ /* your callback code */ }
			});
			              
		}
	
	
	var max_punch_code = i - 1;
	var defect_occured = new Array();
	var defect_occured_ids = new Array();

	for(i = 0 ; i <= max_punch_code ; i++){
		var cidd = 'defect_code'+i;
		var cid = '#defect_code'+i;
		var vall = $(cid).val();
		var status = document.getElementById(cidd).getAttribute("aria-pressed");
		// alert(defect_code_id);
		 if(status == 'true'){			 
			 defect_code_id = document.getElementById(cidd).getAttribute("defect_code_id");
			 defect_occured.push(vall);
			 defect_occured_ids.push(defect_code_id);
		 }
	}
	
	var remark = $('#register-inspection-remark').val();
	
	if(defect_occured.length > 0){
		var result = 'NG';
		
		remark = remark.trim();
		vl= remark.length;
		//alert(val.length);
		if(remark.length > 0)
		{
			
		}
		else{
			if(result == 'NG'){
				// document.getElementById("register-inspection-remark").setAttribute("required");
				$('#register-inspection-remark').addClass('required');
				bootbox.alert({ 
				  title: "Alert Box",
				  message: "Remark is Required.", 
				  callback: function(){ 
						
				  }
				});
			}
			              
		}
	}
	else{ 
		var result = 'OK';
	}
	
	var qr_code = $('#qr_num').val();
	var ex = 0;
	// alert(qr_code);
	if(serial_no.length > 0){
		$.ajax({
				type: 'POST',
				url: 'check_duplicate_qrcode',
				data: { audit_id : audit_id , qr_code : qr_code },
				dataType: 'json',
				success: function(resp) {
						ex = resp.qr_exist;
						// alert(ex);
						// alert(resp.qr_exist);
						if(ex > 0){
							console.log("QR not OK");
							
							bootbox.alert({ 
							  title: "Error Box",
							  message: "This QR Code has been already Scaned. Please scan another QR Code to continue.", 
							  callback: function(){
								  
							  }
							});
						} 
					
				}
				
		});
	}	
		if(ex == 0){
			if((result == 'NG' && remark.length > 0 && serial_no.length > 0) || (result == 'OK' && serial_no.length > 0) ){
				$.ajax({
					type: 'POST',
					url: 'submit_defect',
					data: { audit_id : audit_id , result : result , defect_occured : defect_occured,serial_no : serial_no,defect_occured_ids:defect_occured_ids,remark:remark },
					dataType: 'json',
					success: function(resp) {
						// alert(result);
						if(resp['audit_def_id'] > 0){
							console.log("Data Recored.");
							
							bootbox.alert({ 
							  title: "Confirm Box",
							  message: "Data Recorded.", 
							  callback: function(){
								window.location.href = "<?php echo base_url();	?>auditer/continue_inspection_lqc";
							  }
							});
							
							window.setTimeout(function(){
								bootbox.hideAll();
							}, 2000);
							
							window.location.href = "<?php echo base_url();	?>auditer/continue_inspection_lqc";
						}
					}
						
				});
			}
		}
	
} 

function change_bg(btn){
	var cidd = btn.getAttribute('name');
	var status = document.getElementById(cidd).getAttribute("aria-pressed");
	
	if(status == 'false'){			 
		$(btn).removeClass('unpunched');
		$(btn).addClass('punched');
		
		$('#register-inspection-remark').addClass('required');
		
		
           
	 }
	 else if(status == 'true'){			 
		$(btn).removeClass('punched');
		$(btn).addClass('unpunched');
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
	
function check_qr_duplicate(audit_id,part_id){
	var qr_code = $('#qr_num').val();
	if(qr_code != ''){
	$.ajax({
			type: 'POST',
			url: 'check_duplicate_qrcode',
			data: { audit_id : audit_id , qr_code : qr_code },
			dataType: 'json',
			success: function(resp) {
					ex = resp.qr_exist;
					// alert('abc');
					// alert(resp.qr_exist);
					if(ex > 0){
						console.log("QR Code Already inspected");
						bootbox.alert({ 
							  title: "Error Box",
							  message: "This QR Code has been already Scaned. Please scan another QR Code to continue.", 
							  callback: function(){
							  
							  }
						});
					} 
					else{
						$.ajax({
							type: 'POST',
							url : 'part_related_qrcode',
							data: { qr_code : qr_code , part_id : part_id },
							dataType: 'json',
							success: function(resp) {
									// json_decode(resp);
									// alert(resp);
									if(resp == 0){
										console.log("QR Code donot found for this part number.");
										bootbox.alert({ 
												  title: "Error Box",
												  message: "QR Code donot found for this part number.", 
												  callback: function(){
												}
										});
									} 
									else if(resp > 0){
										//alert('OK');
										console.log("QR Code found for this part number.");
									}
							}
						});
					}
			}
		});
	}
}
	
</script>
