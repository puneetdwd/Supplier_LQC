<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            View QR Code History
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">QR Code History</li>
        </ol>
        
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

            <div class="portlet light bordered">
                <div class="portlet-title">
                    <div class="caption">
                        <i class="fa fa-reorder"></i> View QR Code Print History
                    </div>
                    
                </div>
                <div class="portlet-body">
                    <?php if(empty($qr_prints)) { ?>
                        <p class="text-center">No QR Code exists yet.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
			
                            <thead>
                                <tr>
                                    <th>Print Date</th>
                                    <th>Printed By</th>
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>QR Serial No</th>
                                    <th>Print Count</th>
                                    <th>Remark</th>
                                    
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($qr_prints as $qr_code) { 
								 
						
								?>
                                    <tr>
                                        <td>
											<?php 
												echo implode(',',array_unique(explode(',',$qr_code['print_date'])));
											?>
										</td>
                                        <td>
											<?php 
												echo implode(',',array_unique(explode(',',$qr_code['printed_by'])));
											?>
										</td>
                                        <td><?php echo $qr_code['part_name']; ?></td>
                                        <td><?php echo $qr_code['partno']; ?></td>
                                        <td><?php echo $qr_code['qr_code']; ?></td>
                                        <td><?php echo $qr_code['cnt_qr_code']; ?></td>
                                        <td><?php echo $qr_code['reprint_remark']; ?></td>
                                        
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


<div class="modal fade bs-modal-lg modal-scroll" id="qr-modal" role="basic" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <img src="../assets/global/img/loading-spinner-grey.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;Loading... </span>
            </div>
        </div>
    </div>
</div>


<script>
function print_all_qr(qr_id){
	// alert(qr_id);
	$.ajax({
			type: 'POST',
			url: 'qr_code_generate/view_print_view_direct/'+qr_id,
			data: {   },
			success: function(resp) {
				// console.log(resp);
				var html="<html>";
				html+= resp;
				html+="</html>";
				// alert(html);
				var printWin = window.open('','','left=0,top=0,width=500,height=500,toolbar=0,scrollbars=0,status =0');
				printWin.document.write(html);
				printWin.document.close();
				printWin.focus();
				printWin.print();
				printWin.close();
			}
				
		});
}

</script>


