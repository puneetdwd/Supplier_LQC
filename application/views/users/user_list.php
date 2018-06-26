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
            User List
        </h1>
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <div class="portlet light bordered">
                <div class="portlet-body">
                    
                        <table>
                            <thead>
                                <tr>
                                    <th>First Name</th>
                                    <th>Last Name</th>
                                    <th>Username</th>
                                    <th class="no_sort">User Type</th>
                                    <?php if(!$this->product_id) { ?>
                                        <th>Product</th>
                                    <?php } ?>
                                    <th class="no_sort">Active</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($users as $user) { ?>
                                    <tr>
                                        <td><?php echo $user['first_name']; ?></td>
                                        <td><?php echo $user['last_name']; ?></td>
                                        <td><?php echo $user['username']; ?></td>
                                        <td><?php echo $user['user_type'] ?></td>
                                        <?php if(!$this->product_id) { ?>
                                            <td><?php echo $user['product_name']; ?></td>
                                        <?php } ?>
                                        <td><?php echo ($user['is_active'] ? 'Yes': 'No'); ?></td>
                                       
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