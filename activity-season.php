<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Activity Season';

	include "head.php";

	if(is_manager() || is_storekeeper()) {
		header('Location: index.php');
	}

	if(isset($_POST['new_activity_season_form_btn'])) {
		$new_activity_season_collapse_class = 'show';
	} else {
		$new_activity_season_collapse_class = '';
	}

	?>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
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
							<a data-toggle="collapse" href="#new_activity_season_container" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='activity-season.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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

					<div class="collapse <?php echo $new_activity_season_collapse_class; ?>" id="new_activity_season_container">
						<div class="card card-body">

							<?php

							$new_activity_season_title = $new_activity_season_stock_crop = $new_activity_season_year = $new_activity_season_start_date = $new_activity_season_end_date = '';
							if(isset($_POST['new_activity_season_form_btn'])) {
								$new_activity_season_title = validate($_POST['new_activity_season_title']);
								$new_activity_season_stock_crop = validate($_POST['new_activity_season_stock_crop']);
								$new_activity_season_year = validate($_POST['new_activity_season_year']);
								$new_activity_season_start_date = validate($_POST['new_activity_season_start_date']);
								$new_activity_season_end_date = validate($_POST['new_activity_season_end_date']);

								if( !empty( $new_activity_season_title ) && !empty( $new_activity_season_stock_crop ) && !empty( $new_activity_season_year ) && !empty( $new_activity_season_start_date ) && !empty( $new_activity_season_end_date ) ) {
									$query = mysqli_query($conn, "INSERT INTO stock_activity_season(stock_crop_id, season_title, season_year, season_start_date, season_end_date, season_status, time_created) VALUES('$new_activity_season_stock_crop', '$new_activity_season_title', '$new_activity_season_year', '$new_activity_season_start_date', '$new_activity_season_end_date', '1', '$time_created')");
									$activity_season_id = mysqli_insert_id($conn);

									if($query) {
										$deactive_activity_season = mysqli_query($conn, "UPDATE stock_activity_season SET season_status='0' WHERE stock_crop_id='$new_activity_season_stock_crop' && id!='$activity_season_id'");
										echo "<div class='alert alert-success' id='new_activity_season_form_alert'>Activity season Successfully Created</div>";
										echo "<script>setTimeout(function(){ $('#new_activity_season_container').removeClass('show'); $('#new_activity_season_title, #new_activity_season_stock_crop, #new_activity_season_year, #new_activity_season_start_date, #new_activity_season_end_date').val(''); $('#new_activity_season_form_alert').remove(); }, 1000)</script>";
									} else {
										echo "<div class='alert alert-danger'>Please try again</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							?>

							<form method="post" id="new_activity_season_form" class="form">

								<div class="row">
									<div class="col-md-6 mb-3">
										<label>Season Title:</label>
										<input type="text" pattern="[A-Za-z]+" value="<?= $new_activity_season_title; ?>" class="form-control form-control-border" placeholder="Enter season Title" id="new_activity_season_title" name="new_activity_season_title">
										<div id="new_activity_season_title_msg"></div>
									</div>
									<div class="col-md-6 mb-3">
										<label>Stock Crop:</label>
										<select class="form-control form-control-border" id="new_activity_season_stock_crop" name="new_activity_season_stock_crop">
											<option value="">Select Stock Crop</option>
											<?php

											$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
											if(mysqli_num_rows($stock_crop_query) > 0) {
												while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
													if($stock_crop_result['id'] == $new_stock_crop) { $stock_crop_selected = 'selected'; } else { $stock_crop_selected = ''; }
													echo "<option ".$stock_crop_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['crop']."</option>";
												}
											}

											?>
										</select>
										<div id="new_stock_crop_msg"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4 mb-3">
										<label>Season Year:</label>
										<select class="form-control form-control-border" id="new_activity_season_year" name="new_activity_season_year">
											<option value="">Select Season Year</option>
											<?php

											for($i = 1970 ; $i < date('Y') + 5; $i++) {
												if($i == date('Y')) {
													$activity_season_year_selected = 'selected';
												} else if($i == $new_activity_season_year) {
													$activity_season_year_selected = 'selected';
												} else {
													$activity_season_year_selected = '';
												}
												echo "<option ".$activity_season_year_selected." value='".$i."'>".$i."</option>";
											}

											?>
										</select>
									</div>
									<div class="col-md-4 mb-3">
										<label>Season Start Date:</label>
										<input type="date" value="<?php if($new_activity_season_start_date != '') { echo date('Y-m-d', strtotime($new_activity_season_start_date)); } else if($new_activity_season_start_date == '') { echo date('Y-m-d'); } else { echo date('Y-m-d'); } ?>" class="form-control form-control-border" placeholder="Enter season Start Date" id="new_activity_season_start_date" name="new_activity_season_start_date">
										<div id="new_activity_season_start_date_msg"></div>
									</div>
									<div class="col-md-4 mb-3">
										<label>Season End Date:</label>
										<input type="date" value="<?php if($new_activity_season_start_date != '') { echo date('Y-m-d', strtotime($new_activity_season_start_date)); } else if($new_activity_season_start_date == '') { echo date('Y-m-d', strtotime('-1 day', strtotime('+1 year'))); } else { echo date('Y-m-d', strtotime('-1 day', strtotime('+1 year'))); } ?>" class="form-control form-control-border" placeholder="Enter season End Date" id="new_activity_season_end_date" name="new_activity_season_end_date">
										<div id="new_activity_season_end_date_msg"></div>
									</div>
								</div>
								
								<button class="btn btn-primary" class="submit" id="new_activity_season_form_btn" name="new_activity_season_form_btn">Submit</button>

							</form>
						</div>
					</div>

					<?php

					if(isset($_GET['action']) && $_GET['action'] == 'edit') {

						$edit_activity_season_id = validate($_GET['id']);

					?>

					<div class="collapse show" id="edit_activity_season_container">
						<div class="card card-body">

							<?php

							$edit_activity_season_title = $edit_activity_season_stock_crop = $edit_activity_season_year = $edit_activity_season_start_date = $edit_activity_season_end_date = '';
							if(isset($_POST['edit_activity_season_form_btn'])) {
								$edit_activity_season_title = validate($_POST['edit_activity_season_title']);
								$edit_activity_season_stock_crop = validate($_POST['edit_activity_season_stock_crop']);
								$edit_activity_season_year = validate($_POST['edit_activity_season_year']);
								$edit_activity_season_start_date = validate($_POST['edit_activity_season_start_date']);
								$edit_activity_season_end_date = validate($_POST['edit_activity_season_end_date']);
								$edit_activity_season_status = validate($_POST['edit_activity_season_status']);

								if( !empty($edit_activity_season_title) && !empty($edit_activity_season_stock_crop) && !empty($edit_activity_season_year) && !empty($edit_activity_season_start_date) && !empty($edit_activity_season_end_date) && $edit_activity_season_status != '' ) {

									$query = mysqli_query($conn, "UPDATE stock_activity_season SET season_title='$edit_activity_season_title', stock_crop_id='$edit_activity_season_stock_crop', season_year='$edit_activity_season_year', season_start_date='$edit_activity_season_start_date', season_end_date='$edit_activity_season_end_date', season_status='$edit_activity_season_status' WHERE id='$edit_activity_season_id'");
									if($edit_activity_season_status == 1) {
										$deactive_activity_season = mysqli_query($conn, "UPDATE stock_activity_season SET season_status='0' WHERE id!='$edit_activity_season_id' && stock_crop_id='$edit_activity_season_stock_crop'");
									}

									if($query) {
										echo "<div class='alert alert-success' id='edit_activity_season_form_alert'>season Successfully Updated</div>";
										echo "<script>history.pushState({}, '', 'activity-season.php'); setTimeout(function(){ $('#edit_activity_season_container').removeClass('show'); $('#edit_activity_season_title').val(''); $('#edit_activity_season_form_alert').remove(); }, 1000)</script>";
									} else {
										echo "<div class='alert alert-danger'>Please try again</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							$edit_activity_season_query = mysqli_query($conn, "SELECT * FROM stock_activity_season WHERE id='$edit_activity_season_id' && delete_status='0'");
							if(mysqli_num_rows($edit_activity_season_query) > 0) {
								$edit_activity_season_result = mysqli_fetch_assoc($edit_activity_season_query);

							?>

							<form method="post" id="edit_activity_season_form" class="form">

								<div class="row">
									<div class="col-md-4 mb-3">
										<label>Season Title:</label>
										<input type="text" pattern="[A-Za-z]+" value="<?= $edit_activity_season_result['season_title']; ?>" class="form-control form-control-border" placeholder="Enter season Title" id="edit_activity_season_title" name="edit_activity_season_title">
										<div id="edit_activity_season_title_msg"></div>
									</div>
									<div class="col-md-4 mb-3">
										<label>Stock Crop:</label>
										<select class="form-control form-control-border" id="edit_activity_season_stock_crop" name="edit_activity_season_stock_crop">
											<option value="">Select Stock Crop</option>
											<?php

											$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
											if(mysqli_num_rows($stock_crop_query) > 0) {
												while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
													if($stock_crop_result['id'] == $edit_activity_season_result['stock_crop_id']) {
														$stock_crop_selected = 'selected';
													} else if($stock_crop_result['id'] == $edit_activity_season_stock_crop) {
														$stock_crop_selected = 'selected';
													} else {
														$stock_crop_selected = '';
													}
													echo "<option ".$stock_crop_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['crop']."</option>";
												}
											}

											?>
										</select>
										<div id="edit_stock_crop_msg"></div>
									</div>
									<div class="col-md-4 mb-3">
										<label>Season Year:</label>
										<select class="form-control form-control-border" id="edit_activity_season_year" name="edit_activity_season_year">
											<option value="">Select Season Year</option>
											<?php

											for($i = 1970 ; $i < date('Y') + 5; $i++) {
												if($i == $edit_activity_season_result['season_year']) {
													$activity_season_year_selected = 'selected';
												} else if($i == $edit_activity_season_year) {
													$activity_season_year_selected = 'selected';
												} else {
													$activity_season_year_selected = '';
												}
												echo "<option ".$activity_season_year_selected." value='".$i."'>".$i."</option>";
											}

											?>
										</select>
										<div id="edit_activity_season_year_msg"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-4 mb-3">
										<label>Season Start Date:</label>
										<input type="date" value="<?php if($edit_activity_season_start_date != '') { echo date('Y-m-d', strtotime($edit_activity_season_start_date)); } else { echo date('Y-m-d', strtotime($edit_activity_season_result['season_start_date'])); } ?>" class="form-control form-control-border" placeholder="Enter season Start Date" id="edit_activity_season_start_date" name="edit_activity_season_start_date">
										<div id="edit_activity_season_start_date_msg"></div>
									</div>
									<div class="col-md-4 mb-3">
										<label>Season End Date:</label>
										<input type="date" value="<?php if($edit_activity_season_end_date != '') { echo date('Y-m-d', strtotime($edit_activity_season_end_date)); } else { echo date('Y-m-d', strtotime($edit_activity_season_result['season_end_date'])); } ?>" class="form-control form-control-border" placeholder="Enter season End Date" id="edit_activity_season_end_date" name="edit_activity_season_end_date">
										<div id="edit_activity_season_end_date_msg"></div>
									</div>
									<div class="col-md-4 mb-3">
										<label>Active Status:</label>
										<div class="form-check">
											<input class="form-check-input" type="radio" name="edit_activity_season_status" id="edit_activity_season_active" value="1" <?php if($edit_activity_season_result['season_status'] == '1') { echo 'checked'; } ?>>
											<label class="form-check-label" for="edit_activity_season_active">Active</label>
										</div>
										<div class="form-check">
											<input class="form-check-input" type="radio" name="edit_activity_season_status" id="edit_activity_season_inactive" value="0" <?php if($edit_activity_season_result['season_status'] == '0') { echo 'checked'; } ?>>
											<label class="form-check-label" for="edit_activity_season_inactive">Inactive</label>
										</div>
									</div>
								</div>
								
								<button class="btn btn-primary" class="submit" id="edit_activity_season_form_btn" name="edit_activity_season_form_btn">Submit</button>

							</form>

							<?php

							}

							?>
						</div>
					</div>

					<?php

					}

					if(isset($_GET['query'])) {
						$search_result_msg = 'Search Result';
						if(isset($_GET['filter_crop']) && !empty( $_GET['filter_crop'] ) ) {
							$search_result_msg .= ' of crop <b>'.stock_crop(validate($_GET['filter_crop'])).'</b>';
						}
						if(isset($_GET['filter_year']) && !empty( $_GET['filter_year'] ) ) {
							$search_result_msg .= ' of year <b>'.validate($_GET['filter_year']).'</b>';
						}
						if(isset($_GET['filter_status']) && $_GET['filter_status'] != '' ) {
							$search_result_msg .= ' of status <b>';
							if(validate($_GET['filter_status']) == 0) {
								$search_result_msg .= ' Inactive';
							} else {
								$search_result_msg .= ' Active';
							}
							$search_result_msg .= '</b>';
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
											<div class="col">
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
											<div class="col">
												<select class="form-control w-100 form-control-sm form-control-border filter_year" name="filter_year">
													<option value="">Select Year</option>
													<?php

													for($i = 1970 ; $i < date('Y') + 5; $i++) {
														if(isset($_GET['filter_year']) && $_GET['filter_year'] == $i) {
															$activity_season_year_selected = 'selected';
														} else {
															$activity_season_year_selected = '';
														}
														echo "<option ".$activity_season_year_selected." value='".$i."'>".$i."</option>";
													}

													?>
												</select>
											</div>
											<div class="col">
												<select class="form-control w-100 form-control-sm form-control-border filter_status" name="filter_status">
													<option value="">Select Status</option>
													<option value="0" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 0) { echo 'selected'; } ?>>Inactive</option>
													<option value="1" <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 1) { echo 'selected'; } ?>>Active</option>
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
													<th>Year</th>
													<th>Crop</th>
													<th>Start Date</th>
													<th>End Date</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_activity_season">

												<?php

												if(isset($_GET['query']) && $_GET['query'] == 'filter') {

													$sql_query = "SELECT * FROM stock_activity_season WHERE delete_status='0'";

													if(isset($_GET['filter_crop']) && !empty( $_GET['filter_crop'] ) ) {
														$sql_query .= " && stock_crop_id='".validate($_GET['filter_crop'])."' ";
													}

													if(isset($_GET['filter_year']) && !empty( $_GET['filter_year'] )) {
														$sql_query .= " && season_year='".validate($_GET['filter_year'])."' ";
													}

													if(isset($_GET['filter_status']) && $_GET['filter_status'] != '') {
														$sql_query .= " && season_status='".validate($_GET['filter_status'])."' ";
													}

													$sql_query .= " ORDER BY time_created DESC ";

													$query = mysqli_query($conn, $sql_query);

												} else {
													$query = mysqli_query($conn, "SELECT * FROM stock_activity_season WHERE delete_status='0' ORDER BY time_created DESC");
												}

												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
												?>
												<tr>
													<td><?= $i; ?></td>
													<td><?= $result['season_title']; ?></td>
													<td><a href="?query=filter&filter_year=<?= $result['season_year']; ?>"><?= $result['season_year']; ?></a></td>
													<td><a href="?query=filter&filter_crop=<?= $result['stock_crop_id']; ?>"><?= stock_crop($result['stock_crop_id']); ?></a></td>
													<td><?= date('d-F-Y', strtotime($result['season_start_date'])); ?></td>
													<td><?= date('d-F-Y', strtotime($result['season_end_date'])); ?></td>
													<td>
														<?php

														if($result['season_status'] == 1) {
															echo "<span class='badge badge-success'>Active</span>";
														} else {
															echo "<span class='badge badge-danger'>Inactive</span>";
														}

														?>
													</td>
													<td>
														<div class="btn-group">
															<a href="activity-season.php?id=<?= $result['id']; ?>&action=edit" class="btn btn-primary btn-sm">Edit</a>
														</div>
													</td>
												</tr>
												<?php
														$i++;
													}
												} else {
													echo "<tr><td colspan='8' class='text-center'>No Record Found</td></tr>";
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
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

	<script class="text/javascript">
		$(document).ready(function(){

			$(document).on('click', '.edit_activity_season_container_close', function(){
				history.pushState({}, '', 'activity-season.php');
			});

			$(document).on('click', '.delete_activity_season_btn', function(){

				if(confirm('Are you sure to delete activity_season?')) {
					return true;
				} else {
					return false;
				}

			});

			$('#new_activity_season_title').on('focus', function(){
				$('#new_activity_season_title').removeClass('is-invalid');
				$('#new_activity_season_title_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_activity_season_stock_crop').on('focus', function(){
				$('#new_activity_season_stock_crop').removeClass('is-invalid');
				$('#new_activity_season_stock_crop_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_activity_season_year').on('focus', function(){
				$('#new_activity_season_year').removeClass('is-invalid');
				$('#new_activity_season_year_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_activity_season_start_date').on('focus', function(){
				$('#new_activity_season_start_date').removeClass('is-invalid');
				$('#new_activity_season_start_date_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_activity_season_end_date').on('focus', function(){
				$('#new_activity_season_end_date').removeClass('is-invalid');
				$('#new_activity_season_end_date_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_activity_season_form').on('submit', function(){
				var bool = 0;

				if($('#new_activity_season_title').val() == '') {
					$('#new_activity_season_title').addClass('is-invalid');
					$('#new_activity_season_title_msg').addClass('invalid-feedback').text('season Title Required');
					bool = 1;
				}

				if($('#new_activity_season_stock_crop').val() == '') {
					$('#new_activity_season_stock_crop').addClass('is-invalid');
					$('#new_activity_season_stock_crop_msg').addClass('invalid-feedback').text('Stock Crop Required');
					bool = 1;
				}

				if($('#new_activity_season_year').val() == '') {
					$('#new_activity_season_year').addClass('is-invalid');
					$('#new_activity_season_year_msg').addClass('invalid-feedback').text('season Year Required');
					bool = 1;
				}

				if($('#new_activity_season_start_date').val() == '') {
					$('#new_activity_season_start_date').addClass('is-invalid');
					$('#new_activity_season_start_date_msg').addClass('invalid-feedback').text('season Start Date Required');
					bool = 1;
				}

				if($('#new_activity_season_end_date').val() == '') {
					$('#new_activity_season_end_date').addClass('is-invalid');
					$('#new_activity_season_end_date_msg').addClass('invalid-feedback').text('season End Date Required');
					bool = 1;
				}

				if(bool == 0) {
					return true;
				} else {
					return false;
				}
			});


			$('#edit_activity_season_title').on('focus', function(){
				$('#edit_activity_season_title').removeClass('is-invalid');
				$('#edit_activity_season_title_msg').removeClass('invalid-feedback').text('');
			});

			$('#edit_activity_season_stock_crop').on('focus', function(){
				$('#edit_activity_season_stock_crop').removeClass('is-invalid');
				$('#edit_activity_season_stock_crop_msg').removeClass('invalid-feedback').text('');
			});

			$('#edit_activity_season_year').on('focus', function(){
				$('#edit_activity_season_year').removeClass('is-invalid');
				$('#edit_activity_season_year_msg').removeClass('invalid-feedback').text('');
			});

			$('#edit_activity_season_start_date').on('focus', function(){
				$('#edit_activity_season_start_date').removeClass('is-invalid');
				$('#edit_activity_season_start_date_msg').removeClass('invalid-feedback').text('');
			});

			$('#edit_activity_season_end_date').on('focus', function(){
				$('#edit_activity_season_end_date').removeClass('is-invalid');
				$('#edit_activity_season_end_date_msg').removeClass('invalid-feedback').text('');
			});

			$('#edit_activity_season_form').on('submit', function(){
				var bool = 0;

				if($('#edit_activity_season_title').val() == '') {
					$('#edit_activity_season_title').addClass('is-invalid');
					$('#edit_activity_season_title_msg').addClass('invalid-feedback').text('season Title Required');
					bool = 1;
				}

				if($('#edit_activity_season_stock_crop').val() == '') {
					$('#edit_activity_season_stock_crop').addClass('is-invalid');
					$('#edit_activity_season_stock_crop_msg').addClass('invalid-feedback').text('Stock Crop Required');
					bool = 1;
				}

				if($('#edit_activity_season_year').val() == '') {
					$('#edit_activity_season_year').addClass('is-invalid');
					$('#edit_activity_season_year_msg').addClass('invalid-feedback').text('season Year Required');
					bool = 1;
				}

				if($('#edit_activity_season_start_date').val() == '') {
					$('#edit_activity_season_start_date').addClass('is-invalid');
					$('#edit_activity_season_start_date_msg').addClass('invalid-feedback').text('season Start Date Required');
					bool = 1;
				}

				if($('#edit_activity_season_end_date').val() == '') {
					$('#edit_activity_season_end_date').addClass('is-invalid');
					$('#edit_activity_season_end_date_msg').addClass('invalid-feedback').text('season End Date Required');
					bool = 1;
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