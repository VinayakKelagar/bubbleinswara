<div class="content-wrapper">
	<div class="container-fluid">

		<!-- Title & Breadcrumbs-->
		<div class="row page-titles">
			<div class="col-md-12 align-self-center">
				<h4 class="theme-cl">User Details</h4>
			</div>
		</div>
		<!-- Title & Breadcrumbs-->
		<div class="row">

			<div class="col-md-6">
					  <div class="card">
							<div class="card-img-overlap">
								<a href="#" class="user-icon user-card-mail"><i class="ti-email"></i></a>
								<a href="#" class="user-icon user-card-phone"><i class="ti-mobile"></i></a>
								<?php
										if(!empty($user_data['user_profile']))
										{
											?>
											<img class="card-img-top" style="height: 350px" src="<?=base_url();?>uploads/<?=$user_data['user_profile'];?>" alt="Card image cap">
											<?php
										}
										else
										{
											?>
											<img class="card-img-top" style="height: 350px" src="<?=base_url();?>assets/dist/img/logo.png" alt="Card image cap">
											<?php
										}
									?>

								
							</div>
							<div class="card-block padd-0 translateY-50 text-center">
								<div class="card-avatar style-2">
									<?php
										if(!empty($user_data['user_profile']))
										{
											?>
											<img src="<?=base_url();?>uploads/<?=$user_data['user_profile'];?>" class="img-circle img-responsive" alt="">
											<?php
										}
										else
										{
											?>
											<img src="<?=base_url();?>assets/dist/img/logo.png" class="img-circle img-responsive" alt="">
											<?php
										}
									?>
									
								</div>
								<h5 class="font-normal mrg-bot-0 font-18 card-title"><?=$user_data['full_name'];?></h5>
								<h6 class="font-normal mrg-bot-0 font-15 card-title"><?=$user_data['user_name'];?></h6>
								<p class="card-small-text"><?=$user_data['user_email'];?></p>
							</div>
							<div class="bottom">
								<ul class="social-detail">
									<li><a target="_blank" href="<?=$user_data['fb_url'];?>" class="fa fa-facebook"></a></li>
									<li><a target="_blank" href="<?=$user_data['insta_url'];?>" class="fa fa-instagram"></a></li>
									<li><a target="_blank" href="<?=$user_data['youtube_url'];?>" class="fa fa-youtube"></a></li>
								</ul>
							</div>
						</div>

					  <!-- About Me Box -->
					  <div class="card">
						<div class="card-header">
						  <h4 class="box-title">About Me</h4>
						</div>
						<!-- /.box-header -->
						<div class="card-body">
						  <strong><i class="fa fa-book margin-r-5"></i> Bio</strong>

						  <p class="text-muted">
							<?=$user_data['bio'];?>
						  </p>

						</div>
						<!-- /.card-body -->
					  </div>
					  <!-- /.card -->
					</div>

			<!-- /.col-md-4 -->

			<!-- /.col-md-8 -->
		</div>
		<!-- row -->
		
	</div>