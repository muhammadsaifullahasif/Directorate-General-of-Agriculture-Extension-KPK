<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Prices';

	include "head.php";

	if(is_admin() || is_manager() || is_storekeeper()) {
		header('Location: index.php');
	}

	if(isset($_POST['new_price_form_btn'])) {
		$new_price_collapse_class = 'show';
	} else {
		$new_price_collapse_class = '';
	}

	?>
</head>
<!--
`body` tag options:

Apply one or more of the following classes to to the body tag
to get the desired effect

* sidebar-collapse
* sidebar-mini
-->
<body class="hold-transition sidebar-mini layout-fixed layout-footer-fixed text-sm sidebar-collapse">

	<div class="wrapper">
		
		<?php include "nav.php"; ?>
		<?php include "sidebar.php"; ?>

		<!-- Content Wrapper. Contains page content -->
		<div class="content-wrapper">
			
			<!-- Content Header (Page header) -->
			<div class="content-header">
				<div class="container-fluid">
					<div class="row mb-2">
						<div class="col-sm-6">
							<h1 class="m-0 d-inline"><?= $page_title; ?></h1>
							<a data-toggle="collapse" href="#new_price_container" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='prices.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
							}

							?>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item active"><?= $page_title; ?></li>
							</ol>
						</div><!-- /.col -->
					</div><!-- /.row -->
				</div><!-- /.container-fluid -->
			</div>
			<!-- /.content-header -->

			<!-- Main content -->
			<section class="content">
				<div class="container-fluid">

					<div class="collapse <?php echo $new_price_collapse_class; ?>" id="new_price_container">
						<div class="card card-body">

							<?php

							if(isset($_POST['new_price_form_btn'])) {
								$new_stock_crop_id = validate($_POST['new_stock_crop_id']);
								$new_stock_class_id = validate($_POST['new_stock_class_id']);
								$purchase_price = ucwords(validate($_POST['purchase_price']));
								$sale_price = ucwords(validate($_POST['sale_price']));

								if( !empty( $new_stock_crop_id ) && !empty( $new_stock_class_id ) && !empty( $purchase_price ) && !empty( $sale_price ) ) {

									if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stock_price WHERE stock_crop_id='$new_stock_crop_id' && stock_class_id='$new_stock_class_id' && delete_status='0'")) == 0) {
										$query = mysqli_query($conn, "INSERT INTO stock_price(user_id, stock_crop_id, stock_class_id, purchase_price, sale_price, time_created) VALUES('$user_id', '$new_stock_crop_id', '$new_stock_class_id', '$purchase_price', '$sale_price', '$time_created')");

										if($query) {
											echo "<div class='alert alert-success' id='new_price_form_alert'>Price Successfully Added</div>";
											echo "<script>setTimeout(function(){ $('#new_price_container').removeClass('show'); $('#new_price_name').val(''); $('#new_price_form_alert').remove(); }, 1000)</script>";
										} else {
											echo "<div class='alert alert-danger'>Please try again</div>";
										}
									} else {
										echo "<div class='alert alert-danger'>".stock_crop($new_stock_crop_id)." in class ".stock_class($new_stock_class_id)." already exist</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							?>

							<form method="post" id="new_price_form" class="form">

								<div class="mb-3">
									<label>Crop:</label>
									<select class="form-control" id="new_stock_crop_id" name="new_stock_crop_id">
										<option value="">Select Crop</option>
										<?php

										$new_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
										if(mysqli_num_rows($new_crop_query) > 0) {
											while($new_crop_result = mysqli_fetch_assoc($new_crop_query)) {
												?>
												<option value="<?php echo $new_crop_result['id']; ?>"><?php echo $new_crop_result['crop']; ?></option>
												<?php
											}
										}

										?>
									</select>
									<div id="new_crop_id_msg"></div>
								</div>

								<div class="mb-3">
									<label>Stock Class:</label>
									<select class="form-control" id="new_stock_class_id" name="new_stock_class_id">
										<option value="">Select Stock Class</option>
										<?php

										$new_class_query = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
										if(mysqli_num_rows($new_class_query) > 0) {
											while($new_class_result = mysqli_fetch_assoc($new_class_query)) {
												?>
												<option value="<?php echo $new_class_result['id']; ?>"><?php echo $new_class_result['class_name']; ?></option>
												<?php
											}
										}

										?>
									</select>
									<div id="new_class_id_msg"></div>
								</div>
								
								<div class="mb-3">
									<label>Purchase Price:</label>
									<input type="number" pattern="[0-9]+" class="form-control" placeholder="Enter Purchase Price" id="purchase_price" name="purchase_price">
									<small class="form-text text-muted">Enter price of (KGs)</small>
									<div id="purchase_price_msg"></div>
								</div>

								<div class="mb-3">
									<label>Sale Price:</label>
									<input type="number" pattern="[0-9]+" class="form-control" placeholder="Enter Sale Price" id="sale_price" name="sale_price">
									<small class="form-text text-muted">Enter price of (KGs)</small>
									<div id="sale_price_msg"></div>
								</div>
								<button class="btn btn-primary" class="submit" id="new_price_form_btn" name="new_price_form_btn">Submit</button>

							</form>
						</div>
					</div>

					<?php

					if(isset($_GET['action']) && $_GET['action'] == 'edit') {

						$edit_price_id = validate($_GET['id']);

					?>

					<div class="collapse show" id="edit_price_container">
						<div class="card card-body">

							<?php

							if(isset($_POST['edit_price_form_btn'])) {
								$edit_stock_crop_id = validate($_POST['edit_stock_crop_id']);
								$edit_stock_class_id = validate($_POST['edit_stock_class_id']);
								$edit_purchase_price = ucwords(validate($_POST['edit_purchase_price']));
								$edit_sale_price = ucwords(validate($_POST['edit_sale_price']));

								if( !empty( $edit_stock_crop_id ) && !empty( $edit_stock_class_id ) && !empty( $edit_purchase_price ) && !empty( $edit_sale_price ) ) {

									$query = mysqli_query($conn, "UPDATE stock_price SET purchase_price='$edit_purchase_price', sale_price='$edit_sale_price', stock_crop_id='$edit_stock_crop_id', stock_class_id='$edit_stock_class_id' WHERE id='$edit_price_id'");

									if($query) {
										echo "<div class='alert alert-success' id='edit_price_form_alert'>Price Successfully Updated</div>";
										echo "<script>history.pushState({}, '', 'prices.php'); setTimeout(function(){ $('#edit_price_container').removeClass('show'); $('#edit_price_name').val(''); $('#edit_price_form_alert').remove(); }, 1000)</script>";
									} else {
										echo "<div class='alert alert-danger'>Please try again</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							$edit_price_query = mysqli_query($conn, "SELECT * FROM stock_price WHERE id='$edit_price_id' && delete_status='0'");
							if(mysqli_num_rows($edit_price_query) > 0) {
								$edit_price_result = mysqli_fetch_assoc($edit_price_query);

							?>

							<form method="post" id="edit_price_form" class="form">

								<div class="mb-3">
									<label>Crop:</label>
									<select class="form-control" id="edit_stock_crop_id" name="edit_stock_crop_id">
										<option <?php if( empty( $edit_price_result['id'] ) ) { echo 'selected'; } ?> value="">Select Crop</option>
										<?php

										$edit_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
										if(mysqli_num_rows($edit_crop_query) > 0) {
											while($edit_crop_result = mysqli_fetch_assoc($edit_crop_query)) {
												?>
												<option <?php if($edit_price_result['stock_crop_id'] == $edit_crop_result['id']) { echo 'selected'; } ?> value="<?php echo $edit_crop_result['id']; ?>"><?php echo $edit_crop_result['crop']; ?></option>
												<?php
											}
										}

										?>
									</select>
									<div id="edit_stock_crop_id_msg"></div>
								</div>

								<div class="mb-3">
									<label>Stock Class:</label>
									<select class="form-control" id="edit_stock_class_id" name="edit_stock_class_id">
										<option <?php if( empty( $edit_price_result['stock_class_id'] ) ) { echo 'selected'; } ?> value="">Select Class</option>
										<?php

										$edit_class_query = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
										if(mysqli_num_rows($edit_class_query) > 0) {
											while($edit_class_result = mysqli_fetch_assoc($edit_class_query)) {
												?>
												<option <?php if($edit_price_result['stock_class_id'] == $edit_class_result['id']) { echo 'selected'; } ?> value="<?= $edit_class_result['id']; ?>"><?= $edit_class_result['class_name']; ?></option>
												<?php
											}
										}

										?>
									</select>
									<div id="edit_stock_class_id_msg"></div>
								</div>
								
								<div class="mb-3">
									<label>Purchase Price:</label>
									<input type="text" pattern="[0-9]+" class="form-control" value="<?= $edit_price_result['purchase_price']; ?>" placeholder="Enter Purchase Price" id="edit_purchase_price" name="edit_purchase_price">
									<small class="form-text text-muted">Enter price of (KGs)</small>
									<div id="edit_purchase_price_msg"></div>
								</div>

								<div class="mb-3">
									<label>Sale Price:</label>
									<input type="text" pattern="[0-9]+" class="form-control" value="<?= $edit_price_result['sale_price']; ?>" placeholder="Enter Sale Price" id="edit_sale_price" name="edit_sale_price">
									<small class="form-text text-muted">Enter price of (KGs)</small>
									<div id="edit_sale_price_msg"></div>
								</div>
								<button class="btn btn-primary" class="submit" id="edit_price_form_btn" name="edit_price_form_btn">Submit</button>

								<button class="btn btn-outline-dark edit_price_container_close" type="button" data-toggle="collapse" href="#edit_price_container">Cancel</button>

							</form>

							<?php

							}

							?>
						</div>
					</div>

					<?php

					}

					if(isset($_GET['action']) && $_GET['action'] == 'delete') {

						if(isset($_GET['id']) && $_GET['id'] != 0 && !empty( $_GET['id'] ) ) {
							$id = validate($_GET['id']);

							$delete_query = mysqli_query($conn, "UPDATE stock_price SET delete_status='1' WHERE id='$id'");
							if($delete_query) {
								echo "<div class='alert alert-success notify-alert'>Price Successfully Deleted</div>";
								echo "<script>history.pushState({}, '', 'prices.php'); setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							} else {
								echo "<div class='alert alert-danger notify-alert'>Please Try Again</div>";
								echo "<script>setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							}
						} else {
							echo "<script>window.top.location='prices.php';</script>";
						}
					}


					if(isset($_GET['query'])) {
						$search_result_msg = 'Search Result';
						if(isset($_GET['filter_crop']) && $_GET['filter_crop'] != '') {
							$search_result_msg .= ' of crop <b>'.stock_crop(validate($_GET['filter_crop'])).'</b>';
						}
						if(isset($_GET['filter_variety']) && $_GET['filter_variety'] != '') {
							$search_result_msg .= ' of variety <b>'.stock_variety(validate($_GET['filter_variety'])).'</b>';
						}
						if(isset($_GET['filter_class']) && $_GET['filter_class'] != '') {
							$search_result_msg .= ' of class <b>'.stock_class(validate($_GET['filter_class'])).'</b>';
						}
						echo "<div class='mb-3'><p>".$search_result_msg."</p></div>";
					}

					?>
					
					<!-- Account New -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header row align-items-center justify-content-between">
									<div class="col-md-6">
										<form class="form-inline" method="get">
											<input type="hidden" style="display: none;" value="filter" name="query">

											<div class="col-3">
												<select class="form-control w-100 form-control-sm form-control-border filter_crop" name="filter_crop">
													<option value="">Stock Crop</option>
													<?php

													$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_crop_query) > 0) {
														while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
															if(isset($_GET['filter_crop']) && $_GET['filter_crop'] == $stock_crop_result['id']) {
																$filter_crop_selected = 'selected';
															} else {
																$filter_crop_selected = '';
															}
															echo "<option ".$filter_crop_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['crop']."</option>";
														}
													}

													?>
												</select>
											</div>
											<div class="col-3">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_class">
													<option value="">Class</option>
													<?php

													$stock_class_query = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_class_query) > 0) {
														while($stock_class_result = mysqli_fetch_assoc($stock_class_query)) {
															if(isset($_GET['filter_class']) && $_GET['filter_class'] == $stock_class_result['id']) {
																$filter_class_selected = 'selected';
															} else {
																$filter_class_selected = '';
															}
															echo "<option ".$filter_class_selected." value='".$stock_class_result['id']."'>".$stock_class_result['class_name']."</option>";
														}
													}

													?>
												</select>
											</div>
											<div class="col">
												<button class="btn btn-outline-dark btn-sm" type="submit" name="filter_form_btn">Apply</button>
											</div>
										</form>
									</div>
									<div class="col-md-6">
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped table-hover table-sm" id="table">
											<thead>
												<tr>
													<th>#</th>
													<th>Crop</th>
													<th>Class</th>
													<th>Purchase Price (KGs)</th>
													<th>Purchase Price (Muns)</th>
													<th>Purchase Price (Metric Tons)</th>
													<th>Sale Price (KGs)</th>
													<th>Sale Price (Muns)</th>
													<th>Sale Price (Metric Tons)</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_price">

												<?php

												if(isset($_GET['query']) && $_GET['query'] == 'filter') {

													$sql_query = "SELECT * FROM stock_price WHERE delete_status='0'";

													if(isset($_GET['filter_crop']) && !empty( $_GET['filter_crop'] ) ) {
														$sql_query .= " && stock_crop_id='".validate($_GET['filter_crop'])."' ";
													}

													if(isset($_GET['filter_variety']) && !empty( $_GET['filter_variety'] ) ) {
														$sql_query .= " && stock_variety_id='".validate($_GET['filter_variety'])."' ";
													}

													if(isset($_GET['filter_class']) && !empty( $_GET['filter_class'] ) ) {
														$sql_query .= " && stock_class_id='".validate($_GET['filter_class'])."' ";
													}

													$query = mysqli_query($conn, $sql_query);

												} else {
													$query = mysqli_query($conn, "SELECT * FROM stock_price WHERE delete_status='0'");
												}

												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
												?>
												<tr>
													<td><?= $i; ?></td>
													<td><?= stock_crop($result['stock_crop_id']); ?></td>
													<td><?= stock_class($result['stock_class_id']); ?></td>
													<td><?= 'Rs: '.$result['purchase_price']; ?></td>
													<td><?= 'Rs: '.($result['purchase_price'] * 50); ?></td>
													<td><?= 'Rs: '.($result['purchase_price'] * 1000); ?></td>
													<td><?= 'Rs: '.$result['sale_price']; ?></td>
													<td><?= 'Rs: '.($result['sale_price'] * 50); ?></td>
													<td><?= 'Rs: '.($result['sale_price'] * 1000); ?></td>
													<td>
														<?php

														if($result['active_status'] == 1) {
															echo "<span class='badge badge-success'>Active</span>";
														} else {
															echo "<span class='badge badge-danger'>Inactive</span>";
														}

														?>
													</td>
													<td>
														<div class="btn-group">
															<a href="prices.php?id=<?= $result['id']; ?>&action=edit" class="btn btn-primary btn-sm">Edit</a>
															<a href='prices.php?id=<?= $result['id']; ?>&action=delete' class='btn btn-danger btn-sm delete_price_btn'>Delete</a>
														</div>
													</td>
												</tr>
												<?php
														$i++;
													}
												} else {
													echo "<tr><td colspan='6' class='text-center'>No Record Found</td></tr>";
												}

												?>

											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /.Account New -->

				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->

		</div>
		<!-- /.content-wrapper -->

		<!-- Control Sidebar -->
		<?php include "footer.php"; ?>

	</div>

	<?php include "javascript.php"; ?>

	<script class="text/javascript">
		$(document).ready(function(){

			$(document).on('click', '.edit_price_container_close', function(){
				history.pushState({}, '', 'price.php');
			});

			$(document).on('click', '.delete_price_btn', function(){

				if(confirm('Are you sure to delete price?')) {
					return true;
				} else {
					return false;
				}

			});

			$('#new_crop_id').on('focus', function(){
				$('#new_crop_id').removeClass('is-invalid');
				$('#new_crop_id_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_price').on('focus', function(){
				$('#new_price').removeClass('is-invalid');
				$('#new_price_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_price_form').on('submit', function(){
				var new_crop_id = $('#new_crop_id').val();
				var new_price = $('#new_price').val();
				var bool = 0;

				if(new_crop_id == '') {
					$('#new_crop_id').addClass('is-invalid');
					$('#new_crop_id_msg').addClass('invalid-feedback').text('Crop Required');
					bool = 1;
				} else {
					$('#new_crop_id').remove('is-invalid');
					$('#new_crop_id_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if(new_price == '') {
					$('#new_price').addClass('is-invalid');
					$('#new_price_msg').addClass('invalid-feedback').text('Price Required');
					bool = 1;
				} else {
					$('#new_price').removeClass('is-invalid');
					$('#new_price_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if(bool == 0) {
					return true;
				} else {
					return false;
				}
			});

			$('#edit_crop_id').on('focus', function(){
				$('#edit_crop_id').removeClass('is-invalid');
				$('#edit_crop_id_msg').removeClass('invalid-feedback').text('');
			});

			$('#edit_price').on('focus', function(){
				$("#edit_price").removeClass('is-invalid');
				$('#edit_price_msg').removeClass('invalid-feedback').text('');
			});

			$('#edit_price_form').on('submit', function(){
				var edit_crop_id = $('#edit_crop_id').val();
				var edit_price = $('#edit_price').val();
				var bool = 0;

				if(edit_crop_id == '') {
					$('#edit_crop_id').addClass('is-invalid');
					$('#edit_crop_id_msg').addClass('invalid-feedback').text('Crop Required');
					bool = 1;
				} else {
					$('#edit_crop_id').removeClass('is-invalid');
					$('#edit_crop_id_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if(edit_price == '') {
					$('#edit_price').addClass('is-invalid');
					$('#edit_price_msg').addClass('invalid-feedback').text('Price Required');
					bool = 1;
				} else {
					$('#edit_price').removeClass('is-invalid');
					$('#edit_price_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if(bool == 0) {
					return true;
				} else {
					return false;
				}
			});

		});
	</script>
</body>
</html>