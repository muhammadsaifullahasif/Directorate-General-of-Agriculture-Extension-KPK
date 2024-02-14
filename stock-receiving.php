<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Stock Receiving';

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
							<button class="btn btn-outline-dark btn-sm mb-3 ml-2" data-toggle="modal" data-target="#filter_modal">Advance Filter</button>
							<?php

							if(isset($_GET['query'])) {
								echo "<a href='stock-receiving.php' class='btn btn-link btn-sm mb-3 ml-2'><i class='fas fa-times mr-2'></i>Remove Filter</a>";
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
						} else if(isset($_GET['filter_from_date']) && !empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && !empty( $_GET['filter_to_date'] ) ) {
							$search_result_msg .= 'from <b>'.date('d-M-Y', strtotime(validate($_GET['filter_from_date']))).'</b> till today';
						} else if(isset($_GET['filter_from_date']) && empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && !empty( $_GET['filter_to_date'] ) ) {
							$search_result_msg .= ' till <b>'.date('d-M-Y', strtotime(validate($_GET['filter_to_date']))).'</b>';
						}
						if(isset($_GET['filter_type']) && !empty( $_GET['filter_type'] ) ) {
							$search_result_msg .= ' of type <b>'.stock_crop(validate($_GET['filter_type'])).'</b>';
						}
						if(isset($_GET['filter_variety']) && !empty( $_GET['filter_variety'] ) ) {
							$search_result_msg .= ' of variety <b>'.stock_variety(validate($_GET['filter_variety'])).'</b>';
						}
						if(isset($_GET['filter_class']) && !empty( $_GET['filter_class'] ) ) {
							$search_result_msg .= ' of class <b>'.stock_class(validate($_GET['filter_class'])).'</b>';
						}
						if(isset($_GET['filter_status']) && !empty( $_GET['filter_status'] ) ) {
							$search_result_msg .= ' of status';
							if($_GET['filter_status'] == 'received') {
								$search_result_msg .= ' <b>Received</b>';
							} else if($_GET['filter_status'] == 'on_way') {
								$search_result_msg .= ' <b>On Way</b>';
							} else if($_GET['filter_status'] == 'rejected') {
								$search_result_msg .= ' <b>Rejected</b>';
							}
						}
						if(isset($_GET['filter_from_circle']) && !empty( $_GET['filter_from_circle'] ) ) {
							$search_result_msg .= ' from <b>'.circle_name(validate($_GET['filter_from_circle'])).'</b>';
						}
						if(isset($_GET['filter_to_circle']) && !empty( $_GET['filter_to_circle'] ) ) {
							$search_result_msg .= ' to <b>'.circle_name(validate($_GET['filter_to_circle'])).'</b>';
						}
						if(isset($_GET['filter_farmer']) && !empty( $_GET['filter_farmer'] ) ) {
							$search_result_msg .= ' to Farmer <b>'.validate($_GET['filter_farmer']).'</b>';
						}
						if(isset($_GET['filter_province']) && !empty( $_GET['filter_province'] ) ) {
							$search_result_msg .= ' to Province <b>'.validate($_GET['filter_province']).'</b>';
						}
						echo "<div class='mb-3'><p>".$search_result_msg."</p></div>";
					}




					if(isset($_GET['action']) && $_GET['action'] == 'receive') {

						if(isset($_GET['id']) && $_GET['id'] != 0 && !empty( $_GET['id'] ) ) {
							$id = validate($_GET['id']);

							?>
							<div class="collapse show" id="receive_supply_stock_container">
								<div class="card card-body">

									<?php

									if(isset($_POST['receive_supply_stock_form_btn'])) {

										// begin transaction
										// mysqli_begin_transaction($conn);

										$lot_number = validate($_POST['lot_number']);
										if( isset($_POST['labour_cost']) && !empty($_POST['labour_cost']) ) {
											$labour_cost = validate($_POST['labour_cost']);
										} else {
											$labour_cost = 0;
										}
										if( isset($_POST['miscellaneous_cost']) && !empty($_POST['miscellaneous_cost']) ) {
											$miscellaneous_cost = validate($_POST['miscellaneous_cost']);
										} else {
											$miscellaneous_cost = 0;
										}
										$total_cost_amount = $labour_cost + $miscellaneous_cost;

										if(!empty($lot_number)) {

											$supply_detail_sql = "SELECT * FROM supply WHERE id='$id' && delete_status='0'";
											if(is_manager() || is_storekeeper()) {
												$supply_detail_sql .= " && receiver_detail='$circle_id' ";
											}
											$supply_detail_query = mysqli_query($conn, $supply_detail_sql);

											if(mysqli_num_rows($supply_detail_query) > 0) {

												$supply_detail_result = mysqli_fetch_assoc($supply_detail_query);

												if(is_super_admin() || is_admin()) {
													$receiver_circle = $supply_detail_result['receiver_detail'];
												} else {
													$receiver_circle = $circle_id;
												}
												$supply_circle = $supply_detail_result['circle_id'];
												$parent_stock_id = $supply_detail_result['parent_id'];
												$stock_qty = $supply_detail_result['stock_qty'];
												$stock_price = supply_meta($id, 'stock_price');
												$stock_crop = supply_meta($id, 'stock_crop');
												$stock_variety = supply_meta($id, 'stock_variety');
												$stock_class = supply_meta($id, 'stock_class');
												$stock_qty_price = (int)$stock_price * (int)$stock_qty;

												if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stocks WHERE circle_id='$receiver_circle' && lot_number='$lot_number' && delete_status='0'")) == 0) {

													$query = mysqli_query($conn, "INSERT INTO stocks(circle_id, stock_source, supplier_info, lot_number, type, variety, class, stock_qty, parent_id, time_created) VALUES('$receiver_circle', 'other_circle', '$supply_circle', '$lot_number', '$stock_crop', '$stock_variety', '$stock_class', '$stock_qty', '$parent_stock_id', '$time_created')");
													$stock_id = mysqli_insert_id($conn);
													$meta_query = mysqli_query($conn, "INSERT INTO stock_meta(stock_id, meta_key, meta_value) VALUES('$stock_id', 'stock_price', '$stock_price'), ('$stock_id', 'stock_qty_price', '$stock_qty_price')");

													$stock_transaction_query = mysqli_query($conn, "INSERT INTO stock_transactions(user_id, circle_id, stock_id, stock_qty, stock_status, time_created) VALUES('$user_id', '$receiver_circle', '$stock_id', '$stock_qty', '0', '$time_created')");
													$stock_transaction_id = mysqli_insert_id($conn);

													$supply_query = mysqli_query($conn, "UPDATE supply SET receiver_info='$user_id' && receiver_time_created='$time_created' && receive_status='1' WHERE id='$id'");

													if($query && $meta_query && $stock_transaction_query && $supply_query) {
														// commit transaction
														// mysqli_commit($conn);
														echo "<div class='alert alert-success'>Stock Successfully Accepted</div>";
													} else {
														// rollback transaction on error
														// mysqli_rollback($conn);
														echo "<div class='alert alert-danger'>Please Try Again</div>";
													}

												} else {
													// rollback transaction on error
													// mysqli_rollback($conn);
													echo "<div class='alert alert-danger'>Lot Number Already Exist</div>";
												}

											} else {
												// rollback transaction on error
												// mysqli_rollback($conn);
												echo "<div class='alert alert-danger'>Please Try Again</div>";
											}

										} else {
											// rollback transaction on error
											// mysqli_rollback($conn);
											echo "<div class='alert alert-danger'>Lot Number Required</div>";
										}

									}

									?>

									<form method="post" id="receive_supply_stock_form" class="form">

										<div class="mb-3">
											<label>Lot Number:</label>
											<input type="text" pattern="[0-9]+" class="form-control" value="" placeholder="Enter Lot Number" id="lot_number" name="lot_number">
											<div id="lot_number_msg"></div>
										</div>
								
										<div class="mb-3">
											<label>Labour Cost:</label>
											<input type="text" pattern="[0-9]+" class="form-control" value="" placeholder="Enter Labour Cost" id="labour_cost" name="labour_cost">
											<div id="labour_cost_msg"></div>
										</div>
										<div class="mb-3">
											<label>Miscellaneous Cost:</label>
											<input type="text" pattern="[0-9]+" class="form-control" value="" placeholder="Enter Miscellaneous Cost" id="miscellaneous_cost" name="miscellaneous_cost">
											<div id="miscellaneous_cost_msg"></div>
										</div>

										<button class="btn btn-primary" type="submit" id="receive_supply_stock_form_btn" name="receive_supply_stock_form_btn">Submit</button>

										<button class="btn btn-outline-dark receive_supply_stock_container_close" type="button" data-toggle="collapse" href="#receive_supply_stock_container">Cancel</button>

									</form>

								</div>
							</div>
							<?php






						} else {
							echo "<script>window.top.location='stock-receiving.php';</script>";
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
										<input type="hidden" value="filter" style="display: none;" name="query">
										<div class="row">
											
											<div class="col-md-6 mb-3">
												<label>From Date:</label>
												<input type="date" value="<?php if(isset($_GET['filter_from_date']) && !empty( $_GET['filter_from_date'] ) ) { echo date('Y-m-d', strtotime(validate($_GET['filter_from_date']))); } ?>" name="filter_from_date" id="filter_from_date" class="form-control" placeholder="Enter From Date">
											</div>

											<div class="col-md-6 mb-3">
												<label>To Date:</label>
												<input type="date" value="<?php if(isset($_GET['filter_to_date']) && !empty( $_GET['filter_to_date'] ) ) { echo date('Y-m-d', strtotime(validate($_GET['filter_to_date']))); } ?>" name="filter_to_date" id="filter_to_date" class="form-control" placeholder="Enter To Date">
											</div>

										</div>
										
										<div class="row">
											
											<div class="col-md-3 mb-3">
												<label>Crop:</label>
												<select class="form-control filter_type" id="filter_type" name="filter_type">
													<option value="">Select Stock Crop</option>
													<?php

													$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_crop_query) > 0) {
														while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
															if(isset($_GET['filter_type']) && $_GET['filter_type'] == $stock_crop_result['id']) {
																$filter_type_selected = 'selected';
															} else {
																$filter_type_selected = '';
															}
															echo "<option ".$filter_type_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['type']."</option>";
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

													if(isset($_GET['filter_type']) && $_GET['filter_type'] != '') {
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
													<option <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'received') echo 'selected'; ?> value="received">Received</option>
													<option <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'on_way') echo 'selected'; ?> value="on_way">On Way</option>
													<option <?php if(isset($_GET['filter_status']) && $_GET['filter_status'] == 'rejected') echo 'selected'; ?> value="rejected">Rejected</option>
												</select>
											</div>

										</div>

										<div class="row">
											
											<div class="col-md-6 mb-3">
												<label>From Circle:</label>
												<select class="form-control" name="filter_from_circle" id="filter_from_circle">
													<option value="">Select From Circle</option>
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

											<div class="col-md-6 mb-3">
												<label>To Circle</label>
												<select class="form-control" name="filter_to_circle" id="filter_to_circle">
													<option value="">Select To Circle</option>
													<?php

													$to_circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($to_circle_query) > 0) {
														while($to_circle_result = mysqli_fetch_assoc($to_circle_query)) {
															if(isset($_GET['filter_to_circle']) && $_GET['filter_to_circle'] == $to_circle_result['id']) {
																$to_circle_selected = 'selected';
															} else {
																$to_circle_selected = '';
															}
															echo "<option ".$to_circle_selected." value='".$to_circle_result['id']."'>".circle_name($to_circle_result['id'])."</option>";
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
												<select class="form-control w-100 form-control-sm form-control-border filter_type" name="filter_type">
													<option value="">Stock Crop</option>
													<?php

													$stock_crop_query = mysqli_query($conn, "SELECT * FROM stock_crop WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_crop_query) > 0) {
														while($stock_crop_result = mysqli_fetch_assoc($stock_crop_query)) {
															if(isset($_GET['filter_type']) && $_GET['filter_type'] == $stock_crop_result['id']) {
																$filter_type_selected = 'selected';
															} else {
																$filter_type_selected = '';
															}
															echo "<option ".$filter_type_selected." value='".$stock_crop_result['id']."'>".$stock_crop_result['type']."</option>";
														}
													}

													?>
												</select>
											</div>
											<div class="col-2">
												<select class="form-control w-100 form-control-sm form-control-border filter_variety" name="filter_variety">
													<option value="">Variety</option>
													<?php

													if(isset($_GET['filter_type']) && $_GET['filter_type'] != '') {
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
											<div class="col-3">
												<select class="form-control w-100 form-control-sm form-control-border" name="filter_from_circle" id="filter_from_circle">
													<option value="">From Circle</option>
													<?php

													$from_circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE active_status='1' && delete_status='0'");
													if(mysqli_num_rows($from_circle_query) > 0) {
														while($from_circle_result = mysqli_fetch_assoc($from_circle_query)) {
															if(isset($_GET['filter_from_circle']) && $from_circle_result['id']) {
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
													<th>Type</th>
													<th>Lot Number</th>
													<th>Variety</th>
													<th>Class</th>
													<th>Stock</th>
													<th>Source circle</th>
													<th>Driver CNIC</th>
													<th>Vehicle Number</th>
													<th>Status</th>
													<th>Action</th>
												</tr>
											</thead>
											<tbody id="display_stocks">

												<?php

												if(isset($_GET['query']) && $_GET['query'] == 'filter') {
													
													$sql_query = "SELECT sp.id, sp.circle_id, sp.receive_source, sp.receiver_detail, sp.stock_qty, sp.receiver_info, sp.receiver_time_created, sp.receive_status, sp.time_created, s.type, s.lot_number, s.variety, s.class FROM supply AS sp INNER JOIN stocks AS s INNER JOIN stock_transactions AS st ON sp.parent_id=s.id && sp.stock_id=st.id && s.id=st.stock_id WHERE sp.delete_status='0' && sp.active_status='1' && sp.receive_status='0'";

													if(isset($_GET['filter_from_date']) && !empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && !empty( $_GET['filter_to_date'] ) ) {
														$sql_query .= " && sp.time_created BETWEEN '".strtotime(validate($_GET['filter_from_date']))."' AND '".strtotime(validate($_GET['filter_to_date']))."' ";
													} else if(isset($_GET['filter_from_date']) && !empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && empty( $_GET['filter_to_date'] ) ) {
														$sql_query .= " && sp.time_created>='".strtotime(validate($_GET['filter_to_date']))."' ";
													} else if(isset($_GET['filter_from_date']) && empty( $_GET['filter_from_date'] ) && isset($_GET['filter_to_date']) && !empty( $_GET['filter_to_date'] ) ) {
														$sql_query .= " && sp.time_created<='".strtotime(validate($_GET['filter_to_date']))."' ";
													}

													if(isset($_GET['filter_type']) && !empty( $_GET['filter_type'] ) ) {
														$sql_query .= " && s.type='".validate($_GET['filter_type'])."' ";
													}

													if(isset($_GET['filter_variety']) && !empty( $_GET['filter_variety'] ) ) {
														$sql_query .= " && s.variety='".validate($_GET['filter_variety'])."' ";
													}

													if(isset($_GET['filter_class']) && !empty( $_GET['filter_class'] ) ) {
														$sql_query .= " && s.class='".validate($_GET['filter_class'])."' ";
													}

													if(isset($_GET['filter_from_circle']) && !empty( $_GET['filter_from_circle'] ) ) {
														$sql_query .= " && sp.circle_id='".validate($_GET['filter_from_circle'])."' ";
													}

													if(isset($_GET['filter_to_circle']) && !empty( $_GET['filter_to_circle'] ) ) {
														$sql_query .= " && ( sp.receive_source='other_circle' && sp.receiver_detail='".validate($_GET['filter_to_circle'])."' ) ";
													}

													if(isset($_GET['filter_farmer']) && !empty( $_GET['filter_farmer'] ) ) {
														$sql_query .= " && ( sp.receive_source='to_farmer' && sp.receiver_detail='".validate($_GET['filter_farmer'])."' ) ";
													}

													if(isset($_GET['filter_province']) && !empty( $_GET['filter_province'] ) ) {
														$sql_query .= " && ( sp.receive_source='other_province' && sp.receiver_detail='".validate($_GET['filter_province'])."' ) ";
													}

													$query = mysqli_query($conn, $sql_query);

												} else {
													$query = mysqli_query($conn, "SELECT sp.id, sp.circle_id, sp.receive_source, sp.receiver_detail, sp.stock_qty, sp.receiver_info, sp.receiver_time_created, sp.receive_status, sp.time_created, s.type, s.lot_number, s.variety, s.class FROM supply AS sp INNER JOIN stocks AS s INNER JOIN stock_transactions AS st ON sp.parent_id=s.id && sp.stock_id=st.id && s.id=st.stock_id WHERE sp.delete_status='0' && sp.active_status='1' && sp.receive_status='0'");
												}


												if(mysqli_num_rows($query) > 0) {
													$i = 1;
													while($result = mysqli_fetch_assoc($query)) {
													?>
													<tr>
														<td><a href="stock.php?query=filter&filter_type=<?= $result['type']; ?>"><?= stock_crop($result['type']); ?></a></td>
														<td><?= $result['lot_number']; ?></td>
														<td><a href="stock.php?query=filter&filter_variety=<?= $result['variety']; ?>"><?= stock_variety($result['variety']); ?></a></td>
														<td><a href="stock.php?query=filter&filter_class=<?= $result['class']; ?>"><?= stock_class($result['class']); ?></a></td>
														<td><?= $result['stock_qty'].' (Mun)'; ?></td>
														<td><a href="stock.php?query=filter&circle_id=<?= $result['circle_id']; ?>"><?= circle_name($result['circle_id']); ?></a></td>
														<td><?= supply_meta($result['id'], 'driver_cnic'); ?></td>
														<td><?= supply_meta($result['id'], 'vehicle_number'); ?></td>
														<td>
															<?php

															if($result['receive_status'] == 0) {
																echo "<span class='badge badge-warning'>On Way</span>";
															}

															?>
														</td>
														<td>
															<div class="btn-group">
																<?php
																if($result['receive_status'] == 0) {
																?>
																<a href="stock-receiving.php?id=<?= $result['id']; ?>&action=receive" class="btn btn-success btn-sm receive_stock_btn">Receive</a>
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

			$(document).on('click', '.receive_supply_stock_container_close', function(){
				history.pushState({}, '', 'stock-receiving.php');
			});

			$(document).on('click', '.receive_stock_btn', function(){

				if(confirm('Are you sure to receive stock?')) {
					return true;
				} else {
					return false;
				}

			});


			$('#lot_number').on('blur', function(){
				var lot_number = $('#lot_number').val();

				if(lot_number != '') {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'check_lot_number', lot_number:lot_number },
						success: function(result) {
							if(result == 1) {
								$('#lot_number').addClass('is-invalid');
								$('#lot_number_msg').addClass('invalid-feedback').text('Lot Number Already Exist');
							} else {
								$('#lot_number').removeClass('is-invalid');
								$('#lot_number_msg').removeClass('invalid-feedback').text('');
							}
						}
					});
				} else {
					$('#lot_number').removeClass('is-invalid');
					$('#lot_number_msg').removeClass('invalid-feedback').text('');
				}
			});

		});
	</script>
</body>
</html>