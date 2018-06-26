<html>
<body>

<p style="font-size: 16px;"><b>
<h3>Fool-Proof - Completed Inspection Report</h3><br>
Date: <?php echo $yesterday; ?>
</b></p>
<p style="font-size: 20px;"><b>
</b></p>
                
                    <?php if(empty($foolproofs)) { ?>
                        <p class="text-center">No Fool-Proof Report.</p>
                    <?php } else { ?>
                        <form method="post">
                            <div class="table-responsive">
                                <table class="table table-hover table-light" style='border: 1px solid black;border-collapse: collapse;'>
                                    <thead>
                                        <tr style='background-color:#D3D3D3'>
                                            <th style='border: 1px solid black;'>Sr.No.</th>
                                            <th style='border: 1px solid black;'>Supplier</th>
                                            <th style='border: 1px solid black;'>Date</th>
                                            <th style='border: 1px solid black;'>Stage</th>
                                            <th style='border: 1px solid black;'>Sub Stage</th>
                                            <th style='border: 1px solid black;'>Major Control Parameter</th>
                                            <th style='border: 1px solid black;'>LSL</th>
                                            <th style='border: 1px solid black;'>USL</th>
                                            <th style='border: 1px solid black;'>TGT</th>
                                            <th style='border: 1px solid black;'>Unit</th>
                                            <th style='border: 1px solid black;'>Measuring Equipment</th>
                                            <th style='border: 1px solid black;'>Image</th>
                                            <th style='border: 1px solid black;'>Input Value</th>
                                            <th style='border: 1px solid black;'>Result</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $i=0; foreach($foolproofs as $foolproof) { $i++; ?>
                                            <tr>
                                                <td style='border: 1px solid black;'><?php echo $i; ?></td>
                                                <td style='border: 1px solid black;'><?php echo $foolproof['supplier_name']; ?></td>
                                                <td style='border: 1px solid black;'><?php if(!empty($foolproof['created'])){ echo date('jS M, Y', strtotime($foolproof['created'])); } else { echo 'NA'; } ?></td>
                                                <td  style='border: 1px solid black;'><?php echo $foolproof['stage']; ?></td>
                                                <td style='border: 1px solid black;'><?php echo $foolproof['sub_stage']; ?></td>
                                                <td style='border: 1px solid black;'><?php echo $foolproof['major_control_parameters']; ?></td>
                                                <td style='border: 1px solid black;'><?php echo $foolproof['lsl']; ?></td>
                                                <td style='border: 1px solid black;'><?php echo $foolproof['tgt']; ?></td>
                                                <td style='border: 1px solid black;'><?php echo $foolproof['usl']; ?></td>
                                                <td style='border: 1px solid black;'><?php echo $foolproof['unit']; ?></td>
                                                <td style='border: 1px solid black;'><?php echo $foolproof['measuring_equipment']; ?></td>
                                                <td style='border: 1px solid black;'>
                                                    <?php if($foolproof['image'] == NULL){ echo 'NA'; }else{ ?>
                                                    <img src="<?php echo base_url().'assets/foolproof_captured/'.$foolproof['image']; ?>" 
                                                         height="70" width="100" alt="<?php $foolproof['image']; ?>" />
                                                    <?php } ?>
                                                </td>
                                                <?php 
                                                    if($foolproof['lsl'] == NULL && $foolproof['usl'] == NULL){
                                                        $value = 'NA';
                                                    }
                                                    else if($foolproof['lsl'] == '' && $foolproof['usl'] == ''){
                                                        $value = $foolproof['all_results'];
                                                    }else{
                                                        $value = $foolproof['all_values'];
                                                    }
                                                ?>
                                                <td style='border: 1px solid black;'><?php echo $value; ?></td>
                                                <td style='border: 1px solid black;'><?php if($foolproof['result'] == NULL){ echo 'NA'; }else{ echo $foolproof['result']; } ?></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                        </form>
                    <?php } ?>
            <br>
<br>
<p>
	Regards,<br>
	SQIM Administrator
	<br>
	<br>
	<i><b>Note:</b>&nbsp;This is a system generated mail. Please do not reply.</i>
</p>
</body>
</html>