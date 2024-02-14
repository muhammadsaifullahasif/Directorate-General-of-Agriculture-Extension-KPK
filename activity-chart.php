<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Activity Chart';

	include "head.php";

	if(is_admin() || is_manager() || is_storekeeper()) {
		header('Location: index.php');
	}

	if( isset( $_POST['new_activity_form_btn'] ) ) {
		$new_activity_collapse_class = 'show';
	} else {
		$new_activity_collapse_class = '';
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
							<a data-toggle="collapse" href="#new_activity_container" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
							<?php

							if( isset( $_GET['query'] ) ) {
								echo "<a href='activity-chart.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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

					<div class="collapse <?php echo $new_activity_collapse_class; ?>" id="new_activity_container">
						<div class="card card-body">

							<?php

							if( isset( $_POST['new_activity_form_btn'] ) ) {
								$new_stock_crop = validate( $_POST['new_stock_crop'] );
								$new_activity_type = validate( $_POST['new_activity_type'] );
								$new_activity_time = validate( $_POST['new_activity_time'] );

								if( !empty( $new_stock_crop ) && !empty( $new_activity_type ) && !empty( $new_activity_time ) ) {

									if( mysqli_num_rows( mysqli_query( $conn, "SELECT * FROM activity_chart WHERE crop_id='$new_stock_crop' && activity_type='$new_activity_type' && delete_status='0'" ) ) == 0 ) {
										$query = mysqli_query( $conn, "INSERT INTO activity_chart(user_id, crop_id, activity_time, activity_type, time_created) VALUES('$user_id', '$new_stock_crop', '$new_activity_time', '$new_activity_type', '$time_created')" );

										if( $query ) {
											echo "<div class='alert alert-success' id='new_activity_form_alert'>Activity Created Successfully</div>";
											echo "<script>setTimeout(function(){ $('#new_activity_container').removeClass('show'); $('#new_activity_form_alert').remove(); }, 1000)</script>";
										} else {
											echo "<div class='alert alert-danger'>Please try again</div>";
										}
									} else {
										echo "<div class='alert alert-danger'>".stock_crop( $new_stock_crop )." activity already exist</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							?>

							<form method="post" id="new_activity_form" class="form">
								
								<div class="mb-3">
									<label>Stock Crop:</label>
									<select class="form-control" name="new_stock_crop" id="new_stock_crop">
										<option value="">Select Stock Crop</option>
										<?php

										$stock_crop_query = mysqli_query( $conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'" );
										if( mysqli_num_rows( $stock_crop_query ) > 0 ) {
											while( $stock_crop_result = mysqli_fetch_assoc( $stock_crop_query ) ) {
												echo "<option value='".$stock_crop_result['id']."'>".$stock_crop_result['crop']."</option>";
											}
										}

										?>
									</select>
									<div id="new_stock_crop_msg"></div>
								</div>
								<div class="mb-3">
									<label>Activity Type:</label>
									<select class="form-control" name="new_activity_type" id="new_activity_type">
										<option value="">Select Activity Type</option>
										<option value="0">Procure Stock</option>
										<option value="1">Fumigate Stock</option>
										<option value="2">Clean Stock</option>
										<option value="3">Supply Stock</option>
									</select>
									<div id="new_activity_type_msg"></div>
								</div>
								<div class="mb-3">
									<label>Activity Time:</label>
									<select class="form-control" name="new_activity_time" id="new_activity_time">
										<option value="">Select Month</option>
										<option value="January">January</option>
										<option value="February">February</option>
										<option value="March">March</option>
										<option value="April">April</option>
										<option value="May">May</option>
										<option value="June">June</option>
										<option value="July">July</option>
										<option value="August">August</option>
										<option value="September">September</option>
										<option value="November">November</option>
										<option value="December">December</option>
									</select>
									<div id="new_activity_time_msg"></div>
								</div>
								<button class="btn btn-primary" class="submit" id="new_activity_form_btn" name="new_activity_form_btn">Submit</button>

							</form>
						</div>
					</div>

					<?php

					if( isset( $_GET['action'] ) && $_GET['action'] == 'edit' ) {

						$edit_activity_id = validate( $_GET['id'] );

					?>

					<div class="collapse show" id="edit_activity_container">
						<div class="card card-body">

							<?php

							if( isset( $_POST['edit_activity_form_btn'] ) ) {
								$edit_stock_crop = validate( $_POST['edit_stock_crop'] );
								$edit_activity_type = validate( $_POST['edit_activity_type'] );
								$edit_activity_time = validate( $_POST['edit_activity_time'] );

								if( !empty( $edit_stock_crop ) && !empty( $edit_activity_type ) && !empty( $edit_activity_time ) ) {

									$query = mysqli_query( $conn, "UPDATE activity_chart SET crop_id='$edit_stock_crop', activity_time='$edit_activity_time', activity_type='$edit_activity_type' WHERE id='$edit_activity_id'" );

									if( $query ) {
										echo "<div class='alert alert-success' id='edit_activity_form_alert'>Activity Successfully Updated</div>";
										echo "<script>history.pushState({}, '', 'activity-chart.php'); setTimeout(function(){ $('#edit_activity_container').removeClass('show'); $('#edit_city_form_alert').remove(); }, 1000)</script>";
									} else {
										echo "<div class='alert alert-danger'>Please try again</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							$edit_activity_query = mysqli_query( $conn, "SELECT * FROM activity_chart WHERE id='$edit_activity_id' && delete_status='0'" );
							if( mysqli_num_rows( $edit_activity_query ) > 0 ) {
								$edit_activity_result = mysqli_fetch_assoc( $edit_activity_query );

							?>

							<form method="post" id="edit_activity_form" class="form">
								
								<div class="mb-3">
									<label>Stock Crop:</label>
									<select class="form-control" name="edit_stock_crop" id="edit_stock_crop">
										<option value="">Select Crop</option>
										<?php

										$stock_crop_query = mysqli_query( $conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'" );
										if( mysqli_num_rows( $stock_crop_query ) > 0 ) {
											while( $stock_crop_result = mysqli_fetch_assoc( $stock_crop_query ) ) {
												if( $stock_crop_result['id'] == $edit_activity_result['crop_id'] ) {
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
								<div class="mb-3">
									<label>Activity Type:</label>
									<select class="form-control" name="edit_activity_type" id="edit_activity_type">
										<option value="">Select Activity Type</option>
										<option value="0" <?php if( $edit_activity_result['activity_type'] == '0' ) echo 'selected'; ?>>Procure Stock</option>
										<option value="1" <?php if( $edit_activity_result['activity_type'] == '1' ) echo 'selected'; ?>>Fumigate Stock</option>
										<option value="2" <?php if( $edit_activity_result['activity_type'] == '2' ) echo 'selected'; ?>>Clean Stock</option>
										<option value="3" <?php if( $edit_activity_result['activity_type'] == '3' ) echo 'selected'; ?>>Supply Stock</option>
									</select>
									<div id="edit_activity_type_msg"></div>
								</div>
								<div class="mb-3">
									<label>Activity Time:</label>
									<select class="form-control" name="edit_activity_time" id="edit_activity_time">
										<option value="">Select Month</option>
										<option value="January" <?php if( $edit_activity_result['activity_time'] == 'January' ) echo 'selected'; ?>>January</option>
										<option value="February" <?php if( $edit_activity_result['activity_time'] == 'February' ) echo 'selected'; ?>>February</option>
										<option value="March" <?php if( $edit_activity_result['activity_time'] == 'March' ) echo 'selected'; ?>>March</option>
										<option value="April" <?php if( $edit_activity_result['activity_time'] == 'April' ) echo 'selected'; ?>>April</option>
										<option value="May" <?php if( $edit_activity_result['activity_time'] == 'May' ) echo 'selected'; ?>>May</option>
										<option value="June" <?php if( $edit_activity_result['activity_time'] == 'June' ) echo 'selected'; ?>>June</option>
										<option value="July" <?php if( $edit_activity_result['activity_time'] == 'July' ) echo 'selected'; ?>>July</option>
										<option value="August" <?php if( $edit_activity_result['activity_time'] == 'August' ) echo 'selected'; ?>>August</option>
										<option value="September" <?php if( $edit_activity_result['activity_time'] == 'September' ) echo 'selected'; ?>>September</option>
										<option value="November" <?php if( $edit_activity_result['activity_time'] == 'November' ) echo 'selected'; ?>>November</option>
										<option value="December" <?php if( $edit_activity_result['activity_time'] == 'December' ) echo 'selected'; ?>>December</option>
									</select>
									<div id="edit_activity_time_msg"></div>
								</div>
								<button class="btn btn-primary" class="submit" id="edit_activity_form_btn" name="edit_activity_form_btn">Submit</button>

								<button class="btn btn-outline-dark edit_activity_container_close" type="button" data-toggle="collapse" href="#edit_activity_container">Cancel</button>

							</form>

							<?php

							}

							?>
						</div>
					</div>

					<?php

					}

					if( isset( $_GET['action'] ) && $_GET['action'] == 'delete' ) {

						if( isset( $_GET['id'] ) && $_GET['id'] != 0 && !empty( $_GET['id'] ) ) {
							$id = validate( $_GET['id'] );

							$delete_query = mysqli_query( $conn, "UPDATE activity_chart SET delete_status='1' WHERE id='$id'" );
							if( $delete_query ) {
								echo "<div class='alert alert-success notify-alert'>Activity Successfully Deleted</div>";
								echo "<script>history.pushState({}, '', 'activity-chart.php'); setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							} else {
								echo "<div class='alert alert-danger notify-alert'>Please Try Again</div>";
								echo "<script>setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							}
						} else {
							echo "<script>window.top.location='city.php';</script>";
						}
					}





					if( isset( $_GET['query'] ) ) {
						$search_result_msg = 'Search Result';
						if( isset( $_GET['filter_stock_crop'] ) && !empty( $_GET['filter_stock_crop'] ) ) {
							$search_result_msg .= ' of Stock Crop <b>'.stock_crop( validate( $_GET['filter_stock_crop'] ) ).'</b>';
						}
						if( isset( $_GET['filter_type'] ) && !empty( $_GET['filter_type'] ) ) {
							$search_result_msg .= ' of Activity ';
							if( $_GET['filter_type'] == 0 ) {
								$search_result_msg .= '<b>Procure Stock</b>';
							} else if( $_GET['filter_type'] == 1 ) {
								$search_result_msg .= '<b>Fumigate Stock</b>';
							} else if( $_GET['filter_type'] == 2 ) {
								$search_result_msg .= '<b>Clean Stock</b>';
							} else if( $_GET['filter_type'] == 3 ) {
								$search_result_msg .= '<b>Supply Stock</b>';
							}
						}
						if( isset( $_GET['filter_month'] ) && !empty( $_GET['filter_month'] ) ) {
							$search_result_msg .= ' of Month <b>'.validate( $_GET['filter_month'] ).'</b>';
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
											<input type="hidden" value="filter" name="query" style="display: none;">
											<div class="col-3">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_stock_crop">
													<option value="">Crop</option>
													<?php

													$stock_crop_query = mysqli_query( $conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'" );
													if( mysqli_num_rows( $stock_crop_query ) > 0 ) {
														while( $stock_crop_result = mysqli_fetch_assoc( $stock_crop_query ) ) {
															if( isset( $_GET['filter_stock_crop'] ) && $_GET['filter_stock_crop'] == $stock_crop_result['id'] ) { $stock_crop_selected = 'selected'; } else { $stock_crop_selected = ''; }
															echo "<option ".$stock_crop_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['crop']."</option>";
														}
													}

													?>
												</select>
											</div>
											<div class="col-3">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_type">
													<option value="">Activity Type</option>
													<option value="0" <?php if( isset($_GET['filter_type'] ) && $_GET['filter_type'] == '0' ) { echo 'selected'; } ?>>Procure Stock</option>
													<option value="1" <?php if( isset($_GET['filter_type'] ) && $_GET['filter_type'] == '1' ) { echo 'selected'; } ?>>Fumigate Stock</option>
													<option value="2" <?php if( isset($_GET['filter_type'] ) && $_GET['filter_type'] == '2' ) { echo 'selected'; } ?>>Clean Stock</option>
													<option value="3" <?php if( isset($_GET['filter_type'] ) && $_GET['filter_type'] == '3' ) { echo 'selected'; } ?>>Supply Stock</option>
												</select>
											</div>
											<div class="col-3">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_month" id="filter_month">
													<option value="">Select Month</option>
													<option value="January" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'January' ) { echo 'selected'; } ?>>January</option>
													<option value="February" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'February' ) { echo 'selected'; } ?>>February</option>
													<option value="March" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'March' ) { echo 'selected'; } ?>>March</option>
													<option value="April" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'April' ) { echo 'selected'; } ?>>April</option>
													<option value="May" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'May' ) { echo 'selected'; } ?>>May</option>
													<option value="June" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'June' ) { echo 'selected'; } ?>>June</option>
													<option value="July" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'July' ) { echo 'selected'; } ?>>July</option>
													<option value="August" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'August' ) { echo 'selected'; } ?>>August</option>
													<option value="September" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'September' ) { echo 'selected'; } ?>>September</option>
													<option value="November" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'November' ) { echo 'selected'; } ?>>November</option>
													<option value="December" <?php if( isset($_GET['filter_month']) && $_GET['filter_month'] == 'December' ) { echo 'selected'; } ?>>December</option>
												</select>
												<div id="filter_month_msg"></div>
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
													<th>Crop</th>
													<th>Activity</th>
													<th>Date/Time</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_activity">

												<?php

												if( isset( $_GET['query'] ) && $_GET['query'] == 'filter' ) {
													$sql_query = "SELECT * FROM activity_chart WHERE delete_status='0'";
													if( isset( $_GET['filter_stock_crop'] ) && !empty( $_GET['filter_stock_crop'] ) ) {
														$sql_query .= " && crop_id='".validate( $_GET['filter_stock_crop'] )."' ";
													}
													if( isset( $_GET['filter_type'] ) && !empty( $_GET['filter_type'] ) ) {
														$sql_query .= " && activity_type='".validate( $_GET['filter_type'] )."' ";
													}
													if( isset( $_GET['filter_month'] ) && !empty( $_GET['filter_month'] ) ) {
														$sql_query .= " && activity_time='".validate( $_GET['filter_month'] )."' ";
													}
													$query = mysqli_query( $conn, $sql_query );
												} else {
													$query = mysqli_query( $conn, "SELECT * FROM activity_chart WHERE delete_status='0'" );
												}

												if( mysqli_num_rows( $query ) > 0 ) {
													$i = 1;
													while($result = mysqli_fetch_assoc( $query ) ) {
												?>
												<tr>
													<td><?= $i; ?></td>
													<td><?= stock_crop( $result['crop_id'] ); ?></td>
													<td><?= activity( $result['activity_type'] ); ?></td>
													<td><?= $result['activity_time']; ?></td>
													<td>
														<?php

														if( $result['active_status'] == 1 ) {
															echo "<span class='badge badge-success'>Active</span>";
														} else {
															echo "<span class='badge badge-danger'>Inactive</span>";
														}

														?>
													</td>
													<td>
														<div class="btn-group">
															<a href="activity-chart.php?id=<?= $result['id']; ?>&action=edit" class="btn btn-primary btn-sm">Edit</a>
															<a href='activity-chart.php?id=<?= $result['id']; ?>&action=delete' class='btn btn-danger btn-sm delete_activity_btn'>Delete</a>
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

			$(document).on('click', '.edit_activity_container_close', function(){
				history.pushState({}, '', 'activity-chart.php');
			});

			$(document).on('click', '.delete_activity_btn', function(){

				if(confirm('Are you sure to delete activity?')) {
					return true;
				} else {
					return false;
				}

			});

			$('#new_city_name').on('focus', function(){
				$('#new_city_name').removeClass('is-invalid');
				$('#new_city_name_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_city_form').on('submit', function(){
				var new_city_name = $('#new_city_name').val();

				if(new_city_name == '') {
					$('#new_city_name').addClass('is-invalid');
					$('#new_city_name_msg').addClass('invalid-feedback').text('City Name Required');
					return false;
				} else {
					$('#new_city_name').removeClass('is-invalid');
					$('#new_city_name_msg').removeClass('invalid-feedback').text('');
					return true;
				}
			});

			$('#edit_city_name').on('focus', function(){
				$("#edit_city_name").removeClass('is-invalid');
				$('#edit_city_name_msg').removeClass('invalid-feedback').text('');
			});

			$('#edit_city_form').on('submit', function(){
				var edit_city_name = $('#edit_city_name').val();

				if(edit_city_name == '') {
					$('#edit_city_name').addClass('is-invalid');
					$('#edit_city_name_msg').addClass('invalid-feedback').text('City Name Required');
					return false;
				} else {
					$('#edit_city_name').removeClass('is-invalid');
					$('#edit_city_name_msg').removeClass('invalid-feedback').text('');
					return true;
				}
			});

		});
	</script>
</body>
</html>