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
						<input type="hidden" name="intinout" id="intinout" value="1">
						<div class="col-md-6">
							<div class="form-group">
								<label>Code</label>
								<input type="text" name="vckode" placeholder="Code" class="form-control" id="vckode" required value="<?=$vckode?>" readonly />
							</div>

							<div class="form-group">
								<label>Name Sparepart</label>
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
								<input type="hidden" name="vcsparepart" id="vcsparepart">
							</div>

							<div class="form-group">
								<label>No . PO</label>
								<input type="text" name="vcnomor_po" placeholder="No .PO" class="form-control" id="vcnomor_po" required value="<?=$vcnomor_po?>" />
							</div>

							<div class="form-group">
								<label>Date Order</label>
								<input type="text" name="dtorder" placeholder="Date order" class="form-control" id="datepicker" required value="<?=$dtorder?>" />
							</div>

							<div class="form-group">
								<label>Suplier</label>
								<select name="intsuplier" class="form-control" id="intsuplier">
									<option data-nama="" value="0">-- Select Suplier --</option>
									<?php
										foreach ($listsuplier as $opt) {
											$selected = ($intsuplier == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
								<input type="hidden" name="vcsuplier" id="vcsuplier">
							</div>
						</div>

						<div class="col-md-6">
								<div class="form-group">
									<label>Quantity</label>
									<input type="text" name="decqtymasuk" placeholder="Qty" class="form-control" id="decqtymasuk" required value="<?=$decqtymasuk?>" />
								</div>

								<div class="form-group">
									<label>Price</label>
									<input type="text" name="decharga" placeholder="Qty" class="form-control" id="decharga" required value="<?=$decharga?>" />
								</div>
								<div class="form-group">
									<label>Price total</label>
									<input type="text" name="dectotal" placeholder="Total" class="form-control" id="dectotal" required value="<?=$dectotal?>" />
								</div>
								<div class="form-group">
									<label>Remarks</label>
									<textarea name="vcketerangan" placeholder="Remarks" class="form-control" id="vcketerangan" required><?=$vcketerangan?></textarea>
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

	$('#intgedung').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_cell_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Cell --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#intcell').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

</script>

<script type="text/javascript">
	$('#decharga').keyup(function(){
		var _decharga = $(this).val();
		var _decqtymasuk = $('#decqtymasuk').val();
		var _dectotal = _decqtymasuk * _decharga;
		
		$('#dectotal').val(_dectotal);
	});

	$('#decqtymasuk').keyup(function(){
		var _decqtymasuk = $(this).val();
		var _decharga = $('#decharga').val();
		var _dectotal = _decqtymasuk * _decharga;
		
		$('#dectotal').val(_dectotal);
	});

	$('#intsparepart').change(function(){
		var vcsparepart    = $(this).children('option:selected').data('nama');
		$('#vcsparepart').val(vcsparepart);

	});

	$('#intsuplier').change(function(){
		var vcsuplier    = $(this).children('option:selected').data('nama');
		$('#vcsuplier').val(vcsuplier);

	});

</script>