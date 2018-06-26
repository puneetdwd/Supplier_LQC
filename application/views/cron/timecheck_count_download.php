<html>
	<body>

	<div>
		<h1 >
            Supplier Timecheck Counts Report 
        </h1>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div>
        <div>
            <div>
                <div>
                    <div>
                        List of Supplier Timecheck Count
                    </div>                   
                </div>
                <div>
                    <?php if(!empty($plans)){ ?>
                        <table style='border: 1px solid black;border-collapse: collapse;'>
                            <thead>
                                <tr style='background-color:"#D3D3D3"'>
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
                    <?php }
					else { ?>
                        No Supplier has done timecheck yesterday.
                    <?php } ?>
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>

	<style>
	table {
		border: 1px solid black;
		border-collapse: collapse;
	}
	table, th, td {
		border: 1px solid black;
	}
	</style>
	</body>
</html>