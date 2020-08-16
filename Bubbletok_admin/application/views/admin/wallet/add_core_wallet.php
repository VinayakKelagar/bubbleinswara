<div class="loading"></div>

<div class="content-wrapper">
	<div class="container-fluid">

	<!-- Title & Breadcrumbs-->
	<div class="row page-titles">
		<div class="col-md-12 align-self-center">
			<h4 class="theme-cl">Send Core Wallet Amount</h4>
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
			<div class="card">

				<div class="card-body">

					<form id="send_coin" name="send_coin" method="POST"  enctype="multipart/form-data">

						<input type="hidden" name="btc_address" id="btc_address" value="<?=BTC_ADDRESS;?>">
						<input type="hidden" name="lqx_address" id="lqx_address" value="<?=LQX_ADDRESS;?>">

						<div class="form-group">
							<div class="row">
							  <label for="" class="col-3 col-form-label">Select Coin</label>
							  <div class="col-9">
								<select onchange="findaddress(this);" class="form-control" id="coin" name="coin">
									<option value="lqx">LQX</option>
									<option value="btc">BTC</option>
								</select>
							  </div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
							  <label for="" class="col-3 col-form-label">From Address</label>
							  <div class="col-9">
								<input class="form-control" readonly type="text" value="<?=LQX_ADDRESS;?>" id="from_address" name="from_address" placeholder="">
							  </div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
							  <label for="" class="col-3 col-form-label">To Address</label>
							  <div class="col-9">
								<input class="form-control" type="text" placeholder="Enter to address" value="" id="two_address" name="two_address">
							  </div>
							</div>
						</div>

						<div class="form-group">
							<div class="row">
							  <label for="" class="col-3 col-form-label">Amount</label>
							  <div class="col-9">
								<input class="form-control" type="text" value="" placeholder="Enter amount" id="amount" name="amount">
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

		<div class="row">
			<div class="col-md-12">
				<div class="card">

					<div class="card-body">
						<div class="table-responsive">
							<table id="core-wallet-table" class="table table-bordered" style="width: 100%">
								<thead class="thead-inverse">
									<tr>
										<th>From Address</th>
										<th>To Address</th>
										<th>Coin</th>
										<th>Amount</th>
										<th>Transaction Type</th>
										<th>Hash</th>
										<th>Transaction Status</th>
										<th>Created Date</th>
									</tr>
								</thead>
								<tbody>

								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	<!-- /.row -->
	<!-- /.row --></div>