<div class="control-group">
	    <div class="row">
	    	<div class="col-md-3">
	    		<div class="form-group">
				<label>Component</label>
					<select name="intkomponen2[]" class="form-control intkomponen2 select2" id="intkomponen2">
						<option value="0">-- Select Component --</option>
						<?php
							foreach ($listkomponen2 as $opt) {
						?>
						<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
						<?php
							}
						?>
					</select>
				</div>
	    	</div>

	    	<div class="col-md-3">	
				<div class="form-group">
					<label>Standard layer</label>
					<select class="form-control intlayer2" name="intlayer2[]" id="intlayer2" >
						<option data-nama="" value="">-- Select Standard Layer --</option>
						<?php
							foreach ($listlayer2 as $key => $value) {
								$selected = ($key == $intlayer2) ? 'selected' : '';
						?>
						<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
						<?php
							}
						?>
					</select>
				</div>
			</div>
			
			<div class="col-md-1">
				<div class="form-group">
					<label>2 Layer</label>
					<input type="text" name="deccycle_time2[]" placeholder="" class="form-control deccycle_time2" id="deccycle_time2" value="" required />
					<input type="hidden" name="intkomponenct2[]" class="form-control intkomponenct2" id="intkomponenct2" value="" hidden />
					<input type="hidden" name="vclayer2[]" value="2 layer"/>
					<input type="hidden" name="intlayerct2[]" value="2"/>
				</div>
			</div>

			<div class="col-md-1">
				<div class="form-group">
					<label>4 Layer</label>
					<input type="text" name="deccycle_time2[]" placeholder="" class="form-control deccycle_time2" id="deccycle_time2" value="" required />
					<input type="hidden" name="intkomponenct2[]" class="form-control intkomponenct2" id="intkomponenct2" value="" hidden />
					<input type="hidden" name="vclayer2[]" value="4 layer"/>
					<input type="hidden" name="intlayerct2[]" value="4"/>
				</div>
			</div>

			<div class="col-md-1">
				<div class="form-group">
					<label>6 Layer</label>
					<input type="text" name="deccycle_time2[]" placeholder="" class="form-control deccycle_time2" id="deccycle_time2" value="" required />
					<input type="hidden" name="intkomponenct2[]" class="form-control intkomponenct2" id="intkomponenct2" value="" hidden />
					<input type="hidden" name="vclayer2[]" value="6 layer"/>
					<input type="hidden" name="intlayerct2[]" value="6"/>
				</div>
			</div>

			<div class="col-md-1">
				<div class="form-group">
					<label>8 Layer</label>
					<input type="text" name="deccycle_time2[]" placeholder="" class="form-control deccycle_time2" id="deccycle_time2" value="" required />
					<input type="hidden" name="intkomponenct2[]" class="form-control intkomponenct2" id="intkomponenct2" value="" hidden />
					<input type="hidden" name="vclayer2[]" value="8 layer"/>
					<input type="hidden" name="intlayerct2[]" value="8"/>
				</div>
			</div>
			
			<div class="col-md-1 margin-top-25">
				<div class="form-group">
					<a href="javascript:void(0)" class="btn btn-danger remove"><i class="fa fa-remove"></i></a>
					<!-- <button class="btn btn-danger remove margin-top-25" type="button"><i class="glyphicon glyphicon-remove"></i></button> -->
				</div>
			</div>
		</div>
</div>

<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});

	$('.intkomponen2').change(function(){
		var row         = $(this).closest(".row");
		var intkomponen2 = row.find('.intkomponen2').val();
		
		var base_url = '<?=base_url("models")?>';
		$.ajax({
			url: base_url + '/getintkomponen2/' + intkomponen2,
			method: "GET"
		})
		.done(function( data ) {
			var result = JSON.parse(data);
			
			row.find('.intkomponenct2').val(result[0].intid);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

		});

</script>