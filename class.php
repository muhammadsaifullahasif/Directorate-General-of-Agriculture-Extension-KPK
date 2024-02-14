<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Class';

	include "head.php";

	if(is_admin() || is_manager() || is_storekeeper()) {
		header('Location: index.php');
	}

	if(isset($_POST['new_class_form_btn'])) {
		$new_class_collapse_class = 'show';
	} else {
		$new_class_collapse_class = '';
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
							<a data-toggle="collapse" href="#new_class_container" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
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

					<div class="collapse <?= $new_class_collapse_class; ?>" id="new_class_container">
						<div class="card card-body">

							<?php

							if(isset($_POST['new_class_form_btn'])) {
								$new_class_name = ucwords(validate($_POST['new_class_name']));

								if( !empty($new_class_name) ) {

									if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stock_class WHERE class_name='$new_class_name' && delete_status='0'")) == 0) {
										$query = mysqli_query($conn, "INSERT INTO stock_class(user_id, class_name, time_created) VALUES('$user_id', '$new_class_name', '$time_created')");

										if($query) {
											echo "<div class='alert alert-success' id='new_class_form_alert'>Class Successfully Created</div>";
											echo "<script>setTimeout(function(){ $('#new_class_container').removeClass('show'); $('#new_class_name').val(''); $('#new_class_form_alert').remove(); }, 1000)</script>";
										} else {
											echo "<div class='alert alert-danger'>Please try again</div>";
										}
									} else {
										echo "<div class='alert alert-danger'>".$new_class_name." already exist</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							?>

							<form method="post" id="new_class_form" class="form">
								
								<div class="mb-3">
									<label>Class Name:</label>
									<input type="text" pattern="[A-Za-z]+" class="form-control" placeholder="Enter Class Name" id="new_class_name" name="new_class_name">
									<div id="new_class_name_msg"></div>
								</div>
								<button class="btn btn-primary" class="submit" id="new_class_form_btn" name="new_class_form_btn">Submit</button>

							</form>
						</div>
					</div>

					<?php

					if(isset($_GET['action']) && $_GET['action'] == 'edit') {

						$edit_class_id = validate($_GET['id']);

					?>

					<div class="collapse show" id="edit_class_container">
						<div class="card card-body">

							<?php

							if(isset($_POST['edit_class_form_btn'])) {
								$edit_class_name = ucwords(validate($_POST['edit_class_name']));

								if( !empty($edit_class_name) ) {

									$query = mysqli_query($conn, "UPDATE stock_class SET class_name='$edit_class_name' WHERE id='$edit_class_id'");

									if($query) {
										echo "<div class='alert alert-success' id='edit_class_form_alert'>Class Successfully Updated</div>";
										echo "<script>history.pushState({}, '', 'class.php'); setTimeout(function(){ $('#edit_class_container').removeClass('show'); $('#edit_class_name').val(''); $('#edit_class_form_alert').remove(); }, 1000)</script>";
									} else {
										echo "<div class='alert alert-danger'>Please try again</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							$edit_class_query = mysqli_query($conn, "SELECT * FROM stock_class WHERE id='$edit_class_id' && delete_status='0'");
							if(mysqli_num_rows($edit_class_query) > 0) {
								$edit_class_result = mysqli_fetch_assoc($edit_class_query);

							?>

							<form method="post" id="edit_class_form" class="form">
								
								<div class="mb-3">
									<label>Class Name:</label>
									<input type="text" pattern="[A-Za-z]+" class="form-control" value="<?= $edit_class_result['class_name']; ?>" placeholder="Enter Class Name" id="edit_class_name" name="edit_class_name">
									<div id="edit_class_name_msg"></div>
								</div>
								<button class="btn btn-primary" class="submit" id="edit_class_form_btn" name="edit_class_form_btn">Submit</button>

								<button class="btn btn-outline-dark edit_class_container_close" type="button" data-toggle="collapse" href="#edit_class_container">Cancel</button>

							</form>

							<?php

							}

							?>
						</div>
					</div>

					<?php

					}

					if(isset($_GET['action']) && $_GET['action'] == 'delete') {

						if(isset($_GET['id']) && $_GET['id'] != 0 && !empty($_GET['id'])) {
							$id = validate($_GET['id']);

							$delete_query = mysqli_query($conn, "UPDATE stock_class SET delete_status='1' WHERE id='$id'");
							if($delete_query) {
								echo "<div class='alert alert-success notify-alert'>Class Successfully Deleted</div>";
								echo "<script>history.pushState({}, '', 'class.php'); setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							} else {
								echo "<div class='alert alert-danger notify-alert'>Please Try Again</div>";
								echo "<script>setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							}
						} else {
							echo "<script>window.top.location='class.php';</script>";
						}
					}

					?>
					
					<!-- Account New -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped table-hover table-sm" id="table">
											<thead>
												<tr>
													<th>#</th>
													<th>Name</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_class">

												<?php

												$query = mysqli_query($conn, "SELECT * FROM stock_class WHERE delete_status='0'");

												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
												?>
												<tr>
													<td><?= $i; ?></td>
													<td><?= $result['class_name']; ?></td>
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
															<a href="class.php?id=<?= $result['id']; ?>&action=edit" class="btn btn-primary btn-sm">Edit</a>
															<a href='class.php?id=<?= $result['id']; ?>&action=delete' class='btn btn-danger btn-sm delete_class_btn'>Delete</a>
														</div>
													</td>
												</tr>
												<?php
														$i++;
													}
												} else {
													echo "<tr><td colspan='4' class='text-center'>No Record Found</td></tr>";
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

			$(document).on('click', '.edit_class_container_close', function(){
				history.pushState({}, '', 'class.php');
			});

			$(document).on('click', '.delete_class_btn', function(){

				if(confirm('Are you sure to delete class?')) {
					return true;
				} else {
					return false;
				}

			});

			$('#new_class_name').on('focus', function(){
				$('#new_class_name').removeClass('is-invalid');
				$('#new_class_name_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_class_form').on('submit', function(){
				var new_class_name = $('#new_class_name').val();

				if(new_class_name == '') {
					$('#new_class_name').addClass('is-invalid');
					$('#new_class_name_msg').addClass('invalid-feedback').text('Class Name Required');
					return false;
				} else {
					$('#new_class_name').removeClass('is-invalid');
					$('#new_class_name_msg').removeClass('invalid-feedback').text('');
					return true;
				}
			});

			$('#edit_class_name').on('focus', function(){
				$("#edit_class_name").removeClass('is-invalid');
				$('#edit_class_name_msg').removeClass('invalid-feedback').text('');
			});

			$('#edit_class_form').on('submit', function(){
				var edit_class_name = $('#edit_class_name').val();

				if(edit_class_name == '') {
					$('#edit_class_name').addClass('is-invalid');
					$('#edit_class_name_msg').addClass('invalid-feedback').text('Class Name Required');
					return false;
				} else {
					$('#edit_class_name').removeClass('is-invalid');
					$('#edit_class_name_msg').removeClass('invalid-feedback').text('');
					return true;
				}
			});

		});
	</script>
</body>
</html>