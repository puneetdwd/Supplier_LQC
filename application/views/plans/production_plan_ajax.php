<div class="modal-header">
    <h4 class="modal-title">Adjust Production Plan - <?php echo date('jS M, Y', strtotime($plan['plan_date'])); ?></h4>
</div>
<form role="form" class="adjust-production-form validate-form form-horizontal" action="<?php echo base_url().'sampling/production_plan/'.$plan['id']; ?>" method="post">
    <div class="modal-body">
        <div class="alert alert-danger display-hide">
            <button class="close" data-close="alert"></button>
            Please fill lot size.
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">
                    Line : 
                </label>
                <p class="form-control-static">
                    <?php echo $plan['line']; ?>
                </p>
            </div>
            
            <div class="col-md-6">
                <label class="control-label">
                    Tool :
                </label>
                <p class="form-control-static">
                    <?php echo $plan['tool']; ?>
                </p>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <label class="control-label">
                    Model.Suffix :
                </label>
                <p class="form-control-static">
                    <?php echo $plan['model_suffix']; ?>
                </p>
            </div>
            
            <div class="col-md-6">
                <label class="control-label">
                    Lot Size :
                </label>
                <p class="form-control-static">
                    <?php echo $plan['lot_size']; ?>
                </p>
            </div>
        </div>
		
		<div class="row">
            <div class="col-md-10">
                <div class="form-group">
					<div class="col-md-4" >
						<input type="radio" name="lot_split_adjust" value="adjust_lot" checked> Adjust Lot<br>
                    </div>
					<div class="col-md-4">
						<input type="radio" name="lot_split_adjust" value="lot_split" > Lot Split<br>
                    </div>                    
                </div>
            </div>
			
        </div> 
        
        <div class="row adjust_lot " id="adjust_lot1">
            <div class="col-md-12">
                <div class="form-group">
                    <label class="control-label col-md-5" for="lot_size">Adjust Lot Size:</label>
                    <div class="col-md-4">
                        <input type="text" class="form-control" name="lot_size" value="">
                    </div>
                </div>
            </div>
        </div> 
		
		<div class="row lot_split hidden" id="lot_split1">
			<?php foreach($product_lines as $pl){ ?>
				<div class="col-md-12">
					<div class="form-group">
						<label class="control-label col-md-5" for="line_<?php echo $pl['name']; ?>"><?php echo $pl['name']. ':'; ?></label>
						<div class="col-md-4">
							<input type="text" class="form-control" name="line_<?php echo $pl['name']; ?>" value="">
						</div>
					</div>
				</div>
			<?php } ?>
        </div>
        
    </div>
    
    <div class="modal-footer">
        <button type="button" class="adjust-production-modal-close button white" data-dismiss="modal">Close</button>
        <button type="submit"  class="button">Save</button>
    </div>
</form>

<script>
	$('input:radio[name="lot_split_adjust"]').change(
		function(){
			if (this.checked && this.value == 'adjust_lot') {
				$('#adjust_lot1').removeClass('hidden');
				$('#lot_split1').addClass('hidden');
				
			}
			else  if (this.checked && this.value == 'lot_split') {
				$('#adjust_lot1').addClass('hidden');
				$('#lot_split1').removeClass('hidden');
			}
    });
	
	
</script>