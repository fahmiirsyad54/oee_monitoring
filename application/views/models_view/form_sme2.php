<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">Add SME 2</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<div class="table-responsive">
				<form method="POST" id="postdata" action="<?=base_url($controller . '/simpansme2/' . $intmodel)?>">
					<table class="table table-bordered table-striped table-hover">
						<thead>
							<tr>
								<th>Process Group</th>
								<th>Machine / Technology</th>
								<th>Applicable</th>
								<th>Comply</th>
							</tr>
						</thead>
						<tbody>
							<?php
								foreach ($dataSME2 as $opt) {
									$applicablechecked = ($opt->intapplicable == 1) ? 'checked' : '' ;
									$teknologichecked  = ($opt->intcomply == 1) ? 'checked' : '' ;
							?>
							<tr class="teknologimesin">
								<input type="hidden" name="intprosesgroup[]" class="intprosesgroup" value="<?=$opt->intprosesgroup?>">
								<input type="hidden" name="intteknologimesin[]" class="intteknologimesin" value="<?=$opt->intteknologimesin?>">
								<input type="hidden" name="intapplicable[]" class="intapplicable" value="<?=$opt->intapplicable?>">
								<input type="hidden" name="intcomply[]" class="intcomply" value="<?=$opt->intcomply?>">
								<td><?=$opt->vcprosesgroup?></td>
								<td><?=$opt->vcteknologimesin?></td>
								<td align="center">
									<input type="checkbox" class="intapplicablecheck" name="intapplicablecheck[]" <?=$applicablechecked?>>
								</td>
								<td align="center">
									<input type="checkbox" class="intcomplycheck" name="intcomplycheck[]" <?=$teknologichecked?>>
								</td>
							</tr>
							<?php
								}
							?>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="modal-footer">
	<!-- <a href="<?=base_url($controller . '/edit/' . $dataMain[0]->intid)?>" class="btn btn-warning"><i class="fa fa-pencil"></i>Edit</a> -->
	<button type="button" class="btn btn-success" onclick="simpanData()"><i class="fa fa-save"></i> Save</button>
	<button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-remove"></i>Close</button>
</div>

<script type="text/javascript">
	function simpanData(){
		$('#postdata').submit();
	}

	$('.intapplicablecheck').change(function(){
		var row = $(this).closest(".teknologimesin");
		if ($(this).is(':checked')) {
		    row.find('.intapplicable').val(1);
		} else {
		    row.find('.intapplicable').val(0);
		}
	});

	$('.intcomplycheck').change(function(){
		var row = $(this).closest(".teknologimesin");
		if ($(this).is(':checked')) {
		    row.find('.intcomply').val(1);
		} else {
		    row.find('.intcomply').val(0);
		}
	});
</script>