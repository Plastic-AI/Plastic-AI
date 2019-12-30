<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="utf-8">
	<meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport">
	<title>PlasticAI - Sign In</title>
	<link href="<?php print base_url('files/images/favicon.png');?>" rel="shortcut icon">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/font-awesome/css/font-awesome.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/Ionicons/css/ionicons.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/perfect-scrollbar/css/perfect-scrollbar.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/jquery-switchbutton/jquery.switchButton.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/lib/SpinKit/spinkit.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/css/bracket.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/css/particle.css');?>">
	<link rel="stylesheet" href="<?php print base_url('assets/css/animate.css');?>">

	<script src="<?php print base_url('assets/lib/jquery/jquery.js');?>"></script>
	<script src="<?php print base_url('assets/lib/jquery-ui/jquery-ui.js');?>"></script>
	<script type="text/javascript">
		var request = {
			base_url 	: '<?php print base_url();?>',
			csrf_name 	: '<?php print $this->security->get_csrf_token_name();?>',
			csrf_hash 	: '<?php print $this->security->get_csrf_hash();?>'
		};
	</script>
</head>
<body>
	
	<div id="particles">
		<div id="webcoderskull" class="single-layout d-flex align-items-center justify-content-center ht-100v">

			<?php echo form_open(base_url('login'), array('id' => 'login', 'class' => 'login-wrapper wd-300 wd-xs-400 pd-25 pd-xs-40 bg-white rounded shadow-base animated bounceIn')); ?>

			<div class="signin-logo tx-center tx-30 tx-bold tx-inverse">
				<!-- <img src="<?php print base_url('files/images/logo.png');?>" width="100"> -->
				<span class="tx-normal">[</span> Plastic<span class="tx-primary">AI</span> <span class="tx-normal">]</span>
			</div>
			<br>
			<p style="text-align: center;">Welcome back! Please login.</p>

			<?php $this->load->view('layouts/message');?>

			<div class="form-group">
				<input class="form-control" type="text" name="login" value="<?php print set_value('login');?>" placeholder="Username">
				<ul class="field-message parsley-errors-list filled">
					<?php if(isset($_POST['login'])) echo form_error('login', '<li class="tx-danger-100">', '</li>'); ?>
				</ul>
			</div>

			<div class="form-group">
				<input class="form-control" type="password" name="passwd" value="<?php print set_value('passwd');?>" placeholder="Password" autocomplete>
				<ul class="field-message parsley-errors-list filled">
					<?php if(isset($_POST['passwd'])) echo form_error('passwd', '<li class="tx-danger-100">', '</li>'); ?>
				</ul>
				<a href="<?php print base_url('forgot_password');?>" class="tx-primary tx-12 d-block mg-t-10">Forgot password?</a>
			</div>

			<div class="mg-b-10 mg-t-30 ht-65 text-center" >
				<div id="captcha-loading" style="display: none">
					<div class="sk-rotating-plane bg-gray-800 mg-y-30"></div>
				</div>
				<div id="captcha-img" ><img src="<?php print $captcha_image;?>" class="img-fluid rounded shadow-base" style="border: 1px solid #9da3b3;"></div>
			</div>

			<div class="mg-y-15">
				<div class="input-group">
					<input class="form-control" type="text" name="captcha" placeholder="Captcha Code">
					<span class="input-group-btn">
						<button id="captcha-refresh" class="btn bd bg-white tx-gray-600 pd-x-8 pd-y-4" type="button">
							<i class="ion-ios-refresh tx-24"></i>
						</button>
					</span>
				</div>
				<ul class="parsley-errors-list filled">
					<?php if(isset($_POST['captcha'])) echo form_error('captcha', '<li class="tx-danger-100">', '</li>'); ?>
				</ul>
			</div>

			<button class="btn btn-primary btn-block" type="submit">Sign In <i class="ion ion-md-log-in"></i></button>
			<a class="btn btn-danger btn-block" href="<?php print $google_login_url;?>">Sign In with Google <i class="fa fa-google-plus"></i></a>

			<div class="mg-t-20 tx-center">Don't have an account? <a href="<?php print base_url('register');?>" class="tx-primary">Sign Up</a></div>

			<?php print form_close();?>

		</div>
	</div>
				  
	<script type="text/javascript">
		$(document).ready(function() {
			var captcha_container = $('#captcha-img'),
				captcha_refresh_btn = $('#captcha-refresh'),
				captcha_loading = $('#captcha-loading');

			var request_get = function(endpoint, cb_success) {
				$.ajax({
			        url: request.base_url + endpoint,
			        type: 'get',
			        dataType: 'json',
			        success: cb_success,
			        error: function() {
			        	location.reload();
			        }
			    });
			};

			var get_captcha_success = function(result) {
				if(result.status != 200) {
					return false;
				}
				var img = $('<img />', {
					class: 'img-fluid rounded shadow-base',
					src: result.data,
					style: 'border: 1px solid #9da3b3;'
				});
				captcha_container.html(img);
				captcha_loading.fadeOut('fast', function(){
					captcha_container.fadeIn('fast');
					captcha_refresh_btn.removeAttr('disabled');
				});
			}

			captcha_refresh_btn.click(function(){
				captcha_refresh_btn.attr('disabled', 'disabled');
				captcha_container.fadeOut('fast', function() {
					captcha_loading.fadeIn('fast');
					request_get('captcha',get_captcha_success);
				});
			});
		});
	</script>
	<script src="<?php print base_url('assets/lib/popper.js/popper.js');?>"></script>
	<script src="<?php print base_url('assets/lib/bootstrap/bootstrap.js');?>"></script>
	<script src="<?php print base_url('assets/lib/perfect-scrollbar/js/perfect-scrollbar.jquery.js');?>"></script>
	<script src="<?php print base_url('assets/lib/moment/moment.js');?>"></script>
	<script src="<?php print base_url('assets/lib/jquery-switchbutton/jquery.switchButton.js');?>"></script>
	<script src="<?php print base_url('assets/lib/peity/jquery.peity.js');?>"></script>
	<script src="<?php print base_url('assets/lib/jquery-validation-1.17.0/dist/jquery.validate.min.js');?>"></script>
	<script src="<?php print base_url('assets/js/bracket.js');?>"></script>
	<script src="<?php print base_url('assets/js/particles.js');?>"></script>
</body>
</html>
