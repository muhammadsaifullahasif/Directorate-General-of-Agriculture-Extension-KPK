<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Variety';

	include "head.php";

	if(is_manager() || is_storekeeper()) {
		header('Location: index.php');
	}

	if(isset($_POST['new_variety_form_btn'])) {
		$new_variety_collapse_class = 'show';
	} else {
		$new_variety_collapse_class = '';
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
							<a data-toggle="collapse" href="#new_variety_container" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='variety.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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

					<div class="collapse <?php echo $new_variety_collapse_class; ?>" id="new_variety_container">
						<div class="card card-body">

							<?php

							$new_variety_name = $stock_crop_id = '';
							if(isset($_POST['new_variety_form_btn'])) {
								$new_variety_name = ucwords(validate($_POST['new_variety_name']));
								$stock_crop_id = validate($_POST['stock_crop_id']);

								if( !empty( $new_variety_name ) && !empty( $stock_crop_id ) ) {

									if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stock_variety WHERE variety='$new_variety_name' && stock_crop_id='$stock_crop_id' && delete_status='0'")) == 0) {
										$query = mysqli_query($conn, "INSERT INTO stock_variety(user_id, stock_crop_id, variety, time_created) VALUES('$user_id', '$stock_crop_id', '$new_variety_name', '$time_created')");

										if($query) {
											echo "<div class='alert alert-success' id='new_variety_form_alert'>Stock Variety Successfully Created</div>";
											echo "<script>setTimeout(function(){ $('#new_variety_container').removeClass('show'); $('#new_variety_name').val(''); $('#new_variety_form_alert').remove(); }, 1000)</script>";
										} else {
											echo "<div class='alert alert-danger'>Please try again</div>";
										}
									} else {
										echo "<div class='alert alert-danger'>".$new_variety_name." already exist</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							?>

							<form method="post" id="new_variety_form" class="form">
								
								<div class="mb-3">
									<label>Variety Name:</label>
									<input type="text" pattern="[A-Za-z]+" value="<?= $new_variety_name; ?>" class="form-control" placeholder="Enter Variety Name" id="new_variety_name" name="new_variety_name">
									<div id="new_variety_name_msg"></div>
								</div>
								<div class="mb-3">
									<label>Crop:</label>
									<select class="form-control" id="stock_crop_id" name="stock_crop_id">
										<option value="">Select Crop</option>
										<?php

										$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
										if(mysqli_num_rows($stock_crop_query) > 0) {
											while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
												if($stock_crop_result['id'] == $stock_crop_id) { $stock_crop_selected = 'selected'; } else { $stock_crop_selected = ''; }
												echo "<option ".$stock_crop_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['crop']."</option>";
											}
										}

										?>
									</select>
								</div>
								<button class="btn btn-primary" class="submit" id="new_variety_form_btn" name="new_variety_form_btn">Submit</button>

							</form>
						</div>
					</div>

					<?php

					if(isset($_GET['action']) && $_GET['action'] == 'edit') {

						$edit_variety_id = validate($_GET['id']);

					?>

					<div class="collapse show" id="edit_variety_container">
						<div class="card card-body">

							<?php

							$edit_variety_name = $edit_stock_crop_id = '';
							if(isset($_POST['edit_variety_form_btn'])) {
								$edit_variety_name = ucwords(validate($_POST['edit_variety_name']));
								$edit_stock_crop_id = validate($_POST['stock_crop_id']);

								if( !empty( $edit_variety_name ) && !empty( $edit_stock_crop_id ) ) {

									$query = mysqli_query($conn, "UPDATE stock_variety SET variety='$edit_variety_name', stock_crop_id='$edit_stock_crop_id' WHERE id='$edit_variety_id'");

									if($query) {
										echo "<div class='alert alert-success' id='edit_variety_form_alert'>Variety Successfully Updated</div>";
										echo "<script>history.pushState({}, '', 'variety.php'); setTimeout(function(){ $('#edit_variety_container').removeClass('show'); $('#edit_variety_name').val(''); $('#edit_variety_form_alert').remove(); }, 1000)</script>";
									} else {
										echo "<div class='alert alert-danger'>Please try again</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							$edit_variety_query = mysqli_query($conn, "SELECT * FROM stock_variety WHERE id='$edit_variety_id' && delete_status='0'");
							if(mysqli_num_rows($edit_variety_query) > 0) {
								$edit_variety_result = mysqli_fetch_assoc($edit_variety_query);

							?>

							<form method="post" id="edit_variety_form" class="form">
								
								<div class="mb-3">
									<label>Name:</label>
									<input type="text" pattern="[A-Za-z]+" class="form-control" value="<?= $edit_variety_result['variety']; ?>" placeholder="Enter Name" id="edit_variety_name" name="edit_variety_name">
									<div id="edit_variety_name_msg"></div>
								</div>
								<div class="mb-3">
									<label>Crop:</label>
									<select class="form-control" id="stock_crop_id" name="stock_crop_id">
										<option value="">Select Crop</option>
										<?php

										$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
										if(mysqli_num_rows($stock_crop_query) > 0) {
											while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
												if($stock_crop_result['id'] == $edit_variety_result['stock_crop_id']) { $stock_crop_selected = 'selected'; } else { $stock_crop_selected = ''; }
												echo "<option ".$stock_crop_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['crop']."</option>";
											}
										}

										?>
									</select>
								</div>
								<button class="btn btn-primary" class="submit" id="edit_variety_form_btn" name="edit_variety_form_btn">Submit</button>

								<button class="btn btn-outline-dark edit_variety_container_close" type="button" data-toggle="collapse" href="#edit_variety_container">Cancel</button>

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

							$delete_query = mysqli_query($conn, "UPDATE stock_variety SET delete_status='1' WHERE id='$id'");
							if($delete_query) {
								echo "<div class='alert alert-success notify-alert'>Stock Variety Successfully Deleted</div>";
								echo "<script>history.pushState({}, '', 'variety.php'); setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							} else {
								echo "<div class='alert alert-danger notify-alert'>Please Try Again</div>";
								echo "<script>setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							}
						} else {
							echo "<script>window.top.location='variety.php';</script>";
						}
					}



					if(isset($_GET['query'])) {
						$search_result_msg = 'Search Result';
						if(isset($_GET['filter_type']) && !empty( $_GET['filter_type'] ) ) {
							$search_result_msg .= ' of type <b>'.stock_crop(mysqli_real_escape_string($conn, trim(strip_tags($_GET['filter_type'])))).'</b>';
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
											<div class="col-4">
												<select class="form-control w-100 form-control-sm form-control-border filter_type" name="filter_type">
													<option value="">Stock Type</option>
													<?php

													$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_crop_query) > 0) {
														while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
															if(isset($_GET['filter_type']) && $_GET['filter_type'] == $stock_crop_result['id']) {
																$filter_type_selected = 'selected';
															} else {
																$filter_type_selected = '';
															}
															echo "<option ".$filter_type_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['type']."</option>";
														}
													}

													?>
												</select>
											</div>
											<div class="col">
												<button class="btn btn-outline-dark btn-sm" type="submit">Apply</button>
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
													<th>Name</th>
													<th>Stock Type</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_variety">

												<?php

												if(isset($_GET['query']) && $_GET['query'] == 'filter') {

													$sql_query = "SELECT * FROM stock_variety WHERE delete_status='0'";

													if(isset($_GET['filter_type']) && !empty( $_GET['filter_type'] ) ) {
														$sql_query .= " && stock_crop_id='".validate($_GET['filter_type'])."' ";
													}

													$query = mysqli_query($conn, $sql_query);

												} else {
													$query = mysqli_query($conn, "SELECT * FROM stock_variety WHERE delete_status='0'");
												}

												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
												?>
												<tr>
													<td><?= $i; ?></td>
													<td><?= $result['variety']; ?></td>
													<td><?= stock_crop($result['stock_crop_id']); ?></td>
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
															<a href="variety.php?id=<?= $result['id']; ?>&action=edit" class="btn btn-primary btn-sm">Edit</a>
															<a href='variety.php?id=<?= $result['id']; ?>&action=delete' class='btn btn-danger btn-sm delete_variety_btn'>Delete</a>
														</div>
													</td>
												</tr>
												<?php
														$i++;
													}
												} else {
													echo "<tr><td colspan='5' class='text-center'>No Record Found</td></tr>";
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

			$(document).on('click', '.edit_variety_container_close', function(){
				history.pushState({}, '', 'variety.php');
			});

			$(document).on('click', '.delete_variety_btn', function(){

				if(confirm('Are you sure to delete variety?')) {
					return true;
				} else {
					return false;
				}

			});

			$('#new_variety_name').on('focus', function(){
				$('#new_variety_name').removeClass('is-invalid');
				$('#new_variety_name_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_variety_form').on('submit', function(){
				var new_variety_name = $('#new_variety_name').val();

				if(new_variety_name == '') {
					$('#new_variety_name').addClass('is-invalid');
					$('#new_variety_name_msg').addClass('invalid-feedback').text('Variety Name Required');
					return false;
				} else {
					$('#new_variety_name').removeClass('is-invalid');
					$('#new_variety_name_msg').removeClass('invalid-feedback').text('');
					return true;
				}
			});

			$('#edit_variety_name').on('focus', function(){
				$("#edit_variety_name").removeClass('is-invalid');
				$('#edit_variety_name_msg').removeClass('invalid-feedback').text('');
			});

			$('#edit_variety_form').on('submit', function(){
				var edit_variety_name = $('#edit_variety_name').val();

				if(edit_variety_name == '') {
					$('#edit_variety_name').addClass('is-invalid');
					$('#edit_variety_name_msg').addClass('invalid-feedback').text('Variety Name Required');
					return false;
				} else {
					$('#edit_variety_name').removeClass('is-invalid');
					$('#edit_variety_name_msg').removeClass('invalid-feedback').text('');
					return true;
				}
			});

		});
	</script>
</body>
</html>