<div class="row">
	<div class="col-md-12">
		<div class="box"> 
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div> 

			<div class="box-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
						<div class="col-md-6">
							<div class="form-group">
								<label>Models</label>
								<select name="intmodel" class="form-control select2" id="intmodel">
									<option data-nama="" value="0">-- Select Models --</option>
									<?php
										foreach ($listmodels as $opt) {
											$selected = ($intmodel == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>PO</label>
								<input type="text" name="vcpo" placeholder="PO" class="form-control" id="vcpo" required value="<?=$vcpo?>" />
							</div>

							<div class="form-group">
							<label>SDD</label>
								<input type="text" name="sdd" placeholder="sdd" class="form-control datepicker" id="sdd" value="<?=$sdd?>" />
							</div>

							<div class="form-group">
								<label>Quantity</label>
								<input type="number" name="intqty" placeholder="Quantity" class="form-control" id="intqty" required value="<?=$intqty?>" />
							</div>

							<div class="form-group">
								<label>Quantity Add</label>
								<input type="text" name="intqtyadd" placeholder="Quantity Add" class="form-control" id="intqtyadd" required value="<?=$intqtyadd?>" />
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i>Close</a>
							</div>
						</div>
					</form>
				</div>
			</div>

		</div>
	</div>
</div>



<script type="text/javascript">
	$(function () {
  		$('.datepicker').datepicker({
      	autoclose: true
    	})

		$('.select2').select2()
  	}) 
	  
	function simpanData(action) {
		var vcpo         = $('#vcpo').val();
		var vccontroller = $('#vccontroller').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'vcpo' : vcpo};
			var formdata = {'vcpo' : vcpo, 'vccontroller' : vccontroller};

			$.ajax({
				url: base_url + '/validasiform/required',
				method: "POST",
				data : formrequired
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				if (jsonData.length > 0) {
					var html = '';
					for (var i = 0; i < jsonData.length; i++) {
						html += '' + jsonData[i].error + '<br/>';
					}

					swal({
						type: 'error',
						title: 'There is something wrong',
						html: html
					});
				} else {
					$.ajax({
						url: base_url + '/validasiform/data',
						method: "POST",
						data : formdata
					})
					.done(function( data ) {
						var jsonData = JSON.parse(data);
						if (jsonData.length > 0) {
							var html = '';
							for (var i = 0; i < jsonData.length; i++) {
								html += '' + jsonData[i].error + '<br/>';
							}

							swal({
								type: 'error',
								title: 'There is something wrong',
								html: html
							});
						} else {
							$('#postdata').submit()
						}
					})
					.fail(function( jqXHR, statusText ) {
						alert( "Request failed: " + jqXHR.status );
					});
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		} else if (action == 'Edit') {
			$('#postdata').submit();
		}
	}

</script>