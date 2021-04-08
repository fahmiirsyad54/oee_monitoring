<div class="control-group">
    <div class="row">
		<div class="col-md-2">
    		<div class="form-group">
			<label>Models</label>
				<select name="intmodel[]" class="form-control intmodel select2" id="intmodel">
					<option value="0">-- Select Models --</option>
					<?php
						foreach ($listmodels as $opt) {
					?>
					<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
					<?php
						}
					?>
				</select>
			</div>
    	</div>

		<div class="col-md-2">
    		<div class="form-group">
			<label>Component</label>
				<select name="intkomponen[]" class="form-control intkomponen select2" id="intkomponen">
					<option value="0">-- Select Component --</option>
					<?php
						foreach ($listkomponen as $opt) {
					?>
					<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
					<?php
						}
					?>
				</select>
				<input type="text" name="decct[]" placeholder="cycle time" class="form-control decct" id="decct" value=""/>
				<input type="text" name="intlayer[]" placeholder="tulis jumlah layer saja, contoh :2" class="form-control intlayer" id="intlayer" value=""/>
			</div>
    	</div>

    	<div class="col-md-2">
			<div class="form-group">
				<label>PO</label>
				<select name="vcpo[]" class="form-control" id="vcpo">
					<option data-nama="" value="0">-- Select PO --</option>
					<?php
						foreach ($listpo as $opt) {
					?>
					<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->vcpo?>"><?=$opt->vcpo?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label>Layer Actual</label>
				<select name="intremark[]" class="form-control" id="intremark">
					<option data-nama="" value="0">-- Select Layer --</option>
					<?php
						foreach ($listremark as $opt) {
							$selected = ($intremark == $opt->intid) ? 'selected' : '' ;
					?>
					<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label>Pair</label>
				<input type="number" name="intpasang[]" id="intpasang" class="form-control" value="">
			</div>
		</div>

		<div class="col-md-1">
			<div class="form-group">
				<label>Reject</label>
				<input type="number" name="intreject[]" id="intreject" class="form-control" value="">
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

	$('.intmodel').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("output")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Select Component --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" data-layer="' + jsonData[i].intlayer + '" value="' + jsonData[i].intkomponen + '">' + jsonData[i].vckomponen + '</option>'
			}

			$('.intkomponen').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('.intkomponen').change(function(){
		var intid    = $(this).val();
		var decct    = $(this).children('option:selected').data('ct');
		var intlayer = $(this).children('option:selected').data('layer');

		$('.decct').val(decct),
		$('.intlayer').val(intlayer)
	});

	// $('.intkomponen').change(function(){
	// 	var row         = $(this).closest(".row");
	// 	var intkomponen = row.find('.intkomponen').val();
		
	// 	var base_url = '<?=base_url("output")?>';
	// 	$.ajax({
	// 		url: base_url + '/getintkomponen/' + intkomponen,
	// 		method: "GET"
	// 	})
	// 	.done(function( data ) {
	// 		var result = JSON.parse(data);
			
	// 		row.find('.decct').val(result[0].deccycle_time);
	// 		row.find('.intlayer').val(result[0].intlayer);
	// 	})
	// 	.fail(function( jqXHR, statusText ) {
	// 		alert( "Request failed: " + jqXHR.status );
	// 	});

	// });
</script>