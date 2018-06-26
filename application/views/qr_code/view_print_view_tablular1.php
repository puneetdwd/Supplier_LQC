 <div class="page-content">
 
	<div class="row">
        
		<div class="col-md-12">
               
			<div class="portlet-body">
                    <?php if(empty($qr)) { ?>
                        <p class="text-center">No QR Code exists yet.</p>
                    <?php } else { ?>
                        <table border=1 style="border-collapse: collapse;" width="100%" class="table table-hover table-light"  >
                            
                            <tbody>
                                <?php $i = 0;
								$qr_codes = explode(',',$qr['qr_codes']); 
								 // print_r($qr_codes);exit;
								foreach($qr_codes as $qr_code) { 
								?>
                                    <tr>
                                        <td class="text-center"><?php echo $i+1; ?></td>
                                        <td class="text-center"><?php $aa = explode('.',$qr_code);echo $aa[0]; ?></td>
                                        <td class="text-center">
										
											<div>
												<img src="<?php echo base_url(); ?>global/tmp/qr_codes/<?php echo $qr_code; ?>" alt="<?php echo $qr_code; ?>" />
												
											</div>
										
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
	
