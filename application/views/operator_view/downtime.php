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
									<th>PO</th>
									<th>Mulai</th>
									<th>Selesai</th>
									<th>Aktual</th>
									<th>Reject</th>
									<th>Keterangan</th>
								</tr>
							</thead>
							<tbody id="outputlist">
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
							<input type="hidden" id="intkaryawan" name="intkaryawan">
							<input type="hidden" id="intmesinop" name="intmesinop">
							<input type="hidden" id="intidop" name="intidop">
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
					<input type="hidden" name="intidop" id="intidop2">
					<input type="hidden" name="intkaryawan" id="intkaryawan2">
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
	<div class="modal-dialog" style="width:1250px;">
		<!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
				<h4 class="modal-title" id="numpadLabel">Masukkan Komponen</h4>
			</div>
			<div class="modal-body">
				<!-- Title -->
				<div class="row">
					<div class="col-md-2">
						<h4>PO (4 digit terakhir)</h4>
					</div>

					<div class="col-md-2">
						<h4>Model</h4>
					</div>

					<div class="col-md-3">
						<h4>Komponen</h4>
					</div>

					<div class="col-md-2">
						<h4>Keterangan</h4>
					</div>

					<div class="col-md-2">
						<h4>Aktual</h4>
					</div>

					<div class="col-md-1">
						<h4>Reject</h4>
					</div>
				</div>
				<!-- Component 1 -->
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<input type="text" name="vcpo" id="vcpo1" onclick="setnum(111)" class="form-control" placeholder="PO">
								</div>
							</div>
							<div class="col-md-2 col-md-pull-2">
								<div class="form-group">
									<input type="checkbox" name="cekpo" id="cekpo1" class="cekpo1" style="display:inline-block; position:relative; width:50px; height:25px; "> 
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intmodel" class="form-control" id="intmodel1">
								<option data-nama="" value="0">-- Pilih Model --</option>
							</select>
							<input type="hidden" name="intqty" id="intqty1" class="form-control">
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen1">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct1" class="form-control">
							<input type="hidden" name="intlayer" id="intlayer1" class="form-control">
						</div>
					</div>
					<div class="col-md-1 col-md-pull-0">
						<div class="form-group">
							<button type="button" class="btn btn-block btn-default disabled" id="hasil1">0/0</button>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<select name="intremark" class="form-control" id="intremark1">
								<option data-nama="" value="0">-- Pilih Keterangan --</option>
								<?php
									foreach ($listremark as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intpasang" id="intpasang1" onclick="setnum(1)" class="form-control" placeholder="Aktual" disabled>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group">
							<input type="text" name="intreject" id="intreject1" onclick="setnum(11)" class="form-control" placeholder="Reject">
						</div>
					</div>
				</div>
				<!-- Component 2 -->
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<input type="text" name="vcpo" id="vcpo2" onclick="setnum(222)" class="form-control" placeholder="PO">
								</div>
							</div>
							<div class="col-md-2 col-md-pull-2">
								<div class="form-group">
									<input type="checkbox" name="cekpo" id="cekpo2" class="cekpo2" style="display:inline-block; position:relative; width:50px; height:25px; "> 
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intmodel" class="form-control" id="intmodel2">
								<option data-nama="" value="0">-- Pilih Model --</option>
							</select>
							<input type="hidden" name="intqty" id="intqty2" class="form-control">
						</div>
					</div>

					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen2">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct2" class="form-control">
							<input type="hidden" name="intlayer" id="intlayer2" class="form-control">
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<button type="button" class="btn btn-block btn-default disabled" id="hasil2">0/0</button>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<select name="intremark" class="form-control" id="intremark2">
								<option data-nama="" value="0">-- Pilih Keterangan --</option>
								<?php
									foreach ($listremark as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intpasang" id="intpasang2" onclick="setnum(2)" class="form-control" placeholder="Aktual" disabled>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group">
							<input type="text" name="intreject" id="intreject2" onclick="setnum(22)" class="form-control" placeholder="Reject">
						</div>
					</div>
				</div>
				<!-- Component 3 -->
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<input type="text" name="vcpo" id="vcpo3" onclick="setnum(333)" class="form-control" placeholder="PO">
								</div>
							</div>
							<div class="col-md-2 col-md-pull-2">
								<div class="form-group">
									<input type="checkbox" name="cekpo" id="cekpo3" class="cekpo3" style="display:inline-block; position:relative; width:50px; height:25px; "> 
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intmodel" class="form-control" id="intmodel3">
								<option data-nama="" value="0">-- Pilih Model --</option>
							</select>
							<input type="hidden" name="intqty" id="intqty3" class="form-control">
						</div>
					</div>

					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen3">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct3" class="form-control">
							<input type="hidden" name="intlayer" id="intlayer3" class="form-control">
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<button type="button" class="btn btn-block btn-default disabled" id="hasil3">0/0</button>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<select name="intremark" class="form-control" id="intremark3">
								<option data-nama="" value="0">-- Pilih Keterangan --</option>
								<?php
									foreach ($listremark as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intpasang" id="intpasang3" onclick="setnum(3)" class="form-control" placeholder="Aktual" disabled>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group">
							<input type="text" name="intreject" id="intreject3" onclick="setnum(33)" class="form-control" placeholder="Reject">
						</div>
					</div>
				</div>
				<!-- Component 4 -->
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<input type="text" name="vcpo" id="vcpo4" onclick="setnum(444)" class="form-control" placeholder="PO">
								</div>
							</div>
							<div class="col-md-2 col-md-pull-2">
								<div class="form-group">
									<input type="checkbox" name="cekpo" id="cekpo4" class="cekpo4" style="display:inline-block; position:relative; width:50px; height:25px; "> 
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intmodel" class="form-control" id="intmodel4">
								<option data-nama="" value="0">-- Pilih Model --</option>
							</select>
							<input type="hidden" name="intqty" id="intqty4" class="form-control">
						</div>
					</div>

					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen4">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct4" class="form-control">
							<input type="hidden" name="intlayer" id="intlayer4" class="form-control">
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<button type="button" class="btn btn-block btn-default disabled" id="hasil4">0/0</button>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<select name="intremark" class="form-control" id="intremark4">
								<option data-nama="" value="0">-- Pilih Keterangan --</option>
								<?php
									foreach ($listremark as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intpasang" id="intpasang4" onclick="setnum(4)" class="form-control" placeholder="Aktual" disabled>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group">
							<input type="text" name="intreject" id="intreject4" onclick="setnum(44)" class="form-control" placeholder="Reject">
						</div>
					</div>
				</div>
				<!-- Component 5 -->
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<input type="text" name="vcpo" id="vcpo5" onclick="setnum(555)" class="form-control" placeholder="PO">
								</div>
							</div>
							<div class="col-md-2 col-md-pull-2">
								<div class="form-group">
									<input type="checkbox" name="cekpo" id="cekpo5" class="cekpo5" style="display:inline-block; position:relative; width:50px; height:25px; "> 
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intmodel" class="form-control" id="intmodel5">
								<option data-nama="" value="0">-- Pilih Model --</option>
							</select>
							<input type="hidden" name="intqty" id="intqty5" class="form-control">
						</div>
					</div>

					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen5">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct5" class="form-control">
							<input type="hidden" name="intlayer" id="intlayer5" class="form-control">
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<button type="button" class="btn btn-block btn-default disabled" id="hasil5">0/0</button>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<select name="intremark" class="form-control" id="intremark5">
								<option data-nama="" value="0">-- Pilih Keterangan --</option>
								<?php
									foreach ($listremark as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intpasang" id="intpasang5" onclick="setnum(5)" class="form-control" placeholder="Aktual" disabled>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group">
							<input type="text" name="intreject" id="intreject5" onclick="setnum(55)" class="form-control" placeholder="Reject">
						</div>
					</div>
				</div>
				<!-- Component 6 -->
				<div class="row">
					<div class="col-md-2">
						<div class="row">
							<div class="col-md-10">
								<div class="form-group">
									<input type="text" name="vcpo" id="vcpo6" onclick="setnum(666)" class="form-control" placeholder="PO">
								</div>
							</div>
							<div class="col-md-2 col-md-pull-2">
								<div class="form-group">
									<input type="checkbox" name="cekpo" id="cekpo6" class="cekpo6" style="display:inline-block; position:relative; width:50px; height:25px; "> 
								</div>
							</div>
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intmodel" class="form-control" id="intmodel6">
								<option data-nama="" value="0">-- Pilih Model --</option>
							</select>
							<input type="hidden" name="intqty" id="intqty6" class="form-control">
						</div>
					</div>

					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen6">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct6" class="form-control">
							<input type="hidden" name="intlayer" id="intlayer6" class="form-control">
						</div>
					</div>
					<div class="col-md-1">
						<div class="form-group">
							<button type="button" class="btn btn-block btn-default disabled" id="hasil6">0/0</button>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<select name="intremark" class="form-control" id="intremark6">
								<option data-nama="" value="0">-- Pilih Keterangan --</option>
								<?php
									foreach ($listremark as $opt) {
								?>
								<option value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
								<?php
									}
								?>
							</select>
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<input type="number" name="intpasang" id="intpasang6" onclick="setnum(6)" class="form-control" placeholder="Aktual" disabled>
						</div>
					</div>

					<div class="col-md-1">
						<div class="form-group">
							<input type="text" name="intreject" id="intreject6" onclick="setnum(66)" class="form-control" placeholder="Reject">
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

			<!-- Footer -->
			<div class="modal-footer">
				<i class="fa fa-spinner fa-pulse fa-2x fa-fw hidden" id="loadoutput"></i>
				<button type="button" class="btn btn-danger" id="canceloutput" onclick="canceloutput()">Cancel</button>
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

		var _session      = JSON.parse(localStorage.getItem('session'));
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
				if (dataoutput[i].vcketerangan != '') {
					var _warna = 'danger';
				} else {
					var _warna = '';
				}
				_htmloutput += '<tr class="'+_warna+'">';
				_htmloutput += '<td>' + dataoutput[i].vcmodel + '</td>';
				_htmloutput += '<td>' + dataoutput[i].vckomponen + '</td>';
				_htmloutput += '<td>' + dataoutput[i].vcpo.substring(dataoutput[i].vcpo.length - 4, dataoutput[i].vcpo.length) + '</td>';
				_htmloutput += '<td>' + dataoutput[i].dtmulai + '</td>';
				_htmloutput += '<td>' + dataoutput[i].dtselesai + '</td>';
				_htmloutput += '<td>' + dataoutput[i].intpasang + '</td>';
				_htmloutput += '<td>' + dataoutput[i].intreject + '</td>';
				_htmloutput += '<td>' + dataoutput[i].vcketerangan + '</td>';
				_htmloutput += '</tr>';
			}
			if (dataoutput.length == 0) {
				_htmloutput = '<tr><td colspan="8" align="center">Data Not Found</td></tr>';
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

			// var listmodels = jsonData.listmodels;
			// var _htmlmodels = '<option value="0">-- Pilih Model --</option>';
			// for (var i = 0; i < listmodels.length; i++) {
			// 	var _namamodels = listmodels[i].vcnama;
			// 	_htmlmodels += '<option value="' + listmodels[i].intid + '">' + _namamodels + '</option>';
			// }
			// $('#intmodel1').html(_htmlmodels);
			// $('#intmodel2').html(_htmlmodels);
			// $('#intmodel3').html(_htmlmodels);
			// $('#intmodel4').html(_htmlmodels);
			// $('#intmodel5').html(_htmlmodels);
			// $('#intmodel6').html(_htmlmodels);

			var listremark = jsonData.listremark;
			var _htmlremark = '<option value="0">-- Pilih Keterangan --</option>';
			for (var i = 0; i < listremark.length; i++) {
				var _namaremark = listremark[i].vcnama;
				_htmlremark += '<option value="' + listremark[i].intid + '">' + _namaremark + '</option>';
			}
			$('#intremark1').html(_htmlremark);
			$('#intremark2').html(_htmlremark);
			$('#intremark3').html(_htmlremark);
			$('#intremark4').html(_htmlremark);
			$('#intremark5').html(_htmlremark);
			$('#intremark6').html(_htmlremark);
			
			$('#sisapesan').text(jsonData.sisapesan);
			$('#intidop').val(_intidop);
			$('#intidop2').val(_intidop);
			$('#intkaryawan').val(_intkaryawan);
			$('#intkaryawan2').val(_intkaryawan);
			$('#intmesinop').val(_intmesin);

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
        months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
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
        var seconds = datenow.getSeconds();
        if (minutes < 10) { minutes = '0' + minutes}
        if (seconds < 10) { seconds = '0' + seconds}
        var time    = datenow.getHours() + ":" + minutes + ":" + seconds;

		var inttype_downtime = $('#inttype_downtime').val();
		var inttype_list     = $('#inttype_list').val();
		var dtmulai          = datacounting.dtstart;
		//var dtselesai        = time;
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
		// } else if (dtselesai == '') {
		// 	swal({
		// 			type: 'error',
		// 			title: 'Waktu akhir belum ditentukan'
		// 		});
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
							//'dtmulai'         : dtmulai,
							// 'dtselesai'       : dtselesai,
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
				url: base_url + '/add_downtime_ajax/' + inttipe,
				method: "POST",
				data: data
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				var dttime = jsonData.dttime;
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
		        var seconds = datenow.getSeconds();
		        if (minutes < 10) { minutes = '0' + minutes}
		        if (seconds < 10) { seconds = '0' + seconds}
		        var time    = datenow.getHours() + ":" + minutes + ":" + seconds;
				var timeCounting = { 'dtstart': dttime, 'counttipe': inttipe, 'dtstart_temp' : dttime};
				// Put the object into storage
				localStorage.setItem('timeCounting', JSON.stringify(timeCounting));

				if (inttipe == 1) {
					$('#btnoutput').addClass('hidden');
					$('#btnstartdowntime').text(dttime);
					$('#btndowntimefinish').removeClass('hidden');
				} else {
					$('#btnstartdowntime').text('Mulai');
					$('#btnoutput').removeClass('hidden');
					$('#btnstartoutput').text(dttime);
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

	//cancel output
	function canceloutput() {
		var timeCounting = localStorage.getItem('timeCounting');
		var datacounting = JSON.parse(timeCounting);

		var timeCounting = { 'dtstart': datacounting.dtstart_temp, 'counttipe': 2 , 'dtstart_temp': datacounting.dtstart_temp};
		localStorage.setItem('timeCounting', JSON.stringify(timeCounting));

		$('#btnstartdowntime').text('Mulai');
		$('#btnoutput').removeClass('hidden');
		$('#btnstartoutput').text(datacounting.dtstart_temp);
		$('#btndowntimefinish').addClass('hidden');
		$('#btndowntime').removeAttr('onclick');

		$('#loadoutput').addClass('hidden');
		$('#reloadbutton').addClass('hidden');
		$('#modalCutting').modal('hide');
		
		$('#vcpo1').val('');
		var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
		$('#intmodel1').html(htmlmd);
		var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
		$('#intkomponen1').html(htmlkp);
		$('#decct1').val('');
		$('#intlayer1').val('');
		$('#intremark1').val(0);
		$('#intpasang1').val('');
		$('#intreject1').val('');
		$('#dtmulaioutput').val('');
		$('#dtselesaioutput').val('');
		$('#intremark1').select2().select2('val', '');
		$('#intqty1').val('');
		$('#hasil1').text('0/0');
		document.getElementById("intpasang1").disabled = true;
		document.getElementById("vcpo1").disabled = false;
		document.getElementById("cekpo1").checked = false;

		$('#vcpo2').val('');
		var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
		$('#intmodel2').html(htmlmd);
		var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
		$('#intkomponen2').html(htmlkp);
		$('#decct2').val('');
		$('#intlayer2').val('');
		$('#intremark2').val(0);
		$('#intpasang2').val('');
		$('#intreject2').val('');
		$('#dtmulaioutput').val('');
		$('#dtselesaioutput').val('');
		$('#intremark2').select2().select2('val', '');
		$('#intqty2').val('');
		$('#hasil2').text('0/0');
		document.getElementById("intpasang2").disabled = true;
		document.getElementById("vcpo2").disabled = false;
		document.getElementById("cekpo2").checked = false;

		$('#vcpo3').val('');
		var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
		$('#intmodel3').html(htmlmd);
		var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
		$('#intkomponen3').html(htmlkp);
		$('#decct3').val('');
		$('#intlayer3').val('');
		$('#intremark3').val(0);
		$('#intpasang3').val('');
		$('#intreject3').val('');
		$('#dtmulaioutput').val('');
		$('#dtselesaioutput').val('');
		$('#intremark3').select2().select2('val', '');
		$('#intqty3').val('');
		$('#hasil3').text('0/0');
		document.getElementById("intpasang3").disabled = true;
		document.getElementById("vcpo3").disabled = false;
		document.getElementById("cekpo3").checked = false;

		$('#vcpo4').val('');
		var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
		$('#intmodel4').html(htmlmd);
		var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
		$('#intkomponen4').html(htmlkp);
		$('#decct4').val('');
		$('#intlayer4').val('');
		$('#intremark4').val(0);
		$('#intpasang4').val('');
		$('#intreject4').val('');
		$('#dtmulaioutput').val('');
		$('#dtselesaioutput').val('');
		$('#intremark4').select2().select2('val', '');
		$('#intqty4').val('');
		$('#hasil4').text('0/0');
		document.getElementById("intpasang4").disabled = true;
		document.getElementById("vcpo4").disabled = false;
		document.getElementById("cekpo4").checked = false;

		$('#vcpo5').val('');
		var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
		$('#intmodel5').html(htmlmd);
		var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
		$('#intkomponen5').html(htmlkp);
		$('#decct5').val('');
		$('#intlayer5').val('');
		$('#intremark5').val(0);
		$('#intpasang5').val('');
		$('#intreject5').val('');
		$('#dtmulaioutput').val('');
		$('#dtselesaioutput').val('');
		$('#intremark5').select2().select2('val', '');
		$('#intqty5').val('');
		$('#hasil5').text('0/0');
		document.getElementById("intpasang5").disabled = true;
		document.getElementById("vcpo5").disabled = false;
		document.getElementById("cekpo5").checked = false;

		$('#vcpo6').val('');
		var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
		$('#intmodel6').html(htmlmd);
		var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
		$('#intkomponen6').html(htmlkp);
		$('#decct6').val('');
		$('#intlayer6').val('');
		$('#intremark6').val(0);
		$('#intpasang6').val('');
		$('#intreject6').val('');
		$('#dtmulaioutput').val('');
		$('#dtselesaioutput').val('');
		$('#intremark6').select2().select2('val', '');
		$('#intqty6').val('');
		$('#hasil6').text('0/0');
		document.getElementById("intpasang6").disabled = true;
		document.getElementById("vcpo6").disabled = false;
		document.getElementById("cekpo6").checked = false;
	}
	
	// OUTput Part
	function simpanoutput(dtselesai){
		var timeCutting = localStorage.getItem('timeCutting');
		var datacutting = JSON.parse(timeCutting);

		var intmodel     = $('#intmodel1').val();
		var intkomponen  = $('#intkomponen1').val();
		var vcpo         = $('#vcpo1').val();
		var decct        = $('#decct1').val();
		var intlayer     = $('#intlayer1').val();
		var intremark    = $('#intremark1').val();
		var dtmulai      = datacutting.dtstart;
		var dtselesai    = datacutting.dtfinish;
		var intpasang    = $('#intpasang1').val();
		var intreject    = $('#intreject1').val();
		
		var intmodel2     = $('#intmodel2').val();
		var intkomponen2  = $('#intkomponen2').val();
		var vcpo2         = $('#vcpo2').val();
		var decct2        = $('#decct2').val();
		var intlayer2     = $('#intlayer2').val();
		var intremark2    = $('#intremark2').val();
		var dtmulai2      = datacutting.dtstart;
		var dtselesai2    = datacutting.dtfinish;
		var intpasang2    = $('#intpasang2').val();
		var intreject2    = $('#intreject2').val();

		var intmodel3     = $('#intmodel3').val();
		var intkomponen3  = $('#intkomponen3').val();
		var vcpo3         = $('#vcpo3').val();
		var decct3        = $('#decct3').val();
		var intlayer3     = $('#intlayer3').val();
		var intremark3    = $('#intremark3').val();
		var dtmulai3      = datacutting.dtstart;
		var dtselesai3    = datacutting.dtfinish;
		var intpasang3    = $('#intpasang3').val();
		var intreject3    = $('#intreject3').val();

		var intmodel4     = $('#intmodel4').val();
		var intkomponen4  = $('#intkomponen4').val();
		var vcpo4         = $('#vcpo4').val();
		var decct4        = $('#decct4').val();
		var intlayer4     = $('#intlayer4').val();
		var intremark4    = $('#intremark4').val();
		var dtmulai4      = datacutting.dtstart;
		var dtselesai4    = datacutting.dtfinish;
		var intpasang4    = $('#intpasang4').val();
		var intreject4    = $('#intreject4').val();

		var intmodel5     = $('#intmodel5').val();
		var intkomponen5  = $('#intkomponen5').val();
		var vcpo5         = $('#vcpo5').val();
		var decct5        = $('#decct5').val();
		var intlayer5     = $('#intlayer5').val();
		var intremark5    = $('#intremark5').val();
		var dtmulai5      = datacutting.dtstart;
		var dtselesai5    = datacutting.dtfinish;
		var intpasang5    = $('#intpasang5').val();
		var intreject5    = $('#intreject5').val();

		var intmodel6     = $('#intmodel6').val();
		var intkomponen6  = $('#intkomponen6').val();
		var vcpo6         = $('#vcpo6').val();
		var decct6        = $('#decct6').val();
		var intlayer6     = $('#intlayer6').val();
		var intremark6    = $('#intremark6').val();
		var dtmulai6      = datacutting.dtstart;
		var dtselesai6    = datacutting.dtfinish;
		var intpasang6    = $('#intpasang6').val();
		var intreject6    = $('#intreject6').val();

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
		
		var _timeori   = Math.ceil(intpasang * decct);
		var _ctall     = Math.ceil(_timeori / intpasang);
		var _targetori = Math.ceil(_durasi / _ctall);
		var _target1   = _targetori;
		var _max1      = _target1 + Math.ceil(_target1 * 0.05);
		var _min1      = _target1 - Math.ceil(_target1 * 0.05);
		
		// var totalpasang = parseInt(jumlahpasang) + parseInt(intpasang);
		// var _accept = '';
		// if (totalpasang > intqty) {
		// 	Swal.fire({
		// 		title: 'TOtal potong melebihi target!',
		// 		text: "",
		// 		type: 'warning',
		// 		showCancelButton: true,
		// 		confirmButtonColor: '#3085d6',
		// 		cancelButtonColor: '#d33',
		// 		allowOutsideClick: false,
		// 		allowEscapeKey: false,
		// 		confirmButtonText: 'Setuju',
		// 		cancelButtonText: 'Tidak Setuju'
		// 	}).then((result) => {
		// 		if (result.value) {
		// 			var accept = 1;
		// 		} else {
		// 			return false;
		// 		}
		// 	});
		// }

		
			// Model 2
			if ((intmodel2 > 0 || intkomponen2 > 0 || vcpo2 != '' || intremark2 > 0) && (intpasang2 == '' || intpasang2 == 0 || intpasang2 < 0)) {
				swal({
						type: 'error',
						title: 'Jumlah Komponen ke-2 Harus Diisi !!'
					});
				return false;
			}

			if ((intmodel2 == 0 || intkomponen2 == 0 || vcpo2 == '' || intremark2 == 0) && ((intpasang2 > 0) || (intreject2 > 0))) {
				swal({
						type: 'error',
						title: 'Model, komponen, PO, dan layer ke-2 Harus Diisi !!'
					});
				return false;
			}

			if (intmodel2 > 0 &&  intkomponen2 > 0 && vcpo2 != '' && intremark2 > 0) {
				var _totpasangtemp  = parseInt(intpasang) + parseInt (intpasang2);
				var _persen1        = intpasang / _totpasangtemp;
				var _persen2        = intpasang2 / _totpasangtemp;
				var _timeori1       = Math.ceil(intpasang * decct);
				var _timeori2       = Math.ceil(intpasang2 * decct2);
				var _tottimeoritemp = Math.ceil(_timeori1 + _timeori2);
				var _totctall       = _tottimeoritemp / _totpasangtemp;
				var _targetori      = Math.ceil(_durasi / _totctall);
				var _target1        = Math.ceil(_targetori * _persen1);
				var _target2        = Math.ceil(_targetori * _persen2);
				var _max1           = _target1 + Math.ceil(_target1 * 0.05);
				var _min1           = _target1 - Math.ceil(_target1 * 0.05);
				var _max2           = _target2 + Math.ceil(_target2 * 0.05);
				var _min2           = _target2 - Math.ceil(_target2 * 0.05);
			}
			
		// Model 2
		if ((intmodel2 > 0 || intkomponen2 > 0 || vcpo2 != '' || intremark2 > 0) && (intpasang2 == '' || intpasang2 == 0 || intpasang2 < 0)) {
			swal({
					type: 'error',
					title: 'Jumlah Komponen ke-2 Harus Diisi !!'
				});
			return false;
		}

		if ((intmodel2 == 0 || intkomponen2 == 0 || vcpo2 == '' || intremark2 == 0) && ((intpasang2 > 0) || (intreject2 > 0))) {
			swal({
					type: 'error',
					title: 'Model, komponen, PO, dan layer ke-2 Harus Diisi !!'
				});
			return false;
		}

		if (intmodel2 > 0 &&  intkomponen2 > 0 && vcpo2 != '' && intremark2 > 0) {
			var _totpasangtemp  = parseInt(intpasang) + parseInt (intpasang2);
			var _persen1        = intpasang / _totpasangtemp;
			var _persen2        = intpasang2 / _totpasangtemp;
			var _timeori1       = Math.ceil(intpasang * decct);
			var _timeori2       = Math.ceil(intpasang2 * decct2);
			var _tottimeoritemp = Math.ceil(_timeori1 + _timeori2);
			var _totctall       = _tottimeoritemp / _totpasangtemp;
			var _targetori      = Math.ceil(_durasi / _totctall);
			var _target1        = Math.ceil(_targetori * _persen1);
			var _target2        = Math.ceil(_targetori * _persen2);
			var _max1           = _target1 + Math.ceil(_target1 * 0.05);
			var _min1           = _target1 - Math.ceil(_target1 * 0.05);
			var _max2           = _target2 + Math.ceil(_target2 * 0.05);
			var _min2           = _target2 - Math.ceil(_target2 * 0.05);
		}

		// model 3
		if ((intmodel3 > 0 || intkomponen3 > 0 || vcpo3 != '' || intremark3 > 0) && (intpasang3 == '' || intpasang3 == 0 || intpasang3 < 0)) {
			swal({
					type: 'error',
					title: 'Jumlah Komponen ke-3 Harus Diisi !!'
				});
			return false;
		}

		if ((intmodel3 == 0 || intkomponen3 == 0 || vcpo3 == '' ||  intremark3 == 0) && ((intpasang3 > 0) || (intreject3 > 0))) {
			swal({
					type: 'error',
					title: 'Model, komponen, PO, dan layer ke-3 Harus Diisi !!'
				});
			return false;
		}

		if (intmodel3 > 0 &&  intkomponen3 > 0 && vcpo3 != '' && intremark3 > 0) {
			var _totpasangtemp  = parseInt(intpasang) + parseInt (intpasang2) + parseInt(intpasang3);
			var _persen1        = intpasang / _totpasangtemp;
			var _persen2        = intpasang2 / _totpasangtemp;
			var _persen3        = intpasang3 / _totpasangtemp;
			var _timeori1       = Math.ceil(intpasang * decct);
			var _timeori2       = Math.ceil(intpasang2 * decct2);
			var _timeori3       = Math.ceil(intpasang3 * decct3);
			var _tottimeoritemp = Math.ceil(_timeori1 + _timeori2 + _timeori3);
			var _totctall       = _tottimeoritemp / _totpasangtemp;
			var _targetori      = Math.ceil(_durasi / _totctall);
			var _target1        = Math.ceil(_targetori * _persen1);
			var _target2        = Math.ceil(_targetori * _persen2);
			var _target3        = Math.ceil(_targetori * _persen3);
			var _max1           = _target1 + Math.ceil(_target1 * 0.05);
			var _min1           = _target1 - Math.ceil(_target1 * 0.05);
			var _max2           = _target2 + Math.ceil(_target2 * 0.05);
			var _min2           = _target2 - Math.ceil(_target2 * 0.05);
			var _max3           = _target3 + Math.ceil(_target3 * 0.05);
			var _min3           = _target3 - Math.ceil(_target3 * 0.05);
		}

		// Model 4 
		if ((intmodel4 > 0 || intkomponen4 > 0 || vcpo4 != '' || intremark4 > 0) && (intpasang4 == '' || intpasang4 == 0 || intpasang4 < 0)) {
			swal({
					type: 'error',
					title: 'Jumlah Komponen ke-4 Harus Diisi !!'
				});
			return false;
		}

		if ((intmodel4 == 0 || intkomponen4 == 0 || vcpo4 == '' || intremark4 == 0) && ((intpasang4 > 0) || (intreject4 > 0))) {
			swal({
					type: 'error',
					title: 'Model, komponen, PO, dan layer ke-4 Harus Diisi !!'
				});
			return false;
		}

		if (intmodel4 > 0 &&  intkomponen4 > 0 && vcpo4 != '' && intremark4 > 0) {
			var _totpasangtemp  = parseInt(intpasang) + parseInt (intpasang2) + parseInt(intpasang3) + parseInt(intpasang4);
			var _persen1        = intpasang / _totpasangtemp;
			var _persen2        = intpasang2 / _totpasangtemp;
			var _persen3        = intpasang3 / _totpasangtemp;
			var _persen4        = intpasang4 / _totpasangtemp;
			var _timeori1       = Math.ceil(intpasang * decct);
			var _timeori2       = Math.ceil(intpasang2 * decct2);
			var _timeori3       = Math.ceil(intpasang3 * decct3);
			var _timeori4       = Math.ceil(intpasang4 * decct4);
			var _tottimeoritemp = Math.ceil(_timeori1 + _timeori2 + _timeori3 + _timeori4);
			var _totctall       = _tottimeoritemp / _totpasangtemp;
			var _targetori      = Math.ceil(_durasi / _totctall);
			var _target1        = Math.ceil(_targetori * _persen1);
			var _target2        = Math.ceil(_targetori * _persen2);
			var _target3        = Math.ceil(_targetori * _persen3);
			var _target4        = Math.ceil(_targetori * _persen4);
			var _max1          = _target1 + Math.ceil(_target1 * 0.05);
			var _min1          = _target1 - Math.ceil(_target1 * 0.05);
			var _max2          = _target2 + Math.ceil(_target2 * 0.05);
			var _min2          = _target2 - Math.ceil(_target2 * 0.05);
			var _max3          = _target3 + Math.ceil(_target3 * 0.05);
			var _min3          = _target3 - Math.ceil(_target3 * 0.05);
			var _max4          = _target4 + Math.ceil(_target4 * 0.05);
			var _min4          = _target4 - Math.ceil(_target4 * 0.05);
		}

		// Model 5 
		if ((intmodel5 > 0 || intkomponen5 > 0 || vcpo5 != '' || intremark5 > 0) && (intpasang5 == '' || intpasang5 == 0 || intpasang5 < 0)) {
			swal({
					type: 'error',
					title: 'Jumlah Komponen ke-5 Harus Diisi !!'
				});
			return false;
		}

		if ((intmodel5 == 0 || intkomponen5 == 0 || vcpo5 == '' || intremark5 == 0) && ((intpasang5 > 0) || (intreject5 > 0))) {
			swal({
					type: 'error',
					title: 'Model, komponen, PO, dan layer ke-5 Harus Diisi !!'
				});
			return false;
		}

		if (intmodel5 > 0 &&  intkomponen5 > 0 && vcpo5 != '' && intremark5 > 0) {
			var _totpasangtemp  = parseInt(intpasang) + parseInt (intpasang2) + parseInt(intpasang3) + parseInt(intpasang4) + parseInt(intpasang5);
			var _persen1        = intpasang / _totpasangtemp;
			var _persen2        = intpasang2 / _totpasangtemp;
			var _persen3        = intpasang3 / _totpasangtemp;
			var _persen4        = intpasang4 / _totpasangtemp;
			var _persen5        = intpasang5 / _totpasangtemp;
			var _timeori1       = Math.ceil(intpasang * decct);
			var _timeori2       = Math.ceil(intpasang2 * decct2);
			var _timeori3       = Math.ceil(intpasang3 * decct3);
			var _timeori4       = Math.ceil(intpasang4 * decct4);
			var _timeori5       = Math.ceil(intpasang5 * decct5);
			var _tottimeoritemp = Math.ceil(_timeori1 + _timeori2 + _timeori3 + _timeori4 + _timeori5);
			var _totctall       = _tottimeoritemp / _totpasangtemp;
			var _targetori      = Math.ceil(_durasi / _totctall);
			var _target1        = Math.ceil(_targetori * _persen1);
			var _target2        = Math.ceil(_targetori * _persen2);
			var _target3        = Math.ceil(_targetori * _persen3);
			var _target4        = Math.ceil(_targetori * _persen4);
			var _target5        = Math.ceil(_targetori * _persen5);
			var _max1          = _target1 + Math.ceil(_target1 * 0.05);
			var _min1          = _target1 - Math.ceil(_target1 * 0.05);
			var _max2          = _target2 + Math.ceil(_target2 * 0.05);
			var _min2          = _target2 - Math.ceil(_target2 * 0.05);
			var _max3          = _target3 + Math.ceil(_target3 * 0.05);
			var _min3          = _target3 - Math.ceil(_target3 * 0.05);
			var _max4          = _target4 + Math.ceil(_target4 * 0.05);
			var _min4          = _target4 - Math.ceil(_target4 * 0.05);
			var _max5          = _target5 + Math.ceil(_target5 * 0.05);
			var _min5          = _target5 - Math.ceil(_target5 * 0.05);
		}

		// Model 6 
		if ((intmodel6 > 0 || intkomponen6 > 0 || vcpo6 != '' || intremark6 > 0) && (intpasang6 == '' || intpasang6 == 0 || intpasang6 < 0)) {
			swal({
					type: 'error',
					title: 'Jumlah Komponen ke-6 Harus Diisi !!'
				});
			return false;
		}

		if ((intmodel6 == 0 || intkomponen6 == 0 || vcpo6 == '' || intremark6 == 0) && ((intpasang6 > 0) || (intreject6 > 0))) {
			swal({
					type: 'error',
					title: 'Model, komponen, PO, dan layer ke-6 Harus Diisi !!'
				});
			return false;
		}

		if (intmodel6 > 0 &&  intkomponen6 > 0 && vcpo6 != '' && intremark6 > 0) {
			var _totpasangtemp  = parseInt(intpasang) + parseInt (intpasang2) + parseInt(intpasang3) + parseInt(intpasang4) + parseInt(intpasang5) + parseInt(intpasang6);
			var _persen1        = intpasang / _totpasangtemp;
			var _persen2        = intpasang2 / _totpasangtemp;
			var _persen3        = intpasang3 / _totpasangtemp;
			var _persen4        = intpasang4 / _totpasangtemp;
			var _persen5        = intpasang5 / _totpasangtemp;
			var _persen6        = intpasang6 / _totpasangtemp;
			var _timeori1       = Math.ceil(intpasang * decct);
			var _timeori2       = Math.ceil(intpasang2 * decct2);
			var _timeori3       = Math.ceil(intpasang3 * decct3);
			var _timeori4       = Math.ceil(intpasang4 * decct4);
			var _timeori5       = Math.ceil(intpasang5 * decct5);
			var _timeori6       = Math.ceil(intpasang6 * decct6);
			var _tottimeoritemp = Math.ceil(_timeori1 + _timeori2 + _timeori3 + _timeori4 + _timeori5 + _timeori6);
			var _totctall       = _tottimeoritemp / _totpasangtemp;
			var _targetori      = Math.ceil(_durasi / _totctall);
			var _target1        = Math.ceil(_targetori * _persen1);
			var _target2        = Math.ceil(_targetori * _persen2);
			var _target3        = Math.ceil(_targetori * _persen3);
			var _target4        = Math.ceil(_targetori * _persen4);
			var _target5        = Math.ceil(_targetori * _persen5);
			var _target6        = Math.ceil(_targetori * _persen6);
			var _max1          = _target1 + Math.ceil(_target1 * 0.05);
			var _min1          = _target1 - Math.ceil(_target1 * 0.05);
			var _max2          = _target2 + Math.ceil(_target2 * 0.05);
			var _min2          = _target2 - Math.ceil(_target2 * 0.05);
			var _max3          = _target3 + Math.ceil(_target3 * 0.05);
			var _min3          = _target3 - Math.ceil(_target3 * 0.05);
			var _max4          = _target4 + Math.ceil(_target4 * 0.05);
			var _min4          = _target4 - Math.ceil(_target4 * 0.05);
			var _max5          = _target5 + Math.ceil(_target5 * 0.05);
			var _min5          = _target5 - Math.ceil(_target5 * 0.05);
			var _max6          = _target6 + Math.ceil(_target6 * 0.05);
			var _min6          = _target6 - Math.ceil(_target6 * 0.05);
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
		} else if (vcpo == 0) {
			swal({
					type: 'error',
					title: 'PO Harus Diisi !!'
				});
		} else if (intremark == 0) {
			swal({
					type: 'error',
					title: 'Layer Harus Diisi !!'
				});
		} else if (intpasang == '' || intpasang == 0 || intpasang < 0) {
			swal({
					type: 'error',
					title: 'Jumlah Aktual Harus Diisi, dan tidak boleh kurang dari 0 !!'
				});
		} else {
			var _sop1 = '';
			var _sop2 = '';
			var _sop3 = '';
			var _sop4 = '';
			var _sop5 = '';
			var _sop6 = '';
			// console.log(intpasang + ' ' + _min1 + ' ' + _max1 + ' ' + _target1 + ' | ' +intpasang2 + ' ' + _min2 + ' ' + _max2 + ' ' + _target2 + ' | ' +intpasang3 + ' ' + _min3 + ' ' + _max3 + ' ' + _target3 + ' | ' +intpasang4 + ' ' + _min4 + ' ' + _max4 + ' ' + _target4);
			// console.log(_persen1 + ' ' + _persen2 + ' ' + _persen3 + ' ' + _persen4 + ' ' + _totpasangtemp);
			// return false;
			if (intpasang < _min1 || intpasang > _max1) {
				_sop1 = 'Tidak Mengikuti SOP';
				if (intmodel2 > 0 ||  intkomponen2 > 0 || intremark2 > 0){
					if (intpasang2 < _min2 || intpasang2 > _max2){
						_sop2 = 'Tidak Mengikuti SOP';
					}
				}

				if (intmodel3 > 0 ||  intkomponen3 > 0 || intremark3 > 0){
					if (intpasang3 < _min3 || intpasang3 > _max3){
						_sop3 = 'Tidak Mengikuti SOP';
					}
				}

				if (intmodel4 > 0 ||  intkomponen4 > 0 || intremark4 > 0){
					if (intpasang4 < _min4 || intpasang4 > _max4){
						_sop4 = 'Tidak Mengikuti SOP';
					}
				}
				Swal.fire({
					title: 'Anda tidak mengikuti SOP !',
					text: "",
					type: 'warning',
					//showCancelButton: true,
					confirmButtonColor: '#3085d6',
					cancelButtonColor: '#d33',
					allowOutsideClick: false,
					allowEscapeKey: false,
					confirmButtonText: 'Setuju',
					cancelButtonText: 'Tidak Setuju'
				}).then((result) => {
					if (result.value) {
						saveoutput(_sop1,_sop2,_sop3,_sop4,_sop5,_sop6);
					} else {
						return false;
					}
				});
			} else if (intmodel2 > 0 ||  intkomponen2 > 0 || intremark2 > 0){
				if (intpasang2 < _min2 || intpasang2 > _max2) {
					_sop2 = 'Tidak Mengikuti SOP';

					if (intmodel3 > 0 ||  intkomponen3 > 0 || intremark3 > 0){
						if (intpasang3 < _min3 || intpasang3 > _max3){
							_sop3 = 'Tidak Mengikuti SOP';
						}
					}

					if (intmodel4 > 0 ||  intkomponen4 > 0 || intremark4 > 0){
						if (intpasang4 < _min4 || intpasang4 > _max4){
							_sop4 = 'Tidak Mengikuti SOP';
						}
					}
					Swal.fire({
						title: 'Anda tidak mengikuti SOP !',
						text: "",
						type: 'warning',
						//showCancelButton: true,
						confirmButtonColor: '#3085d6',
						cancelButtonColor: '#d33',
						allowOutsideClick: false,
						allowEscapeKey: false,
						confirmButtonText: 'Setuju',
						cancelButtonText: 'Tidak Setuju'
					}).then((result) => {
						if (result.value) {
							saveoutput(_sop1,_sop2,_sop3,_sop4,_sop5,_sop6);
						} else {
							return false;
						}
					});
				} else if (intmodel3 > 0 ||  intkomponen3 > 0 || intremark3 > 0){
					if (intpasang3 < _min3 || intpasang3 > _max3) {
						_sop3 = 'Tidak Mengikuti SOP';

						if (intmodel4 > 0 ||  intkomponen4 > 0 || intremark4 > 0){
							if (intpasang4 < _min4 || intpasang4 > _max4){
								_sop4 = 'Tidak Mengikuti SOP';
							}
						}
						Swal.fire({
							title: 'Anda tidak mengikuti SOP !',
							text: "",
							type: 'warning',
							//showCancelButton: true,
							confirmButtonColor: '#3085d6',
							cancelButtonColor: '#d33',
							allowOutsideClick: false,
							allowEscapeKey: false,
							confirmButtonText: 'Setuju',
							cancelButtonText: 'Tidak Setuju'
						}).then((result) => {
							if (result.value) {
								saveoutput(_sop1,_sop2,_sop3,_sop4,_sop5,_sop6);
							} else {
								return false;
							}
						});
					} else if (intmodel4 > 0 ||  intkomponen4 > 0 || intremark4 > 0){
						if (intpasang4 < _min4 || intpasang4 > _max4) {
							_sop4 = 'Tidak Mengikuti SOP';
							Swal.fire({
								title: 'Anda tidak mengikuti SOP !',
								text: "",
								type: 'warning',
								//showCancelButton: true,
								confirmButtonColor: '#3085d6',
								cancelButtonColor: '#d33',
								allowOutsideClick: false,
								allowEscapeKey: false,
								confirmButtonText: 'Setuju',
								cancelButtonText: 'Tidak Setuju'
							}).then((result) => {
								if (result.value) {
									saveoutput(_sop1,_sop2,_sop3,_sop4,_sop5,_sop6);
								} else {
									return false;
								}
							});
						} else {
							saveoutput(_sop1,_sop2,_sop3,_sop4,_sop5,_sop6);
						}
					} else {
						saveoutput(_sop1,_sop2,_sop3,_sop4,_sop5,_sop6);
					}
				} else {
					saveoutput(_sop1,_sop2,_sop3,_sop4,_sop5,_sop6);
				}
			} else {
				saveoutput(_sop1,_sop2,_sop3,_sop4,_sop5,_sop6);
			}
		}
		
	}

	function saveoutput(_sop1,_sop2,_sop3,_sop4,_sop5,_sop6){
		var timeCutting = localStorage.getItem('timeCutting');
		var datacutting = JSON.parse(timeCutting);

		var intmodel     = $('#intmodel1').val();
		var intkomponen  = $('#intkomponen1').val();
		var vcpo         = $('#vcpo1').val();
		var decct        = $('#decct1').val();
		var intlayer     = $('#intlayer1').val();
		var intremark    = $('#intremark1').val();
		var dtmulai      = datacutting.dtstart;
		var dtselesai    = datacutting.dtfinish;
		var intpasang    = $('#intpasang1').val();
		var intreject    = $('#intreject1').val();
		
		var intmodel2    = $('#intmodel2').val();
		var intkomponen2 = $('#intkomponen2').val();
		var vcpo2        = $('#vcpo2').val();
		var decct2       = $('#decct2').val();
		var intlayer2    = $('#intlayer2').val();
		var intremark2   = $('#intremark2').val();
		var dtmulai2     = datacutting.dtstart;
		var dtselesai2   = datacutting.dtfinish;
		var intpasang2   = $('#intpasang2').val();
		var intreject2   = $('#intreject2').val();

		var intmodel3    = $('#intmodel3').val();
		var intkomponen3 = $('#intkomponen3').val();
		var vcpo3        = $('#vcpo3').val();
		var decct3       = $('#decct3').val();
		var intlayer3    = $('#intlayer3').val();
		var intremark3   = $('#intremark3').val();
		var dtmulai3     = datacutting.dtstart;
		var dtselesai3   = datacutting.dtfinish;
		var intpasang3   = $('#intpasang3').val();
		var intreject3   = $('#intreject3').val();

		var intmodel4    = $('#intmodel4').val();
		var intkomponen4 = $('#intkomponen4').val();
		var vcpo4        = $('#vcpo4').val();
		var decct4       = $('#decct4').val();
		var intlayer4    = $('#intlayer4').val();
		var intremark4   = $('#intremark4').val();
		var dtmulai4     = datacutting.dtstart;
		var dtselesai4   = datacutting.dtfinish;
		var intpasang4   = $('#intpasang4').val();
		var intreject4   = $('#intreject4').val();

		var intmodel5    = $('#intmodel5').val();
		var intkomponen5 = $('#intkomponen5').val();
		var vcpo5        = $('#vcpo5').val();
		var decct5       = $('#decct5').val();
		var intlayer5    = $('#intlayer5').val();
		var intremark5   = $('#intremark5').val();
		var dtmulai5     = datacutting.dtstart;
		var dtselesai5   = datacutting.dtfinish;
		var intpasang5   = $('#intpasang5').val();
		var intreject5   = $('#intreject5').val();

		var intmodel6    = $('#intmodel6').val();
		var intkomponen6 = $('#intkomponen6').val();
		var vcpo6        = $('#vcpo6').val();
		var decct6       = $('#decct6').val();
		var intlayer6    = $('#intlayer6').val();
		var intremark6   = $('#intremark6').val();
		var dtmulai6     = datacutting.dtstart;
		var dtselesai6   = datacutting.dtfinish;
		var intpasang6   = $('#intpasang6').val();
		var intreject6   = $('#intreject6').val();

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
        var seconds = datenow.getSeconds();
        if (minutes < 10) { minutes = '0' + minutes}
        if (seconds < 10) { seconds = '0' + seconds}
        var timedowntime    = datenow.getHours() + ":" + minutes + ":" + seconds;

		var dtmulaidowntime   = datacounting.dtstart;
		var dtselesaidowntime = timedowntime;

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
						'vcpo'             : vcpo,
						'decct'            : decct,
						'intlayer'         : intlayer,
						'intremark'        : intremark,
						'intpasang'        : intpasang,
						'intreject'        : intreject,
						'dtmulai'          : dtmulai,
						'dtselesai'        : dtselesai,
						'intmodel2'        : intmodel2,
						'intkomponen2'     : intkomponen2,
						'vcpo2'            : vcpo2,
						'decct2'           : decct2,
						'intlayer2'        : intlayer2,
						'intremark2'       : intremark2,
						'intpasang2'       : intpasang2,
						'intreject2'       : intreject2,
						'dtmulai2'         : dtmulai2,
						'dtselesai2'       : dtselesai2,
						'intmodel3'        : intmodel3,
						'intkomponen3'     : intkomponen3,
						'vcpo3'            : vcpo3,
						'decct3'           : decct3,
						'intlayer3'        : intlayer3,
						'intremark3'       : intremark3,
						'intpasang3'       : intpasang3,
						'intreject3'       : intreject3,
						'dtmulai3'         : dtmulai3,
						'dtselesai3'       : dtselesai3,
						'intmodel4'        : intmodel4,
						'intkomponen4'     : intkomponen4,
						'vcpo4'            : vcpo4,
						'decct4'           : decct4,
						'intlayer4'        : intlayer4,
						'intremark4'       : intremark4,
						'intpasang4'       : intpasang4,
						'intreject4'       : intreject4,
						'dtmulai4'         : dtmulai4,
						'dtselesai4'       : dtselesai4,
						'intmodel5'        : intmodel5,
						'intkomponen5'     : intkomponen5,
						'vcpo5'            : vcpo5,
						'decct5'           : decct5,
						'intlayer5'        : intlayer5,
						'intremark5'       : intremark5,
						'intpasang5'       : intpasang5,
						'intreject5'       : intreject5,
						'dtmulai5'         : dtmulai5,
						'dtselesai5'       : dtselesai5,
						'intmodel6'        : intmodel6,
						'intkomponen6'     : intkomponen6,
						'vcpo6'            : vcpo6,
						'decct6'           : decct6,
						'intlayer6'        : intlayer6,
						'intremark6'       : intremark6,
						'intpasang6'       : intpasang6,
						'intreject6'       : intreject6,
						'dtmulai6'         : dtmulai6,
						'dtselesai6'       : dtselesai6,
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
						'sop4'             : _sop4,
						'sop5'             : _sop5,
						'sop6'             : _sop6
					};

		var base_url = '<?=base_url("operator")?>';
		$.ajax({
			url: base_url + '/add_output_ajax',
			method: "POST",
			data: data
		})
		.done(function( data ) {
			var jsonData   = JSON.parse(data);
			var dataoutput = jsonData.dataoutput;
			var _html = '';
			for (var i = 0; i < dataoutput.length; i++) {
				if (dataoutput[i].vcketerangan != '') {
					var _warna = 'danger';
				} else {
					var _warna = '';
				}
				_html += '<tr class="'+_warna+'">';
				_html += '<td>' + dataoutput[i].vcmodel + '</td>';
				_html += '<td>' + dataoutput[i].vckomponen + '</td>';
				_html += '<td>' + dataoutput[i].vcpo.substring(dataoutput[i].vcpo.length - 4, dataoutput[i].vcpo.length) + '</td>';
				_html += '<td>' + dataoutput[i].dtmulai + '</td>';
				_html += '<td>' + dataoutput[i].dtselesai + '</td>';
				_html += '<td>' + dataoutput[i].intpasang + '</td>';
				// _html += '<td class="rejectoutput">' + dataoutput[i].intreject + '</td>';
				_html += '<td class="rejectoutput" id="reject'+dataoutput[i].intid+'" ondblclick="updatereject('+dataoutput[i].intid+', '+dataoutput[i].intreject+', '+dataoutput[i].intpasang+')">'+dataoutput[i].intreject+'</td>'
				_html += '<td>' + dataoutput[i].vcketerangan + '</td>';
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

			var dttime     = jsonData.dttime;
			var timeCounting = { 'dtstart': dttime, 'counttipe': 1};
			// Put the object into storage
			localStorage.setItem('timeCounting', JSON.stringify(timeCounting));

			$('.loadingoutput').addClass('hidden');
			$('.saveoutputbtn').removeClass('hidden');

			var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
			$('#intmodel1').html(htmlmd);
			$('#intmodel2').html(htmlmd);
			$('#intmodel3').html(htmlmd);
			$('#intmodel4').html(htmlmd);
			$('#intmodel5').html(htmlmd);
			$('#intmodel6').html(htmlmd);

			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen1').html(htmlkp);
			$('#intkomponen2').html(htmlkp);
			$('#intkomponen3').html(htmlkp);
			$('#intkomponen4').html(htmlkp);
			$('#intkomponen5').html(htmlkp);
			$('#intkomponen6').html(htmlkp);

			$('#vcpo1').val('');
			$('#decct1').val('');
			$('#intlayer1').val('');
			$('#intremark1').val(0);
			$('#intpasang1').val('');
			$('#intreject1').val('');
			$('#dtmulaioutput').val('');
			$('#dtselesaioutput').val('');
			$('#intremark1').select2().select2('val', '');
			$('#intqty1').val('');
			$('#hasil1').text('0/0');
			document.getElementById("intpasang1").disabled = true;
			document.getElementById("vcpo1").disabled = false;
			document.getElementById("cekpo1").checked = false;
			
			$('#vcpo2').val('');
			$('#decct2').val('');
			$('#intlayer2').val('');
			$('#intremark2').val(0);
			$('#intpasang2').val('');
			$('#intreject2').val('');
			$('#dtmulaioutput').val('');
			$('#dtselesaioutput').val('');
			$('#intremark2').select2().select2('val', '');
			$('#intqty2').val('');
			$('#hasil2').text('0/0');
			document.getElementById("intpasang2").disabled = true;
			document.getElementById("vcpo2").disabled = false;
			document.getElementById("cekpo2").checked = false;

			$('#vcpo3').val('');
			$('#decct3').val('');
			$('#intlayer3').val('');
			$('#intremark3').val(0);
			$('#intpasang3').val('');
			$('#intreject3').val('');
			$('#dtmulaioutput').val('');
			$('#dtselesaioutput').val('');
			$('#intremark3').select2().select2('val', '');
			$('#intqty3').val('');
			$('#hasil3').text('0/0');
			document.getElementById("intpasang3").disabled = true;
			document.getElementById("vcpo3").disabled = false;
			document.getElementById("cekpo3").checked = false;

			$('#vcpo4').val('');
			$('#decct4').val('');
			$('#intlayer4').val('');
			$('#intremark4').val(0);
			$('#intpasang4').val('');
			$('#intreject4').val('');
			$('#dtmulaioutput').val('');
			$('#dtselesaioutput').val('');
			$('#intremark4').select2().select2('val', '');
			$('#intqty4').val('');
			$('#hasil4').text('0/0');
			document.getElementById("intpasang4").disabled = true;
			document.getElementById("vcpo4").disabled = false;
			document.getElementById("cekpo4").checked = false;

			$('#vcpo5').val('');
			$('#decct5').val('');
			$('#intlayer5').val('');
			$('#intremark5').val(0);
			$('#intpasang5').val('');
			$('#intreject5').val('');
			$('#dtmulaioutput').val('');
			$('#dtselesaioutput').val('');
			$('#intremark5').select2().select2('val', '');
			$('#intqty5').val('');
			$('#hasil5').text('0/0');
			document.getElementById("intpasang5").disabled = true;
			document.getElementById("vcpo5").disabled = false;
			document.getElementById("cekpo5").checked = false;

			$('#vcpo5').val('');
			$('#decct5').val('');
			$('#intlayer5').val('');
			$('#intremark5').val(0);
			$('#intpasang5').val('');
			$('#intreject5').val('');
			$('#dtmulaioutput').val('');
			$('#dtselesaioutput').val('');
			$('#intremark5').select2().select2('val', '');
			$('#intqty5').val('');
			$('#hasil5').text('0/0');
			document.getElementById("intpasang5").disabled = true;
			document.getElementById("vcpo5").disabled = false;
			document.getElementById("cekpo5").checked = false;

			$('#btnoutput').addClass('hidden');
			$('#btnstartdowntime').text(dttime);
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
		$('#decct').val('');
		$('#intremark').val(0);
		$('#intpasang').val('');
		$('#intreject').val('');
		$('#dtmulaioutput').val('');
		$('#dtselesaioutput').val('');
		$('#intmodel').select2().select2('val', '');
		$('#intkomponen').select2().select2('val', '');

	}

	// validasi PO
	$('#cekpo1').change(function(){
		if ($(this).is(':checked')) {
		    var vcpo        = $('#vcpo1').val();
			var base_url    = '<?=base_url("operator")?>';
			if (vcpo == 0 || vcpo == '') {
				document.getElementById("cekpo1").checked = false;
				swal({
						type: 'error',
						title: 'Isi PO dahulu !'
					});
			} else {
				$.ajax({
					url: base_url + '/getstatuspo/' + vcpo,
					method: "GET"
				})
				.done(function( data ) {
					var jsonData = JSON.parse(data);
					if (jsonData.intstatus == 0) {
						swal({
							type: 'warning',
							title: 'PO Tidak ada !'
						});
						$('#vcpo1').val('');
						$('#intpasang1').val('');
						document.getElementById("vcpo1").disabled = false;
						document.getElementById("cekpo1").checked = false;
					} else {
						document.getElementById("vcpo1").disabled = true;
						var listmodel = jsonData.listmodel;
						var html = '<option data-nama="" value="0">-- Pilih Model --</option>';
						for (var i = 0; i < listmodel.length; i++) {
							html += '<option data-po="' + listmodel[i].vcpo + '" data-sdd="' + listmodel[i].sdd + '" value="' + listmodel[i].intmodel + '">' + listmodel[i].vcmodel + '</option>'
						}
						$('#intmodel1').html(html);
					}
				})
				.fail(function( jqXHR, statusText ) {
					alert( "Request failed: " + jqXHR.status );
				});
			}
		} else {
			var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
			$('#intmodel1').html(htmlmd);
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen1').html(htmlkp);
			$('#vcpo1').val('');
			$('#intqty1').val('');
			$('#intpasang1').val('');
			$('#hasil1').text('0/0');
			document.getElementById("intpasang1").disabled = true;
			document.getElementById("vcpo1").disabled = false;
		}
	});

	$('#cekpo2').change(function(){
		if ($(this).is(':checked')) {
		    var vcpo        = $('#vcpo2').val();
			var base_url    = '<?=base_url("operator")?>';
			if (vcpo == 0 || vcpo == '') {
				document.getElementById("cekpo2").checked = false;
				swal({
						type: 'error',
						title: 'Isi PO dahulu !'
					});
			} else {
				$.ajax({
					url: base_url + '/getstatuspo/' + vcpo,
					method: "GET"
				})
				.done(function( data ) {
					var jsonData = JSON.parse(data);
					if (jsonData.intstatus == 0) {
						swal({
							type: 'warning',
							title: 'PO Tidak ada !'
						});
						$('#vcpo2').val('');
						$('#intpasang2').val('');
						document.getElementById("vcpo2").disabled = false;
						document.getElementById("cekpo2").checked = false;
					} else {
						document.getElementById("vcpo2").disabled = true;
						var listmodel = jsonData.listmodel;
						var html = '<option data-nama="" value="0">-- Pilih Model --</option>';
						for (var i = 0; i < listmodel.length; i++) {
							html += '<option data-po="' + listmodel[i].vcpo + '" data-sdd="' + listmodel[i].sdd + '" value="' + listmodel[i].intmodel + '">' + listmodel[i].vcmodel + '</option>'
						}
						$('#intmodel2').html(html);
					}
				})
				.fail(function( jqXHR, statusText ) {
					alert( "Request failed: " + jqXHR.status );
				});
			}
		} else {
			var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
			$('#intmodel2').html(htmlmd);
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen2').html(htmlkp);
			$('#vcpo2').val('');
			$('#intqty2').val('');
			$('#intpasang2').val('');
			$('#hasil2').text('0/0');
			document.getElementById("intpasang2").disabled = true;
			document.getElementById("vcpo2").disabled = false;
		}
	});

	$('#cekpo3').change(function(){
		if ($(this).is(':checked')) {
		    var vcpo        = $('#vcpo3').val();
			var base_url    = '<?=base_url("operator")?>';
			if (vcpo == 0 || vcpo == '') {
				document.getElementById("cekpo3").checked = false;
				swal({
						type: 'error',
						title: 'Isi PO dahulu !'
					});
			} else {
				$.ajax({
					url: base_url + '/getstatuspo/' + vcpo,
					method: "GET"
				})
				.done(function( data ) {
					var jsonData = JSON.parse(data);
					if (jsonData.intstatus == 0) {
						swal({
							type: 'warning',
							title: 'PO Tidak ada !'
						});
						$('#vcpo3').val('');
						$('#intpasang3').val('');
						document.getElementById("vcpo3").disabled = false;
						document.getElementById("cekpo3").checked = false;
					} else {
						document.getElementById("vcpo3").disabled = true;
						var listmodel = jsonData.listmodel;
						var html = '<option data-nama="" value="0">-- Pilih Model --</option>';
						for (var i = 0; i < listmodel.length; i++) {
							html += '<option data-po="' + listmodel[i].vcpo + '" data-sdd="' + listmodel[i].sdd + '" value="' + listmodel[i].intmodel + '">' + listmodel[i].vcmodel + '</option>'
						}
						$('#intmodel3').html(html);
					}
				})
				.fail(function( jqXHR, statusText ) {
					alert( "Request failed: " + jqXHR.status );
				});
			}
		} else {
			var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
			$('#intmodel3').html(htmlmd);
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen3').html(htmlkp);
			$('#vcpo3').val('');
			$('#intqty3').val('');
			$('#intpasang3').val('');
			$('#hasil3').text('0/0');
			document.getElementById("intpasang3").disabled = true;
			document.getElementById("vcpo3").disabled = false;
		}
	});

	$('#cekpo4').change(function(){
		if ($(this).is(':checked')) {
		    var vcpo        = $('#vcpo4').val();
			var base_url    = '<?=base_url("operator")?>';
			if (vcpo == 0 || vcpo == '') {
				document.getElementById("cekpo4").checked = false;
				swal({
						type: 'error',
						title: 'Isi PO dahulu !'
					});
			} else {
				$.ajax({
					url: base_url + '/getstatuspo/' + vcpo,
					method: "GET"
				})
				.done(function( data ) {
					var jsonData = JSON.parse(data);
					if (jsonData.intstatus == 0) {
						swal({
							type: 'warning',
							title: 'PO Tidak ada !'
						});
						$('#vcpo4').val('');
						$('#intpasang4').val('');
						document.getElementById("vcpo4").disabled = false;
						document.getElementById("cekpo4").checked = false;
					} else {
						document.getElementById("vcpo4").disabled = true;
						var listmodel = jsonData.listmodel;
						var html = '<option data-nama="" value="0">-- Pilih Model --</option>';
						for (var i = 0; i < listmodel.length; i++) {
							html += '<option data-po="' + listmodel[i].vcpo + '" data-sdd="' + listmodel[i].sdd + '" value="' + listmodel[i].intmodel + '">' + listmodel[i].vcmodel + '</option>'
						}
						$('#intmodel4').html(html);
					}
				})
				.fail(function( jqXHR, statusText ) {
					alert( "Request failed: " + jqXHR.status );
				});
			}
		} else {
			var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
			$('#intmodel4').html(htmlmd);
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen4').html(htmlkp);
			$('#vcpo4').val('');
			$('#intqty4').val('');
			$('#intpasang4').val('');
			$('#hasil4').text('0/0');
			document.getElementById("intpasang4").disabled = true;
			document.getElementById("vcpo4").disabled = false;
		}
	});

	$('#cekpo5').change(function(){
		if ($(this).is(':checked')) {
		    var vcpo        = $('#vcpo5').val();
			var base_url    = '<?=base_url("operator")?>';
			if (vcpo == 0 || vcpo == '') {
				document.getElementById("cekpo5").checked = false;
				swal({
						type: 'error',
						title: 'Isi PO dahulu !'
					});
			} else {
				$.ajax({
					url: base_url + '/getstatuspo/' + vcpo,
					method: "GET"
				})
				.done(function( data ) {
					var jsonData = JSON.parse(data);
					if (jsonData.intstatus == 0) {
						swal({
							type: 'warning',
							title: 'PO Tidak ada !'
						});
						$('#vcpo5').val('');
						$('#intpasang5').val('');
						document.getElementById("vcpo5").disabled = false;
						document.getElementById("cekpo5").checked = false;
					} else {
						document.getElementById("vcpo5").disabled = true;
						var listmodel = jsonData.listmodel;
						var html = '<option data-nama="" value="0">-- Pilih Model --</option>';
						for (var i = 0; i < listmodel.length; i++) {
							html += '<option data-po="' + listmodel[i].vcpo + '" data-sdd="' + listmodel[i].sdd + '" value="' + listmodel[i].intmodel + '">' + listmodel[i].vcmodel + '</option>'
						}
						$('#intmodel5').html(html);
					}
				})
				.fail(function( jqXHR, statusText ) {
					alert( "Request failed: " + jqXHR.status );
				});
			}
		} else {
			var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
			$('#intmodel5').html(htmlmd);
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen5').html(htmlkp);
			$('#vcpo5').val('');
			$('#intqty5').val('');
			$('#intpasang5').val('');
			$('#hasil5').text('0/0');
			document.getElementById("intpasang5").disabled = true;
			document.getElementById("vcpo5").disabled = false;
		}
	});

	$('#cekpo6').change(function(){
		if ($(this).is(':checked')) {
		    var vcpo        = $('#vcpo6').val();
			var base_url    = '<?=base_url("operator")?>';
			if (vcpo == 0 || vcpo == '') {
				document.getElementById("cekpo6").checked = false;
				swal({
						type: 'error',
						title: 'Isi PO dahulu !'
					});
			} else {
				$.ajax({
					url: base_url + '/getstatuspo/' + vcpo,
					method: "GET"
				})
				.done(function( data ) {
					var jsonData = JSON.parse(data);
					if (jsonData.intstatus == 0) {
						swal({
							type: 'warning',
							title: 'PO Tidak ada !'
						});
						$('#vcpo6').val('');
						$('#intpasang6').val('');
						document.getElementById("vcpo6").disabled = false;
						document.getElementById("cekpo6").checked = false;
					} else {
						document.getElementById("vcpo6").disabled = true;
						var listmodel = jsonData.listmodel;
						var html = '<option data-nama="" value="0">-- Pilih Model --</option>';
						for (var i = 0; i < listmodel.length; i++) {
							html += '<option data-po="' + listmodel[i].vcpo + '" data-sdd="' + listmodel[i].sdd + '" value="' + listmodel[i].intmodel + '">' + listmodel[i].vcmodel + '</option>'
						}
						$('#intmodel6').html(html);
					}
				})
				.fail(function( jqXHR, statusText ) {
					alert( "Request failed: " + jqXHR.status );
				});
			}
		} else {
			var htmlmd = '<option data-nama="" value="0">-- Pilih Model --</option>';
			$('#intmodel1').html(htmlmd);
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen1').html(htmlkp);
			$('#vcpo1').val('');
			$('#intqty1').val('');
			$('#intpasang1').val('');
			$('#hasil1').text('0/0');
			document.getElementById("intpasang1").disabled = true;
			document.getElementById("vcpo1").disabled = false;
		}
	});
	
	//choose model with qty
	$('#intmodel1').change(function(){
		var intid    = $(this).val();
		var vcpo     = $(this).children('option:selected').data('po');
		var sdd      = $(this).children('option:selected').data('sdd');
		var base_url = '<?=base_url("operator")?>';

		if (intid == 0) {
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen1').html(htmlkp);
			$('#intqty1').val('');
			$('#intpasang1').val('');
			$('#hasil1').text('0/0');
			$('#decct1').val('');
			$('#intlayer1').val('');
			document.getElementById("intpasang1").disabled = true;
		} else {
			$('#vcpo1').val(vcpo);
			$.ajax({
				url: base_url + '/getkomponen_ajax/' + intid + '/' + sdd + '/' + vcpo,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				$('#intqty1').val(jsonData.intqty);
				var listkomponen = jsonData.listkomponen;
				var html = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
				for (var i = 0; i < listkomponen.length; i++) {
					html += '<option data-ct="' + listkomponen[i].deccycle_time + '" data-layer="' + listkomponen[i].intlayer + '" data-pasang="' + listkomponen[i].jmlpasang + '" value="' + listkomponen[i].intkomponen + '">' + listkomponen[i].vckomponen + '</option>'
				}
				$('#intkomponen1').html(html);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	});

	$('#intmodel2').change(function(){
		var intid    = $(this).val();
		var vcpo     = $(this).children('option:selected').data('po');
		var sdd      = $(this).children('option:selected').data('sdd');
		var base_url = '<?=base_url("operator")?>';
		if (intid == 0) {
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen2').html(htmlkp);
			$('#intqty2').val('');
			$('#intpasang2').val('');
			$('#hasil2').text('0/0');
			$('#decct2').val('');
			$('#intlayer2').val('');
			document.getElementById("intpasang2").disabled = true;
		} else {
			$('#vcpo2').val(vcpo);
			$.ajax({
				url: base_url + '/getkomponen_ajax/' + intid + '/' + sdd + '/' + vcpo,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				$('#intqty2').val(jsonData.intqty);
				var listkomponen = jsonData.listkomponen;
				var html = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
				for (var i = 0; i < listkomponen.length; i++) {
					html += '<option data-ct="' + listkomponen[i].deccycle_time + '" data-layer="' + listkomponen[i].intlayer + '" data-pasang="' + listkomponen[i].jmlpasang + '" value="' + listkomponen[i].intkomponen + '">' + listkomponen[i].vckomponen + '</option>'
				}
				$('#intkomponen2').html(html);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	});

	$('#intmodel3').change(function(){
		var vcpo     = $(this).children('option:selected').data('po');
		var sdd      = $(this).children('option:selected').data('sdd');
		var intid    = $(this).val();
		var base_url = '<?=base_url("operator")?>';
		if (intid == 0) {
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen3').html(htmlkp);
			$('#intqty3').val('');
			$('#intpasang3').val('');
			$('#hasil3').text('0/0');
			$('#decct3').val('');
			$('#intlayer3').val('');
			document.getElementById("intpasang3").disabled = true;
		} else {
			$('#vcpo3').val(vcpo);
			$.ajax({
				url: base_url + '/getkomponen_ajax/' + intid + '/' + sdd + '/' + vcpo,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				$('#intqty3').val(jsonData.intqty);
				var listkomponen = jsonData.listkomponen;
				var html = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
				for (var i = 0; i < listkomponen.length; i++) {
					html += '<option data-ct="' + listkomponen[i].deccycle_time + '" data-layer="' + listkomponen[i].intlayer + '" data-pasang="' + listkomponen[i].jmlpasang + '" value="' + listkomponen[i].intkomponen + '">' + listkomponen[i].vckomponen + '</option>'
				}
				$('#intkomponen3').html(html);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	});

	$('#intmodel4').change(function(){
		var vcpo     = $(this).children('option:selected').data('po');
		var sdd      = $(this).children('option:selected').data('sdd');
		var intid    = $(this).val();
		var base_url = '<?=base_url("operator")?>';
		if (intid == 0) {
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen4').html(htmlkp);
			$('#intqty4').val('');
			$('#intpasang4').val('');
			$('#hasil4').text('0/0');
			$('#decct4').val('');
			$('#intlayer4').val('');
			document.getElementById("intpasang4").disabled = true;
		} else {
			$('#vcpo4').val(vcpo);
			$.ajax({
				url: base_url + '/getkomponen_ajax/' + intid + '/' + sdd + '/' + vcpo,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				$('#intqty4').val(jsonData.intqty);
				var listkomponen = jsonData.listkomponen;
				var html = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
				for (var i = 0; i < listkomponen.length; i++) {
					html += '<option data-ct="' + listkomponen[i].deccycle_time + '" data-layer="' + listkomponen[i].intlayer + '" data-pasang="' + listkomponen[i].jmlpasang + '" value="' + listkomponen[i].intkomponen + '">' + listkomponen[i].vckomponen + '</option>'
				}
				$('#intkomponen4').html(html);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	});

	$('#intmodel5').change(function(){
		var vcpo     = $(this).children('option:selected').data('po');
		var sdd      = $(this).children('option:selected').data('sdd');
		var intid    = $(this).val();
		var base_url = '<?=base_url("operator")?>';
		if (intid == 0) {
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen5').html(htmlkp);
			$('#intqty5').val('');
			$('#intpasang5').val('');
			$('#hasil5').text('0/0');
			$('#decct5').val('');
			$('#intlayer5').val('');
			document.getElementById("intpasang5").disabled = true;
		} else {
			$('#vcpo5').val(vcpo);
			$.ajax({
				url: base_url + '/getkomponen_ajax/' + intid + '/' + sdd + '/' + vcpo,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				$('#intqty5').val(jsonData.intqty);
				var listkomponen = jsonData.listkomponen;
				var html = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
				for (var i = 0; i < listkomponen.length; i++) {
					html += '<option data-ct="' + listkomponen[i].deccycle_time + '" data-layer="' + listkomponen[i].intlayer + '" data-pasang="' + listkomponen[i].jmlpasang + '" value="' + listkomponen[i].intkomponen + '">' + listkomponen[i].vckomponen + '</option>'
				}
				$('#intkomponen5').html(html);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	});

	$('#intmodel6').change(function(){
		var vcpo     = $(this).children('option:selected').data('po');
		var sdd      = $(this).children('option:selected').data('sdd');
		var intid    = $(this).val();
		var base_url = '<?=base_url("operator")?>';
		if (intid == 0) {
			var htmlkp = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
			$('#intkomponen6').html(htmlkp);
			$('#intqty6').val('');
			$('#intpasang6').val('');
			$('#hasil6').text('0/0');
			$('#decct6').val('');
			$('#intlayer6').val('');
			document.getElementById("intpasang6").disabled = true;
		} else {
			$('#vcpo6').val(vcpo);
			$.ajax({
				url: base_url + '/getkomponen_ajax/' + intid + '/' + sdd + '/' + vcpo,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				$('#intqty6').val(jsonData.intqty);
				var listkomponen = jsonData.listkomponen;
				var html = '<option data-nama="" value="0">-- Pilih Komponen --</option>';
				for (var i = 0; i < listkomponen.length; i++) {
					html += '<option data-ct="' + listkomponen[i].deccycle_time + '" data-layer="' + listkomponen[i].intlayer + '" data-pasang="' + listkomponen[i].jmlpasang + '" value="' + listkomponen[i].intkomponen + '">' + listkomponen[i].vckomponen + '</option>'
				}
				$('#intkomponen6').html(html);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	});
	
	//choose komponen, ct, layer, and total cutting
	$('#intkomponen1').change(function(){
		var intkomponen = $(this).val();
		var vcpo        = $('#vcpo1').val();
		var intmodel    = $('#intmodel1').val();
		var intqty      = $('#intqty1').val();
		var base_url    = '<?=base_url("operator")?>';
		var decct       = $(this).children('option:selected').data('ct');
		var intlayer    = $(this).children('option:selected').data('layer');
		var jmlpasang   = $(this).children('option:selected').data('pasang');

		if (intkomponen == 0) {
			$('#decct1').val('');
			$('#intlayer1').val('');
			$('#hasil1').text('0/0');
			$('#intpasang1').val('');
			document.getElementById("intpasang1").disabled = true;
		} else {
			if (jmlpasang >= intqty) {
				swal({
					type: 'warning',
					title: 'Cutting sudah mencapai target PO !',
				});
				$('#decct1').val('');
				$('#intlayer1').val('');
				$('#hasil1').text(jmlpasang + ' / ' + intqty);
				$('#intpasang1').val('');
				document.getElementById("intpasang1").disabled = true;
			} else {
				$('#decct1').val(decct);
				$('#intlayer1').val(intlayer);
				$('#hasil1').text('' + jmlpasang + ' / ' + intqty);
				$('#intpasang1').val('');
				document.getElementById("intpasang1").disabled = false;
			}
		}

		// $.ajax({
		// 	url: base_url + '/getaktual_ajax/' + intkomponen + '/' + intmodel + '/' + vcpo,
		// 	method: "GET"
		// })
		// .done(function( data ) {
		// 	var jsonData = JSON.parse(data);
		// 	if (jsonData.intstatus == 0) {
		// 		swal({
		// 			type: 'warning',
		// 			title: 'Cutting sudah mencapai target PO !',
		// 		});
		// 		$('#intkomponen1').val(0);
		// 		$('#decct1').val('');
		// 		$('#intlayer1').val('');
		// 		$('#intpasang1').val('');
		// 		document.getElementById("intpasang1").disabled = true;
		// 	} else if (jsonData.intstatus == 1) {
		// 		$('#decct1').val(decct);
		// 		$('#intlayer1').val(intlayer);
		// 		$('#intpasang1').val('');
		// 		document.getElementById("intpasang1").disabled = false;
		// 	}
		// })
		// .fail(function( jqXHR, statusText ) {
		// 	alert( "Request failed: " + jqXHR.status );
		// });
	});

	$('#intkomponen2').change(function(){
		var intkomponen = $(this).val();
		var vcpo        = $('#vcpo2').val();
		var intmodel    = $('#intmodel2').val();
		var intqty      = $('#intqty2').val();
		var base_url    = '<?=base_url("operator")?>';
		var decct       = $(this).children('option:selected').data('ct');
		var intlayer    = $(this).children('option:selected').data('layer');
		var jmlpasang   = $(this).children('option:selected').data('pasang');

		if (intkomponen == 0) {
			$('#decct2').val('');
			$('#intlayer2').val('');
			$('#hasil2').text('0/0');
			$('#intpasang2').val('');
			document.getElementById("intpasang2").disabled = true;
		} else {
			if (jmlpasang >= intqty) {
				swal({
					type: 'warning',
					title: 'Cutting sudah mencapai target PO !',
				});
				$('#decct2').val('');
				$('#intlayer2').val('');
				$('#hasil2').text(jmlpasang + ' / ' + intqty);
				$('#intpasang2').val('');
				document.getElementById("intpasang2").disabled = true;
			} else {
				$('#decct2').val(decct);
				$('#intlayer2').val(intlayer);
				$('#hasil2').text('' + jmlpasang + ' / ' + intqty);
				$('#intpasang2').val('');
				document.getElementById("intpasang2").disabled = false;
			}
		}
	});

	$('#intkomponen3').change(function(){
		var intkomponen = $(this).val();
		var vcpo        = $('#vcpo3').val();
		var intmodel    = $('#intmodel3').val();
		var intqty      = $('#intqty3').val();
		var base_url    = '<?=base_url("operator")?>';
		var decct       = $(this).children('option:selected').data('ct');
		var intlayer    = $(this).children('option:selected').data('layer');
		var jmlpasang   = $(this).children('option:selected').data('pasang');

		if (intkomponen == 0) {
			$('#decct3').val('');
			$('#intlayer3').val('');
			$('#hasil3').text('0/0');
			$('#intpasang3').val('');
			document.getElementById("intpasang3").disabled = true;
		} else {
			if (jmlpasang >= intqty) {
				swal({
					type: 'warning',
					title: 'Cutting sudah mencapai target PO !',
				});
				$('#decct3').val('');
				$('#intlayer3').val('');
				$('#hasil3').text(jmlpasang + ' / ' + intqty);
				$('#intpasang3').val('');
				document.getElementById("intpasang3").disabled = true;
			} else {
				$('#decct3').val(decct);
				$('#intlayer3').val(intlayer);
				$('#hasil3').text('' + jmlpasang + ' / ' + intqty);
				$('#intpasang3').val('');
				document.getElementById("intpasang3").disabled = false;
			}
		}
	});

	$('#intkomponen4').change(function(){
		var intkomponen = $(this).val();
		var vcpo        = $('#vcpo4').val();
		var intmodel    = $('#intmodel4').val();
		var intqty      = $('#intqty4').val();
		var base_url    = '<?=base_url("operator")?>';
		var decct       = $(this).children('option:selected').data('ct');
		var intlayer    = $(this).children('option:selected').data('layer');
		var jmlpasang   = $(this).children('option:selected').data('pasang');

		if (intkomponen == 0) {
			$('#decct4').val('');
			$('#intlayer4').val('');
			$('#hasil4').text('0/0');
			$('#intpasang4').val('');
			document.getElementById("intpasang4").disabled = true;
		} else {
			if (jmlpasang >= intqty) {
				swal({
					type: 'warning',
					title: 'Cutting sudah mencapai target PO !',
				});
				$('#decct4').val('');
				$('#intlayer4').val('');
				$('#hasil4').text(jmlpasang + ' / ' + intqty);
				$('#intpasang4').val('');
				document.getElementById("intpasang4").disabled = true;
			} else {
				$('#decct4').val(decct);
				$('#intlayer4').val(intlayer);
				$('#hasil4').text('' + jmlpasang + ' / ' + intqty);
				$('#intpasang4').val('');
				document.getElementById("intpasang4").disabled = false;
			}
		}
	});

	$('#intkomponen5').change(function(){
		var intkomponen = $(this).val();
		var vcpo        = $('#vcpo5').val();
		var intmodel    = $('#intmodel5').val();
		var intqty      = $('#intqty5').val();
		var base_url    = '<?=base_url("operator")?>';
		var decct       = $(this).children('option:selected').data('ct');
		var intlayer    = $(this).children('option:selected').data('layer');
		var jmlpasang   = $(this).children('option:selected').data('pasang');

		if (intkomponen == 0) {
			$('#decct5').val('');
			$('#intlayer5').val('');
			$('#hasil5').text('0/0');
			$('#intpasang5').val('');
			document.getElementById("intpasang5").disabled = true;
		} else {
			if (jmlpasang >= intqty) {
				swal({
					type: 'warning',
					title: 'Cutting sudah mencapai target PO !',
				});
				$('#decct5').val('');
				$('#intlayer5').val('');
				$('#hasil5').text(jmlpasang + ' / ' + intqty);
				$('#intpasang5').val('');
				document.getElementById("intpasang5").disabled = true;
			} else {
				$('#decct5').val(decct);
				$('#intlayer5').val(intlayer);
				$('#hasil5').text('' + jmlpasang + ' / ' + intqty);
				$('#intpasang5').val('');
				document.getElementById("intpasang5").disabled = false;
			}
		}
	});

	$('#intkomponen6').change(function(){
		var intkomponen = $(this).val();
		var vcpo        = $('#vcpo6').val();
		var intmodel    = $('#intmodel6').val();
		var intqty      = $('#intqty6').val();
		var base_url    = '<?=base_url("operator")?>';
		var decct       = $(this).children('option:selected').data('ct');
		var intlayer    = $(this).children('option:selected').data('layer');
		var jmlpasang   = $(this).children('option:selected').data('pasang');

		if (intkomponen == 0) {
			$('#decct6').val('');
			$('#intlayer6').val('');
			$('#hasil6').text('0/0');
			$('#intpasang6').val('');
			document.getElementById("intpasang6").disabled = true;
		} else {
			if (jmlpasang >= intqty) {
				swal({
					type: 'warning',
					title: 'Cutting sudah mencapai target PO !',
				});
				$('#decct6').val('');
				$('#intlayer6').val('');
				$('#hasil6').text(jmlpasang + ' / ' + intqty);
				$('#intpasang6').val('');
				document.getElementById("intpasang6").disabled = true;
			} else {
				$('#decct6').val(decct);
				$('#intlayer6').val(intlayer);
				$('#hasil6').text('' + jmlpasang + ' / ' + intqty);
				$('#intpasang6').val('');
				document.getElementById("intpasang6").disabled = false;
			}
		}
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
        var seconds = datenow.getSeconds();
        if (minutes < 10) { minutes = '0' + minutes}
        if (seconds < 10) { seconds = '0' + seconds}
        var time    = datenow.getHours() + ":" + minutes + ":" + seconds;

		var base_url = '<?=base_url("operator")?>';
		var _session     = JSON.parse(localStorage.getItem('session'));
		var _intshift    = _session.intshift;
		var _intmesin    = _session.intmesinop;

		$.ajax({
			url: base_url + '/add_finish_cutting/' + _intmesin + '/' + _intshift,
			method: "GET"
			})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var dttime   = jsonData.dttime;

			var timeCounting = localStorage.getItem('timeCounting');
			var datacounting = JSON.parse(timeCounting);

			var timeCutting = { 'dtstart': datacounting.dtstart, 'dtfinish': dttime};
			localStorage.setItem('timeCutting', JSON.stringify(timeCutting));

			var timeCounting = { 'dtstart': dttime, 'counttipe': 1 , 'dtstart_temp': datacounting.dtstart};
			localStorage.setItem('timeCounting', JSON.stringify(timeCounting));

			$('#loadoutput').addClass('hidden');
			$('#reloadbutton').addClass('hidden');
			$('#simpanoutput').removeClass('hidden');
			$('#modalCutting').modal({ backdrop: 'static' },'show');		

			$('#btnoutput').addClass('hidden');
			$('#btnstartdowntime').text(dttime);
			$('#btndowntimefinish').removeClass('hidden');
			$('#btndowntime').attr('onClick', 'getlistdowntime()');

			// var _numpad = '<button type="button" class="btn btn-success btn-block" onclick="savenum('+1+')">Done</button>';
			// var label = (1 == 1) ? 'Input Pairs' : 'Input Reject';
			// $('#savenum').html(_numpad);
			// $('#numpadLabel').text(label);
			// $('#modalNumpad').modal('show');

		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
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
			url: base_url + '/addlembur_ajax/' + intjamlembur + '/' + _intshift + '/' + _intkaryawan + '/' + _intmesin + '/' + _intgedung,
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
			} else if (dataId == 5) {
				$('#intpasang5').val('');
			} else if (dataId == 6) {
				$('#intpasang6').val('');
			} else if (dataId == 11) {
				$('#intreject1').val('');
			} else if (dataId == 22) {
				$('#intreject2').val('');
			} else if (dataId == 33) {
				$('#intreject3').val('');
			} else if (dataId == 44) {
				$('#intreject4').val('');
			} else if (dataId == 55) {
				$('#intreject5').val('');
			} else if (dataId == 66) {
				$('#intreject6').val('');
			}  else if (dataId == 111) {
				$('#vcpo1').val('');
			} else if (dataId == 222) {
				$('#vcpo2').val('');
			} else if (dataId == 333) {
				$('#vcpo3').val('');
			} else if (dataId == 444) {
				$('#vcpo4').val('');
			} else if (dataId == 555) {
				$('#vcpo5').val('');
			} else if (dataId == 666) {
				$('#vcpo6').val('');
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
			} else if (dataId == 5) {
				var _intpasang  = $('#intpasang5').val();
				    _intpasang += value;
				$('#intpasang5').val(_intpasang);
			} else if (dataId == 6) {
				var _intpasang  = $('#intpasang6').val();
				    _intpasang += value;
				$('#intpasang6').val(_intpasang);
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
			} else if (dataId == 55) {
				var _intreject  = $('#intreject5').val();
				    _intreject += value;
				$('#intreject5').val(_intreject);
			} else if (dataId == 66) {
				var _intreject  = $('#intreject6').val();
				    _intreject += value;
				$('#intreject6').val(_intreject);
			} else if (dataId == 111) {
				var _vcpo  = $('#vcpo1').val();
				    _vcpo += value;
				$('#vcpo1').val(_vcpo);
			} else if (dataId == 222) {
				var _vcpo  = $('#vcpo2').val();
				    _vcpo += value;
				$('#vcpo2').val(_vcpo);
			} else if (dataId == 333) {
				var _vcpo  = $('#vcpo3').val();
				    _vcpo += value;
				$('#vcpo3').val(_vcpo);
			} else if (dataId == 444) {
				var _vcpo  = $('#vcpo4').val();
				    _vcpo += value;
				$('#vcpo4').val(_vcpo);
			} else if (dataId == 555) {
				var _vcpo  = $('#vcpo5').val();
				    _vcpo += value;
				$('#vcpo5').val(_vcpo);
			} else if (dataId == 666) {
				var _vcpo  = $('#vcpo6').val();
				    _vcpo += value;
				$('#vcpo6').val(_vcpo);
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
			localStorage.removeItem('timeCounting');
			localStorage.removeItem('timeCutting');

			$('#logoutoperator').submit();
		}
	}
</script>