<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Stock New';

	include "head.php";

	if(is_super_admin() || is_admin()) {
		header('Location: stock.php');
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
							<h1 class="m-0 d-inline">Procurement Detail</h1>
							<a href="stock-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item"><a href="stock.php">Stock</a></li>
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

					$stock_source = 'from_farmer';
					$stock_circle = $stock_lot_number = $stock_crop = $stock_class = $stock_variety = $stock_qty = $stock_price = $institute_name = $farmer_cnic = $farmer_name = $farmer_mobile_number = $farmer_address = $source_province = $source_district = $source_circle = '';

					if(isset($_POST['new_stock_form_btn'])) {

						// begin transaction
						mysqli_begin_transaction($conn);

						if(is_super_admin() || is_admin()) {
							$stock_circle = validate($_POST['stock_circle']);
						} else {
							$stock_circle = $circle_id;
						}
						$stock_source = validate($_POST['stock_source']);
						$stock_lot_number = validate($_POST['lot_number']);
						$stock_crop = validate($_POST['stock_crop']);
						$stock_class = validate($_POST['stock_class']);
						$stock_variety = validate($_POST['stock_variety']);
						// $stock_qty = validate($_POST['stock_qty']);
						// $stock_price = stock_price($stock_crop, $stock_class, 'purchase_price');
						// $stock_qty_price = $stock_price * $stock_qty;
						$stock_activity_season_id = current_activity_season_id($stock_crop);

						if( !empty($stock_lot_number) && !empty($stock_crop) && !empty($stock_class) && !empty($stock_variety) ) {

							if( $stock_source == 'from_farmer' ) {

								$farmer_cnic = validate($_POST['farmer_cnic']);
								$farmer_name = validate($_POST['farmer_name']);
								$farmer_mobile_number = validate($_POST['farmer_mobile_number']);
								$farmer_address = validate($_POST['farmer_address']);
								$smp_id = validate($_POST['smp_id']);
								$stock_qty = ( validate($_POST['stock_qty']) - ( validate($_POST['stock_qty']) / 100 ) - 0.115 );
								$stock_price = stock_price($stock_crop, $stock_class, 'purchase_price');
								$stock_qty_price = $stock_price * $stock_qty;

								if( !empty($farmer_cnic) && !empty($stock_qty) ) {

									if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM farmers WHERE farmer_cnic='$farmer_cnic' && delete_status='0'")) == 0) {
										$farmer_query = mysqli_query($conn, "INSERT INTO farmers(user_id, circle_id, farmer_cnic, farmer_name, farmer_mobile_number, farmer_address, time_created) VALUES('$user_id', '$stock_circle', '$farmer_cnic', '$farmer_name', '$farmer_mobile_number', '$farmer_address', '$time_created')");
									} else {
										$farmer_query = mysqli_query($conn, "UPDATE farmers SET farmer_name='$farmer_name', farmer_mobile_number='$farmer_mobile_number', farmer_address='$farmer_address' WHERE farmer_cnic='$farmer_cnic'");
									}

									if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stocks WHERE lot_number='$stock_lot_number' && circle_id='$stock_circle' && delete_status='0'")) == 0) {

										$stock_query = mysqli_query($conn, "INSERT INTO stocks(user_id, circle_id, stock_source, supplier_info, smp_id, activity_season, lot_number, crop, variety, class, stock_qty, time_created) VALUES('$user_id', '$stock_circle', '$stock_source', '$farmer_cnic', '$smp_id', '$stock_activity_season_id', '$stock_lot_number', '$stock_crop', '$stock_variety', '$stock_class', '$stock_qty', '$time_created')");
										$stock_id = mysqli_insert_id($conn);
										$stock_meta_query = mysqli_query($conn, "INSERT INTO stock_meta(stock_id, meta_key, meta_value) VALUES
											('$stock_id', 'stock_price', '$stock_price'), 
											('$stock_id', 'stock_qty_price', '$stock_qty_price')
										");

										$fscrd_report_query = mysqli_query($conn, "INSERT INTO fscrd_report(user_id, circle_id, stock_id, report_type, time_created) VALUES('$user_id', '$circle_id', '$stock_id', '2', '$time_created')");

										$stock_transaction_query = mysqli_query($conn, "INSERT INTO stock_transactions(user_id, circle_id, stock_qty, stock_status, class, stock_id, time_created) VALUES('$user_id', '$stock_circle', '$stock_qty', '0', '$stock_class', '$stock_id', '$time_created')");
										$stock_transaction_id = mysqli_insert_id($conn);

										if(isset($_POST['smp_complete'])) {
											$supply_query = mysqli_query($conn, "UPDATE supply SET smp_status='1' WHERE id='$smp_id'");
										}

									} else {
										// rollback transaction on error
										mysqli_rollback($conn);
										echo "<div class='alert alert-danger'>Lot Number Already Exist</div>";
									}

								} else {
									// rollback transaction on error
									mysqli_rollback($conn);
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
									return;
								}

							} else if( $stock_source == 'others' ) {

								$institute_name = validate($_POST['institute_name']);
								$farmer_cnic = validate($_POST['farmer_cnic']);
								$farmer_name = validate($_POST['farmer_name']);
								$farmer_mobile_number = validate($_POST['farmer_mobile_number']);
								$farmer_address = validate($_POST['farmer_address']);
								$smp_id = validate($_POST['smp_id']);
								$stock_qty = ( validate($_POST['stock_qty']) - ( validate($_POST['stock_qty']) / 100 ) - 0.115 );
								$stock_price = stock_price($stock_crop, $stock_class, 'purchase_price');
								$stock_qty_price = $stock_price * $stock_qty;

								if( !empty($farmer_cnic) && !empty($stock_qty) ) {

									if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM farmers WHERE farmer_cnic='$farmer_cnic' && delete_status='0'")) == 0) {
										$farmer_query = mysqli_query($conn, "INSERT INTO farmers(user_id, circle_id, farmer_cnic, farmer_name, farmer_mobile_number, farmer_address, time_created) VALUES('$user_id', '$stock_circle', '$farmer_cnic', '$farmer_name', '$farmer_mobile_number', '$farmer_address', '$time_created')");
									} else {
										$farmer_query = mysqli_query($conn, "UPDATE farmers SET farmer_name='$farmer_name', farmer_mobile_number='$farmer_mobile_number', farmer_address='$farmer_address' WHERE farmer_cnic='$farmer_cnic'");
									}

									if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stocks WHERE lot_number='$stock_lot_number' && circle_id='$stock_circle' && delete_status='0'")) == 0) {

										$stock_query = mysqli_query($conn, "INSERT INTO stocks(user_id, circle_id, stock_source, supplier_info, smp_id, activity_season, lot_number, crop, variety, class, stock_qty, time_created) VALUES('$user_id', '$stock_circle', '$stock_source', '$farmer_cnic', '$smp_id', '$stock_activity_season_id', '$stock_lot_number', '$stock_crop', '$stock_variety', '$stock_class', '$stock_qty', '$time_created')");
										$stock_id = mysqli_insert_id($conn);
										$stock_meta_query = mysqli_query($conn, "INSERT INTO stock_meta(stock_id, meta_key, meta_value) VALUES
											('$stock_id', 'institute_name', '$institute_name'), 
											('$stock_id', 'stock_price', '$stock_price'), 
											('$stock_id', 'stock_qty_price', '$stock_qty_price')
										");

										$fscrd_report_query = mysqli_query($conn, "INSERT INTO fscrd_report(user_id, circle_id, stock_id, report_type, time_created) VALUES('$user_id', '$circle_id', '$stock_id', '2', '$time_created')");

										$stock_transaction_query = mysqli_query($conn, "INSERT INTO stock_transactions(user_id, circle_id, stock_qty, stock_status, class, stock_id, time_created) VALUES('$user_id', '$stock_circle', '$stock_qty', '0', '$stock_class', '$stock_id', '$time_created')");
										$stock_transaction_id = mysqli_insert_id($conn);

										if(isset($_POST['smp_complete'])) {
											$supply_query = mysqli_query($conn, "UPDATE supply SET smp_status='1' WHERE id='$smp_id'");
										}

									} else {
										// rollback transaction on error
										mysqli_rollback($conn);
										echo "<div class='alert alert-danger'>Lot Number Already Exist</div>";
									}

								} else {
									// rollback transaction on error
									mysqli_rollback($conn);
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
									return;
								}

							} else if($stock_source == 'other_province') {

								$source_province = validate($_POST['source_province']);
								$stock_qty = validate($_POST['stock_qty']);
								$stock_price = stock_price($stock_crop, $stock_class, 'purchase_price');
								$stock_qty_price = $stock_price * $stock_qty;
								if(isset($_POST['is_cleaned'])) {
									$stock_status = 3;
								} else {
									$stock_status = 0;
								}

								if( !empty($source_province) && !empty($stock_qty) ) {

									if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stocks WHERE lot_number='$stock_lot_number' && circle_id='$stock_circle' && delete_status='0'")) == 0) {

										$stock_query = mysqli_query($conn, "INSERT INTO stocks(user_id, circle_id, stock_source, supplier_info, activity_season, lot_number, crop, variety, class, stock_qty, time_created) VALUES('$user_id', '$stock_circle', '$stock_source', '$source_province', '$stock_activity_season_id', '$stock_lot_number', '$stock_crop', '$stock_variety', '$stock_class', '$stock_qty', '$time_created')");
										$stock_id = mysqli_insert_id($conn);
										$stock_meta_query = mysqli_query($conn, "INSERT INTO stock_meta(stock_id, meta_key, meta_value) VALUES
											('$stock_id', 'stock_price', '$stock_price'), 
											('$stock_id', 'stock_qty_price', '$stock_qty_price')
										");

										$stock_transaction_query = mysqli_query($conn, "INSERT INTO stock_transactions(user_id, circle_id, stock_qty, stock_status, class, stock_id, time_created) VALUES('$user_id', '$stock_circle', '$stock_qty', '$stock_status', '$stock_class', '$stock_id', '$time_created')");
										$stock_transaction_id = mysqli_insert_id($conn);

									} else {
										// rollback transaction on error
										mysqli_rollback($conn);
										echo "<div class='alert alert-danger'>Lot Number Already Exist</div>";
									}

								} else {
									// rollback transaction on error
									mysqli_rollback($conn);
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
									return;
								}

							} else if($stock_source == 'other_circle') {

								$source_circle = validate($_POST['source_circle']);
								$stock_qty = validate($_POST['stock_qty']);
								$stock_price = stock_price($stock_crop, $stock_class, 'purchase_price');
								$stock_qty_price = $stock_price * $stock_qty;
								if(isset($_POST['is_cleaned'])) {
									$stock_status = 3;
								} else {
									$stock_status = 0;
								}

								if( !empty($source_circle) && !empty($stock_qty) ) {

									if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM stocks WHERE lot_number='$stock_lot_number' && circle_id='$stock_circle' && delete_status='0'")) == 0) {

										$stock_query = mysqli_query($conn, "INSERT INTO stocks(user_id, circle_id, stock_source, supplier_info, activity_season, lot_number, crop, variety, class, stock_qty, time_created) VALUES('$user_id', '$stock_circle', '$stock_source', '$source_circle', '$stock_activity_season_id', '$stock_lot_number', '$stock_crop', '$stock_variety', '$stock_class', '$stock_qty', '$time_created')");
										$stock_id = mysqli_insert_id($conn);
										$stock_meta_query = mysqli_query($conn, "INSERT INTO stock_meta(stock_id, meta_key, meta_value) VALUES
											('$stock_id', 'stock_price', '$stock_price'), 
											('$stock_id', 'stock_qty_price', '$stock_qty_price')
										");

										$stock_transaction_query = mysqli_query($conn, "INSERT INTO stock_transactions(user_id, circle_id, stock_qty, stock_status, class, stock_id, time_created) VALUES('$user_id', '$stock_circle', '$stock_qty', '$stock_status', '$stock_class', '$stock_id', '$time_created')");
										$stock_transaction_id = mysqli_insert_id($conn);

									} else {
										// rollback transaction on error
										mysqli_rollback($conn);
										echo "<div class='alert alert-danger'>Lot Number Already Exist</div>";
									}

								} else {
									// rollback transaction on error
									mysqli_rollback($conn);
									echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
									return;
								}

							}

							if($stock_query && $stock_meta_query && $stock_transaction_query) {
								// commit transaction
								mysqli_commit($conn);
								echo "<div class='alert alert-success'>Stock Successfully Added</div>";
								echo "<meta http-equiv='refresh' content='1'>";
							} else {
								// rollback transaction on error
								mysqli_rollback($conn);
								echo "<div class='alert alert-danger'>Please Try Again</div>";
							}

						} else {
							// rollback transaction on error
							mysqli_rollback($conn);
							echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
						}

					}

					?>
					
					<form class="form" enctype="multipart/form-data" id="new_stock_form" method="post">

						<div class="row">
							<div class="col-md-12 mb-3">
								<label>Procurement Source: <span class="text-danger">*</span></label>
								<select class="form-control" id="stock_source" name="stock_source">
									<option <?php if($stock_source == '') { echo 'selected'; } ?> value="">Select Stock Source</option>
									<option <?php if($stock_source == 'from_farmer') { echo 'selected'; } ?> value="from_farmer">From Farmer</option>
									<option <?php if($stock_source == 'other_district') { echo 'selected'; } ?> value="other_district">Other Districts</option>
									<option <?php if($stock_source == 'within_district') { echo 'selected'; } ?> value="within_district">Within Districts</option>
									<option <?php if($stock_source == 'other_province') { echo 'selected'; } ?> value="other_province">Other province</option>
									<option <?php if($stock_source == 'others') { echo 'selected'; } ?> value="others">Others</option>
								</select>
								<div id="stock_source_msg"></div>
							</div>
						</div>

						<div id="source_info_container">

							<?php

							if($stock_source == 'from_farmer' || $stock_source == '') {

							?>

								<div class="row">
									<div class="col-md-6 mb-3">
										<label>Farmer CNIC: <span class="text-danger">*</span></label>
										<input type="text" pattern="\d{5}-\d{7}-\d{1}" value="<?= $farmer_cnic; ?>" class="form-control farmer_cnic" placeholder="Enter Farmer CNIC" name="farmer_cnic" id="farmer_cnic">
										<div id="farmer_cnic_msg"></div>
									</div>
									<div class="col-md-6 mb-3">
										<label>Farmer Name:</label>
										<input type="text" pattern="[A-Za-z]+" value="<?= $farmer_name; ?>" class="form-control" placeholder="Enter Farmer Name" name="farmer_name" id="farmer_name">
										<div id="farmer_name_msg"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 mb-3">
										<label>Farmer Mobile Number:</label>
										<input type="tel" pattern="^0\d{10}$" value="<?= $farmer_mobile_number; ?>" class="form-control" placeholder="Enter Farmer Mobile Number" name="farmer_mobile_number" id="farmer_mobile_number">
										<div id="farmer_mobile_number_msg"></div>
									</div>
									<div class="col-md-6 mb-3">
										<label>Farmer Address:</label>
										<textarea class="form-control" name="farmer_address" id="farmer_address" placeholder="Enter Farmer Address"><?= $farmer_address; ?></textarea>
										<div id="farmer_address_msg"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12 mb-3">
										<label>SMP Record: <span class="text-danger">*</span></label>
										<select class="form-control smp_id" id="smp_id" name="smp_id">
											<option value="">Select SMP Record</option>
											<?php

											if($farmer_cnic != '') {
												$smp_record_query = mysqli_query($conn, "SELECT * FROM supply WHERE receive_source='to_farmer' && receiver_detail='$farmer_cnic' && smp_status='1' && active_status='1' && delete_status='0'");
												if(mysqli_num_rows($smp_record_query) > 0) {
													while($smp_record_result = mysqli_fetch_assoc($smp_record_query)) {
														echo "<option value='".$smp_record_result['id']."'>".stock_crop(stock_detail($smp_record_result['stock_id'], 'type')).' - '.stock_variety(stock_detail($smp_record_result['stock_id'], 'variety'))."</option>";
													}
												}
											}

											?>
										</select>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12 mb-3">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" id="smp_complete" name="smp_complete" value="1" checked>
											<label class="custom-control-label" for="smp_complete">SMP Complete</label>
										</div>
									</div>
								</div>
							<?php

							} else if($stock_source == 'others') {

							?>

								<div class="row">
									<div class="col-md-4 mb-3">
										<label>Institute Name: <span class="text-danger">*</span></label>
										<input type="text" pattern="[A-Za-z]+" value="<?= $institute_name; ?>" class="form-control" placeholder="Enter Institute Name" name="institute_name" id="institute_name">
										<div id="institute_name_msg"></div>
									</div>
									<div class="col-md-4 mb-3">
										<label>Focal Person CNIC: <span class="text-danger">*</span></label>
										<input type="text" pattern="\d{5}-\d{7}-\d{1}" value="<?= $farmer_cnic; ?>" class="form-control" placeholder="Enter Focal Person CNIC" name="farmer_cnic" id="farmer_cnic">
										<div id="farmer_cnic_msg"></div>
									</div>
									<div class="col-md-4 mb-3">
										<label>Focal Person Name:</label>
										<input type="text" pattern="[A-Za-z]+" value="<?= $farmer_name; ?>" class="form-control" placeholder="Enter Focal Person Name" name="farmer_name" id="farmer_name">
										<div id="farmer_name_msg"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 mb-3">
										<label>Focal Person Mobile Number:</label>
										<input type="tel" pattern="^0\d{10}$" value="<?= $farmer_mobile_number; ?>" class="form-control" placeholder="Enter Farmer Mobile Number" name="farmer_mobile_number" id="farmer_mobile_number">
										<div id="farmer_mobile_number_msg"></div>
									</div>
									<div class="col-md-6 mb-3">
										<label>Institute Address:</label>
										<textarea class="form-control" name="farmer_address" id="farmer_address" placeholder="Enter Farmer Address"><?= $farmer_address; ?></textarea>
										<div id="farmer_address_msg"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12 mb-3">
										<label>SMP Record: <span class="text-danger">*</span></label>
										<select class="form-control smp_id" id="smp_id" name="smp_id">
											<option value="">Select SMP Record</option>
											<?php

											if($farmer_cnic != '') {
												$smp_record_query = mysqli_query($conn, "SELECT * FROM supply WHERE receive_source='to_farmer' && receiver_detail='$farmer_cnic' && smp_status='1' && active_status='1' && delete_status='0'");
												if(mysqli_num_rows($smp_record_query) > 0) {
													while($smp_record_result = mysqli_fetch_assoc($smp_record_query)) {
														echo "<option value='".$smp_record_result['id']."'>".stock_crop(stock_detail($smp_record_result['stock_id'], 'type')).' - '.stock_variety(stock_detail($smp_record_result['stock_id'], 'variety'))."</option>";
													}
												}
											}

											?>
										</select>
									</div>
								</div>

								<div class="row">
									<div class="col-md-12 mb-3">
										<div class="custom-control custom-switch">
											<input type="checkbox" class="custom-control-input" id="smp_complete" name="smp_complete" value="1" checked>
											<label class="custom-control-label" for="smp_complete">SMP Complete</label>
										</div>
									</div>
								</div>
							<?php

							} else if($stock_source == 'other_province') {
							?>
							<div class="row">
								<div class="col-md-12 mb-3">
									<label>Select Province: <span class="text-danger">*</span></label>
									<select class="form-control" id="source_province" name="source_province">
										<option <?php if($source_province == '') { echo 'selected'; } ?> value="">Select Province</option>
										<option <?php if($source_province == 'Balochistan') { echo 'selected'; } ?> value="Balochistan">Balochistan</option>
										<option <?php if($source_province == 'Punjab') { echo 'selected'; } ?> value="Punjab">Punjab</option>
										<option <?php if($source_province == 'Sindh') { echo 'selected'; } ?> value="Sindh">Sindh</option>
									</select>
									<div id="source_province_msg"></div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 mb-3">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_cleaned" name="is_cleaned" value="1" checked>
										<label class="custom-control-label" for="is_cleaned">Is Stock Cleaned?</label>
									</div>
								</div>
							</div>
							<?php
							} else if($stock_source == 'within_district') {
							?>
							<div class="row">
								<div class="col-md-12 mb-3">
									<label>Select AO Circle: <span class="text-danger">*</span></label>
									<select class="form-control" id="source_circle" name="source_circle">
										<option <?php if($source_circle == '') { echo 'selected'; } ?> value="">Select AO Circle</option>
										<?php

										$source_circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE district='$user_district' && active_status='1' && delete_status='0'");
										if(mysqli_num_rows($source_circle_query) > 0) {
											while($source_circle_result = mysqli_fetch_assoc($source_circle_query)) {
												?>
												<option <?php if($source_circle_result['id'] == $source_circle) { echo 'selected'; } ?> value="<?= $source_circle_result['id'] ?>"><?= $source_circle_result['name']; ?></option>
												<?php
											}
										}

										?>
									</select>
									<div id="source_circle_msg"></div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 mb-3">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_cleaned" name="is_cleaned" value="1" checked>
										<label class="custom-control-label" for="is_cleaned">Is Stock Cleaned?</label>
									</div>
								</div>
							</div>
							<?php
							} else if($stock_source == 'other_district') {
							?>
							<div class="row">
								<div class="col-md-6 mb-3">
									<label>Select District: <span class="text-danger">*</span></label>
									<select class="form-control source_district" id="source_district" name="source_district">
										<option value="">Select District</option>
										<?php

										$out_district_query = mysqli_query($conn, "SELECT * FROM configurations WHERE type='district' && active_status='1' && delete_status='0' && id!='$user_district'");
										if(mysqli_num_rows($out_district_query) > 0) {
											while($out_district_result = mysqli_fetch_assoc($out_district_query)) {
												if($out_district_result['id'] == $source_district) { $receiver_district_selected = 'selected'; } else { $receiver_district_selected = ''; }
												echo "<option ".$receiver_district_selected." value='".$out_district_result['id']."'>".$out_district_result['name']."</option>";
											}
										}

										?>
									</select>
									<div id="receiver_district_msg"></div>
								</div>
								<div class="col-md-6 mb-3">
									<label>Select AO Office: <span class="text-danger">*</span></label>
									<select class="form-control receiver_detail" id="receiver_detail" name="receiver_detail">
										<option <?php if( empty( $receiver_detail ) ) { echo 'selected'; } ?> value="">Select AO Office</option>
										<?php

										$other_circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE delete_status='0' && id!='$circle_id' && district='$source_district'");

										if(mysqli_num_rows($other_circle_query) > 0) {
											while($other_circle_result = mysqli_fetch_assoc($other_circle_query)) {
												if($other_circle_result['id'] == $receiver_detail) { $receiver_circle_selected = 'selected'; } else { $receiver_circle_selected = ''; }
												echo "<option ".$receiver_circle_selected." value='".$other_circle_result['id']."'>".$other_circle_result['name']."</option>";
											}
										}

										?>
									</select>
									<div id="receiver_detail_msg"></div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-12 mb-3">
									<div class="custom-control custom-switch">
										<input type="checkbox" class="custom-control-input" id="is_cleaned" name="is_cleaned" value="1" checked>
										<label class="custom-control-label" for="is_cleaned">Is Stock Cleaned?</label>
									</div>
								</div>
							</div>
							<?php
							}

							?>

						</div>

						<div class="row">
							<div class="col-md-4 mb-3">
								<label>Class: <span class="text-danger">*</span></label>
								<select class="form-control" id="stock_class" name="stock_class">
									<option <?php if($stock_class == '') { echo 'selected'; } ?> value="">Select Class</option>
									<?php

									$class_query = mysqli_query($conn, "SELECT id, class_name FROM stock_class WHERE active_status='1' && delete_status='0'");
									if(mysqli_num_rows($class_query) > 0) {
										while($class_result = mysqli_fetch_assoc($class_query)) {
											if($stock_class == $class_result['class_name']) { $stock_class_selected = 'selected'; } else { $stock_class_selected = ''; }
											echo "<option ".$stock_class_selected." value='".$class_result['id']."'>".$class_result['class_name']."</option>";
										}
									}

									?>
								</select>
								<div id="stock_class_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Crop: <span class="text-danger">*</span></label>
								<select class="form-control" id="stock_crop" name="stock_crop">
									<option <?php if($stock_crop == '') { echo 'selected'; } ?> value="">Select Crop</option>
									<?php

									$type_query = mysqli_query($conn, "SELECT id, crop FROM stock_crop WHERE active_status='1' && delete_status='0'");
									if(mysqli_num_rows($type_query) > 0) {
										while($type_result = mysqli_fetch_assoc($type_query)) {
											if($stock_crop == $type_result['crop']) { $stock_crop_selected = 'selected'; } else { $stock_crop_selected = ''; }

											$stock_price = '';
											$class_price_query = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
											if(mysqli_num_rows($class_price_query) > 0) {
												while($class_price_result = mysqli_fetch_assoc($class_price_query)) {
													$class_id = $class_price_result['id'];
													$type_id = $type_result['id'];
													$stock_price_query = mysqli_query($conn, "SELECT * FROM stock_price WHERE stock_crop_id='$type_id' && stock_class_id='$class_id' && active_status='1' && delete_status='0'");
													if(mysqli_num_rows($stock_price_query) > 0) {
														while($stock_price_result = mysqli_fetch_assoc($stock_price_query)) {
															$stock_price .= "data-class_".$class_id."_purchase_price='".$stock_price_result['purchase_price']."' data-class_".$class_id."_sale_price='".$stock_price_result['sale_price']."'";
														}
													} else {
														$stock_price .= "data-class_".$class_id."_purchase_price='0' data-class_".$class_id."_sale_price='0'";
													}
												}
											}

											echo "<option ".$stock_price." ".$stock_crop_selected." value='".$type_result['id']."'>".$type_result['crop']."</option>";
										}
									}

									?>
								</select>
								<div id="stock_crop_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Variety: <span class="text-danger">*</span></label>
								<select class="form-control" id="stock_variety" name="stock_variety">
									<option <?php if($stock_variety == '') { echo 'selected'; } ?> value="">Select Variety</option>
								</select>
								<div id="stock_variety_msg"></div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-6 mb-3">
								<label>Lot/FIR Number: <span class="text-danger">*</span></label>
								<input type="text" pattern="[0-9]+" value="<?= $stock_lot_number; ?>" class="form-control" placeholder="Enter Lot Number" id="lot_number" name="lot_number">
								<div id="lot_number_msg"></div>
							</div>
							<div class="col-md-6 mb-3">
								<label>Qty: <span class="text-danger">*</span></label>
								<input type="text" pattern="[0-9]+" value="<?= $stock_qty; ?>" class="form-control" placeholder="Enter Stock Qty" id="stock_qty" name="stock_qty">
								<small class="form-text text-muted">Enter Stock in (KGs)</small>
								<div id="stock_qty_msg"></div>
							</div>
						</div>

						<button class="btn btn-primary" type="submit" name="new_stock_form_btn" id="new_stock_form_btn">Submit</button>

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

			$('#stock_source').on('change', function(){
				var stock_source = $('#stock_source').val();
				if(stock_source == 'from_farmer') {
					$('#source_info_container').html(
						'<div class="row">' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer CNIC: <span class="text-danger">*</span></label>' + 
								'<input type="text" pattern="\d{5}-\d{7}-\d{1}" value="<?= $farmer_cnic; ?>" class="form-control farmer_cnic" placeholder="Enter Farmer CNIC" name="farmer_cnic" id="farmer_cnic">' + 
								'<div id="farmer_cnic_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer Name:</label>' + 
								'<input type="text" pattern="[A-Za-z]+" value="<?= $farmer_name; ?>" class="form-control" placeholder="Enter Farmer Name" name="farmer_name" id="farmer_name">' + 
								'<div id="farmer_name_msg"></div>' + 
							'</div>' + 
						'</div>' + 

						'<div class="row">' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer Mobile Number:</label>' + 
								'<input type="tel" pattern="^0\d{10}$" value="<?= $farmer_mobile_number; ?>" class="form-control" placeholder="Enter Farmer Mobile Number" name="farmer_mobile_number" id="farmer_mobile_number">' + 
								'<div id="farmer_mobile_number_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer Address:</label>' + 
								'<textarea class="form-control farmer_cnic" name="farmer_address" id="farmer_address" placeholder="Enter Farmer Address"><?= $farmer_address; ?></textarea>' + 
								'<div id="farmer_address"></div>' + 
							'</div>' + 
						'</div>' + 

						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<label>SMP Record: <span class="text-danger">*</span></label>' + 
								'<select class="form-control smp_id" id="smp_id" name="smp_id">' + 
									'<option value="">Select SMP Record</option>' + 
									<?php

									if($farmer_cnic != '') {
										$smp_record_query = mysqli_query($conn, "SELECT * FROM supply WHERE receive_source='to_farmer' && receiver_detail='$farmer_cnic' && smp_status='1' && active_status='1' && delete_status='0'");
										if(mysqli_num_rows($smp_record_query) > 0) {
											while($smp_record_result = mysqli_fetch_assoc($smp_record_query)) {
												echo "'<option value='".$smp_record_result['id']."'>".stock_crop(stock_detail($smp_record_result['parent_id'], 'type')).' - '.stock_variety(stock_detail($smp_record_result['parent_id'], 'variety'))."</option>' + ";
											}
										}
									}

									?>
								'</select>' + 
							'</div>' + 
						'</div>' + 
						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<div class="custom-control custom-switch">' + 
									'<input type="checkbox" class="custom-control-input" id="smp_complete" name="smp_complete" value="1" checked>' + 
									'<label class="custom-control-label" for="smp_complete">SMP Complete</label>' + 
								'</div>' + 
							'</div>' + 
						'</div>'
					);
				} else if(stock_source == 'others') {
					$('#source_info_container').html(
						'<div class="row">' + 
							'<div class="col-md-4 mb-3">' + 
								'<label>Institute Name: <span class="text-danger">*</span></label>' + 
								'<input type="text" pattern="[A-Za-z]+" value="<?= $institute_name; ?>" class="form-control institute_name" placeholder="Enter Institute Name" name="institute_name" id="institute_name">' + 
								'<div id="farmer_cnic_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-4 mb-3">' + 
								'<label>Focal Person CNIC: <span class="text-danger">*</span></label>' + 
								'<input type="text" pattern="\d{5}-\d{7}-\d{1}" value="<?= $farmer_cnic; ?>" class="form-control farmer_cnic" placeholder="Enter Focal Person CNIC" name="farmer_cnic" id="farmer_cnic">' + 
								'<div id="farmer_cnic_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-4 mb-3">' + 
								'<label>Focal Person Name:</label>' + 
								'<input type="text" pattern="[A-Za-z]+" value="<?= $farmer_name; ?>" class="form-control" placeholder="Enter Focal Person Name" name="farmer_name" id="farmer_name">' + 
								'<div id="farmer_name_msg"></div>' + 
							'</div>' + 
						'</div>' + 

						'<div class="row">' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Focal Person Mobile Number:</label>' + 
								'<input type="tel" pattern="^0\d{10}$" value="<?= $farmer_mobile_number; ?>" class="form-control" placeholder="Enter Focal Person Mobile Number" name="farmer_mobile_number" id="farmer_mobile_number">' + 
								'<div id="farmer_mobile_number_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Focal Person Address:</label>' + 
								'<textarea class="form-control farmer_cnic" name="farmer_address" id="farmer_address" placeholder="Enter Focal Person Address"><?= $farmer_address; ?></textarea>' + 
								'<div id="farmer_address"></div>' + 
							'</div>' + 
						'</div>' + 

						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<label>SMP Record: <span class="text-danger">*</span></label>' + 
								'<select class="form-control smp_id" id="smp_id" name="smp_id">' + 
									'<option value="">Select SMP Record</option>' + 
									<?php

									if($farmer_cnic != '') {
										$smp_record_query = mysqli_query($conn, "SELECT * FROM supply WHERE receive_source='to_farmer' && receiver_detail='$farmer_cnic' && smp_status='1' && active_status='1' && delete_status='0'");
										if(mysqli_num_rows($smp_record_query) > 0) {
											while($smp_record_result = mysqli_fetch_assoc($smp_record_query)) {
												echo "'<option value='".$smp_record_result['id']."'>".stock_crop(stock_detail($smp_record_result['parent_id'], 'type')).' - '.stock_variety(stock_detail($smp_record_result['parent_id'], 'variety'))."</option>' + ";
											}
										}
									}

									?>
								'</select>' + 
							'</div>' + 
						'</div>' + 
						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<div class="custom-control custom-switch">' + 
									'<input type="checkbox" class="custom-control-input" id="smp_complete" name="smp_complete" value="1" checked>' + 
									'<label class="custom-control-label" for="smp_complete">SMP Complete</label>' + 
								'</div>' + 
							'</div>' + 
						'</div>'
					);
				} else if(stock_source == 'other_province') {
					$('#source_info_container').html(
						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<label>Select Province: <span class="text-danger">*</span></label>' + 
								'<select class="form-control" id="source_province" name="source_province">' + 
									'<option <?php if($source_province == '') { echo 'selected'; } ?> value="">Select Province</option>' + 
									'<option <?php if($source_province == 'Balochistan') { echo 'selected'; } ?> value="Balochistan">Balochistan</option>' + 
									'<option <?php if($source_province == 'Punjab') { echo 'selected'; } ?> value="Punjab">Punjab</option>' + 
									'<option <?php if($source_province == 'Sindh') { echo 'selected'; } ?> value="Sindh">Sindh</option>' + 
								'</select>' + 
								'<div id="source_province_msg"></div>' + 
							'</div>' + 
						'</div>' + 
						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<div class="custom-control custom-switch">' + 
									'<input type="checkbox" class="custom-control-input" id="is_cleaned" name="is_cleaned" value="1" checked>' + 
									'<label class="custom-control-label" for="is_cleaned">Cleaned Stock</label>' + 
								'</div>' + 
							'</div>' + 
						'</div>'
					);
				} else if(stock_source == 'within_district') {
					$('#source_info_container').html(
						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<label>Select AO Office: <span class="text-danger">*</span></label>' + 
								'<select class="form-control" id="source_circle" name="source_circle">' + 
									'<option <?php if($source_circle == '') { echo 'selected'; } ?> value="">Select AO Office</option>' + 
									<?php

									$source_circle_query = mysqli_query($conn, "SELECT * FROM circles WHERE district='$user_district' && active_status='1' && delete_status='0'");
									if(mysqli_num_rows($source_circle_query) > 0) {
										while($source_circle_result = mysqli_fetch_assoc($source_circle_query)) {
											?>
											'<option <?php if($source_circle_result['id'] == $source_circle) { echo 'selected'; } ?> value="<?= $source_circle_result['id'] ?>"><?= $source_circle_result['name']; ?></option>' + 
											<?php
										}
									}

									?> 
								'</select>' + 
								'<div id="source_circle_msg"></div>' + 
							'</div>' + 
						'</div>' + 
						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<div class="custom-control custom-switch">' + 
									'<input type="checkbox" class="custom-control-input" id="is_cleaned" name="is_cleaned" value="1" checked>' + 
									'<label class="custom-control-label" for="is_cleaned">Cleaned Stock</label>' + 
								'</div>' + 
							'</div>' + 
						'</div>'
					);
				} else if(stock_source == 'other_district') {
					$('#source_info_container').html(
						'<div class="row">' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Select District: <span class="text-danger">*</span></label>' + 
								'<select class="form-control source_district" id="source_district" name="source_district">' + 
									'<option value="">Select District</option>' + 
									<?php

									$out_district_query = mysqli_query($conn, "SELECT * FROM configurations WHERE type='district' && active_status='1' && delete_status='0' && id!='$user_district'");
									if(mysqli_num_rows($out_district_query) > 0) {
										while($out_district_result = mysqli_fetch_assoc($out_district_query)) {
											if($out_district_result['id'] == $source_district) { $receiver_district_selected = 'selected'; } else { $receiver_district_selected = ''; }
											?>
											'<option <?= $receiver_district_selected ?> value="<?= $out_district_result['id'] ?>"><?= $out_district_result['name']; ?></option>' + 
											<?php
										}
									}

									?>
								'</select>' + 
								'<div id="source_district_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Select AO Office: <span class="text-danger">*</span></label>' + 
								'<select class="form-control source_circle" id="source_circle" name="source_circle">' + 
									'<option value="">Select AO Office</option>' + 
								'</select>' + 
								'<div id="source_circle_msg"></div>' + 
							'</div>' + 
						'</div>' + 
						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<div class="custom-control custom-switch">' + 
									'<input type="checkbox" class="custom-control-input" id="is_cleaned" name="is_cleaned" value="1" checked>' + 
									'<label class="custom-control-label" for="is_cleaned">Cleaned Stock</label>' + 
								'</div>' + 
							'</div>' + 
						'</div>'
					);
				}

				if(stock_source == 'other_province' || stock_source == 'within_district' || stock_source == 'other_district') {
					$('#stock_crop').html(
						'<option <?php if($stock_crop == '') { echo 'selected'; } ?> value="">Select Stock Crop</option>'
						<?php

						$type_query = mysqli_query($conn, "SELECT id, crop FROM stock_crop WHERE active_status='1' && delete_status='0'");
						if(mysqli_num_rows($type_query) > 0) {
							while($type_result = mysqli_fetch_assoc($type_query)) {
								if($stock_crop == $type_result['crop']) { $stock_crop_selected = 'selected'; } else { $stock_crop_selected = ''; }

								$stock_price = '';
								$class_price_query = mysqli_query($conn, "SELECT * FROM stock_class WHERE active_status='1' && delete_status='0'");
								if(mysqli_num_rows($class_price_query) > 0) {
									while($class_price_result = mysqli_fetch_assoc($class_price_query)) {
										$class_id = $class_price_result['id'];
										$type_id = $type_result['id'];
										$stock_price_query = mysqli_query($conn, "SELECT * FROM stock_price WHERE stock_crop_id='$type_id' && stock_class_id='$class_id' && active_status='1' && delete_status='0'");
										if(mysqli_num_rows($stock_price_query) > 0) {
											while($stock_price_result = mysqli_fetch_assoc($stock_price_query)) {
												$stock_price .= "data-class_".$class_id."_purchase_price=\"".$stock_price_result['purchase_price']."\" data-class_".$class_id."_sale_price=\"".$stock_price_result['sale_price']."\"";
											}
										} else {
											$stock_price .= "data-class_".$class_id."_purchase_price=\"0\" data-class_".$class_id."_sale_price=\"0\"";
										}
									}
								}

								echo " + '<option ".$stock_price." ".$stock_crop_selected." value=\"".$type_result['id']."\">".$type_result['crop']."</option>'";
							}
						}

						?>
					);
					$('#stock_variety').html("<option value=''>Select Variety</option>");
				}
				
			});

			$(document).on('change', '.source_district', function(){
				var source_district = $(this).val();
				if(source_district != '' && source_district != 0) {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'display_out_district_circles', district_id:source_district },
						success: function(result) {
							$('.source_circle').html(result);
						}
					});
				}
			});

			$(document).on('blur', '.farmer_cnic', function(){
				var stock_source = $('#stock_source').val();
				if(stock_source == '') {
					stock_source = 'from_farmer';
				}
				var farmer_cnic = $(this).val();

				if(farmer_cnic != '') {

					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'get_farmer_info', farmer_cnic:farmer_cnic },
						success: function(result) {
							result = JSON.parse(result);

							if(result.status == 'success') {
								$('#farmer_name').val(result.farmer_name);
								$('#farmer_mobile_number').val(result.farmer_mobile_number);
								$('#farmer_address').val(result.farmer_address);
							} else {
								$('#farmer_name').val('');
								$('#farmer_mobile_number').val('');
								$('#farmer_address').val('');
								$('#smp_id').html("<option value=''>Select SMP Record</option>");
							}
						}
					});

					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'get_smp_record', farmer_cnic:farmer_cnic, stock_source:stock_source },
						success: function(result) {
							$('#smp_id').html(result);
						}
					});

				} else {
					$('.farmer_cnic').val('');
					$('#farmer_mobile_number').val('');
					$('#farmer_address').val('');
					$('#smp_id').html("<option value=''>Select SMP Record</option>");
				}
			});

			$(document).on('change', '.smp_id', function(){
				var smp_id = $('#smp_id').val();
				var farmer_cnic = $('.farmer_cnic').val();

				if(smp_id != '' && smp_id != 0 && farmer_cnic != '') {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action: 'get_smp_stock_crop', smp_id:smp_id, farmer_cnic:farmer_cnic }, 
						success: function(result) {
							$('#stock_crop').html(result);
						}
					});

					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action: 'get_smp_stock_variety', smp_id:smp_id, farmer_cnic:farmer_cnic }, 
						success: function(result) {
							$('#stock_variety').html(result);
						}
					});
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
							}
						}
					});
				}
			});

			$('#stock_class, #stock_crop, #stock_qty').on('change keyup', function(){

				var stock_crop = $('#stock_crop option:selected').val();
				var stock_class = $('#stock_class option:selected').val();
				var stock_price = parseInt($('#stock_crop option:selected').data('class_'+stock_class+'_purchase_price'));
				var stock_qty = $('#stock_qty').val();

				if(stock_crop != '' && stock_crop != 0 && stock_class != '' && stock_class != 0 && stock_qty != '') {
					$('#stock_qty_price').val(parseInt(stock_qty) * stock_price);
					$('#stock_price').val(stock_price);
				} else {
					$('#stock_qty_price, #stock_price').val(0);
				}

			});

			$('#stock_crop').on('change', function(){
				var stock_crop = $('#stock_crop option:selected').val();
				if(stock_crop != '' && stock_crop != 0) {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'display_stock_variety', stock_crop:stock_crop },
						success: function(result) {
							$('#stock_variety').html(result);
						}
					});
				}
			});

			$('#stock_source').on('focus', function(){
				$('#stock_source').removeClass('is-invalid');
				$('#stock_source_msg').removeClass('invalid-feedback').text('');
			});

			$('#farmer_cnic').on('focus', function(){
				$('#farmer_cnic').removeClass('is-invalid');
				$('#farmer_cnic_msg').removeClass('invalid-feedback').text('');
			});

			$('#stock_province').on('focus', function(){
				$('#stock_province').removeClass('is-invalid');
				$('#stock_province_msg').removeClass('invalid-feedback').text('');
			});

			$('#lot_number').on('focus', function(){
				$('#lot_number').removeClass('is-invalid');
				$('#lot_number_msg').removeClass('invalid-feedback').text('');
			});

			$('#stock_qty').on('focus', function(){
				$('#stock_qty').removeClass('is-invalid');
				$('#stock_qty_msg').removeClass('invalid-feedback').text('');
			});

			$('#stock_crop').on('focus', function(){
				$('#stock_crop').removeClass('is-invalid');
				$('#stock_crop_msg').removeClass('invalid-feedback').text('');
			});

			$('#stock_class').on('focus', function(){
				$('#stock_class').removeClass('is-invalid');
				$('#stock_class_msg').removeClass('invalid-feedback').text('');
			});

			$('#new_stock_form').on('submit', function(){
				var bool = 0;

				if($('#stock_source').val() == '') {
					$('#stock_source').addClass('is-invalid');
					$('#stock_source_msg').addClass('invalid-feedback').text('Please Select Stock Source');
					bool = 1;
				} else if($('#stock_source').val() == 'from_farmer') {
					if($('#farmer_cnic').val() == '') {
						$('#farmer_cnic').addClass('is-invalid');
						$('#farmer_cnic_msg').addClass('invalid-feedback').text('Farmer CNIC Required');
						bool = 1;
					} else {
						$('#farmer_cnic').removeClass('is-invalid');
						$('#farmer_cnic_msg').removeClass('invalid-feedback').text('');
					}
				} else if($('#stock_source').val() == 'other_province') {
					if($('#source_province').val() == '') {
						$('#source_province').addClass('is-invalid');
						$('#source_province_msg').addClass('invalid-feedback').text('Please Select Province');
						bool = 1;
					} else {
						$('#source_province').removeClass('is-invalid');
						$('#source_province_msg').removeClass('invalid-feedback').text('');
					}
				} else if($('#stock_source').val() == 'other_circle') {
					if($('#source_circle').val() == '') {
						$('#source_circle').addClass('is-invalid');
						$('#source_circle_msg').addClass('invalid-feedback').text('Please Select Source circle');
						bool = 1;
					} else {
						$('#source_circle').removeClass('is-invalid');
						$('#source_circle_msg').removeClass('invalid-feedback').text('');
					}
				}

				if($('#lot_number').val() == '') {
					$('#lot_number').addClass('is-invalid');
					$('#lot_number_msg').addClass('invalid-feedback').text('Lot/Referance Number Required');
					bool = 1;
				} else {
					$('#lot_number').removeClass('is-invalid');
					$('#lot_number_msg').removeClass('invalid-feedback').text('');
				}

				if($('#stock_qty').val() == '') {
					$('#stock_qty').addClass('is-invalid');
					$('#stock_qty_msg').addClass('invalid-feedback').text('Stock Quantity Required');
					bool = 1;
				} else {
					$('#stock_qty').removeClass('is-invalid');
					$('#stock_qty_msg').removeClass('invalid-feedback').text('');
				}

				if($('#stock_crop').val() == '') {
					$('#stock_crop').addClass('is-invalid');
					$('#stock_crop_msg').addClass('invalid-feedback').text('Stock Type Required');
					bool = 1;
				} else {
					$('#stock_crop').removeClass('is-invalid');
					$('#stock_crop_msg').removeClass('invalid-feedback').text('');
				}

				if($('#stock_class').val() == '') {
					$('#stock_class').addClass('is-invalid');
					$('#stock_class_msg').addClass('invalid-feedback').text('Stock Class Required');
					bool = 1;
				} else {
					$('#stock_class').removeClass('is-invalid');
					$('#stock_class_msg').removeClass('invalid-feedback').text('');
				}

				if($('#stock_variety').val() == '') {
					$('#stock_variety').addClass('is-invalid');
					$('#stock_variety_msg').addClass('invalid-feedback').text('Stock Variety Required');
					bool = 1;
				} else {
					$('#stock_variety').removeClass('is-invalid');
					$('#stock_variety_msg').remove('invalid-feedback').text('');
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