<!DOCTYPE html>
<html lang="en">

<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
		<meta name="description" content="">
		<meta name="author" content="">
		<title>Bubble - Login</title>

		<!-- Bootstrap core CSS -->
		<link href="<?=base_url();?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">

		<!-- Custom fonts for this template -->
		<link href="<?=base_url();?>assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

		<!-- Custom fonts for this template -->
		<link href="<?=base_url();?>assets/plugins/themify/css/themify.css" rel="stylesheet" type="text/css">

		<!-- Angular Tooltip Css -->
		<link href="<?=base_url();?>assets/plugins/angular-tooltip/angular-tooltips.css" rel="stylesheet">

		<!-- Page level plugin CSS -->
		<link href="<?=base_url();?>assets/dist/css/animate.css" rel="stylesheet">

		<!-- Custom styles for this template -->
		<link href="<?=base_url();?>assets/dist/css/glovia.css" rel="stylesheet">
		<link href="<?=base_url();?>assets/dist/css/glovia-responsive.css" rel="stylesheet">

		<!-- Custom styles for Color -->
		<link id="jssDefault" rel="stylesheet" href="<?=base_url();?>assets/dist/css/skins/default.css">
		<link rel="stylesheet" href="<?= base_url(); ?>assets/dist/css/jquery.toast.css" type="text/css">

		<link id="jssDefault" rel="stylesheet" href="<?=base_url();?>assets/dist/css/custom.css">
	</head>

	<body class="blue-skin">

		<div class="container-fluid">
			<div class="row">
				<div class="hidden-xs hidden-sm col-lg-6 col-md-6 gredient-bg">
					<div class="clearfix">
						<div class="logo-title-container text-center">
							<h3 class="cl-white text-upper">Welcome To</h3>
							<img class="img-responsive" src="<?=base_url();?>assets/dist/img/logo.png" alt="Logo Icon" style="height: 115px;">
							 <div class="copy animated fadeIn">
								<p class="cl-white">Bubble Admin</p>
							</div>
						</div> <!-- .logo-title-container -->
					</div>
				</div>

				<div class="col-12 col-sm-12 col-md-6 col-lg-6 login-sidebar animated fadeInRightBig">

					<div class="login-container animated fadeInRightBig">

						<h2 class="text-center text-upper">Login To Bubble Dashboard</h2>

						<form class="form-horizontal" id="loginform" name="loginform" method="POST">
							<div class="form-group">
								<input type="text" class="form-control" id="username" name="username" placeholder="Username">
								<i class="fa fa-user"></i>
							</div>
							<div class="form-group help">
								<input type="password" class="form-control" id="password" name="password" placeholder="Password">
								<i class="fa fa-lock"></i>
							</div>


							<div class="form-group">
								<div class="flexbox align-items-center">
									<button type="submit" class="btn theme-bg">log in</button>
									<!-- https://we.tl/t-HCd6Ohptkj -->
								</div>
							</div>

						</form>
					</div>
					<!-- .login-container -->

				</div> <!-- .login-sidebar -->
			</div> <!-- .row -->
		</div>


		<!-- Bootstrap core JavaScript-->
		<script src="<?=base_url();?>assets/plugins/jquery/jquery.min.js"></script>
		<script src="<?=base_url();?>assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>

		<!-- Core plugin JavaScript-->
		<script src="<?=base_url();?>assets/plugins/jquery-easing/jquery.easing.min.js"></script>

		 <!-- Slick Slider Js -->
		<script src="<?=base_url();?>assets/plugins/slick-slider/slick.js"></script>

		<!-- Slim Scroll -->
		<script src="<?=base_url();?>assets/plugins/slim-scroll/jquery.slimscroll.min.js"></script>

		<script src="<?= base_url(); ?>assets/dist/js/jquery.validate.js"></script>
		<script src="<?= base_url(); ?>assets/dist/js/jquery.validate.min.js"></script>

		<!-- Custom scripts for all pages-->
		<script src="<?=base_url();?>assets/dist/js/glovia.js"></script>
		<script src="<?=base_url();?>assets/dist/js/jQuery.style.switcher.js"></script>
		<script src="<?= base_url(); ?>assets/dist/js/jquery.toast.js"></script>

		<script>

		var base_url = '<?=base_url();?>';

		  	$('.dropdown-toggle').dropdown();

		  	$("form[name='loginform']").validate({

				rules: {
					username: "required",
					password: "required",
				},
				messages: {
					username: "Please enter username",
					password: "Please enter password",
				},

				submitHandler: function (form) {

					 $.ajax({
				        url: base_url+'admin/Login/jadminlogin',
				        data: new FormData($('#loginform')[0]),
				        type: 'POST',
				        contentType : false,
				        processData : false,
				        success: function ( data ) {
				        	if(data == 1)
				        	{
				        		$.toast({
							        heading: 'Success',
							        text: 'Login successfull',
							        icon: 'success',
							        position: 'top-right',
							    });
							    setTimeout(function () {
									window.location.href = base_url+'admin/dashboard';
								}, 3000);
				        	}
				        	else if(data == 2)
				        	{
							    window.location.href = base_url+'admin/two_fa_verify';
				        	}
				        	else
				        	{
				        		$.toast({
									heading: 'Error',
									text: 'Username or password something went wrong',
									icon: 'error',
									position: 'top-right',
							  });
				        	}
				        }
				    });


				}
			});

		</script>

	</body>
</html>