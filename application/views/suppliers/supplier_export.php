<style>
table {
    border-collapse: collapse;
}

table, td, th {
    border: 1px solid black;
}
</style>

<div class="page-content">
    <div class="row">
        <div class="col-md-12">
			<div class="portlet light bordered">
                
                <div class="portlet-body">
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Supplier Code</th>
                                    <th>Supplier Name</th>
                                    <th>Supplier Email</th>    
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($suppliers as $supplier) { ?>
                                    <tr>
                                        <td><?php echo $supplier['supplier_no']; ?></td>
                                        <td><?php echo $supplier['name']; ?></td>
                                        <td><?php echo $supplier['email']; ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                   
                </div>
            </div>

        </div>
    </div>
</div>