<div class="row"> 
	<div class="col-md-12">
		<div class="box">
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div>

			<div class="box-body">
				<div class="row">
					<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
						<div class="col-md-3">
							<div class="form-group">
								<label>Date</label>
								<input type="text" name="dttanggal" placeholder="dttanggal" class="form-control dttanggal" id="dttanggal" value="<?=$dttanggal?>" required/>
							</div>
						</div>

						<div class="col-md-3">	
							<div class="form-group">
								<label>Building</label>
								<select name="intgedung" class="form-control intgedung select2" id="intgedung" required>
									<option value="0">-- Select Building --</option>
									<?php
										foreach ($listgedung as $opt) {
									?>
									<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Cell Capa/day</label>
								<input type="text" name="inttarget" placeholder="" class="form-control inttarget" id="inttarget" value="" required />
							</div>
						</div>

						<div class="box-body">
							<div class="row">
								<div class="col-md-12">
									<div class="after-add-more">
										<div class="row control-group">
											<div class="col-md-3">	
												<div class="form-group">
													<label>Models</label>
													<select name="intmodel[]" class="form-control intmodel select2" id="intmodel">
														<option value="0">-- Select Models --</option>
														<?php
															foreach ($listmodels as $opt) {
														?>
														<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
														<?php
															}
														?>
													</select>
												</div>
											</div>

											<div class="col-md-2">
												<div class="form-group">
													<label>Cell Qty</label>
													<input type="text" name="intcell[]" placeholder="" class="form-control intcell" id="intcell" value="" required />
												</div>
											</div>
											
											<div class="col-md-2">
												<div class="form-group">
													<button class="btn btn-success margin-top-25" onclick="addmore()" type="button"><i class="glyphicon glyphicon-plus"></i></button>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i>Cancel</a>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2()

		$(".dttanggal").datetimepicker({locale: 'id'});
	});
	
	function simpanData(action) {
		var vcnama       = $('#vcnama').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {'vcnama' : vcnama};
			var formdata = {'vcnama' : vcnama};

			$.ajax({
				url: base_url + '/validasiform/required',
				method: "POST",
				data : formrequired
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				if (jsonData.length > 0) {
					var html = '';
					for (var i = 0; i < jsonData.length; i++) {
						html += '' + jsonData[i].error + '<br/>';
					}

					swal({
						type: 'error',
						title: 'There is something wrong',
						html: html
					});
				} else {
					$.ajax({
						url: base_url + '/validasiform/data',
						method: "POST",
						data : formdata
					})
					.done(function( data ) {
						var jsonData = JSON.parse(data);
						if (jsonData.length > 0) {
							var html = '';
							for (var i = 0; i < jsonData.length; i++) {
								html += '' + jsonData[i].error + '<br/>';
							}

							swal({
								type: 'error',
								title: 'There is something wrong',
								html: html
							});
						} else {
							$('#postdata').submit()
						}
					})
					.fail(function( jqXHR, statusText ) {
						alert( "Request failed: " + jqXHR.status );
					});
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		} else if (action == 'Edit') {
			$('#postdata').submit();
		}
	}

	function addmore(){
		var html = $(".copy-fields").html();
	  		// $(".after-add-more").append(html);
	  		var base_url = '<?=base_url($controller)?>';
	  		$.ajax({
				url: base_url + '/form_detail_models',
				method: "GET"
			})
			.done(function( data ) {
				$(".after-add-more").append(data);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
	}
		$(document).ready(function() {
			//here first get the contents of the div with name class copy-fields and add it to after "after-add-more" div class.
		  	$(".add-more").click(function(){ 
		    	var html = $(".copy-fields").html();
		      	$(".after-add-more").append(html);
		  	});
			//here it will remove the current value of the remove button which has been pressed
		  	$("body").on("click",".remove",function(){ 
		      	$(this).parents(".control-group").remove();
		  	});
		});

</script>