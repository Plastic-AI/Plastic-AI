<?php defined('BASEPATH') OR exit('No direct script access allowed');?>

<div class="card bd-0 shadow-base pd-15">
	<div class="bg-gray-300 bd pd-15 mg-b-15 rounded">
		<form method="GET" action="<?php print base_url('user_group/index');?>">
			<div class="row row-sm">
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Group Name</label>
						<input type="text" name="txtName" class="form-control" value="<?php print !empty($this->input->get('txtName')) ? $this->input->get('txtName') : '';?>" placeholder="Group Name">
					</div>
				</div>
				<div class="col-lg-2">
					<div class="form-group">
						<label class="form-control-label">Status</label>
						<select name="cbStatus" class="form-control select2" data-placeholder="All">
							<option value="" <?php echo set_select('cbStatus', '', empty($this->input->get('cbStatus'))); ?> >All</option>
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
			<a href="<?php print base_url('user_group/add');?>" class="btn btn-primary">
				<i class="ion ion-md-add-circle-outline"></i> ADD
			</a>
		<?php endif;?>

	</div>

	<?php if (!empty($arr_user_group)): ?>

		<div class="bd bd-gray-300 rounded table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th class="min-w align-middle">Created<br>Updated</th>
						<th class="align-middle">Name</th>
						<th class="text-center align-middle">Is Default Group</th>
						<th class="text-center align-middle">Status</th>
						<th class="text-center align-middle"></th>
					</tr>
				</thead>
				<tbody>

					<?php foreach ($arr_user_group as $value): ?>
					<tr>
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
						<td class="align-middle">
							<strong><?php print strtoupper($value->user_group_name);?></strong>
						</td>
						<td class="align-middle text-center">
							<?php if ($value->user_group_default == IS_DEFAULT_GROUP_ID): ?>
								<span class="badge badge-success">YES</span>
							<?php elseif ($value->user_group_default == NOT_DEFAULT_GROUP_ID): ?>
								<span class="badge badge-danger">NO</span>
							<?php endif;?>
						</td>
						<td class="align-middle text-center">
							<?php if ($value->user_group_status == STATUS_ENABLE): ?>
								<span class="badge badge-success">Enable</span>
							<?php elseif ($value->user_group_status == STATUS_DISABLE): ?>
								<span class="badge badge-info">Disable</span>
							<?php elseif ($value->user_group_status == STATUS_TERMINATE): ?>
								<span class="badge badge-danger">Terminated</span>
							<?php endif;?>
						</td>
						<td class="min-w text-center align-middle">
							<?php if (isset($this->role->{$this->controller}->update) && $value->user_group_name != 'useristrator'): ?>

								<a href="<?php print base_url($this->controller . '/update/' . $value->user_group_id);?>" class="btn btn-outline-primary btn-icon rounded-circle" data-toggle="tooltip" data-placement="bottom" title="Update">
									<div class="tx-20"><i class="icon ion-md-create"></i></div>
								</a>

							<?php endif;?>
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