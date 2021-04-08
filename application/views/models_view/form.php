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
								<label>Name</label>
								<input type="text" name="vcnama" placeholder="Model Name" class="form-control" id="vcnama" required value="<?=$vcnama?>" />
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
														<div class="after-add-more">
															<?php
																if (count($dataModels) == 0) {
															?>
																<div class="row control-group">
																	<div class="col-md-3">	
																		<div class="form-group">
																			<label>Component</label>
																			<select name="intkomponen[]" class="form-control intkomponen select2" id="intkomponen">
																				<option value="0">-- Select Component --</option>
																				<?php
																					foreach ($listkomponen as $opt) {
																				?>
																				<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
																				<?php
																					}
																				?>
																			</select>
																		</div>
																	</div>

																	<div class="col-md-3">	
																		<div class="form-group">
																			<label>Standard Layer</label>
																			<select class="form-control intlayer" name="intlayer[]" id="intlayer" >
																				<option data-nama="" value="">-- Select Standard Layer --</option>
																				<?php
																					foreach ($listlayer as $key => $value) {
																						$selected = ($key == $intlayer) ? 'selected' : '';
																				?>
																				<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
																				<?php
																					}
																				?>
																			</select>
																		</div>
																	</div>

																	<div class="col-md-1">
																		<div class="form-group">
																			<label>2 Layer</label>
																			<input type="text" name="deccycle_time[]" placeholder="" class="form-control deccycle_time" id="deccycle_time" value="" required />
																			<input type="hidden" name="intkomponenct[]" placeholder="" class="form-control intkomponenct" id="intkomponenct" value="" required />
																			<input type="hidden" name="vclayer[]" value="2 layer"/>
																			<input type="hidden" name="intlayerct[]" value="2"/>
																		</div>
																	</div>

																	<div class="col-md-1">
																		<div class="form-group">
																			<label>4 Layer</label>
																			<input type="text" name="deccycle_time[]" placeholder="" class="form-control deccycle_time" id="deccycle_time" value="" required />
																			<input type="hidden" name="intkomponenct[]" class="form-control intkomponenct" id="intkomponenct" value="" required />
																			<input type="hidden" name="vclayer[]" value="4 layer"/>
																			<input type="hidden" name="intlayerct[]" value="4"/>
																		</div>
																	</div>

																	<div class="col-md-1">
																		<div class="form-group">
																			<label>6 Layer</label>
																			<input type="text" name="deccycle_time[]" placeholder="" class="form-control deccycle_time" id="deccycle_time" value="" required />
																			<input type="hidden" name="intkomponenct[]" class="form-control intkomponenct" id="intkomponenct" value="" required />
																			<input type="hidden" name="vclayer[]" value="6 layer"/>
																			<input type="hidden" name="intlayerct[]" value="6"/>
																		</div>
																	</div>

																	<div class="col-md-1">
																		<div class="form-group">
																			<label>8 Layer</label>
																			<input type="text" name="deccycle_time[]" placeholder="" class="form-control deccycle_time" id="deccycle_time" value="" required />
																			<input type="hidden" name="intkomponenct[]" class="form-control intkomponenct" id="intkomponenct" value="" required />
																			<input type="hidden" name="vclayer[]" value="8 layer"/>
																			<input type="hidden" name="intlayerct[]" value="8"/>
																		</div>
																	</div>
																	<div class="col-md-1">
																		<div class="form-group">
																			<button class="btn btn-success margin-top-25" onclick="addmore()" type="button"><i class="glyphicon glyphicon-plus"></i></button>
																		</div>
																	</div>
																</div>
																	<?php
																		} else {

																		$loop = 0;
																		foreach ($dataModels as $models) {
																			$hideadd = ($loop == 0) ? '' : 'hidden' ;
																			$hideremove = ($loop == 0) ? 'hidden' : '' ;
																	?>
																		<input type="hidden" name="intmodelkomponen[]" value="<?=$models['intid']?>">
																		<div class="control-group input-group">
																			<div class="row">
																				<div class="col-md-3">	
																					<div class="form-group">
																						<label>Component</label>
																						<select name="intkomponen[]" class="form-control intkomponen select2">
																							<option value="0">-- Select Component --</option>
																							<?php
																								foreach ($models['listkomponen'] as $opt) {
																									$selected = ($opt->intid == $models['intkomponen']) ? 'selected' : '' ;
																							?>
																							<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
																							<?php
																								}
																							?>
																						</select>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Standard layer</label>
																						<select class="form-control intlayer" name="intlayer[]" id="intlayer" >
																							<option data-nama="" value="">-- Select Standard Layer --</option>
																							<?php
																								foreach ($models['listlayer'] as $key => $value) {
																									$selected = ($key == $models['intlayer']) ? 'selected' : '';
																							?>
																							<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
																							<?php
																								}
																							?>
																						</select>
																					</div>
																				</div>

																				<?php
																					$loopct = 0;
																					foreach ($models['datact'] as $datact) {
																				?>
																					<div class="col-md-1">
																						<div class="form-group">
																							<label><?=++$loopct * 2?> Layer</label>
																							<input type="text" name="deccycle_time[]" placeholder="<?=$loopct * 2?> Layer" class="form-control deccycle_time" id="deccycle_time" value="<?=$datact->deccycle_time?>" required />
																							<input type="hidden" name="intkomponenct[]" class="form-control intkomponenct" id="intkomponenct" value="<?=$models['intkomponen']?>" required />
																							<input type="hidden" name="vclayer[]" value="<?=$datact->vcnama?>"/>
																							<input type="hidden" name="intlayerct[]" value="<?=$datact->intlayerct?>"/>
																						</div>
																					</div>
																				<?php
																					}
																				?>
																				
																				<div class="col-md-1 margin-top-25">
																					<div class="form-group">
																						<a href="javascript:void(0)" class="btn btn-success <?=$hideadd?>" onclick="addmore()"><i class="fa fa-plus"></i></a>
																						<a href="javascript:void(0)" class="btn btn-danger <?=$hideremove?> remove"><i class="fa fa-remove"></i></a>
																					</div>
																				</div>
																			</div>
																		</div>
																		<?php
																$loop++;
																}
															}
															?>
														</div>
													</div>
							                	</div>
							                </div>

							                <div class="tab-pane <?=$laser?>" id="laser">
							                	<div class="row">
							                		<div class="col-md-12">
							                			<div class="after-add-more2">
							                				<?php
																if (count($dataModels2) == 0) {
															?>

															<div class="row control-group">
																<div class="col-md-3">	
																	<div class="form-group">
																		<label>Component</label>
																		<select name="intkomponen2[]" class="form-control intkomponen2 select2" id="intkomponen2">
																			<option value="0">-- Select Component --</option>
																			<?php
																				foreach ($listkomponen2 as $opt) {
																			?>
																			<option data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
																			<?php
																				}
																			?>
																		</select>
																	</div>
																</div>

																<div class="col-md-3">	
																	<div class="form-group">
																		<label>Standard Layer</label>
																		<select class="form-control intlayer2" name="intlayer2[]" id="intlayer2" >
																			<option data-nama="" value="">-- Select Standard Layer --</option>
																			<?php
																				foreach ($listlayer as $key => $value) {
																					$selected = ($key == $intlayer2) ? 'selected' : '';
																			?>
																			<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
																			<?php
																				}
																			?>
																		</select>
																	</div>
																</div>

																<div class="col-md-1">
																	<div class="form-group">
																		<label>2 Layer</label>
																		<input type="text" name="deccycle_time2[]" placeholder="" class="form-control deccycle_time2" id="deccycle_time2" value="" required />
																		<input type="hidden" name="intkomponenct2[]" placeholder="" class="form-control intkomponenct2" id="intkomponenct2" value="" required />
																		<input type="hidden" name="vclayer2[]" value="2 layer"/>
																		<input type="hidden" name="intlayerct2[]" value="2"/>
																	</div>
																</div>

																<div class="col-md-1">
																	<div class="form-group">
																		<label>4 Layer</label>
																		<input type="text" name="deccycle_time2[]" placeholder="" class="form-control deccycle_time2" id="deccycle_time2" value="" required />
																		<input type="hidden" name="intkomponenct2[]" class="form-control intkomponenct2" id="intkomponenct2" value="" required />
																		<input type="hidden" name="vclayer2[]" value="4 layer"/>
																		<input type="hidden" name="intlayerct2[]" value="4"/>
																	</div>
																</div>

																<div class="col-md-1">
																	<div class="form-group">
																		<label>6 Layer</label>
																		<input type="text" name="deccycle_time2[]" placeholder="" class="form-control deccycle_time2" id="deccycle_time2" value="" required />
																		<input type="hidden" name="intkomponenct2[]" class="form-control intkomponenct2" id="intkomponenct2" value="" required />
																		<input type="hidden" name="vclayer2[]" value="6 layer"/>
																		<input type="hidden" name="intlayerct2[]" value="6"/>
																	</div>
																</div>

																<div class="col-md-1">
																	<div class="form-group">
																		<label>8 Layer</label>
																		<input type="text" name="deccycle_time2[]" placeholder="" class="form-control deccycle_time2" id="deccycle_time2" value="" required />
																		<input type="hidden" name="intkomponenct2[]" class="form-control intkomponenct2" id="intkomponenct2" value="" required />
																		<input type="hidden" name="vclayer2[]" value="8 layer"/>
																		<input type="hidden" name="intlayerct2[]" value="8"/>
																	</div>
																</div>
																<div class="col-md-1">
																	<div class="form-group">
																		<button class="btn btn-success margin-top-25" onclick="addmore2()" type="button"><i class="glyphicon glyphicon-plus"></i></button>
																	</div>
																</div>
															</div>
																<?php
																	} else {

																	$loop2 = 0;
																	foreach ($dataModels2 as $models2) {
																		$hideadd2 = ($loop2 == 0) ? '' : 'hidden' ;
																		$hideremove2 = ($loop2 == 0) ? 'hidden' : '' ;

																?>
																<input type="hidden" name="intmodelkomponen2[]" value="<?=$models['intid']?>">
																<div class="control-group input-group">
																	<div class="row">
																		<div class="col-md-3">	
																			<div class="form-group">
																				<label>Component</label>
																				<select name="intkomponen2[]" class="form-control intkomponen2 select2">
																					<option value="0">-- Select Component --</option>
																					<?php
																						foreach ($models2['listkomponen2'] as $opt) {
																							$selected = ($opt->intid == $models2['intkomponen2']) ? 'selected' : '' ;
																					?>
																					<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
																					<?php
																						}
																					?>
																				</select>
																			</div>
																		</div>
																		<div class="col-md-3">
																			<div class="form-group">
																				<label>Standard layer</label>
																				<select class="form-control intlayer2" name="intlayer2[]" id="intlayer2" >
																					<option data-nama="" value="">-- Select Standard Layer --</option>
																					<?php
																						foreach ($models2['listlayer2'] as $key => $value) {
																							$selected = ($key == $models2['intlayer2']) ? 'selected' : '';
																					?>
																					<option <?=$selected?> value="<?=$key?>"><?=$value?></option>
																					<?php
																						}
																					?>
																				</select>
																			</div>
																		</div>

																		<?php
																			$loopct2 = 0;
																			foreach ($models2['datact2'] as $datact2) {
																		?>
																			<div class="col-md-1">
																				<div class="form-group">
																					<label><?=++$loopct2 * 2?> Layer</label>
																					<input type="text" name="deccycle_time2[]" placeholder="<?=$loopct2 * 2?> Layer" class="form-control deccycle_time2" id="deccycle_time2" value="<?=$datact2->deccycle_time?>" required />
																					<input type="hidden" name="intkomponenct2[]" class="form-control intkomponenct2" id="intkomponenct2" value="<?=$models2['intkomponen2']?>" required />
																					<input type="hidden" name="vclayer2[]" value="<?=$datact2->vcnama?>"/>
																					<input type="hidden" name="intlayerct2[]" value="<?=$datact2->intlayerct?>"/>
																				</div>
																			</div>
																		<?php
																			}
																		?>
																		
																		<div class="col-md-1 margin-top-25">
																			<div class="form-group">
																				<a href="javascript:void(0)" class="btn btn-success <?=$hideadd2?>" onclick="addmore2()"><i class="fa fa-plus"></i></a>
																				<a href="javascript:void(0)" class="btn btn-danger <?=$hideremove2?> remove"><i class="fa fa-remove"></i></a>
																			</div>
																		</div>
																	</div>
																</div>
																		<?php
																$loop2++;
																	}
																}
															?>
							                			</div>
							                			
							                		</div>
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

	function addmore2(){
		var html = $(".copy-fields").html();
	  		// $(".after-add-more").append(html);
	  		var base_url = '<?=base_url($controller)?>';
	  		$.ajax({
				url: base_url + '/form_detail_models2',
				method: "GET"
			})
			.done(function( data ) {
				$(".after-add-more2").append(data);
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
		$(document).ready(function() {
			//here first get the contents of the div with name class copy-fields and add it to after "after-add-more" div class.
		  	$(".add-more").click(function(){ 
		    	var html = $(".copy-fields").html();
		      	$(".after-add-more2").append(html);
		  	});
			//here it will remove the current value of the remove button which has been pressed
		  	$("body").on("click",".remove",function(){ 
		      	$(this).parents(".control-group").remove();
		  	});
		});

	$('.intkomponen').change(function(){
		var row         = $(this).closest(".row");
		var intkomponen = row.find('.intkomponen').val();
		
		var base_url = '<?=base_url("models")?>';
		$.ajax({
			url: base_url + '/getintkomponen/' + intkomponen,
			method: "GET"
		})
		.done(function( data ) {
			var result = JSON.parse(data);
			
			row.find('.intkomponenct').val(result[0].intid);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

	});

	$('.intkomponen2').change(function(){
		var row         = $(this).closest(".row");
		var intkomponen2 = row.find('.intkomponen2').val();
		
		var base_url = '<?=base_url("models")?>';
		$.ajax({
			url: base_url + '/getintkomponen2/' + intkomponen2,
			method: "GET"
		})
		.done(function( data ) {
			var result = JSON.parse(data);
			
			row.find('.intkomponenct2').val(result[0].intid);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

	});

</script>