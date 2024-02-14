<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Notification New';

	include "head.php";

	if(is_manager() || is_storekeeper()) {
		header('Location: index.php');
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
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item"><a href="notifications.php">Notifications</a></li>
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

					$content = $activity_id = $notify_circle_id = $notify_user_id = $instant_notify = $notify_time = '';
					if(isset($_POST['new_notification_form_btn'])) {

						$content = ucwords(validate($_POST['new_content']));
						$notify_circle_id = validate($_POST['new_circle_id']);
						$notify_user_id = validate($_POST['new_user_id']);
						if(isset($_POST['instant_notify_time'])) {
							$instant_notify = 1;
							$notify_time = $time_created;
							$active_status = 1;
						} else {
							$instant_notify = 0;
							$notify_time = strtotime(validate($_POST['notify_time']));
							$active_status = 2;
						}

						if( !empty( $content ) && !empty( $notify_time ) ) {

							$sql_query = "INSERT INTO notifications (user_id, content, notify_time, active_status, time_created";

							if(!empty($notify_all_user)) {
								$sql_query .= ", notify_user_id";
							}
							if(!empty($notify_all_circle)) {
								$sql_query .= ", notify_circle_id";
							}
							if (!empty($activity_id)) {
								$sql_query .= ", activity_id";
							}
							$sql_query .= ") VALUES ('$user_id', '$content', '$notify_time', '$active_status', '$time_created'";
							if(!empty($notify_all_user)) {
								$sql_query .= ", '$notify_user_id'";
							}
							if(!empty($notify_all_circle)) {
								$sql_query .= ", '$notify_circle_id'";
							}
							if (!empty($activity_id)) {
								$sql_query .= ", '$activity_id'";
							}
							$sql_query .= ")";

							$query = mysqli_query($conn, "INSERT INTO notifications(user_id, notify_user_id, notify_circle_i, content, notify_time, active_status, time_created) VALUES('$user_id', '$notify_user_id', '$notify_circle_id', '$content', '$notify_time', '$active_status', '$time_created')");

							if($query) {
								echo "<div class='alert alert-success'>Notification Successfully Created</div>";
								echo "<meta http-equiv='refresh' content='1'>";
							} else {
								echo "<div class='alert alert-danger'>Please Try Again</div>";
							}

						} else {
							echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
						}

					}

					?>

					<form class="form" enctype="multipart/form-data" id="new_notification_form" method="post">
						
						<div class="mb-3">
							<label>Content:</label>
							<textarea class="form-control" placeholder="Enter Notifications Content" name="new_content" id="new_content"></textarea>
							<div id="new_content_msg"></div>
						</div>
						<div class="mb-3">
							<label>Circle:</label>
							<select class="form-control" name="new_circle_id" id="new_circle_id">
								<option value="">Select AO Circle</option>
								<option value="0">All Circles</option>
								<?php

								$circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE active_status='1' && delete_status='0'");
								if(mysqli_num_rows($circle_query) > 0) {
									while($circle_result = mysqli_fetch_assoc($circle_query)) {
										echo "<option value='".$circle_result['id']."'>".circle_name($circle_result['id'])."</option>";
									}
								}

								?>
							</select>
							<div id="new_circle_id_msg"></div>
							<small class="form-text text-muted">Select circle if the nofication is for circle, This notification will be sent to the Procurement Officier of the circle.</small>
						</div>
						<div class="mb-3">
							<label>User:</label>
							<select class="form-control" name="new_user_id" id="new_user_id">
								<option value="">Select User</option>
								<option value="0">All Users</option>
								<?php

								$user_query = mysqli_query($conn, "SELECT * FROM users WHERE active_status='1' && delete_status='0' && id!='$user_id'");
								if(mysqli_num_rows($user_query) > 0) {
									while($user_result = mysqli_fetch_assoc($user_query)) {
										echo "<option value='".$user_result['id']."'>".$user_result['display_name']." - ".user_role($user_result['role'], $user_result['type'])." - ".( ($user_result['role'] != 0 && $user_result['type'] != 0) ? circle_name($user_result['circle_id']) : '' )."</option>";
									}
								}

								?>
							</select>
							<div id="new_user_id_msg"></div>
							<small class="form-text text-muted">Select user if the nofication is for specific user, or select All Users then it will be sended to all users.</small>
						</div>
						<div class="mb-3">
							<label>Notification Time:</label>
							<div class="custom-control custom-switch">
								<input type="checkbox" class="custom-control-input" id="instant_notify_time" name="instant_notify_time" value="1">
								<label class="custom-control-label" for="instant_notify_time">Send Instant Notification</label>
							</div>
							<div id="notify_time_container">
								<label>Notification Time:</label>
								<input type="datetime-local" class="form-control" placeholder="Enter Notification Time" id="notify_time" name="notify_time">
							</div>
							<div id="notify_time_msg"></div>
						</div>

						<button class="btn btn-primary" type="submit" name="new_notification_form_btn" id="new_notification_form_btn">Submit</button>

					</form>
				</div><!-- /.container-fluid -->
			</section>
			<!-- /.content -->

		</div>
		<!-- /.content-wrapper -->

		<!-- Control Sidebar -->
		<?php include "footer.php"; ?>

	</div>

	<?php include "javascript.php"; ?>

	<script type="text/javascript">
		$(document).ready(function(){

			$('#instant_notify_time').on('change', function(){
				if($('#instant_notify_time').is(':checked')) {
					$('#notify_time_container').html('');
				} else {
					$('#notify_time_container').html(
						"<label>Notification Time:</label>" + 
						"<input type='datetime-local' class='form-control' placeholder='Enter Notification Time' id='notify_time' name='notify_time'>"
					);
				}
			});

			$('#new_content').on('focus', function(){
				$('#new_content').removeClass('is-invalid');
				$('#new_content_msg').removeClass('invalid-feedback').text('');
			}).focus();

			$('#notify_time').on('focus', function(){
				$('#notify_time').removeClass('is-invalid');
				$('#notify_time_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_notification_form').on('submit', function(e){
				var bool = 0;

				if($('#new_content').val() == '') {
					$('#new_content').addClass('is-invalid');
					$('#new_content_msg').addClass('invalid-feedback').text('Notification Content Required');
					bool = 1;
				} else {
					$('#new_content').removeClass('is-invalid');
					$('#new_content_msg').removeClass('invalid-feedback').text('');
				}

				if($('#instant_notify_time').is(':checked')) {
					$('#notify_time_msg').removeClass('invalid-feedback').text('');
				} else {
					if($('#notify_time').val() == '') {
						$('#notify_time').addClass('is-invalid');
						$('#notify_time_msg').addClass('invalid-feedback').text('Notification Time Required');
						bool = 1;
					} else {
						$('#notify_time').removeClass('is-invalid');
						$('#notify_time_msg').removeClass('invalid-feedback').text('');
					}
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