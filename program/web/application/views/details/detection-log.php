<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<div class="row">
	<div class="col-md-7 col-sm-12">

		<?php print form_open(current_url(), array('id' => 'form-news-update', 'class' => 'card shadow-base bd-0'));?>

		<div class="card-body">
			<div class="row">
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Plastic Type </label>
						<input type="text" value="<?php echo strtoupper($arr_data->plastic_type);?>" class="form-control" readonly required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Percentage (%) </label>
						<input type="text" value="<?php echo $arr_data->plastic_percentage;?>" class="form-control" readonly required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Execute Data </label>
						<input type="text" value="<?php echo timestamp_to_date($arr_data->created);?>" class="form-control" readonly required>
					</div>
				</div>
				<div class="col-md-6">
					<div class="form-group">
						<label class="form-control-label">Time Used (second) </label>
						<input type="text" value="<?php echo $arr_data->time_used;?>" class="form-control" readonly required>
					</div>
				</div>
				<div class="col-md-12">
					<div class="form-group">
						<label class="form-control-label">PlasticAI Brain JSON Response </label>
						<textarea type="text" class="form-control" rows="45" readonly required><?php echo json_encode(json_decode($arr_data->request_data), JSON_PRETTY_PRINT);?></textarea>
					</div>
				</div>
			</div>
		</div>

		<?php print form_close();?>

	</div>
	<div class="col-md-5 col-sm-12 mg-t-20 mg-md-t-0">
		<div class="card shadow-base bd-0">
			<div class="card-header bd-gray-200 mg-t-auto">
				<span>Image Response</span>
			</div>
			<div class="card-body pd-20">
				<div class="tx-center">
					<img src="<?php print base_url($arr_data->plastic_file);?>" width="100%" />
				</div>
			</div>
		</div>

		<br>
		<div class="card shadow-base bd-0">
			<div class="card-header bd-gray-200 mg-t-auto">
				<span>Detection Flow</span>
			</div>
			<div class="card-body pd-20">
				<p>1. Memuat gambar dan mengubah ukuranya menjadi 32 x 32 pixel</p>
				<p>2. Skala nilai piksel ke [0, 1], dan kemudian kami akan meratakan gambar jika perlu</p>
				<p>3. Load model dan binarizer input gambar kedalam bentuk vector 3 dimensional dengan shape [1, 3072], angka 3027 sendiri didpaatkan dari 32x32x3=3072</p>
				<p>4. Melakukan prediksi dan mencari presentasi paling tinggi untuk menentukan gambar dapat dilabeli sebagai tipe plastik yang mana.</p>
			</div>
		</div>
	</div>

</div>