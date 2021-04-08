<script>
  $(function () {
    $('.datepicker').datepicker({
      autoclose: true
    })
  }) 
</script>
<?php
  $hideoee1 = ($jumlahcell > 1) ? 'hidden' : '';
  $hideoee2 = ($jumlahcell > 1) ? '' : 'hidden';
?>
<div class="row">
  <div class="col-md-12">
    <div class="alert alert-info" style="border-radius: 0px; background-color: #b7fd35 !important; border-color: #b7fd35 !important">
      <span id="realtime" style="color: #000"></span>
      <div class="pull-right">
        <a href="<?=base_url('oee_monitoring')?>" style="padding-right: 20px; color: #000">Home</a>
        <a href="<?=base_url('akses/logoutoee')?>" style="color: #000"><i class="fa fa-sign-out"></i> Log Out</a>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-md-1 <?=$hideoee2?>">
      <h4 class="" style="font-weight: bold; color: #000000"><?=$gedung[0]->vcnama?></h4>
    </div>
    <div class="col-md-4">
      <div class="form-group">
        <div class="btn-group" role="group">
          <a href="<?=base_url('oee_monitoring/bdg/'.$intgedung)?>" class="btn btn-success">Real Time</a>
          <!-- <a href="<?=base_url('oee_monitoring/downtime/'.$intgedung)?>" class="btn btn-default">Summary Downtime</a> -->
          <a href="<?=base_url('oee_monitoring/out/'.$intgedung)?>" class="btn btn-default">Summary Output</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <a href="javascript:void()" onclick="window.history.go(-1); return false;" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
      <a href="<?=base_url('oee_monitoring')?>" class="btn btn-default"><i class="fa fa-home"></i></a>
      <a href="javascript:void()" onclick="window.history.go(1); return false;" class="btn btn-default"><i class="fa fa-arrow-right"></i></a>
    </div>

    <div class="col-md-2 hidden">
      <div class="form-group">
        <input type="text" name="from" placeholder="From" class="form-control datepicker" id="from" value="<?=$from?>" />
      </div>
    </div>

    <div class="col-md-2 hidden">
      <div class="form-group">
        <input type="text" name="to" placeholder="To" class="form-control datepicker" id="to" value="<?=$to?>" />
      </div>
    </div>

    <div class="col-md-1 hidden">
      <div class="form-group">
        <button class="btn btn-info btn-block" onclick="showoee()">Show</button>
      </div>
    </div>
    
    <div class="col-md-12 <?=$hideoee1?>">
      <h4 class="" style="font-weight: bold; color: #000000"><?=$gedung[0]->vcnama?></h4>
    </div>
  </div>
  <?php
    if ($avgaf >= 80) {
        $warnaaf = '#00ff00';
        $baraf = '#00ff00';
    } elseif ($avgaf >= 60) {
        $warnaaf = '#ffff00';
        $baraf = '#ffff00';
    } else{
        $warnaaf = '#ff0000';
        $baraf = '#ff0000';
    }

    if ($avgpf >= 80) {
        $warnapf = '#00ff00';
        $barpf = '#00ff00';
    } elseif ($avgpf >= 60) {
        $warnapf = '#ffff00';
        $barpf = '#ffff00';
    } else{
        $warnapf = '#ff0000';
        $barpf = '#ff0000';
    }

    if ($avgqf >= 80) {
        $warnaqf = '#00ff00';
        $barqf = '#00ff00';
    } elseif ($avgqf >= 60) {
        $warnaqf = '#ffff00';
        $barqf = '#ffff00';
    } else{
        $warnaqf = '#ff0000';
        $barqf = '#ff0000';
    }

    if ($avgoee >= 80) {
        $warnaoee = '#00ff00';
        $baroee = '#00ff00';
    } elseif ($avgoee >= 60) {
        $warnaoee = '#ffff00';
        $baroee = '#ffff00';
    } else{
        $warnaoee = '#ff0000';
        $baroee = '#ff0000';
    }
  ?>
  <div class="row <?=$hideoee1?>">
    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #ffc266">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #000000">OEE</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgoee?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaoee?>" readonly>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Availability Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgaf?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaaf?>" readonly>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Performance Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgpf?>%" data-width="120" data-height="120" data-fgColor="<?=$warnapf?>" readonly>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Quality Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgqf?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaqf?>" readonly>
        </div>
      </div>
    </div>
  </div>
  <hr class="<?=$hideoee1?>" style="border: 3px solid #000000; margin-top: -30px; margin-bottom: 10px;">
  <div class="row <?=$hideoee1?>">
    <?php
      foreach ($oee as $mesin) {
        if ($mesin['avgoee'] >= 80) {
            $warna = '#00ff00';
            $bar = '#00ff00';
        } elseif ($mesin['avgoee'] >= 60) {
            $warna = '#ffff00';
            $bar = '#ffff00';
        } else{
            $warna = '#ff0000';
            $bar = '#ff0000';
        }

        if ($mesin['intautocutting'] == 3) {
          $namamesin = 'Winwings';
        } else {
          $namamesin = 'Comelz';
        }
    ?>

    <a href="<?=base_url('oee_monitoring/machine/') . $mesin['intmesin']?>">
      <div class="col-md-3">
        <div class="box box-solid" style="background-color: #b3b3b3">
          <div class="box-header">
            <h3 class="box-title" style="color: #000000"><?=$namamesin . ' ' . substr($mesin['vcmesin'], -3) . ' - ' . $mesin['vckodemesin']?></h3>
            <span class="pull-right label label-<?=($mesin['statusmesin'] == 'On') ? 'success' : 'danger'?>"><?=$mesin['statusmesin']?></span>
          </div>
          <div class="box-body text-center">
            <span class="pull-left label label-success"><?=$mesin['statuskalibrasi']?></span>
            <br>
            <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$mesin['avgoee']?>%" data-width="120" data-height="120" data-fgColor="<?=$warna?>" readonly>
          </div>
          <div class="box-header" >
            <h3 style="color: #000000;">
              OEE
              <small class="pull-right" style="color: #000000;"><?=$mesin['avgoee']?>%</small>
            </h3>
            <div class="progress xs">
              <div class="progress-bar progress-bar-success" style="width: <?=$mesin['avgoee']?>%; background-color: <?=$bar?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
          </div>
        </div>
      </div>
    </a>
    <?php
      }
    ?>
  </div>

  <div class="row <?=$hideoee2?>">
    <?php
      foreach ($cell as $dtcell) {
        if ($avgaf >= 80) {
            $warnaaf = '#00ff00';
            $baraf = '#00ff00';
        } elseif ($avgaf >= 60) {
            $warnaaf = '#ffff00';
            $baraf = '#ffff00';
        } else{
            $warnaaf = '#ff0000';
            $baraf = '#ff0000';
        }

        if ($dtcell['availabilityfactor'] >= 80) {
            $warnaaf = '#00ff00';
            $baraf = 'success';
        } elseif ($dtcell['availabilityfactor'] >= 60) {
            $warnaaf = '#ffff00';
            $baraf = 'warning';
        } else{
            $warnaaf = '#ff0000';
            $baraf = 'danger';
        }

        if ($dtcell['performancefactor'] >= 80) {
            $warnapf = '#00ff00';
            $barpf = 'success';
        } elseif ($dtcell['performancefactor'] >= 60) {
            $warnapf = '#ffff00';
            $barpf = 'warning';
        } else{
            $warnapf = '#ff0000';
            $barpf = 'danger';
        }

        if ($dtcell['qualityfactor'] >= 80) {
            $warnaqf = '#00ff00';
            $barqf = 'success';
        } elseif ($dtcell['qualityfactor'] >= 60) {
            $warnaqf = '#ffff00';
            $barqf = 'warning';
        } else{
            $warnaqf = '#ff0000';
            $barqf = 'danger';
        }

        if ($dtcell['oee'] >= 80) {
            $warnaoee = '#00ff00';
            $baroee = 'success';
        } elseif ($dtcell['oee'] >= 60) {
            $warnaoee = '#ffff00';
            $baroee = 'warning';
        } else {
            $warnaoee = '#ff0000';
            $baroee = 'danger';
        }
    ?>
      <div class="col-md-6">
        <div class="row">
          <div class="col-md-12">
            <h4 class="pull-right" style="color: #000000"><?=$dtcell['vccell']?></h4>
          </div>
        </div>
        
        <div class="row">
          <div class="col-md-12">
            <div class="box box-solid" style="background-color: #ffc266">
              <div class="box-header">
                <h3 class="box-title" style="color: #000000; font-weight: bold;">OEE</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$dtcell['oee']?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaoee?>" readonly>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="color: #FFFFFF; font-weight: bold;">Availability Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$dtcell['availabilityfactor']?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaaf?>" readonly>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="color: #FFFFFF; font-weight: bold;">Performance Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$dtcell['performancefactor']?>%" data-width="120" data-height="120" data-fgColor="<?=$warnapf?>" readonly>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="color: #FFFFFF; font-weight: bold;">Quality Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$dtcell['qualityfactor']?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaqf?>" readonly>
              </div>
            </div>
          </div>
        </div>
        <hr style="border: 2px solid #000000; margin-top: -30px; margin-bottom: 10px;">

        <div class="row">
          <?php
            foreach ($dtcell['datamesin'] as $mesin) {
              if ($mesin['avgoee'] >= 80) {
                  $warna = '#00ff00';
                  $bar = '#00ff00';
              } elseif ($mesin['avgoee'] >= 60) {
                  $warna = '#ffff00';
                  $bar = '#ffff00';
              } else{
                  $warna = '#ff0000';
                  $bar = '#ff0000';
              }

              if ($mesin['intautocutting'] == 3) {
                $namamesin = 'Winwings';
              } else {
                $namamesin = 'Comelz';
              }
          ?>
            <a href="<?=base_url('oee_monitoring/machine/') . $mesin['intmesin']?>">
              <div class="col-md-4">
                <div class="box box-solid" style="background-color: #b3b3b3">
                  <div class="box-header">
                    <h3 class="box-title" style="color: #000000"><?=$namamesin . ' ' . substr($mesin['vcmesin'], -4) . ' - ' . $mesin['vckodemesin']?></h3>
                    <span class="pull-right label label-<?=($mesin['statusmesin'] == 'On') ? 'success' : 'danger'?>"><?=$mesin['statusmesin']?></span>
                  </div>
                  <div class="box-body text-center">
                    <span class="pull-left label label-success"><?=$mesin['statuskalibrasi']?></span>
                    <br>
                    <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$mesin['avgoee']?>%" data-width="100" data-height="100" data-fgColor="<?=$warna?>" readonly>
                  </div>
                  <div class="box-header" style="color: #000000">
                    <h5>
                      OEE
                      <small class="pull-right" style="color: #000000"><?=$mesin['avgoee']?>%</small>
                    </h5>
                    <div class="progress xs">
                      <div class="progress-bar progress-bar-success" style="width: <?=$mesin['avgoee']?>%; background-color: <?=$bar?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </a>
          <?php
            }
          ?>
        </div>
      </div>
    <?php
      }
    ?>
  </div>
</div>


<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-knob/js/jquery.knob.js"></script>
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script type="text/javascript">
    $(function () {
      setTimeout(function(){
            location.reload();
          },180000);
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
    var intgedung        = <?=$intgedung?>;
    window.location.href = base_url + 'oee_monitoring/bdg/'+ intgedung + '/' + convertdate(_date1) + '/' + convertdate(_date2);
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
    /* jQueryKnob */

    $(".knob").knob({
      'min': 0,
      'max': 100,
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

  });
</script>