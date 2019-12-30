<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-md-7 col-sm-12">

		<?php print form_open(current_url(), array('id' => 'form-user-update', 'class' => 'card shadow-base bd-0'));?>

		<div class="card-body">
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
						<label class="form-control-label">Login <span class="tx-danger">*</span></label>
						<input class="form-control" type="text" value="<?php echo set_value('txtUsername', $arr_user->user_login); ?>" placeholder="" required readonly="readonly">
						<ul class="fields-message">
							<?php echo form_error('cbUserGroup', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Group <span class="tx-danger">*</span></label>
						<select id="select-brand" name="cbUserGroup" class="form-control select-two" data-placeholder="-- Select --" required>
							<option></option>
							<?php foreach ($arr_user_group as $adm): ?>
								<option value="<?php print $adm->user_group_id;?>" <?php echo set_select('cbUserGroup', $adm->user_group_id, ($adm->user_group_id == $arr_user->user_group_id)); ?> >
									<?php print $adm->user_group_name;?>
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
						<label class="form-control-label">Email <span class="tx-danger">*</span></label>
						<input class="form-control" type="text" name="txtEmailLogin" value="<?php echo set_value('txtFullname', $arr_user->user_email); ?>" placeholder="" required>
						<ul class="fields-message">
							<?php echo form_error('txtEmailLogin', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Full Name <span class="tx-danger">*</span></label>
						<input class="form-control" type="text" name="txtFullname" value="<?php echo set_value('txtFullname', $arr_user->user_name); ?>" placeholder="" required>
						<ul class="fields-message">
							<?php echo form_error('txtFullname', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Balance</label>
						<input class="form-control" type="number" name="txtBalance" value="<?php echo set_value('txtBalance', $arr_user->user_balance); ?>" placeholder="">
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
							<option value="<?php print STATUS_ENABLE;?>" <?php echo set_select('cbStatus', STATUS_ENABLE, (STATUS_ENABLE == $arr_user->user_status)); ?>>Enable</option>
							<option value="<?php print STATUS_DISABLE;?>" <?php echo set_select('cbStatus', STATUS_DISABLE, (STATUS_DISABLE == $arr_user->user_status)); ?>>Disable</option>
						</select>
						<ul class="fields-message">
							<?php echo form_error('cbStatus', '<li class="tx-danger-100">', '</li>'); ?>
						</ul>
					</div>
				</div>

				<div class="col-md-12 pd-t-15">
					<div class="form-group">
						<label class="ckbox ckbox-danger">
							<input type="checkbox" value="1" name="cbApiAccessOnly" <?php echo set_checkbox('cbApiAccessOnly', '1', $arr_user->api_access_only == 1); ?>>
							<span>API Access Only</span>
						</label>
						<ul class="fields-message">
							<?php echo form_error('cbApiAccessOnly', '<li class="tx-danger-100">', '</li>'); ?>
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

	<div class="col-md-5 col-sm-12 mg-t-20 mg-md-t-0">
		<div class="card shadow-base bd-0 mg-b-20">
			<?php if (!empty($arr_user->updated) && !empty($arr_user->updated_by) && !empty($arr_user->created) && !empty($arr_user->created_by)): ?>
			<div class="card-body bg-transparent pd-0 bd-gray-200 mg-t-auto">
				<div class="row no-gutters tx-center">
					<?php if (!empty($arr_user->updated) && !empty($arr_user->updated_by)): ?>
					<div class="col pd-y-15">
						<p class="mg-b-5 tx-uppercase tx-12 tx-mont tx-semibold">Last Updated</p>
						<h4 class="tx-16 tx-bold mg-b-0 tx-inverse">
							<?php print strtoupper($arr_user->updated_by);?>
						</h4>
						<span class="tx-12 tx-primary tx-roboto">
							<?php print timestamp_to_date($arr_user->updated);?>
						</span>
					</div>
					<?php endif;?>


					<div class="col pd-y-15 bd-l bd-gray-200">
						<?php if (!empty($arr_user->created) && !empty($arr_user->created_by)): ?>
						<p class="mg-b-5 tx-uppercase tx-12 tx-mont tx-semibold">Created</p>
						<h4 class="tx-16 tx-inverse tx-bold mg-b-0">
							<?php print strtoupper($arr_user->created_by);?>
						</h4>
						<span class="tx-12 tx-primary tx-roboto">
							<?php print timestamp_to_date($arr_user->created);?>
						</span>
					</div>
					<?php endif;?>
				</div>
			</div>
			<?php endif;?>
		</div>

		<?php $this->load->view('layouts/change-log');?>
	</div>
</div>

<script type="text/javascript">
$(document).ready(function() {
	get_action_log();

	$('#form-user-update').on('submit', function(event){
		event.preventDefault();

		var request 	= '<?php print $this->req_controller;?>/<?php print $this->action;?>/<?php print $arr_user->user_id;?>',
			form 		= $(this);

		loading(form, 'show');
		ajax_post(request, form.serialize(), function(result) {
			init_meta(result.meta);
			get_action_log();
			loading(form, 'hide');
		});
	});
});
</script>