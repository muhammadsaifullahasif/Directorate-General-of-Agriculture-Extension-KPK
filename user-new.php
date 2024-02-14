<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'User New';

	include "head.php";

	if(is_storekeeper()) {
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
							<a href="user-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item"><a href="users.php">Users</a></li>
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

					$user_first_name = '';
					$user_last_name = '';
					$user_phone_number = '';
					$user_address = '';
					$user_circle_id = '';
					$user_email = '';
					$user_pass = '';

					if(isset($_POST['new_user_form_btn'])) {

						$user_first_name = ucwords(validate($_POST['user_first_name']));
						$user_last_name = ucwords(validate($_POST['user_last_name']));
						$user_display_name = $user_first_name.' '.$user_last_name;
						$user_phone_number = validate($_POST['user_phone_number']);
						$user_address = ucwords(validate($_POST['user_address']));
						$user_email = validate($_POST['user_email']);
						$user_pass = validate($_POST['user_pass']);

						$user_phone_number = validate($_POST['user_phone_number']);
						$user_address = validate($_POST['user_address']);
						
						/*if(is_manager()) {
							$user_circle_id = $circle_id;
						} else {
							$user_circle_id = validate($_POST['user_circle_id']);
						}
						if(is_admin() || is_manager()) {
							$user_district_id = $user_district;
						} else {
							$user_district_id = validate($_POST['user_district']);
						}*/

						$user_email = validate($_POST['user_email']);
						$user_pass = password_hash(validate($_POST['user_pass']), PASSWORD_DEFAULT);
						$role = validate($_POST['user_role']);

						if($role == 'admin') {
							$user_role = 0;
							$user_type = 1;
							$user_district_id = validate($_POST['user_district']);
						} else if($role == 'manager') {
							$user_role = 1;
							$user_type = 0;
							$user_district_id = validate($_POST['user_district']);
							$user_circle_id = validate($_POST['user_circle_id']);
						} else if($role == 'storekeeper') {
							$user_role = 1;
							$user_type = 1;
							$user_district_id = validate($_POST['user_district']);
							$user_circle_id = validate($_POST['user_circle_id']);
						}

						if(isset($_FILES['user_profile_img']['name']) && !empty( $_FILES['user_profile_img']['name'] ) ) {
							$user_profile_img_name = $_FILES['user_profile_img']['name'];
							$user_profile_img_tmp_name = $_FILES['user_profile_img']['tmp_name'];
							$user_profile_img_size = $_FILES['user_profile_img']['size'];
							// Get image file circle
							$user_profile_img_type = pathinfo($user_profile_img_name, PATHINFO_circle);
							if(! in_array($user_profile_img_type, $allowed_image_circle)) {
								echo "<div class='alert alert-danger'>Profile Image must be in PNG, JPG, or JPEG type</div>";
								exit;
							} else if($user_profile_img_size > 2000000) {
								echo "<div class='alert alert-danger'>Profile Image must be less than 2MB in size</div>";
								exit;
							} else {
								$target = 'media/users/'. $time_created.'_'.$user_profile_img_name;
								if(move_uploaded_file($user_profile_img_tmp_name, $target)) {
									$user_profile_img_path = $main_url.$target;
									$user_profile_img = json_encode(array(
										'image_name' => $time_created.'_'.$user_profile_img_name,
										'image_path' => $user_profile_img_path,
										'image_size' => $user_profile_img_size,
										'image_type' => $user_profile_img_type
									));
								} else {
									$user_profile_img = json_encode(array(
										'image_name' => '',
										'image_path' => '',
										'image_size' => '',
										'image_type' => ''
									));
								}
							}
						} else {
							$user_profile_img = json_encode(array(
								'image_name' => '',
								'image_path' => '',
								'image_size' => '',
								'image_type' => ''
							));
						}

						if( !empty( $user_first_name ) && !empty( $user_last_name ) && !empty( $user_email ) && !empty( $user_pass ) ) {

							if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users WHERE user_login='$user_email' && active_status='1' && delete_status='0'")) == 0) {

								$query = mysqli_query($conn, "INSERT INTO users(user_login, user_pass, display_name, circle_id, district, role, type, time_created) VALUES('$user_email', '$user_pass', '$user_display_name', '$user_circle_id', '$user_district_id', '$user_role', '$user_type', '$time_created')");
								$user_id = mysqli_insert_id($conn);
								$user_meta = mysqli_query($conn, "INSERT INTO user_meta(user_id, meta_key, meta_value) VALUES
									('$user_id', 'first_name', '$user_first_name'), 
									('$user_id', 'last_name', '$user_last_name'), 
									('$user_id', 'phone_number', '$user_phone_number'), 
									('$user_id', 'address', '$user_address'), 
									('$user_id', 'email_address', '$user_email'), 
									('$user_id', 'profile_image', '$user_profile_img'), 
									('$user_id', 'user_role', ''), 
									('$user_id', 'session_tokens', '')
								");

								if($query && $user_meta) {
									echo "<div class='alert alert-success'>User Successfully Created</div>";
									echo "<meta http-equiv='refresh' content='1'>";
								} else {
									echo "<div class='alert alert-danger'>Please Try Again</div>";
								}

							} else {
								echo "<div class='alert alert-danger'>Email Already Exist</div>";
							}

						} else {
							echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
						}

					}

					?>

					<form class="form" enctype="multipart/form-data" id="new_user_form" method="post">
						
						<div class="row">
							<div class="col-md-6 mb-3">
								<label>First Name: <span class="text-danger">*</span></label>
								<input type="text" pattern="[A-Za-z]+" value="<?= $user_first_name; ?>" class="form-control" placeholder="Enter First Name" id="user_first_name" name="user_first_name">
								<div id="user_first_name_msg"></div>
							</div>
							<div class="col-md-6 mb-3">
								<label>Last Name: <span class="text-danger">*</span></label>
								<input type="text" pattern="[A-Za-z]+" value="<?= $user_last_name; ?>" class="form-control" placeholder="Enter Last Name" id="user_last_name" name="user_last_name">
								<div id="user_last_name_msg"></div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 mb-3">
								<label>Phone Number: <span class="text-danger">*</span></label>
								<input type="tel" pattern="^0\d{10}$" value="<?= $user_phone_number; ?>" class="form-control" placeholder="Enter Phone Number" id="user_phone_number" name="user_phone_number">
								<div id="user_phone_number_msg"></div>
							</div>
							<div class="col-md-6 md-3">
								<label>Address</label>
								<textarea class="form-control" placeholder="Enter Address" id="user_address" name="user_address"><?= $user_address; ?></textarea>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 mb-3">
								<label>Email: <span class="text-danger">*</span></label>
								<input type="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}" value="<?= $user_email; ?>" class="form-control" placeholder="Enter Email Address" id="user_email" name="user_email">
								<div id="user_email_msg"></div>
							</div>

							<div class="col-md-6 mb-3">
								<label>Password: <span class="text-danger">*</span></label>
								<input type="password" pattern="^(?=.*[A-Z])(?=.*[a-z])(?=.*\d).{8,}$" value="<?= $user_pass; ?>" class="form-control" placeholder="Enter Password" id="user_pass" name="user_pass">
								<div id="user_pass_msg"></div>
							</div>
						</div>

						<div class="row">
							<?php

							if(is_super_admin() || is_admin()) {
							?>
							<div class="col-md-6 mb-3">
								<label>Role: <span class="text-danger">*</span></label>
								<select class="form-control" name="user_role" id="user_role">
									<option value="">Select Role</option>
									<?php

									if(is_super_admin()) {
										echo "<option value='admin'>District Director</option>";
									}

									?>
									<option value="manager">Procurement Officer</option>
									<option value="storekeeper">Storekeeper</option>
								</select>
							</div>
							<?php
							}

							?>
							<div class="col-md-6 mb-3">
								<label>Profile Image:</label>
								<input type="file" accept="image/*" class="d-block" id="user_profile_img" name="user_profile_img">
								<div id="user_profile_img_msg"></div>
							</div>
						</div>

						<?php

						if(is_super_admin() || is_admin()) {
						?>
						<div class="row" id="posting_container">
							<?php

							if(is_super_admin()) {
							?>
							<div class="col-md-6 mb-3" id="district_container">
								<label>District: <span class="text-danger">*</span></label>
								<select class="form-control" id="user_district" name="user_district">
									<option value="">Select District</option>
									<?php

									$district_query = mysqli_query($conn, "SELECT * FROM configurations WHERE type='district'");
									if(mysqli_num_rows($district_query) > 0) {
										while($district_result = mysqli_fetch_assoc($district_query)) {
										?>
										<option value="<?= $district_result['id'] ?>"><?= $district_result['name']; ?></option>
										<?php
										}
									}

									?>
								</select>
							</div>
							<?php
							}

							?>
							<div class="<?php if(is_admin()) { echo 'col-md-12'; } else { echo 'col-md-6'; } ?> mb-3" id="circle_container">
								<label>Circle: <span class="text-danger">*</span></label>
								<select class="form-control" id="user_circle_id" name="user_circle_id">
									<option value="" <?php if($user_circle_id == '') echo 'selected'; ?>>Select circle</option>
								</select>
							</div>
						</div>
						<?php
						}

						?>

						<button class="btn btn-primary" type="submit" name="new_user_form_btn" id="new_user_form_btn">Submit</button>

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

			$('#user_role').on('change', function(){
				var user_role = $('#user_role').val();
				if(user_role == 'admin') {
					$('#district_container').removeClass('col-md-6').addClass('col-md-12');
					$('#circle_container').html('');
				} else {
					$('#district_container').removeClass('col-md-12').addClass('col-md-6');
					$('#circle_container').html(
						'<label>Circle: <span class="text-danger">*</span></label>' + 
						'<select class="form-control" id="user_circle_id" name="user_circle_id">' + 
							'<option value="" <?php if($user_circle_id == '') echo 'selected'; ?>>Select Circle</option>' + 
						'</select>'
					);
					var user_district = $('#user_district').val();

					if(user_district != '' && user_district != 0) {
						$.ajax({
							url: 'ajax.php',
							type: 'POST',
							data: { action:'display_circles', district_id:user_district },
							success: function(result) {
								$('#user_circle_id').html(result);
							}
						});
					}
				}
			});

			$('#user_district').on('change', function(){
				var user_district = $('#user_district').val();

				if(user_district != '' && user_district != 0) {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'display_circles', district_id:user_district },
						success: function(result) {
							$('#user_circle_id').html(result);
						}
					});
				}
			});

			<?php

			if(is_admin()) {
			?>
			$.ajax({
				url: 'ajax.php',
				type: 'POST',
				data: { action:'display_circles', district_id:<?= $user_district; ?> },
				success: function(result) {
					$('#user_circle_id').html(result);
				}
			});
			<?php
			}

			?>

			$('#user_first_name').on('focus', function(){
				$('#user_first_name').removeClass('is-invalid');
				$('#user_first_name_msg').removeClass('invalid-feedback').text('');
			}).focus();

			$('#user_last_name').on('focus', function(){
				$('#user_last_name').removeClass('is-invalid');
				$('#user_last_name_msg').removeClass('invalid-feedback').text('');
			});

			$('#user_email').on('focus', function(){
				$('#user_email').removeClass('is-invalid');
				$('#user_email_msg').removeClass('invalid-feedback').text('');
			});

			$('#user_pass').on('focus', function(){
				$('#user_pass').removeClass('is-invalid');
				$('#user_pass_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_user_form').on('submit', function(e){
				var user_first_name = $('#user_first_name').val();
				var user_last_name = $('#user_last_name').val();
				var user_phone_number = $('#user_phone_number').val();
				var user_address = $('#user_address').val();
				var user_profile_img = $('#user_profile_img');
				var user_circle_id = $('#user_circle_id').val();
				var user_email = $('#user_email').val();
				var user_pass = $('#user_pass').val();
				var bool = 0;

				if(user_first_name == '') {
					$('#user_first_name').addClass('is-invalid');
					$('#user_first_name_msg').addClass('invalid-feedback').text('First Name Required');
					bool = 1;
				} else {
					$('#user_first_name').removeClass('is-invalid');
					$('#user_first_name_msg').removeClass('invalid-feedback').text('');
				}

				if(user_last_name == '') {
					$('#user_last_name').addClass('is-invalid');
					$('#user_last_name_msg').addClass('invalid-feedback').text('Last Name Required');
					bool = 1;
				} else {
					$('#user_last_name').removeClass('is-invalid');
					$('#user_last_name_msg').removeClass('invalid-feedback').text('');
				}

				if(user_email == '') {
					$('#user_email').addClass('is-invalid');
					$('#user_email_msg').addClass('invalid-feedback').text('Email Address Required');
					bool = 1;
				} else {
					$('#user_email').removeClass('is-invalid');
					$('#user_email_msg').removeClass('invalid-feedback').text('');
				}

				if(user_pass == '') {
					$('#user_pass').addClass('is-invalid');
					$('#user_pass_msg').addClass('invalid-feedback').text('Password Required');
					bool = 1;
				} else {
					$('#user_pass').removeClass('is-invalid');
					$('#user_pass_msg').removeClass('invalid-feedback').text('');
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