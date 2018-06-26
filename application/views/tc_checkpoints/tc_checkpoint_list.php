<style>
    .form-inline .select2-container--bootstrap{
        width: 300px !important;
    }
		
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
    
</style>

<div class="page-content">
    
    
    <div class="row" style="margin-top:15px;">
        
        <div class="col-md-12">
			<div class="portlet-body">
                    <div class="table-responsive">
                            <table class="table table-hover table-light">
                                <thead>
                                    <tr>
                                        <th>Supplier No</th>
                                        <th>Supplier Name</th>
                                        <th>Product</th>
                                        <th class="text-center">Part</th>
                                        <th>Child Part No.</th>
                                        <th>Child Part Name</th>
                                        <th>Mold</th>
                                        <th>Stage</th>
										<th>Char.</th>
                                        <th>Insp. Item</th>
                                        <th>Spec.</th>
                                        <th>LSL</th>
                                        <th>TGT</th>
                                        <th>USL</th>
										<th>UOM</th>
                                        <th>Frequency</th>
                                        <th>Sample Qty</th>
                                        <th>Instrument</th>
                                        <th>Use Flag</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($checkpoints as $checkpoint) { ?>

		
                                        <tr class="checkpoint-<?php echo $checkpoint['id']; ?>">
                                            <td><?php echo $checkpoint['supplier_no']; ?></td>
                                            <td><?php echo $checkpoint['supplier_name']; ?></td>
                                            <td><?php echo $proc_code; ?></td>
                                            <td><?php echo $checkpoint['part_no']; ?></td>
                                            <td><?php echo $checkpoint['child_part_no']; ?></td>
                                            <td><?php echo $checkpoint['child_part_name']; ?></td>
											<td><?php echo $checkpoint['mold_no']; ?></td>
                                            <td><?php echo $checkpoint['stage']; ?></td>
											<td><?php echo $checkpoint['measure_type']; ?></td>
											<td><?php echo $checkpoint['insp_item']; ?></td>
                                            <td><?php echo $checkpoint['spec']; ?></td>
                                            <td nowrap>
                                                <?php echo ($checkpoint['lsl']) ? $checkpoint['lsl'].' '.$checkpoint['unit'] : ''; ?>
                                            </td>
                                           
                                            <td nowrap>
                                                <?php echo ($checkpoint['tgt']) ? $checkpoint['tgt'].' '.$checkpoint['unit'] : ''; ?>
                                            </td>
											 <td nowrap>
                                                <?php echo ($checkpoint['usl']) ? $checkpoint['usl'].' '.$checkpoint['unit'] : ''; ?>
                                            </td>
                                            <td><?php echo $checkpoint['unit']; ?></td>
                                            <td><?php echo $checkpoint['frequency'].' hours'; ?></td>
                                            <td><?php echo $checkpoint['sample_qty']; ?></td>
											 <td><?php echo $checkpoint['instrument']; ?></td>
											
                                            <td><?php echo "Y"; ?></td>
                                            
                                            
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                       
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
