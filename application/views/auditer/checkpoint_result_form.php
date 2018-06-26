<script type="text/javascript">
	function lookup(arg){		
		 
		  /* var charCode = (arg.which) ? arg.which : arg.keyCode;
          if (charCode != 46 && charCode > 31 
            && (charCode < 48 || charCode > 57))
             return false;

          return true; */
		 
		 var id = arg.getAttribute('id');
		 var value = id.value;
		 var val = document.getElementById(id);
		 val = val.value;		 
		 val = parseFloat(val);
		 var lsl = document.getElementById("register-inspection-checkpoint-lsl");
		 lsl = lsl.value;
		 lsl = parseFloat(lsl)
		 var usl = document.getElementById("register-inspection-checkpoint-usl");
		 usl = usl.value;
		 usl = parseFloat(usl)
		document.getElementById(id).style.background   = '#fff';
		var as = 'NG';
			
		if(val){
			if(lsl != '' && parseFloat(val) < parseFloat(lsl)) {
				document.getElementById(id).style.background   = 'red';
				as = 'NG';
			}
			else{
				as = 'OK';
			}
			if(usl != '' && parseFloat(val) > parseFloat(usl)) {
				document.getElementById(id).style.background   = 'red';
				as = 'NG';
			}
			else{
				as = 'OK';
			}
			
		}
		if(as == 'OK'){
				$('#register-inspection-remark').removeClass('required');
		}		
	}	
	
	function lookup_rad(arg){
		var id = arg.getAttribute('name');
		var cls = arg.getAttribute('class');
		//alert(cls);
		rad_cls1 = cls.indexOf(" ");
		rad_cls = cls.substring(rad_cls1,19);
		var id_val = arg.getAttribute('value');
		var cssa = '.'+rad_cls;
		cssa = cssa.replace(/\s+/g, '');
		s = cssa.substring(0, cssa.length - 1);
		if(id_val == 'OK'){
			$('#register-inspection-remark').removeClass('required');
		}
				
		var ok_all_status;
		if(id_val == 'NG'){
			$(cssa).parent().parent().siblings().addClass("rad_change");
			$('#ok_all').parent('span').removeClass('checked');
			/* for(var i=1;i<=rad;i++){
			} */
		}
		else if(id_val == 'OK'){
			$(cssa).parent().parent().parent().siblings().find( "div" ).siblings().removeClass("rad_change");
			$('#ng_all').parent('span').removeClass('checked');
		}
	} 
	
	function handleClick(cb,rad) {
	  var id = cb.getAttribute('name');
	  var rad_id ;
		if(id == 'ok_all'){			
			//remove checks from NG ALL//
			$('#ng_all').parent('span').removeClass('checked');
			$('#ok_all').parent('span').addClass('checked');
                        //$('#ok_all').attr('readonly','readonly');
                        //$('#ng_all').removeAttr('readonly');
			for(var i=1;i<=rad;i++){
				rad_id = '.ng_radios'+i;
                                id = '#ng_result_'+i;
				// $(rad_id).attr('checked',true);	
				$(rad_id).parent('span').removeClass('checked');
                                $(id).removeAttr("checked");
			}			
			//ADD checks from OK ALL//
			for(var i=1;i<=rad;i++){
				rad_id = '.ok_radios'+i;
                                id = '#ok_result_'+i;
                                $(id).click();
				// $(rad_id).attr('checked',true);				//alert(rad_id);
				$(rad_id).parent('span').addClass('checked');
				$(rad_id).parent('span').parent('div').addClass('radio');
				$(rad_id).parent().parent().parent().siblings().find( "div" ).siblings().removeClass("rad_change");
                                //$(id).attr('checked', 'checked');
                                
			}				 
		}
		if(id == 'ng_all'){
			//remove checks from OK ALL//
			$('#ok_all').parent('span').removeClass('checked');
			$('#ng_all').parent('span').addClass('checked');
                        //$('#ng_all').attr('readonly','readonly');
                        //$('#ok_all').removeAttr('readonly');
			for(var i=1;i<=rad;i++){
				rad_id = '.ok_radios'+i;
                                id = '#ok_result_'+i;
				$(rad_id).parent('span').removeClass('checked');
                                $(id).removeAttr("checked");
				
			}			
			//Apply checks to NG ALL.
			for(var i=1;i<=rad;i++){
				rad_id = '.ng_radios'+i;
                                id = '#ng_result_'+i;
                                $(id).click();
				$(rad_id).parent('span').addClass('checked');
				$(rad_id).parent('span').parent('div').addClass('radio');
				$(rad_id).parent().parent().siblings().addClass("rad_change");
                                //$(id).attr('checked', 'checked');
                                
			}				
		}		
	}
	
	function check_space(id) {
		var colClass = id.className;
		//alert(colClass);exit;
		if(colClass == 'form-control'){
			//alert($(this).html());
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
	
	function isNumberKey(evt)
    {
	  var charCode = (evt.which) ? evt.which : evt.keyCode;
	  //alert(charCode);
	  if ( charCode != 46 && charCode > 31 && charCode != 190 && charCode != 110
		&& (charCode < 48 || charCode > 57) && (charCode < 96 || charCode > 105))
		 return false;

	  return true;
    } 
	
	

</script>
    
    <?php 
	if(!empty($checkpoint['lsl']) || !empty($checkpoint['usl'])) { ?>
        <input type="hidden" value="<?php echo $checkpoint['lsl']; ?>"  id="register-inspection-checkpoint-lsl" />
        <input type="hidden" value="<?php echo $checkpoint['usl']; ?>"  id="register-inspection-checkpoint-usl" />
        
        <input type="hidden" value="value"  id="checkpoint-result-type" />
    <?php } else { ?>
        <input type="hidden" value="radio"  id="checkpoint-result-type" />
    <?php } ?>
    
    <?php $all_values = explode(',', $checkpoint['all_values']); ?>
    <?php $all_results = explode(',', $checkpoint['all_results']); ?>
    
    <table class="table table-condensed">
        <thead>
            <tr>
                <th class="text-center">Sample No</th>
                <th class="text-center">Result</th>
            </tr>
        </thead>
        
        <tbody>
            <?php for($i = 1; $i <= $checkpoint['sampling_qty']; $i++) { ?>
                <tr>
                    <td class="text-center"><?php echo $audit['lot_no'].$i; ?></td>
                    <td>
                        <?php if(!empty($checkpoint['lsl']) || !empty($checkpoint['usl'])) { ?>
                            <div class="row">
                                <div class="col-md-8 col-md-offset-2" style="padding-right:0px;">
                                    <div class="form-group">
                                        <input type="text" class="required form-control input-sm audit_values" id="audit_value_<?php echo $i; ?>" onkeydown="return isNumberKey(event);" name="audit_value_<?php echo $i; ?>" value="<?php echo isset($all_values[$i-1]) ? $all_values[$i-1] : ''; ?>" onblur="lookup(this);">
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <div class="radio-list">
                                            <?php $res = isset($all_results[$i-1]) ? $all_results[$i-1] : ''; ?>
                                            <label class="radio-inline" style="padding-left:0;">
                                                <input class="required ok_radios<?php echo $i; ?> audit_result_<?php echo $i; ?>" type="radio" name="audit_result_<?php echo $i; ?>" id="ok_result_<?php echo $i; ?>" value="OK" <?php if($res == 'OK') { ?> checked="checked" <?php } ?> onclick="lookup_rad(this)" onChange="get_status(this,<?php echo $checkpoint['sampling_qty']; ?>);" ><span class="radOK_<?php echo $i; ?>">OK</span>
                                            </label>
                                            <label class="radio-inline" style="padding-left:0;">
                                                <input class="required ng_radios<?php echo $i; ?> audit_result_<?php echo $i; ?>" type="radio" name="audit_result_<?php echo $i; ?>" id="ng_result_<?php echo $i; ?>" value="NG" <?php if($res == 'NG') { ?> checked="checked" <?php } ?> onclick="lookup_rad(this)" onChange="get_status(this,<?php echo $checkpoint['sampling_qty']; ?>);"><span class="radNG_<?php echo $i; ?>">NG</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } ?>
                    </td>
					
                </tr>
            <?php }
			//print_r($checkpoint);exit;
				if(empty($checkpoint['lsl']) && empty($checkpoint['usl'])) { ?>
            			<tr>
							<td>&nbsp;</td>
							<td>
								<input type="checkbox" name="ok_all" id="ok_all" onclick="handleClick(this,<?php echo $checkpoint['sampling_qty']; ?>);" value="ok_all"><span style="font-size:10px" >ALL OK</span>
								<input type="checkbox" name="ng_all" id="ng_all" onclick="handleClick(this,<?php echo $checkpoint['sampling_qty']; ?>);" value="ng_all" ><span style="font-size:10px" >ALL NG</span>
							</td>
						</tr>
			<?php } ?>
        </tbody>
    </table>
    
    <button type="button" id="ng-confirm" class="btn btn-block red-sunglo" style="display:none;">NG</button>
    <button type="submit" id="na-button" name="result" value="NA" class="btn yellow-gold" style="display:none;">NA</button>
    <button type="submit" id="ng-button" class="btn btn-block red-sunglo" style="display:none;">NG</button>
    <div class="row">
        <div class="col-md-12" style="padding-right: 0;">
            <div class="form-group">
                <label for="remark" class="control-label">Remarks: </label>
                <textarea class="form-control" onblur="return check_space(this);" id="register-inspection-remark" name="remark" placeholder="Remarks" rows="2"><?php echo $checkpoint['remark']; ?></textarea>
                <span class="help-block"></span>                
            </div>
        </div>
    </div>
    
    <div class="form-actions text-center">
        <button type="submit" id="register-inspection-submit" class="btn btn-circle green-meadow">Submit</button>
    </div>
	<style>
		.help-block-error{ font-size: 12px !important;}
		.form-group span{ width:121% !important;}
		.rad_change{color:red}
	</style>