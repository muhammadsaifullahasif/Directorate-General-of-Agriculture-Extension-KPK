<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Users';

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
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='users.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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

					if(isset($_GET['query'])) {
						$search_result_msg = 'Search Result';
						if(isset($_GET['filter_circle']) && !empty( $_GET['filter_circle'] ) ) {
							$search_result_msg .= ' of circle <b>'.circle_name(validate($_GET['filter_circle'])).'</b>';
						}
						if(isset($_GET['filter_role']) && !empty( $_GET['filter_role'] ) ) {
							$search_result_msg .= ' of role';
							if($_GET['filter_role'] == 'administrator') {
								$search_result_msg .= ' <b>Administrator</b>';
							} else if($_GET['filter_role'] == 'circle_manager') {
								$search_result_msg .= ' <b>circle Manager</b>';
							} else if($_GET['filter_role'] == 'store_keeper') {
								$search_result_msg .= ' <b>Storekeeper</b>';
							} else if($_GET['filter_role'] == 'super_admin') {
								$search_result_msg .= ' <b>Super Admin</b>';
							}
						}
						echo "<div class='mb-3'><p>".$search_result_msg."</p></div>";
					}





					if(isset($_GET['action']) && $_GET['action'] == 'delete') {

						if(isset($_GET['id']) && $_GET['id'] != 0 && !empty( $_GET['id'] ) ) {
							$id = validate($_GET['id']);

							$delete_query = mysqli_query($conn, "UPDATE users SET delete_status='1' WHERE id='$id'");
							if($delete_query) {
								echo "<div class='alert alert-success notify-alert'>User Successfully Deleted</div>";
								echo "<script>history.pushState({}, '', 'users.php'); setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							} else {
								echo "<div class='alert alert-danger notify-alert'>Please Try Again</div>";
								echo "<script>setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							}
						} else {
							echo "<script>window.top.location='users.php';</script>";
						}
					}

					?>
					
					<!-- Account New -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header row align-items-center justify-content-between">
									<div class="col-md-6">
										<form class="form-inline">
											<input type="hidden" value="filter" style="display: none;" name="query">
											<?php

											if(is_super_admin() || is_admin()) {
											?>
											<div class="col-4">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_circle">
													<option value="">Select Circle</option>
													<?php

													$circle_sql = "SELECT * FROM circles WHERE delete_status='0'";
													if(is_admin()) {
														$circle_sql .= " && district='$user_district' ";
													} else if(is_manager()) {
														$circle_sql .= " && circle_id='$circle_id' ";
													}
													$circle_query = mysqli_query($conn, $circle_sql);
													if(mysqli_num_rows($circle_query) > 0) {
														while($circle_result = mysqli_fetch_assoc($circle_query)) {
															echo "<option value='".$circle_result['id']."'>".circle_name($circle_result['id'])."</option>";
														}
													}

													?>
												</select>
											</div>
											<div class="col-4">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_role">
													<option value="">Select Role</option>
													<?php

													if(is_super_admin()) {
														echo "<option value='administrator'>District Director</option>";
														echo "<option value='administrator'>Director Seed KPK</option>";
													}

													?>
													<option value="circle_manager">Procurement Officer</option>
													<option value="storekeeper">Storekeeper</option>
												</select>
											</div>
											<div class="col">
												<button class="btn btn-outline-dark btn-sm" type="submit">Apply</button>
											</div>
											<?php
											}

											?>
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
													<th>Email</th>
													<th>Circle</th>
													<th>District</th>
													<th>Role</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_users">

												<?php

												if(isset($_GET['query']) && $_GET['query'] == 'filter') {
													$sql_query = "SELECT * FROM users WHERE delete_status='0'";
													if(isset($_GET['filter_circle']) && !empty( $_GET['filter_circle'] ) ) {
														$sql_query .= " && circle_id='".validate($_GET['filter_circle'])."' ";
													}
													if(isset($_GET['filter_role']) && !empty( $_GET['filter_role'] ) ) {
														if($_GET['filter_role'] == 'administrator') {
															if(is_super_admin())
																$sql_query .= " && role='0' && type='1' ";
														} else if($_GET['filter_role'] == 'circle_manager') {
															$sql_query .= " && role='1' && type='0' ";
														} else if($_GET['filter_role'] == 'store_keeper') {
															$sql_query .= " && role='1' && type='1' ";
														} else if($_GET['filter_role'] == 'super_admin') {
															if(is_super_admin())
																$sql_query .= " && role='0' && type='0' ";
														}
													}

													if(is_admin()) {
														$sql_query .= " && city='$user_city' && id!='$user_id' ";
													}

													if(is_manager()) {
														$sql_query .= " && circle_id='$circle_id' && id!='$user_id' ";
													}

													$sql_query .= " ORDER BY display_name ASC ";

													$query = mysqli_query($conn, $sql_query);
												} else {
													$sql_query = "SELECT * FROM users WHERE delete_status='0'";

													if(is_admin()) {
														$sql_query .= " && district='$user_district' && id!='$user_id' ";
													}
													if(is_manager()) {
														$sql_query .= " && circle_id='$circle_id' && id!='$user_id' ";
													}

													$sql_query .= " ORDER BY display_name ASC ";

													$query = mysqli_query($conn, $sql_query);
												}

												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
												?>
												<tr>
													<td><?= $i; ?></td>
													<td><?= user_display_name($result['id']); ?></td>
													<td><a href="mailto:<?= user_meta($result['id'], 'email_address') ?>"><?= user_meta($result['id'], 'email_address'); ?></a></td>
													<td><a href="#"><?php if(!is_null($result['circle_id'])) { echo circle_name($result['circle_id']); } ?></a></td>
													<td><?php if(!is_null($result['district'])) { echo district_name($result['district']); } ?></td>
													<td><?= user_role($result['role'], $result['type']); ?></td>
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
															<a href="user-edit.php?id=<?= $result['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
															<?php

															if($result['role'] == 0 && $result['type'] == 0) {
															} else {
																echo "<a href='users.php?id=".$result['id']."&action=delete' class='btn btn-danger btn-sm delete_user_btn'>Delete</a>";
															}

															?>
														</div>
													</td>
												</tr>
												<?php
														$i++;
													}
												} else {
													echo "<td><td colspan='8' class='text-center'>No Record Found</td></tr>";
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

	<script type="text/javascript">
		$(document).ready(function(){

			$(document).on('click', '.delete_user_btn', function(){

				if(confirm('Are you sure to delete user?')) {
					return true;
				} else {
					return false;
				}

			});

		});
	</script>
</body>
</html>