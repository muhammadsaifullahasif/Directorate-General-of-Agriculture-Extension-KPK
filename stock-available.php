<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Available Stock';

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
							<?php

							if(is_manager()) {
							?>
							<a href="stock-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
							<?php
							}

							?>
							<button class="btn btn-outline-dark btn-sm mb-3 ml-2" data-toggle="modal" data-target="#filter_modal">Advance Filter</button>
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='stock-available.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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
						$search_result_msg = 'Search Result of';
						
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
						if(isset($_GET['filter_variety']) && !empty( $_GET['filter_variety'] ) ) {
							$search_result_msg .= ' & variety <b>'.stock_variety(validate($_GET['filter_variety'])).'</b>';
						}
						if(isset($_GET['filter_class']) && !empty( $_GET['filter_class'] ) ) {
							$search_result_msg .= ' & class <b>'.stock_class(validate($_GET['filter_class'])).'</b>';
						}
						if(isset($_GET['filter_status']) && !empty( $_GET['filter_status'] ) ) {
							$search_result_msg .= ' & status';
							if($_GET['filter_status'] == 'uncleaned') {
								$search_result_msg .= ' <b>Uncleaned</b>';
							} else if($_GET['filter_status'] == 'cleaned') {
								$search_result_msg .= ' <b>Cleaned</b>';
							} else if($_GET['filter_status'] == 'fumigate') {
								$search_result_msg .= ' <b>Fumigate</b>';
							}
						}
						if(isset($_GET['filter_circle']) && !empty( $_GET['filter_circle'] ) ) {
							$search_result_msg .= ' circle <b>'.circle_name(validate($_GET['filter_circle'])).'</b>';
						}
						if(isset($_GET['filter_activity_season']) && !empty( $_GET['filter_activity_season'] ) ) {
							$search_result_msg .= ' <b>'.activity_season_title(validate($_GET['filter_activity_season'])).'</b>';
						}
						echo "<div class='mb-3'><p>".$search_result_msg."</p></div>";
					}

					if(isset($_GET['action']) && $_GET['action'] == 'apply_supply_tags') {

						$id = validate($_GET['id']);

						$query = mysqli_query($conn, "UPDATE stock_transactions SET supply_status='1' WHERE id='$id' && active_status!='0' && delete_status='0'");
						if($query) {
							echo "<div class='alert alert-success' id='msg_alert'>Supply tags is successfully applied on selected stock</div>";
							echo "<script>history.pushState({}, '', 'stock-available.php'); setTimeout(function(){ $('#msg_alert').remove(); }, 1000)</script>";
						} else {
							echo "<div class='alert alert-danger'>Please try again</div>";
						}

					}


					if(isset($_GET['action']) && $_GET['action'] == 'delete') {

						if(isset($_GET['id']) && $_GET['id'] != 0 && !empty( $_GET['id'] ) ) {
							$id = validate($_GET['id']);

							$check_query = mysqli_query($conn, "SELECT * FROM stocks WHERE id='$id'");

							if(mysqli_num_rows($check_query) > 0) {
								
								$check_result = mysqli_fetch_assoc($check_query);
								$stock_circle_id = $check_result['circle_id'];

								$check_stock_query = mysqli_query($conn, "SELECT * FROM stock_transactions WHERE stock_id='$id' && circle_id='$stock_circle_id'");
								if(mysqli_num_rows($check_stock_query) > 0) {
									while($check_stock_result = mysqli_fetch_assoc($check_stock_query)) {

										$stock_id = $check_stock_result['id'];

										
										$check_cleaning_query = mysqli_query($conn, "SELECT * FROM stock_cleaning WHERE circle_id='$stock_circle_id' && parent_id='$id' && stock_id='$stock_id'");

										if(mysqli_num_rows($check_cleaning_query) > 0) {
											while($check_cleaning_result = mysqli_fetch_assoc($check_cleaning_query)) {
												$cleaning_id = $check_cleaning_result['id'];

												$delete_cleaning_query = mysqli_query($conn, "DELETE FROM stock_cleaning WHERE id='$cleaning_id'");
												$delete_cleaning_meta_query = mysqli_query($conn, "DELETE FROM stock_cleaning_meta WHERE stock_cleaning_id='$cleaning_id'");

											}
										}
										


										
										$check_fumigation_query = mysqli_query($conn, "SELECT * FROM stock_fumigation WHERE circle_id='$stock_circle_id' && parent_id='$id' && stock_id='$stock_id'");

										if(mysqli_num_rows($check_fumigation_query) > 0) {
											while($check_fumigation_result = mysqli_fetch_assoc($check_fumigation_query)) {
												$fumigation_id = $check_fumigation_result['id'];

												$delete_fumigation_query = mysqli_query($conn, "DELETE FROM stock_fumigation WHERE id='$fumigation_id'");
												$delete_fumigation_meta_query = mysqli_query($conn, "DELETE FROM stock_fumigation_meta WHERE stock_fumigation_id='$fumigation_id'");

											}
										}
										


										$check_supply_query = mysqli_query($conn, "SELECT * FROM supply WHERE circle_id='$stock_circle_id' && parent_id='$id' && stock_id='$stock_id'");

										if(mysqli_num_rows($check_supply_query) > 0) {
											while($check_supply_result = mysqli_fetch_assoc($check_supply_query)) {

												$supply_id = $check_supply_result['id'];

												$delete_supply_query = mysqli_query($conn, "DELETE FROM supply WHERE id='$supply_id'");
												$delete_supply_meta_query = mysqli_query($conn, "DELETE FROM supply_meta WHERE supply_id='$supply_id'");

											}
										}

									}

								}

								$delete_stock_query = mysqli_query($conn, "DELETE FROM stock_transactions WHERE stock_id='$stock_id' && circle_id='$stock_circle_id'");

								if($delete_stock_query) {
									echo "<div class='alert alert-success notify-alert'>Stock Successfully Deleted</div>";
									echo "<script>history.pushState({}, '', 'stock-available.php'); setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
								} else {
									echo "<div class='alert alert-danger notify-alert'>Please Try Again</div>";
									echo "<script>setInterval(function(){ $('.notify-alert').hide(); }, 1000)</script>";
								}

							} else {
								header('Location: stock-available.php');
							}
							
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
											
											<div class="col-md-3 mb-3">
												<label>Crop:</label>
												<select class="form-control filter_crop" name="filter_crop">
													<option value="">Select Crop</option>
													<?php

													$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_crop_query) > 0) {
														while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
															if(isset($_GET['filter_crop']) && $_GET['filter_crop'] == $stock_crop_result['id']) {
																$filter_crop_selected = 'selected';
															} else {
																$filter_crop_selected = '';
															}
															echo "<option ".$filter_crop_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['type']."</option>";
														}
													}

													?>
												</select>
											</div>

											<div class="col-md-3 mb-3">
												<label>Variety:</label>
												<select class="form-control filter_variety" id="filter_variety" name="filter_variety">
													<option value="">Select Variety</option>
													<?php

													if(isset($_GET['filter_type']) && !empty( $_GET['filter_type'] ) ) {
														$filter_type = validate($_GET['filter_type']);

														$variety_query = mysqli_query($conn, "SELECT * FROM stock_variety WHERE stock_crop_id='$filter_type' && active_status='1' && delete_status='0'");
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

											<div class="col-md-3 mb-3">
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

											<div class="col-md-3 mb-3">
												<label>Status:</label>
												<select class="form-control" id="filter_status" name="filter_status">
													<option value="">Select Status</option>
													<option <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'uncleaned') echo 'selected'; ?> value="uncleaned">Uncleaned</option>
													<option <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'cleaned') echo 'selected'; ?> value="cleaned">Cleaned</option>
													<option <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'fumigate') echo 'selected'; ?> value="fumigate">Fumigate</option>
												</select>
											</div>

										</div>

										<div class="row">
											<?php

											if(is_super_admin() || is_admin()) {

											?>
											<div class="col-md-6 mb-3">
												<label>circle:</label>
												<select class="form-control" name="filter_circle" id="filter_circle">
													<option value="">Select circle</option>
													<?php

													$circle_sql = "SELECT * FROM circles WHERE active_status='1' && delete_status='0'";
													if(is_admin()) {
														$circle_sql .= " && district='$user_district' ";
													}
													$circle_query = mysqli_query($conn, $circle_sql);
													if(mysqli_num_rows($circle_query) > 0) {
														while($circle_result = mysqli_fetch_assoc($circle_query)) {
															if(isset($_GET['filter_circle']) && $_GET['filter_circle'] == $circle_result['id']) {
																$circle_selected = 'selected';
															} else {
																$circle_selected = '';
															}
														?>
														<option <?= $circle_selected; ?> value="<?= $circle_result['id'] ?>"><?= $circle_result['name']; ?></option>
														<?php
														}
													}

													?>
												</select>
											</div>
											<?php

											}

											?>
											<div class="<?php if(is_super_admin() || is_admin()) { echo 'col-md-6'; } ?> mb-3">
												<label>Activity Season:</label>
												<select class="form-control filter_activity_season" name="filter_activity_season">
													<option value="">Select Activity Season</option>
													<?php

													if(isset($_GET['filter_activity_season']) && !empty( $_GET['filter_activity_season'] )) {
														$filter_activity_season = validate($_GET['filter_activity_season']);

														$activity_season_query = mysqli_query($conn, "SELECT * FROM stock_activity_season WHERE active_status='1' && delete_status='0' && stock_crop_id = (SELECT stock_crop_id FROM stock_activity_season WHERE id='$filter_activity_season' && active_status='1' && delete_status='0') ORDER BY time_created DESC");
														if(mysqli_num_rows($activity_season_query) > 0) {
															while($activity_season_result = mysqli_fetch_assoc($activity_season_query)) {

																if($filter_activity_season == $activity_season_result['id']) {
																	$filter_activity_season_selected = 'selected';
																} else {
																	$filter_activity_season_selected = '';
																}

																echo "<option ".$filter_activity_season_selected." value='".$activity_season_result['id']."'>".$activity_season_result['season_title']."</option>";

															}
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
									<div class="col-md-12">
										<form class="form-inline">
											<input type="hidden" style="display: none;" value="filter" name="query">

											<div class="col-2">
												<select class="form-control w-100 form-control-sm form-control-border filter_crop" name="filter_crop">
													<option value="">Crop</option>
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

													if(isset($_GET['filter_type']) && !empty( $_GET['filter_type'] ) ) {
														$filter_type = validate($_GET['filter_type']);

														$variety_query = mysqli_query($conn, "SELECT * FROM stock_variety WHERE stock_crop_id='$filter_type' && active_status='1' && delete_status='0'");
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
											<div class="col-2">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_status">
													<option value="">Select Status</option>
													<option <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'uncleaned') echo 'selected'; ?> value="uncleaned">Uncleaned</option>
													<option <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'cleaned') echo 'selected'; ?> value="cleaned">Cleaned</option>
													<option <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'fumigate') echo 'selected'; ?> value="fumigate">Fumigate</option>
												</select>
											</div>
											<div class="col-2">
												<select class="form-control w-100 form-control-sm form-control-border filter_activity_season" name="filter_activity_season">
													<option value="">Activity Season</option>
													<?php

													if(isset($_GET['filter_activity_season']) && !empty( $_GET['filter_activity_season'] )) {
														$filter_activity_season = validate($_GET['filter_activity_season']);

														$activity_season_query = mysqli_query($conn, "SELECT * FROM stock_activity_season WHERE active_status='1' && delete_status='0' && stock_crop_id = (SELECT stock_crop_id FROM stock_activity_season WHERE id='$filter_activity_season' && active_status='1' && delete_status='0') ORDER BY time_created DESC");
														if(mysqli_num_rows($activity_season_query) > 0) {
															while($activity_season_result = mysqli_fetch_assoc($activity_season_query)) {

																if($filter_activity_season == $activity_season_result['id']) {
																	$filter_activity_season_selected = 'selected';
																} else {
																	$filter_activity_season_selected = '';
																}

																echo "<option ".$filter_activity_season_selected." value='".$activity_season_result['id']."'>".$activity_season_result['season_title']."</option>";

															}
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
													<th>Quantity</th>
													<th>circle</th>
													<th>Season</th>
													<th>Status</th>
													<?php

													if(is_manager()) {
														echo "<th>Operations</th>";
													}

													?>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_stocks">

												<?php

												if(isset($_GET['query'])) {

													$sql_query = "SELECT s.id, s.circle_id, s.stock_source, s.supplier_info, s.activity_season, s.lot_number, s.crop, s.variety, s.class, s.time_created";

													if(is_admin()) {
														$sql_query .= " e.district ";
													}
													$sql_query .= " FROM stocks AS s";
													if(is_admin()) {
														$sql_query .= " INNER JOIN circles AS e ON s.circle_id=e.id ";
													}
													$sql_query .= " WHERE s.active_status='1' && s.delete_status='0'";
													if(is_admin()) {
														$sql_query .= " && e.district='$user_district' ";
													}
													if(is_manager() || is_storekeeper()) {
														$sql_query .= " && s.circle_id='$circle_id' ";
													}

													if($_GET['query'] == 'filter') {

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

														if(!isset($_GET['filter_status']) && isset($_GET['filter_from_date']) && isset($_GET['filter_to_date'])) {

															$filter_from_date = strtotime(validate($_GET['filter_from_date']));
															$filter_to_date = strtotime(validate($_GET['filter_to_date']).date(' H:i:s'));

															if( !empty( $filter_from_date ) && !empty( $filter_to_date ) ) {
																$sql_query .= " && ( s.time_created BETWEEN '$filter_from_date' AND '$filter_to_date' ) ";
															} else if( !empty( $filter_from_date ) && empty( $filter_to_date ) ) {
																$sql_query .= " && s.time_created>='$filter_from_date' ";
															} else if( empty( $filter_from_date ) && !empty( $filter_to_date ) ) {
																$sql_query .= " s.time_created<='$filter_to_date' ";
															}

														}

														if(isset($_GET['filter_circle']) && !empty( $_GET['filter_circle'] ) ) {
															
															$filter_circle = validate($_GET['filter_circle']);
															$sql_query .= " && s.circle_id='$filter_circle' ";
														
														}

														if(isset($_GET['filter_activity_season']) && !empty( $_GET['filter_activity_season'] ) ) {
															$sql_query .= " && s.activity_season='".validate($_GET['filter_activity_season'])."' ";
														}

													}

													$sql_query .= " && ( (s.stock_source='from_farmer' || s.stock_source='others') && s.stock_status='1' ) || (s.stock_source <> 'from_farmer' && s.stock_source <> 'others') ORDER BY s.time_created DESC";
													$query = mysqli_query($conn, $sql_query);

												} else {

													$sql_query = "SELECT s.id, s.circle_id, s.stock_source, s.supplier_info, s.activity_season, s.lot_number, s.crop, s.variety, s.class, s.time_created";
													if(is_admin()) {
														$sql_query .= ", e.district ";
													}
													$sql_query .= " FROM stocks AS s";
													if(is_admin()) {
														$sql_query .= " INNER JOIN circles AS e ON s.circle_id=e.id ";
													}
													$sql_query .= " WHERE s.active_status='1' && s.delete_status='0'";
													if(is_admin()) {
														$sql_query .= " && e.district='$user_district' ";
													}
													if(is_manager() || is_storekeeper()) {
														$sql_query .= " && s.circle_id='$circle_id' ";
													}
													$sql_query .= " && ( (s.stock_source='from_farmer' || s.stock_source='others') && s.stock_status='1' ) || (s.stock_source <> 'from_farmer' && s.stock_source <> 'others') ORDER BY s.time_created DESC";
													// $sql_query .= " && ( (s.stock_source='from_farmer' || s.stock_source='others') && s.stock_status='1' ) || ( stock_source NOT IN ('from_farmer', 'others')) ORDER BY s.time_created DESC";

													$query = mysqli_query($conn, $sql_query);

												}

												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
														$stock_id = $result['id'];
														$stock_transaction_sql = "SELECT * FROM stock_transactions WHERE stock_id='$stock_id' && stock_qty!='0' && active_status!='2' && delete_status='0'";

														if(isset($_GET['query']) && $_GET['query'] == 'filter') {

															if(isset($_GET['filter_status']) && !empty($_GET['filter_status'])) {
																$status = validate($_GET['filter_status']);

																if($status == 'uncleaned') {
																	$stock_transaction_sql .= " && stock_status='0' ";
																} else if($status == 'cleaned') {
																	$stock_transaction_sql .= " && stock_status='2' ";
																} else if($status == 'fumigate') {
																	$stock_transaction_sql .= " && active_status='3' ";
																}
															}

															if(isset($_GET['filter_status']) && isset($_GET['filter_from_date']) && isset($_GET['filter_to_date'])) {

																$filter_from_date = strtotime(validate($_GET['filter_from_date']));
																$filter_to_date = strtotime(validate($_GET['filter_to_date']).date(' H:i:s'));

																if( !empty( $filter_from_date ) && !empty( $filter_to_date ) ) {
																	$stock_transaction_sql .= " && ( time_created BETWEEN '$filter_from_date' AND '$filter_to_date' ) ";
																} else if( !empty( $filter_from_date ) && empty( $filter_to_date ) ) {
																	$stock_transaction_sql .= " && time_created>='$filter_from_date' ";
																} else if( empty( $filter_from_date ) && !empty( $filter_to_date ) ) {
																	$stock_transaction_sql .= " time_created<='$filter_to_date' ";
																}

															}

														}
														$stock_transaction_sql .= "  ORDER BY time_created DESC ";

														$stock_transaction_query = mysqli_query($conn, $stock_transaction_sql);

														if(mysqli_num_rows($stock_transaction_query) > 0) {
															while($stock_transaction_result = mysqli_fetch_assoc($stock_transaction_query)) {
																$stock_transaction_id = $stock_transaction_result['id'];
															?>
															<tr>
																<td><a href="stock-available.php?query=filter&filter_crop=<?= $result['crop']; ?>"><?= stock_crop($result['crop']); ?></a></td>
																<td><?= $result['lot_number']; ?></td>
																<td><a href="stock-available.php?query=filter&filter_variety=<?= $result['variety']; ?>"><?= stock_variety($result['variety']); ?></a></td>
																<td><a href="stock-available.php?query=filter&filter_class=<?= $result['class']; ?>"><?= stock_class($result['class']); ?></a></td>
																<td><?= $stock_transaction_result['stock_qty'].' (KGs)'; ?></td>
																<td><a href="stock-available.php?query=filter&filter_circle=<?= $result['circle_id']; ?>"><?= circle_name($result['circle_id']); ?></a></td>
																<td><a href="stock-available.php?query=filter&filter_activity_season=<?= $result['activity_season']; ?>"><?= activity_season_title($result['activity_season']); ?></a></td>
																<td>
																	<?php

																	if($stock_transaction_result['active_status'] == 2) {
																		echo "<span class='badge badge-danger'>Stock Completed</span>";
																	} else {

																		if($stock_transaction_result['stock_status'] == 0) {
																			echo "<span class='mx-1 badge badge-warning'>Uncleaned</span>";
																		} else if($stock_transaction_result['stock_status'] == 1) {
																			echo "<span class='mx-1 badge badge-danger'>Under Cleaning</span>";
																		} else if($stock_transaction_result['stock_status'] == 2) {
																			echo "<span class='mx-1 badge badge-success'>Cleaned</span>";
																		}

																		if($stock_transaction_result['active_status'] == 3) {
																			echo "<span class='mx-1 badge badge-primary'>Under Fumigation</span>";
																		}

																	}

																	?>
																</td>
																<?php

																if(is_manager()) {
																?>
																<td>
																	<div class="btn-group">
																		<?php

																		if($stock_transaction_result['active_status'] != 3) {
																			echo "<a href='stock-fumigation-new.php?id=".$stock_transaction_result['id']."' class='btn btn-primary btn-sm'>Fumigation</a>";
																		}

																		if((mysqli_num_rows(mysqli_query($conn, "SELECT * FROM fscrd_report WHERE stock_id='$stock_id' && report_type='2' && report_status='1'")) == 1) && (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM fscrd_report WHERE stock_id='$stock_transaction_id' && report_type='3' && report_status='1'")) == 0)) {
																			echo "<a href='stock-cleaning-new.php?id=".$stock_transaction_result['id']."' class='btn btn-danger btn-sm'>Cleaning</a>";
																		}

																		if($stock_transaction_result['supply_status'] == 1 && $stock_transaction_result['stock_status'] == 2) {
																			echo "<a href='stock-supply-new.php?id=".$stock_transaction_result['id']."' class='btn btn-success btn-sm'>Supply</a>";
																		} else {
																			if($stock_transaction_result['stock_status'] == 2) {
																				echo "<a href='?id=".$stock_transaction_result['id']."&action=apply_supply_tags' class='btn btn-warning btn-sm supply_tag_btn'>Apply Tags <strong>".floor($stock_transaction_result['stock_qty'] / 50)." tags required</strong></a>";
																			}
																		}

																		?>
																	</div>
																</td>
																<?php
																}

																?>
																<td>
																	<div class="btn-group">
																		<a href="stock-detail.php?id=<?= $result['id']; ?>" class="btn btn-success btn-sm"><i class="fas fa-eye"></i></a>
																	</div>
																</td>
															</tr>
															<?php
															}
														}
														?>
														<?php
														$i++;
													}
												} else {
													echo "<tr><td colspan='10' class='text-center'>No Record Found</td></tr>";
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

			$(document).on('change', '.filter_type', function(){
				var stock_crop = $(this).val();
				// var stock_crop = $('.filter_type option:selected').val();
				if(stock_crop != '' && stock_crop != 0) {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'display_stock_variety', stock_crop:stock_crop },
						success: function(result) {
							$('.filter_variety').html(result);
						}
					});
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action: 'display_filter_activity_season', stock_crop:stock_crop },
						success: function(result) {
							$('.filter_activity_season').html(result);
						}
					});
				}
			});

			$(document).on('click', '.supply_tag_btn', function(){

				if(confirm('Are you sure to apply tags on selected stock?')) {
					return true;
				} else {
					return false;
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