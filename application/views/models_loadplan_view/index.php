<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-6 margin-bottom-10">
								<button type="button" class="btn btn-success" data-toggle="modal" data-target="#modalImport">
								<i class="fa fa-file-excel-o"></i> Import Excel
								</button>
							</div>
							<div class="col-md-4 margin-bottom-10 ">
								<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Data </a>
							</div>
						</div>
					</div>

					<div class="col-md-10">
						<div class="row">
							<form method="GET" action="<?=base_url($controller . '/view')?>">
								<div class="col-md-3 col-md-offset-4">
									<select name="intmodel" class="form-control select2" id="intmodel">
										<option value="0">-- All Models --</option>
										<?php
											foreach ($listmodel as $opt) {
												$selected = ($opt->intid == $intmodel) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>

								<div class="col-md-2">
									<select name="vcpo" class="form-control select2" id="vcpo">
										<option value="">-- All PO --</option>
										<?php
											foreach ($listpo as $opt) {
												$selected = ($opt->vcpo == $vcpo) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcpo?>" value="<?=$opt->vcpo?>"><?=$opt->vcpo?></option>
										<?php
											}
										?>
									</select>
								</div>
								<div class="col-md-1">
									<button class="btn btn-default btn-block" type="sbumit"><i class="fa fa-search"></i></button>
								</div>
							</form>	
						</div>
					</div>
					
				</div>

				<div class="table-responsive">
					<table class="table table-bordered table-hover table-striped">
						<thead>
							<tr>
								<th>No</th>
								<th>Date Import</th>
								<th align="center">Model</th>
								<th>PO</th>
								<th>Qty</th>
								<th>SDD</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$jmldata = count($dataP);
								if ($jmldata === 0) {
							?>
								<tr>
									<td colspan="6" align="center">Data Not found</td>
								</tr>
							<?php
								} else {
									$no = $firstnum;
									foreach ($dataP as $data) {
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=date('d M Y', strtotime($data->dttanggal))?></td>
									<td><?=$data->vcmodel?></td>
									<td><?=$data->vcpo?></td>
									<td><?=$data->intqty?></td>
									<td><?=date('d M Y', strtotime($data->sdd))?></td>
									<td>
										<a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Edit</a>
										<a href="javascript:void(0);" onclick="hapusData(<?=$data->intid?>)" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i> Delete</a>
									</td>
								</tr>
							<?php
									}
								}
							?>
						</tbody>
					</table>
				</div>

				<?php
					$link = base_url($controller . '/view');
					echo pagination12($halaman, $link, $jmlpage, $intmodel, $vcpo);
				?>
			</div>

		</div>
	</div>
</div>
<!-- Modal -->
<div id="modalDetail" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<!-- Modal content-->
		<div class="modal-content" id="datadetail">
		</div>
	</div>
</div>

<!-- Modal -->
<div id="modalImport" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Import Data Load Plan</h4>
			</div>
			<div class="modal-body">
			<form method="POST" id="formimport" enctype="multipart/form-data" action="<?=base_url($controller.'/importdata')?>">
                        <div class="row">
                            <div class="col-md-6">
                                <input type="file" name="dataimport" id="dataimport" class="form-control" required>
                            </div>
                        </div>
                    </form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-success" onclick="importact()"><i class="fa fa-save"></i> Import</button>
				<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"> </i>Close</button>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});

	function detailData(intid) {
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/detail/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			$('#datadetail').html(data);
			$('#modalDetail').modal('show');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function hapusData(intid){
		swal({
			title: 'Data will Delete ?',
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Delete',
			cancelButtonText: 'Cancel'
		}).then((result) => {
		  if (result.value) {
		    var base_url = '<?=base_url($controller)?>';
			$.ajax({
				url: base_url + '/aksi/hapus/' + intid,
				method: "GET"
			})
			.done(function( data ) {
				window.location.replace(base_url + "/view");
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		  }
		})
	}

	function importact(){
		var dataimport =  $('#dataimport').val();
		var intmodel  =  $('#intmodel').val();
		var intkomponen  =  $('#intkomponen').val();

        if (dataimport == '') {
            alert('Data not empty!');
        } else {
            $('#formimport').submit();
        }
    }

	$('#intmodel').change(function(){
		var base_url = '<?=base_url($controller)?>';
		var intmodel = $(this).val();
		$.ajax({
			url: base_url + '/getpoajax/' + intmodel,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="">-- All PO --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="'+jsonData[i].vcpo+'">'+jsonData[i].vcpo+'</option>';
			}
			$('#vcpo').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});
</script>