<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Notification Edit';

	include "head.php";

	if(is_manager() || is_storekeeper()) {
		header('Location: index.php');
	}

	if(isset($_GET['id']) && !empty( $_GET['id'] ) && $_GET['id'] != 0) {
		$id = validate($_GET['id']);

		$query = mysqli_query($conn, "SELECT * FROM notifications WHERE id='$id' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
		} else {
			header('Location: notifications.php');
		}
	} else {
		header('Location: notifications.php');
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

					if(isset($_POST['edit_notification_form_btn'])) {

						$content = ucwords(validate($_POST['edit_content']));
						$activity_id = validate($_POST['edit_activity']);
						$notify_circle_id = validate($_POST['edit_circle_id']);
						$notify_user_id = validate($_POST['edit_user_id']);
						if(isset($_POST['instant_notify_time'])) {
							$instant_notify = 1;
							$notify_time = $time_created;
							$active_status = 1;
						} else {
							$instant_notify = 0;
							$notify_time = strtotime(validate($_POST['notify_time']));
							$active_status = 2;
						}

						if( !empty( $content ) && ( $instant_notify == 0 && !empty( $notify_time ) ) ) {

							$query = mysqli_query($conn, "UPDATE notifications SET notify_user_id='$notify_user_id', notify_circle_id='$notify_circle_id', activity_id='$activity_id', content='$content', notify_time='$notify_time', active_status='$active_status' WHERE id='$id'");

							if($query) {
								echo "<div class='alert alert-success'>Notification Successfully Updated</div>";
								echo "<meta http-equiv='refresh' content='1'>";
							} else {
								echo "<div class='alert alert-danger'>Please Try Again</div>";
							}

						} else {
							echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
						}

					}

					?>

					<form class="form" enctype="multipart/form-data" id="edit_notification_form" method="post">
						
						<div class="mb-3">
							<label>Content:</label>
							<textarea class="form-control" placeholder="Enter Notifications Content" name="edit_content" id="edit_content"><?= $result['content']; ?></textarea>
							<div id="edit_content_msg"></div>
						</div>
						<div class="mb-3">
							<label>Activity:</label>
							<select class="form-control" name="edit_activity" id="edit_activity">
								<option value="">Select Activity</option>
								<?php

								$activity_query = mysqli_query($conn, "SELECT * FROM activity_chart WHERE active_status='1' && delete_status='0'");
								if(mysqli_num_rows($activity_query) > 0) {
									while($activity_result = mysqli_fetch_assoc($activity_query)) {
										if($activity_result['id'] == $result['activity_id']) { $activity_selected = 'selected'; } else { $activity_selected = ''; }
										echo "<option ".$activity_selected." value='".$activity_result['id']."'>".activity_id($activity_result['id']).'</option>';
									}
								}

								?>
							</select>
							<div id="edit_activity_msg"></div>
						</div>
						<div class="mb-3">
							<label>Circle:</label>
							<select class="form-control" name="edit_circle_id" id="edit_circle_id">
								<option value="">Select AO Circle</option>
								<option value="0" <?php if(!is_null($result['notify_circle_id']) && $result['notify_circle_id'] == 0) { echo 'selected'; } ?>>All Circles</option>
								<?php

								$circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE active_status='1' && delete_status='0'");
								if(mysqli_num_rows($circle_query) > 0) {
									while($circle_result = mysqli_fetch_assoc($circle_query)) {
										if($circle_result['id'] == $result['notify_circle_id']) { $circle_selected = 'selected'; } else { $circle_selected = ''; }
										echo "<option ".$circle_selected." value='".$circle_result['id']."'>".circle_name($circle_result['id'])."</option>";
									}
								}

								?>
							</select>
							<div id="edit_circle_id_msg"></div>
							<small class="form-text text-muted">Select circle if the nofication is for circle, This notification will be sent to the Procurement Officier of the circle.</small>
						</div>
						<div class="mb-3">
							<label>User:</label>
							<select class="form-control" name="edit_user_id" id="edit_user_id">
								<option value="">Select User</option>
								<option value="0" <?php if(!is_null($result['notify_user_id']) && $result['notify_user_id'] == 0) { echo 'selected'; } ?>>All Users</option>
								<?php

								$user_query = mysqli_query($conn, "SELECT * FROM users WHERE active_status='1' && delete_status='0' && id!='$user_id'");
								if(mysqli_num_rows($user_query) > 0) {
									while($user_result = mysqli_fetch_assoc($user_query)) {
										if($user_result['id'] == $result['notify_user_id']) { $user_selected = 'selected'; } else { $user_selected = ''; }
										echo "<option ".$user_selected." value='".$user_result['id']."'>".$user_result['display_name']." - ".user_role($user_result['role'], $user_result['type'])." - ".( ($user_result['role'] != 0 && $user_result['type'] != 0) ? circle_name($user_result['circle_id']) : '' )."</option>";
									}
								}

								?>
							</select>
							<div id="edit_user_id_msg"></div>
							<small class="form-text text-muted">Select user if the nofication is for specific user, or select All Users then it will be sended to all users.</small>
						</div>
						<div class="mb-3">
							<label>Notification Time:</label>
							<div class="custom-control custom-switch">
								<input type="checkbox" <?php if($result['active_status'] == 1) { echo 'checked'; } ?> class="custom-control-input" id="instant_notify_time" value="1">
								<label class="custom-control-label" for="instant_notify_time">Send Instant Notification</label>
							</div>
							<div id="notify_time_container">
								<?php

								if($result['active_status'] == 2) {
								?>
								<label>Notification Time:</label>
								<input type="datetime-local" value="<?= date('Y-m-d h:i:s', $result['notify_time']); ?>" class="form-control" placeholder="Enter Notification Time" id="notify_time" name="notify_time">
								<?php
								}

								?>
							</div>
							<div id="notify_time_msg"></div>
						</div>

						<button class="btn btn-primary" type="submit" name="edit_notification_form_btn" id="edit_notification_form_btn">Submit</button>

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
						"<input type='datetime-local' value='<?= date('Y-m-d h:i:s', $result['notify_time']); ?>' class='form-control' placeholder='Enter Notification Time' id='notify_time' name='notify_time'>"
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