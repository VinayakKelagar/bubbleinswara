<div class="loading"></div>

<div class="content-wrapper">
	<div class="container-fluid">

	<!-- Title & Breadcrumbs-->
	<div class="row page-titles">
		<div class="col-md-12 align-self-center">
			<h4 class="theme-cl">Edit User</h4>
		</div>
	</div>
	<!-- Title & Breadcrumbs-->
	<!-- .row -->
	<div class="row">
		<!-- .col-md-6 -->
		<div class="col-md-2 col-lg-2 col-sm-12">
		</div>
		<div class="col-md-8 col-lg-8 col-sm-12">

			<div id="mspref">
			<div id="msg" style="display: none;" class="alert alert-danger"></div>
			<div class="card">

				<div class="card-body">

					<form name="edit_user" id="edit_user" method="POST"  enctype="multipart/form-data">

						<input type="hidden" name="user_id" id="user_id" value="<?=$user_data['user_id'];?>">

						<div class="form-group">
							<div class="row">
							  <label for="" class="col-3 col-form-label">Full Name</label>
							  <div class="col-9">
								<input class="form-control" type="text" value="<?=$user_data['full_name'];?>" id="full_name" name="full_name">
							  </div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
							  <label for="" class="col-3 col-form-label">User Name</label>
							  <div class="col-9">
								<input class="form-control" type="text" value="<?=$user_data['user_name'];?>" id="user_name" name="user_name">
							  </div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
							  <label for="" class="col-3 col-form-label">Email</label>
							  <div class="col-9">
								<input class="form-control" type="text" value="<?=$user_data['user_email'];?>" id="user_email" name="user_email">
							  </div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
							  <label for="" class="col-3 col-form-label">Fb Url</label>
							  <div class="col-9">
								<input class="form-control" type="text" value="<?=$user_data['fb_url'];?>" id="fb_url" name="fb_url">
							  </div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
							  <label for="" class="col-3 col-form-label">Insta Url</label>
							  <div class="col-9">
								<input class="form-control" type="text" value="<?=$user_data['insta_url'];?>" id="insta_url" name="insta_url">
							  </div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
							  <label for="" class="col-3 col-form-label">Youtube Url</label>
							  <div class="col-9">
								<input class="form-control" type="text" value="<?=$user_data['youtube_url'];?>" id="youtube_url" name="youtube_url">
							  </div>
							</div>
						</div>

						<div class="form-group">				
							<div class="row">
								<div class="col-md-12">
							<button type="submit" class="btn btn-primary mr-3">Submit</button>
							<a href="" class="btn btn-danger">Cancel</a>
							</div>
							</div>
						</div>

					</form>

				</div>
			</div>
		</div>
		</div>
		<div class="col-md-2 col-lg-2 col-sm-12">
		</div>
		<!-- /.col-md-6 -->
	</div>
	<!-- /.row -->
	<!-- /.row --></div>