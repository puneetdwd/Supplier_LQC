<style type="text/css" media="print">
  @page { 
      /*size: landscape;*/
      size: A4;
      margin: 0;
  }
</style>
<div class="page-content">
    
    <?php if(!isset($download)) { ?>
        <div class="breadcrumbs">
            <h1>
                Part Inspection Report 
            </h1>
            <ol class="breadcrumb">
                <li>
                    <a href="<?php echo base_url(); ?>">Home</a>
                </li>
                <li class="active">Part Inspection Report</li>
            </ol>
            
        </div>
        <div class="caption">
            <!--<i class="fa fa-reorder"></i>List of Users-->
        </div>
        <div class="actions">
            <a class="button normals btn-circle" onclick="printPage('part_insp_table');" href="javascript:void(0);">
                <i class="fa fa-print"></i> Print
            </a>
        </div>
    <?php } ?>

    <!-- BEGIN PAGE CONTENT-->
    <div class="row" id="part_insp_table">
        <div class="col-md-12">
            
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered" border="1" style="border-collapse: collapse;">
                           
                            <tr>
                                <td colspan="10">
                                    <span style="font-size: 24px; font-weight: bold;">Part Inspection Report</span>
                                </td >
                                <td colspan="10" class="text-center" >Vendor Inspector <br /> SIGN</td colspan="5">
                                <td colspan="10" class="text-center" >LG Inspector <br /> SIGN</td>
                            </tr>
                            <tr>
                                <td colspan="5">
                                    Part Name: <?php echo $audit['part_name']; ?>
                                </td>
                                <td colspan="5">
                                    Part No: <?php echo $audit['part_no']; ?>
                                </td>
                                <td colspan="5">
                                    Lot Size: <?php echo $audit['prod_lot_qty']; ?>
                                </td>
                                <td colspan="10">
                                    Date: <?php echo $audit['register_datetime']; ?>
                                </td>
                                
                            </tr>
                            <tr>
                                <td colspan="5">
                                    Supplier Name: <?php echo $audit['supplier_name']; ?>
                                </td>
                                <td colspan="4">
                                    LQC Lot No: <?php echo $audit['lot_no']; ?>
                                </td>
                                <td colspan="11">
                                    Supplier Inspector: <?php echo $audit['inspector_name']; ?>
                                </td>
                                <td class="text-center" colspan="20">Deviation / Segregation</td>
                            </tr>
                            <tr>
                                <th>Sr. No.</th>
                                <th colspan="3">QR Code</th>
                                <th colspan="10">Defect Code Occured</th>
                                <th colspan="2">Result</th>
                                <th colspan="5" style="min-width:150px;">Retest Remark</th>
                                <th colspan="12" style="min-width:150px;">Remark</th>
                            </tr>
                            <tbody>
                                <?php foreach($lqc_insp as $ind => $li) { 
											
                                            if($li['result'] == 'NG'){
                                                $bg = 'background-color:red;';
                                            }else{
												$bg = '';
											}
								
										?>
                                    <tr>
                                        <td rowspan="2"><?php echo $ind+1;?></td>
                                        <td rowspan="2" colspan="3"><?php echo $li['serial_no'];?></td>
                                        <td rowspan="2" colspan="10"><?php echo $li['defect_occured'];?></td>
                                        <td rowspan="2" colspan="2" style="<?php echo $bg; ?>"><?php echo $li['result'];?></td>
                                        <td rowspan="2" colspan="5" style="text-align:center;">
											<?php 
												if(isset($li['retest_remark']))
													echo $li['retest_remark'];
												else "";
											?>
										</td>
                                        
                                        <td rowspan="2" colspan="3" style="text-align:center;"><?php echo $li['remark'];?></td>
										<tr>
											<td colspan='20'>
											Remarks : <?php echo implode(', ',array_filter(array_column($li, 'retest_remark'))); ?>
											</td>
										</tr>
                                    </tr>
								<?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- END PAGE CONTENT-->
</div>