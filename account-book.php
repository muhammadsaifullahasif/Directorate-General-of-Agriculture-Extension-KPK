<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Account Book';

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
							<a href="expense-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add Expense</a>
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='account-book.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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
						if(isset($_GET['filter_type']) && $_GET['filter_type'] != '' ) {
							$search_result_msg .= ' of Type';
							if($_GET['filter_type'] == 1) {
								$search_result_msg .= ' <b>Credit</b>';
							} else if($_GET['filter_type'] == 0) {
								$search_result_msg .= ' <b>Debit</b>';
							}
						}
						echo "<div class='mb-3'><p>".$search_result_msg."</p></div>";
					}





					if(isset($_GET['action']) && $_GET['action'] == 'delete') {

						$id = validate($_GET['id']);
						if($id != '' && $id != 0) {

							$query = mysqli_query($conn, "SELECT * FROM transactions WHERE id='$id' && active_status!='0' && delete_status='0'");

							if(mysqli_num_rows($query) > 0) {

								$result = mysqli_fetch_assoc($query);
								$finance_id = $result['finance_id'];
								$finance_amount = finance_amount($result['circle_id']);

								if($result['active_status'] == 1) {
									if($result['trans_flow'] == 0) {
										$finance_amount += $result['amount'];
									} else if($result['trans_flow'] == 1) {
										$finance_amount -= $result['amount'];
									}
									$update_finance = mysqli_query($conn, "UPDATE finance SET amount='$finance_amount' WHERE id='$finance_id'");
								}

								$delete_query = mysqli_query($conn, "UPDATE transactions SET delete_status='1' WHERE id='$id'");

								if($delete_query) {
									echo "<div class='alert alert-success notify-alert'>Expense Successfully Deleted</div>";
									echo "<script>history.pushState({}, '', 'account-book.php'); setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
								} else {
									echo "<div class='alert alert-danger notify-alert'>Please Try Again</div>";
									echo "<script>setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
								}

							} else {
								echo "<div class='alert alert-danger notify-alert'>Expense Not Found</div>";
								echo "<script>history.pushState({}, '', 'account-book.php'); setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							}

						} else {
							echo "<script>window.top.location='account-book.php';</script>";
						}

					}

					?>
					
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header row align-items-center justify-content-between">
									<div class="col-md-6">
										<form class="form-inline" method="get">
											<input type="text" style="display: none;" value="filter" name="query">
											<div class="col-4">
												<select class="form-control w-100 form-control-sm form-control-border" id="filter_type" name="filter_type">
													<option value="">Select Action</option>
													<option value="1" <?php if(isset($_GET['filter_type']) && $_GET['filter_type'] == '1') { echo 'selected'; } ?>>Credit</option>
													<option value="0" <?php if(isset($_GET['filter_type']) && $_GET['filter_type'] == '0') { echo 'selected'; } ?>>Debit</option>
												</select>
											</div>
											<?php
											if(is_super_admin() || is_admin()) {
											?>
											<div class="col-4">
												<select class="form-control w-100 form-control-sm form-control-border" id="filter_circle" name="filter_circle">
													<option value="">Select AO Circle</option>
													<?php

													$circle_sql = "SELECT * FROM circles WHERE active_status='1' && delete_status='0'";
													if(is_admin()) {
														$circle_sql .= " && city='$user_city' ";
													}
													$circle_sql .= " ORDER BY name ASC ";
													$circle_query = mysqli_query($conn, $circle_sql);
													if(mysqli_num_rows($circle_query) > 0) {
														while($circle_result = mysqli_fetch_assoc($circle_query)) {
															if(isset($_GET['filter_circle']) && $_GET['filter_circle'] == $circle_result['id']) {
																$circle_selected = 'selected';
															} else {
																$circle_selected = '';
															}
															echo "<option ".$circle_selected." value='".$circle_result['id']."'>".$circle_result['name']."</option>";
														}
													}

													?>
												</select>
											</div>
											<?php
											}
											?>
											<div class="col">
												<button class="btn btn-outline-dark btn-sm" type="submit">Apply</button>
											</div>
										</form>
									</div>
									<div class="col-md-6"></div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped table-hover table-sm" id="table">
											<thead>
												<tr>
													<th>Source/Destination</th>
													<th>Type</th>
													<th>Amount</th>
													<th>Flow</th>
													<th>Date</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_expenses">
												<?php

												if(isset($_GET['query']) && $_GET['query'] == 'filter') {

													$sql_query = "SELECT * FROM transactions WHERE active_status!='0' && delete_status='0'";

													if(isset($_GET['filter_circle']) && !empty($_GET['filter_circle'])) {

														$filter_circle = validate($_GET['filter_circle']);

														$sql_query .= " && circle_id='$filter_circle' ";

													}

													if(isset($_GET['filter_type']) && $_GET['filter_type'] != '') {
														$filter_type = validate($_GET['filter_type']);

														$sql_query .= " && trans_flow='$filter_type' ";

													}

													$sql_query .= " ORDER BY time_created DESC ";

													$query = mysqli_query($conn, $sql_query);

												} else {

													$sql_query = "SELECT * FROM transactions WHERE active_status!='0' && delete_status='0'";

													if(is_manager() || is_storekeeper()) {
														$sql_query .= " circle_id='$circle_id' ";
													}

													$sql_query .= " ORDER BY time_created DESC ";

													$query = mysqli_query($conn, $sql_query);

												}

												if(mysqli_num_rows($query) > 0) {

													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
													?>
													<tr>
														<td><?= circle_name($result['circle_id']); ?></td>
														<td><?= transaction_type($result['type']); ?></td>
														<td><?= 'Rs: '.$result['amount'] ?></td>
														<td>
															<?php

															if($result['trans_flow'] == 0) {
																echo "<span class='badge badge-danger'>Debit</span>";
															} else {
																echo "<span class='badge badge-success'>Credit</span>";
															}

															?>
														</td>
														<td><?= date('d-m-Y', $result['time_created']); ?></td>
														<td>
															<div class="btn-group">
																<?php
																if($result['type'] == 3 || $result['type'] == 5) {
																} else {
																?>
																<a class="btn btn-primary btn-sm" href="expense-edit.php?id=<?= $result['id'] ?>"><i class="fas fa-edit"></i></a>
																<a class="btn btn-danger btn-sm delete_expense_btn" href="account-book.php?id=<?= $result['id'] ?>&action=delete"><i class="fas fa-trash"></i></a>
																<?php
																}
																?>
															</div>
														</td>
													</tr>
													<?php
														$i++;
													}

												}

												?>
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
	<script type="text/javascript">
		$(document).ready(function(){

			$(document).on('click', '.delete_expense_btn', function(){

				if(confirm('Are you sure to delete expense?')) {
					return true;
				} else {
					return false;
				}

			});

		});
	</script>
</body>
</html>