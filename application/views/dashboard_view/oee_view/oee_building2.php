<div class="row">
<?php
    foreach ($cell as $dtcell) {
        if ($dtcell['availabilityfactor'] >= 70) {
            $warnaaf = '#00ff00';
            $baraf = 'success';
        } elseif ($dtcell['availabilityfactor'] >= 60) {
            $warnaaf = '#ffff00';
            $baraf = 'warning';
        } else{
            $warnaaf = '#ff0000';
            $baraf = 'danger';
        }

        if ($dtcell['performancefactor'] >= 70) {
            $warnapf = '#00ff00';
            $barpf = 'success';
        } elseif ($dtcell['performancefactor'] >= 60) {
            $warnapf = '#ffff00';
            $barpf = 'warning';
        } else{
            $warnapf = '#ff0000';
            $barpf = 'danger';
        }

        if ($dtcell['qualityfactor'] >= 70) {
            $warnaqf = '#00ff00';
            $barqf = 'success';
        } elseif ($dtcell['qualityfactor'] >= 60) {
            $warnaqf = '#ffff00';
            $barqf = 'warning';
        } else{
            $warnaqf = '#ff0000';
            $barqf = 'danger';
        }

        if ($dtcell['oee'] >= 70) {
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
<div class="col-md-12">

    <div class="row">
    <div class="col-md-12">
        <h4 style="color: #000000"><?=$vcgedung . ' - ' . $dtcell['vccell']?></h4>
    </div>
    </div>
    
    <div class="row">
    <div class="col-md-3">
        <div class="box box-solid" style="background-color: #ffc266">
        <div class="box-header">
            <h3 class="box-title" style="color: #000000; font-weight: bold;">OEE</h3>
        </div>
        <div class="box-body text-center">
            <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$dtcell['oee']?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaoee?>" readonly>
        </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
            <h3 class="box-title" style="color: #FFFFFF; font-weight: bold;">Availability Factor</h3>
        </div>
        <div class="box-body text-center">
            <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$dtcell['availabilityfactor']?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaaf?>" readonly>
        </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
            <h3 class="box-title" style="color: #FFFFFF; font-weight: bold;">Performance Factor</h3>
        </div>
        <div class="box-body text-center">
            <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$dtcell['performancefactor']?>%" data-width="120" data-height="120" data-fgColor="<?=$warnapf?>" readonly>
        </div>
        </div>
    </div>

    <div class="col-md-3">
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
        if ($mesin['avgoee'] >= 70) {
            $warna = '#00ff00';
            $bar = '#00ff00';
        } elseif ($mesin['avgoee'] >= 60) {
            $warna = '#ffff00';
            $bar = '#ffff00';
        } else{
            $warna = '#ff0000';
            $bar = '#ff0000';
        }
    ?>
        <a href="<?=base_url('dashboard/oee_machine/'.$mesin['intmesin'])?>">
        <div class="col-md-3">
            <div class="box box-solid" style="background-color: #b3b3b3">
            <div class="box-header">
                <h3 class="box-title" style="color: #000000; font-weight: bold;"><?='Comelz ' . substr($mesin['vcmesin'], -4) . ' - ' . $mesin['vckodemesin']?></h3>
                <span class="pull-right label label-<?=($mesin['statusmesin'] == 'On') ? 'success' : 'danger'?>"><?=$mesin['statusmesin']?></span>
            </div>
            <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$mesin['avgoee']?>%" data-width="120" data-height="120" data-fgColor="<?=$warna?>" readonly>
            </div>
            <div class="box-header" style="color: #000000">
                <h3>
                OEE
                <small class="pull-right" style="color: #000000"><?=$mesin['avgoee']?>%</small>
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
</div>
<?php
    }
?>
</div>

<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-knob/js/jquery.knob.js"></script>
<script src="<?=BASE_URL_PATH?>assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<script>
    $(function () {
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
    // drawDocSparklines();
    // drawMouseSpeedDemo();

  });
</script>