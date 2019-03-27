<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Data Models</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<label>Data</label>
			<label class="pull-right">Status : <span class="label label-<?=$dataMain[0]->vcstatuswarna?>"><?=$dataMain[0]->vcstatus?></span></label>
		</div>

		<div class="col-md-8">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Name Models :</label></td>
						<td><?=$dataMain[0]->vcnama?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<thead>
						<tr>
							<th>Component</th>
							<th>Standard Layer</th>
							<th>2 Layer</th>
							<th>4 Layer</th>
							<th>6 Layer</th>
							<th>8 Layer</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$loop = 0;
							foreach ($dataDetail as $detail) {
								if ($detail['intlayer'] == 2) {
									$vclayer = '2 Layer';
								} elseif ($detail['intlayer'] == 4) {
									$vclayer = '4 Layer';
								} elseif ($detail['intlayer'] == 6) {
									$vclayer = '6 Layer';
								} elseif ($detail['intlayer'] == 8) {
									$vclayer = '8 Layer';
								}
						?>
						<tr>
							<td><?=$detail['vckomponen']?></td>
							<td><?=$vclayer?></td>
								<?php
									foreach ($detail['datact'] as $datact) {
								?>
							<td><?=$datact->deccycle_time?></td>

								<?php
									}
								?>
						</tr>
						<?php
								$loop++;
								
							}
						?>
					</tbody>
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
							<th>user</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
							foreach ($dataHistory as $history) {
						?>
						<tr>
							<td><?=date('d-m-Y H:i:s',strtotime($history->dtupdate))?></td>
							<td><?=$history->user?></td>
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