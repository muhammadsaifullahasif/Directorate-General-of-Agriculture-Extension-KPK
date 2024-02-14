<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'SMP Rejected List';

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
							<a href="stock-supply-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
							<?php

							}

							?>
							<button class="btn btn-outline-dark btn-sm mb-3 ml-2" data-toggle="modal" data-target="#filter_modal">Advance Filter</button>
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='supply.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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
						if(isset($_GET['filter_from_date']) && !empty($_GET['filter_from_date']) && isset($_GET['filter_to_date']) && !empty($_GET['filter_to_date'])) {
							$search_result_msg .= ' from <b>'.date('d-M-Y', strtotime(validate($_GET['filter_from_date']))).'</b> to <b>'.date('d-M-Y', strtotime(validate($_GET['filter_to_date']))).'</b>';
						} else if(isset($_GET['filter_from_date']) && !empty($_GET['filter_from_date']) && isset($_GET['filter_to_date']) && !empty($_GET['filter_to_date'])) {
							$search_result_msg .= 'from <b>'.date('d-M-Y', strtotime(validate($_GET['filter_from_date']))).'</b> till today';
						} else if(isset($_GET['filter_from_date']) && !empty($_GET['filter_from_date']) && isset($_GET['filter_to_date']) && !empty($_GET['filter_to_date'])) {
							$search_result_msg .= ' till <b>'.date('d-M-Y', strtotime(validate($_GET['filter_to_date']))).'</b>';
						}
						if(isset($_GET['filter_crop']) && !empty($_GET['filter_crop'])) {
							$search_result_msg .= ' of crop <b>'.stock_crop(validate($_GET['filter_crop'])).'</b>';
						}
						if(isset($_GET['filter_variety']) && !empty($_GET['filter_variety'])) {
							$search_result_msg .= ' of variety <b>'.stock_variety(validate($_GET['filter_variety'])).'</b>';
						}
						if(isset($_GET['filter_class']) && !empty($_GET['filter_class'])) {
							$search_result_msg .= ' of class <b>'.stock_class(validate($_GET['filter_class'])).'</b>';
						}
						if(isset($_GET['filter_from_circle']) && !empty($_GET['filter_from_circle'])) {
							$search_result_msg .= ' from <b>'.circle_name(validate($_GET['filter_from_circle'])).'</b>';
						}
						if(isset($_GET['filter_farmer']) && !empty($_GET['filter_farmer'])) {
							$search_result_msg .= ' to Farmer <b>'.validate($_GET['filter_farmer']).'</b>';
						}
						if(isset($_GET['filter_activity_season']) && !empty( $_GET['filter_activity_season'] ) ) {
							$search_result_msg .= ' of season <b>'.activity_season_title(validate($_GET['filter_activity_season'])).'</b>';
						}
						echo "<div class='mb-3'><p>".$search_result_msg."</p></div>";
					}



					if(isset($_GET['action']) && $_GET['action'] == 'fsrd_report') {

						$supply_id = validate($_GET['id']);
						if(isset($_GET['report'])) {
							$report = validate($_GET['report']);
						} else {
							$report = '';
						}

					?>

					<div class="collapse show" id="fsrd_report_container">
						<div class="card card-body">

							<?php

							if(isset($_POST['fsrd_report_form_btn'])) {
								$fsrd_comments = ucwords(validate($_POST['fsrd_comments']));

								if( !empty($supply_id) ) {

									if($report == 'accepted') {
										$smp_status = 1;
									} else if($report == 'rejected') {
										$smp_status = 2;
									} else if($report == '') {
										$smp_status = validate($_POST['fsrd_report_status']);
									}

									$query = mysqli_query($conn, "UPDATE supply SET smp_status='$smp_status' WHERE id='$supply_id'");

									$meta_query = mysqli_query($conn, "UPDATE supply_meta SET meta_value='$fsrd_comments' WHERE supply_id='$supply_id' && meta_key='fsrd_comments'");

									if($query && $meta_query) {
										if($smp_status == 1) {
											$report_status = 'Accepted';
										} else {
											$report_status = 'Rejected';
										}
										echo "<div class='alert alert-success' id='fsrd_report_form_alert'>FSRD ".$report_status." Successfully</div>";
										echo "<script>history.pushState({}, '', 'smp-rejected-list.php'); setTimeout(function(){ $('#fsrd_report_container').removeClass('show'); $('#fsrd_comments').val(''); $('#fsrd_report_form_alert').remove(); }, 1000)</script>";
									} else {
										echo "<div class='alert alert-danger'>Please try again</div>";
									}

								} else {
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
								}
							}

							?>

							<form method="post" id="fsrd_report_form" class="form">

								<?php

								if(!isset($_GET['report'])) {
								?>
								<div class="mb-3">
									<label>Report Status:</label>
									<select class="form-control" name="fsrd_report_status" id="fsrd_report_status">
										<option value="">Select FSRD Report Status</option>
										<option value="1">Accepted</option>
										<option value="2">Rejected</option>
									</select>
									<div id="report_msg"></div>
								</div>
								<?php
								}

								?>
								
								<div class="mb-3">
									<label>FSRD Comments:</label>
									<textarea class="form-control" id="fsrd_comments" name="fsrd_comments" placeholder="Enter FSRD Comments"><?= supply_meta($supply_id, 'fsrd_comments'); ?></textarea>
									<div id="fsrd_comments_msg"></div>
								</div>
								<button class="btn btn-primary" class="submit" id="fsrd_report_form_btn" name="fsrd_report_form_btn">Submit</button>

								<button class="btn btn-outline-dark fsrd_report_container_close" type="button" data-toggle="collapse" href="#fsrd_report_container">Cancel</button>

							</form>

						</div>
					</div>

					<?php

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
										<input type="hidden" value="filter" style="display: none;" name="query">

										<div class="row">
											
											<div class="col-md-6 mb-3">
												<label>From Date:</label>
												<input type="date" value="<?php if(isset($_GET['filter_from_date']) && !empty($_GET['filter_from_date'])) { echo date('Y-m-d', strtotime(validate($_GET['filter_from_date']))); } ?>" name="filter_from_date" id="filter_from_date" class="form-control" placeholder="Enter From Date">
											</div>

											<div class="col-md-6 mb-3">
												<label>To Date:</label>
												<input type="date" value="<?php if(isset($_GET['filter_to_date']) && !empty($_GET['filter_to_date'])) { echo date('Y-m-d', strtotime(validate($_GET['filter_to_date']))); } ?>" name="filter_to_date" id="filter_to_date" class="form-control" placeholder="Enter To Date">
											</div>

										</div>
										
										<div class="row">
											
											<div class="col-md-4 mb-3">
												<label>Crop:</label>
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
												<select class="form-control filter_variety" name="filter_variety">
													<option value="">Select Variety</option>
													<?php

													if(isset($_GET['filter_crop']) && !empty($_GET['filter_crop'])) {
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
												<select class="form-control" name="filter_class">
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

										<div class="row">
											
											<?php

											if(is_super_admin() || is_admin()) {
											?>
											<div class="col-md-6 mb-3">
												<label>Source Circle:</label>
												<select class="form-control" name="filter_from_circle" id="filter_from_circle">
													<option value="">Select Source Circle</option>
													<?php

													$from_circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($from_circle_query) > 0) {
														while($from_circle_result = mysqli_fetch_assoc($from_circle_query)) {
															if(isset($_GET['filter_from_circle']) && $_GET['filter_from_circle'] == $from_circle_result['id']) {
																$from_circle_selected = 'selected';
															} else {
																$from_circle_selected = '';
															}
															echo "<option ".$from_circle_selected." value='".$from_circle_result['id']."'>".circle_name($from_circle_result['id'])."</option>";
														}
													}

													?>
												</select>
											</div>
											<?php
											}

											?>

											<div class="col-md-<?php if(is_super_admin() || is_admin()) { echo '6'; } else { echo '12'; } ?> mb-3">
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
													<option value="">Stock Crop</option>
													<?php

													$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_crop_query) > 0) {
														while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
															$filter_crop = validate($_GET['filter_crop']);
															if(isset($_GET['filter_crop']) && $filter_crop == $stock_crop_result['id']) {
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

													if(isset($_GET['filter_crop']) && $_GET['filter_crop'] != '') {
														$filter_crop = validate($_GET['filter_crop']);

														$variety_query = mysqli_query($conn, "SELECT * FROM stock_variety WHERE stock_crop_id='$filter_crop' && active_status='1' && delete_status='0'");
														if(mysqli_num_rows($variety_query) > 0) {
															$filter_variety = validate($_GET['filter_variety']);
															while($variety_result = mysqli_fetch_assoc($variety_query)) {
																if(isset($_GET['filter_variety']) && $filter_variety == $variety_result['id']) {
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
														$filter_class = validate($_GET['filter_class']);
														while($stock_class_result = mysqli_fetch_assoc($stock_class_query)) {
															if(isset($_GET['filter_class']) && $filter_class == $stock_class_result['id']) {
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
													<th>Farmer Name</th>
													<th>Farmer CNIC</th>
													<th>Farmer Contact</th>
													<th>Farmer Address</th>
													<th>Area</th>
													<th>Crop</th>
													<th>Variety</th>
													<th>Class</th>
													<th>Quantity</th>
													<th>Season</th>
													<th>Source Circle</th>
													<th>FSRD Result</th>
													<th>FSRD Comments</th>
												</tr>
											</thead>
											<tbody id="display_supply">

												<?php

												if(isset($_GET['query']) && $_GET['query'] == 'filter') {

													// $supply_sql = "SELECT sp.id, sp.circle_id, sp.receive_source, sp.receiver_detail, sp.stock_qty, sp.receiver_info, sp.receiver_time_created, sp.receive_status, sp.time_created, s.crop, s.lot_number, s.variety, s.class, s.activity_season FROM supply AS sp INNER JOIN stocks AS s INNER JOIN circles AS e ON sp.parent_id=s.id && s.circle_id=e.id WHERE sp.delete_status='0' && sp.active_status='1'";

													$supply_sql = "SELECT sp.id, sp.circle_id, sp.receive_source, sp.receiver_detail, sp.stock_qty, sp.receiver_info, sp.receiver_time_created, sp.receive_status, sp.smp_status, sp.time_created, s.crop, s.variety, s.class, sp.activity_season FROM supply AS sp INNER JOIN stocks AS s INNER JOIN circles AS e ON sp.parent_id=s.id && s.circle_id=e.id WHERE sp.receive_source='to_farmer' && sp.active_status='1' && s.active_status='1' && e.active_status='1' && sp.delete_status='0' && s.delete_status='0' && e.delete_status='0' && sp.smp_status='2'";

													if(is_admin()) {
														$supply_sql .= " && e.district='$user_district' ";
													}
													if(is_manager()) {
														$supply_sql .= " && sp.circle_id='$circle_id' ";
													}

													if(isset($_GET['filter_from_date']) && !empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && !empty( $_GET['filter_to_date'] ) ) {
														$supply_sql .= " && sp.time_created BETWEEN '".strtotime(validate($_GET['filter_from_date']))."' AND '".strtotime(validate($_GET['filter_to_date']))."' ";
													} else if(isset($_GET['filter_from_date']) && !empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && empty( $_GET['filter_to_date'] ) ) {
														$supply_sql .= " && sp.time_created>='".strtotime(validate($_GET['filter_to_date']))."' ";
													} else if(isset($_GET['filter_from_date']) && empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && !empty( $_GET['filter_to_date'] ) ) {
														$supply_sql .= " && sp.time_created<='".strtotime(validate($_GET['filter_to_date']))."' ";
													}

													if(isset($_GET['filter_crop']) && !empty( $_GET['filter_crop'] ) ) {
														$supply_sql .= " && s.crop='".validate($_GET['filter_crop'])."' ";
													}

													if(isset($_GET['filter_variety']) && !empty( $_GET['filter_variety'] ) ) {
														$supply_sql .= " && s.variety='".validate($_GET['filter_variety'])."' ";
													}

													if(isset($_GET['filter_class']) && !empty( $_GET['filter_class'] ) ) {
														$supply_sql .= " && s.class='".validate($_GET['filter_class'])."' ";
													}

													if(is_super_admin() || is_admin()) {
														if(isset($_GET['filter_from_circle']) && !empty( $_GET['filter_from_circle'] ) ) {
															$supply_sql .= " && sp.circle_id='".validate($_GET['filter_from_circle'])."' ";
														}
													}

													if(isset($_GET['filter_farmer']) && !empty( $_GET['filter_farmer'] ) ) {
														$supply_sql .= " && ( sp.receive_source='to_farmer' && sp.receiver_detail='".validate($_GET['filter_farmer'])."' ) ";
													}

													if(isset($_GET['filter_activity_season']) && !empty( $_GET['filter_activity_season'] ) ) {
															$supply_sql .= " && s.activity_season='".validate($_GET['filter_activity_season'])."' ";
														}

													$supply_sql .= " ORDER BY time_created DESC ";

													$query = mysqli_query($conn, $supply_sql);

												} else {

													// $supply_sql = "SELECT sp.id, sp.circle_id, sp.receive_source, sp.receiver_detail, sp.stock_qty, sp.receiver_info, sp.receiver_time_created, sp.receive_status, sp.time_created, s.crop, s.lot_number, s.variety, s.class, s.activity_season FROM supply AS sp INNER JOIN stocks AS s INNER JOIN circles AS e ON sp.parent_id=s.id && s.circle_id=e.id WHERE sp.delete_status='0' && sp.active_status='1'";

													$supply_sql = "SELECT sp.id, sp.circle_id, sp.receive_source, sp.receiver_detail, sp.stock_qty, sp.receiver_info, sp.receiver_time_created, sp.receive_status, sp.smp_status, sp.time_created, s.crop, s.variety, s.class, sp.activity_season FROM supply AS sp INNER JOIN stocks AS s INNER JOIN circles AS e ON sp.parent_id=s.id && s.circle_id=e.id WHERE sp.receive_source='to_farmer' && sp.active_status='1' && s.active_status='1' && e.active_status='1' && sp.delete_status='0' && s.delete_status='0' && e.delete_status='0' && sp.smp_status='2'";

													if(is_admin()) {
														$supply_sql .= " && e.district='$user_district' ";
													}
													if(is_manager()) {
														$supply_sql .= " && sp.circle_id='$circle_id' ";
													}
													$supply_sql .= " ORDER BY time_created DESC ";

													$query = mysqli_query($conn, $supply_sql);
												}

												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
													?>
													<tr>
														<td><?= farmer_info($result['receiver_detail'], 'farmer_name', 'cnic'); ?></td>
														<td><a href="?query=filter&filter_farmer=<?= $result['receiver_detail'] ?>"><?= $result['receiver_detail']; ?></a></td>
														<td><?= farmer_info($result['receiver_detail'], 'farmer_mobile_number', 'cnic'); ?></td>
														<td><?= farmer_info($result['receiver_detail'], 'farmer_address', 'cnic'); ?></td>
														<td><?= supply_meta($result['id'], 'area'); ?></td>

														<td><a href="supply.php?query=filter&filter_crop=<?= $result['crop']; ?>"><?= stock_crop($result['crop']); ?></a></td>
														<td><a href="?query=filter&filter_variety=<?= $result['variety']; ?>"><?= stock_variety($result['variety']); ?></a></td>
														<td><a href="?query=filter&filter_class=<?= $result['class']; ?>"><?= stock_class($result['class']); ?></a></td>
														<td><?= $result['stock_qty'].' (KGs)'; ?></td>
														<td><a href="?query=filter&filter_activity_season=<?= $result['activity_season']; ?>"><?= activity_season_title($result['activity_season']); ?></a></td>
														<td><a href="?query=filter&filter_from_circle=<?= $result['circle_id']; ?>"><?= circle_name($result['circle_id']); ?></a></td>
														<td>
															<div class="btn-group">
															<?php

															if($result['smp_status'] == 2) {
																echo "<span class='badge badge-danger'>Rejected</span>";
															}

															?>
															</div>
														</td>
														<td><?= supply_meta($result['id'], 'fsrd_comments'); if($result['smp_status'] != 0) { echo "<span class='ml-2'>"; if(is_admin()) { echo "<a href='smp-rejected-list.php?id=".$result['id']."&action=fsrd_report'><i class='fas fa-edit'></i></a></span>"; } } ?></td>
													</tr>
													<?php
														$i++;
													}
												} else {
													echo "<tr><td colspan='13' class='text-center'>No Record Found</td></tr>";
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

			$(document).on('click', '.fsrd_report_container_close', function(){
				history.pushState({}, '', 'smp-rejected-list.php');
			});

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

		});
	</script>
</body>
</html>