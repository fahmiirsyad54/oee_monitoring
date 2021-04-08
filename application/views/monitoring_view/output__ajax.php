<script>
  $(function () {
    $('.datepicker').datepicker({
      autoclose: true
    })
  }) 
</script>
<div class="row">
  <div class="col-md-12">
    <div class="alert alert-info" style="border-radius: 0px; background-color: #b7fd35 !important; border-color: #b7fd35 !important">
      <span id="realtime" style="color: #000"></span>
      <div class="pull-right">
        <a href="<?=base_url('oee_monitoring/building_/'.encrypt_url($intgedung))?>" style="padding-right: 20px; color: #000">Home</a>
        <a href="<?=base_url('akses/logoutoee')?>" style="color: #000"><i class="fa fa-sign-out"></i> Log Out</a>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-1">
            <h4 class="" style="font-weight: bold; color: #000000"><?=$gedung[0]->vcnama?></h4>
        </div>

        <div class="col-md-3">
            <a href="javascript:void()" onclick="window.history.go(-1); return false;" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
            <a href="<?=base_url('oee_monitoring/building_/'.encrypt_url($intgedung))?>" class="btn btn-default"><i class="fa fa-home"></i></a>
            <a href="javascript:void()" onclick="window.history.go(1); return false;" class="btn btn-default"><i class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</div>

<div class="col-md-12">
    <div class="box box-solid">
        <div class="box-header">
            <h3 class="box-title" style="font-weight: bold; color: #000000">Summary Output</h3>
        </div>
        <div box-body>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="row">
                        <div class="col-md-2 col-md-offset-5">
                          <div class="form-group">
                            <input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="<?=$from?>" />
                          </div>
                        </div>
                        <div class="col-md-2">
                          <div class="form-group">
                            <input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="<?=$to?>" />
                          </div>
                        </div>

                        <div class="col-md-2">
                          <select name="intshift" class="form-control select2" id="intshift">
                            <option value="0">-- All Shift --</option>
                            <?php
                              foreach ($listshift as $opt) {
                                $selected = ($opt->intid == $intshift) ? 'selected' : '';
                            ?>
                            <option <?=$selected?> data-nama="<?=$opt->vcnama?>" value="<?=$opt->intid?>"><?=$opt->vcnama?></option>
                            <?php
                              }
                            ?>
                          </select>
                        </div>

                        <div class="col-md-1">
                          <button type="button" class="btn btn-default btn-block" id="simpanoutput" onclick="search()"><i class="fa fa-search"></i></button>
                          <!-- <a href="javascript:void();" onclick="search()" class="btn btn-default btn-block"><i class="fa fa-search"></i></a> -->
                        </div>
						        </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="text-align:center">No</th>
                                    <th style="text-align:center">Model</th>
                                    <th style="text-align:center">Component</th>
                                    <th style="text-align:center">Target</th>
                                    <th style="text-align:center">Actual</th>
                                    <th style="text-align:center">GAP</th>
                                    <?php
                                        foreach ($mesin as $dtmesin) {
                                    ?>
                                        <th style="text-align:center"><?=substr($dtmesin->vcnama,-4)?></th>
                                    <?php
                                        }
                                    ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                                $jmldata = count($listoutput);
                                if ($jmldata === 0) {
                            ?>
                                <tr>
                                    <td colspan="18" align="center">Data Not found</td>
                                </tr>

                            <?php
                                } else {
                                $no = 0;
                                foreach ($listoutput as $dataoutput) {
                                  $gap = $dataoutput['intpasang'] - $dataoutput['inttarget'];
                            ?>
                                <tr>
                                    <td><?=++$no?></td>
                                    <td><?=$dataoutput['vcmodel']?></td>
                                    <td><?=$dataoutput['vckomponen']?></td>
                                    <td style="text-align:center"><?=$dataoutput['inttarget']?></td>
                                    <td style="text-align:center"><?=$dataoutput['intpasang']?></td>
                                    <td style="text-align:center"><?=$gap?></td>
                                    <?php
                                        foreach ($dataoutput['datamesinoutput'] as $datamesinoutput) {
                                      ?>
                                    <td style="text-align:center"><?=$datamesinoutput->intpasangmesin?></td>

                                      <?php
                                          }
                                      ?> 
                                </tr>
                            <?php
                                    }
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <div>
        
    </div>
</div>


<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-knob/js/jquery.knob.js"></script>
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script type="text/javascript">
  var _intrealtime = 1;

  if (_intrealtime == 1) {
    $(function () {
      setTimeout(function(){
           location.reload();
          },180000);
    });
  }

  function convertdate(_date){
    now = new Date(_date);
    year = now.getFullYear();
    month = (now.getMonth() + 1);
    day = now.getDate();
    return year + "-" + month + "-" + day;
  }

  function showoee(){
    var _date1           = $('#from').val();
    var _date2           = $('#to').val();
    var base_url         = '<?=base_url()?>';
    var intgedung        = <?=$intgedung?>;
    window.location.href = base_url + 'oee_monitoring/building_/'+ intgedung + '/' + convertdate(_date1) + '/' + convertdate(_date2);
  }

  function search(){
    var intgedung = '<?=encrypt_url($intgedung)?>';
    var base_url  = '<?=base_url()?>';
    var from      = $('#from').val();
    var to        = $('#to').val();
    var intshift  = $('#intshift').val();

		//window.open(base_url + '/exportexcelnew?from=' + from + '&to=' + to + '&intmesin=' + intmesin + '&intgedung=' + intgedung + '&intshift=' + intshift);
    window.location.replace(base_url + "oee_monitoring/output_/" + intgedung + "/" + convertdate(from) + "/" + convertdate(to) + "/" + intshift);
    //window.location.replace(base_url + "/view");
	}

   function date_time(id){
        date = new Date;
        year = date.getFullYear();
        month = date.getMonth();
        months = new Array('January', 'February', 'March', 'April', 'May', 'June', 'Jully', 'August', 'September', 'October', 'November', 'December');
        d = date.getDate();
        day = date.getDay();
        days = new Array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
        h = date.getHours();
        if(h<10)
        {
                h = "0"+h;
        }
        m = date.getMinutes();
        if(m<10)
        {
                m = "0"+m;
        }
        s = date.getSeconds();
        if(s<10)
        {
                s = "0"+s;
        }
        result = ''+days[day]+', '+d+' '+months[month]+' '+year+' '+h+':'+m+':'+s;
        document.getElementById(id).innerHTML = result;
        setTimeout('date_time("'+id+'");','1000');
        return true;
    }

  $(function () {
    window.onload = date_time('realtime');
    console.log(date_time('realtime'));
  });
</script>