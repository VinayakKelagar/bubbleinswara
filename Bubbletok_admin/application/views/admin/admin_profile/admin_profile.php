<?php
 	$adminid = $this->session->userdata('admin_id');
	$profile = $this->db->query("SELECT * from tbl_admin WHERE admin_id = '".$adminid."'")->row_array();
?>

		<div class="content-wrapper">
	<div class="container-fluid">

	<!-- Title & Breadcrumbs-->
	<div class="row page-titles">
		<div class="col-md-12 align-self-center">
			<h4 class="theme-cl">Profile Page</h4>
		</div>
	</div>
	<!-- Title & Breadcrumbs-->

	<!-- row -->
	<div class="refreshdiv">
		<div class="loading"></div>
	<div class="row">

		<div style="display: none;">
			<input type="file" name="file" id="file" onchange="uploadadminprofile();" />
		</div>
		<div class="col-md-4">

		  <div class="card">
				<div class="card-img-overlap">
					<a href="JavaScript:Void(0);"  onclick="selectfileadmin();"  class="user-icon user-card-phone"><i class="ti-check-box"></i></a>

					<img class="card-img-top profileimg"  src="<?=base_url()?>uploads/<?=$profile['admin_profile']?>" alt="Card image cap" />

				</div>
				<div class="card-block padd-0 translateY-50 text-center">
					<div class="card-avatar style-2">

						<img src="<?=base_url()?>uploads/<?=$profile['admin_profile']?>" style="height: 100%; width: 150px;" class="img-circle img-responsive" alt="" />

					</div>
					<h5 class="font-normal mrg-bot-0 font-18 card-title"><?=$profile['admin_name']?></h5>
					<p class="card-small-text"><?=$profile['admin_email']?></p>

				</div>
			</div>
		</div>
		<!-- /.col-md-4 -->

		<div class="col-md-8">
		  <div class="nav-tabs-custom bg-white">
			<ul class="nav nav-tabs">
			  <li class="active"><a href="#activity" data-toggle="tab">Profile</a></li>
			</ul>
			<div class="tab-content">

			  <div class="active tab-pane" id="activity">
				<form class="form-horizontal" method="POST" enctype="multipart/form-data" name="adminprofile" id="adminprofile">

				<input type="hidden" name="admin_id" id="admin_id" value="<?=$profile['admin_id']?>">

				  <div class="form-group">
					<label for="inputName" class="col-sm-2 control-label">Name</label>

					<div class="col-sm-10">
					  <input type="text" class="form-control" placeholder="Name" id="admin_name" name="admin_name" value="<?=$profile['admin_name']?>">
					</div>
				  </div>

				  <div class="form-group">
					<label for="inputEmail" class="col-sm-2 control-label">Email</label>

					<div class="col-sm-10">
					  <input type="text" class="form-control" id="admin_email" name="admin_email" placeholder="Email" value="<?=$profile['admin_email']?>">
					</div>
				  </div>

				  <div class="form-group">
					<label for="inputName" class="col-sm-2 control-label">Password</label>

					<div class="col-sm-10">
					  <input type="password" class="form-control" name="admin_password" id="admin_password" placeholder="Password" value="<?=$profile['admin_password']?>">
					  <a href="#" class="pass-view fa fa-eye"></a>
					</div>
				  </div>

				  <div class="form-group">
					<div class="col-sm-offset-2 col-sm-10">
					  <button type="submit" class="btn btn-danger">Submit</button>
					</div>
				  </div>

				</form>
			  </div>
			  <!-- /.tab-pane -->

			</div>
			<!-- /.tab-content -->
		  </div>
		  <!-- /.nav-tabs-custom -->
		</div>
		<!-- /.col-md-8 -->
	</div>
	<!-- /row -->
</div>
</div>