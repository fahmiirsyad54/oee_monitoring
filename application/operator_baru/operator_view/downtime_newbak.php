<link rel="stylesheet" href="<?=BASE_URL_PATH?>assets/plugins/toastr/build/toastr.css">
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-info" style="border-radius: 0px; background-color: #357ca5 !important; border-color: #357ca5 !important">
			<span id="realtime"></span> <span>- <?=$intshift?></span>
			<div class="pull-right">
				<span style="padding-right: 20px; ">ID Mesin : <?=$this->session->vckodemesin?></span>
				<span style="padding-right: 20px; ">Operator : <?=$this->session->vckaryawan . ' (' . $this->session->vcnik . ')'?></span>
				<span style="padding-right: 20px; " id="jamkerja">Jam Kerja : <?=$jammasuk . ' s/d ' . $jamkeluar?></span>
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

							<!-- <div class="col-md-3 text-center">
								<i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-top-25 hidden loadingdt"></i>
								<a href="javascript:void(0)" onclick="simpandowntime()" class="btn btn-success btn-block margin-top-25 savedtbtn"><i class="fa fa-save"></i> Save</a>
							</div> -->
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
								<?php
									if (count($datadowntime) == 0) {
								?>
								<tr>
									<td colspan="6" align="center">Data Not Found</td>
								</tr>
								<?php
									} else {
										foreach ($datadowntime as $downtime) {
								?>
								<tr>
									<td><?=$downtime->vcdowntime?></td>
									<td><?=$downtime->dtmulai?></td>
									<td><?=$downtime->dtselesai?></td>
									<td><?=$downtime->vcmekanik?></td>
									<td><?=$downtime->vcsparepart?></td>
									<td><?=$downtime->intjumlah?></td>
								</tr>
								<?php
									}
									}
								?>
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
						<!-- <div class="row">
							<div class="col-md-4">
								<div class="form-group">
									<label>Model</label>
									<select name="intmodel" class="form-control select2" id="intmodel">
										<option data-nama="" value="0">-- Select Model --</option>
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
									<label>Component</label>
									<select name="intkomponen" class="form-control select2" id="intkomponen">
										<option data-ct="0" value="0">-- Select Component --</option>
									</select>
									<input type="hidden" name="decct" id="decct" class="form-control">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang" id="intpasang" class="form-control">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject" id="intreject" class="form-control">
								</div>
							</div>
						</div> -->

						<div class="row hidden" id="btnoutput">
							<div class="col-md-4">
								<a href="javascript:void(0)" class="btn btn-primary btn-block" id="btnstartoutput">Mulai</a>
								<input type="hidden" name="dtmulai" placeholder="Start" class="form-control" id="dtmulaioutput" required value="" />
							</div>

							<div class="col-md-4">
								<a href="javascript:void(0)" onclick="finishcutting()" class="btn btn-primary btn-block">Selesai</a>
							</div>
						</div>
						
						<!-- <div class="row">
							<div class="col-md-2 col-md-offset-4">
								<div class="form-group">
									<label>Start</label>
									<input type="text" name="dtmulai" placeholder="Start" class="form-control datetimepicker1" id="dtmulaioutput" required value="" />
			                	</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
					                <label>Finish</label>
						            <input type="text" name="dtselesai" placeholder="Finish" class="form-control datetimepicker1" id="dtselesaioutput" required value="" />
								</div>
							</div>

							<div class="col-md-2 text-center">
								<i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-top-25 hidden loadingoutput"></i>
								<a href="javascript:void(0)" onclick="simpanoutput()" class="btn btn-success btn-block  margin-top-25 saveoutputbtn"><i class="fa fa-save"></i> Save</a>
							</div>

							<div class="col-md-2 text-center">
								<i class="fa fa-spinner fa-pulse fa-2x fa-fw margin-top-25 hidden loadingoutput"></i>
								<a href="javascript:void(0)" onclick="resetoutput()" class="btn btn-danger btn-block  margin-top-25 saveoutputbtn"><i class="fa fa-refresh"></i> Reset</a>
							</div>
						</div> -->
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
								<?php
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
								?>
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
							<button class="btn btn-success margin-top-15" type="submit"><i class="fa fa-send"></i> Kirim</button>
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
				<form method="POST" action="<?=base_url('akses/logoutop')?>">
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
							<button class="btn btn-danger margin-top-15 pull-right" type="submit"><i class="fa fa-send"></i> Logout</button>
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
	<div class="modal-dialog modal-lg">
		<!-- Modal content -->
		<div class="modal-content">
			<div class="modal-header">
				<!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button> -->
				<h4 class="modal-title" id="numpadLabel">Masukkan Komponen</h4>
			</div>
			<div class="modal-body">
				<!-- Component 1 -->
				<div class="row">
					<div class="col-md-3">
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

					<div class="col-md-3">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen1">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct1" class="form-control">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<select name="intlayer1" id="intlayer1" class="form-control">
								<option value="0">-- Pilih Layer --</option>
							</select>
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
					<div class="col-md-3">
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

					<div class="col-md-3">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen2">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct2" class="form-control">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<select name="intlayer2" id="intlayer2" class="form-control">
								<option value="0">-- Pilih Layer --</option>
							</select>
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
					<div class="col-md-3">
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

					<div class="col-md-3">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen3">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct3" class="form-control">
						</div>
					</div>
					
					<div class="col-md-2">
						<div class="form-group">
							<select name="intlayer3" id="intlayer3" class="form-control">
								<option value="0">-- Pilih Layer --</option>
							</select>
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
					<div class="col-md-3">
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

					<div class="col-md-3">
						<div class="form-group">
							<select name="intkomponen" class="form-control" id="intkomponen4">
								<option data-ct="0" value="0">-- Pilih Komponen --</option>
							</select>
							<input type="hidden" name="decct" id="decct4" class="form-control">
						</div>
					</div>

					<div class="col-md-2">
						<div class="form-group">
							<select name="intlayer4" id="intlayer4" class="form-control">
								<option value="0">-- Pilih Layer --</option>
							</select>
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
					<div class="col-md-offset-6">
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
				<button type="button" class="btn btn-success" onclick="simpanoutput()">Simpan</button>
				<!-- <button type="button" data-dismiss="modal" class="btn btn-default">Close</button> -->
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?=BASE_URL_PATH?>assets/plugins/toastr/toastr.js"></script>


