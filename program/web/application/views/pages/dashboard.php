<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
   <div class="col-md-12">
		<p class="card card-body bg-primary tx-white bd-0">
			<b>WEBSITE INFO</b>
			Current Accuracy: <?php print ALGO_ACCURACCY;?> - Total Label (Output Node): 7 - Total Dataset Real Used: <?php print ALGO_DATASET_REAL;?> - Total Generated Dataset Used: <?php print ALGO_DATASET_GENERATE;?> - Algorithm: Backpropagation
		</p>
     	<div id="screen" class="row row-sm">
     	</div>
   </div>
</div>

<div id="chart"></div>

<div align="center"><br></div>
<div class="card bd-0 shadow-base pd-15">
	<div class="bd pd-15 mg-b-15 rounded">
		Latest Detection
	</div>

	<?php if (!empty($arr_data)): ?>

		<div class="bd bd-gray-300 rounded table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th class="align-middle">Preview</th>
						<th class="align-middle">Plastic Type</th>
						<th class="align-middle">Percentage (%)</th>
						<th class="align-middle">Time Used (second)</th>
						<th class="align-middle">Date</th>
						<th class="text-center align-middle">Status</th>
					</tr>
				</thead>
				<tbody>

					<?php foreach ($arr_data as $value): ?>
					<tr>
						<td class="align-middle">
							<a target="_blank" href="<?php print base_url($value->plastic_file);?>"><img src="<?php print base_url($value->plastic_file);?>" width="60"/></a>
						</td>
						<td class="align-middle">
							<?php print strtoupper($value->plastic_type);?>
						</td>
						<td class="align-middle">
							<?php print $value->plastic_percentage;?>
						</td>
						<td class="align-middle">
							<?php print $value->time_used;?>
						</td>
						<td class="align-middle">
							<?php print timestamp_to_date($value->created);?>
						</td>
						<td class="text-center align-middle">
							<?php if ($value->plastic_status == STATUS_ENABLE): ?>
								<span class="badge badge-success">Enable</span>
							<?php elseif ($value->plastic_status == STATUS_DISABLE): ?>
								<span class="badge badge-danger">Disable</span>
							<?php endif;?>
						</td>
					</tr>
					<?php endforeach;?>

				</tbody>
			</table>
		</div>

	<?php else: ?>
	<div class="mg-t-30">
		<h3 class="text-center">No record found</h3>
	</div>

	<?php endif;?>

</div>

<script>
   // Banner status auto update
   $(document).ready(function(){
      setInterval(function(){
         $("#screen").load('<?php print base_url('dashboard/index/status');?>')
      }, 2000);
   });
</script>