<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-md-7 col-sm-12">

		<?php print form_open(current_url(), array('id' => 'form-news-update', 'class' => 'card shadow-base bd-0'));?>

		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Suspect Name </label>
						<input type="text" value="<?php echo $arr_suspect->suspect_name;?>" class="form-control" readonly required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
                        <label class="form-control-label">Status </label>
                        <?php if($arr_suspect->suspect_status == STATUS_NOT_WANTED):?>
                            <input type="text" value="Not Wanted" class="form-control" readonly required>
                        <?php elseif($arr_suspect->suspect_status == STATUS_WANTED):?>
                            <input type="text" value="Wanted" class="form-control" readonly required>
                        <?php endif;?>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="form-control-label">Detail</label>
						<textarea type="text" name="txtDetail" class="form-control" required readonly><?php echo $arr_suspect->suspect_detail;?></textarea>
						<ul class="fields-message"></ul>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="form-control-label">Suspect Face Token </label>
						<input type="text" value="<?php echo $arr_suspect->suspect_face_token;?>" class="form-control" readonly required>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="form-control-label">JSON Response </label>
						<textarea type="text" class="form-control" rows="25" readonly required><?php echo json_encode(json_decode($json_detail), JSON_PRETTY_PRINT);?></textarea>
					</div>
				</div>
			</div>
		</div>

		<?php print form_close();?>

	</div>
	<div class="col-md-5 col-sm-12 mg-t-20 mg-md-t-0">
		<div class="card shadow-base bd-0">
			<div class="card-header bd-gray-200 mg-t-auto">
				<span>Face Detection</span>
			</div>
			<div class="card-body pd-20">
				<div class="tx-center">
					<?php
						$json = json_decode(json_encode(json_decode($json_detail), JSON_PRETTY_PRINT));
						print '<img src="'.FACEAI_URL.'files/images/detect/' . $json->request_id . '/' . $json->request_key . '.jpg'.'" width="100%"/>';
					?>
				</div>
			</div>
		</div>

		<br>
		<div class="card shadow-base bd-0">
			<div class="card-header bd-gray-200 mg-t-auto">
				<span>Face Found</span>
			</div>
			<div class="card-body pd-20">
				<?php
                    print '<img src="'.FACEAI_URL.$arr_suspect->suspect_face_cloud.'" width="120"/>';
				?>
			</div>
		</div>
	</div>

</div>