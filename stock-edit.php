<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Stock Edit';
	
	include "head.php";

	if(is_super_admin() || is_admin() || is_storekeeper()) {
		header('Location: stocks.php');
	}

	if(isset($_GET['id']) && !empty( $_GET['id'] ) && $_GET['id'] != 0) {

		$id = validate($_GET['id']);

		$query = mysqli_query($conn, "SELECT * FROM stocks WHERE id='$id' && delete_status='0'");

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
		} else {
			header('Location: stock.php');
		}

	} else {
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
							<h1 class="m-0 d-inline"><?= $page_title; ?></h1>
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

					if(isset($_POST['edit_stock_form_btn'])) {

						$stock_source = validate($_POST['stock_source']);
						$stock_lot_number = validate($_POST['lot_number']);
						$stock_crop = validate($_POST['stock_crop']);
						$stock_class = validate($_POST['stock_class']);
						$stock_variety = validate($_POST['stock_variety']);
						$stock_qty = validate($_POST['stock_qty']);
						$stock_price = stock_price($stock_crop, $stock_class, 'purchase_price');
						$stock_qty_price = $stock_price * $stock_qty;
						if(isset($_POST['labour_cost'])) {
							$labour_cost = validate($_POST['labour_cost']);
						} else {
							$labour_cost = 0;
						}
						if(isset($_POST['packing_bag_cost'])) {
							$packing_bag_cost = validate($_POST['packing_bag_cost']);
						} else {
							$packing_bag_cost = 0;
						}
						if(isset($_POST['miscellaneous_cost'])) {
							$miscellaneous_cost = validate($_POST['miscellaneous_cost']);
						} else {
							$miscellaneous_cost = 0;
						}


						if( !empty( $stock_lot_number ) && !empty( $stock_crop ) && !empty( $stock_class ) && !empty( $stock_variety ) && !empty( $stock_qty ) ) {

							$check_stock_query = mysqli_query($conn, "SELECT * FROM stocks WHERE id='$id' && delete_status='0'");
							if(mysqli_num_rows($check_stock_query) > 0) {
								$check_stock_result = mysqli_fetch_assoc($check_stock_query);
								$stock_circle_id = $check_stock_result['circle_id'];
								$old_stock_source = $check_stock_result['stock_source'];

								if($stock_source == 'from_farmer') {

									$form_1_number = validate($_POST['form_1_number']);
									$farmer_cnic = validate($_POST['farmer_cnic']);
									$farmer_name = validate($_POST['farmer_name']);
									$farmer_mobile_number = validate($_POST['farmer_mobile_number']);
									$farmer_address = validate($_POST['farmer_address']);

									if( !empty( $form_1_number ) && !empty( $farmer_cnic ) ) {

										if(mysqli_num_rows(mysqli_query($conn, "SELECT * FROM farmers WHERE farmer_cnic='$farmer_cnic' && delete_status='0'")) == 0) {
											$farmer_query = mysqli_query($conn, "INSERT INTO farmers(user_id, circle_id, farmer_cnic, farmer_name, farmer_mobile_number, farmer_address, time_created) VALUES('$user_id', '$circle_id', '$farmer_cnic', '$farmer_name', '$farmer_mobile_number', '$farmer_address', '$time_created')");
										} else {
											$farmer_query = mysqli_query($conn, "UPDATE farmers SET farmer_name='$farmer_name', farmer_mobile_number='$farmer_mobile_number', farmer_address='$farmer_address' WHERE farmer_cnic='$farmer_cnic'");
										}

										$stock_query = mysqli_query($conn, "UPDATE stocks SET stock_source='$stock_source', supplier_info='$farmer_cnic', lot_number='$stock_lot_number', type='$stock_crop', variety='$stock_variety', class='$stock_class', stock_qty='$stock_qty' WHERE id='$id'");

										if($old_stock_source == 'from_farmer') {
											$stock_meta_query = mysqli_query($conn, "UPDATE stock_meta SET meta_value= CASE
													WHEN meta_key='form_1_number' THEN '$form_1_number'
													WHEN meta_key='stock_price' THEN '$stock_price'
													WHEN meta_key='stock_qty_price' THEN '$stock_qty_price'
													ELSE `meta_value`
													END
												WHERE stock_id='$id'");
										} else {
											$stock_meta_query = mysqli_query($conn, "INSERT INTO stock_meta(stock_id, meta_key, meta_value) VALUES
												('$id', 'form_1_number', '$form_1_number'), 
												('$id', 'stock_price', '$stock_price'), 
												('$id', 'stock_qty_price', '$stock_qty_price')
											");
										}

									} else {
										echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
									}

								} else if($stock_source == 'other_province') {

									$stock_province = validate($_POST['stock_province']);

									if( !empty( $stock_province ) ) {

										$stock_query = mysqli_query($conn, "UPDATE stocks SET stock_source='$stock_source', supplier_info='$stock_province', lot_number='$stock_lot_number', type='$stock_crop', variety='$stock_variety', class='$stock_class', stock_qty='$stock_qty' WHERE id='$id'");
										$stock_meta_query = mysqli_query($conn, "UPDATE stock_meta SET meta_value= CASE
												WHEN meta_key='form_1_number' THEN '$form_1_number'
												ELSE `meta_value`
												END
											WHERE stock_id='$id'");

										if($old_stock_source == 'from_farmer') {
											$stock_meta_query = mysqli_query($conn, "UPDATE stock_meta SET meta_value= CASE
													WHEN meta_key='stock_price' THEN '$stock_price'
													WHEN meta_key='stock_qty_price' THEN '$stock_qty_price'
													ELSE `meta_value`
													END
												WHERE stock_id='$id'");
											$del_stock_meta_query = mysqli_query($conn, "DELETE FROM stock_meta WHERE stock_id='$id' && meta_key='form_1_number'");
										} else {
											$stock_meta_query = mysqli_query($conn, "INSERT INTO stock_meta(stock_id, meta_key, meta_value) VALUES
												('$id', 'stock_price', '$stock_price'), 
												('$id', 'stock_qty_price', '$stock_qty_price')
											");
										}

									} else {
										echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
									}

								}

								$check_stock_transaction_query = mysqli_query($conn, "SELECT * FROM stock_transactions WHERE circle_id='$circle_id' && stock_id='$id' && stock_status='0' && active_status='1' && delete_status='0'");
								if(mysqli_num_rows($check_stock_transaction_query) == 1) {
									$check_stock_transaction_result = mysqli_fetch_assoc($check_stock_transaction_query);
									$old_stock_qty = $check_stock_transaction_result['stock_qty'];
									if($stock_qty > $old_stock_qty) {
										$new_stock_qty = $old_stock_qty + $stock_qty;
									} else if($stock_qty < $old_stock_qty) {
										$new_stock_qty = $old_stock_qty - $stock_qty;
									} else {
										$new_stock_qty = $stock_qty;
									}
									$stock_transaction_query = mysqli_query($conn, "UPDATE stock_transactions SET stock_qty='$new_stock_qty' WHERE circle_id='$circle_id' && stock_id='$id' && stock_status='0' && active_status='1'");
								}


#=================================================Stock Finance Start============================================

								$old_total_cost_amount = ( stock_meta($id, 'stock_qty_price') == '' ? 0 : stock_meta($id, 'stock_qty_price') ) + ( transaction_detail($id, '0', '0', 'labour_cost') == '' ? 0 :transaction_detail($id, '0', '0', 'labour_cost') ) + ( transaction_detail($id, '0', '0', 'packing_bag_cost') == '' ? 0 : transaction_detail($id, '0', '0', 'packing_bag_cost') ) + ( transaction_detail($id, '0', '0', 'miscellaneous_cost') == '' ? 0 : transaction_detail($id, '0', '0', 'miscellaneous_cost') );

								$total_amount = $labour_cost + $miscellaneous_cost + $packing_bag_cost + $stock_qty_price;
								$finance_query = mysqli_query($conn, "SELECT * FROM finance WHERE circle_id='$circle_id' && delete_status='0'");
								if(mysqli_num_rows($finance_query) > 0) {
									$finance_result = mysqli_fetch_assoc($finance_query);
									$finance_id = $finance_result['id'];
									$finance_amount = $finance_result['amount'];
									$finance_amount += $old_total_cost_amount;
									$update_old_finance_query = mysqli_query($conn, "UPDATE finance SET amount='$old_total_cost_amount' WHERE id='$finance_id'");

									$transaction_query = mysqli_query($conn, "UPDATE transactions SET amount='$total_amount' WHERE type='0' && trans_flow='0' && ref_id='$id' && finance_id='$finance_id'");
									$transaction_id = transaction_detail($id, '0', '0', 'id', 'transactions');

									$transaction_meta_query = mysqli_query($conn, "UPDATE transaction_meta SET meta_value= CASE
											WHEN meta_key='total_amount' THEN '$total_amount'
											WHEN meta_key='stock_qty_price' THEN '$stock_qty_price'
											WHEN meta_key='labour_cost' THEN '$labour_cost'
											WHEN meta_key='miscellaneous_cost' THEN '$miscellaneous_cost'
											WHEN meta_key='packing_bag_cost' THEN '$packing_bag_cost'
											ELSE `meta_value`
											END
										WHERE transaction_id='$transaction_id'");

									$finance_amount -= $total_amount;

									$finance_update_query = mysqli_query($conn, "UPDATE finance SET amount='$finance_amount' WHERE id='$finance_id'");

								}

#=================================================Stock Finance Ends=============================================


								if($stock_query && $stock_meta_query && $transaction_query && $transaction_meta_query) {
									echo "<div class='alert alert-success'>Stock Successfully Updated</div>";
									echo "<meta http-equiv='refresh' content='1'>";
								} else {
									echo "<div class='alert alert-danger'>Please Try Again</div>";
								}



							}

						} else {
							echo "<div class='alert alert-danger'>Please Fill Required Fields</div>";
						}

					}

					?>
					
					<form class="form" enctype="multipart/form-data" id="edit_stock_form" method="post">

						<div class="row">
							<div class="col-md-12 mb-3">
								<label>Stock Source:</label>
								<select class="form-control" id="stock_source" name="stock_source">
									<option <?php if( empty( $result['stock_source'] ) ) { echo 'selected'; } ?> value="">Select Stock Source</option>
									<option <?php if($result['stock_source'] == 'from_farmer') { echo 'selected'; } ?> value="from_farmer">From Farmer</option>
									<option <?php if($result['stock_source'] == 'other_province') { echo 'selected'; } ?> value="other_province">Other province</option>
								</select>
								<div id="stock_source_msg"></div>
							</div>
						</div>

						<div id="farmer_info_container">

							<?php

							if($result['stock_source'] == 'from_farmer' || empty( $result['stock_source'] ) ) {

							?>
								<div class="row">
									<div class="col-md-12 mb-3">
										<label>Form 1 Number:</label>
										<input type="text" pattern="[0-9]+" value="<?= stock_meta($id, 'form_1_number'); ?>" class="form-control" placeholder="Enter Form 1 Number" id="form_1_number" name="form_1_number">
										<div id="form_1_number_msg"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 mb-3">
										<label>Farmer CNIC:</label>
										<input type="text" pattern="\d{5}-\d{7}-\d{1}" value="<?= $result['supplier_info']; ?>" class="form-control" placeholder="Enter Farmer CNIC" name="farmer_cnic" id="farmer_cnic">
										<div id="farmer_cnic_msg"></div>
									</div>
									<div class="col-md-6 mb-3">
										<label>Farmer Name:</label>
										<input type="text" pattern="[A-Za-z]+" value="<?= farmer_info($result['supplier_info'], 'farmer_name'); ?>" class="form-control" placeholder="Enter Farmer Name" name="farmer_name" id="farmer_name">
										<div id="farmer_name_msg"></div>
									</div>
								</div>

								<div class="row">
									<div class="col-md-6 mb-3">
										<label>Farmer Mobile Number:</label>
										<input type="tel" pattern="^0\d{10}$" value="<?= farmer_info($result['supplier_info'], 'farmer_mobile_number'); ?>" class="form-control" placeholder="Enter Farmer Mobile Number" name="farmer_mobile_number" id="farmer_mobile_number">
										<div id="farmer_mobile_number_msg"></div>
									</div>
									<div class="col-md-6 mb-3">
										<label>Farmer Address:</label>
										<textarea class="form-control" name="farmer_address" id="farmer_address" placeholder="Enter Farmer Address"><?= farmer_info($result['supplier_info'], 'farmer_address'); ?></textarea>
										<div id="farmer_address_msg"></div>
									</div>
								</div>
							<?php

							} else if($result['stock_source'] == 'other_province') {
							?>
							<div class="row">
								<div class="col-md-12 mb-3">
									<label>Select Province:</label>
									<select class="form-control" id="stock_province" name="stock_province">
										<option <?php if( empty( $result['supplier_info'] ) ) { echo 'selected'; } ?> value="">Select Province</option>
										<option <?php if($result['supplier_info'] == 'Balochistan') { echo 'selected'; } ?> value="Balochistan">Balochistan</option>
										<!-- <option <?php if($result['supplier_info'] == 'Khyber Pakhtunkhwa') { echo 'selected'; } ?> value="Khyber Pakhtunkhwa">Khyber Pakhtunkhwa</option> -->
										<option <?php if($result['supplier_info'] == 'Punjab') { echo 'selected'; } ?> value="Punjab">Punjab</option>
										<option <?php if($result['supplier_info'] == 'Sindh') { echo 'selected'; } ?> value="Sindh">Sindh</option>
									</select>
									<div id="stock_province_msg"></div>
								</div>
							</div>
							<?php
							}

							?>

						</div>

						<div class="row">
							<div class="col-md-4 mb-3">
								<label>Class:</label>
								<select class="form-control" id="stock_class" name="stock_class">
									<option <?php if( empty( $result['class'] ) ) { echo 'selected'; } ?> value="">Select Class</option>
									<?php

									$class_query = mysqli_query($conn, "SELECT id, class_name FROM stock_class WHERE active_status='1' && delete_status='0'");
									if(mysqli_num_rows($class_query) > 0) {
										while($class_result = mysqli_fetch_assoc($class_query)) {
											if($result['class'] == $class_result['id']) { $stock_class_selected = 'selected'; } else { $stock_class_selected = ''; }
											echo "<option ".$stock_class_selected." value='".$class_result['id']."'>".$class_result['class_name']."</option>";
										}
									}

									?>
								</select>
								<div id="stock_class_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Stock Type:</label>
								<select class="form-control" id="stock_crop" name="stock_crop">
									<option <?php if( empty( $result['type'] ) ) { echo 'selected'; } ?> value="">Select Stock Type</option>
									<?php

									$type_query = mysqli_query($conn, "SELECT id, type FROM stock_crop WHERE active_status='1' && delete_status='0'");
									if(mysqli_num_rows($type_query) > 0) {
										while($type_result = mysqli_fetch_assoc($type_query)) {
											if($result['type'] == $type_result['id']) { $stock_crop_selected = 'selected'; } else { $stock_crop_selected = ''; }

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

											echo "<option ".$stock_price." ".$stock_crop_selected." value='".$type_result['id']."'>".$type_result['type']."</option>";
										}
									}

									?>
								</select>
								<div id="stock_crop_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Variety:</label>
								<select class="form-control" id="stock_variety" name="stock_variety">
									<option <?php if( empty( $result['variety'] ) ) { echo 'selected'; } ?> value="">Select Variety</option>
									<?php

									$variety_query = mysqli_query($conn, "SELECT * FROM stock_variety WHERE stock_crop_id='{$result['type']}' && active_status='1' && delete_status='0'");
									if(mysqli_num_rows($variety_query) > 0) {
										while($variety_result = mysqli_fetch_assoc($variety_query)) {
											if($result['variety'] == $variety_result['id']) { $stock_variety_selected = 'selected'; } else { $stock_variety_selected = ''; }
											echo "<option ".$stock_variety_selected." value='".$variety_result['id']."'>".$variety_result['variety']."</option>";
										}
									}

									?>
								</select>
								<div id="stock_variety_msg"></div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 mb-3">
								<label>Lot Number:</label>
								<input type="text" readonly value="<?= $result['lot_number']; ?>" class="form-control" placeholder="Enter Lot Number" id="lot_number" name="lot_number">
								<div id="lot_number_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Qty:</label>
								<input type="text" pattern="[0-9]+" value="<?= $result['stock_qty']; ?>" class="form-control" placeholder="Enter Stock Qty" id="stock_qty" name="stock_qty">
								<small class="form-text text-muted">Enter Stock in (Muns)</small>
								<div id="stock_qty_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Price:</label>
								<input type="text" pattern="[0-9]+" value="<?= ( empty( stock_price($result['type'], $result['class'], 'purchase_price') ) ? 0 : stock_price($result['type'], $result['class'], 'purchase_price') * $result['stock_qty'] ); ?>" class="form-control" placeholder="Enter Stock Price" readonly id="stock_price" name="stock_price">
								<div id="stock_price_msg"></div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 mb-3">
								<label>Labour Cost:</label>
								<input type="text" pattern="[0-9]+" value="<?= transaction_detail($id, '0', '0', 'labour_cost'); ?>" class="form-control" placeholder="Enter Labour Cost" id="labour_cost" name="labour_cost">
								<div id="labour_cost_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Packing Bag Cost:</label>
								<input type="text" pattern="[0-9]+" value="<?= transaction_detail($id, '0', '0', 'packing_bag_cost'); ?>" class="form-control" placeholder="Enter Packing Bag Cost" id="packing_bag_cost" name="packing_bag_cost">
								<div id="packing_bag_cost_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Miscellaneous Cost:</label>
								<input type="text" pattern="[0-9]+" value="<?= transaction_detail($id, '0', '0', 'miscellaneous_cost'); ?>" class="form-control" placeholder="Enter Miscellaneous Cost" id="miscellaneous_cost" name="miscellaneous_cost">
								<div id="miscellaneous_cost_msg"></div>
							</div>
						</div>

						<button class="btn btn-primary" type="submit" name="edit_stock_form_btn" id="edit_stock_form_btn">Submit</button>

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

			$('#stock_class, #stock_crop, #stock_qty').on('change keyup', function(){

				var stock_crop = $('#stock_crop option:selected').val();
				var stock_class = $('#stock_class option:selected').val();
				var stock_price = parseInt($('#stock_crop option:selected').data('class_'+stock_class+'_purchase_price'));
				var stock_qty = $('#stock_qty').val();
				console.log(stock_qty);

				if(stock_crop != '' && stock_crop != 0 && stock_class != '' && stock_class != 0 && stock_qty != '') {
					$('#stock_price').val(parseInt(stock_qty) * stock_price);
				} else {
					$('#stock_price').val(0);
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

			$('#form_1_number').on('focus', function(){
				$('#form_1_number').removeClass('is-invalid');
				$('#form_1_number_msg').removeClass('invalid-feedback').text('');
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

			$('#edit_stock_form').on('submit', function(){
				var bool = 0;

				if($('#stock_source').val() == '') {
					$('#stock_source').addClass('is-invalid');
					$('#stock_source_msg').addClass('invalid-feedback').text('Please Select Stock Source');
					bool = 1;
				} else if($('#stock_source').val() == 'from_farmer') {
					if($('#form_1_number').val() == '') {
						$('#form_1_number').addClass('is-invalid');
						$('#form_1_number_msg').addClass('invalid-feedback').text('Form 1 Number Required');
						bool = 1;
					} else {
						$('#form_1_number').removeClass('is-invalid');
						$('#form_1_number_msg').removeClass('invalid-feedback').text('');
						bool = 0;
					}
					if($('#farmer_cnic').val() == '') {
						$('#farmer_cnic').addClass('is-invalid');
						$('#farmer_cnic_msg').addClass('invalid-feedback').text('Farmer CNIC Required');
						bool = 1;
					} else {
						$('#farmer_cnic').removeClass('is-invalid');
						$('#farmer_cnic_msg').removeClass('invalid-feedback').text('');
						bool = 0;
					}
				} else if($('#stock_source').val() == 'other_province') {
					if($('#stock_province').val() == '') {
						$('#stock_province').addClass('is-invalid');
						$('#stock_province_msg').addClass('invalid-feedback').text('Please Select Province');
						bool = 1;
					} else {
						$('#stock_province').removeClass('is-invalid');
						$('#stock_province_msg').removeClass('invalid-feedback').text('');
						bool = 0;
					}
				}

				if($('#lot_number').val() == '') {
					$('#lot_number').addClass('is-invalid');
					$('#lot_number_msg').addClass('invalid-feedback').text('Lot Number Required');
					bool = 1;
				} else {
					$('#lot_number').removeClass('is-invalid');
					$('#lot_number_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if($('#stock_qty').val() == '') {
					$('#stock_qty').addClass('is-invalid');
					$('#stock_qty_msg').addClass('invalid-feedback').text('Stock Quantity Required');
					bool = 1;
				} else {
					$('#stock_qty').removeClass('is-invalid');
					$('#stock_qty_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if($('#stock_crop').val() == '') {
					$('#stock_crop').addClass('is-invalid');
					$('#stock_crop_msg').addClass('invalid-feedback').text('Stock Type Required');
					bool = 1;
				} else {
					$('#stock_crop').removeClass('is-invalid');
					$('#stock_crop_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if($('#stock_class').val() == '') {
					$('#stock_class').addClass('is-invalid');
					$('#stock_class_msg').addClass('invalid-feedback').text('Stock Class Required');
					bool = 1;
				} else {
					$('#stock_class').removeClass('is-invalid');
					$('#stock_class_msg').removeClass('invalid-feedback').text('');
					bool = 0;
				}

				if($('#stock_variety').val() == '') {
					$('#stock_variety').addClass('is-invalid');
					$('#stock_variety_msg').addClass('invalid-feedback').text('Stock Variety Required');
					bool = 1;
				} else {
					$('#stock_variety').removeClass('is-invalid');
					$('#stock_variety_msg').remove('invalid-feedback').text('');
					bool = 0;
				}

				if(bool == 0) {
					return true;
				} else {
					return false;
				}

			});

			$('#stock_source').on('change', function(){
				var stock_source = $('#stock_source').val();
				if(stock_source == 'from_farmer') {
					$('#farmer_info_container').html(
						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<label>Form 1 Number:</label>' + 
								'<input type="text" pattern="[0-9]+" value="<?php echo stock_meta($id, 'form_1_number'); ?>" class="form-control" placeholder="Enter Form 1 Number" id="form_1_number" name="form_1_number">' + 
								'<div id="form_1_number_msg"></div>' + 
							'</div>' + 
						'</div>' + 

						'<div class="row">' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer CNIC:</label>' + 
								'<input type="text" pattern="\d{5}-\d{7}-\d{1}" value="<?php echo ($result['stock_source'] == 'from_farmer') ? $result['supplier_info'] : ''; ?>" class="form-control" placeholder="Enter Farmer CNIC" name="farmer_cnic" id="farmer_cnic">' + 
								'<div id="farmer_cnic_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer Name:</label>' + 
								'<input type="text" pattern="[A-Za-z]+" value="<?php echo ($result['stock_source'] == 'from_farmer') ? farmer_info($result['supplier_info'], 'farmer_name') : ''; ?>" class="form-control" placeholder="Enter Farmer Name" name="farmer_name" id="farmer_name">' + 
								'<div id="farmer_name_msg"></div>' + 
							'</div>' + 
						'</div>' + 

						'<div class="row">' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer Mobile Number:</label>' + 
								'<input type="tel" pattern="^0\d{10}$" value="<?php echo ($result['stock_source'] == 'from_farmer') ? farmer_info($result['supplier_info'], 'farmer_mobile_number') : ''; ?>" class="form-control" placeholder="Enter Farmer Mobile Number" name="farmer_mobile_number" id="farmer_mobile_number">' + 
								'<div id="farmer_mobile_number_msg"></div>' + 
							'</div>' + 
							'<div class="col-md-6 mb-3">' + 
								'<label>Farmer Address:</label>' + 
								'<textarea class="form-control" name="farmer_address" id="farmer_address" placeholder="Enter Farmer Address"><?php echo ($result['stock_source'] == 'from_farmer') ? farmer_info($result['supplier_info'], 'farmer_address') : '' ?></textarea>' + 
								'<div id="farmer_address"></div>' + 
							'</div>' + 
						'</div>'
					);
				} else if(stock_source == 'other_province') {
					$('#farmer_info_container').html(
						'<div class="row">' + 
							'<div class="col-md-12 mb-3">' + 
								'<label>Select Province:</label>' + 
								'<select class="form-control" id="stock_province" name="stock_province">' + 
									'<option <?php if($result['stock_source'] == 'other_province' && $result['supplier_info'] == '') { echo 'selected'; } ?> value="">Select Province</option>' + 
									'<option <?php if($result['stock_source'] == 'other_province' && $result['supplier_info'] == 'Balochistan') { echo 'selected'; } ?> value="Balochistan">Balochistan</option>' + 
									// '<option <?php if($result['stock_source'] == 'other_province' && $result['supplier_info'] == 'Khyber Pakhtunkhwa') { echo 'selected'; } ?> ?> value="Khyber Pakhtunkhwa">Khyber Pakhtunkhwa</option>' + 
									'<option <?php if($result['stock_source'] == 'other_province' && $result['supplier_info'] == 'Punjab') { echo 'selected'; } ?> value="Punjab">Punjab</option>' + 
									'<option <?php if($result['stock_source'] == 'other_province' && $result['supplier_info'] == 'Sindh') { echo 'selected'; } ?> value="Sindh">Sindh</option>' + 
								'</select>' + 
								'<div id="stock_province_msg"></div>' + 
							'</div>' + 
						'</div>'
					);
				}
			});

			$('#farmer_cnic').on('blur', function(){
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

			$('#lot_number').on('blur', function(){
				var lot_number = $('#lot_number').val();

				if(lot_number != '') {
					$.ajax({
						url: 'ajax.php',
						type: 'POST',
						data: { action:'check_lot_number', lot_number:lot_number, id:<?php echo $id; ?> },
						success: function(result) {
							if(result == 1) {
								$('#lot_number').addClass('is-invalid');
								$('#lot_number_msg').addClass('invalid-feedback').text('Lot Number Already Exist');
							}
						}
					});
				}
			});

		});
	</script>
</body>
</html>