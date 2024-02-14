<!DOCTYPE html>
<html>
<head>
	<?php

	$page_title = 'Stock Cleaning New';
	
	include "head.php";

	if(is_super_admin() || is_admin()) {
		header('Location: stock-cleaning.php');
	}

	if(isset($_GET['id']) && !empty( $_GET['id'] ) && $_GET['id'] != 0) {
		$id = validate($_GET['id']);

		$query = mysqli_query($conn, "SELECT * FROM stock_transactions WHERE id='$id' && delete_status='0'");

		if(mysqli_num_rows($query) > 0) {
			$result = mysqli_fetch_assoc($query);
		} else {
			header("Location: stock-cleaning.php");
		}
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
								<li class="breadcrumb-item"><a href="stock-cleaning.php">Stock Cleaning</a></li>
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

					$processing_stock_id = $processing_quantity = $grade_1 = $small_grains = $gundi = $broken = $straw = $dust = $other = '';

					if(isset($_POST['stock_cleaning_form_btn'])) {

						$processing_stock_id = validate($_POST['stock_id']);
						$processing_quantity = validate($_POST['processing_quantity']);
						$available_stock_qty = validate($_POST['available_stock_qty']);
						$grade_1 = validate($_POST['grade_1']);
						$small_grains = validate($_POST['small_grains']);
						$gundi = validate($_POST['gundi']);
						$broken = validate($_POST['broken']);
						$straw = validate($_POST['straw']);
						$dust = validate($_POST['dust']);
						$other = validate($_POST['other']);


						if( !empty( $processing_stock_id ) && !empty( $processing_quantity ) ) {

							$available_stock_qty = stock_transaction_detail($processing_stock_id, 'stock_qty');

							if($processing_quantity > $available_stock_qty) {
								echo "<div class='alert alert-danger'>Processing Stock must be less than or equal to available stock</div>";
							} else {

								$parent_stock_id = stock_transaction_detail($processing_stock_id, 'stock_id');
								$active_status = stock_transaction_detail($processing_stock_id, 'active_status');

								if($processing_quantity < $available_stock_qty) {
									$new_stock_qty = $available_stock_qty - $processing_quantity;
									$update_stock_query = mysqli_query($conn, "UPDATE stock_transactions SET stock_qty='$new_stock_qty' WHERE id='$processing_stock_id'");

#================================================================================================================
# if stock is already exist in this status then merge it other wise create new stock
									$check_exist_stock_query = mysqli_query($conn, "SELECT * FROM stock_transactions WHERE stock_id='$parent_stock_id' && stock_status='2' && active_status='1' && delete_status='0'");
									if(mysqli_num_rows($check_exist_stock_query) > 0) {
										$check_exist_stock_result = mysqli_fetch_assoc($check_exist_stock_query);
										$exist_stock_id = $check_exist_stock_result['id'];
										$new_exist_stock_qty = $check_exist_stock_result['stock_qty'] + $grade_1;
										$cleaned_stock_query = mysqli_query($conn, "UPDATE stock_transactions SET stock_qty='$new_exist_stock_qty', active_status='1' WHERE id='$exist_stock_id'");
										$new_stock_id = $exist_stock_id;
										// echo 'Processing stock is less than actual stock and stock already exists so we merge it';
									} else {
										$cleaned_stock_query = mysqli_query($conn, "INSERT INTO stock_transactions(user_id, circle_id, stock_id, stock_qty, stock_status, active_status, time_created) VALUES('$user_id', '$circle_id', '$parent_stock_id', '$grade_1', '2', '1', '$time_created')");
										$new_stock_id = mysqli_insert_id($conn);
										// echo 'Processing stock is less than actual stock and stock not exists so we create new stock';
									}
#================================================================================================================


								} else { // if processing stock quantity is equal to available stock quantity

#================================================================================================================
# if stock is already exist in this status then merge it other wise create new stock

									$check_exist_stock_query = mysqli_query($conn, "SELECT * FROM stock_transactions WHERE stock_id='$parent_stock_id' && stock_status='2' && active_status='1' && delete_status='0'");
									if(mysqli_num_rows($check_exist_stock_query) > 0) {
										$check_exist_stock_result = mysqli_fetch_assoc($check_exist_stock_query);
										$exist_stock_id = $check_exist_stock_result['id'];
										$new_exist_stock_qty = $check_exist_stock_result['stock_qty'] + $grade_1;
										$cleaned_stock_query = mysqli_query($conn, "UPDATE stock_transactions SET stock_qty='$new_exist_stock_qty', active_status='1' WHERE id='$exist_stock_id'");
										$new_stock_id = $exist_stock_id;
										$del_processing_stock = mysqli_query($conn, "UPDATE stock_transactions SET stock_qty='0', delete_status='2' WHERE id='$processing_stock_id'");
										// echo 'Procesing stock is equal to actual stock and stock already exists so we merge it';
									} else {
										$cleaned_stock_query = mysqli_query($conn, "UPDATE stock_transactions SET stock_qty='$grade_1', stock_status='2', active_status='1' WHERE id='$processing_stock_id'");
										$new_stock_id = $processing_stock_id;
										// echo 'Processing stock is equal to actual stock and stock not exists so we create new stock';
									}

#================================================================================================================


								}

								$fscrd_report_query = mysqli_query($conn, "INSERT INTO fscrd_report(user_id, circle_id, stock_id, report_type, time_created) VALUES('$user_id', '$circle_id', '$new_stock_id', '3', '$time_created')");

								$query = mysqli_query($conn, "INSERT INTO stock_cleaning(user_id, circle_id, parent_id, stock_id, processing_qty, time_created) VALUES('$user_id', '$circle_id', '$parent_stock_id', '$new_stock_id', '$processing_quantity', '$time_created')");
								$stock_cleaning_id = mysqli_insert_id($conn);
								$stock_cleaning_meta_query = mysqli_query($conn, "INSERT INTO stock_cleaning_meta(stock_cleaning_id, meta_key, meta_value) VALUES
									('$stock_cleaning_id', 'total_stock_qty', '$available_stock_qty'), 
									('$stock_cleaning_id', 'processing_qty', '$processing_quantity'), 
									('$stock_cleaning_id', 'grade_1', '$grade_1'), 
									('$stock_cleaning_id', 'small_grains', '$small_grains'), 
									('$stock_cleaning_id', 'gundi', '$gundi'), 
									('$stock_cleaning_id', 'broken', '$broken'), 
									('$stock_cleaning_id', 'straw', '$straw'), 
									('$stock_cleaning_id', 'dust', '$dust'), 
									('$stock_cleaning_id', 'other', '$other') 
								");

								if($query && $stock_cleaning_meta_query && $cleaned_stock_query) {
									echo "<div class='alert alert-success'>Stock is Successfully Cleaned</div>";
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

					<div id="stock_cleaning_form_msg"></div>
					
					<form class="form" method="post" id="stock_cleaning_form" enctype="multipart/form-data">

						<h5>Cleaning Input</h5>

						<?php

						if(isset($id) && !empty( $id ) && $id != 0) {
							echo "<input type='hidden' value='".$id."' name='stock_id'>";
							echo "<input type='hidden' value='".$result['stock_qty']."' name='available_stock_qty' id='available_stock_qty'>";
						} else {
						?>

						<div class="row">
							<div class="col-md-12 mb-3">
								<label>Select Stock:</label>
								<select class="form-control" id="stock_id" name="stock_id">
									<option value="">Select Stock</option>
									<?php

									$supply_stock_query = mysqli_query($conn, "SELECT st.id, s.circle_id, s.crop, s.lot_number, s.variety, s.class, st.stock_qty, st.stock_status, st.active_status FROM stocks AS s INNER JOIN stock_transactions AS st ON s.id=st.stock_id WHERE st.active_status!='0' && (s.delete_status='0' && st.delete_status='0') ORDER BY st.time_created DESC");

									if(mysqli_num_rows($supply_stock_query) > 0) {
										while($supply_stock_result = mysqli_fetch_assoc($supply_stock_query)) {
											$stock_transaction_id = $supply_stock_result['id'];
											if((mysqli_num_rows(mysqli_query($conn, "SELECT * FROM fscrd_report WHERE stock_id='$stock_id' && report_type='2' && report_status='1'")) == 1) && (mysqli_num_rows(mysqli_query($conn, "SELECT * FROM fscrd_report WHERE stock_id='$stock_transaction_id' && report_type='3' && report_status='1'")) == 0)) {
												if($supply_stock_result['id'] == $processing_stock_id) {
													$supply_stock_selected = 'selected';
												} else {
													$supply_stock_selected = '';
												}
												if($supply_stock_result['active_status'] == 3 && $supply_stock_result['stock_status'] == 0) {
													$supply_stock_class = 'text-primary';
													$supply_stock_status = 'Uncleaned, Under Fumigation';
												} else if($supply_stock_result['active_status'] == 3 && $supply_stock_result['stock_status'] == 2) {
													$supply_stock_class = 'text-success';
													$supply_stock_status = 'Cleaned, Under Fumigation';
												} else if($supply_stock_result['stock_status'] == 0) {
													$supply_stock_class = 'text-danger';
													$supply_stock_status = 'Uncleaned';
												} else if($supply_stock_result['stock_status'] == 2) {
													$supply_stock_class = 'text-success';
													$supply_stock_status = 'Cleaned';
												}
											?>
												<option <?= $supply_stock_selected; ?> class="<?= $supply_stock_class; ?>" data-qty="<?= $supply_stock_result['stock_qty']; ?>" value="<?= $supply_stock_result['id']; ?>"><?=

													$supply_stock_result['lot_number'].' - '.stock_crop($supply_stock_result['crop']).' - '.stock_variety($supply_stock_result['variety']).' - '.stock_class($supply_stock_result['class']).' - '.$supply_stock_result['stock_qty'].' (KGs) - '.$supply_stock_status;

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
								<label>Processing Quantity:</label>
								<input type="text" pattern="[0-9]+" value="<?php if(isset($result['stock_qty']) && !empty( $processing_quantity ) ) { echo $processing_quantity; } else if(isset($result['stock_qty']) && $processing_quantity == '') { echo $result['stock_qty']; } else { $processing_quantity; } ?>" class="form-control" placeholder="Enter Process Quantity" id="processing_quantity" name="processing_quantity">
								<div id="processing_quantity_msg"></div>
							</div>
						</div>

						<h5>Cleaning Outcomes</h5>
						
						<div class="row">
							<div class="col-md-12 mb-3">
								<label>Grade 1:</label>
								<input type="text" pattern="[0-9]+" value="<?= $grade_1; ?>" class="form-control" placeholder="Enter Grade 1" id="grade_1" name="grade_1">
								<small class="form-text text-muted">Enter Grade 1 in (KGs)</small>
								<div id="grade_1_msg"></div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 mb-3">
								<label>Small Grains:</label>
								<input type="text" pattern="[0-9]+" value="<?= $small_grains; ?>" class="form-control" placeholder="Enter Small Grains" id="small_grains" name="small_grains">
								<small class="form-text text-muted">Enter Small Grains in (KGs)</small>
								<div id="small_grains_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Gundi:</label>
								<input type="text" pattern="[0-9]+" value="<?= $gundi; ?>" class="form-control" placeholder="Enter Gundi" id="gundi" name="gundi">
								<small class="form-text text-muted">Enter Gundi in (KGs)</small>
								<div id="gundi_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Broken:</label>
								<input type="text" pattern="[0-9]+" value="<?= $broken; ?>" class="form-control" placeholder="Enter Broken" id="broken" name="broken">
								<small class="form-text text-muted">Enter Broken in (KGs)</small>
								<div id="broken_msg"></div>
							</div>
						</div>

						<div class="row">
							<div class="col-md-4 mb-3">
								<label>Straw: (Bhoosa)</label>
								<input type="text" pattern="[0-9]+" value="<?= $straw; ?>" class="form-control" placeholder="Enter Straw (Bhoosa)" id="straw" name="straw">
								<small class="form-text text-muted">Enter Straw in (KGs)</small>
								<div id="straw_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Dust:</label>
								<input type="text" pattern="[0-9]+" value="<?= $dust; ?>" class="form-control" placeholder="Enter Dust" id="dust" name="dust">
								<small class="form-text text-muted">Enter Dust in (KGs)</small>
								<div id="dust_msg"></div>
							</div>
							<div class="col-md-4 mb-3">
								<label>Other:</label>
								<input type="text" pattern="[0-9]+" value="<?= $other; ?>" class="form-control" placeholder="Enter Other Output" id="other" name="other">
								<small class="form-text text-muted">Enter Other Output in (KGs)</small>
								<div id="other_msg"></div>
							</div>
						</div>

						<button type="submit" class="btn btn-primary" id="stock_cleaning_form_btn" name="stock_cleaning_form_btn">Submit</button>

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

			$('#processing_quantity').on('focus', function(){
				$('#processing_quantity').removeClass('is-invalid');
				$('#processing_quantity_msg').removeClass('invalid-feedback').text('');
			});

			$('#grade_1').on('focus', function(){
				$('#grade_1').removeClass('is-invalid');
				$('#grade_1_msg').removeClass('invalid-feedback').text('');
			});

			$('#small_grains').on('focus', function(){
				$('#small_grains').removeClass('is-invalid');
				$('#small_grains_msg').removeClass('invalid-feedback').text('');
			});

			$('#gundi').on('focus', function(){
				$('#gundi').removeClass('is-invalid');
				$('#gundi_msg').removeClass('invalid-feedback').text('');
			});

			$('#broken').on('focus', function(){
				$('#broken').removeClass('is-invalid');
				$('#broken_msg').removeClass('invalid-feedback').text('');
			});

			$('#straw').on('focus', function(){
				$('#straw').removeClass('is-invalid');
				$('#straw_msg').removeClass('invalid-feedback').text('');
			});

			$('#dust').on('focus', function(){
				$('#dust').removeClass('is-invalid');
				$('#dust_msg').removeClass('invalid-feedback').text('');
			});

			$('#processing_quantity, #grade_1, #small_grains, #gundi, #broken, #straw, #dust, #other').on('blur', function(){

				if($('#processing_quantity').val() == '') {
					var processing_quantity = 0;
				} else {
					var processing_quantity = $('#processing_quantity').val();
				}
				if($('#grade_1').val() == '') {
					grade_1 = 0;
				} else {
					var grade_1 = $('#grade_1').val();
				}
				if($('#small_grains').val() == '') {
					var small_grains = 0;
				} else {
					var small_grains = $('#small_grains').val();
				}
				if($('#gundi').val() == '') {
					var gundi = 0;
				} else {
					var gundi = $('#gundi').val();
				}
				if($('#broken').val() == '') {
					var broken = 0;
				} else {
					var broken = $('#broken').val();
				}
				if($('#straw').val() == '') {
					straw = 0;
				} else {
					var straw = $('#straw').val();
				}
				if($('#dust').val() == '') {
					var dust = 0;
				} else {
					var dust = $('#dust').val();
				}
				if($('#other').val() == '') {
					var other = 0;
				} else {
					var other = $('#other').val();
				}
				var total_quantity = parseFloat(grade_1) + parseFloat(small_grains) + parseFloat(gundi) + parseFloat(broken) + parseFloat(straw) + parseFloat(dust) + parseFloat(other);
				console.log('Processing Quantity: ' + processing_quantity + ' Total Quantity: ' + total_quantity);

				if(total_quantity != processing_quantity) {
					$('#stock_cleaning_form_msg').html("<div class='alert alert-danger'>Sum of <strong>Grade 1 Stock</strong>, <strong>Small Grains</strong>, <strong>Gundi</strong>, <strong>Broken</strong>, <strong>Straw</strong>, <strong>Dust</strong>, <strong>Other</strong> must be equal to <strong>Processing Quantity</strong>");
				} else {
					$('#stock_cleaning_form_msg').html("");
				}

			});

			$('#stock_cleaning_form').on('submit', function(){
				var bool = 0;
				if($('#processing_quantity').val() == '') {
					var processing_quantity = 0;
				} else {
					var processing_quantity = $('#processing_quantity').val();
				}
				if($('#grade_1').val() == '') {
					grade_1 = 0;
				} else {
					var grade_1 = $('#grade_1').val();
				}
				if($('#small_grains').val() == '') {
					var small_grains = 0;
				} else {
					var small_grains = $('#small_grains').val();
				}
				if($('#gundi').val() == '') {
					var gundi = 0;
				} else {
					var gundi = $('#gundi').val();
				}
				if($('#broken').val() == '') {
					var broken = 0;
				} else {
					var broken = $('#broken').val();
				}
				if($('#straw').val() == '') {
					straw = 0;
				} else {
					var straw = $('#straw').val();
				}
				if($('#dust').val() == '') {
					var dust = 0;
				} else {
					var dust = $('#dust').val();
				}
				if($('#other').val() == '') {
					var other = 0;
				} else {
					var other = $('#other').val();
				}
				var total_quantity = parseFloat(grade_1) + parseFloat(small_grains) + parseFloat(gundi) + parseFloat(broken) + parseFloat(straw) + parseFloat(dust) + parseFloat(other);

				if($('#processing_quantity').val() == '') {
					$('#processing_quantity').addClass('is-invalid');
					$('#processing_quantity_msg').addClass('invalid-feedback').text('Processing Quantity Required');
					bool = 1;
				} else {
					<?php
					if(isset($id)) {
					?>
					if(parseInt($('#processing_quantity').val()) > <?= $result['stock_qty']; ?>) {
						$('#processing_quantity').addClass('is-invalid');
						$('#processing_quantity_msg').addClass('invalid-feedback').text('Processing Quantity must be less than or equal to actual stock');
						bool = 1;
					} else {
						$('#processing_quantity').removeClass('is-invalid');
						$('#processing_quantity_msg').removeClass('invalid-feedback').text('');
					}
					<?php
					} else {
					?>
					if(parseInt($('#processing_quantity').val()) > parseInt($('#available_stock_qty').val())) {
						$('#processing_quantity').addClass('is-invalid');
						$('#processing_quantity_msg').addClass('invalid-feedback').text('Processing Quantity must be less than or equal to actual stock');
						bool = 1;
					} else {
						$('#processing_quantity').removeClass('is-invalid');
						$('#processing_quantity_msg').removeClass('invalid-feedback').text('');
					}
					<?php
					}
					?>
				}

				if($('#grade_1').val() == '') {
					$('#grade_1').addClass('is-invalid');
					$('#grade_1_msg').addClass('invalid-feedback').text('Grade 1 Required');
					bool = 1;
				} else {
					$('#grade_1').removeClass('is-invalid');
					$('#grade_1_msg').removeClass('invalid-feedback').text('');
				}

				if(total_quantity != processing_quantity) {
					$('#stock_cleaning_form_msg').html("<div class='alert alert-danger'>Sum of <strong>Grade 1 Stock</strong>, <strong>Small Grains</strong>, <strong>Gundi</strong>, <strong>Broken</strong>, <strong>Straw</strong>, <strong>Dust</strong>, <strong>Other</strong> must be equal to <strong>Processing Quantity</strong>");
					bool = 1;
				}

				if(bool == 0) {
					return true;
				} else {
					return false;
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

			$('#stock_id').on('change', function(){
				var stock_id = $('#stock_id').val();
				var stock_qty = $('#stock_id option:selected').data('qty');

				if(stock_id != '') {
					$('#available_stock_qty').val(stock_qty);
				} else {
					$('#available_stock_qty').val('0');
				}
			});

		});
	</script>
</body>
</html>