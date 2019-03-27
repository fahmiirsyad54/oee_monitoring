<header class="main-header">
        <!-- Logo -->
        <a href="<?=base_url()?>" class="logo" style="background: #00ccff">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>T</b>PM</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="<?=BASE_URL_PATH?>dist/img/logo.jpg" style="height: 40px;"> System</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" style="background: #00b8e6">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      <div class="navbar-custom-menu" style="background: #00b8e6">
        <ul class="nav navbar-nav">
          <li class="dropdown messages-menu">
            <a href="<?=BASE_URL_PATH?>notes/view" class="dropdown-toggle" data-toggle="dropdown">
              <i class="fa fa-envelope-o"></i>
              <span class="label label-success"><?=$this->notesin?></span>
            </a>
            <ul class="dropdown-menu">
              <li class="header">You have <?=$this->jmlnotes?> messages today, <?=$this->notesin?> message unread</li>
              <li>
                <!-- inner menu: contains the actual data -->
                <ul class="menu">
                  <?php
                   foreach ($this->datanotes as $data) {
                            if ($data->intstatus == 0) {
                              $colorstatus = '#F5F5F5';
                            } elseif ($data->intstatus == 1) {
                              $colorstatus = '#FFFFFF';
                            }
                  ?>

                  <li><!-- start message -->
                    <a href="javascript:void(0);" style="background-color: <?=$colorstatus?>" onclick="detailNotes(<?=$data->intid?>)">
                      <div class="pull-left">
                        <img src="<?=BASE_URL_PATH?>assets/dist/img/iconuser2.png" class="user-image" alt="User Image">
                      </div>
                      <h4>
                        <?=$data->vcmesin?>
                        <small><i class="fa fa-clock-o"></i> <?=date('H:i',strtotime($data->dttanggal))?></small>
                      </h4>
                      <p><?=$data->vcpesan?></p>
                    </a>
                  </li>
                  <?php
                    }
                  ?>
                  <!-- end message -->
                </ul>
              </li>
              <li class="footer"><a href="<?=BASE_URL_PATH?>notes/view">See All Messages</a></li>
            </ul>
          </li>

          <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <img src="<?=BASE_URL_PATH?>assets/dist/img/iconuser2.png" class="user-image" alt="User Image">
              <span class="hidden-xs"><?=$this->session->vcnama?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <img src="<?=BASE_URL_PATH?>assets/dist/img/iconuser2.png" class="img-circle" alt="User Image">

                <p>
                  <?=$this->session->vcnama?>
                  <small></small>
                </p>
              </li>
              <li class="user-footer">
                <div class="pull-left">
                  <a href="<?=base_url('profil/view')?>" class="btn btn-default btn-flat">Profile</a>
                </div>
                <div class="pull-right">
                  <a href="<?=base_url('akses/logout')?>" class="btn btn-default btn-flat">Sign out</a>
                </div>
              </li>
            </ul>
          </li>
        </ul>
      </div>
        </nav>
    </header>

<div id="modalNotes" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">
    <!-- Modal content-->
    <div class="modal-content" id="datanotes">
    </div>
  </div>
</div>
<script type="text/javascript">
  function detailNotes(intid, intstatus) {
    var base_url = '<?=base_url('notes')?>';
    $.ajax({
      url: base_url + '/detail/' + intid,
      method: "GET"
    })
    .done(function( data ) {
      $('#datanotes').html(data);
      $('#modalNotes').modal('show');
      $.ajax({
      url: base_url + '/aksi/detailnotes/' + intid + '/' + intstatus,
      method: "GET"
      })
    })
    .fail(function( jqXHR, statusText ) {
      alert( "Request failed: " + jqXHR.status );
    });
  }
</script>