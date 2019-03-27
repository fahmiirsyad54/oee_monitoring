<?php
	foreach ($listteknologimesin as $opt) {
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
	<td>
		<input type="text" name="vcketerangan[]" class="form-control">
	</td>
</tr>
<?php
	}
?>

<script type="text/javascript">
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