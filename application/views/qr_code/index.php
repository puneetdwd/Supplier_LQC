<div class="page-content">
    <!-- BEGIN PAGE HEADER-->
    <div class="breadcrumbs">
        <h1>
            Manage QR Code
        </h1>
        <ol class="breadcrumb">
            <li>
                <a href="<?php echo base_url(); ?>">Home</a>
            </li>
            <li class="active">Manage QR Code</li>
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
                        <i class="fa fa-reorder"></i> QR Code
                    </div>
                    <div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."qr_code_generate/number_qr_print"; ?>">
                            <i class="fa fa-plus"></i> Add QR Code
                        </a>
                    </div>
					<div class="actions">
                        <a class="button normals btn-circle" href="<?php echo base_url()."qr_code_generate/qr_print_count"; ?>">
                            View Print History
                        </a>
                    </div>
                </div>
                <div class="portlet-body">
                    <?php if(empty($qr_codes)) { ?>
                        <p class="text-center">No QR Code exists yet.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" id="make-data-table">
                            <thead>
                                <tr>
                                    <th>Created On</th>
                                    <th>Supplier</th>
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>View</th>
                                    
                                   
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($qr_codes as $qr_code) { 
								$qrc = explode(',',$qr_code['qr_codes']); 
								?>
                                    <tr>
                                        <td><?php echo $qr_code['created']; ?></td>
                                        <td><?php echo $qr_code['supplier_no'].' - '.$qr_code['supplier_name']; ?></td>
                                        <td><?php echo $qr_code['part_name']; ?></td>
                                        <td><?php echo $qr_code['partno']; ?></td>
                                        <td>    <a class="button small gray" 
                                                href="<?php echo base_url()."qr_code_generate/view_print_view_tablular/".$qr_code['id'];?>" data-target="#qr-modal" data-toggle="modal" >
                                                <i class="fa fa-view"></i> View All(<?php echo count($qrc);	?>)
                                            </a> 
										</td>
                                        <!--<td nowrap>
											<a class="button small gray" onclick="return print_all_qr(<?php echo $qr_code['id'];	?>);" >
                                                <i class="fa fa-print"></i> Print All
                                            </a>
                                            
											
											href="<?php echo base_url()."qr_code_generate/view_print_view/".$qr_code['id'];?>"
											
											a class="btn btn-xs btn-outline sbold red-thunderbird" data-confirm="Are you sure you want to this Phone Number?" href="<?php echo base_url()."phones/delete_phone_number/".$qr_code['id'];?>">
                                                <i class="fa fa-trash-o"></i> Delete
                                            </a
                                        </td>-->
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


