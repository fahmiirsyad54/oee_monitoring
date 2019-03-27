<!-- bootstrap datepicker -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script>
  $(function () {
  	$('#datepicker').datepicker({
      autoclose: true
    })
  })
</script>

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
								<label>Sparepart</label>
								<select name="intsparepart" class="form-control select2" id="intsparepart">
									<option data-nama="" value="0">-- Select Sparepart --</option>
									<?php
										foreach ($listsparepart as $opt) {
											$selected = ($intsparepart == $opt->intid) ? 'selected' : '' ;
											$vcspekpart = '';
											if ($opt->vcspesifikasi != '' && $opt->vcpart != '') {
												$vcspekpart = ' - ' . $opt->vcspesifikasi . ' - ' . $opt->vcpart;
											} elseif ($opt->vcspesifikasi == '' && $opt->vcpart != '') {
												$vcspekpart = ' - ' . $opt->vcpart;
											} elseif ($opt->vcspesifikasi != '' && $opt->vcpart == '') {
												$vcspekpart = ' - ' . $opt->vcspesifikasi;
											}
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?='('.$opt->vckode.') '.$opt->vcnama . $vcspekpart?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Bulding</label>
								<select name="intgedung" class="form-control select2" id="intgedung">
									<option data-nama="" value="0">-- Select Building --</option>
									<?php
										foreach ($listgedung as $opt) {
											$selected = ($intgedung == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>

							<div class="form-group">
								<label>Quantity</label>
								<input type="text" name="decqtykeluar" placeholder="QTY" class="form-control" id="decqtykeluar" required value="<?=$decqtykeluar?>" />
							</div>

							<div class="form-group">
								<label>Date Sparepart Out</label>
								<input type="text" name="dtorder" placeholder="Date order" class="form-control" id="datepicker" required value="<?=$dtorder?>" />
							</div>

							<div class="form-group">
								<label>Remaks</label>
								<textarea name="vcketerangan" placeholder="Remaks" class="form-control" id="vcketerangan" required><?=$vcketerangan?></textarea>
							</div>
						</div>

						<div class="col-md-6">
							
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
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});
	function simpanData(action) {
		var vckode       = $('#vckode').val();
		var vcnama       = $('#vcnama').val();
		var vccontroller = $('#vccontroller').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'vckode' : vckode, 'vcnama' : vcnama};
			var formdata = {'vckode' : vckode, 'vcnama' : vcnama, 'vccontroller' : vccontroller};

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