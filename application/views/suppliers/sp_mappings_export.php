<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
</style>

<table class="table table-hover table-light">
	<thead>
		<tr>
			<th> SR. </th>
			<th>Part Number</th>
			<th>Part Name</th>
			<th>Supplier Code</th>
			<th>Supplier Name</th>
		</tr>
	</thead>
	<tbody>
		<?php $i=1;
		foreach($sp_mappings as $sp_mapping) { ?>
			<tr>
				<td><?php echo $i;$i++; ?></td>
				<td><?php echo $sp_mapping['part_code']; ?></td>
				<td><?php echo $sp_mapping['part_name']; ?></td>
				<td><?php echo $sp_mapping['supplier_no']; ?></td>
				<td><?php echo $sp_mapping['supplier_name']; ?></td>
				
			</tr>
		<?php } ?>
	</tbody>
</table>
