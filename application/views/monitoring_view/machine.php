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
      <div class="pull-right" style="color: #000">
        <a href="<?=base_url('oee_monitoring')?>" style="padding-right: 20px; color: #000">Home</a>
        <a href="<?=base_url('oee_monitoring/building/'.$intgedung)?>" style="padding-right: 20px; color: #000">Building</a>
        <span style="padding-right: 20px; ">ID Machine : <?=$vckodemesin . ' - ' . $vcmesin?></span>
        <span style="padding-right: 20px; ">Operator : <?=$vcoperator . ' (' . $vcnik . ')'?></span>
        <!-- <a href="javascript:void(0)" data-toggle="modal" data-target="#modalPesan"><span style="padding-right: 20px; "><i class="fa fa-envelope"></i> Catatan</span></a>
        <a href="<?=base_url('akses/logoutop')?>"><i class="fa fa-sign-out"></i> Log Out</a> -->
        <a href="<?=base_url('akses/logoutoee')?>" style="color: #000"><i class="fa fa-sign-out"></i> Log Out</a>
      </div>
    </div>
  </div>
</div>
<?php
  if ($availabilityfactor >= 70) {
      $warnaaf = '#00ff00';
      $baraf = '#00ff00';
  } elseif ($availabilityfactor >= 60) {
      $warnaaf = '#ffff00';
      $baraf = '#ffff00';
  } else{
      $warnaaf = '#ff0000';
      $baraf = '#ff0000';
  }

  if ($availabilityfactor >= 70) {
      $warnaaf = '#00ff00';
      $baraf = '#00ff00';
  } elseif ($availabilityfactor >= 60) {
      $warnaaf = '#ffff00';
      $baraf = '#ffff00';
  } else{
      $warnaaf = '#ff0000';
      $baraf = '#ff0000';
  }

  if ($performancefactor >= 70) {
      $warnapf = '#00ff00';
      $barpf = '#00ff00';
  } elseif ($performancefactor >= 60) {
      $warnapf = '#ffff00';
      $barpf = '#ffff00';
  } else{
      $warnapf = '#ff0000';
      $barpf = '#ff0000';
  }

  if ($qualityfactor >= 70) {
      $warnaqf = '#00ff00';
      $barqf = '#00ff00';
  } elseif ($qualityfactor >= 60) {
      $warnaqf = '#ffff00';
      $barqf = '#ffff00';
  } else{
      $warnaqf = '#ff0000';
      $barqf = '#ff0000';
  }

  if ($oee >= 70) {
      $warnaoee = '#00ff00';
      $baroee = '#00ff00';
  } elseif ($oee >= 60) {
      $warnaoee = '#ffff00';
      $baroee = '#ffff00';
  } else{
      $warnaoee = '#ff0000';
      $baroee = '#ff0000';
  }
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-2">
      <div class="form-group">
        <div class="btn-group" role="group">
          <a href="<?=base_url('oee_monitoring/machine/'.$intmesin)?>" class="btn <?=$btnreal?>">Real Time</a>
          <a href="<?=base_url('oee_monitoring/machine/'.$intmesin.'/'.date('Y-m-d',strtotime($datest)).'/'.date('Y-m-d',strtotime($datefs)))?>" class="btn <?=$btnhistory?>">History Time</a>
        </div>
      </div>
    </div>

    <div class="col-md-5">
      <a href="javascript:void()" onclick="window.history.go(-1); return false;" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
      <a href="<?=base_url('oee_monitoring')?>" class="btn btn-default"><i class="fa fa-home"></i></a>
      <a href="javascript:void()" onclick="window.history.go(1); return false;" class="btn btn-default"><i class="fa fa-arrow-right"></i></a>
    </div>

    <div class="col-md-2 <?=$hidedate?>">
      <div class="form-group">
        <input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="<?=$datest?>" />
      </div>
    </div>

    <div class="col-md-2 <?=$hidedate?>">
      <div class="form-group">
        <input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="<?=$datefs?>" />
      </div>
    </div>

    <div class="col-md-1 <?=$hidedate?>">
      <div class="form-group">
        <button class="btn btn-info btn-block" onclick="showoee()">Show</button>
      </div>
    </div>
  </div>

	<div class="row">
    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #ffc266">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #000000">OEE</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$oee?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaoee?>" readonly>
        </div>
        <div class="box-header" >
          <span style="font-size: 14px; color: #000000">
            OEE
            <small class="pull-right" style="color: #000000"><?=$oee?>%</small>
          </span>
          <div class="progress xs">
            <div class="progress-bar progress-bar-success" style="width: <?=$oee?>%; background-color: <?=$baroee?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Availability Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$availabilityfactor?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaaf?>" data-fgBorder="#000000" readonly>
        </div>
        <div class="box-header" >
          <span style="font-size: 14px; color: #FFFFFF">
            Run Time
            <small class="pull-right"><?=$runtime?> min</small>
          </span>
          <div class="progress xs">
            <div class="progress-bar progress-bar-success" style="width: <?=$availabilityfactor?>%; background-color: <?=$baraf?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
          <span style="font-size: 14px; color: #FFFFFF">
            Planned Production Time
            <small class="pull-right"><?=$plannedproduction?> min</small>
          </span>
          <div class="progress xs">
            <div class="progress-bar progress-bar-success" style="width: 100%; background-color: #00ff00" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Performance Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$performancefactor?>%" data-width="120" data-height="120" data-fgColor="<?=$warnapf?>" readonly>
        </div>
        <div class="box-header" >
          <span style="font-size: 14px; color: #FFFFFF">
            Actual Output
            <small class="pull-right"><?=$actualoutput?></small>
          </span>
          <div class="progress xs">
            <div class="progress-bar progress-bar-success" style="width: <?=$performancefactor?>%; background-color: <?=$barpf?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
          <span style="font-size: 14px; color: #FFFFFF">
            Theoritical Output
            <small class="pull-right"><?=$theoriticaloutput?></small>
          </span>
          <div class="progress xs">
            <div class="progress-bar progress-bar-success" style="width: 100%; background-color: #00ff00" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Quality Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$qualityfactor?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaqf?>" readonly>
        </div>
        <div class="box-header" >
          <span style="font-size: 14px; color: #FFFFFF">
            Good Output
            <small class="pull-right"><?=$goodoutput?></small>
          </span>
          <div class="progress xs">
            <div class="progress-bar progress-bar-success" style="width: <?=$qualityfactor?>%; background-color: <?=$barqf?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
          <span style="font-size: 14px; color: #FFFFFF">
            Actual Output
            <small class="pull-right"><?=$actualoutput?></small>
          </span>
          <div class="progress xs">
            <div class="progress-bar progress-bar-success" style="width: 100%; background-color: #00ff00" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>


  <?php
    if ($intshift == 1) {
      $shift1active = 'active';
      $shift2active = '';
    } elseif ($intshift == 2) {
      $shift1active = '';
      $shift2active = 'active';
    }
  ?>
  <div class="row">
    <div class="col-md-6">
      <div class="box box-solid">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #000000">Downtime</h3>
        </div>
        <div class="box-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="<?=$shift1active?>"><a href="#shift1" data-toggle="tab">Shift 1</a></li>
              <li class="<?=$shift2active?>"><a href="#shift2" data-toggle="tab">Shift 2</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane <?=$shift1active?>" id="shift1">
                <div class="row">
                  <div class="col-md-12">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Downtime</th>
                            <th>Start</th>
                            <th>Finish</th>
                            <th>Duration</th>
                          </tr>
                          <tr>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th><?=$totdurasi1?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach ($listdowntime1 as $dtdowntime) {
                          ?>
                          <tr>
                            <td><?=$dtdowntime->vcdowntime?></td>
                            <td><?=$dtdowntime->dtmulai?></td>
                            <td><?=$dtdowntime->dtselesai?></td>
                            <td><?=$dtdowntime->decdurasi?></td>
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

              <div class="tab-pane <?=$shift2active?>" id="shift2">
                <div class="row">
                  <div class="col-md-12">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <tr>
                            <th>Downtime</th>
                            <th>Start</th>
                            <th>Finish</th>
                            <th>Duration</th>
                          </tr>
                          <tr>
                            <th>Total</th>
                            <th></th>
                            <th></th>
                            <th><?=$totdurasi2  ?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach ($listdowntime2 as $dtdowntime) {
                          ?>
                          <tr>
                            <td><?=$dtdowntime->vcdowntime?></td>
                            <td><?=$dtdowntime->dtmulai?></td>
                            <td><?=$dtdowntime->dtselesai?></td>
                            <td><?=$dtdowntime->decdurasi?></td>
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
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="box box-solid">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #000000">Output</h3>
        </div>
        <div class="box-body">
          <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="<?=$shift1active?>"><a href="#shift1ouput" data-toggle="tab">Shift 1</a></li>
              <li class="<?=$shift2active?>"><a href="#shift2ouput" data-toggle="tab">Shift 2</a></li>
            </ul>

            <div class="tab-content">
              <div class="tab-pane <?=$shift1active?>" id="shift1ouput">
                <div class="row">
                  <div class="col-md-12">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-hover">
                        <thead>
                          <tr>
                            <th rowspan="2" style="vertical-align:middle; align:center">Model</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Component</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Time (Minutes)</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Target</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Output</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Reject</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Loss</th>
                            <th colspan="2">Not Follow SOP</th>
                          </tr>
                          <tr>
                            <th>Over</th>
                            <th>Less</th>
                          </tr>
                          <tr>
                            <th>Total</th>
                            <th></th>
                            <th><?=$durasioutput1?></th>
                            <th><?=$tottarget1?></th>
                            <th><?=$totoutput1?></th>
                            <th><?=$totreject1?></th>
                            <th><?=$tottarget1 - $totoutput1?></th>
                            <th><?=$totalover1?></th>
                            <th><?=$totalless1?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach ($listoutput1 as $dtoutput) {
                              $target = round((strtotime($dtoutput->dtselesai) - strtotime($dtoutput->dtmulai))/$dtoutput->decct);
                              if ($dtoutput->inttarget > 0) {
                                $target = $dtoutput->inttarget;
                              }

                              $intactual      = ($dtoutput->intpasang > $target && $dtoutput->vcketerangan != '') ? $target : $dtoutput->intpasang;
                              $losses         = $target - $intactual;
                              $lossessop      = ($dtoutput->intpasang > $target && $dtoutput->vcketerangan != '') ? -($target - $dtoutput->intpasang) : $target - $dtoutput->intpasang;
                              $keteranganover = 0;
                              $keteranganless = 0;
                              $warna          = '';

                              if ($dtoutput->intpasang > $target && $dtoutput->vcketerangan != '') {
                                $keteranganover = $lossessop;
                                $warna          = 'danger';
                              } elseif ($dtoutput->vcketerangan != '') {
                                $keteranganless = $lossessop;
                                $warna          = 'danger';
                              }
                          ?>
                          <tr class="<?=$warna?>">
                            <td><?=$dtoutput->vcmodel?></td>
                            <td><?=$dtoutput->vckomponen?></td>
                            <td><?=$dtoutput->dtmulai . ' - ' . $dtoutput->dtselesai . ' ('.round($dtoutput->decdurasi).')'?></td>
                            <td><?=$target?></td>
                            <td><?=$intactual?></td>
                            <td><?=$dtoutput->intreject?></td>
                            <td><?=$losses?></td>
                            <td><?=$keteranganover?></td>
                            <td><?=$keteranganless?></td>
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

              <div class="tab-pane <?=$shift2active?>" id="shift2ouput">
                <div class="row">
                  <div class="col-md-12">
                    <div class="table-responsive">
                      <table class="table table-bordered table-striped table-hover">
                      <thead>
                          <tr>
                            <th rowspan="2" style="vertical-align:middle; align:center">Model</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Component</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Time (Minutes)</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Target</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Output</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Reject</th>
                            <th rowspan="2" style="vertical-align:middle; align:center">Loss</th>
                            <th colspan="2">Not Follow SOP</th>
                          </tr>
                          <tr>
                            <th>Over</th>
                            <th>Less</th>
                          </tr>
                          <tr>
                            <th>Total</th>
                            <th></th>
                            <th><?=$durasioutput2?></th>
                            <th><?=$tottarget2?></th>
                            <th><?=$totoutput2?></th>
                            <th><?=$totreject2?></th>
                            <th><?=$tottarget2 - $totoutput2?></th>
                            <th><?=$totalover2?></th>
                            <th><?=$totalless2?></th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php
                            foreach ($listoutput2 as $dtoutput) {
                              $target = round((strtotime($dtoutput->dtselesai) - strtotime($dtoutput->dtmulai))/$dtoutput->decct);
                              if ($dtoutput->inttarget > 0) {
                                $target = $dtoutput->inttarget;
                              }

                              $intactual      = ($dtoutput->intpasang > $target && $dtoutput->vcketerangan != '') ? $target : $dtoutput->intpasang;
                              $losses         = $target - $intactual;
                              $lossessop      = ($dtoutput->intpasang > $target && $dtoutput->vcketerangan != '') ? -($target - $dtoutput->intpasang) : $target - $dtoutput->intpasang;
                              $keteranganover = 0;
                              $keteranganless = 0;
                              $warna          = '';

                              if ($dtoutput->intpasang > $target && $dtoutput->vcketerangan != '') {
                                $keteranganover = $lossessop;
                                $warna          = 'danger';
                              } elseif ($dtoutput->vcketerangan != '') {
                                $keteranganless = $lossessop;
                                $warna          = 'danger';
                              }
                          ?>
                          <tr class="<?=$warna?>">
                            <td><?=$dtoutput->vcmodel?></td>
                            <td><?=$dtoutput->vckomponen?></td>
                            <td><?=$dtoutput->dtmulai . ' - ' . $dtoutput->dtselesai . ' ('.round($dtoutput->decdurasi).')'?></td>
                            <td><?=$target?></td>
                            <td><?=$intactual?></td>
                            <td><?=$dtoutput->intreject?></td>
                            <td><?=$losses?></td>
                            <td><?=$keteranganover?></td>
                            <td><?=$keteranganless?></td>
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
            </div>
          </div>
          <div class="table-responsive">
            
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-knob/js/jquery.knob.js"></script>
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script type="text/javascript">
  var _intrealtime = <?=$intrealtime?>

  if (_intrealtime == 1) {
    $(function () {
      setTimeout(function(){
           location.reload();
          },60000);
    });
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
    /* jQueryKnob */

    $(".knob").knob({
      /*change : function (value) {
       //console.log("change : " + value);
       },
       release : function (value) {
       console.log("release : " + value);
       },
       cancel : function () {
       console.log("cancel : " + this.value);
       },*/
      draw: function () {

        // "tron" case
        if (this.$.data('skin') == 'tron') {

          var a = this.angle(this.cv)  // Angle
              , sa = this.startAngle          // Previous start angle
              , sat = this.startAngle         // Start angle
              , ea                            // Previous end angle
              , eat = sat + a                 // End angle
              , r = true;

          this.g.lineWidth = this.lineWidth;

          this.o.cursor
          && (sat = eat - 0.3)
          && (eat = eat + 0.3);

          if (this.o.displayPrevious) {
            ea = this.startAngle + this.angle(this.value);
            this.o.cursor
            && (sa = ea - 0.3)
            && (ea = ea + 0.3);
            this.g.beginPath();
            this.g.strokeStyle = this.previousColor;
            this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sa, ea, false);
            this.g.stroke();
          }

          this.g.beginPath();
          this.g.strokeStyle = r ? this.o.fgColor : this.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth, sat, eat, false);
          this.g.stroke();

          this.g.lineWidth = 2;
          this.g.beginPath();
          this.g.strokeStyle = this.o.fgColor;
          this.g.arc(this.xy, this.xy, this.radius - this.lineWidth + 1 + this.lineWidth * 2 / 3, 0, 2 * Math.PI, false);
          this.g.stroke();

          return false;
        }
      }
    });
    /* END JQUERY KNOB */

    //INITIALIZE SPARKLINE CHARTS
    $(".sparkline").each(function () {
      var $this = $(this);
      $this.sparkline('html', $this.data());
    });

    /* SPARKLINE DOCUMENTATION EXAMPLES http://omnipotent.net/jquery.sparkline/#s-about */
    drawDocSparklines();
    drawMouseSpeedDemo();

  });

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
    var intmesin        = <?=$intmesin?>;
    window.location.href = base_url + 'oee_monitoring/machine/'+ intmesin + '/' + convertdate(_date1) + '/' + convertdate(_date2);
  }
</script>