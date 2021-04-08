<style>
@keyframes blink {50% { color: transparent }}
.loader__dot { animation: 2s blink infinite }
.loader__dot:nth-child(2) { animation-delay: 500ms }
.loader__dot:nth-child(3) { animation-delay: 750ms }
</style>
<div id="oeeview">

</div>
<div class="modal fade" id="loadingOEE" role="dialog">
  <div class="modal-dialog">
  
    <!-- Modal content-->
    <div class="modal-content modal-sm">
      <div class="modal-body">
        <div class="text-center">
          <i class="fa fa-spinner fa-pulse fa-5x fa-fw"></i>
          <div class="loader" style="font-size: 18px;">Loading<span class="loader__dot">.</span><span class="loader__dot">.</span><span class="loader__dot">.</span></div>
        </div>
      </div>
    </div>
    
  </div>
</div>
<script>
    $(function (){
        var base_url = "<?=base_url('oee_monitoring')?>";
        $('#loadingOEE').modal({ backdrop: 'static' },'show');
        $.ajax({
            url: base_url + '/buildingall_ajax/<?=$intgedung?>',
            method: "GET"
        })
        .done(function( data ) {
          $('#loadingOEE').modal('hide');
          $('#oeeview').html(data);
        })
        .fail(function( jqXHR, statusText ) {
            alert( "Request failed: " + jqXHR.status );
        });
    });
</script>