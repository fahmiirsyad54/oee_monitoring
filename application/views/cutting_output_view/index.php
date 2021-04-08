<script>
  $(function () {
  	$('.datepicker').datepicker({
      autoclose: true
    })
  }) 
</script>
<div class="row">
	<div class="col-md-12">
		<div class="box">
			<div class="box-body">
				<div class="row">
					<div class="col-md-1 margin-bottom-10">
						<a href="<?=base_url($controller . '/add')?>" class="btn btn-primary"><i class="fa fa-plus"></i> Add Data</a>
					</div>
					<!-- <div class="col-md-1 margin-bottom-10">
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#modalImport">
						  <i class="fa fa-plus"></i> Import Data
						</button>
					</div> -->
					<div class="col-md-10">
						<div class="row">
							<form method="GET" action="<?=base_url($controller . '/view')?>">
								<div class="col-md-3">
									<div class="form-group">
										<input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="<?=$from_input?>" />
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="<?=$to_input?>" />
									</div>
								</div>
								
								<div class="col-md-4">
									<select name="intgedung" class="form-control select2" id="intgedung">
										<option value="0">-- Select Building --</option>
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
								<th>Model</th>
								<th>Quantity Cell</th>
								<th>Cell capa / day</th>
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
									$gap = 0;
									foreach ($dataP as $data) {
										// $gap    = $data->intpasang - $data->targetcell;
										// $target = $data->intcell * $data->targetcell;
							?>
								<tr>
									<td><?=++$no?></td>
									<td><?=date('d M Y', strtotime($data->dttanggal))?></td>
									<td><?=$data->vcgedung?></td>
									<td><?=$data->vcmodel?></td>
									<td><?=$data->intcell?></td>
									<td><?=$data->inttarget?></td>
									<td>
										<div class="<?=$hideaction?>">
											<!-- <a href="javascript:void(0);" onclick="detailData(<?=$data->intid?>)" class="btn btn-xs btn-info"><i class="fa fa-info"></i> Detail</a> -->

											<a href="<?=base_url($controller . '/edit/' . $data->intid)?>" class="btn btn-xs btn-warning"><i class="fa fa-pencil"></i>Edit</a>
										</div>
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
					echo pagination9($halaman, $link, $jmlpage, $from, $to, $intgedung, $keyword);
				?>
			</div>

		</div>
	</div>
</div>

<!-- Modal -->
<div id="modalImport" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title">Import Data Target Cell</h4>
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

	function ubahStatus(intid, intstatus){
		swal({
			title: 'Warning !',
			text: "Status will change",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Change',
			cancelButtonText: 'Cancel'
		}).then((result) => {
		  if (result.value) {
		    var base_url = '<?=base_url($controller)?>';
			$.ajax({
				url: base_url + '/aksi/ubahstatus/' + intid + '/' + intstatus,
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

	function exportexcel(){
		var base_url  = '<?=base_url($controller)?>';
		var from      = $('#from').val();
		var to        = $('#to').val();
		var intgedung = $('#intgedung').val();
		// //var intmesin  = $('#intmesin').val();
		// var intshift  = $('#intshift').val();
		//window.open(base_url + '/exportexcelnew?from=' + from + '&to=' + to + '&intgedung=' + intgedung + '&intshift=' + intshift);
		window.open(base_url + '/exportexcelnew?from=' + from + '&to=' + to + '&intgedung=' + intgedung);
	}

	function importact(){
		var dataimport =  $('#dataimport').val();

        if (dataimport == '') {
            alert('Data not empty!');
        } else {
            $('#formimport').submit();
        }
    }
</script>