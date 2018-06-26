<style>
	table {
		border-collapse: collapse;
	}

	table, td, th {
		border: 1px solid black;
	}
</style>

<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Product List
        </h1>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
			<div class="portlet light bordered">
                <div class="portlet-body">
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Org ID</th>
                                    <th>Org Code</th>
                                    <th>Product Code</th>
                                    <th>Product Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($products as $product) { ?>
                                    <tr>
                                        <td><?php echo $product['org_id']; ?></td>
                                        <td><?php echo $product['org_name']; ?></td>
                                        <td><?php echo $product['code']; ?></td>
                                        <td><?php echo $product['name']; ?></td>                                        
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                </div>
            </div>
        </div>
    </div>
</div>