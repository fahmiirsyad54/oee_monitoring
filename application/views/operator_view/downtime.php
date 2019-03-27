<link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/plugins/toastr/build/toastr.css">
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-info" style="border-radius: 0px; background-color: #357ca5 !important; border-color: #357ca5 !important">
			<span id="realtime"></span> <span id="intshift"></span>
			<div class="pull-right">
				<span style="padding-right: 20px; " id="vckodemesin">ID Mesin : </span>
				<span style="padding-right: 20px; " id="vcoperator">Operator : </span>
				<span style="padding-right: 20px; " id="jamkerja">Jam Kerja : </span>
				<a href="javascript:void(0)" data-toggle="modal" data-target="#modalLembur"><span style="padding-right: 20px; "><i class="fa fa-clock-o"></i> Lembur</span></a>
				<a href="javascript:void(0)" data-toggle="modal" data-target="#modalPesan"><span style="padding-right: 20px; "><i class="fa fa-envelope"></i> Catatan</span></a>
				<a href="javascript:void()" data-toggle="modal" data-target="#modalLogout"><i class="fa fa-sign-out"></i> Keluar</a>
				<!-- <a href="<?=base_url('akses/logoutop')?>"><i class="fa fa-sign-out"></i> Log Out</a> -->
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="alert alert-warning alert-dismissible" id="warningactual" style="display: none;">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
			  	<strong>Peringatan!</strong> Masukkan Actual Output.
			</div>
		</div>
		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					Downtime
				</div>

				<div class="box-body">
					<form method="POST" id="savedowntime" action="<?=base_url($controller . '/add_downtime')?>">
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<a href="javascript:void()" id="btnstartdowntime" class="btn btn-primary btn-block btn-lg margin-top-15">Mulai</a>
									<input type="hidden" name="dtmulai" id="dtmulai">
								</div>
							</div>
							
							<div class="col-md-4">
								<div class="form-group">
									<a href="javascript:void()" onclick="getlistdowntime()" id="btndowntime" class="btn btn-primary btn-block btn-lg margin-top-15">Pilih Downtime</a>
									<input type="hidden" name="inttype_list" id="inttype_list" value="0">
									<input type="hidden" name="inttype_downtime" id="inttype_downtime" value="0">
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<label>Mekanik</label>
									<select name="intmekanik" class="form-control select2" id="intmekanik">
										<option data-nama="" value="0">-- Pilih Mekanik --</option>
										<?php
											foreach ($listmekanik as $opt) {
												// $selected = ($inttype_list == $opt->intid) ? 'selected' : '' ;
										?>
										<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>
						</div>
 
						<div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Sparepart</label>
									<select name="intsparepart" class="form-control select2" id="intsparepart">
										<option data-nama="" value="0">-- Pilih Sparepart --</option>
										<?php
											foreach ($listsparepart as $opt) {
												$vcspekpart = '';
											if ($opt->vcspesifikasi != '' && $opt->vcpart != '') {
												$vcspekpart = ' - ' . $opt->vcspesifikasi . ' - ' . $opt->vcpart;
											} elseif ($opt->vcspesifikasi == '' && $opt->vcpart != '') {
												$vcspekpart = ' - ' . $opt->vcpart;
											} elseif ($opt->vcspesifikasi != '' && $opt->vcpart == '') {
												$vcspekpart = ' - ' . $opt->vcspesifikasi;
											}
										?>
										<option value="<?=$opt->intid?>"><?=$opt->vcnama . ' - ' . $vcspekpart?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Qty</label>
									<input type="number" name="intjumlah" id="intjumlah" class="form-control" />
								</div>
							</div>
						</div>

						<div class="row" id="btndowntimefinish">
							<div class="col-md-4 text-center">
								<i class="fa fa-spinner fa-pulse fa-2x fa-fw hidden loadingdt"></i>
								<a href="javascript:void(0)" onclick="resetdowntime()" class="btn btn-danger btn-block savedtbtn"><i class="fa fa-refresh"></i> Reset</a>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<i class="fa fa-spinner fa-pulse fa-2x fa-fw hidden loadingdt"></i>
									<a href="javascript:void()" id="btnfinish" onclick="simpandowntime(1)" class="btn btn-primary btn-block savedtbtn">Downtime Baru</a>
								</div>
							</div>

							<div class="col-md-4">
								<div class="form-group">
									<i class="fa fa-spinner fa-pulse fa-2x fa-fw hidden loadingdt"></i>
									<a href="javascript:void()" id="startcutting" onclick="simpandowntime(2)" class="btn btn-primary btn-block savedtbtn">Mulai Potong</a>
								</div>
							</div>
						</div>
					</form>

					<hr>

					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>Downtime</th>
									<th>Mulai</th>
									<th>Selesai</th>
									<th>Mekanik</th>
									<th>Sparepart</th>
									<th>Qty</th>
								</tr>
							</thead>
							<tbody id="downtimelist">
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="box box-primary">
				<div class="box-header with-border">
					Output
				</div>

				<div class="box-body">
					<form method="POST" id="saveoutput" action="<?=base_url($controller . '/add_output')?>">
						<div class="row hidden" id="btnoutput">
							<div class="col-md-4">
								<a href="javascript:void(0)" class="btn btn-primary btn-block" id="btnstartoutput">Mulai</a>
								<input type="hidden" name="dtmulai" placeholder="Start" class="form-control" id="dtmulaioutput" required value="" />
							</div>

							<div class="col-md-4">
								<a href="javascript:void(0)" onclick="finishcutting()" class="btn btn-primary btn-block">Selesai</a>
							</div>
						</div>
					</form>

					<hr>

					<div class="table-responsive">
						<table class="table table-bordered table-striped table-hover">
							<thead>
								<tr>
									<th>Model</th>
									<th>Komponen</th>
									<th>Mulai</th>
									<th>Selesai</th>
									<th>Aktual</th>
									<th>Reject</th>
								</tr>
							</thead>
							<tbody id="outputlist">
								<!-- <?php
									if (count($dataoutput) == 0) {
								?>
								<tr>
									<td colspan="6" align="center">Data Not Found</td>
								</tr>
								<?php
									} else {
										foreach ($dataoutput as $output) {
								?>
								<tr>
									<td><?=$output->vcmodel?></td>
									<td><?=$output->vckomponen?></td>
									<td><?=$output->dtmulai?></td>
									<td><?=$output->dtselesai?></td>
									<td><?=$output->intpasang?></td>
									<td class="rejectoutput" id="reject<?=$output->intid?>" ondblclick="updatereject(<?=$output->intid?>, <?=$output->intreject?>, <?=$output->intpasang?>)"><?=$output->intreject?></td>
								</tr>
								<?php
									}
									}
								?> -->
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Modal -->
<div id="modalPesan" class="modal fade" role="dialog">
	<div class="modal-dialog ">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Note</h4>
			</div>
			<div class="modal-body">
				<form method="POST" action="<?=base_url($controller . '/add_pesan')?>">
					<div class="row">
						<div class="col-md-12">
							
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<textarea required class="form-control" name="vcpesan" placeholder="Tambahkan catatan di sini" rows="5"></textarea>
						</div>
						<div class="col-md-12">
							<span>Batas Maksimal mengirim pesan 3 kali (Tersisa <span id="sisapesan"></span> Kali)</span>
						</div>
						<div class="col-md-12">
							<button class="btn btn-success margin-top-15" type="submit"><i class="fa fa-send"></i> Kirim</button>
						</div>
					</div>
					<div class="row hidden">
						<div class="col-md-12">
							<h3>Maaf anda sudah mencapai batas  untuk mengirim pesan, terima kasih</h3>
						</div>
					</div>
					
				</form>
			</div>
		</div>
	</div>
</div>

<div id="modalLogout" class="modal fade" role="dialog">
	<div class="modal-dialog ">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Logout</h4>
			</div>
			<div class="modal-body">
				<form method="POST" action="<?=base_url('akses/logoutop')?>" id="logoutoperator">
					<input type="hidden" name="intidop" id="intidop">
					<input type="hidden" name="intkaryawan" id="intkaryawan">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<label>Jam Masuk Kerja</label>
								<input type="text" name="dtmasuk" placeholder="Work In" class="form-control datetimepicker1" id="dtmasuk" required value="" />
		                	</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<label>Jam Pulang Kerja
								</label>
								<input type="text" name="dtpulang" placeholder="Work Out" class="form-control datetimepicker1" id="dtpulang" required value="" />
		                	</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							Notes : Isi sesuai jam mulai dan jam selesai anda bekerja, misalnya pukul 20:00 jam masuk kerja dan pukul 07:00 jam pulang kerja. kemudian klik "Logout".
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<button class="btn btn-danger margin-top-15 pull-right" type="button" onclick="logout()"><i class="fa fa-send"></i> Logout</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div id="modalDowntime" class="modal fade" role="dialog">
	<div class="modal-dialog ">
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Downtime List</h4>
			</div>
			<div class="modal-body" id="listdowntime">
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
			</div>
		</div>
	</div>
</div>

<div id="modalNumpad" class="modal fade" role="dialog">
	<div class="modal-dialog ">
		<!-- Modal content-->
		<div class="modal-content modal-sm">
			<div class="modal-header">
				<button type="button" class="close" onclick="closenumpad()" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="numpadLabel">Downtime List</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-12">
						<input type="text" class="form-control" name="numpadvalue" id="numpadvalue" value="" readonly>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addnum(7)">7</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addnum(8)">8</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addnum(9)">9</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addnum(4)">4</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addnum(5)">5</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addnum(6)">6</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addnum(1)">1</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addnum(2)">2</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addnum(3)">3</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addnum(0)">0</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-danger btn-block" onclick="addnum(999)">Hapus</button>
					</div>

					<div class="col-md-4 margin-top-10" id="savenum">
						
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" onclick="closenumpad()" class="btn btn-default">Batal</button>
			</div>
		</div>
	</div>
</div>

<div id="modalLembur" class="modal fade" role="dialog">
	<div class="modal-dialog ">
		<!-- Modal content-->
		<div class="modal-content modal-sm">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="numpadLabel">Jam Lembur</h4>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addlembur(30)">0.5 Jam</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addlembur(60)">1 Jam</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addlembur(90)">1.5 Jam</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addlembur(120)">2 Jam</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addlembur(150)">2.5 Jam</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addlembur(180)">3 Jam</button>
					</div>

					<div class="col-md-4 margin-top-10">
						<button type="button" class="btn btn-default btn-block" onclick="addlembur(210)">3.5 Jam</button>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn btn-default">Batal</button>
			</div>
		</div>
	</div>
</div>

<div id="modalCutting" class="modal fade" role="dialog">
	<div class="modal-dialog ">
		<!-- Modal content -->
		<div class="modal-content modal-lg">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
				<h4 class="modal-title" id="numpadLabel">Masukkan Komponen</h4>
			</div>
			<div class="modal-body">
				<!-- Component 1 -->
				<div class="row">
					<!-- <div class="col-md-2">
						<div class="form-group">
							<input type="text" name="vcnomorpo" id="vcnomorpo1" class="form-control" placeholder="Nomor PO">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="text" name="vcartikel" id="vcartikel1"  class="form-control" placeholder="Artikel">
						</div>
					</div> -->
					
					<div class="col-md-4">
						<div class="form-group">
							<select name="intmodel" class="form-control" id="intmodel1">
								<option data-nama="" value="0">-- Pilih Model --</option>
								<?php
									foreach ($listmodels as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen1">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct1" class="form-control">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intpasang" id="intpasang1" onclick="setnum(1)" class="form-control" placeholder="Aktual">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intreject" id="intreject1" onclick="setnum(11)" class="form-control" placeholder="Reject">
						</div>
					</div>
				</div>
				
				<!-- Component 2 -->
				<div class="row">
					<!-- <div class="col-md-2">
						<div class="form-group">
							<input type="text" name="vcnomorpo" id="vcnomorpo2" class="form-control" placeholder="Nomor PO">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="text" name="vcartikel" id="vcartikel2"  class="form-control" placeholder="Artikel">
						</div>
					</div> -->

					<div class="col-md-4">
						<div class="form-group">
							<select name="intmodel" class="form-control" id="intmodel2">
								<option data-nama="" value="0">-- Pilih Model --</option>
								<?php
									foreach ($listmodels as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen2">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct2" class="form-control">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intpasang" id="intpasang2" onclick="setnum(2)" class="form-control" placeholder="Aktual">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intreject" id="intreject2" onclick="setnum(22)" class="form-control" placeholder="Reject">
						</div>
					</div>
				</div>
				
				<!-- Component 3 -->
				<div class="row">
					<!-- <div class="col-md-2">
						<div class="form-group">
							<input type="text" name="vcnomorpo" id="vcnomorpo3" class="form-control" placeholder="Nomor PO">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="text" name="vcartikel" id="vcartikel3"  class="form-control" placeholder="Artikel">
						</div>
					</div> -->

					<div class="col-md-4">
						<div class="form-group">
							<select name="intmodel" class="form-control" id="intmodel3">
								<option data-nama="" value="0">-- Pilih Model --</option>
								<?php
									foreach ($listmodels as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen3">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct3" class="form-control">
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intpasang" id="intpasang3" onclick="setnum(3)" class="form-control" placeholder="Aktual">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intreject" id="intreject3" onclick="setnum(33)" class="form-control" placeholder="Reject">
						</div>
					</div>
				</div>
				
				<!-- Component 4 -->
				<div class="row">
					<!-- <div class="col-md-2">
						<div class="form-group">
							<input type="text" name="vcnomorpo" id="vcnomorpo4" class="form-control" placeholder="Nomor PO">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="text" name="vcartikel" id="vcartikel4"  class="form-control" placeholder="Artikel">
						</div>
					</div> -->

					<div class="col-md-4">
						<div class="form-group">
							<select name="intmodel" class="form-control" id="intmodel4">
								<option data-nama="" value="0">-- Pilih Model --</option>
								<?php
									foreach ($listmodels as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-4">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen4">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct4" class="form-control">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intpasang" id="intpasang4" onclick="setnum(4)" class="form-control" placeholder="Aktual">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intreject" id="intreject4" onclick="setnum(44)" class="form-control" placeholder="Reject">
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-12">
						<!-- <div class="row"> -->
							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-default btn-block numpadoutput" onclick="addnumoutput(this,7)">7</button>
							</div>

							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-default btn-block numpadoutput" onclick="addnumoutput(this,8)">8</button>
							</div>

							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-default btn-block numpadoutput" onclick="addnumoutput(this,9)">9</button>
							</div>

							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-default btn-block numpadoutput" onclick="addnumoutput(this,4)">4</button>
							</div>

							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-default btn-block numpadoutput" onclick="addnumoutput(this,5)">5</button>
							</div>

							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-default btn-block numpadoutput" onclick="addnumoutput(this,6)">6</button>
							</div>

							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-default btn-block numpadoutput" onclick="addnumoutput(this,1)">1</button>
							</div>

							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-default btn-block numpadoutput" onclick="addnumoutput(this,2)">2</button>
							</div>

							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-default btn-block numpadoutput" onclick="addnumoutput(this,3)">3</button>
							</div>

							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-default btn-block numpadoutput" onclick="addnumoutput(this,0)">0</button>
							</div>

							<div class="col-md-4 margin-top-10">
								<button type="button" class="btn btn-danger btn-block numpadoutput" onclick="addnumoutput(this,999)">Hapus</button>
							</div>

							<div class="col-md-4 margin-top-10" id="savenum">
								
							</div>
						<!-- </div> -->
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<i class="fa fa-spinner fa-pulse fa-2x fa-fw hidden" id="loadoutput"></i>
				<button type="button" class="btn btn-success" id="simpanoutput" onclick="simpanoutput()">Simpan</button>
				<button type="button" class="btn btn-default hidden" id="reloadbutton" onclick="window.location.reload()">Terjadi Kesalahan, Silahkan Reload!</button>
			</div>
		</div>
	</div>
</div>

<div id="myModal" class="modal fade" role="dialog">
  	<div class="modal-dialog">
		<!-- Modal content-->
		<div class="modal-content modal-sm">
			<div class="modal-body text-center">
				<i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
			</div>
		</div>
  	</div>
</div>

<script type="text/javascript" src="<?=BASE_URL_PATH?>assets/plugins/toastr/toastr.js"></script>


<script type="text/javascript">

	// Set Default Page
	$(function () {
	    $('.select2').select2();

		var _session     = JSON.parse(localStorage.getItem('session'));
		if (_session == null) {
            window.location.replace("<?=base_url('akses/loginop')?>");
        }

		var timeCounting = localStorage.getItem('timeCounting');
		var datacounting = JSON.parse(timeCounting);
		if (datacounting.counttipe == 1) {
			$('#btnoutput').addClass('hidden');
			$('#btnstartdowntime').text(datacounting.dtstart);
			$('#btndowntimefinish').removeClass('hidden');
			$('#dtmulai').val(datacounting.dtstart);
		} else {
			$('#btnoutput').removeClass('hidden');
			$('#btnstartoutput').text(datacounting.dtstart);
			$('#btndowntimefinish').addClass('hidden');
			$('#dtmulaioutput').val(datacounting.dtstart);
			$('#btndowntime').removeAttr('onclick');
		}

		var _intshift    = _session.intshift;
		var _intgedung   = _session.intgedungop;
		var _intmesin    = _session.intmesinop;
		var _intkaryawan = _session.intkaryawan;
		var _vckodemesin = _session.vckodemesin;
		var _vckaryawan  = _session.vckaryawan;
		var _vcnik       = _session.vcnik;
		var _intidop     = _session.intidop;
		var base_url     = '<?=base_url("operator")?>';
		$('#myModal').modal('show');
		$.ajax({
			url: base_url + '/getdatadefault_ajax/' + _intshift + '/' + _intgedung + '/' + _intmesin + '/' + _intkaryawan,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData    = JSON.parse(data);
			var intjamkerja = jsonData.intjamkerja;
			if (intjamkerja == 0) {
				window.location.replace("<?=base_url('akses/loginop')?>");
			}
			$('#intshift').text('- ' + jsonData.intshift);
			$('#vckodemesin').text('ID Mesin : ' + _vckodemesin);
			$('#vcoperator').text('Operator : ' + _vckaryawan + ' ('+ _vcnik +')');
			$('#jamkerja').text('Jam Kerja : ' + jsonData.jammasuk + ' s/d ' + jsonData.jamkeluar);

			var datadowntime = jsonData.datadowntime;
			var _html = '';
			for (var i = 0; i < datadowntime.length; i++) {
				_html += '<tr>';
				_html += '<td>' + datadowntime[i].vcdowntime + '</td>';
				_html += '<td>' + datadowntime[i].dtmulai + '</td>';
				_html += '<td>' + datadowntime[i].dtselesai + '</td>';
				_html += '<td>' + datadowntime[i].vcmekanik + '</td>';
				_html += '<td>' + datadowntime[i].vcsparepart + '</td>';
				_html += '<td>' + datadowntime[i].intjumlah + '</td>';
				_html += '</tr>';
			}
			if (datadowntime.length == 0) {
				_html = '<tr><td colspan="6" align="center">Data Not Found</td></tr>';
			}
			$('#downtimelist').html(_html);

			var dataoutput = jsonData.dataoutput;
			var _htmloutput = '';
			for (var i = 0; i < dataoutput.length; i++) {
				_htmloutput += '<tr>';
				_htmloutput += '<td>' + dataoutput[i].vcmodel + '</td>';
				_htmloutput += '<td>' + dataoutput[i].vckomponen + '</td>';
				_htmloutput += '<td>' + dataoutput[i].dtmulai + '</td>';
				_htmloutput += '<td>' + dataoutput[i].dtselesai + '</td>';
				_htmloutput += '<td>' + dataoutput[i].intpasang + '</td>';
				_htmloutput += '<td>' + dataoutput[i].intreject + '</td>';
				_htmloutput += '</tr>';
			}
			if (dataoutput.length == 0) {
				_htmloutput = '<tr><td colspan="6" align="center">Data Not Found</td></tr>';
			}
			$('#outputlist').html(_htmloutput);
			
			var listmekanik = jsonData.listmekanik;
			var _htmlmekanik = '<option value="0">-- Pilih Mekanik --</option>';
			for (var i = 0; i < listmekanik.length; i++) {
				if (listmekanik[i].vckode == '') {
					var _namamekanik = listmekanik[i].vcnama;
				} else {
					var _namamekanik = listmekanik[i].vckode + ' - ' + listmekanik[i].vcnama;
				}
				_htmlmekanik += '<option value="' + listmekanik[i].intid + '">' + _namamekanik + '</option>';
			}
			$('#intmekanik').html(_htmlmekanik);

			var listsparepart = jsonData.listsparepart;
			var _htmlsparepart = '<option value="0">-- Pilih Sparepart --</option>';
			for (var i = 0; i < listsparepart.length; i++) {
				if (listsparepart[i].vcspesifikasi == '') {
					var _namasparepart = listsparepart[i].vcnama;
				} else {
					var _namasparepart = listsparepart[i].vcspesifikasi + ' - ' + listsparepart[i].vcnama;
				}
				_htmlsparepart += '<option value="' + listsparepart[i].intid + '">' + _namasparepart + '</option>';
			}
			$('#intsparepart').html(_htmlsparepart);

			var listmodels = jsonData.listmodels;
			var _htmlmodels = '<option value="0">-- Pilih Model --</option>';
			for (var i = 0; i < listmodels.length; i++) {
				var _namamodels = listmodels[i].vcnama;
				_htmlmodels += '<option value="' + listmodels[i].intid + '">' + _namamodels + '</option>';
			}
			$('#intmodel1').html(_htmlmodels);
			$('#intmodel2').html(_htmlmodels);
			$('#intmodel3').html(_htmlmodels);
			$('#intmodel4').html(_htmlmodels);
			
			$('#sisapesan').text(jsonData.sisapesan);
			$('#intidop').val(_intidop);
			$('#intkaryawan').val(_intkaryawan);

			$('#myModal').modal('hide');
			console.log(jsonData);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

	});

	// Timer Part
	window.onload = date_time('realtime');

	function date_time(id){
        date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
        d = date.getDate();
        day = date.getDay();
        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();
        if(h<10)
        {
                h = "0"+h;
        }
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
        result = ''+days[day]+', '+d+' '+months[month]+' '+year+' '+h+':'+m+':'+s;
        document.getElementById(id).innerHTML = result;
        setTimeout('date_time("'+id+'");','1000');
        return true;
    }

	// Downtime Part
	$('#inttype_list').change(function(){
		var inttype_downtime = $(this).children('option:selected').data('inttype_downtime');
		$('#inttype_downtime').val(inttype_downtime);
	});

	function getlistdowntime(){
		var base_url = '<?=base_url("operator")?>';
		$.ajax({
			url: base_url + '/getlistdowntime_ajax',
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var _html = '<div class="row">';

			for (var i = 0; i < jsonData.length; i++) {
				var vcdowntime = "'" + jsonData[i].vcnama + "'";
				_html += '<div class="col-md-4">';
				_html += '<a class="btn btn-default btn-block" style="margin: 5px;" onclick="selectdowntime('+ vcdowntime +','+ jsonData[i].intid +','+ jsonData[i].intheader +')">' + jsonData[i].vcnama + '</a>';
				_html += '</div>';
			}

			_html += "</div>"

			$('#listdowntime').html(_html);
			$('#modalDowntime').modal('show');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function selectdowntime(vcdowntime, inttype_list, inttype_downtime){
		$('#btndowntime').text(vcdowntime);
		$('#inttype_list').val(inttype_list);
		$('#inttype_downtime').val(inttype_downtime);

		$('#modalDowntime').modal('hide');
	}

	function simpandowntime(inttipe){
		var timeCounting = localStorage.getItem('timeCounting');
		var datacounting = JSON.parse(timeCounting);

		var datenow = new Date();
		var minutes = datenow.getMinutes();
		if (minutes < 10) { minutes = '0' + minutes}
		var time    = datenow.getHours() + ":" + minutes;

		var inttype_downtime = $('#inttype_downtime').val();
		var inttype_list     = $('#inttype_list').val();
		var dtmulai          = datacounting.dtstart;
		var dtselesai        = time;
		var intmekanik       = $('#intmekanik').val();
		var intsparepart     = $('#intsparepart').val();
		var intjumlah        = $('#intjumlah').val();

		if (inttype_list == 0) {
			swal({
					type: 'error',
					title: 'Downtime Problem Belum Terisi'
				});
		} else if (dtmulai == '') {
			swal({
					type: 'error',
					title: 'Waktu awal belum ditentukan'
				});
		} else if (dtmulai == '') {
			swal({
					type: 'error',
					title: 'Waktu awal belum ditentukan'
				});
		} else if (dtselesai == '') {
			swal({
					type: 'error',
					title: 'Waktu akhir belum ditentukan'
				});
		} else if (intsparepart > 0 && (intjumlah == '' || intjumlah == 0)) {
			swal({
					type: 'error',
					title: 'Masukkan jumlah sparepart'
				});
		} else {
			$('.loadingdt').removeClass('hidden');
			$('.savedtbtn').addClass('hidden');
			var _session     = JSON.parse(localStorage.getItem('session'));
			var _intshift    = _session.intshift;
			var _intgedung   = _session.intgedungop;
			var _intcell     = _session.intcellop;
			var _intmesin    = _session.intmesinop;
			var _intkaryawan = _session.intkaryawan;
			var _vckodemesin = _session.vckodemesin;
			var _vckaryawan  = _session.vckaryawan;
			var _vcnik       = _session.vcnik;
			var _intidop     = _session.intidop;
			var data     = {
							'inttype_downtime': inttype_downtime,
							'inttype_list'    : inttype_list,
							'dtmulai'         : dtmulai,
							'dtselesai'       : dtselesai,
							'intmekanik'      : intmekanik,
							'intsparepart'    : intsparepart,
							'intjumlah'       : intjumlah,
							'intgedung'       : _intgedung,
							'intmesin'        : _intmesin,
							'intoperator'     : _intkaryawan,
							'intcell'         : _intcell,
							'intshift'        : _intshift,
							'intidop'         : _intidop
						}
			var base_url = '<?=base_url("operator")?>';
			$.ajax({
				url: base_url + '/add_downtime_ajax',
				method: "POST",
				data: data
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				var datadowntime = jsonData.datadowntime;
				var _html = '';
				for (var i = 0; i < datadowntime.length; i++) {
					_html += '<tr>';
					_html += '<td>' + datadowntime[i].vcdowntime + '</td>';
					_html += '<td>' + datadowntime[i].dtmulai + '</td>';
					_html += '<td>' + datadowntime[i].dtselesai + '</td>';
					_html += '<td>' + datadowntime[i].vcmekanik + '</td>';
					_html += '<td>' + datadowntime[i].vcsparepart + '</td>';
					_html += '<td>' + datadowntime[i].intjumlah + '</td>';
					_html += '</tr>';
				}

				$('#downtimelist').html(_html);

				$('.loadingdt').addClass('hidden');
				$('.savedtbtn').removeClass('hidden');


				$('#inttype_list').val(0);
				$('#inttype_downtime').val(0);
				$('#btndowntime').text('Pilih Downtime')
				$('#btnstart').text('Mulai');
				// $('#btnstart').click(start);
				$('#btnfinish').text('Downtime Baru');
				$('#dtmulai').val('');
				$('#dtselesai').val('');
				$('#intmekanik').val(0);
				$('#intsparepart').val(0);
				$('#intjumlah').val('');
				// $('#inttype_list').select2().select2('val', '');
				$('#intmekanik').select2().select2('val', '');
				$('#intsparepart').select2().select2('val', '');

				var datenow = new Date();
				var minutes = datenow.getMinutes();
				if (minutes < 10) { minutes = '0' + minutes}
				var time    = datenow.getHours() + ":" + minutes;

				var timeCounting = { 'dtstart': time, 'counttipe': inttipe};
				// Put the object into storage
				localStorage.setItem('timeCounting', JSON.stringify(timeCounting));

				if (inttipe == 1) {
					$('#btnoutput').addClass('hidden');
					$('#btnstartdowntime').text(time);
					$('#btndowntimefinish').removeClass('hidden');
				} else {
					$('#btnstartdowntime').text('Mulai');
					$('#btnoutput').removeClass('hidden');
					$('#btnstartoutput').text(time);
					$('#btndowntimefinish').addClass('hidden');
					$('#btndowntime').removeAttr('onclick');
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});

			// $('#savedowntime').submit();
		}

	}

	function resetdowntime(){
		$('#inttype_list').val(0);
		$('#btnstart').text('Mulai');
		$('#btnstart').click(start);
		$('#btnfinish').text('New Downtime');
		$('#btnfinish').removeAttr('onclick');
		$('#dtmulai').val('');
		$('#dtselesai').val('');
		$('#intmekanik').val(0);
		$('#intsparepart').val(0);
		$('#intjumlah').val('');
		// $('#inttype_list').select2().select2('val', '');
		$('#intmekanik').select2().select2('val', '');
		$('#intsparepart').select2().select2('val', '');

	}

	// OUTput Part
	function simpanoutput(dtselesai){
		var timeCutting = localStorage.getItem('timeCutting');
		var datacutting = JSON.parse(timeCutting);

		var intmodel    = $('#intmodel1').val();
		var intkomponen = $('#intkomponen1').val();
		var decct       = $('#decct1').val();
		var dtmulai     = datacutting.dtstart;
		var dtselesai   = datacutting.dtfinish;
		var intpasang   = $('#intpasang1').val();
		var intreject   = $('#intreject1').val();
		// var vcnomorpo   = $('#vcnomorpo1').val();
		// var vcartikel   = $('#vcartikel1').val();
		
		var intmodel2    = $('#intmodel2').val();
		var intkomponen2 = $('#intkomponen2').val();
		var decct2       = $('#decct2').val();
		var dtmulai2     = datacutting.dtstart;
		var dtselesai2   = datacutting.dtfinish;
		var intpasang2   = $('#intpasang2').val();
		var intreject2   = $('#intreject2').val();
		// var vcnomorpo2   = $('#vcnomorpo2').val();
		// var vcartikel2   = $('#vcartikel2').val();

		var intmodel3    = $('#intmodel3').val();
		var intkomponen3 = $('#intkomponen3').val();
		var decct3       = $('#decct3').val();
		var dtmulai3     = datacutting.dtstart;
		var dtselesai3   = datacutting.dtfinish;
		var intpasang3   = $('#intpasang3').val();
		var intreject3   = $('#intreject3').val();
		// var vcnomorpo3   = $('#vcnomorpo3').val();
		// var vcartikel3   = $('#vcartikel3').val();

		var intmodel4    = $('#intmodel4').val();
		var intkomponen4 = $('#intkomponen4').val();
		var decct4       = $('#decct4').val();
		var dtmulai4     = datacutting.dtstart;
		var dtselesai4   = datacutting.dtfinish;
		var intpasang4   = $('#intpasang4').val();
		var intreject4   = $('#intreject4').val();
		// var vcnomorpo4   = $('#vcnomorpo4').val();
		// var vcartikel4   = $('#vcartikel4').val();

		var datedate   = new Date();
		var day        = datedate.getDate();
		var monthIndex = datedate.getMonth() + 1;
		var year       = datedate.getFullYear();
		var tanggal    = year+'-'+monthIndex+'-'+day;

		var d       = new Date(tanggal+" "+dtselesai);
		var d2      = new Date(tanggal+" "+dtmulai);
		var n       = d.getTime();
		var n2      = d2.getTime();
		var _durasi = ((n - n2)/1000);

		var _target1 = Math.ceil(_durasi / decct);
		var _max1       = _target1 + Math.ceil(_target1 * 0.1);
		var _min1       = _target1 - Math.ceil(_target1 * 0.4);
		
		// Model 2
		if ((intmodel2 > 0 || intkomponen2 > 0) && (intpasang2 == '' || intpasang2 == 0 || intpasang2 < 0)) {
			swal({
					type: 'error',
					title: 'Jumlah Komponen ke-2 Harus Diisi !!'
				});
			return false;
		}

		if ((intmodel2 == 0 || intkomponen2 == 0) && ((intpasang2 > 0) || (intreject2 > 0))) {
			swal({
					type: 'error',
					title: 'Model dan Komponen ke-2 Harus Diisi !!'
				});
			return false;
		}

		if (intmodel2 > 0 ||  intkomponen2 > 0) {
            var _totpasangtemp = parseInt(intpasang) + parseInt(intpasang2);
            var _targetall1    = _durasi / decct;
            var _targetall2    = _durasi / decct2;
            var _persen1       = intpasang / _totpasangtemp;
            var _persen2       = intpasang2 / _totpasangtemp;
            var _target1       = Math.ceil(_persen1 * _targetall1);
            var _target2       = Math.ceil(_persen2 * _targetall2);
            var _max1          = _target1 + Math.ceil(_target1 * 0.1);
            var _min1          = _target1 - Math.ceil(_target1 * 0.4);
            var _max2          = _target2 + Math.ceil(_target2 * 0.1);
            var _min2          = _target2 - Math.ceil(_target2 * 0.4);
		}

		// model 3
		if ((intmodel3 > 0 || intkomponen3 > 0) && (intpasang3 == '' || intpasang3 == 0 || intpasang3 < 0)) {
			swal({
					type: 'error',
					title: 'Jumlah Komponen ke-3 Harus Diisi !!'
				});
			return false;
		}

		if ((intmodel3 == 0 || intkomponen3 == 0) && ((intpasang3 > 0) || (intreject3 > 0))) {
			swal({
					type: 'error',
					title: 'Model dan Komponen ke-3 Harus Diisi !!'
				});
			return false;
		}

		if (intmodel3 > 0 ||  intkomponen3 > 0) {
            var _totpasangtemp = parseInt(intpasang) + parseInt(intpasang2) + parseInt(intpasang3);
            var _targetall1    = _durasi / decct;
            var _targetall2    = _durasi / decct2;
            var _targetall3    = _durasi / decct3;
            var _persen1       = intpasang / _totpasangtemp;
            var _persen2       = intpasang2 / _totpasangtemp;
            var _persen3       = intpasang3 / _totpasangtemp;
            var _target1       = Math.ceil(_persen1 * _targetall1);
            var _target2       = Math.ceil(_persen2 * _targetall2);
            var _target3       = Math.ceil(_persen3 * _targetall3);
            var _max1          = _target1 + Math.ceil(_target1 * 0.1);
            var _min1          = _target1 - Math.ceil(_target1 * 0.4);
            var _max2          = _target2 + Math.ceil(_target2 * 0.1);
            var _min2          = _target2 - Math.ceil(_target2 * 0.4);
            var _max3          = _target3 + Math.ceil(_target3 * 0.1);
            var _min3          = _target3 - Math.ceil(_target3 * 0.4);
		}

		// Model 4 
		if ((intmodel4 > 0 || intkomponen4 > 0) && (intpasang4 == '' || intpasang4 == 0 || intpasang4 < 0)) {
			swal({
					type: 'error',
					title: 'Jumlah Komponen ke-4 Harus Diisi !!'
				});
			return false;
		}

		if ((intmodel4 == 0 || intkomponen4 == 0) && ((intpasang4 > 0) || (intreject4 > 0))) {
			swal({
					type: 'error',
					title: 'Model dan Komponen ke-4 Harus Diisi !!'
				});
			return false;
		}

		if (intmodel4 > 0 ||  intkomponen4 > 0) {
            var _totpasangtemp = parseInt(intpasang) + parseInt(intpasang2) + parseInt(intpasang3) + parseInt(intpasang4);
            var _targetall1    = _durasi / decct;
            var _targetall2    = _durasi / decct2;
            var _targetall3    = _durasi / decct3;
            var _targetall4    = _durasi / decct4;
            var _persen1       = intpasang / _totpasangtemp;
            var _persen2       = intpasang2 / _totpasangtemp;
            var _persen3       = intpasang3 / _totpasangtemp;
            var _persen4       = intpasang4 / _totpasangtemp;
            var _target1       = Math.ceil(_persen1 * _targetall1);
            var _target2       = Math.ceil(_persen2 * _targetall2);
            var _target3       = Math.ceil(_persen3 * _targetall3);
            var _target4       = Math.ceil(_persen4 * _targetall4);
            var _max1          = _target1 + Math.ceil(_target1 * 0.1);
            var _min1          = _target1 - Math.ceil(_target1 * 0.4);
            var _max2          = _target2 + Math.ceil(_target2 * 0.1);
            var _min2          = _target2 - Math.ceil(_target2 * 0.4);
            var _max3          = _target3 + Math.ceil(_target3 * 0.1);
            var _min3          = _target3 - Math.ceil(_target3 * 0.4);
            var _max4          = _target4 + Math.ceil(_target4 * 0.1);
            var _min4          = _target4 - Math.ceil(_target4 * 0.4);
		}

		if (intmodel == 0) {
			swal({
					type: 'error',
					title: 'Model Harus Diisi !!'
				});
		} else if (intkomponen == 0) {
			swal({
					type: 'error',
					title: 'Komponen Harus Diisi !!'
				});
		} else if (dtmulai == '') {
			swal({
					type: 'error',
					title: 'Jam Mulai Harus Diisi !!'
				});
		} else if (dtselesai == '') {
			swal({
					type: 'error',
					title: 'Jam Akhir Harus Diisi !!'
				});
		}else if (intpasang == '' || intpasang == 0 || intpasang < 0) {
			swal({
					type: 'error',
					title: 'Jumlah Actual Harus Diisi, dan tidak boleh kurang dari 0 !!'
				});
		} else {
			var _sop1 = '';
			var _sop2 = '';
			var _sop3 = '';
			var _sop4 = '';
			// console.log(intpasang + ' ' + _min1 + ' ' + _max1 + ' ' + _target1 + ' | ' +intpasang2 + ' ' + _min2 + ' ' + _max2 + ' ' + _target2 + ' | ' +intpasang3 + ' ' + _min3 + ' ' + _max3 + ' ' + _target3 + ' | ' +intpasang4 + ' ' + _min4 + ' ' + _max4 + ' ' + _target4);
			// console.log(_persen1 + ' ' + _persen2 + ' ' + _persen3 + ' ' + _persen4 + ' ' + _totpasangtemp);
			// return false;
			if (intpasang < _min1 || intpasang > _max1) {
				_sop1 = 'Tidak Mengikuti SOP';
				if (intmodel2 > 0 ||  intkomponen2 > 0){
					if (intpasang2 < _min2 || intpasang2 > _max2){
						_sop2 = 'Tidak Mengikuti SOP';
					}
				}

				if (intmodel3 > 0 ||  intkomponen3 > 0){
					if (intpasang3 < _min3 || intpasang3 > _max3){
						_sop3 = 'Tidak Mengikuti SOP';
					}
				}

				if (intmodel4 > 0 ||  intkomponen4 > 0){
					if (intpasang4 < _min4 || intpasang4 > _max4){
						_sop4 = 'Tidak Mengikuti SOP';
					}
				}
				Swal.fire({
					title: 'Anda tidak mengikuti SOP ?',
					text: "",
					type: 'warning',
					showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					confirmButtonText: 'Setuju',
					cancelButtonText: 'Tidak Setuju'
				}).then((result) => {
					if (result.value) {
						saveoutput(_sop1,_sop2,_sop3,_sop4);
					} else {
						return false;
					}
				});
			} else if(intmodel2 > 0 ||  intkomponen2 > 0){
				if (intpasang2 < _min2 || intpasang2 > _max2) {
					_sop2 = 'Tidak Mengikuti SOP';

					if (intmodel3 > 0 ||  intkomponen3 > 0){
						if (intpasang3 < _min3 || intpasang3 > _max3){
							_sop3 = 'Tidak Mengikuti SOP';
						}
					}

					if (intmodel4 > 0 ||  intkomponen4 > 0){
						if (intpasang4 < _min4 || intpasang4 > _max4){
							_sop4 = 'Tidak Mengikuti SOP';
						}
					}
					Swal.fire({
						title: 'Anda tidak mengikuti SOP ?',
						text: "",
						type: 'warning',
						showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						confirmButtonText: 'Setuju',
						cancelButtonText: 'Tidak Setuju'
					}).then((result) => {
						if (result.value) {
							saveoutput(_sop1,_sop2,_sop3,_sop4);
						} else {
							return false;
						}
					});
				} else if(intmodel3 > 0 ||  intkomponen3 > 0){
					if (intpasang3 < _min3 || intpasang3 > _max3) {
						_sop3 = 'Tidak Mengikuti SOP';

						if (intmodel4 > 0 ||  intkomponen4 > 0){
							if (intpasang4 < _min4 || intpasang4 > _max4){
								_sop4 = 'Tidak Mengikuti SOP';
							}
						}
						Swal.fire({
							title: 'Anda tidak mengikuti SOP ?',
							text: "",
							type: 'warning',
							showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							confirmButtonText: 'Setuju',
							cancelButtonText: 'Tidak Setuju'
						}).then((result) => {
							if (result.value) {
								saveoutput(_sop1,_sop2,_sop3,_sop4);
							} else {
								return false;
							}
						});
					} else if(intmodel4 > 0 ||  intkomponen4 > 0){
						if (intpasang4 < _min4 || intpasang4 > _max4) {
							_sop4 = 'Tidak Mengikuti SOP';

							Swal.fire({
								title: 'Anda tidak mengikuti SOP ?',
								text: "",
								type: 'warning',
								showCancelButton: true,
								confirmButtonColor: '#3085d6',
								cancelButtonColor: '#d33',
								confirmButtonText: 'Setuju',
								cancelButtonText: 'Tidak Setuju'
							}).then((result) => {
								if (result.value) {
									saveoutput(_sop1,_sop2,_sop3,_sop4);
								} else {
									return false;
								}
							});
						} else {
							saveoutput(_sop1,_sop2,_sop3,_sop4);
						}
					} else {
						saveoutput(_sop1,_sop2,_sop3,_sop4);
					}
				} else {
					saveoutput(_sop1,_sop2,_sop3,_sop4);
				}
			} else {
				saveoutput(_sop1,_sop2,_sop3,_sop4);
			}
			
		}

	}

	function saveoutput(_sop1,_sop2,_sop3,_sop4){
		var timeCutting = localStorage.getItem('timeCutting');
		var datacutting = JSON.parse(timeCutting);

		var intmodel    = $('#intmodel1').val();
		var intkomponen = $('#intkomponen1').val();
		var decct       = $('#decct1').val();
		var dtmulai     = datacutting.dtstart;
		var dtselesai   = datacutting.dtfinish;
		var intpasang   = $('#intpasang1').val();
		var intreject   = $('#intreject1').val();
		// var vcnomorpo   = $('#vcnomorpo1').val();
		// var vcartikel   = $('#vcartikel1').val();
		
		var intmodel2    = $('#intmodel2').val();
		var intkomponen2 = $('#intkomponen2').val();
		var decct2       = $('#decct2').val();
		var dtmulai2     = datacutting.dtstart;
		var dtselesai2   = datacutting.dtfinish;
		var intpasang2   = $('#intpasang2').val();
		var intreject2   = $('#intreject2').val();
		// var vcnomorpo2   = $('#vcnomorpo2').val();
		// var vcartikel2   = $('#vcartikel2').val();

		var intmodel3    = $('#intmodel3').val();
		var intkomponen3 = $('#intkomponen3').val();
		var decct3       = $('#decct3').val();
		var dtmulai3     = datacutting.dtstart;
		var dtselesai3   = datacutting.dtfinish;
		var intpasang3   = $('#intpasang3').val();
		var intreject3   = $('#intreject3').val();
		// var vcnomorpo3   = $('#vcnomorpo3').val();
		// var vcartikel3   = $('#vcartikel3').val();

		var intmodel4    = $('#intmodel4').val();
		var intkomponen4 = $('#intkomponen4').val();
		var decct4       = $('#decct4').val();
		var dtmulai4     = datacutting.dtstart;
		var dtselesai4   = datacutting.dtfinish;
		var intpasang4   = $('#intpasang4').val();
		var intreject4   = $('#intreject4').val();
		// var vcnomorpo4   = $('#vcnomorpo4').val();
		// var vcartikel4   = $('#vcartikel4').val();

		$('#loadoutput').removeClass('hidden');
		$('#simpanoutput').addClass('hidden');
		setTimeout(function(){
			$('#loadoutput').addClass('hidden');
			$('#reloadbutton').removeClass('hidden');
		}, 6000);

		$('.loadingoutput').removeClass('hidden');
		$('.saveoutputbtn').addClass('hidden');
		// downtime menghitung komponen
		var timeCounting = localStorage.getItem('timeCounting');
		var datacounting = JSON.parse(timeCounting);

		var datenow = new Date();
		var minutes = datenow.getMinutes();
		if (minutes < 10) { minutes = '0' + minutes}
		var timedowntime = datenow.getHours() + ":" + minutes;

		var dtmulaidowntime   = datacounting.dtstart;
		var dtselesaidowntime = timedowntime;

		var timeCounting = { 'dtstart': timedowntime, 'counttipe': 1};
		// Put the object into storage
		localStorage.setItem('timeCounting', JSON.stringify(timeCounting));


		// ----
		var _session     = JSON.parse(localStorage.getItem('session'));
		var _intshift    = _session.intshift;
		var _intgedung   = _session.intgedungop;
		var _intcell     = _session.intcellop;
		var _intmesin    = _session.intmesinop;
		var _intkaryawan = _session.intkaryawan;
		var _vckodemesin = _session.vckodemesin;
		var _vckaryawan  = _session.vckaryawan;
		var _vcnik       = _session.vcnik;
		var _intidop     = _session.intidop;
		var data     = {
						'intmodel'         : intmodel,
						'intkomponen'      : intkomponen,
						'decct'            : decct,
						'intpasang'        : intpasang,
						'intreject'        : intreject,
						'dtmulai'          : dtmulai,
						'dtselesai'        : dtselesai,
						// 'vcnomorpo'        : vcnomorpo,
						// 'vcartikel'        : vcartikel,
						'intmodel2'        : intmodel2,
						'intkomponen2'     : intkomponen2,
						'decct2'           : decct2,
						'intpasang2'       : intpasang2,
						'intreject2'       : intreject2,
						'dtmulai2'         : dtmulai2,
						'dtselesai2'       : dtselesai2,
						// 'vcnomorpo2'       : vcnomorpo2,
						// 'vcartikel2'       : vcartikel2,
						'intmodel3'        : intmodel3,
						'intkomponen3'     : intkomponen3,
						'decct3'           : decct3,
						'intpasang3'       : intpasang3,
						'intreject3'       : intreject3,
						'dtmulai3'         : dtmulai3,
						'dtselesai3'       : dtselesai3,
						// 'vcnomorpo3'       : vcnomorpo3,
						// 'vcartikel3'       : vcartikel3,
						'intmodel4'        : intmodel4,
						'intkomponen4'     : intkomponen4,
						'decct4'           : decct4,
						'intpasang4'       : intpasang4,
						'intreject4'       : intreject4,
						'dtmulai4'         : dtmulai4,
						'dtselesai4'       : dtselesai4,
						// 'vcnomorpo4'       : vcnomorpo4,
						// 'vcartikel4'       : vcartikel4,
						'dtmulaidowntime'  : dtmulaidowntime,
						'dtselesaidowntime': dtselesaidowntime,
						'intgedung'        : _intgedung,
						'intmesin'         : _intmesin,
						'intoperator'      : _intkaryawan,
						'intcell'          : _intcell,
						'intshift'         : _intshift,
						'intidop'          : _intidop,
						'sop1'             : _sop1,
						'sop2'             : _sop2,
						'sop3'             : _sop3,
						'sop4'             : _sop4
					};
		var base_url = '<?=base_url("operator")?>';
		$.ajax({
			url: base_url + '/add_output_ajax',
			method: "POST",
			data: data
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var dataoutput = jsonData.dataoutput;
			var _html = '';
			for (var i = 0; i < dataoutput.length; i++) {
				_html += '<tr>';
				_html += '<td>' + dataoutput[i].vcmodel + '</td>';
				_html += '<td>' + dataoutput[i].vckomponen + '</td>';
				_html += '<td>' + dataoutput[i].dtmulai + '</td>';
				_html += '<td>' + dataoutput[i].dtselesai + '</td>';
				_html += '<td>' + dataoutput[i].intpasang + '</td>';
				// _html += '<td class="rejectoutput">' + dataoutput[i].intreject + '</td>';
				_html += '<td class="rejectoutput" id="reject'+dataoutput[i].intid+'" ondblclick="updatereject('+dataoutput[i].intid+', '+dataoutput[i].intreject+', '+dataoutput[i].intpasang+')">'+dataoutput[i].intreject+'</td>'
				_html += '</tr>';
			}

			$('#outputlist').html(_html);

			var datadowntime = jsonData.datadowntime;
			var _htmldt = '';
			for (var i = 0; i < datadowntime.length; i++) {
				_htmldt += '<tr>';
				_htmldt += '<td>' + datadowntime[i].vcdowntime + '</td>';
				_htmldt += '<td>' + datadowntime[i].dtmulai + '</td>';
				_htmldt += '<td>' + datadowntime[i].dtselesai + '</td>';
				_htmldt += '<td>' + datadowntime[i].vcmekanik + '</td>';
				_htmldt += '<td>' + datadowntime[i].vcsparepart + '</td>';
				_htmldt += '<td>' + datadowntime[i].intjumlah + '</td>';
				_htmldt += '</tr>';
			}

			$('#downtimelist').html(_htmldt);

			$('.loadingoutput').addClass('hidden');
			$('.saveoutputbtn').removeClass('hidden');


			$('#intmodel1').val(0);
			$('#intkomponen1').val(0);
			$('#intpasang1').val('');
			$('#intreject1').val('');
			$('#dtmulaioutput').val('');
			$('#dtselesaioutput').val('');
			$('#intmodel1').select2().select2('val', '');
			$('#intkomponen1').select2().select2('val', '');

			$('#intmodel2').val(0);
			$('#intkomponen2').val(0);
			$('#intpasang2').val('');
			$('#intreject2').val('');
			$('#dtmulaioutput').val('');
			$('#dtselesaioutput').val('');
			$('#intmodel2').select2().select2('val', '');
			$('#intkomponen2').select2().select2('val', '');

			$('#intmodel3').val(0);
			$('#intkomponen3').val(0);
			$('#intpasang3').val('');
			$('#intreject3').val('');
			$('#dtmulaioutput').val('');
			$('#dtselesaioutput').val('');
			$('#intmodel3').select2().select2('val', '');
			$('#intkomponen3').select2().select2('val', '');

			$('#intmodel4').val(0);
			$('#intkomponen4').val(0);
			$('#intpasang4').val('');
			$('#intreject4').val('');
			$('#dtmulaioutput').val('');
			$('#dtselesaioutput').val('');
			$('#intmodel4').select2().select2('val', '');
			$('#intkomponen4').select2().select2('val', '');

			$('#btnoutput').addClass('hidden');
			$('#btnstartdowntime').text(timedowntime);
			$('#btndowntimefinish').removeClass('hidden');

			$('#loadoutput').addClass('hidden');
			$('#reloadbutton').addClass('hidden');
			$('#modalCutting').modal('hide');

		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function resetoutput(){
		$('#intmodel').val(0);
		$('#intkomponen').val(0);
		$('#intpasang').val('');
		$('#intreject').val('');
		$('#dtmulaioutput').val('');
		$('#dtselesaioutput').val('');
		$('#intmodel').select2().select2('val', '');
		$('#intkomponen').select2().select2('val', '');

	}

	$('#intmodel1').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("operator")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" data-intid="' + jsonData[i].intid + '" value="' + jsonData[i].intkomponen + '">' + jsonData[i].vckomponen + '</option>'
			}

			$('#intkomponen1').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intmodel2').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("operator")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" data-intid="' + jsonData[i].intid + '" value="' + jsonData[i].intkomponen + '">' + jsonData[i].vckomponen + '</option>'
			}

			$('#intkomponen2').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intmodel3').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("operator")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" data-intid="' + jsonData[i].intid + '" value="' + jsonData[i].intkomponen + '">' + jsonData[i].vckomponen + '</option>'
			}

			$('#intkomponen3').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intmodel4').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("operator")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" data-intid="' + jsonData[i].intid + '" value="' + jsonData[i].intkomponen + '">' + jsonData[i].vckomponen + '</option>'
			}

			$('#intkomponen4').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intkomponen1').change(function(){
		var intkomponen = $(this).val();
		var intid       = $(this).children('option:selected').data('intid');
		var decct       = $(this).children('option:selected').data('ct');
		var base_url    = '<?=base_url("operator")?>';

		$('#decct1').val(decct);
		
		// $.ajax({
		// 	url: base_url + '/getkomponenct_ajax/' + intid,
		// 	method: "GET"
		// })
		// .done(function( data ) {
		// 	var jsonData = JSON.parse(data);
		// 	var html = '<option data-nama="" value="0">-- Pilih Layer --</option>';
		// 	for (var i = 0; i < jsonData.length; i++) {
		// 		html += '<option data-ct="' + jsonData[i].deccycle_time + '" >' + jsonData[i].vcnama + '</option>'
		// 	}

		// 	$('#intlayer1').html(html)
		// })
		// .fail(function( jqXHR, statusText ) {
		// 	alert( "Request failed: " + jqXHR.status );
		// });
	});

	$('#intkomponen2').change(function(){
		var intkomponen = $(this).val();
		var intid       = $(this).children('option:selected').data('intid');
		var decct       = $(this).children('option:selected').data('ct');
		var base_url    = '<?=base_url("operator")?>';

		$('#decct2').val(decct);
		
		// $.ajax({
		// 	url: base_url + '/getkomponenct_ajax/' + intid,
		// 	method: "GET"
		// })
		// .done(function( data ) {
		// 	var jsonData = JSON.parse(data);
		// 	var html = '<option data-nama="" value="0">-- Pilih Layer --</option>';
		// 	for (var i = 0; i < jsonData.length; i++) {
		// 		html += '<option data-ct="' + jsonData[i].deccycle_time + '" >' + jsonData[i].vcnama + '</option>'
		// 	}

		// 	$('#intlayer2').html(html)
		// })
		// .fail(function( jqXHR, statusText ) {
		// 	alert( "Request failed: " + jqXHR.status );
		// });
	});

	$('#intkomponen3').change(function(){
		var intkomponen = $(this).val();
		var intid       = $(this).children('option:selected').data('intid');
		var decct       = $(this).children('option:selected').data('ct');
		var base_url    = '<?=base_url("operator")?>';

		$('#decct3').val(decct);
		
		// $.ajax({
		// 	url: base_url + '/getkomponenct_ajax/' + intid,
		// 	method: "GET"
		// })
		// .done(function( data ) {
		// 	var jsonData = JSON.parse(data);
		// 	var html = '<option data-nama="" value="0">-- Pilih Layer --</option>';
		// 	for (var i = 0; i < jsonData.length; i++) {
		// 		html += '<option data-ct="' + jsonData[i].deccycle_time + '" >' + jsonData[i].vcnama + '</option>'
		// 	}

		// 	$('#intlayer3').html(html)
		// })
		// .fail(function( jqXHR, statusText ) {
		// 	alert( "Request failed: " + jqXHR.status );
		// });
	});

	$('#intkomponen4').change(function(){
		var intkomponen = $(this).val();
		var intid       = $(this).children('option:selected').data('intid');
		var decct       = $(this).children('option:selected').data('ct');
		var base_url    = '<?=base_url("operator")?>';

		$('#decct4').val(decct);
		
		// $.ajax({
		// 	url: base_url + '/getkomponenct_ajax/' + intid,
		// 	method: "GET"
		// })
		// .done(function( data ) {
		// 	var jsonData = JSON.parse(data);
		// 	var html = '<option data-nama="" value="0">-- Pilih Layer --</option>';
		// 	for (var i = 0; i < jsonData.length; i++) {
		// 		html += '<option data-ct="' + jsonData[i].deccycle_time + '" >' + jsonData[i].vcnama + '</option>'
		// 	}

		// 	$('#intlayer4').html(html)
		// })
		// .fail(function( jqXHR, statusText ) {
		// 	alert( "Request failed: " + jqXHR.status );
		// });
	});

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
	});

	// Finish Cutting Part
	function finishcutting(){
		var datenow = new Date();
		var minutes = datenow.getMinutes();
		if (minutes < 10) { minutes = '0' + minutes}
		var time    = datenow.getHours() + ":" + minutes;

		var timeCounting = localStorage.getItem('timeCounting');
		var datacounting = JSON.parse(timeCounting);

		var timeCutting = { 'dtstart': datacounting.dtstart, 'dtfinish': time};
		localStorage.setItem('timeCutting', JSON.stringify(timeCutting));

		var timeCounting = { 'dtstart': time, 'counttipe': 1};
        localStorage.setItem('timeCounting', JSON.stringify(timeCounting));

		$('#loadoutput').addClass('hidden');
		$('#reloadbutton').addClass('hidden');
		$('#simpanoutput').removeClass('hidden');
		$('#modalCutting').modal({ backdrop: 'static' },'show');		

		$('#btnoutput').addClass('hidden');
		$('#btnstartdowntime').text(time);
		$('#btndowntimefinish').removeClass('hidden');
		$('#btndowntime').attr('onClick', 'getlistdowntime()');

		// var _numpad = '<button type="button" class="btn btn-success btn-block" onclick="savenum('+1+')">Done</button>';
		// var label = (1 == 1) ? 'Input Pairs' : 'Input Reject';
		// $('#savenum').html(_numpad);
		// $('#numpadLabel').text(label);
		// $('#modalNumpad').modal('show');
	}

	// Numpad Part
	function shownumpad(id){
		var _numpad = '<button type="button" class="btn btn-success btn-block" onclick="savenum('+id+')">Done</button>';
		var label = (id == 1) ? 'Input Pairs' : 'Input Reject';
		$('#savenum').html(_numpad);
		$('#numpadLabel').text(label);
		$('#modalNumpad').modal('show');
	}

	function addnum(value){
		if (value == 999) {
			$('#numpadvalue').val('');
		} else {
			var numpadvalue = $('#numpadvalue').val();
			numpadvalue     += value;
			$('#numpadvalue').val(numpadvalue);
		}
	}

	function savenum(id){
		var numpadvalue = $('#numpadvalue').val();
		if (id == 1) {
			$('#intpasang').val(numpadvalue);
			$('#numpadvalue').val('');
			$('#modalNumpad').modal('hide');
		} else if (id == 2) {
			$('#intreject').val(numpadvalue);
			$('#numpadvalue').val('');
			$('#modalNumpad').modal('hide');
		}
	}

	function addlembur(intjamlembur){
		var base_url = '<?=base_url("operator")?>';
		var _session     = JSON.parse(localStorage.getItem('session'));
		var _intshift    = _session.intshift;
		var _intgedung   = _session.intgedungop;
		var _intcell     = _session.intcellop;
		var _intmesin    = _session.intmesinop;
		var _intkaryawan = _session.intkaryawan;
		var _vckodemesin = _session.vckodemesin;
		var _vckaryawan  = _session.vckaryawan;
		var _vcnik       = _session.vcnik;
		var _intidop     = _session.intidop;
		$.ajax({
			url: base_url + '/addlembur_ajax/' + intjamlembur + '/' + _intshift + '/' + _intmesin + '/' + _intgedung,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var jamkerja = 'Jam Kerja : ' + jsonData.jammasuk + ' s/d ' + jsonData.jamkeluar;
			$('#jamkerja').text(jamkerja);
			$('#modalLembur').modal('hide');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	}

	function closenumpad(){
		$('#numpadvalue').val('');
		$('#modalNumpad').modal('hide');
	}

	// Numpad Output Part
	function addnumoutput(elem,value){
		var dataId = $(elem).data("type");
		
		if (value == 999) {
			if (dataId == 1) {
				$('#intpasang1').val('');	
			} else if (dataId == 2) {
				$('#intpasang2').val('');
			} else if (dataId == 3) {
				$('#intpasang3').val('');
			} else if (dataId == 4) {
				$('#intpasang4').val('');
			} else if (dataId == 11) {
				$('#intreject1').val('');
			} else if (dataId == 22) {
				$('#intreject2').val('');
			} else if (dataId == 33) {
				$('#intreject3').val('');
			} else if (dataId == 44) {
				$('#intreject4').val('');
			}
		} else {
			// var numpadvalue = $('#numpadvalue').val();
			// numpadvalue     += value;
			// $('#numpadvalue').val(numpadvalue);
			if (dataId == 1) {
				var _intpasang  = $('#intpasang1').val();
				    _intpasang += value;
				$('#intpasang1').val(_intpasang);
			} else if (dataId == 2) {
				var _intpasang  = $('#intpasang2').val();
				    _intpasang += value;
				$('#intpasang2').val(_intpasang);
			} else if (dataId == 3) {
				var _intpasang  = $('#intpasang3').val();
				    _intpasang += value;
				$('#intpasang3').val(_intpasang);
			} else if (dataId == 4) {
				var _intpasang  = $('#intpasang4').val();
				    _intpasang += value;
				$('#intpasang4').val(_intpasang);
			} else if (dataId == 11) {
				var _intreject  = $('#intreject1').val();
				    _intreject += value;
				$('#intreject1').val(_intreject);
			} else if (dataId == 22) {
				var _intreject  = $('#intreject2').val();
				    _intreject += value;
				$('#intreject2').val(_intreject);
			} else if (dataId == 33) {
				var _intreject  = $('#intreject3').val();
				    _intreject += value;
				$('#intreject3').val(_intreject);
			} else if (dataId == 44) {
				var _intreject  = $('#intreject4').val();
				    _intreject += value;
				$('#intreject4').val(_intreject);
			}
		}
	}

	function setnum(id){
		$('.numpadoutput').data('type', id);
	}

	// Update Reject Parr
	function updatereject(intid, inttarget, intpasang){
		// alert(intid + ' ' + inttarget);
		// var _html = '<input type="number" id="val'+intid+'" onblur="savereject('+intid+')" onkeyup="test('+intid+')" value="'+inttarget+'" name="intreject" size="2" class="intrejectload" placeholder="Reject">';
		// $('#reject'+intid).html(_html);
		// $("#val"+intid).focus();

		var _numpad = '<button type="button" class="btn btn-success btn-block" onclick="savereject('+intid+', '+intpasang+')">Simpan</button>';
		$('#savenum').html(_numpad);
		$('#numpadLabel').text('Masukkan Reject');
		$('#modalNumpad').modal('show');
	}

	function savereject(intid, intpasang){
		// var _val = $("#val"+intid).val();
		// alert(_val);

		var numpadvalue = $('#numpadvalue').val();
		if (numpadvalue != '') {
			if (numpadvalue > intpasang) {
				swal({
					type: 'error',
					title: 'Komponen reject melebihi Actual'
				});
			} else {
				$('#reject'+intid).html(numpadvalue);	
				var base_url = '<?=base_url("operator")?>';
				$.ajax({
					url: base_url + '/updatereject_ajax/'+intid+'/'+numpadvalue,
					method: "GET"
				})
				.done(function( data ) {
					$('#numpadvalue').val('');
					$('#modalNumpad').modal('hide');
				})
				.fail(function( jqXHR, statusText ) {
					alert( "Request failed: " + jqXHR.status );
				});
			}
		} else {
			$('#numpadvalue').val('');
			$('#modalNumpad').modal('hide');
		}
	}

	function logout(){
		var _dtmasuk = $('#dtmasuk').val();
		var _dtpulang = $('#dtpulang').val();

		if (_dtmasuk == '' || _dtpulang == '') {
			alert('Masukkan Jam Masuk dan Jam Pulang');
		} else {
			localStorage.removeItem('session');
			$('#logoutoperator').submit();
		}
	}
</script>