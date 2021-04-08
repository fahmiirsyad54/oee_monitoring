<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Building Data</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<label>Data</label>
		</div>

		<div class="col-md-4">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Date</label></td>
						<td><?=date('d M Y', strtotime($dataMain[0]->dttanggal))?></td>
					</tr>

					<tr>
						<td><label>Week</label></td>
						<td><?=$dataMain[0]->intweek?></td>
					</tr>

					<tr>
						<td><label>Building</label></td>
						<td><?=$dataMain[0]->vcgedung?></td>
					</tr>

					<tr>
						<td><label>Cell</label></td>
						<td><?=$dataMain[0]->vccell?></td>
					</tr>

					<tr>
						<td><label>Model</label></td>
						<td><?=$dataMain[0]->vcmodel?></td>
					</tr>

					<tr>
						<td><label>Score</label></td>
						<td><?=round($dataMain[0]->decscore,0)?> %</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="col-md-8">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Procces Group</th>
							<th>Technology / Machine</th>
							<th>Applicable</th>
							<th>Comply</th>
							<th>Remark</th>
						</tr>
					</thead>
					<tbody>
						<?php
							$sumapplicable = 0;
							$sumcomply  = 0;
							foreach ($dataDetail as $opt) {
								$applicablechecked = ($opt->intapplicable == 1) ? 'checked' : '' ;
								$teknologichecked  = ($opt->intcomply == 1) ? 'checked' : '' ;
								if ($opt->intapplicable == 1) {
									$sumapplicable = $sumapplicable + 1;
								}
								if ($opt->intcomply == 1) {
									$sumcomply = $sumcomply + 1;
								}
						?>
						<tr class="teknologimesin">
							<td><?=$opt->vcprosesgroup?></td>
							<td><?=$opt->vcteknologimesin?></td>
							<td align="center">
								<input type="checkbox" class="intapplicablecheck disabled" name="intapplicablecheck[]" <?=$applicablechecked?>>
							</td>
							<td align="center">
								<input type="checkbox" class="intcomplycheck disabled" name="intcomplycheck[]" <?=$teknologichecked?>>
							</td>
							<td><?=$opt->vcketerangan?></td>
						</tr>
						<?php
							}
						?>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="2" style="text-align: right;">Total</th>
							<th style="text-align: center;"><?=$sumapplicable?></th>
							<th style="text-align: center;"><?=$sumcomply?></th>
							<th style="text-align: center;"></th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-md-12">
			<label>History</label>
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Date</th>
							<th>User</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($dataHistory as $history) {
						?>
						<tr>
							<td><?=date('d-m-Y H:i:s',strtotime($history->dtupdate))?></td>
							<td><?=$history->pengguna?></td>
							<td><?=$history->aksi?></td>
						</tr>
						<?php
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<a href="<?=base_url($controller . '/edit/' . $dataMain[0]->intid)?>" class="btn btn-warning"><i class="fa fa-pencil"></i>Edit</a>
	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i>Close</button>
</div>