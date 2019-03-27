<?php
    $hidenamamesin = ($intmesin == 0) ? 'hidden' : '';
    $hidekodemesin = ($intmesin == 0) ? '' : 'hidden';
?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <div class="box box-info">
            <div class="box-header">
            <h3 class="box-title">Autonomus Form</h3>
            </div>
            <div class="box-body">
                <form action="<?=base_url($controller)?>/aksi/Add" method="POST">
                    <div class="row">
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="">Date</label>
                                <input type="text" name="dttanggal" placeholder="Date order" class="form-control" id="datepicker" required value="<?=$dttanggal?>" />
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="">Machine</label>
                                <input type="text" class="form-control <?=$hidenamamesin?>" name="" value="<?=$vcnamamesin?>" readonly>
                                <input type="text" class="form-control <?=$hidekodemesin?>" name="vckodemesin" value="<?=$vckodemesin?>" readonly>
                                <input type="hidden" name="intmesin" value="<?=$intmesin?>">
                            </div>
                        </div>
                    
                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                            <label for="">Building</label>
                                <select name="intgedung" id="intgedung" class="form-control">
                                    <option value="0">-- Select Building --</option>
                                    <?php
                                        foreach ($listgedung as $opt) {
                                            $selected = ($opt->intid == $intgedung) ? 'selected' : '';
                                    ?>
                                    <option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                            <label for="">Cell</label>
                                <select name="intcell" id="intcell" class="form-control">
                                    <option value="0">-- Select Cell --</option>
                                    <?php
                                        foreach ($listcell as $opt) {
                                            $selected = ($opt->intid == $intcell) ? 'selected' : '';
                                    ?>
                                    <option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="">Operator</label>
                                <div id="operatorloading">
                                    <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
                                </div>
                                <select class="form-control select2" name="intoperator" id="intoperator">
                                    <option value="0">-- Select Operator --</option>
                                    <?php
                                        foreach ($listmachine as $opt) {
                                            $selected = ($opt->intid == $intoperator) ? 'selected' : '';
                                    ?>
                                    <option <?=$selected?> value="<?=$opt->intid?>"><?=$opt->vckode . ' - ' . $opt->vcnama?></option>
                                    <?php
                                        }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                            <label for="">Form</label>
                                <select name="intformterisi" id="" class="form-control">
                                    <option value="0">0</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="30">40</option>
                                    <option value="50">50</option>
                                    <option value="60">60</option>
                                    <option value="70">70</option>
                                    <option value="80">80</option>
                                    <option value="90">90</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                            <label for="">Implementation</label>
                                <select name="intimplementasi" id="" class="form-control">
                                    <option value="0">0</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                    <option value="30">30</option>
                                    <option value="30">40</option>
                                    <option value="50">50</option>
                                    <option value="60">60</option>
                                    <option value="70">70</option>
                                    <option value="80">80</option>
                                    <option value="90">90</option>
                                    <option value="100">100</option>
                                </select>
                            </div>
                        </div>

                        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <label for="">Remark</label>
                                <input type="text" name="vcketerangan" class="form-control" name="operator" placeholder="Remark">
                            </div>
                        </div>

                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group">
                                <button type="submit" class="pull-right btn btn-default" id="sendEmail">Save</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
		$('.select2').select2();

		$('#datepicker').datepicker({
	      	autoclose: true,
	      	format: 'dd-mm-yyyy'
    	})

		var base_url = '<?=base_url($controller)?>';

		$('.select2').addClass('hidden');
		$('#machineloading').removeClass('hidden');
		$('#operatorloading').removeClass('hidden');
		// $('#intoperator').addClass('hidden');

	    $.ajax({
			url: base_url + '/getmesinoperatorajax',
			method: "GET"
		})
		.done(function( data ) {
			var jsonData     = JSON.parse(data);
			var intoperator  = <?=$intoperator?>;
			var dataoperator = jsonData.dataoperator;
			var htmloperator = '<option value="0">-- Select Operator --</option>';

			for (var i = 0; i < dataoperator.length; i++) {
				var _selected = (dataoperator[i].intid == intoperator) ? 'selected' : '';
				htmloperator += '<option  ' + _selected + '  value="'+dataoperator[i].intid+'">'+ dataoperator[i].vckode + ' - ' + dataoperator[i].vcnama + '</option>';
			}

			$('#intoperator').html(htmloperator);
			$('#operatorloading').addClass('hidden');
			$('.select2').removeClass('hidden');
			// $('#intoperator').removeClass('hidden');
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
    });
    
    $('#intgedung').change(function(){
		var base_url = '<?=base_url($controller)?>';
		var intid    = $(this).val();
		$.ajax({
			url: base_url + '/getcellajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Cell --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="'+jsonData[i].intid+'">'+jsonData[i].vcnama+'</option>';
			}
			$('#intcell').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});
</script>