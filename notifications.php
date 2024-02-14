<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Notifications';

	include "head.php";

	if(is_manager() || is_storekeeper()) {
		header('Location: index.php');
	}

	if(isset($_POST['new_activity_form_btn'])) {
		$new_notification_collapse_class = 'show';
	} else {
		$new_notification_collapse_class = '';
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
							<a href="notification-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='notifications.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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

					<?php

					if(isset($_GET['action']) && $_GET['action'] == 'delete') {

						if(isset($_GET['id']) && $_GET['id'] != 0 && !empty( $_GET['id'] ) ) {
							$id = validate( $_GET['id'] );

							$delete_query = mysqli_query($conn, "UPDATE activity_chart SET delete_status='1' WHERE id='$id'");
							if($delete_query) {
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





					if(isset($_GET['query'])) {
						$search_result_msg = 'Search Result';
						if(isset($_GET['filter_circle']) && !empty( $_GET['filter_circle'] ) ) {
							$search_result_msg .= ' of circle <b>'.($_GET['filter_circle'] == 0 ? 'All circles' : circle_name(mysqli_real_escape_string($conn, trim(strip_tags($_GET['filter_circle']))))).'</b>';
						}
						if(isset($_GET['filter_user']) && $_GET['filter_user'] != '') {
							$search_result_msg .= ' of User <b>'.($_GET['filter_user'] == 0 ? 'All Users' : user_display_name(mysqli_real_escape_string($conn, trim(strip_tags($_GET['filter_user']))))).'</b>';
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
											<div class="col-4">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_circle">
													<option value="">Select AO Circle</option>
													<option value="0" <?php if(isset($_GET['filter_circle']) && $_GET['filter_circle'] == 0) { echo 'selected'; } ?>>All Circles</option>
													<?php

													$circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($circle_query) > 0) {
														while($circle_result = mysqli_fetch_assoc($circle_query)) {
															if(isset($_GET['filter_circle']) && $_GET['filter_circle'] == $circle_result['id']) { $circle_selected = 'selected'; } else { $circle_selected = ''; }
															echo "<option ".$circle_selected." value='".$circle_result['id']."'>".circle_name($circle_result['id'])."</option>";
														}
													}

													?>
												</select>
											</div>
											<div class="col-4">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_user">
													<option value="">Select User</option>
													<option value="0" <?php if(isset($_GET['filter_user']) && $_GET['filter_user'] == 0) { echo 'selected'; } ?>>All Users</option>
													<?php

													$user_query = mysqli_query($conn, "SELECT * FROM users WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($user_query) > 0) {
														while($user_result = mysqli_fetch_assoc($user_query)) {
															if(isset($_GET['filter_user']) && $_GET['filter_user'] == $user_result['id']) { $user_selected = 'selected'; } else { $user_selected = ''; }
															echo "<option ".$user_selected." value='".$user_result['id']."'>".user_display_name($user_result['id'])."</option>";
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
													<th class="col-3">Content</th>
													<th>Circle</th>
													<th>User</th>
													<th>Activity</th>
													<th>Seen Status</th>
													<th>Seen Time</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_notification">

												<?php

												if(isset($_GET['query']) && $_GET['query'] == 'filter') {
													$sql_query = "SELECT * FROM notifications WHERE parent_id is NULL && delete_status='0'";
													if(isset($_GET['filter_circle']) && !empty( $_GET['filter_circle'] ) ) {
														$sql_query .= " && (notify_circle_id='".validate($_GET['filter_circle'])."' ";
														$sql_query .= " || notify_circle_id='0') ";
													}
													if(isset($_GET['filter_user']) && !empty( $_GET['filter_user'] ) ) {
														$sql_query .= " && (notify_user_id='".validate($_GET['filter_user'])."' ";
														$sql_query .= " || notify_user_id='0') ";
													}
													$sql_query .= " ORDER BY time_created DESC ";
													$query = mysqli_query($conn, $sql_query);
												} else {
													$query = mysqli_query($conn, "SELECT * FROM notifications WHERE delete_status='0' && parent_id is NULL ORDER BY time_created DESC");
												}

												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
														$notification_id = $result['id'];
												?>
												<tr>
													<td><?= $i; ?></td>
													<td><?= $result['content']; ?></td>
													<td><?php if(is_null($result['notify_circle_id'])) { echo ''; } else if($result['notify_circle_id'] == 0) { echo 'All circles'; } else { echo circle_name($result['notify_circle_id']); } ?></td>
													<td><?php if(is_null($result['notify_user_id'])) { echo ''; } else if($result['notify_user_id'] == 0) { echo 'All Users'; } else { echo user_display_name($result['notify_user_id']); } ?></td>
													<td><?php if(!is_null($result['activity_id'])) { echo activity_id($result['activity_id']); } ?></td>
													<td><?php

													if($result['notify_circle_id'] == 0 || $result['notify_user_id'] == 0) {
														echo '<button class="btn btn-link btn-sm notification_seen_user_btn" data-id="'.$result['id'].'">Seen by <b>'.mysqli_num_rows(mysqli_query($conn, "SELECT * FROM notification_seen WHERE notify_id='$notification_id' && active_status='1' && delete_status='0'")).'</b> users</button>';
													} else {
														if($result['seen_status'] == 1) {
															echo "<span class='badge badge-success'>Seen</span>";
														} else {
															echo "<span class='badge badge-secondary'>Unseen</span>";
														}
													}

													?></td>
													<td><?php if(!empty($result['seen_time'])) { echo date('d-F-Y h:i:s', $result['seen_time']); } ?></td>
													<td>
														<?php

														if($result['active_status'] == 1) {
															echo "<span class='badge badge-success'>".date('d-F-Y h:i A', $result['notify_time'])."</span>";
														} else if($result['active_status'] == 2) {
															echo "<span class='badge badge-secondary'>".date('d-F-Y h:i A', $result['notify_time'])."</span>";
														} else {
															echo "<span class='badge badge-danger'>Inactive</span>";
														}

														?>
													</td>
													<td>
														<div class="btn-group">
															<a href="notification-edit.php?id=<?= $result['id']; ?>&action=edit" class="btn btn-primary btn-sm">Edit</a>
															<a href='notifications.php?id=<?= $result['id']; ?>&action=delete' class='btn btn-danger btn-sm delete_notification_btn'>Delete</a>
														</div>
													</td>
												</tr>
												<?php
														$i++;
													}
												} else {
													echo "<tr><td colspan='9' class='text-center'>No Record Found</td></tr>";
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

					<div class="modal fade" id="notification_seen_user_modal">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5>Notification Seen Users</h5>
									<a href="#" class="close" data-dismiss="modal">&times;</a>
								</div>
								<div class="modal-body">
									<div class="table-responsive">
										<table class="table table-striped table-hover table-sm">
											<thead>
												<tr>
													<th>#</th>
													<th>User</th>
													<th>Seen Time</th>
												</tr>
											</thead>
											<tbody id="display_notification_seen_users">
												<tr><td colspan="3" class="text-center">No User Found</td></tr>
											</tbody>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>

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

			$(document).on('click', '.notification_seen_user_btn', function(){
				$('#notification_seen_user_modal').modal('show');
				var id = $(this).data('id');

				if(id != '' && id != 0) {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'display_notification_seen_users', id:id },
						success: function(result) {
							$('#display_notification_seen_users').html(result);
						}
					});
				}
			});

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