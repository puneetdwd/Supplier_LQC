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
                <div class="col-md-12" style="padding:0;">
                    <div class="portlet light bordered">
                        <div class="portlet-title">
                            <div class="caption">
                                <i class="fa fa-reorder"></i>List of Inspections (Total Records - <?php echo $total_records; ?>)
                            </div>
                            <div class="actions">
                                <!--<a class="button normals btn-circle" onclick="printPage('part_insp_table');" href="javascript:void(0);">
                                    <i class="fa fa-print"></i> Print
                                </a>-->
                                <?php $supplier_id = ($this->input->post('supplier_id'))?$this->input->post('supplier_id'):$this->supplier_id; ?>
                                <?php if($this->input->post()){ ?>
                                <!--a class="button normals btn-circle" href="<?php echo base_url()."reports/lot_wise_report/".$this->input->post('date')."/".$supplier_id;?>">
                                    <i class="fa fa-print"></i> Download
                                </a>-->
								<a class="button normals btn-circle" href="<?php echo base_url().'reports/lot_wise_report_download/lot_wise_report_download'; ?>">
									<i class="fa fa-download"></i> Excel Export
								</a>
                                <?php } ?>
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
                                                <th rowspan="2" class="merged-cell text-center">Last Inspect Date</th>
                                                <th rowspan="2" class="merged-cell text-center">Product</th>
                                                <?php if($this->user_type == 'Admin' || $this->user_type == 'LG Inspector'){ ?>
                                                    <th rowspan="2" class="merged-cell text-center">Supplier</th>
                                                <?php } ?>
                                                <th rowspan="2" class="merged-cell text-center">Part</th>
                                                <th colspan="3" class="merged-cell text-center">No. Of Lots</th>
                                            </tr>
                                            <tr>
                                                <th class="text-center">Inspected</th>
                                                <th class="text-center">OK</th>
                                                <th class="text-center">NG</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
											$bg = '';
											foreach($audits as $audit) { 
											//echo "<pre>";print_r($audits);
											if(!empty($audit['ng_lots']))
												$bg = 'background-color:red';
											?>
                                                <tr>
                                                    <td nowrap><?php echo date('d M y', strtotime($audit['audit_date'])); ?></td>
                                                    <td><?php echo $audit['product_name']; ?></td>
                                                    <?php if($this->user_type == 'Admin' || $this->user_type == 'LG Inspector'){ ?>
                                                        <td><?php echo $audit['supplier_no'].' - '.$audit['supplier_name']; ?></td>
                                                    <?php } ?>
                                                    <td><?php echo $audit['part_no'].' - '.$audit['part_name']; ?></td>
                                                    <td class="text-center"><?php echo $audit['no_of_lots']; ?></td>
                                                    <td class="text-center"><?php echo $audit['ok_lots']; ?></td>
                                                    <td class="text-center" style="<?php echo $bg; ?>">
														<?php echo $audit['ng_lots']; ?>
													</td>
                                                </tr>
                                            <?php 
											$bg = '';
											} ?>
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
<?php if(!empty($audits)) { ?>
    <script>
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
    </script>
<?php } ?>
