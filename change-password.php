<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Change Password';

	include "head.php";

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

					if(isset($_POST['change_password_form_btn'])) {

						$old_password = validate($_POST['old_password']);
						$new_password = validate($_POST['new_password']);
						$confirm_new_password = validate($_POST['confirm_new_password']);

						if( !empty($old_password) && !empty($new_password) && !empty($confirm_new_password) ) {

							if( $new_password == $confirm_new_password ) {
								$new_pass = password_hash($confirm_new_password, PASSWORD_DEFAULT);
								$query = mysqli_query($conn, "SELECT * FROM users WHERE id='$user_id' && active_status='1' && delete_status='0'");
								if(mysqli_num_rows($query) > 0) {
									$result = mysqli_fetch_assoc($query);
									if(password_verify($old_password, $result['user_pass'])) {
										$update_query = mysqli_query($conn, "UPDATE users SET user_pass='$new_pass' WHERE id='$user_id'");

										if($update_query) {
											echo "<div class='alert alert-success'>Password Successfully Updated</div>";
											echo "<meta http-equiv='refresh' content='1'>";
										}
									} else {
										echo "<div class='alert alert-danger'>Old Password Not Matched</div>";
									}
								} else {
									echo "<div class='alert alert-danger'>No User Found</div>";
								}

							} else {
								echo "<div class='alert alert-danger'>Old Password and Confirm Password Not Matched</div>";
							}

						} else {
							echo "<div class='alert alert-danger'>Fill Required Fields</div>";
						}

					}

					?>
					
					<form class="form" method="post" id="change_password_form">
						
						<div class="mb-3">
							<label>Old Password: <span class="text-danger">*</span></label>
							<input type="password" class="form-control" placeholder="Enter Old Password" id="old_password" name="old_password">
							<div id="old_password_msg"></div>
						</div>

						<div class="mb-3">
							<label>New Password: <span class="text-danger">*</span></label>
							<input type="password" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$" class="form-control" placeholder="Enter New Password" id="new_password" name="new_password">
							<small class="form-text text-muted">Password must contain One Uppercase, and One Lowercase letter, One number and minimum 8 characters.</small>
							<div id="new_password_msg"></div>
						</div>

						<div class="mb-3">
							<label>Confirm New Password: <span class="text-danger">*</span></label>
							<input type="password" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$" class="form-control" placeholder="Confirm New Password" id="confirm_new_password" name="confirm_new_password">
							<small class="form-text text-muted">Password must contain One Uppercase, and One Lowercase letter, One number and minimum 8 characters.</small>
							<div id="confirm_new_password_msg"></div>
						</div>

						<button class="btn btn-primary" type="submit" name="change_password_form_btn" id="change_password_form_btn">Submit</button>

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

			$('#old_password').on('focus', function(){
				$('old_password').removeClass('is-invalid');
				$('#old_password_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_password').on('focus', function(){
				$('#new_password').removeClass('is-invalid');
				$('#new_password_msg').removeClass('invalid-feedback').text('');
			});

			$('#confirm_new_password').on('focus', function(){
				$('#confirm_new_password').removeClass('is-invalid');
				$('#confirm_new_password_msg').removeClass('invalid-feedback').text('');
			});

			$('#change_password_form').on('submit', function(){

				var old_password = $('#old_password').val();
				var new_password = $('#new_password').val();
				var confirm_new_password = $('#confirm_new_password').val();
				var bool = 0;

				if(old_password == '') {
					$('#old_password').addClass('is-invalid');
					$('#old_password_msg').addClass('invalid-feedback').text('Old Password Required');
					bool = 1;
				} else {
					$('#old_password').removeClass('is-invalid');
					$('#old_password_msg').removeClass('invalid-feedback').text('');
				}

				if(new_password == '') {
					$('#new_password').addClass('is-invalid');
					$('#new_password_msg').addClass('invalid-feedback').text('New Password Required');
					bool = 1;
				} else {
					$('#new_password').removeClass('is-invalid');
					$('#new_password_msg').removeClass('invalid-feedback').text('');
				}

				if(confirm_new_password == '') {
					$('#confirm_new_password').addClass('is-invalid');
					$('#confirm_new_password_msg').addClass('invalid-feedback').text('Confirm Password Required');
					bool = 1;
				} else {
					$('#confirm_new_password').removeClass('is-invalid');
					$('#confirm_new_password_msg').removeClass('invalid-feedback').text('');
				}

				if(new_password != confirm_new_password) {
					$('#confirm_new_password').addClass('is-invalid');
					$('#confirm_new_password_msg').addClass('invalid-feedback').text('Confirm password not matched');
					bool = 1;
				} else {
					$('#confirm_new_password').removeClass('is-invalid');
					$('#confirm_new_password_msg').removeClass('invalid-feedback').text('');
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