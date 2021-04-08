<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal">&times;</button>
	<h4 class="modal-title">User Data</h4>
</div>
<div class="modal-body">
	<div class="row">
		<div class="col-md-12">
			<label>Data</label>
			<label class="pull-right">Status : <span class="label label-<?=$dataMain[0]->vcstatuswarna?>"><?=$dataMain[0]->vcstatus?></span></label>
		</div>

		<div class="col-md-12">
			<div class="table-responsive">
				<table class="table table-bordered table-hover table-striped">
					<tr>
						<td><label>Code</label></td>
						<td><?=$dataMain[0]->vckode?></td>
					</tr>

					<tr>
						<td><label>Name</label></td>
						<td><?=$dataMain[0]->vcnama?></td>
					</tr>

					<tr>
						<td><label>Username</label></td>
						<td><?=$dataMain[0]->vcusername?></td>
					</tr>

					<tr>
						<td><label>Access Rights</label></td>
						<td><?=$dataMain[0]->vchakakses?></td>
					</tr>
				</table>
			</div>
		</div>

		<div class="row">
			<div class="col-md-8">
				<div class="panel-heading" align="Left">
					Change password
				</div>
				<div class="table-responsive">
					<table class="table table-bordered tabel-hover tabel-striped">
						<tr>
							<td>Enter new password</td>
							<td>
								<input type="text" name="vcpassword" placeholder="New password" class="form-control" id="vcpassword" required/>
							</td>
						</tr>
						<tr>
							<td>Retype new password</td>
							<td>
								<input type="text" name="vcpasswordretype" placeholder="Retype new password" class="form-control" id="vcpasswordretype" required/>
							</td>
						</tr>
						<tr>
							<td></td>
							<td>
							<div class="form-group pull-right">
								<a href="javascript:void(0);" onclick="reset('<?=$dataMain[0]->intid?>')" class="btn btn-success"><i class="fa fa-retweet"></i>  Reset</a>
							</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	function reset(intid) {
		var vcpassword       = $('#vcpassword').val();
		var vcpasswordretype = $('#vcpasswordretype').val();
		var base_url 		 = '<?=base_url($controller)?>';
		if (vcpassword != vcpasswordretype) {
			swal({
					type: 'error',
					title: 'There is something wrong',
					text: 'Sorry, password is not same'
				});
		} else {
			$.ajax({
				url: base_url + '/validasi_password/' + intid + '/' + vcpassword,
				method: "GET"
				})
			.done(function( data ) {
				$('#vcpassword').val('');
            	$('#vcpasswordretype').val('');
				$('#modalReset').modal('hide');
				swal({
					type: 'success',
					title: 'Congrats',
					text: 'the password was successfully replaced'
					});
			})
			.fail(function( jqXHR, statusText ) {
				alert( "Request failed: " + jqXHR.status );
			});
		}
	}
</script>

