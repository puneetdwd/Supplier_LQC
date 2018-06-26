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
            View Timecheck Report
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
                                <i class="fa fa-reorder"></i>List of Timechecks (Total Records - <?php echo $total_records; ?>)
                            </div>
                        </div>
                        <div class="portlet-body">
                            <?php if(empty($plans)) { ?>
                                <p class="text-center">No plans done yet.</p>
                            <?php } else { ?>
                                <div class="pagination-sec pull-right"></div>
                                <div style="clear:both;"></div>
                                
                                <!--<div class="table-scrollable">-->
                                    <table class="table table-hover table-light">
                                        <thead>
                                            <tr>
                                                <th>Part</th>
                                                <th>Date</th>
                                                <th>From Time</th>
                                                <th>To Time</th>
                                                <th>Result</th>
                                                <!--<th>Progress</th>-->
                                               </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach($plans as $plan) {
											/* if($plan['ng_lots'] > 0)
												$bg = 'background-color:red;'; */
											?>
                                                <tr>
                                                    <td><?php echo $plan['part_name'].' ('.$plan['part_no'].')'; ?></td>
                                                    <td><?php echo date('d M Y', strtotime($plan['plan_date'])); ?></td>
                                                    <td><?php echo $plan['from_time']; ?></td>
                                                    <td><?php echo $plan['to_time']; ?></td>
                                                    <td><?php echo $plan['ng_count'] > 0 ? '<span style="color:red">NG</span>' : 'OK'; ?></td>
                                                   <!-- <td>
                                                        <?php
                                                            $freq_results = explode(',', $plan['freq_results']);
                                                            $freq_indexs = explode(',', $plan['freq_indexs']);
                                                            
                                                            $fresults = array();
                                                            foreach($freq_indexs as $k => $idx) {
                                                                $fresults[$idx] = $freq_results[$k];
                                                            }
                                                        ?>

                                                        <div class="color-progress-box">
                                                            <?php for($i = 1;$i <= $plan['total_frequencies']; $i++) { ?>
                                                                <?php 
                                                                    $cls = '';
                                                                    if(!isset($fresults[$i])) {
                                                                        $cls = 'empty-item';
                                                                    } else if($fresults[$i] == 'NG') {
                                                                        $cls = 'ng-item';
                                                                    } else {
                                                                        $cls = 'ok-item';
                                                                    }
                                                                ?>
                                                                <span class="progress-item <?php echo $cls; ?>"></span>
                                                            <?php } ?>
                                                            
                                                        </div>
                                                    </td>
                                                    <td nowrap>
                                                        
                                                        
                                                        <a class="button small gray" target="_blank" 
                                                            href="<?php echo base_url()."timecheck/view/".$plan['id'];?>">
                                                            <i class="fa fa-edit"></i> View
                                                        </a>
                                                        <a class="button small gray" target="_blank" 
                                                            href="<?php echo base_url()."timecheck/view/".$plan['id']."?download=true";?>">
                                                            <i class="fa fa-edit"></i> Download
                                                        </a>
                                                        
                                                    </td>-->
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
    </div>
    <!-- END PAGE CONTENT-->
</div>
</div>
