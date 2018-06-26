<html>
<body>

<p style="font-size: 16px;"><b>
<h3>Timecheck - Completed Inspection Report</h3><br>
Date: <?php echo $yesterday; ?>
</b></p>
<p style="font-size: 20px;"><b>
</b></p>
<?php if(!empty($plans)) { ?>
	<table class="table table-hover table-light" style='border: 1px solid black;border-collapse: collapse;'>
		<thead>
			<tr style="background-color:#D3D3D3">
				<th style='border: 1px solid black;'>Part</th>
				<th style='border: 1px solid black;'>Date</th>
				<th style='border: 1px solid black;'>From Time</th>
				<th style='border: 1px solid black;'>To Time</th>
				<th style='border: 1px solid black;'>Result</th>
			</tr>
		</thead>
		<tbody>
				<?php foreach($plans as $plan) { ?>
					<tr>
						<td style='border: 1px solid black;'><?php echo $plan['part_name'].' ('.$plan['part_no'].')'; ?></td>
						<td style='border: 1px solid black;'><?php echo date('jS M, Y', strtotime($plan['plan_date'])); ?></td>
						<td style='border: 1px solid black;'><?php echo $plan['from_time']; ?></td>
						<td style='border: 1px solid black;'><?php echo $plan['to_time']; ?></td>
						<td style='border: 1px solid black;'><?php echo $plan['ng_count'] > 0 ? '<span style="color:red">NG</span>' : 'OK'; ?></td>	
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