<script type="text/javascript">

	// Set Default Page
	$(function () {
	    $('.select2').select2();

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
			var data     = {
							'inttype_downtime': inttype_downtime,
							'inttype_list'    : inttype_list,
							'dtmulai'         : dtmulai,
							'dtselesai'       : dtselesai,
							'intmekanik'      : intmekanik,
							'intsparepart'    : intsparepart,
							'intjumlah'       : intjumlah
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
				$('#btnstart').text('Start');
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
					$('#btnstartdowntime').text('Start');
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
		$('#btnstart').text('Start');
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
		
		var intmodel2    = $('#intmodel2').val();
		var intkomponen2 = $('#intkomponen2').val();
		var decct2       = $('#decct2').val();
		var dtmulai2     = datacutting.dtstart;
		var dtselesai2   = datacutting.dtfinish;
		var intpasang2   = $('#intpasang2').val();
		var intreject2   = $('#intreject2').val();

		var intmodel3    = $('#intmodel3').val();
		var intkomponen3 = $('#intkomponen3').val();
		var decct3       = $('#decct3').val();
		var dtmulai3     = datacutting.dtstart;
		var dtselesai3   = datacutting.dtfinish;
		var intpasang3   = $('#intpasang3').val();
		var intreject3   = $('#intreject3').val();

		var intmodel4    = $('#intmodel4').val();
		var intkomponen4 = $('#intkomponen4').val();
		var decct4       = $('#decct4').val();
		var dtmulai4     = datacutting.dtstart;
		var dtselesai4   = datacutting.dtfinish;
		var intpasang4   = $('#intpasang4').val();
		var intreject4   = $('#intreject4').val();

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
		} else if (intpasang == '' || intpasang == 0 || intpasang < 0) {
			swal({
					type: 'error',
					title: 'Jumlah Actual Harus Diisi, dan tidak boleh kurang dari 0 !!'
				});
		} else {
			$('.loadingoutput').removeClass('hidden');
			$('.saveoutputbtn').addClass('hidden');
			var data     = {
							'intmodel'    : intmodel,
							'intkomponen' : intkomponen,
							'decct'       : decct,
							'intpasang'   : intpasang,
							'intreject'   : intreject,
							'dtmulai'     : dtmulai,
							'dtselesai'   : dtselesai,
							'intmodel2'   : intmodel2,
							'intkomponen2': intkomponen2,
							'decct2'      : decct2,
							'intpasang2'  : intpasang2,
							'intreject2'  : intreject2,
							'dtmulai2'    : dtmulai2,
							'dtselesai2'  : dtselesai2,
							'intmodel3'   : intmodel3,
							'intkomponen3': intkomponen3,
							'decct3'      : decct3,
							'intpasang3'  : intpasang3,
							'intreject3'  : intreject3,
							'dtmulai3'    : dtmulai3,
							'dtselesai3'  : dtselesai3,
							'intmodel4'   : intmodel4,
							'intkomponen4': intkomponen4,
							'decct4'      : decct4,
							'intpasang4'  : intpasang4,
							'intreject4'  : intreject4,
							'dtmulai4'    : dtmulai4,
							'dtselesai4'  : dtselesai4
						}
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

				$('#modalCutting').modal('hide');

			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
			// $('#saveoutput').submit();
		}

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
		
		$.ajax({
			url: base_url + '/getkomponenct_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Pilih Layer --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" >' + jsonData[i].vcnama + '</option>'
			}

			$('#intlayer1').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intkomponen2').change(function(){
		var intkomponen = $(this).val();
		var intid       = $(this).children('option:selected').data('intid');
		var decct       = $(this).children('option:selected').data('ct');
		var base_url    = '<?=base_url("operator")?>';

		$('#decct2').val(decct);
		
		$.ajax({
			url: base_url + '/getkomponenct_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Pilih Layer --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" >' + jsonData[i].vcnama + '</option>'
			}

			$('#intlayer2').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intkomponen3').change(function(){
		var intkomponen = $(this).val();
		var intid       = $(this).children('option:selected').data('intid');
		var decct       = $(this).children('option:selected').data('ct');
		var base_url    = '<?=base_url("operator")?>';

		$('#decct2').val(decct);
		
		$.ajax({
			url: base_url + '/getkomponenct_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Pilih Layer --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" >' + jsonData[i].vcnama + '</option>'
			}

			$('#intlayer3').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intkomponen4').change(function(){
		var intkomponen = $(this).val();
		var intid       = $(this).children('option:selected').data('intid');
		var decct       = $(this).children('option:selected').data('ct');
		var base_url    = '<?=base_url("operator")?>';

		$('#decct4').val(decct);
		
		$.ajax({
			url: base_url + '/getkomponenct_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option data-nama="" value="0">-- Pilih Layer --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option data-ct="' + jsonData[i].deccycle_time + '" >' + jsonData[i].vcnama + '</option>'
			}

			$('#intlayer4').html(html)
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
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
		$.ajax({
			url: base_url + '/addlembur_ajax/' + intjamlembur,
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
</script>