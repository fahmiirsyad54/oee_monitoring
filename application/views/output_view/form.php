	<div class="row">
	<div class="col-md-12">
		<div class="box"> 
			<div class="box-header with-border">
				<?=$action . ' ' . $title?>
			</div> 
 
			<div class="box-body">
				<form method="POST" id="postdata" action="<?=base_url($controller . '/aksi/' . $action . '/' . $intid)?>">
					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Date</label>
								<input type="text" name="dttanggal" placeholder="Date order" class="form-control form_datetime" id="" required value="<?=$dttanggal?>" />
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Shift</label>
								<select name="intshift" class="form-control" id="intshift">
									<option data-nama="" value="0">-- Select Shift --</option>
									<?php
										foreach ($listshift as $opt) {
											$selected = ($intshift == $opt->intid) ? 'selected' : '' ;
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
								<label>Machine</label>
								<select name="intmesin" class="form-control select2" id="intmesin">
									<option data-nama="" value="0">-- Select Machine --</option>
									<?php
										foreach ($listmesin as $opt) {
											$selected = ($intmesin == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vckode?>" value="<?=$opt->intid?>"><?=$opt->vckode . ' - ' . $opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-3">
							<div class="form-group">
								<label>Operator</label>
								<select name="intoperator" class="form-control select2" id="intoperator">
									<option data-nama="" value="0">-- Select Operator --</option>
									<?php
										foreach ($listoperator as $opt) {
											$selected = ($intoperator == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vckode . ' - ' . $opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
								<label>Leader</label>
								<select name="intleader" class="form-control select2" id="intleader">
									<option data-nama="" value="0">-- Select Leader --</option>
									<?php
										foreach ($listleader as $opt) {
											$selected = ($intleader == $opt->intid) ? 'selected' : '' ;
									?>
									<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
									<?php
										}
									?>
								</select>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-2">
							<div class="form-group">
								<label>Start</label>
								<input type="text" name="dtmulai" placeholder="Start" class="form-control datetimepicker1" id="" required value="<?=$dtmulai?>" />
		                	</div>
						</div>

						<div class="col-md-2">
							<div class="form-group">
				                <label>Finish</label>
					            <input type="text" name="dtselesai" placeholder="Finish" class="form-control datetimepicker1" id="" required value="<?=$dtselesai?>" />
							</div>
						</div>
					</div>

					
					<?php
						if ($intid == 0) {
					?>
						<!-- column cutting 1 -->
						<div class="row control-group">
							<div class="col-md-2">
								<div class="form-group">
									<label>Models</label>
									<select name="intmodel1" class="form-control select2" id="intmodel1">
										<option data-nama="" value="0">-- Select Models --</option>
										<?php
											foreach ($listmodels as $opt) {
												$selected = ($intmodel == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen1" class="form-control select2" id="intkomponen1">
										<option data-nama="" value="0">-- Select Komponent --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$selected = ($intkomponen == $opt->intkomponen) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->intkomponen?>"><?=$opt->vckomponen?></option>
										
										<?php
											}
										?>
									</select>
									<input type="hidden" name="decct1" id="decct1" class="form-control" value="<?=$decct?>" >
									<input type="hidden" name="intlayer1" id="intlayer1" class="form-control" value="<?=$intlayer?>" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>PO</label>
									<select name="vcpo1" class="form-control select2" id="vcpo1">
										<option data-nama="" value="">-- Select PO --</option>
										<?php
											foreach ($listpo as $opt) {
												$selected = ($vcpo == $opt->vcpo) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->vcpo?>"><?=$opt->vcpo?></option>
										
										<?php
											}
										?>
									</select>
									<input type="hidden" name="intqty1" id="intqty1" class="form-control" value="" >
									<input type="hidden" name="jumlahpasang1" id="jumlahpasang1" class="form-control" value="" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Layer Actual</label>
									<select name="intremark1" class="form-control select2" id="intremark1">
										<option data-nama="" value="0">-- Select Layer --</option>
										<?php
											foreach ($listremark as $opt) {
												$selected = ($intremark == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang1" id="intpasang1" class="form-control" value="<?=$intpasang?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject1" id="intreject1" class="form-control" value="<?=$intreject?>">
								</div>
							</div>
						</div>
						
						<!-- column cutting 2 -->
						<div class="row control-group">
							<div class="col-md-2">
								<div class="form-group">
									<label>Models</label>
									<select name="intmodel2" class="form-control select2" id="intmodel2">
										<option data-nama="" value="0">-- Select Models --</option>
										<?php
											foreach ($listmodels as $opt) {
												$selected = ($intmodel == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen2" class="form-control select2" id="intkomponen2">
										<option data-nama="" value="0">-- Select Komponent --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$selected = ($intkomponen == $opt->intkomponen) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->intkomponen?>"><?=$opt->vckomponen?></option>
										
										<?php
											}
										?>
									</select>
									<input type="hidden" name="decct2" id="decct2" class="form-control" value="<?=$decct?>" >
									<input type="hidden" name="intlayer2" id="intlayer2" class="form-control" value="<?=$intlayer?>" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>PO</label>
									<select name="vcpo2" class="form-control select2" id="vcpo2">
										<option data-nama="" value="">-- Select PO --</option>
										<?php
											foreach ($listpo as $opt) {
												$selected = ($vcpo == $opt->vcpo) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->vcpo?>"><?=$opt->vcpo?></option>
										
										<?php
											}
										?>
									</select>
									<input type="hidden" name="intqty2" id="intqty2" class="form-control" value="" >
									<input type="hidden" name="jumlahpasang2" id="jumlahpasang2" class="form-control" value="" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Layer Actual</label>
									<select name="intremark2" class="form-control select2" id="intremark2">
										<option data-nama="" value="0">-- Select Layer --</option>
										<?php
											foreach ($listremark as $opt) {
												$selected = ($intremark == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang2" id="intpasang2" class="form-control" value="<?=$intpasang?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject2" id="intreject2" class="form-control" value="<?=$intreject?>">
								</div>
							</div>
						</div>
						
						<!-- column cutting 3 -->
						<div class="row control-group">
							<div class="col-md-2">
								<div class="form-group">
									<label>Models</label>
									<select name="intmodel3" class="form-control select2" id="intmodel3">
										<option data-nama="" value="0">-- Select Models --</option>
										<?php
											foreach ($listmodels as $opt) {
												$selected = ($intmodel == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen3" class="form-control select2" id="intkomponen3">
										<option data-nama="" value="0">-- Select Komponent --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$selected = ($intkomponen == $opt->intkomponen) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->intkomponen?>"><?=$opt->vckomponen?></option>
										
										<?php
											}
										?>
									</select>
									<input type="hidden" name="decct3" id="decct3" class="form-control" value="<?=$decct?>" >
									<input type="hidden" name="intlayer3" id="intlayer3" class="form-control" value="<?=$intlayer?>" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>PO</label>
									<select name="vcpo3" class="form-control select2" id="vcpo3">
										<option data-nama="" value="">-- Select PO --</option>
										<?php
											foreach ($listpo as $opt) {
												$selected = ($vcpo == $opt->vcpo) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->vcpo?>"><?=$opt->vcpo?></option>
										
										<?php
											}
										?>
									</select>
									<input type="hidden" name="intqty3" id="intqty3" class="form-control" value="" disabled = "">
									<input type="hidden" name="jumlahpasang3" id="jumlahpasang3" class="form-control" value="" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Layer Actual</label>
									<select name="intremark3" class="form-control select2" id="intremark3">
										<option data-nama="" value="0">-- Select Layer --</option>
										<?php
											foreach ($listremark as $opt) {
												$selected = ($intremark == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang3" id="intpasang3" class="form-control" value="<?=$intpasang?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject3" id="intreject3" class="form-control" value="<?=$intreject?>">
								</div>
							</div>
						</div>
						
						<!-- column cutting 4 -->
						<div class="row control-group">
							<div class="col-md-2">
								<div class="form-group">
									<label>Models</label>
									<select name="intmodel4" class="form-control select2" id="intmodel4">
										<option data-nama="" value="0">-- Select Models --</option>
										<?php
											foreach ($listmodels as $opt) {
												$selected = ($intmodel == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen4" class="form-control select2" id="intkomponen4">
										<option data-nama="" value="0">-- Select Komponent --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$selected = ($intkomponen == $opt->intkomponen) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->intkomponen?>"><?=$opt->vckomponen?></option>
										
										<?php
											}
										?>
									</select>
									<input type="hidden" name="decct4" id="decct4" class="form-control" value="<?=$decct?>" >
									<input type="hidden" name="intlayer4" id="intlayer4" class="form-control" value="<?=$intlayer?>" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>PO</label>
									<select name="vcpo4" class="form-control select2" id="vcpo4">
										<option data-nama="" value="">-- Select PO --</option>
										<?php
											foreach ($listpo as $opt) {
												$selected = ($vcpo == $opt->vcpo) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->vcpo?>"><?=$opt->vcpo?></option>
										
										<?php
											}
										?>
									</select>
									<input type="hidden" name="intqty4" id="intqty4" class="form-control" value="" >
									<input type="hidden" name="jumlahpasang4" id="jumlahpasang4" class="form-control" value="" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Layer Actual</label>
									<select name="intremark4" class="form-control select2" id="intremark4">
										<option data-nama="" value="0">-- Select Layer --</option>
										<?php
											foreach ($listremark as $opt) {
												$selected = ($intremark == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang4" id="intpasang4" class="form-control" value="<?=$intpasang?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject4" id="intreject4" class="form-control" value="<?=$intreject?>">
								</div>
							</div>
						</div>

					<?php
						} else {
							$d = $datacombine;
							$j = count($datacombine);
					?>
						<div class="row control-group">
							<input type="hidden" name="intid1" id="intid1" class="form-control" value="<?=$datacombine[0]->intid?>">
							<div class="col-md-2">
								<div class="form-group">
									<label>Models</label>
									<select name="intmodel1" class="form-control select2" id="intmodel1">
										<option data-nama="" value="0">-- Select Models --</option>
										<?php
											foreach ($listmodels as $opt) {
												$selected = ($datacombine[0]->intmodel == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen1" class="form-control select2" id="intkomponen1">
										<option data-nama="" value="0">-- Select Komponent --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$selected = ($datacombine[0]->intkomponen == $opt->intkomponen) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->intkomponen?>"><?=$opt->vckomponen?></option>
										
										<?php
											}
										?>
									</select>
									<input type="text" name="decct1" id="decct1" class="form-control" value="<?=$datacombine[0]->decct?>" >
									<input type="text" name="intlayer1" id="intlayer1" class="form-control" value="<?=$datacombine[0]->intlayer?>" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>PO</label>
									<select name="vcpo1" class="form-control select2" id="vcpo1">
										<option data-nama="" value="">-- Select PO --</option>
										<?php
											foreach ($listpo as $opt) {
												$selected = ($datacombine[0]->vcpo == $opt->vcpo) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-intid="<?=$opt->intid?>" value="<?=$opt->vcpo?>"><?=$opt->vcpo?></option>
										
										<?php
											}
										?>
									</select>
									<input type="text" name="intqty1" id="intqty1" class="form-control" value="" >
									<input type="text" name="jumlahpasang1" id="jumlahpasang1" class="form-control" value="" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Layer Actual</label>
									<select name="intremark1" class="form-control select2" id="intremark1">
										<option data-nama="" value="0">-- Select Layer --</option>
										<?php
											foreach ($listremark as $opt) {
												$selected = ($datacombine[0]->intremark == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang1" id="intpasang1" class="form-control" value="<?=$datacombine[0]->intpasang?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject1" id="intreject1" class="form-control" value="<?=$datacombine[0]->intreject?>">
								</div>
							</div>
						</div>

						<div class="row control-group">
							<input type="hidden" name="intid2" id="intid2" class="form-control" value="<?=$j > 1 ? $d[1]->intid : ''?>">
							<div class="col-md-2">
								<div class="form-group">
									<label>Models</label>
									<select name="intmodel2" class="form-control select2" id="intmodel2">
										<option data-nama="" value="0">-- Select Models --</option>
										<?php
											foreach ($listmodels as $opt) {
												$intmodel = $j > 1 ? $d[1]->intmodel : 0;
												$selected = ($intmodel == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen2" class="form-control select2" id="intkomponen2">
										<option data-nama="" value="0">-- Select Komponent --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$intkomponen = $j > 1 ? $d[1]->intkomponen : 0;
												$selected = ($intkomponen == $opt->intkomponen) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->intkomponen?>"><?=$opt->vckomponen?></option>
										
										<?php
											}
										?>
									</select>
									<input type="text" name="decct2" id="decct2" class="form-control" value="<?=$j > 1 ? $d[1]->decct : ''?>" >
									<input type="text" name="intlayer2" id="intlayer2" class="form-control" value="<?=$j > 1 ? $d[1]->intlayer : ''?>" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>PO</label>
									<select name="vcpo2" class="form-control select2" id="vcpo2">
										<option data-nama="" value="">-- Select PO --</option>
										<?php
											foreach ($listpo as $opt) {
												$vcpo = $j > 1 ? $d[1]->vcpo : '';
												$selected = ($vcpo == $opt->vcpo) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-intid="<?=$opt->intid?>" value="<?=$opt->vcpo?>"><?=$opt->vcpo?></option>
										
										<?php
											}
										?>
									</select>
									<input type="text" name="intqty2" id="intqty2" class="form-control" value="" >
									<input type="text" name="jumlahpasang2" id="jumlahpasang2" class="form-control" value="" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Layer Actual</label>
									<select name="intremark2" class="form-control select2" id="intremark2">
										<option data-nama="" value="0">-- Select Layer --</option>
										<?php
											foreach ($listremark as $opt) {
												$intremark = $j > 1 ? $d[1]->intremark : 0;
												$selected = ($intremark == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang2" id="intpasang2" class="form-control" value="<?=$j > 1 ? $d[1]->intpasang : ''?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject2" id="intreject2" class="form-control" value="<?=$j > 1 ? $d[1]->intreject : ''?>">
								</div>
							</div>
						</div>

						<div class="row control-group">
							<input type="hidden" name="intid3" id="intid3" class="form-control" value="<?=$j > 2 ? $d[2]->intid : ''?>">
							<div class="col-md-2">
								<div class="form-group">
									<label>Models</label>
									<select name="intmodel3" class="form-control select2" id="intmodel3">
										<option data-nama="" value="0">-- Select Models --</option>
										<?php
											foreach ($listmodels as $opt) {
												$intmodel = $j > 2 ? $d[2]->intmodel : 0;
												$selected = ($intmodel == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen3" class="form-control select2" id="intkomponen3">
										<option data-nama="" value="0">-- Select Komponent --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$intkomponen = $j > 2 ? $d[2]->intkomponen : 0;
												$selected = ($intkomponen == $opt->intkomponen) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->intkomponen?>"><?=$opt->vckomponen?></option>
										
										<?php
											}
										?>
									</select>
									<input type="text" name="decct3" id="decct3" class="form-control" value="<?=$j > 2 ? $d[2]->decct : ''?>" >
									<input type="text" name="intlayer3" id="intlayer3" class="form-control" value="<?=$j > 2 ? $d[2]->intlayer : ''?>" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>PO</label>
									<select name="vcpo3" class="form-control select2" id="vcpo3">
										<option data-nama="" value="">-- Select PO --</option>
										<?php
											foreach ($listpo as $opt) {
												$vcpo = $j > 2 ? $d[2]->vcpo : '';
												$selected = ($vcpo == $opt->vcpo) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-intid="<?=$opt->intid?>" value="<?=$opt->vcpo?>"><?=$opt->vcpo?></option>
										
										<?php
											}
										?>
									</select>
									<input type="text" name="intqty3" id="intqty3" class="form-control" value="" >
									<input type="text" name="jumlahpasang3" id="jumlahpasang3" class="form-control" value="" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Layer Actual</label>
									<select name="intremark3" class="form-control select2" id="intremark3">
										<option data-nama="" value="0">-- Select Layer --</option>
										<?php
											foreach ($listremark as $opt) {
												$intremark = $j > 2 ? $d[2]->intremark : 0;
												$selected = ($intremark == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang3" id="intpasang3" class="form-control" value="<?=$j > 2 ? $d[2]->intpasang : ''?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject3" id="intreject3" class="form-control" value="<?=$j > 2 ? $d[2]->intreject : ''?>">
								</div>
							</div>
						</div>

						<div class="row control-group">
							<input type="hidden" name="intid4" id="intid4" class="form-control" value="<?=$j > 3 ? $d[3]->intid : ''?>">
							<div class="col-md-2">
								<div class="form-group">
									<label>Models</label>
									<select name="intmodel4" class="form-control select2" id="intmodel4">
										<option data-nama="" value="0">-- Select Models --</option>
										<?php
											foreach ($listmodels as $opt) {
												$intmodel = $j > 3 ? $d[3]->intmodel : 0;
												$selected = ($intmodel == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Component</label>
									<select name="intkomponen4" class="form-control select2" id="intkomponen4">
										<option data-nama="" value="0">-- Select Komponent --</option>
										<?php
											foreach ($listkomponen as $opt) {
												$intkomponen = $j > 3 ? $d[3]->intkomponen : 0;
												$selected = ($intkomponen == $opt->intkomponen) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vckomponen?>" value="<?=$opt->intkomponen?>"><?=$opt->vckomponen?></option>
										
										<?php
											}
										?>
									</select>
									<input type="text" name="decct4" id="decct4" class="form-control" value="<?=$j > 3 ? $d[3]->decct : ''?>" >
									<input type="text" name="intlayer4" id="intlayer4" class="form-control" value="<?=$j > 3 ? $d[3]->intlayer : ''?>" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>PO</label>
									<select name="vcpo4" class="form-control select2" id="vcpo4">
										<option data-nama="" value="">-- Select PO --</option>
										<?php
											foreach ($listpo as $opt) {
												$vcpo = $j > 3 ? $d[3]->vcpo : '';
												$selected = ($vcpo == $opt->vcpo) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-intid="<?=$opt->intid?>" value="<?=$opt->vcpo?>"><?=$opt->vcpo?></option>
										
										<?php
											}
										?>
									</select>
									<input type="text" name="intqty4" id="intqty4" class="form-control" value="" >
									<input type="text" name="jumlahpasang4" id="jumlahpasang4" class="form-control" value="" >
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Layer Actual</label>
									<select name="intremark4" class="form-control select2" id="intremark4">
										<option data-nama="" value="0">-- Select Layer --</option>
										<?php
											foreach ($listremark as $opt) {
												$intremark = $j > 3 ? $d[3]->intremark : 0;
												$selected = ($intremark == $opt->intid) ? 'selected' : '' ;
										?>
										<option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
										<?php
											}
										?>
									</select>
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Pair</label>
									<input type="number" name="intpasang4" id="intpasang4" class="form-control" value="<?=$j > 3 ? $d[3]->intpasang : ''?>">
								</div>
							</div>

							<div class="col-md-2">
								<div class="form-group">
									<label>Reject</label>
									<input type="number" name="intreject4" id="intreject4" class="form-control" value="<?=$j > 3 ? $d[3]->intreject : ''?>">
								</div>
							</div>
						</div>

					<?php
								
						}
					?>

					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<!-- <button class="btn btn-success" type="submit"><i class="fa fa-save"></i> Simpan</button> -->
								<a href="javascript:void(0);" onclick="simpanData('<?=$action?>')" class="btn btn-success"><i class="fa fa-save"></i> Save</a>
								<a href="<?=base_url($controller . '/view')?>" class="btn btn-danger"><i class="fa fa-close"></i>Close</a>
							</div>
						</div>						
					</div>
				</form>
			</div>

		</div>
	</div>
</div>

<script type="text/javascript">
	$(function () {
	    //Initialize Select2 Elements
	    $('.select2').select2();
	    $(".form_datetime").datetimepicker({locale: 'id'});
	});
	function simpanData(action) {
		var vckode       = $('#vckode').val();
		var vcnama       = $('#vcnama').val();
		var vccontroller = $('#vccontroller').val();

		if (action == 'Add') {
			var base_url = '<?=base_url($controller)?>';
			var formrequired = {};
			var formdata = {};

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

	$('#intgedung').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_cell_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Cell --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#intcell').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intcell').change(function(){ 
		var intid    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_mesin_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Machine --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vckode + '</option>';
			}
			$('#intmesin').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intgedung').change(function(){
		var intgedung    = $(this).val();
		var base_url = '<?=base_url($controller)?>';
		$.ajax({
			url: base_url + '/get_karyawan_ajax/' + intgedung + '/3',
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Operator --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#intoperator').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

		$.ajax({
			url: base_url + '/get_karyawan_ajax/' + intgedung + '/1',
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Leader --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#intleader').html(html);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});

		$.ajax({
			url: base_url + '/get_karyawan_ajax/' + intgedung + '/2',
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var html = '<option value="0">-- Select Operator --</option>';
			for (var i = 0; i < jsonData.length; i++) {
				html += '<option value="' + jsonData[i].intid + '">' + jsonData[i].vcnama + '</option>';
			}
			$('#intmekanik').html(html);
		})
		
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intmodel1').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("output")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var listkomponen = jsonData.listkomponen;
			var html = '<option data-nama="" value="0">-- Select Component --</option>';
			for (var i = 0; i < listkomponen.length; i++) {
				html += '<option data-ct="' + listkomponen[i].deccycle_time + '" data-layer="' + listkomponen[i].intlayer + '" value="' + listkomponen[i].intkomponen + '">' + listkomponen[i].vckomponen + '</option>'
			}
			$('#intkomponen1').html(html);

			var listpo = jsonData.listpo;
			var htmlpo = '<option data-nama="" value="0">-- Select PO --</option>';
			for (var i = 0; i < listpo.length; i++) {
				htmlpo += '<option data-qty="' + listpo[i].intqty + '" value="' + listpo[i].vcpo + '">' + listpo[i].vcpo + '</option>'
			}
			$('#vcpo1').html(htmlpo);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intmodel2').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("output")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var listkomponen = jsonData.listkomponen;
			var html = '<option data-nama="" value="0">-- Select Component --</option>';
			for (var i = 0; i < listkomponen.length; i++) {
				html += '<option data-ct="' + listkomponen[i].deccycle_time + '" data-layer="' + listkomponen[i].intlayer + '" value="' + listkomponen[i].intkomponen + '">' + listkomponen[i].vckomponen + '</option>'
			}
			$('#intkomponen2').html(html);

			var listpo = jsonData.listpo;
			var htmlpo = '<option data-nama="" value="0">-- Select PO --</option>';
			for (var i = 0; i < listpo.length; i++) {
				htmlpo += '<option data-qty="' + listpo[i].intqty + '" value="' + listpo[i].vcpo + '">' + listpo[i].vcpo + '</option>'
			}
			$('#vcpo2').html(htmlpo);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intmodel3').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("output")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var listkomponen = jsonData.listkomponen;
			var html = '<option data-nama="" value="0">-- Select Component --</option>';
			for (var i = 0; i < listkomponen.length; i++) {
				html += '<option data-ct="' + listkomponen[i].deccycle_time + '" data-layer="' + listkomponen[i].intlayer + '" value="' + listkomponen[i].intkomponen + '">' + listkomponen[i].vckomponen + '</option>'
			}
			$('#intkomponen3').html(html);

			var listpo = jsonData.listpo;
			var htmlpo = '<option data-nama="" value="0">-- Select PO --</option>';
			for (var i = 0; i < listpo.length; i++) {
				htmlpo += '<option data-qty="' + listpo[i].intqty + '" value="' + listpo[i].vcpo + '">' + listpo[i].vcpo + '</option>'
			}
			$('#vcpo3').html(htmlpo);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intmodel4').change(function(){
		var intid    = $(this).val();
		var base_url = '<?=base_url("output")?>';
		$.ajax({
			url: base_url + '/getkomponen_ajax/' + intid,
			method: "GET"
		})
		.done(function( data ) {
			var jsonData = JSON.parse(data);
			var listkomponen = jsonData.listkomponen;
			var html = '<option data-nama="" value="0">-- Select Component --</option>';
			for (var i = 0; i < listkomponen.length; i++) {
				html += '<option data-ct="' + listkomponen[i].deccycle_time + '" data-layer="' + listkomponen[i].intlayer + '" value="' + listkomponen[i].intkomponen + '">' + listkomponen[i].vckomponen + '</option>'
			}
			$('#intkomponen4').html(html);

			var listpo = jsonData.listpo;
			var htmlpo = '<option data-nama="" value="0">-- Select PO --</option>';
			for (var i = 0; i < listpo.length; i++) {
				htmlpo += '<option data-qty="' + listpo[i].intqty + '" value="' + listpo[i].vcpo + '">' + listpo[i].vcpo + '</option>'
			}
			$('#vcpo4').html(htmlpo);
		})
		.fail(function( jqXHR, statusText ) {
			alert( "Request failed: " + jqXHR.status );
		});
	});

	$('#intkomponen1').change(function(){
		var intid    = $(this).val();
		var decct    = $(this).children('option:selected').data('ct');
		var intlayer = $(this).children('option:selected').data('layer');

		$('#decct1').val(decct),
		$('#intlayer1').val(intlayer)
	});

	$('#intkomponen2').change(function(){
		var intid    = $(this).val();
		var decct    = $(this).children('option:selected').data('ct');
		var intlayer = $(this).children('option:selected').data('layer');

		$('#decct2').val(decct),
		$('#intlayer2').val(intlayer)
	});

	$('#intkomponen3').change(function(){
		var intid    = $(this).val();
		var decct    = $(this).children('option:selected').data('ct');
		var intlayer = $(this).children('option:selected').data('layer');

		$('#decct3').val(decct),
		$('#intlayer3').val(intlayer)
	});

	$('#intkomponen4').change(function(){
		var intid    = $(this).val();
		var decct    = $(this).children('option:selected').data('ct');
		var intlayer = $(this).children('option:selected').data('layer');

		$('#decct4').val(decct),
		$('#intlayer4').val(intlayer)
	});

	$('#vcpo1').change(function(){
		var vcpo        = $(this).val();
		var intmodel    = $('#intmodel1').val();
		var intkomponen = $('#intkomponen1').val();
		var intqty      = $(this).children('option:selected').data('qty');
		var base_url    = '<?=base_url("output")?>';
		if (intkomponen == 0) {
			swal({
					type: 'error',
					title: 'Pilih komponen dahulu !'
				});
			$('#vcpo1').val(0);
			$('#intpasang1').val('');
		} else {
			$.ajax({
				url: base_url + '/getstatuskolom/' + intmodel + '/' + intkomponen + '/' + vcpo,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				if (jsonData.intstatus == 0) {
					swal({
					type: 'warning',
					title: 'Cutting sudah mencapai target PO !'
					});
					$('#vcpo1').val(0);
					$('#jumlahpasang1').val(0);
				} else {
					$('#jumlahpasang1').val(jsonData.jumlahpasang);
					$('#intqty1').val(intqty);
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	});

	$('#vcpo2').change(function(){
		var vcpo        = $(this).val();
		var intmodel    = $('#intmodel2').val();
		var intkomponen = $('#intkomponen2').val();
		var intqty      = $(this).children('option:selected').data('qty');
		var base_url    = '<?=base_url("output")?>';
		if (intkomponen == 0) {
			swal({
					type: 'error',
					title: 'Pilih komponen dahulu !'
				});
			$('#vcpo2').val(0);
			$('#intpasang2').val('');
		} else {
			$.ajax({
				url: base_url + '/getstatuskolom/' + intmodel + '/' + intkomponen + '/' + vcpo,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				if (jsonData.intstatus == 0) {
					swal({
					type: 'warning',
					title: 'Cutting sudah mencapai target PO !'
					});
					$('#vcpo2').val(0);
					$('#jumlahpasang2').val(0);
				} else {
					$('#jumlahpasang2').val(jsonData.jumlahpasang);
					$('#intqty2').val(intqty);
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	});

	$('#vcpo3').change(function(){
		var vcpo        = $(this).val();
		var intmodel    = $('#intmodel3').val();
		var intkomponen = $('#intkomponen3').val();
		var intqty      = $(this).children('option:selected').data('qty');
		var base_url    = '<?=base_url("output")?>';
		if (intkomponen == 0) {
			swal({
					type: 'error',
					title: 'Pilih komponen dahulu !'
				});
			$('#vcpo3').val(0);
			$('#intpasang3').val('');
		} else {
			$.ajax({
				url: base_url + '/getstatuskolom/' + intmodel + '/' + intkomponen + '/' + vcpo,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				if (jsonData.intstatus == 0) {
					swal({
					type: 'warning',
					title: 'Cutting sudah mencapai target PO !'
					});
					$('#vcpo3').val(0);
					$('#jumlahpasang3').val(0);
				} else {
					$('#jumlahpasang3').val(jsonData.jumlahpasang);
					$('#intqty3').val(intqty);
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	});

	$('#vcpo4').change(function(){
		var vcpo        = $(this).val();
		var intmodel    = $('#intmodel4').val();
		var intkomponen = $('#intkomponen4').val();
		var intqty      = $(this).children('option:selected').data('qty');
		var base_url    = '<?=base_url("output")?>';
		if (intkomponen == 0) {
			swal({
					type: 'error',
					title: 'Pilih komponen dahulu !'
				});
			$('#vcpo4').val(0);
			$('#intpasang4').val('');
		} else {
			$.ajax({
				url: base_url + '/getstatuskolom/' + intmodel + '/' + intkomponen + '/' + vcpo,
				method: "GET"
			})
			.done(function( data ) {
				var jsonData = JSON.parse(data);
				if (jsonData.intstatus == 0) {
					swal({
					type: 'warning',
					title: 'Cutting sudah mencapai target PO !'
					});
					$('#vcpo4').val(0);
					$('#jumlahpasang4').val(0);
				} else {
					$('#jumlahpasang4').val(jsonData.jumlahpasang);
					$('#intqty4').val(intqty);
				}
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
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

	function addmore(){
		var html = $(".copy-fields").html();
	  		// $(".after-add-more").append(html);
	  		var base_url = '<?=base_url($controller)?>';
	  		$.ajax({
				url: base_url + '/form_detail_output',
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
