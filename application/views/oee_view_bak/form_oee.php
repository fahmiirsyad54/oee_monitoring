<div class="row">
	<div class="col-md-12">
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label>Overtime</label>
					<select name="intlembur" class="form-control" id="intlembur">
						<option data-nama="" value="0">-- Select Overtime --</option>
						<?php
							foreach ($listlembur as $opt) {
								$selected = ($intlembur == $opt->intid) ? 'selected' : '' ;
						?>
						<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intnilai?>"><?=$opt->vcnama?></option>
						<?php
							}
						?>
					</select>
				</div>
				<div class="row">
					<div class="col-md-6">
						 <div class="form-group">
							<label>Planned Stop</label>
							<input type="text" name="decplanned_stop" placeholder="Name" class="form-control" id="decplanned_stop" required value="<?=$decplanned_stop?>" readonly />
		                </div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Machine breakdown</label>
							<input type="text" name="decmachine_breakdown" placeholder="Name" class="form-control" id="decmachine_breakdown" required value="<?=$decmachine_breakdown?>" />
		                </div>
					</div>
				</div>

				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label>Available Time</label>
							<input type="text" name="decavailable_time" placeholder="Name" class="form-control" id="decavailable_time" required value="<?=$decavailable_time?> "readonly/>
		                </div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label>Idle Time</label>
							<input type="text" name="decidle_time" placeholder="Name" class="form-control" id="decidle_time" required value="<?=$decidle_time?>" />
		                </div>
					</div>
				</div>
				<div class="form-group">
					<label>Planned Product</label>
					<input type="text" name="decplanned_product" placeholder="Name" class="form-control" id="decplanned_product" required value="<?=$decplanned_product?>" readonly />
                </div>
            </div>

            <div class="col-md-2">
            	<div class="form-group">
					<label>Change Over</label>
					<input type="text" name="decchange_over" placeholder="Name" class="form-control" id="decchange_over" required value="<?=$decchange_over?>" />
                </div>
                <div class="form-group">
					<label>Start Up</label>
					<input type="text" name="decstartup" placeholder="Name" class="form-control" id="decstartup" required value="<?=$decstartup?>" readonly/>
                </div>
                <div class="form-group">
					<label>Shutdown</label>
					<input type="text" name="decshutdown" placeholder="Name" class="form-control" id="decshutdown" required value="<?=$decshutdown?>" readonly/>
                </div>
				<div class="form-group">
					<label>total Downtime</label>
					<input type="text" name="dectotal_downtime" placeholder="Name" class="form-control dectotal_downtime" id="dectotal_downtime" required value="<?=$dectotal_downtime?>" readonly />
				</div>
            </div>

            <div class="col-md-2">
            	<div class="form-group">
					<label>Theoritical Cycle Time</label>
					<input type="text" name="deccycle_time" placeholder="Name" class="form-control" id="deccycle_time" required value="<?=$deccycle_time?>" />
                </div>
                <div class="form-group">
					<label>Run Time</label>
					<input type="text" name="decrun_time" placeholder="Name" class="form-control decrun_time" id="decrun_time" required value="<?=$decrun_time?>" readonly />
                </div>
                <div class="form-group">
					<label>Availability Factor</label>
					<input type="text" name="decaf" placeholder="Name" class="form-control" id="decaf" required value="<?=$decaf?>" readonly />
                </div>
                <div class="form-group">
					<label>Theoritical Output</label>
					<input type="text" name="decoutput" placeholder="Name" class="form-control" id="decoutput" required value="<?=$decoutput?>" readonly/>
                </div>
                
            </div>
            
            <div class="col-md-2">
            	<div class="form-group">
					<label>Actual Output</label>
					<input type="text" name="decactual_output" placeholder="Name" class="form-control" id="decactual_output" required value="<?=$decactual_output?>" />
                </div>
                 <div class="form-group">
					<label>Peformance Factor</label>
					<input type="text" name="decpf" placeholder="Name" class="form-control" id="decpf" required value="<?=$decpf?>" readonly/>
                </div>
                 <div class="form-group">
					<label>Quality Factor</label>
					<input type="text" name="decqf" placeholder="Name" class="form-control" id="decqf" required value="<?=$decqf?>" readonly />
                </div>
            </div>

			<div class="col-md-2">
				<div class="form-group">
					<label>Reject</label>
					<input type="text" name="decreject" placeholder="Name" class="form-control" id="decreject" required value="<?=$decreject?>" />
                </div>
                <!-- <div class="form-group">
					<label>OEE</label>
					<input type="text" name="decoee" placeholder="Name" class="form-control" id="decoee" required value="<?=$decoee?>" readonly/>
                </div> -->
                <div class="form-group">
					<label>OEE</label>
					<input type="text" name="decoeeori" placeholder="Name" class="form-control" id="decoeeori" required value="<?=$decoeeori?>" readonly/>
                </div>
			</div>
			<input type="hidden" name="decafori" placeholder="Name" class="form-control" id="decafori" required value="<?=$decafori?>"/>
			<input type="hidden" name="decpfori" placeholder="Name" class="form-control" id="decpfori" required value="<?=$decpfori?>"/>
			<input type="hidden" name="decqfori" placeholder="Name" class="form-control" id="decqfori" required value="<?=$decqfori?>"/>
			<input type="hidden" name="decoutputori" placeholder="Name" class="form-control" id="decoutputori" required value="<?=$decoutputori?>"/>
		</div>	
	</div>
