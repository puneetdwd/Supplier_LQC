<html>
<body>
<p style="font-size: 16px;"><h3>
Supplier Timecheck Counts - Completed Inspection Report<br>
Date: <?php echo $yesterday; ?>
</h3></p>
<p style="font-size: 20px;"><b>
</b></p>
<?php if(!empty($plans)) { ?>
	<table class="table table-hover table-light" style='border-collapse: collapse;'>
		<thead>
			<tr style="background-color:#D3D3D3;">
				<th style='border: 1px solid black;'>Supplier Id</th>
                <th style='border: 1px solid black;'>Supplier Name</th>
                <th style='border: 1px solid black;'>Counts</th>
			</tr>
		</thead>
		<tbody>
				<?php foreach($plans as $plan) { ?>
					<tr>
						<td style='border: 1px solid black;'><?php echo $plan['supplier_id']; ?></td>
						<td style='border: 1px solid black;'><?php echo $plan['name']; ?></td>
						<td style='border: 1px solid black;'><?php echo $plan['cnt']; ?></td>
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
</body>
</html>