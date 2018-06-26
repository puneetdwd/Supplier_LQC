<html>
<body>
<p style="font-size: 16px;"><b>
<h3>Part Inspection - Completed Inspection Report</h3></br>
Date: <?php echo $yesterday; ?>
</b></p>
<p style="font-size: 20px;"><b>
</b></p>
<?php
$CI = &get_instance();
$CI->load->model('audit_model');
?>
<?php if(!empty($audits)) { ?>
	<table class="table table-hover table-light" style='border: 1px solid black;border-collapse: collapse;'>
		<thead>
			<tr style="background-color:#D3D3D3;">
				<th style='border: 1px solid black;'>Date</th>
				<th style='border: 1px solid black;'>Supplier</th>
				<th style='border: 1px solid black;'>Lot No</th>
				<th style='border: 1px solid black;'>Part Name</th>
				<th style='border: 1px solid black;'>Part No</th>
				<th style='border: 1px solid black;'>Insp Qty.</th>
				<th style='border: 1px solid black;'>Judgment</th>
				<th style='border: 1px solid black;'>Inspector Name</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($audits as $audit) { ?>
				<tr>
					<td style='border: 1px solid black;'><?php echo date('jS M, y', strtotime($audit['audit_date'])); ?></td>
					<td style='border: 1px solid black;'><?php echo $audit['supplier_name']; ?></td>
					<td style='border: 1px solid black;'><?php echo $audit['lot_no']; ?></td>
					<td style='border: 1px solid black;'><?php echo $audit['part_name']; ?></td>
					<td style='border: 1px solid black;'><?php echo $audit['part_no']; ?></td>
					<td style='border: 1px solid black;'><?php echo $audit['prod_lot_qty']; ?></td>
					<td class="judgement-col" style='border: 1px solid black;'>
						<?php
							$res = $CI->audit_model->get_judgement_result($audit['lot_no']);
							if($res[0]['ng_count'] > 0)
								echo '<span style="color:red">NG</span>';
							else
								echo 'OK';
						?>
					</td>
					<td style='border: 1px solid black;'><?php echo $audit['inspector_name']; ?></td>				   
				</tr>
			<?php } ?>
		</tbody>		
	</table>	
	
<?php }else{ ?>
<p>
No Part Inspection has been done yesterday.
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