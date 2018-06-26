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
        <div class="col-md-offset-2 col-md-8">
            <div class="portlet light bordered">
                
                <div class="portlet-body">
                    <?php if(empty($parts)) { ?>
                        <p class="text-center">No Product Part exists yet.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Part Number</th>
                                    <th>Part Name</th>
                                    <th>Is deleted</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($parts as $part) { ?>
                                    <tr>
                                        <td><?php echo $part['code']; ?></td>
                                        <td><?php echo $part['name']; ?></td>
                                        <td>&nbsp;</td>
                                        
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    
                </div>
            </div>

        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>