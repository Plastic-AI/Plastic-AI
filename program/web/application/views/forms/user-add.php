<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-md-12 col-sm-12">

		<?php print form_open(current_url(), array('id' => 'form-user-add', 'class' => 'card shadow-base bd-0'));?>

		<div class="card-body">

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Group <span class="tx-danger">*</span></label>
						<select id="select-brand" name="cbUserGroup" class="form-control select-two" data-placeholder="-- Select --" >
							<option></option>
							<?php foreach ($arr_user_group as $value): ?>
								<option value="<?php print $value->user_group_id;?>" >
									<?php print $value->user_group_name;?>
								</option>
							<?php endforeach;?>
						</select>
						<ul class="fields-message">
							<?php echo form_error('cbUserGroup', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Fullname <span class="tx-danger">*</span></label>
						<input class="form-control" type="text" name="txtFullname" placeholder="">
						<ul class="fields-message">
							<?php echo form_error('txtFullname', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Login <span class="tx-danger">*</span></label>
						<input class="form-control" name="txtNewLogin" type="text" placeholder="">
						<ul class="fields-message">
							<?php echo form_error('txtNewLogin', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Email <span class="tx-danger">*</span></label>
						<input class="form-control" name="txtEmailLogin" type="text" placeholder="">
						<ul class="fields-message">
							<?php echo form_error('txtEmailLogin', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Password <span class="tx-danger">*</span></label>
						<input class="form-control" type="password" name="txtNewPassword" placeholder="">
						<ul class="fields-message">
							<?php echo form_error('txtNewPassword', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Confirm Password <span class="tx-danger">*</span></label>
						<input class="form-control" type="password" name="txtConfirmNewPassword" placeholder="">
						<ul class="fields-message">
							<?php echo form_error('txtConfirmNewPassword', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Balance</label>
						<input class="form-control" name="txtBalance" type="number" placeholder="">
						<ul class="fields-message">
							<?php echo form_error('txtBalance', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Status <span class="tx-danger">*</span></label>
						<select class="form-control select-two" name="cbStatus" data-placeholder="-- Select Status --">
							<option></option>
							<option value="<?php print STATUS_ENABLE;?>" <?php echo set_select('cbStatus', STATUS_ENABLE); ?>>Enable</option>
							<option value="<?php print STATUS_DISABLE;?>" <?php echo set_select('cbStatus', STATUS_DISABLE); ?>>Disable</option>
						</select>
						<ul class="fields-message">
							<?php echo form_error('cbStatus', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-md-6 pd-t-15">
					<div class="form-group">
						<label class="ckbox ckbox-danger">
							<input type="checkbox" value="1" name="cbApiAccessOnly" <?php echo set_checkbox('cbApiAccessOnly', '1'); ?>>
							<span>API Access Only</span>
						</label>
						<ul class="fields-message">
						</ul>
					</div>
				</div>

			</div>
		</div>
		<div class="card-footer bd-color-gray-lighter text-right">
			<button type="submit" class="btn btn-primary tx-size-xs ">Submit</button>
		</div>

		<?php print form_close();?>

	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {

	$('#form-user-add').on('submit', function(event){
		event.preventDefault();

		var request 	= '<?php print $this->req_controller;?>/<?php print $this->action;?>',
			form 		= $(this);

		loading(form, 'show');
		ajax_post(request, form.serialize(), function(result) {
			init_meta(result.meta);
			loading(form, 'hide');
		});
	});
});
</script>