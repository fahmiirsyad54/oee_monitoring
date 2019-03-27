<!-- bootstrap datepicker -->
<script src="<?=BASE_URL_PATH?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>

<script>
  $(function () {
  	$('.datepicker').datepicker({
      autoclose: true
    })
  })
</script>
<?php
	$listbulan = array(
				'1' => 'January',
				'2' => 'February',
				'3' => 'March',
				'4' => 'April',
				'5' => 'May',
				'6' => 'July',
				'7' => 'June',
				'8' => 'August',
				'9' => 'September',
				'10' => 'October',
				'11' => 'November',
				'12' => 'December',
			);
	$date         = (isset($_GET['dtmonth'])) ? $this->input->get('dtmonth') : date('m');
	$year         = (isset($_GET['intyear'])) ? $this->input->get('intyear') : date('Y');
	$intsparepart = (isset($_GET['intsparepart'])) ? $this->input->get('intsparepart') : 0;
?>
<div class="row">
	<div class="col-md-12">
		<div class="box">
			
			<div class="box-body">
				<div class="row margin-bottom-10">
					<form method="GET" action="<?=base_url($controller . '/view')?>">
					<div class="col-md-4">
						<select class="form-control select2" name="intsparepart" id="intsparepart" style="width: 100%;">
							<option selected="selected" value="0">-- Select All --</option>
							<?php
								foreach ($listsparepart as $opt) {
									$selected = ($opt->intid == $intsparepart) ? 'selected' : '';
									$vcspekpart = '';
									if ($opt->vcspesifikasi != '' && $opt->vcpart != '') {
										$vcspekpart = ' - ' . $opt->vcspesifikasi . ' - ' . $opt->vcpart;
									} elseif ($opt->vcspesifikasi == '' && $opt->vcpart != '') {
										$vcspekpart = ' - ' . $opt->vcpart;
									} elseif ($opt->vcspesifikasi != '' && $opt->vcpart == '') {
										$vcspekpart = ' - ' . $opt->vcspesifikasi;
									}
							?>
							<option <?=$selected?> value="<?=$opt->intid?>"><?='(' . $opt->vckode . ') ' . $opt->vcnama . $vcspekpart?></option>
							<?php
								}
							?>
						</select>
					</div>

					<div class="col-md-2">
						<select class="form-control" name="dtmonth" id="dtmonth">
							<?php
								foreach ($listbulan as $key => $value) {
									$selected = ($date == $key) ? 'selected' : '';
							?>
							<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
							<?php
								}
							?>
						</select>
					</div>

					<div class="col-md-2">
						<select class="form-control" name="intyear" id="intyear">
							<?php
								for ($i=2015; $i <= date('Y'); $i++) { 
									$selected = ($i == $year) ? 'selected' : '';
							?>
							<option <?=$selected?> value="<?=$i?>"><?=$i?></option>
							<?php
								}
							?>
						</select>
					</div>
					
					<div class="col-md-2">
						<button class="btn btn-default btn-block" type="sbumit"><i class="fa fa-search"></i> Search</button>
					</div>

					<div class="col-md-2">
						<a href="javascript:void();" onclick="exportexcel()" class="btn btn-success btn-block"><i class="fa fa-file-excel-o"></i> Export Excel</a>
					</div>
					</form>
				</div>
				<?php
					if ($intsparepart == 0) {
						include 'index_all.php';
					} elseif ($intsparepart > 0) {
						include 'index_persparepart.php';
					}
				?>
				<?php
					$link = base_url($controller . '/view');
					echo pagination2($halaman, $link, $jmlpage, $intsparepart, $date, $year);
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
<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()
	});

	function exportexcel() {
		var base_url     = '<?=base_url($controller)?>';
		var intsparepart = $('#intsparepart').val();
		var dtmonth      = $('#dtmonth').val();
		var intyear      = $('#intyear').val();
		window.open(base_url + '/exportexcel?intsparepart=' + intsparepart + '&dtmonth=' + dtmonth + '&intyear=' + intyear);
	}
</script>