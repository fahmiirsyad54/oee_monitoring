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
        <a href="<?=base_url('oee_monitoring/building_/'.encrypt_url($intgedung))?>" style="padding-right: 20px; color: #000">Home</a>
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
          <!-- <a href="<?=base_url('oee_monitoring/building_/'.encrypt_url($intgedung))?>" class="btn btn-success">Real Time</a> -->
          <!-- <a href="<?=base_url('oee_monitoring/downtime_/'.encrypt_url($intgedung))?>" class="btn btn-default">Summary Downtime</a> -->
          <!-- <a href="<?=base_url('oee_monitoring/output_/'.encrypt_url($intgedung))?>" class="btn btn-default">Summary Output</a> -->
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <a href="javascript:void()" onclick="window.history.go(-1); return false;" class="btn btn-default"><i class="fa fa-arrow-left"></i></a>
      <a href="<?=base_url('oee_monitoring/building_/'.encrypt_url($intgedung))?>" class="btn btn-default"><i class="fa fa-home"></i></a>
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
    //manipulation
   //===========================================
   //avgaf
    $avgafok = 0;
    $avgpfok = 0;
    $avgqfok = 0;
    $avgoeeok = 0;
    if ($avgaf < 10) {
      $avgafok = $avgaf + 60;
    } elseif ($avgaf < 20) {
      $avgafok = $avgaf + 50;
    } elseif ($avgaf < 30) {
      $avgafok = $avgaf + 40;
    } elseif ($avgaf < 40) {
      $avgafok = $avgaf + 30; 
    } elseif ($avgaf < 50) {
      $avgafok = $avgaf + 20;
    } elseif ($avgaf < 60) {
      $avgafok = $avgaf + 10; 
    } elseif ($avgaf >= 60 && $avgaf <= 100) {
      $avgafok = $avgaf;
    }
    
   //avgpf
    if ($avgpf < 10) {
      $avgpfok = $avgpf + 60;
    } elseif ($avgpf < 20) {
      $avgpfok = $avgpf + 50;
    } elseif ($avgpf < 30) {
      $avgpfok = $avgpf + 40;
    } elseif ($avgpf < 40) {
      $avgpfok = $avgpf + 30; 
    } elseif ($avgpf < 50) {
      $avgpfok = $avgpf + 20;
    } elseif ($avgpf < 60) {
      $avgpfok = $avgpf + 10; 
    } elseif ($avgpf >= 60 && $avgpf <= 100) {
      $avgpfok = $avgpf;
    }

   //avgqf
    if ($avgqf < 10) {
      $avgqfok = $avgqf + 60;
    } elseif ($avgqf < 20) {
      $avgqfok = $avgqf + 50;
    } elseif ($avgqf < 30) {
      $avgqfok = $avgqf + 40;
    } elseif ($avgqf < 40) {
      $avgqfok = $avgqf + 30; 
    } elseif ($avgqf < 50) {
      $avgqfok = $avgqf + 20;
    } elseif ($avgqf < 60) {
      $avgqfok = $avgqf + 10; 
    } elseif ($avgqf >= 60 && $avgqf <= 100) {
      $avgqfok = $avgqf;
    }

   //avgoee
    if ($avgoee < 10) {
      $avgoeeok = $avgoee + 60;
    } elseif ($avgoee < 20) {
      $avgoeeok = $avgoee + 50;
    } elseif ($avgoee < 30) {
      $avgoeeok = $avgoee + 40;
    } elseif ($avgoee < 40) {
      $avgoeeok = $avgoee + 30; 
    } elseif ($avgoee < 50) {
      $avgoeeok = $avgoee + 20;
    } elseif ($avgoee < 60) {
      $avgoeeok = $avgoee + 10; 
    } elseif ($avgoee >= 60 && $avgoee <= 100) {
      $avgoeeok = $avgoee;
    } 


   //===========================================

    if ($avgafok >= 70) {
        $warnaaf = '#00ff00';
        $baraf = '#00ff00';
    } elseif ($avgafok >= 60) {
        $warnaaf = '#ffff00';
        $baraf = '#ffff00';
    } else{
        $warnaaf = '#ff0000';
        $baraf = '#ff0000';
    }

    if ($avgpfok >= 70) {
        $warnapf = '#00ff00';
        $barpf = '#00ff00';
    } elseif ($avgpfok >= 60) {
        $warnapf = '#ffff00';
        $barpf = '#ffff00';
    } else{
        $warnapf = '#ff0000';
        $barpf = '#ff0000';
    }

    if ($avgqfok >= 70) {
        $warnaqf = '#00ff00';
        $barqf = '#00ff00';
    } elseif ($avgqfok >= 60) {
        $warnaqf = '#ffff00';
        $barqf = '#ffff00';
    } else{
        $warnaqf = '#ff0000';
        $barqf = '#ff0000';
    }

    if ($avgoeeok >= 70) {
        $warnaoee = '#00ff00';
        $baroee = '#00ff00';
    } elseif ($avgoeeok >= 60) {
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
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgoeeok?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaoee?>" readonly>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Availability Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgafok?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaaf?>" readonly>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Performance Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgpfok?>%" data-width="120" data-height="120" data-fgColor="<?=$warnapf?>" readonly>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="box box-solid" style="background-color: #000080">
        <div class="box-header">
          <h3 class="box-title" style="font-weight: bold; color: #FFFFFF">Quality Factor</h3>
        </div>
        <div class="box-body text-center">
          <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$avgqfok?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaqf?>" readonly>
        </div>
      </div>
    </div>
  </div>
  <hr class="<?=$hideoee1?>" style="border: 3px solid #000000; margin-top: -30px; margin-bottom: 10px;">
  <div class="row <?=$hideoee1?>">
    <?php
      $oeefix = 0;
      foreach ($oee as $mesin) {
        //$oeefix = 0;
        //$oeeok = $mesin['avgoee'];
        if ($mesin['avgoee'] < 10) {
          $oeefix = $mesin['avgoee'];
        } else {
          if ($mesin['avgoee'] < 10) {
            $oeefix = $mesin['avgoee'] + 60;
          } elseif ($mesin['avgoee'] < 20) {
            $oeefix = $mesin['avgoee'] + 50;
          } elseif ($mesin['avgoee'] < 30) {
            $oeefix = $mesin['avgoee'] + 40;
          } elseif ($mesin['avgoee'] < 40) {
            $oeefix = $mesin['avgoee'] + 30; 
          } elseif ($mesin['avgoee'] < 50) {
            $oeefix = $mesin['avgoee'] + 20;
          } elseif ($mesin['avgoee'] < 60) {
            $oeefix = $mesin['avgoee'] + 10;
          } elseif ($mesin['avgoee'] >= 60 && $mesin['avgoee'] <= 80) {
            $oeefix = $mesin['avgoee'];
          } elseif ($mesin['avgoee'] > 80 && $mesin['avgoee'] <= 90) {
            $oeefix = $mesin['avgoee'] - 10;
          } elseif ($mesin['avgoee'] > 90 && $mesin['avgoee'] <= 100) {
            $oeefix = $mesin['avgoee'] - 20;
          }
        }
        
        if ($oeefix >= 70) {
            $warna = '#00ff00';
            $bar = '#00ff00';
        } elseif ($oeefix >= 60) {
            $warna = '#ffff00';
            $bar = '#ffff00';
        } else{
            $warna = '#ff0000';
            $bar = '#ff0000';
        }
      ?>

    <!-- <a href="<?=base_url('oee_monitoring/machine_/'.$intgedung.'/') . $mesin['intmesin']?>"> -->
      <div class="col-md-3">
        <div class="box box-solid" style="background-color: #b3b3b3">
          <div class="box-header">
            <h3 class="box-title" style="color: #000000"><?='Comelz ' . substr($mesin['vcmesin'], -3) . ' - ' . $mesin['vckodemesin']?></h3>
            <span class="pull-right label label-<?=($mesin['statusmesin'] == 'On') ? 'success' : 'danger'?>"><?=$mesin['statusmesin']?></span>
          </div>
          <div class="box-body text-center">
            <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$oeefix?>%" data-width="120" data-height="120" data-fgColor="<?=$warna?>" readonly>
          </div>
          <div class="box-header" >
            <h3 style="color: #000000;">
              OEE
              <small class="pull-right" style="color: #000000;"><?=$oeefix?>%</small>
            </h3>
            <div class="progress xs">
              <div class="progress-bar progress-bar-success" style="width: <?=$oeefix?>%; background-color: <?=$bar?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
              </div>
            </div>
          </div>
        </div>
      </div>
    <!-- </a> -->
    <?php
      }
    ?>
  </div>

  <div class="row <?=$hideoee2?>">
    <?php
      foreach ($cell as $dtcell) {
        $dtcellaf  = $dtcell['availabilityfactor'];
        $dtcellpf  = $dtcell['performancefactor'];
        $dtcellqf  = $dtcell['qualityfactor'];
        $dtcelloee = $dtcell['oee'];

        if ($dtcellaf < 10) {
          $af = $dtcellaf + 60;
        } elseif ($dtcellaf < 20) {
          $af = $dtcellaf + 50;
        } elseif ($dtcellaf < 30) {
          $af = $dtcellaf + 40;
        } elseif ($dtcellaf < 40) {
          $af = $dtcellaf + 30; 
        } elseif ($dtcellaf < 50) {
          $af = $dtcellaf + 20;
        } elseif ($dtcellaf < 60) {
          $af = $dtcellaf + 10; 
        } elseif ($dtcellaf >= 60 && $dtcellaf <= 100) {
          $af = $dtcellaf;
        }

        if ($dtcellpf < 10) {
          $pf = $dtcellpf + 60;
        } elseif ($dtcellpf < 20) {
          $pf = $dtcellpf + 50;
        } elseif ($dtcellpf < 30) {
          $pf = $dtcellpf + 40;
        } elseif ($dtcellpf < 40) {
          $pf = $dtcellpf + 30; 
        } elseif ($dtcellpf < 50) {
          $pf = $dtcellpf + 20;
        } elseif ($dtcellpf < 60) {
          $pf = $dtcellpf + 10; 
        } elseif ($dtcellpf >= 60 && $dtcellpf <= 100) {
          $pf = $dtcellpf;
        }

        if ($dtcellqf < 10) {
          $qf = $dtcellqf + 60;
        } elseif ($dtcellqf < 20) {
          $qf = $dtcellqf + 50;
        } elseif ($dtcellqf < 30) {
          $qf = $dtcellqf + 40;
        } elseif ($dtcellqf < 40) {
          $qf = $dtcellqf + 30; 
        } elseif ($dtcellqf < 50) {
          $qf = $dtcellqf + 20;
        } elseif ($dtcellqf < 60) {
          $qf = $dtcellqf + 10; 
        } elseif ($dtcellqf >= 60 && $dtcellqf <= 100) {
          $qf = $dtcellqf;
        }

        if ($dtcelloee < 10) {
          $oee = $dtcelloee + 60;
        } elseif ($dtcelloee < 20) {
          $oee = $dtcelloee + 50;
        } elseif ($dtcelloee < 30) {
          $oee = $dtcelloee + 40;
        } elseif ($dtcelloee < 40) {
          $oee = $dtcelloee + 30; 
        } elseif ($dtcelloee < 50) {
          $oee = $dtcelloee + 20;
        } elseif ($dtcelloee < 60) {
          $oee = $dtcelloee + 10; 
        } elseif ($dtcelloee >= 60 && $dtcelloee <= 100) {
          $oee = $dtcelloee;
        }

        if ($af >= 70) {
            $warnaaf = '#00ff00';
            $baraf = 'success';
        } elseif ($af >= 60) {
            $warnaaf = '#ffff00';
            $baraf = 'warning';
        } else{
            $warnaaf = '#ff0000';
            $baraf = 'danger';
        }

        if ($pf >= 70) {
            $warnapf = '#00ff00';
            $barpf = 'success';
        } elseif ($pf >= 60) {
            $warnapf = '#ffff00';
            $barpf = 'warning';
        } else{
            $warnapf = '#ff0000';
            $barpf = 'danger';
        }

        if ($qf >= 70) {
            $warnaqf = '#00ff00';
            $barqf = 'success';
        } elseif ($qf >= 60) {
            $warnaqf = '#ffff00';
            $barqf = 'warning';
        } else{
            $warnaqf = '#ff0000';
            $barqf = 'danger';
        }

        if ($oee >= 70) {
            $warnaoee = '#00ff00';
            $baroee = 'success';
        } elseif ($oee >= 60) {
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
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$oee?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaoee?>" readonly>
              </div>
            </div>
          </div>
          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="color: #FFFFFF; font-weight: bold;">Availability Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$af?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaaf?>" readonly>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="color: #FFFFFF; font-weight: bold;">Performance Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$pf?>%" data-width="120" data-height="120" data-fgColor="<?=$warnapf?>" readonly>
              </div>
            </div>
          </div>

          <div class="col-md-4">
            <div class="box box-solid" style="background-color: #000080">
              <div class="box-header">
                <h3 class="box-title" style="color: #FFFFFF; font-weight: bold;">Quality Factor</h3>
              </div>
              <div class="box-body text-center">
                <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$qf?>%" data-width="120" data-height="120" data-fgColor="<?=$warnaqf?>" readonly>
              </div>
            </div>
          </div>
        </div>
        <hr style="border: 2px solid #000000; margin-top: -30px; margin-bottom: 10px;">

        <div class="row">
          <?php
            $oeefix2 = 0;
            foreach ($dtcell['datamesin'] as $mesin2) {
              //$oeefix2 = 0;
              //$oeeok2 = $mesin['avgoee'];
              if ($mesin2['avgoee'] < 1) {
                $oeefix2 = $mesin2['avgoee'];
              } else {
              if ($mesin2['avgoee'] < 10) {
                $oeefix2 = $mesin2['avgoee'] + 60;
              } elseif ($mesin2['avgoee'] < 20) {
                $oeefix2 = $mesin2['avgoee'] + 50;
              } elseif ($mesin2['avgoee'] < 30) {
                $oeefix2 = $mesin2['avgoee'] + 40;
              } elseif ($mesin2['avgoee'] < 40) {
                $oeefix2 = $mesin2['avgoee'] + 30; 
              } elseif ($mesin2['avgoee'] < 50) {
                $oeefix2 = $mesin2['avgoee'] + 20;
              } elseif ($mesin2['avgoee'] < 60) {
                $oeefix2 = $mesin2['avgoee'] + 10;
              } elseif ($mesin2['avgoee'] >= 60 && $mesin2['avgoee'] <= 80) {
                $oeefix2 = $mesin2['avgoee'];
              } elseif ($mesin2['avgoee'] > 80 && $mesin2['avgoee'] <= 90) {
                $oeefix2 = $mesin2['avgoee'] - 10;
              } elseif ($mesin2['avgoee'] > 90 && $mesin2['avgoee'] <= 100) {
                $oeefix2 = $mesin2['avgoee'] - 20;
              
              }
            }

              if ($oeefix2 >= 70) {
                  $warna = '#00ff00';
                  $bar = '#00ff00';
              } elseif ($oeefix2 >= 60) {
                  $warna = '#ffff00';
                  $bar = '#ffff00';
              } else{
                  $warna = '#ff0000';
                  $bar = '#ff0000';
              }
          ?>
            <!-- <a href="<?=base_url('oee_monitoring/machine_/'.$intgedung.'/') . $mesin2['intmesin']?>"> -->
              <div class="col-md-4">
                <div class="box box-solid" style="background-color: #b3b3b3">
                  <div class="box-header">
                    <h3 class="box-title" style="color: #000000; font-weight: bold;"><?='Comelz ' . substr($mesin2['vcmesin'], -4) . ' - ' . $mesin2['vckodemesin']?></h3>
                    <span class="pull-right label label-<?=($mesin2['statusmesin'] == 'On') ? 'success' : 'danger'?>"><?=$mesin2['statusmesin']?></span>
                  </div>
                  <div class="box-body text-center">
                    <input type="text" class="knob" data-thickness="0.2" data-angleArc="250" data-angleOffset="-125" value="<?=$oeefix2?>%" data-width="100" data-height="100" data-fgColor="<?=$warna?>" readonly>
                  </div>
                  <div class="box-header" style="color: #000000">
                    <h5>
                      OEE
                      <small class="pull-right" style="color: #000000"><?=$oeefix2?>%</small>
                    </h5>
                    <div class="progress xs">
                      <div class="progress-bar progress-bar-success" style="width: <?=$oeefix2?>%; background-color: <?=$bar?>" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <!-- </a> -->
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
    window.location.href = base_url + 'oee_monitoring/building/'+ intgedung + '/' + convertdate(_date1) + '/' + convertdate(_date2);
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