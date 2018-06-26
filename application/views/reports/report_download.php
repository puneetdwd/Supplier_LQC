<?php
$CI = &get_instance();
$CI->load->model('audit_model');
?>

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
            View Report
        </h1>
        
    </div>
    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">

            <?php if($this->session->flashdata('error')) {?>
                <div class="alert alert-danger">
                   <i class="fa fa-times"></i>
                   <?php echo $this->session->flashdata('error');?>
                </div>
            <?php } else if($this->session->flashdata('success')) { ?>
                <div class="alert alert-success">
                    <i class="fa fa-check"></i>
                   <?php echo $this->session->flashdata('success');?>
                </div>
            <?php } ?>
            
            <div class="row">
                    
                <div class="col-md-12">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-reorder"></i>List of Inspections (Total Records - <?php echo $total_records; ?>)
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($audits)) { ?>
                                <p class="text-center">No inspection done yet.</p>
                            <?php } else { ?>
                                <div class="pagination-sec pull-right"></div>
                                <div style="clear:both;"></div>
                                
                                <!--<div class="table-scrollable">-->
                                    <table class="table table-hover table-light">
                                        <thead>
                                            <tr>
                                                <th>Date</th>
                                                <?php if($this->user_type == 'Admin' || $this->user_type == 'LG Inspector'){ ?>
                                                    <th>Supplier</th>
                                                <?php } ?>
                                               
                                                <th>Part Name</th>
                                                <th>Part No</th>
                                                <th>Insp Qty.</th>
                                                <th>OK Qty.</th>
                                                <th>NG Qty.</th>
                                               
                                                <th>Remark</th>
                                                <th>Inspector Name</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($audits as $audit) { 
											//echo "<pre>";print_r($audit);
											?>
                                                <tr>
                                                    <td nowrap><?php echo date('d M y', strtotime($audit['audit_date'])); ?></td>
                                                    <?php if($this->user_type == 'Admin' || $this->user_type == 'LG Inspector') { ?>
                                                        <td><?php echo $audit['supplier_name']; ?></td>
                                                    <?php } ?>
                                                    
                                                    <td><?php echo $audit['part_name']; ?></td>
                                                    <td><?php echo $audit['part_no']; ?></td>
                                                    <td><?php echo $audit['prod_lot_qty']; ?></td>
                                                     <td>
														<?php //echo $audit['ok_count'];
														ECHO $defect_OK = $this->Audit_model->get_count_audit_lqc_defect_by_result($audit['id'], 'OK');
														
														?>
													</td>
                                                    <td>
														<?php //echo $audit['ng_count']; 
															ECHO $defect_NG = $this->Audit_model->get_count_audit_lqc_defect_by_result($audit['id'], 'NG');
														?>
													
													</td>
                                                    <td><?php echo $audit['remark']; ?></td>
                                                    <td><?php echo $audit['inspector_name']; ?></td>
                                                </tr>
                                            <?php } ?>
                                        </tbody>
                                    </table>
                                <!--</div>-->
                                
                                <div class="pagination-sec pull-right"></div>
                                <div style="clear:both;"></div>
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
<?php if(!empty($audits)) { ?>
    <!--script>
        $(window).load(function() {
            $('.check-judgement-button:first').trigger('click');
            
            $('.pagination-sec').bootpag({
                total: <?php echo $total_page; ?>,
                page: <?php echo $page_no; ?>,
                maxVisible: 5,
                leaps: true,
                firstLastUse: true,
                first: 'â†?',
                last: 'â†’',
                wrapClass: 'pagination',
                activeClass: 'active',
                disabledClass: 'disabled',
                nextClass: 'next',
                prevClass: 'prev',
                lastClass: 'last',
                firstClass: 'first'
            }).on("page", function(event, num){
                show_page(num); // or some ajax content loading...
            }); 
        });
    </script-->
<?php } ?>
