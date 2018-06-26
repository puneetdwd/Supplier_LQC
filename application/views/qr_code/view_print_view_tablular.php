 <?php
	$CI =&get_instance();
	$CI->load->model('QR_model')
 ?>
 
 <div class="page-content">
 
	<div class="row">
        <div class="col-md-12">

				<div class="portlet">
					<div class="portlet-title">
						<div class="caption">
							<i class="fa fa-reorder"></i> QR Code
						</div>
						<div class="actions">
							<a class="button normals btn-circle" 
							<?php if($printed_qr > 0){	?>
								data-toggle="modal" href="#myModal" 
							<?php } ?>
							onclick="printPage_new('print_qr_all_model',<?php echo "'".$qr['qr_codes']."'"; ?>,<?php echo $qr['part_id']; ?>,<?php echo $qr['supplier_id']; ?>)">
								<i class="fa fa-print"></i> Print All
							</a>
							<button type="button" class="btn default" data-dismiss="modal">X</button>
			
						</div>
					</div>					
                </div>
		 </div>
		<div class="col-md-12" id='print_qr_all_model'>
               
			<div class="portlet-body">
                    <?php if(empty($qr)) { ?>
                        <p class="text-center">No QR Code exists yet.</p>
                    <?php } else { ?>
                        <table width="100%" class="table table-hover table-light"  >
                            <thead>
                                <tr>
                                    <th>SR. No.</th>
                                    <th>QR Serial No.</th>
                                    <th>QR Code</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
								$qr_codes = explode(',',$qr['qr_codes']); 
								// print_r($qr_codes);exit;
								foreach($qr_codes as $qr_code) { 
								?>
                                    <tr>
                                        <td><?php echo $i+1; ?></td>
                                        <td>
											<?php
												$aa = explode('.',$qr_code);
														$qr_str = explode('$',$aa[0]);
														// print_r($qr_str);
											
														echo "<b>Part No.:</b>".$qr_str[0]."</br>";
												
														$date = date_create_from_format("Ymd_His",$qr_str[1]);
														echo "<b>Generated On:</b>".date_format($date,"Y/m/d H:i:s");
														echo "</br>";
												
														echo "<b>Serial No.:</b>".$qr_str[2]."</br>";
														
														$qrr = count($CI->QR_model->get_print_by_qr($aa[0]));
												
											?>
										</td>
                                        <td>
										
											<div style=" float: left;padding:20px;" id="print_qrc<?php echo $i;	?>">
												<img src="<?php echo base_url(); ?>global/tmp/qr_codes/<?php echo $qr_code; ?>" alt="<?php echo $qr_code; ?>" />
												
											</div>
										
										</td>
                                        
                                        
                                        <td nowrap>
											<a class="button normals btn-circle" onclick="printPage_new('print_qrc<?php echo $i;	?>',<?php echo "'".$qr_code."'"; ?>,<?php echo $qr['part_id']; ?>,<?php echo $qr['supplier_id']; ?>);"
											<?php if($qrr > 0){ ?>	data-toggle="modal" href="#myModal"	<?php } ?>
											>
												<i class="fa fa-print"></i> Print
											</a>
                                            
                                        </td>
                                    </tr>
                                <?php 
								$i++;
								} ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    
                </div>

		</div>

        </div>
    </div>
	
	    <div class="modal-footer">
        <div class="form-actions text-center">
            <button type="button" class="btn default" data-dismiss="modal">Close</button>
        </div>
    </div>
	
    </div>
	
	<!-- Modal -->
  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Submit Remark for Reprint</h4>
        </div>
        <div class="modal-body">
          <form action="<?php echo base_url(); ?>qr_code_generate/submit_remark" method="post">
				<input type="hidden" id="print_idds" name="print_idds" ></input>
				<input type="text" required id="print_remark" name="print_remark"	/>
				<input type="submit" id="submit" name="submit"	/>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
	
	