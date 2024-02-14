<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Circle Edit';

	include "head.php";

	if(is_manager() || is_storekeeper()) {
		header('Location: index.php');
	}

	if(isset($_GET['id']) && $_GET['id'] != '' && $_GET['id'] != 0) {
		$id = validate($_GET['id']);

		$query = mysqli_query($conn, "SELECT * FROM circles WHERE id='$id' && active_status='1' && delete_status='0'");
		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
		} else {
			header("location:circles.php");
		}
	} else {
		header("location: circles.php");
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
							<a href="circle-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item"><a href="circles.php">Circles</a></li>
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

					if(isset($_POST['new_circle_form_btn'])) {

						$circle_name = ucwords(validate($_POST['circle_name']));
						if(isset($_POST['circle_district'])) {
							$circle_district = validate($_POST['circle_district']);
						} else {
							$circle_district = $user_district;
						}
						$circle_phone_number = validate($_POST['circle_phone_number']);
						$circle_manager_id = validate($_POST['circle_manager_id']);

						if( !empty( $circle_name ) && !empty( $circle_district ) && !empty( $circle_manager_id ) ) {

							/*if(isset($_POST['allot_budget']) && !empty($_POST['allot_budget'])) {
								$allot_budget = validate($_POST['allot_budget']);
								$finance_id = finance_id($id);
								$finance_amount = finance_amount($id) + $allot_budget;

								$update_finance = mysqli_query($conn, "UPDATE finance SET amount='$finance_amount' WHERE id='$finance_id'");
								$transaction_query = mysqli_query($conn, "INSERT INTO transactions(user_id, circle_id, finance_id, amount, type, trans_flow, time_created) VALUES('$user_id', '$id', '$finance_id', '$allot_budget', '8', '1', '$time_created')");
								$transaction_id = mysqli_insert_id($conn);
								$transaction_meta_query = mysqli_query($conn, "INSERT INTO transaction_meta(transaction_id, meta_key, meta_value) VALUES('$transaction_id', 'allot_budget', '$allot_budget')");
							}*/

							$sql_query = "
								UPDATE circles SET name='$circle_name', district='$circle_district' WHERE id='$id';
								UPDATE users SET circle_id='$id' WHERE id='$circle_manager_id';
								UPDATE circle_meta SET meta_value= CASE
									WHEN meta_key='circle_phone_number' THEN '$circle_phone_number'
									ELSE `meta_value`
									END
								WHERE circle_id='$id'";

							$query = mysqli_multi_query($conn, $sql_query);

							if($query) {
								echo "<div class='alert alert-success'>circle Successfully Updated</div>";
								echo "<meta http-equiv='refresh' content='1'>";
							} else {
								echo "<div class='alert alert-danger'>Please Try Again</div>";
							}

						} else {
							echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
						}

					}

					?>

					<form class="form" enctype="multipart/form-data" id="new_circle_form" method="post">
						
						<div class="row">
							<!-- <div class="col-md-<?= ( is_super_admin() ? '4' : '6' ); ?> mb-3"> -->
							<div class="col-md-6 mb-3">
								<label>Name: <span class="text-danger">*</span></label>
								<input type="text" pattern="[A-Za-z]+" value="<?= $result['name']; ?>" class="form-control" placeholder="Enter Circle Name" id="circle_name" name="circle_name">
								<div id="circle_name_msg"></div>
							</div>
							<!-- <div class="col-md-<?= ( is_super_admin() ? '4' : '6' ); ?> mb-3"> -->
							<div class="col-md-6 mb-3">
								<label>Phone Number:</label>
								<input type="tel" pattern="^0\d{10}$" value="<?= circle_meta($result['id'], 'circle_phone_number'); ?>" class="form-control" placeholder="Enter circle Phone Number" id="circle_phone_number" name="circle_phone_number">
								<div id="circle_phone_number_msg"></div>
							</div>
							<?php
							if(is_super_admin()) {
							?>
							<!-- <div class="col-md-4 mb-3">
								<label>Allot Budget:</label>
								<input type="text" class="form-control" placeholder="Enter Allot Budget" id="allot_budget" name="allot_budget">
								<div id="allot_budget_msg"></div>
								<small class="form-text text-muted">Leave Blank if you don't want to change it.</small>
							</div> -->
							<?php
							}
							?>
						</div>

						<div class="row">
							<div class="col-md-6 mb-3">
								<label>Circle Manager: <span class="text-danger">*</span></label>
								<select class="form-control" id="circle_manager_id" name="circle_manager_id">
									<option value="" <?php if(circle_meta($result['id'], 'circle_manager_id') == '') echo 'selected'; ?>>Select Circle Manager</option>
									<?php

									$circle_manager_query = mysqli_query($conn, "SELECT id, display_name, circle_id FROM users WHERE ( (role=0 && type=1) || (role=1 && type=0) || (role=1 && type=1) ) && active_status='1' && delete_status='0'");

									if(mysqli_num_rows($circle_manager_query) > 0) {
										while($circle_manager_result = mysqli_fetch_assoc($circle_manager_query)) {
											if($circle_manager_result['circle_id'] == $id) { $selected = 'selected'; } else { $selected = ''; }
											echo "<option ".$selected." value='".$circle_manager_result['id']."'>".$circle_manager_result['display_name']."</option>";
										}
									}

									?>
								</select>
							</div>
							<?php
							if(is_super_admin()) {
							?>
							<div class="col-md-6 mb-3">
								<label>Circle district: <span class="text-danger">*</span></label>
								<select class="form-control" id="circle_district" name="circle_district">
									<option value="">Select district</option>
									<?php

									$circle_district_query = mysqli_query($conn, "SELECT * FROM configurations WHERE type='district' && delete_status='0' && ( active_status='1' ||  name='{$result['district']}' ) ORDER BY name DESC");
									if(mysqli_num_rows($circle_district_query) > 0) {
										while($circle_district_result = mysqli_fetch_assoc($circle_district_query)) {
											?>
											<option <?php if($circle_district_result['id'] == $result['district']) echo 'selected'; ?> value="<?php echo $circle_district_result['id'] ?>"><?php echo $circle_district_result['name'] ?></option>
											<?php
										}
									}

									?>
								</select>
								<div id="circle_district_msg"></div>
							</div>
							<?php
							} else {
							?>
							<div class="col-md-6 mb-3">
								<label>Opening Balance:</label>
								<input type="text" pattern="[0-9]+" class="form-control" placeholder="Enter Opening Balance" id="opening_balance" name="opening_balance">
								<div id="opening_balance_msg"></div>
								<small class="form-text text-muted">Leave Blank if you don't want to change it.</small>
							</div>
							<?php
							}
							?>
						</div>

						<button class="btn btn-primary" type="submit" name="new_circle_form_btn" id="new_circle_form_btn">Submit</button>

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

			$('#circle_name').on('focus', function(){
				$('#circle_name').removeClass('is-invalid');
				$('#circle_name_msg').removeClass('invalid-feedback').text('');
			}).focus();

			$('#circle_district').on('focus', function(){
				$('#circle_district').removeClass('is-invalid');
				$('#circle_district_msg').removeClass('invalid-feedback').text('');
			});

			$('#circle_manager_id').on('focus', function(){
				$('#circle_manager_id').removeClass('is-invalid');
				$('#circle_manager_id_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_circle_form').on('submit', function(e){
				var circle_name = $('#circle_name').val();
				var circle_district = $('#circle_district').val();
				var circle_manager_id = $('#circle_manager_id').val();
				var bool = 0;

				if(circle_name == '') {
					$('#circle_name').addClass('is-invalid');
					$('#circle_name_msg').addClass('invalid-feedback').text('Circle Name Required');
					// return false;
					bool = 1;
				} else {
					$('#circle_name').removeClass('is-invalid');
					$('#circle_name_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if(circle_district == '') {
					$('#circle_district').addClass('is-invalid');
					$('#circle_district_msg').addClass('invalid-feedback').text('Circle district Required');
					// return false;
					bool = 1;
				} else {
					$('#circle_district').removeClass('is-invalid');
					$('#circle_district_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if(circle_manager_id == '') {
					$('#circle_manager_id').addClass('is-invalid');
					$('#circle_manager_id_msg').addClass('invalid-feedback').text('Circle Manager Required');
					bool = 1;
				} else {
					$('#circle_manager_id').removeClass('is-invalid');
					$('#circle_manager_id_msg').removeClass('invalid-feedback').text('');
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