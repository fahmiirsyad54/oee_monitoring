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

		<?php
		 	$mesin =1;
		    if ($mesin == 1) {
		      $comelz = 'active';
		      $laser = '';
		    } elseif ($mesin == 2) {
		      $comelz = '';
		      $laser = 'active';
		    }
		  ?>
		<div class="row">
			<div class="col-md-12">
				<div class="box-body">
					<div class="nav-tabs-custom">
						<ul class="nav nav-tabs">
			              <li class="<?=$comelz?>"><a href="#comelz" data-toggle="tab">Comelz</a></li>
			              <li class="<?=$laser?>"><a href="#laser" data-toggle="tab">Laser</a></li>
			            </ul>

			            <div class="tab-content">
			            	<div class="tab-pane <?=$comelz?>" id="comelz">
			            		<div class="row">
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
			            	</div>

			            	<div class="tab-pane <?=$laser?>" id="laser">
			            		<div class="row">
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
														foreach ($dataDetail2 as $detail2) {
															if ($detail2['intlayer'] == 2) {
																$vclayer2 = '2 Layer';
															} elseif ($detail2['intlayer'] == 4) {
																$vclayer2 = '4 Layer';
															} elseif ($detail2['intlayer'] == 6) {
																$vclayer2 = '6 Layer';
															} elseif ($detail2['intlayer'] == 8) {
																$vclayer2 = '8 Layer';
															}
													?>
													<tr>
														<td><?=$detail2['vckomponen']?></td>
														<td><?=$vclayer2?></td>
															<?php
																foreach ($detail2['datact'] as $datact2) {
															?>
														<td><?=$datact2->deccycle_time?></td>

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
			            	</div>
			            	
			            </div>
					</div>
				</div>
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