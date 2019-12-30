<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-md-7 col-sm-12">
		<?php print form_open('', array('id' => 'form-admin-change-password', 'class' => 'card shadow-base bd-0'));?>
			<div class="card-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="form-control-label">New Password <span class="tx-danger">*</span></label>
							<input class="form-control" type="password" name="txtNewPassword" value="" autofocus >
							<ul class="fields-message"></ul>
						</div>
					</div>
					<div class="col-md-12">
						<div class="form-group">
							<label class="form-control-label">Confirm New Password <span class="tx-danger">*</span></label>
							<input class="form-control" type="password" name="txtNewPasswordVerify" value="">
							<ul class="fields-message"></ul>
						</div>
					</div>
				</div>
			</div>
			<div class="card-footer bd-color-gray-lighter text-right">
				<button type="submit" class="btn btn-primary tx-size-xs">Submit</button>
			</div>
		<?php print form_close();?>
	</div>
	<div class="col-md-5 col-sm-12 mg-t-20 mg-md-t-0">
		<?php $this->load->view('layouts/change-log');?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	get_action_log();
	$('#form-admin-change-password').on('submit', function(event){
		event.preventDefault();
		var request 	= '<?php print $this->req_controller;?>/<?php print $this->action;?>/<?php print $arr_user->user_id;?>',
			form 		= $(this);

		loading(form, 'show');
		ajax_post(request, form.serialize(), function(result) {
			if(result.status == 200) {
				$('form input[name="txtNewPasswordVerify"]').val('');
			}else{
				$('#form-admin-change-password').find('input').val('');
			}
			init_meta(result.meta);
			get_action_log();
			loading(form, 'hide');
		});
	});
});
</script>