</div>

<script type="text/javascript">
	$('#intlembur').change(function() {

		var _intnilai = $(this).val();
		var _decplanned_stop      = $('#decplanned_stop').val();

		var _decavailable_time = parseInt(480) + parseInt(_intnilai);
		if (_intnilai == 0) {
			_decavailable_time = _decavailable_time
		}

		$('#decavailable_time').val(_decavailable_time);

		var _decplanned_product = parseInt(_decavailable_time) - parseInt(_decplanned_stop);

		$('#decplanned_product').val(_decplanned_product);

		$('#decchange_over').keyup(function() {

		var _decchange_over       = $(this).val();
		var _decstartup           = $('#decstartup').val();
		var _decshutdown          = $('#decshutdown').val();
		var _decmachine_breakdown = $('#decmachine_breakdown').val();
		var _decidle_time         = $('#decidle_time').val();
		var _decplanned_product   = $('#decplanned_product').val();

		var _dectotal_downtime = parseInt(_decchange_over)+parseInt(_decstartup)+parseInt(_decshutdown)+parseInt(_decmachine_breakdown)+parseInt(_decidle_time);
		
		$('#dectotal_downtime').val(_dectotal_downtime);

		var _decrun_time = parseInt(_decplanned_product) - parseInt(_dectotal_downtime);

		$('#decrun_time').val(_decrun_time);
		
		

		var _decaf =_decrun_time/_decplanned_product*100;

		$('#decaf').val(_decaf.toFixed(0));

		$('#decafori').val(_decaf);

		});

	$('#deccycle_time').keyup(function() {
		var _deccycle_time = $(this).val();
		var _decrun_time = $('#decrun_time').val();

		var _decoutput = 60/_deccycle_time *_decrun_time;

		$('#decoutput').val(_decoutput.toFixed(0));
		$('#decoutputori').val(_decoutput);
	});

	$('#decactual_output').keyup(function() {
		var _decactual_output = $(this).val();
		var _decoutput        = $('#decoutput').val();
		var _decoutputori        = $('#decoutputori').val();

		var _decpf = (parseInt(_decactual_output)/parseInt(_decoutput)) * parseInt(100);
		var _decpfori = (parseInt(_decactual_output)/parseInt(_decoutputori)) * parseInt(100);

		$('#decpf').val(_decpf.toFixed(0));
		$('#decpfori').val(_decpfori);

		$('#decreject').keyup(function(){
		var _decreject        = $(this).val();
		var _decactual_output = $('#decactual_output').val();
		var _decaf            = $('#decaf').val();
		var _decpf            = $('#decpf').val();

		var _decqf = (_decactual_output-_decreject) / _decactual_output *100;

		$('#decqf').val(_decqf.toFixed(0));
		$('#decqfori').val(_decqf);

		var _decafori            = $('#decafori').val();
		var _decpfori            = $('#decpfori').val();
		var _decqfori            = $('#decqfori').val();

		var _decoee = (_decaf*_decpf*_decqf)/10000;
		var _decoeeori = (_decafori*_decpfori*_decqfori)/10000;

		$('#decoee').val(_decoee.toFixed(1));
		$('#decoeeori').val(_decoeeori.toFixed(1));
		});
	});

	
	});

</script>