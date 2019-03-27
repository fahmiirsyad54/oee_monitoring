<div class="control-group input-group">
	<div class="row">
		<div class="col-md-2">
			<div class="form-group">
				<label>Type Downtime</label>
				<select name="inttype_downtime[]" class="form-control inttype_downtime" id="inttype_downtime">
					<option data-nama="" value="0">-- Select Type --</option>
					<?php
						foreach ($listtype as $opt) {
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
				<label>Type List</label>
				<select name="inttype_list[]" class="form-control select2 inttype_list" id="">
					<option data-nama="" value="0">-- Select List --</option>
					<?php
						foreach ($listtypelist as $opt) {
					?>
					<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>

		<div class="col-md-1">
			<div class="form-group">
				<label>Start</label>
				<input type="text" name="dtmulai[]" placeholder="Start" class="form-control datetimepicker1" id="" required value="<?=date('H:i:s')?>" />
        	</div>
		</div>

		<div class="col-md-1">
			<div class="form-group">
                <label>Finish</label>
	            <input type="text" name="dtselesai[]" placeholder="Finish" class="form-control datetimepicker1" id="" required value="<?=date('H:i:s')?>" />
			</div>
		</div>

		<div class="col-md-4">
			<div class="form-group">
				<label>Problem</label>
				<input type="text" name="vcmasalah[]" placeholder="Problem" class="form-control" id="vcmasalah" required value="" />
			</div>
		</div>
	</div>

	<div class="row margin-top-10">
		<div class="col-md-4">
			<div class="form-group">
				<label>Solution</label>
				<input type="text" name="vctindakan[]" placeholder="Solution" class="form-control" id="vctindakan" required value="" />
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group">
				<label>Mechanic</label>
				<select name="intmekanik[]" class="form-control select2" id="intmekanik">
					<option data-nama="" value="0">-- Select Mechanic --</option>
					<?php
						foreach ($listmekanik as $opt) {
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
				<label>Sparepart</label>
				<select name="intsparepart[]" class="form-control select2" id="intsparepart">
					<option data-nama="" value="0">-- Select Sparepart --</option>
					<?php
						foreach ($listsparepart as $opt) {
					?>
					<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
					<?php
						}
					?>
				</select>
			</div>
		</div>

		<div class="col-md-1">
			<div class="form-group">
				<label>Qty</label>
				<input type="text" name="intjumlah[]" placeholder="Qty" class="form-control" id="intjumlah" required value="" />
			</div>
		</div>

		<div class="col-md-2">
			<div class="form-group margin-top-25">
				<a href="javascript:void(0)" class="btn btn-danger remove"><i class="fa fa-remove"></i></a>
			</div>
		</div>
	</div>

	<hr style="margin-top: 0px; margin-bottom: 10px;">
</div>

<script type="text/javascript">
	$(function (){
		$('#datepicker').datepicker({
      	autoclose: true
    	})

		$('.datetimepicker1').datetimepicker({
			format: 'HH:mm'
		});
		
		$('#datetimepicker2').datetimepicker({
			format: 'HH:mm'
		});

		$('.select2').select2();
	});

	$('.control-group .inttype_downtime').change(function(){ 
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_typelist_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Type --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			// $(this).closest('.inttype_list').html(html);
			// $('.inttype_downtime').parents().find($('.inttype_list')).html(html);
			// $('.inttype_downtime').siblings('.inttype_list').html(html);
			// $(this).parent().parent().find(".inttype_list").html(html);
			$(".inttype_list").html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$(document).ready(function() {
	  	$("body").on("change",".inttype_downtime",function(){ 
	      	// $(this).parents(".control-group").remove();
	      	var intid    = $(this).val();
			var base_url = '<?=base_url($controller)?>';
			$.ajax({
				url: base_url + '/get_typelist_ajax/' + intid,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				var html = '<option value="0">-- Select Type --</option>';
				for (var i = 0; i < jsonData.length; i++) {
					html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
				}
				// $(this).closest('.inttype_list').html(html);
				// $('.inttype_downtime').parents().find($('.inttype_list')).html(html);
				// $('.inttype_downtime').siblings('.inttype_list').html(html);
				// $(this).parent().parent().find(".inttype_list").html(html);
				// $(".inttype_list").html(html);
				$(this).parents(".inttype_list").html(html);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
	  	});
	});
</script>