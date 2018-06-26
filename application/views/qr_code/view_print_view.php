 <div class="page-content">
 <div class="breadcrumbs">
        <h1>
            Print QR Code
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li> 
			<li>
                <a href="<?php echo base_url(); ?>qr_code_generate">QR Codes </a>
            </li>
            <li class="active" >Manage QR Code</li>
        </ol>
    </div>
	
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

            <div class="portlet">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> QR Code
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" onclick="printPage('print_qr_all');">
                            <i class="fa fa-print"></i> Print All
                        </a>
                    </div>
                </div>
		 </div>
		<div class="portlet-body" id='print_qr_all'>
                    <?php if(empty($qr)) { ?>
                        <p class="text-center">No QR Code exists yet.</p>
                    <?php } else { ?>
                        <table  width="100%" class="table table-hover table-light"  >
                            <thead>
                                <tr>
                                    <th>SR. No.</th>
                                    <th>QR Serial No.</th>
                                    <th>QR Code</th>
                                    <th> </th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0;
								$qr_codes = explode(',',$qr['qr_codes']); 
								// print_r($qr_codes);exit;
								foreach($qr_codes as $qr_code) { 
								?>
                                    <tr >
                                        <td><?php echo $i+1; ?></td>
                                        <td><?php $aa = explode('.',$qr_code);echo $aa[0]; ?></td>
                                        <td  >
										
											<div style=" float: left;padding:20px;" id="print_qrc<?php echo $i;	?>">
												<img src="<?php echo base_url(); ?>global/tmp/qr_codes/<?php echo $qr_code; ?>" alt="<?php echo $qr_code; ?>" />
												
											</div>
										
										</td>
                                        
                                        
                                        <td nowrap>
											<a class="button normals btn-circle" onclick="printPage('print_qrc<?php echo $i;	?>');">
												<i class="fa fa-print"></i> Print
											</a>
                                            <!--a class="btn btn-xs btn-outline sbold red-thunderbird" data-confirm="Are you sure you want to this Phone Number?" href="<?php echo base_url()."phones/delete_phone_number/".$qr_code['id'];?>">
                                                <i class="fa fa-trash-o"></i> Delete
                                            </a-->
                                        </td>
                                    </tr>
                                <?php $i++;	} ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    
                </div>
        </div>
    </div>
	
    </div>