<?php
    foreach ($gedung as $dtgedung) {
        if ($dtgedung['avgoee'] >= 70) {
            $warna = '#00ff00';
            $bar = '#00ff00';
        } elseif ($dtgedung['avgoee'] >= 60) {
            $warna = '#ffff00';
            $bar = '#ffff00';
        } else{
            $warna = '#ff0000';
            $bar = '#ff0000';
        }
?>
    <div class="col-md-4">
        <a href="<?=base_url('dashboard/oee_building/'.$dtgedung['intgedung'])?>">
            <div class="box box-solid" style="background-color: #000000">
                <div class="box-header">
                    <h3 class="box-title" style="color: #ffffff; font-weight: bold; ">OEE <?=$dtgedung['vcgedung']?></h3>
                </div>
                <div class="box-body text-center">
                    <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$dtgedung['avgoee']?>%" data-width="120" data-height="120" data-fgColor="<?=$warna?>" readonly>
                </div>
                <div class="box-header" >
                    <h4 style="color: #ffffff;" >
                        OEE
                        <small class="pull-right" style="color: #ffffff;"><?=$dtgedung['avgoee']?>%</small>
                    </h4>
                    <div class="progress xs">
                        <div class="progress-bar progress-bar-success" style="width: <?=$dtgedung['avgoee']?>%; background-color: <?=$bar?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
            </div>
        </a>
    </div>
    
<?php
    }
?>

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