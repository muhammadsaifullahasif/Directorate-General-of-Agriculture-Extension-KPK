<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Circles';

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
							<a href="circle-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='circles.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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
						if(isset($_GET['filter_district']) && !empty( $_GET['filter_district'] ) ) {
							$search_result_msg .= ' of district <b>'.district_name(mysqli_real_escape_string($conn, trim(strip_tags($_GET['filter_district'])))).'</b>';
						}
						echo "<div class='mb-3'><p>".$search_result_msg."</p></div>";
					}






					if(isset($_GET['action']) && $_GET['action'] == 'delete') {

						if(isset($_GET['id']) && $_GET['id'] != 0 && !empty( $_GET['id'] ) ) {
							$id = trim(strip_tags(mysqli_real_escape_string($conn, $_GET['id'])));

							$delete_query = mysqli_query($conn, "UPDATE circles SET delete_status='1' WHERE id='$id'");
							if($delete_query) {
								echo "<div class='alert alert-success notify-alert'>Circle Successfully Deleted</div>";
								echo "<script>history.pushState({}, '', 'circles.php'); setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							} else {
								echo "<div class='alert alert-danger notify-alert'>Please Try Again</div>";
								echo "<script>setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							}
						} else {
							echo "<script>window.top.location='circles.php';</script>";
						}
					}

					?>
					
					<!-- Account New -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header row align-items-center justify-content-between">
									<div class="col-md-6">
										<form class="form-inline" method="get">
											<input type="hidden" value="filter" name="query">
											<div class="col-4">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_district">
													<option>Select District</option>
													<?php

													$district_query = mysqli_query($conn, "SELECT * FROM configurations WHERE type='district' && active_status='1' && delete_status='0'");
													if(mysqli_num_rows($district_query) > 0) {
														while($district_result = mysqli_fetch_assoc($district_query)) {
															if(isset($_GET['filter_district']) && $_GET['filter_district'] == $district_result['id']) {
																$district_selected = 'selected';
															} else {
																$district_selected = '';
															}
															echo "<option ".$district_selected." value='".$district_result['id']."'>".district_name($district_result['id'])."</option>";
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
													<th>Name</th>
													<th>District</th>
													<th>Manager</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_circles">

												<?php

												if(isset($_GET['query']) && $_GET['query'] == 'filter') {
													$sql_query = "SELECT * FROM circles WHERE delete_status='0'";
													
													if(isset($_GET['filter_district']) && !empty( $_GET['filter_district'] ) ) {
														$sql_query .= " && district='".validate($_GET['filter_district'])."' ";
													}

													if(is_admin()) {
														$sql_query .= " && district='$user_district' ";
													}

													$sql_query .= " ORDER BY name ASC ";

													$query = mysqli_query($conn, $sql_query);
												} else {
													$sql_query = "SELECT * FROM circles WHERE delete_status='0'";
													if(is_admin()) {
														$sql_query .= " && district='$user_district' ";
													}
													$sql_query .= " ORDER BY name ASC ";
													$query = mysqli_query($conn, $sql_query);
												}

												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
														$id = $result['id'];
														$manager_query = mysqli_query($conn, "SELECT id FROM users WHERE circle_id='$id' && role='1' && type='0' && active_status='1' && delete_status='0'");
														if(mysqli_num_rows($manager_query) > 0) {
															$manager_result = mysqli_fetch_assoc($manager_query);
															$circle_manager = user_display_name($manager_result['id']);
														} else {
															$circle_manager = '';
														}
												?>
												<tr>
													<td><?= $i; ?></td>
													<td><?= $result['name']; ?></td>
													<td><a href="?query=filter&filter_district=<?php echo $result['district']; ?>"><?= district_name($result['district']); ?></a></td>
													<td><a href="#"><?= circle_manager($id); ?></a></td>
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
															<a href="circle-edit.php?id=<?= $result['id']; ?>" class="btn btn-primary btn-sm">Edit</a>
															<?php
															if(is_super_admin()) {
															?>
															<a href="circles.php?id=<?= $result['id']; ?>&action=delete" class='btn btn-danger btn-sm delete_circle_btn'>Delete</a>
															<?php
															}
															?>
														</div>
													</td>
												</tr>
												<?php
														$i++;
													}
												} else {
													echo "<tr><td class='text-center' colspan='6'>No Record Found</td></tr>";
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

			$(document).on('click', '.delete_circle_btn', function(){

				if(confirm('Are you sure to delete circle?')) {
					return true;
				} else {
					return false;
				}

			});

		});
	</script>
</body>
</html>