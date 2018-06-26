<div class="page-content">
    <div class="breadcrumbs">
        <h1>
            <?php echo $this->session->userdata('name'); ?>
            <small>Welcome to your dashboard</small>
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="#">Home</a>
            </li>
            <li class="active">Dashboard</li>
        </ol>
        
    </div>
        
    <?php if($this->session->flashdata('error')) {?>
        <div class="alert alert-danger">
           <i class="icon-remove"></i>
           <?php echo $this->session->flashdata('error');?>
        </div>
    <?php } else if($this->session->flashdata('success')) { ?>
        <div class="alert alert-success">
            <i class="icon-ok"></i>
           <?php echo $this->session->flashdata('success');?>
        </div>
    <?php } ?>
    
    <div class="row">
        <div class="col-md-12">
            <div class="mt-element-ribbon bg-grey-steel" id="dashboard-on-going-insp">
                
                <div class="ribbon ribbon-clip ribbon-color-danger uppercase">
                    <div class="ribbon-sub ribbon-clip"></div> Scheduled LQC Inspection
                </div>
                
                <div class="ribbon-content">
                    <table class="table table-hover table-light dashboard-on-going-insp-table" id="make-data-table" style="background-color:inherit;">
                        <thead>
                            <tr>
                                <th>Part</th>
                                <th>Scheduled</th>
                                <!--th class="text-center">Status</th-->
                                <th class="no_sort" style="width:150px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($plans as $plan) { ?>
                                <tr>
                                    <td><?php echo $plan['part_name'].' ('.$plan['part_no'].')'; ?></td>
                                    <td><?php echo $plan['plan_date']; ?></td>
                                    <!--td class="text-center">
                                        <?php if($plan['plan_status'] == 'started') { ?>
                                            <span class="label label-warning label-sm"> 
                                                <i class="fa fa-play"></i> Started
                                            </span>
                                        <?php } else if($plan['plan_status'] == 'completed') { ?>
                                            <span class="label label-success label-sm"> 
                                                <i class="fa fa-check"></i> Completed
                                            </span>
                                        <?php } else {  ?>
                                            <span class="label label-danger label-sm"> 
                                                <i class="fa fa-ban"></i> Not Started
                                            </span>
                                        <?php } ?>
                                    </td-->
                                    <!--td nowrap class="text-center">
                                        <?php if($plan['plan_status'] == 'started' || $plan['plan_status'] == 'completed') { ?>
                                            <a class="button small gray" href="<?php echo base_url()."timecheck/view/".$plan['id']; ?>">
                                                <i class="fa fa-eye"></i> View
                                            </a>
                                        <?php } ?>
                                    </td-->
									<td nowrap class="text-center">
                                        <?php if($plan['plan_status'] != 'started') { ?>
                                            <a class="button small gray" href="<?php echo base_url()."register_inspection_lqc"; ?>">
                                                Start
                                            </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
   
    
    
</div>