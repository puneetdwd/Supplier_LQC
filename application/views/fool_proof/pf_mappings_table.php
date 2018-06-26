<div class="portlet-body" id='mapping'>
                    <?php if(empty($part_nums)) { ?>
                        <p class="text-center">No Parts.</p>
                    <?php } else { ?>
                        <table class="table table-hover table-light" >
                            <thead>
                                <tr>
                                    <th>Part Name</th>
                                    <th>Part Number</th>
                                    <th>
									Mapping(Part-Foolproof)
									</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php 
								$i = 0;
								foreach($part_nums as $part_num) { ?>
								<?php	$checked = ''; ?>
                                    <tr>
                                       <td><?php echo $part_num['name']; ?></td>
                                        <td><?php echo $part_num['code']; ?></td>
                                        <td>
											<?php
												//$check_map = $CI->foolproof_model->pf_map_status($part_num['id']);
												foreach($pf_mapping1 as $m) { 
													if($m['part_num'] == $part_num['code'])
														if($m['checkpoint_id'] == $foolproof_id)
															$checked = 'checked';
												}
											?>
											<!--input <?php  echo $checked; ?> data-index="<?php echo $part_num['id']; ?>" type="checkbox" name="map_pf" id="map_pf" onClick="return map_part_foolproof(<?php echo $part_num['id']; ?>);" -->
											<input  <?php  echo $checked; ?>	data-index="<?php echo $part_num['id']; ?>" type="checkbox" name="map_pf_<?php echo $i; ?>" id="map_pf_<?php echo $i; ?>" onClick='map_part_foolproof(<?php echo $part_num['id'].",".$i; ?>);' >
										</td>
                                       
                                    </tr>
                                <?php 
								$i++;
								} ?>
                            </tbody>
                        </table>
                    <?php } ?>
                    
</div>