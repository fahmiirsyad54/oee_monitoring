<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-4 margin-bottom-10 ">
								<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add New Data </a>
							</div>
						</div>
					</div>

					<div class="col-md-10">
						<div class="row">
							<form method="GET" action="<?=base_url($controller . '/view')?>">
								<div class="col-md-2 col-md-offset-1">
									<select name="intgedung" class="form-control select2" id="intgedung">
										<option value="0">-- All Building --</option>
										<?php
											foreach ($listgedung as $opt) {
												$selected = ($opt->intid == $intgedung) ? 'selected' : '';
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>

								<div class="col-md-3">
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
									<select name="intkomponen" class="form-control select2" id="intkomponen">
										<option value="0">-- All Component --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$selected = ($opt->intid == $intkomponen) ? 'selected' : '';
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
								<div class="col-md-1">
									<a href="javascript:void();" onclick="exportexcel()" class="btn btn-success btn-block"><i class="fa fa-file-excel-o"></i></a>
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
								<th>Date</th>
								<th>Building</th>
								<th align="center">Model</th>
								<th>Component</th>
								<th>PO</th>
								<th>Qty</th>
								<th>Total Cut</th>
								<th>GAP</th>
								<th></th>
							</tr>
						</thead>
						<tbody>
							<?php
								$jmldata = count($dataP);
								if ($jmldata === 0) {
							?>
								<tr>
									<td colspan="10" align="center">Data Not found</td>
								</tr>
							<?php
								} else {
									$no = $firstnum;
									foreach ($dataP as $data) {
										// if ($data->vcketerangan != '') {
										// 	$keterangan = "Not Follow SOP";
										// } else {
										// 	$keterangan = "Follow SOP";
										// }

										$gap = $data->intpasang - $data->intqty;
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=date('d M Y', strtotime($data->dttanggal))?></td>
									<td><?=$data->vcgedung?></td>
									<td><?=$data->vcmodel?></td>
									<td><?=$data->vckomponen?></td>
									<td><?=$data->vcpo?></td>
									<td><?=$data->intqty?></td>
									<td><?=$data->intpasang?></td>
									<td><?=$gap?></td>
									<td>
										<a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i> Edit</a>
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
					echo pagination11($halaman, $link, $jmlpage, $intgedung, $intmodel, $intkomponen, $vcpo);
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

    // $('#intgedung').change(function(){
	// 	var base_url = '<?=base_url($controller)?>';
	// 	var intid    = $(this).val();
	// 	$.ajax({
	// 		url: base_url + '/getmodelajax/' + intid,
	// 		method: "GET"
	// 	})
	// 	.done(function( data ) {
	// 		var jsonData = JSON.parse(data);
	// 		var html = '<option value="0">-- All Model --</option>';
	// 		for (var i = 0; i < jsonData.length; i++) {
	// 			html += '<option value="'+jsonData[i].intmodel+'">'+jsonData[i].vcmodel+'</option>';
	// 		}
	// 		$('#intmodel').html(html);
	// 	})
	// 	.fail(function( jqXHR, statusText ) {
	// 		alert( "Request failed: " + jqXHR.status );
	// 	});
	// });

	$('#intmodel').change(function(){
		var base_url  = '<?=base_url($controller)?>';
		var intgedung = $("#intgedung").val();
		var intmodel     = $(this).val();
		$.ajax({
			url: base_url + '/getkomponenajax/' + intmodel + '/' + intgedung,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var listkomponen = jsonData.listkomponen;
			var html = '<option data-nama="" value="0">-- All Component --</option>';
			for (var i = 0; i < listkomponen.length; i++) {
				html += '<option data-intid="' + listkomponen[i].intid + '" value="' + listkomponen[i].intid + '">' + listkomponen[i].vckomponen + '</option>'
			}
			$('#intkomponen').html(html);

			var listpo = jsonData.listpo;
			var pohtml = '<option data-nama="" value="0">-- All PO --</option>';
			for (var i = 0; i < listpo.length; i++) {
				pohtml += '<option value="' + listpo[i].vcpo + '">' + listpo[i].vcpo + '</option>'
			}
			$('#vcpo').html(pohtml);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});
	
</script>