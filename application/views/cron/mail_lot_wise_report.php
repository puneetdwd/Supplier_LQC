<html>
<body>
<p style="font-size: 16px;"><b>
Timecheck - Completed Inspection Report<br>
Date: <?php echo $yesterday; ?>
</b></p>
<p style="font-size: 20px;"><b>
</b></p>
<?php if(!empty($audits)) { ?>
	<table style='border: 1px solid black;border-collapse: collapse;'>
		<thead>
			<tr style="background-color:#D3D3D3;">
				<th style='border: 1px solid black;' rowspan="2" class="merged-cell text-center">Last Inspect Date</th>
				<th style='border: 1px solid black;'  rowspan="2" class="merged-cell text-center">Product</th>
				<th style='border: 1px solid black;'  rowspan="2" class="merged-cell text-center">Supplier</th>
				<th style='border: 1px solid black;'  rowspan="2" class="merged-cell text-center">Part</th>
				<th  style='border: 1px solid black;' colspan="3" class="merged-cell text-center">No. Of Lots</th>
			</tr>
			<tr  style="background-color:#D3D3D3;">
				<th  style='border: 1px solid black;' class="text-center">Inspected</th>
				<th style='border: 1px solid black;'  class="text-center">OK</th>
				<th  style='border: 1px solid black;' class="text-center">NG</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($audits as $audit) {
				if($audit['ng_lots'] > 0)
					$bg = 'background-color:red;';
			?>
				<tr>
					<td  style='border: 1px solid black;' nowrap><?php echo date('jS M, y', strtotime($audit['audit_date'])); ?></td>
					<td  style='border: 1px solid black;' ><?php echo $audit['product_name']; ?></td>
					<td style='border: 1px solid black;' ><?php echo $audit['supplier_no'].' - '.$audit['supplier_name']; ?></td>
					<td style='border: 1px solid black;' ><?php echo $audit['part_no'].' - '.$audit['part_name']; ?></td>
					<td style='border: 1px solid black;'  class="text-center"><?php echo $audit['no_of_lots']; ?></td>
					<td  style='border: 1px solid black;' class="text-center"><?php echo $audit['ok_lots']; ?></td>
					<?php
						if($audit['ng_lots'] > 0) { ?>
							<td  style='border: 1px solid black;background-color:red' class="text-center">
								<?php echo $audit['ng_lots']; ?>
							</td>
					<?php } else {?>
							<td  style='border: 1px solid black;' class="text-center">
								<?php echo $audit['ng_lots']; ?>
							</td>					
					<?php } ?>
				</tr>
			<?php } ?>
		</tbody>
	</table>
<?php }else{ ?>
<p>
No Timechecks has been done yesterday.
</p>
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
<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
table{
    margin: 0% 0% 0% 5%;
    width: 90%;
th{
		text-align: center;
	    font-size: 30px !important;
		padding:10px
}
tr{
	    padding: 0 auto;
}
</style>
</body>
</html>
