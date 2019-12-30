<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="card bd-0 shadow-base pd-15">
	<div class="bg-gray-300 bd pd-15 mg-b-15 rounded">
		<form method="GET" action="<?php print base_url($this->controller . '/index');?>">
			<div class="row row-sm">
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Login</label>
						<input name="txtLogin" value="<?php print $this->input->get('txtLogin');?>" class="form-control" type="text" placeholder="Login">
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Name</label>
						<input name="txtName" value="<?php print $this->input->get('txtName');?>" class="form-control" type="text" placeholder="Name">
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Status</label>
						<select name="cbStatus" class="form-control select2" data-placeholder="-- All --" data-allow-clear="true">
							<option value="">All</option>
							<option value="<?php print STATUS_ENABLE;?>" <?php echo set_select('cbStatus', STATUS_ENABLE, (STATUS_ENABLE == $this->input->get('cbStatus'))); ?>>Enabled</option>
							<option value="<?php print STATUS_DISABLE;?>" <?php echo set_select('cbStatus', STATUS_DISABLE, (STATUS_DISABLE == $this->input->get('cbStatus'))); ?>>Disabled</option>
						</select>
					</div>
				</div>
				<div class="col-lg-2">
					<button type="submit" class="btn btn-block btn-primary mg-t-30">
						<i class="fa fa-search mg-r-10"></i> Search
					</button>
				</div>
			</div>
		</form>
	</div>
	<div class="card-block mg-b-15">
		<?php if (isset($this->role->{$this->controller}->add)): ?>

			<a href="<?php print base_url($this->controller . '/add');?>" class="btn btn-primary rounded ">
				<div>
					<i class="ion ion-md-add-circle-outline"></i> ADD
				</div>
			</a>

		<?php endif;?>

	</div>

	<?php if (!empty($arr_users)): ?>

		<div class="bd bd-gray-300 rounded table-responsive">

			<table class="table">
				<thead>
					<tr class="col-align-middle">
						<th>Updated<br>Created</th>
						<th>Name</th>
						<th>Login</th>
						<th>Email</th>
						<th>Group</th>
						<th>Balance</th>
						<th class="text-center">Status</th>
						<th>Last Login<br>IP</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($arr_users as $value): ?>

					<tr class="col-align-middle">
						<td class="min-w">
							<?php if ($value->created != $value->updated): ?>

								<strong><?php print strtoupper($value->updated_by);?></strong>
								<br>
								<?php print timestamp_to_date($value->updated);?>
								<hr>

							<?php endif;?>

							<strong><?php print strtoupper($value->created_by);?></strong>
							<br>
							<?php print timestamp_to_date($value->created);?>

						</td>
						<td>
							<?php print $value->user_name;?>
						</td>
						<td>
							<?php print $value->user_login;?>
						</td>
						<td>
							<?php print $value->user_email;?>
						</td>
						<td>
							<?php print $value->user_group_name;?>
						</td>
						<td>
							<?php print $value->user_balance ? $value->user_balance : '0';?> USD
						</td>
						<td class="text-center">
							<?php if ($value->user_status == STATUS_ENABLE): ?>
								<span class="badge badge-success">Enable</span>
							<?php elseif ($value->user_status == STATUS_DISABLE): ?>
								<span class="badge badge-info">Disable</span>
							<?php elseif ($value->user_status == STATUS_TERMINATE): ?>
								<span class="badge badge-danger">Terminated</span>
							<?php endif;?>
						</td>
						<td>
							<?php print timestamp_to_date($value->user_last_login);?>
							<br>
							<?php print $value->user_last_ip;?>
						</td>
						<td class="min-w text-center align-middle">
							<div class="d-xs-flex align-items-center justify-content-start">
								<div class="d-flex align-items-center mg-xs-t-0 btn-action-table">

									<?php if ($value->user_id !== $this->user->user_id && $value->user_status != STATUS_TERMINATE): ?>
									<?php if (isset($this->role->{$this->controller}->update)): ?>
									<a href="<?php print base_url($this->controller . '/update/' . $value->user_id);?>" class="btn btn-outline-primary btn-icon rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Update">
										<div class="tx-20"><i class="ion ion-md-create"></i></div>
									</a>
									<?php endif;?>
									<?php if (isset($this->role->{$this->controller}->change_password)): ?>
									<a href="<?php print base_url($this->controller . '/change_password/' . $value->user_id);?>" class="btn btn-outline-warning btn-icon rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Change Password">
										<div class="tx-20"><i class="ion ion-md-unlock"></i></div>
									</a>
									<?php endif;?>
									<?php if (isset($this->role->{$this->controller}->authenticate)): ?>
									<a href="<?php print base_url($this->controller . '/authenticate/' . $value->user_id);?>" class="btn btn-outline-success btn-icon rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Reset Authenticate">
										<div class="tx-20"><i class="icon ion-md-shuffle"></i></div>
									</a>
									<?php endif;?>
									<?php if (isset($this->role->{$this->controller}->terminate)): ?>
									<a href="javascript:;" onclick="terminateConfirm('<?php print base_url($this->controller . '/terminate/' . $value->user_id);?>');" class="btn btn-outline-danger btn-icon rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Terminate">
										<div class="tx-20"><i class="ion ion-md-trash"></i></div>
									</a>
									<?php endif;?>
									<?php endif;?>

								</div>
							</div>
						</td>
					</tr>

				<?php endforeach;?>
				</tbody>
			</table>
		</div>

		<?php if (!empty($this->pagination->create_links())): ?>

			<div class="ht-80 d-flex align-items-center justify-content-center">
				<nav aria-label="Page navigation">
					<?php print $this->pagination->create_links();?>
				</nav>
			</div>

		<?php endif;?>

	<?php else: ?>
		<div class="mg-t-30">
			<h3 class="text-center">No record found</h3>
		</div>
	<?php endif;?>
</div>

<script>
	function terminateConfirm(link){
    	Swal({
			title: 'Are you sure?',
			text: "You will not be able to return this!",
			type: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yes, terminate this!',
			cancelButtonText: 'Cancel',
		}).then((result) => {
  			if (result.value) {
    			location.href = link;
  			}
		})
	}
</script>