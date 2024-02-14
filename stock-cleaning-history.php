<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Stock Cleaning History';

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
							<a href="stock-cleaning-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
							<button class="btn btn-outline-dark btn-sm mb-3 ml-2" data-toggle="modal" data-target="#filter_modal">Advance Filter</button>
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='stock-cleaning-history.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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
						if(isset($_GET['filter_from_date']) && !empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && !empty( $_GET['filter_to_date'] ) ) {
							$search_result_msg .= ' from <b>'.date('d-M-Y', strtotime(validate($_GET['filter_from_date']))).'</b> to <b>'.date('d-M-Y', strtotime(validate($_GET['filter_to_date']))).'</b>';
						} else if(isset($_GET['filter_from_date']) && !empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && empty( $_GET['filter_to_date'] ) ) {
							$search_result_msg .= 'from <b>'.date('d-M-Y', strtotime(validate($_GET['filter_from_date']))).'</b> till today';
						} else if(isset($_GET['filter_from_date']) && empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && !empty( $_GET['filter_to_date'] ) ) {
							$search_result_msg .= ' till <b>'.date('d-M-Y', strtotime(validate($_GET['filter_to_date']))).'</b>';
						}
						if(isset($_GET['filter_crop']) && !empty( $_GET['filter_crop'] ) ) {
							$search_result_msg .= ' & crop <b>'.stock_crop(validate($_GET['filter_crop'])).'</b>';
						}
						if(isset($_GET['filter_variety']) && !emtpy( $_GET['filter_variety'] ) ) {
							$search_result_msg .= ' & variety <b>'.stock_variety(validate($_GET['filter_variety'])).'</b>';
						}
						if(isset($_GET['filter_class']) && !empty( $_GET['filter_class'] ) ) {
							$search_result_msg .= ' & class <b>'.stock_class(validate($_GET['filter_class'])).'</b>';
						}
						echo "<div class='mb-3'><p>".$search_result_msg."</p></div>";
					}






					if(isset($_GET['action']) && $_GET['action'] == 'delete') {

						if(isset($_GET['id']) && $_GET['id'] != 0 && !empty( $_GET['id'] ) ) {
							$id = validate($_GET['id']);

							$delete_query = mysqli_query($conn, "UPDATE stocks SET delete_status='1' WHERE id='$id'");
							if($delete_query) {
								echo "<div class='alert alert-success notify-alert'>Stock Successfully Deleted</div>";
								echo "<script>history.pushState({}, '', 'stock.php'); setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							} else {
								echo "<div class='alert alert-danger notify-alert'>Please Try Again</div>";
								echo "<script>setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
							}
						} else {
							echo "<script>window.top.location='stock.php';</script>";
						}
					}

					?>

					<div class="modal fade" id="filter_modal">
						<div class="modal-dialog modal-lg">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-heading">Advance Filter</h5>
									<a href="#" class="close" data-dismiss="modal">&times;</a>
								</div>
								<div class="modal-body">
									<form class="form" method="get">
										<input type="text" style="display: none;" value="filter" name="query">

										<div class="row">
											
											<div class="col-md-6 mb-3">
												<label>From Date:</label>
												<input type="date" value="<?php if(isset($_GET['filter_from_date']) && $_GET['filter_from_date']) echo date('Y-m-d', strtotime(validate($_GET['filter_from_date']))); ?>" name="filter_from_date" id="filter_from_date" class="form-control" placeholder="Enter From Date">
											</div>

											<div class="col-md-6 mb-3">
												<label>To Date:</label>
												<input type="date" value="<?php if(isset($_GET['filter_to_date']) && $_GET['filter_to_date']) echo date('Y-m-d', strtotime(validate($_GET['filter_to_date']))); ?>" name="filter_to_date" id="filter_to_date" class="form-control" placeholder="Enter To Date">
											</div>

										</div>
										
										<div class="row">
											
											<div class="col-md-4 mb-3">
												<label>Stock Crop:</label>
												<select class="form-control filter_crop" name="filter_crop">
													<option value="">Select Stock Crop</option>
													<?php

													$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_crop_query) > 0) {
														while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
															if(isset($_GET['filter_crop']) && $_GET['filter_crop'] == $stock_crop_result['id']) {
																$filter_crop_selected = 'selected';
															} else {
																$filter_crop_selected = '';
															}
															echo "<option ".$filter_crop_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['crop']."</option>";
														}
													}

													?>
												</select>
											</div>

											<div class="col-md-4 mb-3">
												<label>Variety:</label>
												<select class="form-control filter_variety" id="filter_variety" name="filter_variety">
													<option value="">Select Variety</option>
													<?php

													if(isset($_GET['filter_crop']) && !empty( $_GET['filter_crop'] ) ) {
														$filter_crop = validate($_GET['filter_crop']);

														$variety_query = mysqli_query($conn, "SELECT * FROM stock_variety WHERE stock_crop_id='$filter_crop' && active_status='1' && delete_status='0'");
														if(mysqli_num_rows($variety_query) > 0) {
															while($variety_result = mysqli_fetch_assoc($variety_query)) {
																if(isset($_GET['filter_variety']) && $_GET['filter_variety'] == $variety_result['id']) {
																	$filter_variety_selected = 'selected';
																} else {
																	$filter_variety_selected = '';
																}
																echo "<option ".$filter_variety_selected." value='".$variety_result['id']."'>".$variety_result['variety']."</option>";
															}
														}

													}

													?>
												</select>
											</div>

											<div class="col-md-4 mb-3">
												<label>Class:</label>
												<select class="form-control" id="filter_class" name="filter_class">
													<option value="">Select Class</option>
													<?php

													$stock_class_query = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_class_query) > 0) {
														while($stock_class_result = mysqli_fetch_assoc($stock_class_query)) {
															if(isset($_GET['filter_class']) && $_GET['filter_class'] == $stock_class_result['id']) {
																$filter_class_selected = 'selected';
															} else {
																$filter_class_selected = '';
															}
															echo "<option ".$filter_class_selected." value='".$stock_class_result['id']."'>".$stock_class_result['class_name']."</option>";
														}
													}

													?>
												</select>
											</div>

										</div>

										<button type="submit" class="btn btn-primary" name="filter_form_btn" id="filter_form_btn">Apply Filter</button>

									</form>
								</div>
							</div>
						</div>
					</div>
					
					<!-- Account New -->
					<div class="row">
						<div class="col-12">
							<div class="card">
								<div class="card-header row align-items-center justify-content-between">
									<div class="col-md-9">
										<form class="form-inline">
											<input type="hidden" style="display: none;" value="filter" name="query">

											<div class="col-2">
												<select class="form-control w-100 form-control-sm form-control-border filter_crop" name="filter_crop">
													<option value="">Stock Crop</option>
													<?php

													$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_crop_query) > 0) {
														while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
															if(isset($_GET['filter_crop']) && $_GET['filter_crop'] == $stock_crop_result['id']) {
																$filter_crop_selected = 'selected';
															} else {
																$filter_crop_selected = '';
															}
															echo "<option ".$filter_crop_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['crop']."</option>";
														}
													}

													?>
												</select>
											</div>
											<div class="col-2">
												<select class="form-control w-100 form-control-sm form-control-border filter_variety" name="filter_variety">
													<option value="">Variety</option>
													<?php

													if(isset($_GET['filter_crop']) && !empty( $_GET['filter_crop'] ) ) {
														$filter_crop = validate($_GET['filter_crop']);

														$variety_query = mysqli_query($conn, "SELECT * FROM stock_variety WHERE stock_crop_id='$filter_crop' && active_status='1' && delete_status='0'");
														if(mysqli_num_rows($variety_query) > 0) {
															while($variety_result = mysqli_fetch_assoc($variety_query)) {
																if(isset($_GET['filter_variety']) && $_GET['filter_variety'] == $variety_result['id']) {
																	$filter_variety_selected = 'selected';
																} else {
																	$filter_variety_selected = '';
																}
																echo "<option ".$filter_variety_selected." value='".$variety_result['id']."'>".$variety_result['variety']."</option>";
															}
														}

													}

													?>
												</select>
											</div>
											<div class="col-2">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_class">
													<option value="">Class</option>
													<?php

													$stock_class_query = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_class_query) > 0) {
														while($stock_class_result = mysqli_fetch_assoc($stock_class_query)) {
															if(isset($_GET['filter_class']) && $_GET['filter_class'] == $stock_class_result['id']) {
																$filter_class_selected = 'selected';
															} else {
																$filter_class_selected = '';
															}
															echo "<option ".$filter_class_selected." value='".$stock_class_result['id']."'>".$stock_class_result['class_name']."</option>";
														}
													}

													?>
												</select>
											</div>
											<div class="col">
												<button class="btn btn-outline-dark btn-sm" type="submit" name="filter_form_btn">Apply</button>
											</div>
										</form>
									</div>
									<div class="col-md-3">
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table class="table table-striped table-hover table-sm" id="table">
											<thead>
												<tr>
													<th>Crop</th>
													<th>Lot Number</th>
													<th>Variety</th>
													<th>Class</th>
													<th>Circle</th>
													<th>Processing Stock</th>
													<th>Grade 1</th>
													<th>Small Grains</th>
													<th>Gundi</th>
													<th>Broken</th>
													<th>Straw</th>
													<th>Dust</th>
													<th>Other</th>
													<th>%</th>
													<th>Date</th>
												</tr>
											</thead>
											<tbody id="display_stocks">

												<?php

												if(isset($_GET['query']) && $_GET['query'] == 'filter') {

													$sql_query = "SELECT sc.id, sc.stock_id, sc.circle_id, s.crop, s.lot_number, s.variety, s.class, sc.parent_id, sc.processing_qty, sc.time_created FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.delete_status='0'";

													if(isset($_GET['filter_crop']) && !empty( $_GET['filter_crop'] ) ) {
														$crop = validate($_GET['filter_crop']);
														$sql_query .= " && s.crop='$crop' ";
													}

													if(isset($_GET['filter_variety']) && !empty( $_GET['filter_variety'] ) ) {
														$variety = validate($_GET['filter_variety']);
														$sql_query .= " && s.variety='$variety' ";
													}

													if(isset($_GET['filter_class']) && !empty( $_GET['filter_class'] ) ) {
														$class = validate($_GET['filter_class']);
														$sql_query .= " && s.class='$class' ";
													}

													if(isset($_GET['filter_from_date']) && isset($_GET['filter_to_date'])) {

														$filter_from_date = strtotime(validate($_GET['filter_from_date']));
														$filter_to_date = strtotime(validate($_GET['filter_to_date']).date(' H:i:s'));

														if( !empty( $filter_from_date ) && !empty( $filter_to_date ) ) {
															$sql_query .= " && ( sf.time_created BETWEEN '$filter_from_date' AND '$filter_to_date' ) ";
														} else if( !empty( $filter_from_date ) && empty( $filter_to_date ) ) {
															$sql_query .= " && sf.time_created>='$filter_from_date' ";
														} else if( empty( $filter_from_date ) && !empty( $filter_to_date ) ) {
															$sql_query .= " sf.time_created<='$filter_to_date' ";
														}

													}

													if(isset($_GET['circle_id']) && !empty( $_GET['circle_id'] ) ) {
														
														$circle_id = validate($_GET['circle_id']);
														$sql_query .= " && sf.circle_id='$circle_id' ";
													
													}

													$sql_query .= " ORDER BY time_created DESC";

													$query = mysqli_query($conn, $sql_query);

												} else {

													$query = mysqli_query($conn, "SELECT sc.id, sc.stock_id, sc.circle_id, s.crop, s.lot_number, s.variety, s.class, sc.parent_id, sc.processing_qty, sc.time_created FROM stocks AS s INNER JOIN stock_cleaning AS sc ON s.id=sc.parent_id WHERE s.delete_status='0'");

												}

												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
														?>
														<tr>
															<td><a href="?query=filter&crop=<?= $result['crop']; ?>"><?= stock_crop($result['crop']); ?></a></td>
															<td><?= $result['lot_number']; ?></td>
															<td><a href="?query=filter&variety=<?= $result['variety']; ?>"><?= stock_variety($result['variety']); ?></a></td>
															<td><a href="?query=filter&class=<?= $result['class']; ?>"><?= stock_class($result['class']); ?></a></td>
															<td><a href="?query=filter&circle_id=<?= $result['circle_id']; ?>"><?= circle_name($result['circle_id']); ?></a></td>
															<td><?= $result['processing_qty'].' (KGs)'; ?></td>
															<td><?= stock_cleaning_meta($result['id'], 'grade_1').' (KGs)'; ?></td>
															<td><?= stock_cleaning_meta($result['id'], 'small_grains').' (KGs)'; ?></td>
															<td><?= stock_cleaning_meta($result['id'], 'gundi').' (KGs)'; ?></td>
															<td><?= stock_cleaning_meta($result['id'], 'broken').' (KGs)'; ?></td>
															<td><?= stock_cleaning_meta($result['id'], 'straw').' (KGs)'; ?></td>
															<td><?= stock_cleaning_meta($result['id'], 'dust').' (KGs)'; ?></td>
															<td><?= stock_cleaning_meta($result['id'], 'other').' (KGs)'; ?></td>
															<td><?= ceil( (stock_cleaning_meta($result['id'], 'grade_1') * 100) / stock_cleaning_meta($result['id'], 'processing_qty') ).'%'; ?></td>
															<td><?= date('d-m-Y', $result['time_created']); ?></td>
														</tr>
														<?php
														$i++;
													}
												} else {
													echo "<tr><td colspan='15' class='text-center'>No Record Found</td></tr>";
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

			$(document).on('change', '.filter_crop', function(){
				var stock_crop = $(this).val();
				if(stock_crop != '' && stock_crop != 0) {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'display_stock_variety', stock_crop:stock_crop },
						success: function(result) {
							$('.filter_variety').html(result);
						}
					});
				}
			});

			$(document).on('click', '.delete_stock_btn', function(){

				if(confirm('Are you sure to delete stock?')) {
					return true;
				} else {
					return false;
				}

			});

		});
	</script>
</body>
</html>