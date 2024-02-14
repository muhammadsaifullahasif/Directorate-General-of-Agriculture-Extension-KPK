<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Stock Supply Edit';

	include "head.php";

	if(isset($_GET['id']) && !empty( $_GET['id'] ) && $_GET['id'] != 0) {

		$id = validate($_GET['id']);

		$query = mysqli_query($conn, "SELECT * FROM supply WHERE id='$id' && delete_status='0'");

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
		} else {
			header('Location: supply.php');
		}

	} else {
		header('Location: supply.php');
	}

	if(is_super_admin() || is_admin() || is_storekeeper()) {
		header('Location: supply.php');
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
							<a href="stock-new.php" class="btn btn-outline-dark btn-sm mb-3 ml-2">Add New</a>
						</div><!-- /.col -->
						<div class="col-sm-6">
							<ol class="breadcrumb float-sm-right">
								<li class="breadcrumb-item"><a href="index.php"><i class="fas fa-home"></i></a></li>
								<li class="breadcrumb-item"><a href="supply.php">Supply</a></li>
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

					$stock_circle = $supply_source = $form_1_number = $farmer_cnic = $farmer_name = $farmer_mobile_number = $farmer_address = $receiver_province = $receiver_circle = $driver_cnic = $driver_name = $driver_mobile_number = $driver_address = $vehicle_number = $stock_id = $supply_quantity = $stock_price = '';


					if(isset($_POST['stock_supply_form_btn'])) {

						// begin transaction
						// mysqli_begin_transaction($conn);

						if(is_super_admin() || is_admin()) {
							$stock_circle = validate($_POST['stock_circle']);
						} else {
							$stock_circle = $circle_id;
						}
						$supply_source = validate($_POST['supply_source']);
						$stock_id = validate($_POST['stock_id']);
						$parent_stock_id = stock_transaction_detail($stock_id, 'stock_id');
						$stock_lot_number = stock_detail($parent_stock_id, 'lot_number');
						$stock_crop = stock_detail($parent_stock_id, 'type');
						$stock_variety = stock_detail($parent_stock_id, 'variety');
						$stock_class = stock_detail($parent_stock_id, 'class');
						$stock_status = stock_transaction_detail($stock_id, 'stock_status');
						$available_stock_qty = stock_transaction_detail($stock_id, 'stock_qty');
						$stock_price = supply_meta($id, 'stock_sale_price');
						$supply_quantity = validate($_POST['supply_quantity']);
						$supply_stock_price = (int)$stock_price * (int)$supply_quantity;

						if( !empty($stock_circle) && !empty($stock_id) && !empty($parent_stock_id) && !empty($supply_quantity) ) {

							if( $supply_quantity > (stock_transaction_detail($stock_id, 'stock_qty') + $result['stock_qty']) ) {
								echo "<div class='alert alert-danger'>Stock must be less than or equal to available stock quantity</div>";
							} else {

								$new_stock_qty = ( $available_stock_qty + $result['stock_qty'] ) - $supply_quantity;

								$update_stock_sql = "UPDATE stock_transactions SET stock_qty='$new_stock_qty'";
								if($new_stock_qty == '0') {
									$update_stock_sql .= ", active_status='2' ";
								} else {
									$update_stock_sql .= ", active_status='1' ";
								}
								$update_stock_sql .= " WHERE id='$stock_id'";
								$update_stock_query = mysqli_query($conn, $update_stock_sql);

								if($supply_source == 'to_farmer') {

									$farmer_cnic = validate($_POST['farmer_cnic']);
									$farmer_name = validate($_POST['farmer_name']);
									$farmer_mobile_number = validate($_POST['farmer_mobile_number']);
									$farmer_address = validate($_POST['farmer_address']);

									if( !empty($farmer_cnic) ) {

										if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM farmers WHERE farmer_cnic='$farmer_cnic' && delete_status='0'")) == 0) {
											$farmer_query = mysqli_query($conn, "INSERT INTO farmers(user_id, circle_id, farmer_cnic, farmer_name, farmer_mobile_number, farmer_address, time_created) VALUES('$user_id', '$stock_circle', '$farmer_cnic', '$farmer_name', '$farmer_mobile_number', '$farmer_address', '$time_created')");
										} else {
											$farmer_query = mysqli_query($conn, "UPDATE farmers SET farmer_name='$farmer_name', farmer_mobile_number='$farmer_mobile_number', farmer_address='$farmer_address' WHERE farmer_cnic='$farmer_cnic'");
										}

										$supply_query = mysqli_query($conn, "UPDATE supply SET circle_id='$stock_circle', receive_source='$to_farmer', receiver_detail='$farmer_cnic', parent_id='$parent_stock_id', stock_id='$stock_id', stock_qty='$supply_quantity', receiver_info='$farmer_cnic', receive_status='1' WHERE id='$id'");

										$supply_meta_query = mysqli_query($conn, "UPDATE supply_meta SET meta_value= CASE
												WHEN meta_key='stock_lot_number' THEN '$stock_lot_number'
												WHEN meta_key='stock_crop' THEN '$stock_crop'
												WHEN meta_key='stock_variety' THEN '$stock_variety'
												WHEN meta_key='stock_class' THEN '$stock_class'
												WHEN meta_key='stock_status' THEN '$stock_status'
												WHEN meta_key='stock_price' THEN '$$stock_price'
												ELSE `meta_value`
												END
											WHERE supply_id='$id'");

										if($result['receive_source'] != 'to_farmer') {
											$delete_meta_query = mysqli_query($conn, "DELETE FROM supply_meta WHERE supply_id='$id' && ( meta_key='driver_cnic' || meta_key='driver_name' || meta_key='driver_mobile_number' || meta_key='driver_address' || meta_key='vehicle_number' ) ");
										}

									} else {
										// rollback transaction on error
										// mysqli_rollback($conn);
										echo "<div class='alert alert-danger'>Farmer CNIC required</div>";
									}

								} else if($supply_source == 'other_circle' || $supply_source == 'other_province') {

									$receiver_detail = validate($_POST['receiver_detail']);
									$driver_cnic = validate($_POST['driver_cnic']);
									$driver_name = validate($_POST['driver_name']);
									$driver_mobile_number = validate($_POST['driver_mobile_number']);
									$driver_address = validate($_POST['driver_address']);
									$vehicle_number = validate($_POST['vehicle_number']);

									if( !empty($receiver_detail) && !empty($driver_cnic) && !empty($vehicle_number) ) {

										$sql_query = "UPDATE supply SET
											circle_id='$stock_circle',";
										if($supply_source == 'other_circle') {
											$sql_query .= " receive_source='other_circle' ";
										} else if($supply_source == 'other_province') {
											$sql_query .= " receive_source='other_province' ";
										}
										$sql_query .= ", parent_id='$parent_stock_id',stock_id='$stock_id',stock_qty='$supply_quantity', receive_status='1', receiver_info='$receiver_detail' WHERE id='$id'";

										$supply_query = mysqli_query($conn, $sql_query);
										$supply_meta_query = mysqli_query($conn, "UPDATE supply_meta SET meta_value= CASE
												WHEN meta_key='stock_lot_number' THEN '$stock_lot_number'
												WHEN meta_key='stock_crop' THEN '$stock_crop'
												WHEN meta_key='stock_variety' THEN '$stock_variety'
												WHEN meta_key='stock_class' THEN '$stock_class'
												WHEN meta_key='stock_status' THEN '$stock_status'
												WHEN meta_key='stock_price' THEN '$stock_price'
												ELSE `meta_value`
												END
											WHERE supply_id='$id'");

									} else {
										// rollback transaction on error
										// mysqli_rollback($conn);
										echo "<div class='alert alert-danger'>Destination, Driver CNIC, and Vehicle Number Required</div>";
									}

								}

								if($supply_query && $supply_meta_query && $update_stock_query) {
									// commit transaction
									// mysqli_commit($conn);
									echo "<div class='alert alert-success'>Supply Updated Successfully</div>";
									echo "<meta http-equiv='refresh' content='1'>";
								} else {
									// rollback transaction on error
									// mysqli_rollback($conn);
									echo "<div class='alert alert-danger'>Please Try Again</div>";
								}

							}

						} else {
							// rollback transaction on error
							// mysqli_rollback($conn);
							echo "<div class='alert alert-danger'>Fill Required Fields</div>";
						}

					}

					?>
					
					<form class="form" method="post" id="stock_supply_form">

						<?php

						if(is_super_admin() || is_admin()) {
						?>
						<div class="row">
							<div class="col-md-12 mb-3">
								<label>circle:</label>
								<select class="form-control" name="stock_circle" id="stock_circle">
									<option value="">Select circle</option>
									<?= select_circle('', $result['circle_id']); ?>
								</select>
							</div>
						</div>
						<?php
						}

						?>
						
						<div class="row">
							<div class="col-md-12 mb-3">
								<label>Supply Source:</label>
								<select class="form-control" id="supply_source" name="supply_source">
									<option value="">Select Supply Source</option>
									<option <?php if($result['receive_source'] == 'to_farmer') echo 'selected'; ?> value="to_farmer">To Farmer</option>
									<option <?php if($result['receive_source'] == 'other_circle') echo 'selected'; ?> value="other_circle">Other circle</option>
									<option <?php if($result['receive_source'] == 'other_province') echo 'selected'; ?> value="other_province">Other Province</option>
								</select>
							</div>
						</div>

						<div id="farmer_info_container">

							<?php

							if($result['receive_source'] == 'to_farmer' || empty( $result['receive_source'] ) ) {

							?>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label>Farmer CNIC:</label>
									<input type="text" pattern="\d{5}-\d{7}-\d{1}" value="<?= $result['receiver_detail']; ?>" class="form-control" placeholder="Enter Farmer CNIC" name="farmer_cnic" id="farmer_cnic">
									<div id="farmer_cnic_msg"></div>
								</div>
								<div class="col-md-6 mb-3">
									<label>Farmer Name:</label>
									<input type="text" pattern="[A-Za-z]+" value="<?= farmer_info($result['receiver_detail'], 'farmer_name'); ?>" class="form-control" placeholder="Enter Farmer Name" name="farmer_name" id="farmer_name">
									<div id="farmer_name_msg"></div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label>Farmer Mobile Number:</label>
									<input type="tel" pattern="^0\d{10}$" value="<?= farmer_info($result['receiver_detail'], 'farmer_mobile_number'); ?>" class="form-control" placeholder="Enter Farmer Mobile Number" name="farmer_mobile_number" id="farmer_mobile_number">
									<div id="farmer_mobile_number_msg"></div>
								</div>
								<div class="col-md-6 mb-3">
									<label>Farmer Address:</label>
									<textarea class="form-control" name="farmer_address" id="farmer_address" placeholder="Enter Farmer Address"><?= farmer_info($result['receiver_detail'], 'farmer_address') ?></textarea>
									<div id="farmer_address_msg"></div>
								</div>
							</div>
							<?php

							} else if($result['receive_source'] == 'other_province') {
							?>
							<div class="row">
								<div class="col-md-12 mb-3">
									<label>Select Province:</label>
									<select class="form-control" id="receiver_detail" name="receiver_detail">
										<option <?php if($result['receiver_detail'] == '') echo 'selected'; ?> value="">Select Province</option>
										<option <?php if($result['receiver_detail'] == 'Balochistan') echo 'selected'; ?> value="Balochistan">Balochistan</option>
										<option <?php if($result['receiver_detail'] == 'Punjab') echo 'selected'; ?> value="Punjab">Punjab</option>
										<option <?php if($result['receiver_detail'] == 'Sindh') echo 'selected'; ?> value="Sindh">Sindh</option>
									</select>
									<div id="receiver_province_msg"></div>
								</div>
							</div>
							<?php
							} else if($result['receive_source'] == 'other_circle') {
							?>
							<div class="row">
								<div class="col-md-12 mb-3">
									<label>Select circle:</label>
									<select class="form-control" id="receiver_detail" name="receiver_detail">
										<option <?php if( empty( $receiver_circle ) ) { echo 'selected'; } ?> value="">Select circle</option>
										<?= select_circle('', $result['receiver_detail'], ((is_super_admin() || is_admin()) ? '' : $circle_id) ); ?>
									</select>
									<div id="receiver_circle_msg"></div>
								</div>
							</div>
							<?php
							}

							if($result['receive_source'] == 'other_province' || $result['receive_source'] == 'other_circle') {
							?>
							<div class="row">
								<div class="col-md-4 mb-3">
									<label>Driver CNIC:</label>
									<input type="text" pattern="\d{5}-\d{7}-\d{1}" value="<?= supply_meta($id, 'driver_cnic'); ?>" class="form-control" placeholder="Enter Driver Name" id="driver_cnic" name="driver_cnic">
									<div id="driver_cnic_msg"></div>
								</div>
								<div class="col-md-4 mb-3">
									<label>Driver Name:</label>
									<input type="text" pattern="[A-Za-z]+" value="<?= supply_meta($id, 'driver_name'); ?>" class="form-control" placeholder="Enter Driver Name" id="driver_name" name="driver_name">
									<div id="driver_name_msg"></div>
								</div>
								<div class="col-md-4 mb-3">
									<label>Driver Mobile Number:</label>
									<input type="tel" pattern="^0\d{10}$" value="<?= supply_meta($id, 'driver_mobile_number'); ?>" class="form-control" placeholder="Enter Driver Mobile Number" id="driver_mobile_number" name="driver_mobile_number">
									<div id="driver_mobile_number_msg"></div>
								</div>
							</div>

							<div class="row">
								<div class="col-md-6 mb-3">
									<label>Driver Address:</label>
									<textarea class="form-control" placeholder="Enter Driver Address" id="driver_address" name="driver_address"><?= supply_meta($id, 'driver_address'); ?></textarea>
									<div id="driver_address_msg"></div>
								</div>
								<div class="col-md-6 mb-3">
									<label>Vehicle Number:</label>
									<input type="text" pattern="[0-9]+" value="<?= supply_meta($id, 'vehicle_number'); ?>" class="form-control" placeholder="Enter Vehicle Number" id="vehicle_number" name="vehicle_number">
									<div id="vehicle_number_msg"></div>
								</div>
							</div>
							<?php
							}

							?>

						</div>

						<?php

						if(isset($id) && !empty( $id ) && $id != 0) {
							echo "<input type='hidden' value='".$result['stock_id']."' name='stock_id'>";
							echo "<input type='hidden' value='".( $result['stock_qty'] + stock_transaction_detail($result['stock_id'], 'stock_qty') )."' name='available_stock_qty' id='available_stock_qty'>";
							echo "<input type='hidden' value='".stock_price(stock_detail($result['parent_id'], 'type'), stock_detail($result['parent_id'], 'class'), 'sale_price')."' name='stock_price' id='stock_price'>";
						} else {
						?>

						<div class="row">
							<div class="col-md-12 mb-3">
								<label>Select Stock:</label>
								<select class="form-control" id="stock_id" name="stock_id">
									<option <?php if( empty( $stock_id ) ) echo 'selected'; ?> value="">Select Stock</option>
									<?php

									if(is_manager() || is_storekeeper()) {

										$supply_stock_query = mysqli_query($conn, "SELECT * FROM stock_transactions WHERE delete_status='0' && active_status!='2' && circle_id='$circle_id'");

										if(mysqli_num_rows($supply_stock_query) > 0) {
											while($supply_stock_result = mysqli_fetch_assoc($supply_stock_query)) {
												if($supply_stock_result['id'] == $stock_id) {
													$supply_stock_selected = 'selected';
												} else {
													$supply_stock_selected = '';
												}
												if($supply_stock_result['stock_status'] == 0) {
													$supply_stock_class = 'text-danger';
													$supply_stock_status = 'Uncleaned';
												} else if($supply_stock_result['stock_status'] == 2) {
													$supply_stock_class = 'text-success';
													$supply_stock_status = 'Cleaned';
												} else if($supply_stock_result['stock_status'] == 3) {
													$supply_stock_class = 'text-primary';
													$supply_stock_status = 'Under Fumigation';
												}
											?>
												<option <?= $supply_stock_selected; ?> class="<?= $supply_stock_class; ?>" data-qty="<?= $supply_stock_result['stock_qty']; ?>" data-price="<?= stock_price(stock_detail($supply_stock_result['stock_id'], 'type'), stock_detail($supply_stock_result['stock_id'], 'class'), 'sale_price'); ?>" value="<?= $supply_stock_result['id']; ?>"><?=

													stock_detail($supply_stock_result['stock_id'], 'lot_number').' - '.stock_crop(stock_detail($supply_stock_result['stock_id'], 'type')).' - '.stock_variety(stock_detail($supply_stock_result['stock_id'], 'variety')).' - '.stock_class(stock_detail($supply_stock_result['stock_id'], 'class')).' - '.$supply_stock_result['stock_qty'].' (KGs) - '.$supply_stock_status;

												?></option>
											<?php
											}
										}

									}

									?>
								</select>
							</div>
						</div>

						<input type="hidden" value="0" id="available_stock_qty" name="available_stock_qty">

						<?php
						}

						?>

						<div class="row">
							<div class="col-md-12 mb-3">
								<label>Supply Quantity: &nbsp;&nbsp;</label><span>Available Stock <span id="available_stock"><?php if(isset($id)) { echo $result['stock_qty'] + stock_transaction_detail($result['stock_id'], 'stock_qty'); } else { echo 0; } ?></span> (Mun)</span>
								<input type="text" pattern="[0-9]+" value="<?= $result['stock_qty']; ?>" class="form-control" placeholder="Enter Supply Quantity" id="supply_quantity" name="supply_quantity">
								<small class="form-text text-muted">Enter Stock Quantity in (KGs)</small>
								<div id="supply_quantity_msg"></div>
							</div>
						</div>

						<button type="submit" class="btn btn-primary" id="stock_supply_form_btn" name="stock_supply_form_btn">Submit</button>

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

			$('#stock_circle').on('change', function(){
				var stock_circle = $('#stock_circle').val();

				if(stock_circle != '' && stock_circle != 0) {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'display_circle_stock', stock_circle:stock_circle, stock_id:'<?= $result['stock_id']; ?>' },
						success: function(result) {
							$('#stock_id').html(result);
						}
					});
				}
			});

			$('#supply_source').on('focus', function(){
				$('#supply_source').removeClass('is-invalid');
				$('#supply_source_msg').removeClass('invalid-feedback').text('');
			});

			$('#form_1_number').on('focus', function(){
				$('#form_1_number').removeClass('is-invalid');
				$('#form_1_number_msg').removeClass('invalid-feedback').text('');
			});

			$('#farmer_cnic').on('focus', function(){
				$('#farmer_cnic').removeClass('is-invalid');
				$('#farmer_cnic_msg').removeClass('invalid-feedback').text('');
			});

			$('#receiver_province').on('focus', function(){
				$('#receiver_province').removeClass('is-invalid');
				$('#receiver_province_msg').removeClass('invalid-feedback').text('');
			});

			$('#receiver_circle').on('focus', function(){
				$('#receiver_circle').removeClass('is-invalid');
				$('#receiver_circle_msg').removeClass('invalid-feedback').text('');
			});

			$('#driver_cnic').on('focus', function(){
				$('#driver_cnic').removeClass('is-invalid');
				$('#driver_cnic_msg').removeClass('invalid-feedback').text('');
			});

			$('#supply_quantity').on('focus', function(){
				$('#supply_quantity').removeClass('is-invalid');
				$('#supply_quantity_msg').removeClass('invalid-feedback').text('');
			});

			$('#supply_quantity').on('keyup change', function(){
				var supply_quantity = parseFloat($('#supply_quantity').val());
				var available_quantity = parseFloat($('#available_stock_qty').val());
				if(supply_quantity > available_quantity) {
					$('#supply_quantity').addClass('is-invalid');
					$('#supply_quantity_msg').addClass('invalid-feedback').text('Supply Quantity Must be less than or equal to ' + available_quantity + ' KGs');
				} else {
					$('#supply_quantity').removeClass('is-invalid');
					$('#supply_quantity_msg').removeClass('invalid-feedback').text('');
				}
			});

			$('#stock_id').on('change', function(){
				var stock_id = $('#stock_id').val();
				var stock_qty = $('#stock_id option:selected').data('qty');

				if(stock_id != '') {
					$('#available_stock_qty').val(stock_qty);
					$('#available_stock').text(stock_qty);

				} else {
					$('#available_stock_qty').val('0');
					$('#available_stock').text('0');
				}
			});

			$('#stock_supply_form').on('submit', function(){
				var bool = 0;

				if($('#supply_source').val() == '') {
					$('#supply_source').addClass('is-invalid');
					$('#supply_source_msg').addClass('invalid-feedback').text('Supply Source Required');
					bool = 1;
				} else {
					$('#supply_source').removeClass('is-invalid');
					$('#supply_source_msg').removeClass('invalid-feedback').text('');
				}

				if($('#supply_source').val() == 'to_farmer') {
					if($('#farmer_cnic').val() == '') {
						$('#farmer_cnic').addClass('is-invalid');
						$('#farmer_cnic_msg').addClass('invalid-feedback').text('Farmer CNIC Required');
						bool = 1;
					} else {
						$('#farmer_cnic').removeClass('is-invalid');
						$('#farmer_cnic_msg').removeClass('invalid-feedback').text('');
					}
				} else if($('#supply_source').val() == 'other_province') {
					if($('#receiver_province').val() == '') {
						$('#receiver_province').addClass('is-invalid');
						$('#receiver_province_msg').addClass('invalid-feedback').text('Receiver Province Required');
						bool = 1;
					} else {
						$('#receiver_province').removeClass('is-invalid');
						$('#receiver_province_msg').removeClass('invalid-feedback').text('');
					}
				} else if($('#supply_source').val() == 'other_circle') {
					if($('#receiver_circle').val() == '') {
						$('#receiver_circle').addClass('is-invalid');
						$('#receiver_circle_msg').addClass('invalid-feedback').text('Receiver circle Required');
						bool = 1;
					} else {
						$('#receiver_circle').removeClass('is-invalid');
						$('#receiver_circle_msg').removeClass('invalid-feedback').text('');
					}
				}

				<?php

				if(!isset($id)) {
				?>

				if($('#stock_id').val() == '') {
					$('#stock_id').addClass('is-invalid');
					$('#stock_id_msg').addClass('invalid-feedback').text('Stock Required');
					bool = 1;
				} else {
					$('#stock_id').removeClass('is-invalid');
					$('#stock_id_msg').removeClass('invalid-feedback').text('');
				}

				<?php
				}

				?>

				if($('#supply_quantity').val() == '') {
					$('#supply_quantity').addClass('is-invalid');
					$('#supply_quantity_msg').addClass('invalid-feedback').text('Supply Quantity Required');
					bool = 1;
				} else {
					if(parseInt($('#supply_quantity').val()) <= parseInt($('#available_stock_qty').val())) {
						$('#supply_quantity').removeClass('is-invalid');
						$('#supply_quantity_msg').removeClass('invalid-feedback').text('');
					} else {
						$('#supply_quantity').addClass('is-invalid');
						$('#supply_quantity_msg').addClass('invalid-feedback').text('Supply Quantity must be less than or equal to available stock');
						bool = 1;
					}
				}

				if(bool == 0) {
					return true;
				} else {
					return false;
				}
			});


			$('#supply_source').on('change', function(){
				var supply_source = $('#supply_source').val();
				if(supply_source == 'to_farmer') {
					$('#farmer_info_container').html(

						'<div class="row">' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer CNIC:</label>' + 
								'<input type="text" pattern="\d{5}-\d{7}-\d{1}" value="<?= $result['receiver_detail']; ?>" class="form-control" placeholder="Enter Farmer CNIC" name="farmer_cnic" id="farmer_cnic">' + 
								'<div id="farmer_cnic_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer Name:</label>' + 
								'<input type="text" pattern="[A-Za-z]+" value="<?= farmer_info($result['receiver_detail'], 'farmer_name'); ?>" class="form-control" placeholder="Enter Farmer Name" name="farmer_name" id="farmer_name">' + 
								'<div id="farmer_name_msg"></div>' + 
							'</div>' + 
						'</div>' + 

						'<div class="row">' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer Mobile Number:</label>' + 
								'<input type="tel" pattern="^0\d{10}$" value="<?= farmer_info($result['receiver_detail'], 'farmer_mobile_number'); ?>" class="form-control" placeholder="Enter Farmer Mobile Number" name="farmer_mobile_number" id="farmer_mobile_number">' + 
								'<div id="farmer_mobile_number_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer Address:</label>' + 
								'<textarea class="form-control" name="farmer_address" id="farmer_address" placeholder="Enter Farmer Address"><?= farmer_info($result['receiver_detail'], 'farmer_address'); ?></textarea>' + 
								'<div id="farmer_address"></div>' + 
							'</div>' + 
						'</div>'
					);
				} else if(supply_source == 'other_province' || supply_source == 'other_circle') {
					if(supply_source == 'other_province') {
						var output = '<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<label>Select Province:</label>' + 
								'<select class="form-control" id="receiver_detail" name="receiver_detail">' + 
									'<option value="">Select Province</option>' + 
									'<option value="Balochistan" <?php if($result['receiver_detail'] == 'Balochistan') { echo 'selected'; } ?>>Balochistan</option>' + 
									'<option value="Punjab" <?php if($result['receiver_detail'] == 'Punjab') { echo 'selected'; } ?>>Punjab</option>' + 
									'<option value="Sindh" <?php if($result['receiver_detail'] == 'Sindh') { echo 'selected'; } ?>>Sindh</option>' + 
								'</select>' + 
								'<div id="receiver_province_msg"></div>' + 
							'</div>' + 
						'</div>';
					} else if(supply_source == 'other_circle') {
						var output = '<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<label>Select circle:</label>' + 
								'<select class="form-control" id="receiver_detail" name="receiver_detail">' + 
									'<option value="">Select circle</option>' + 
									"<?= select_circle('', $result['receiver_detail']); ?>" + 
								'</select>' + 
								'<div id="receiver_circle_msg"></div>' + 
							'</div>' + 
						'</div>';
					}
					$('#farmer_info_container').html(
						output + 

						'<div class="row">' + 
							'<div class="col-md-4 mb-3">' + 
								'<label>Driver CNIC:</label>' + 
								'<input type="text" pattern="\d{5}-\d{7}-\d{1}" value="<?= supply_meta($id, 'driver_cnic'); ?>" class="form-control" placeholder="Enter Driver Name" id="driver_cnic" name="driver_cnic">' + 
								'<div id="driver_cnic_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-4 mb-3">' + 
								'<label>Driver Name:</label>' + 
								'<input type="text" pattern="[A-Za-z]+" value="<?= supply_meta($id, 'driver_name'); ?>" class="form-control" placeholder="Enter Driver Name" id="driver_name" name="driver_name">' + 
								'<div id="driver_name_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-4 mb-3">' + 
								'<label>Driver Mobile Number:</label>' + 
								'<input type="tel" pattern="^0\d{10}$" value="<?= supply_meta($id, 'driver_mobile_number'); ?>" class="form-control" placeholder="Enter Driver Mobile Number" id="driver_mobile_number" name="driver_mobile_number">' + 
								'<div id="driver_mobile_number_msg"></div>' + 
							'</div>' + 
						'</div>' + 

						'<div class="row">' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Driver Address:</label>' + 
								'<textarea class="form-control" placeholder="Enter Driver Address" id="driver_address" name="driver_address"><?= supply_meta($id, 'driver_address'); ?></textarea>' + 
								'<div id="driver_address_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Vehicle Number:</label>' + 
								'<input type="text" pattern="[A-Za-z0-9]+" value="<?= supply_meta($id, 'vehicle_number'); ?>" class="form-control" placeholder="Enter Vehicle Number" id="vehicle_number" name="vehicle_number">' + 
								'<div id="vehicle_number_msg"></div>' + 
							'</div>' + 
						'</div>'
					);
				}
			});

			$(document).on('blur', '#farmer_cnic', function(){
				var farmer_cnic = $('#farmer_cnic').val();

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
							}
						}
					});

				}
			});

			<?php
			if(!isset($id)) {
			?>
			if($('#stock_id option:selected').val() != '') {
				$('#available_stock_qty').val($('#stock_id option:selected').data('qty'));
			} else {
				$('#available_stock_qty').val('0');
			}
			<?php
			}
			?>

		});
	</script>
</body>
</html>